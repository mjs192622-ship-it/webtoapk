<?php
/**
 * APK Build Server - Self-Hosted Build System
 * Handles async APK compilation using Android SDK on VPS
 * 
 * Run setup_build_server.sh first to install dependencies.
 * 
 * NOTE: config.php and db.php must be loaded BEFORE including this file.
 */

class APKBuilder {
    private $androidSdkPath;
    private $javaHome;
    private $gradleHome;
    private $buildDir;
    private $outputDir;
    
    public function __construct() {
        $this->androidSdkPath = defined('ANDROID_SDK_PATH') ? ANDROID_SDK_PATH : '/opt/android-build/android-sdk';
        $this->javaHome = defined('JAVA_HOME') ? JAVA_HOME : '/usr/lib/jvm/java-17-openjdk-amd64';
        $this->gradleHome = defined('GRADLE_HOME') ? GRADLE_HOME : '/opt/android-build/gradle/gradle-8.11.1';
        $this->buildDir = defined('BUILD_DIR') ? BUILD_DIR : __DIR__ . '/builds/';
        $this->outputDir = defined('OUTPUT_DIR') ? OUTPUT_DIR : __DIR__ . '/output/';
        
        foreach ([$this->buildDir, $this->outputDir] as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }
    
    /**
     * Check if build environment is properly set up
     */
    public function checkEnvironment() {
        $javaPath = $this->javaHome . '/bin/java';
        $gradlePath = $this->gradleHome . '/bin/gradle';
        
        $checks = [
            'java' => file_exists($javaPath) || $this->commandExists('java'),
            'gradle' => file_exists($gradlePath) || $this->commandExists('gradle'),
            'android_sdk' => is_dir($this->androidSdkPath),
            'build_tools' => is_dir($this->androidSdkPath . '/build-tools'),
            'platforms' => is_dir($this->androidSdkPath . '/platforms'),
        ];
        
        $checks['ready'] = $checks['java'] && $checks['gradle'] && $checks['android_sdk'] && $checks['build_tools'];
        
        return $checks;
    }
    
    /**
     * Queue a build in the database
     */
    public function queueBuild($buildId, $projectDir, $appName) {
        $db = getDB();
        
        $logFile = $this->buildDir . $buildId . '_build.log';
        
        $stmt = $db->prepare("INSERT INTO build_queue (build_id, project_dir, app_name, status, log_file, created_at) VALUES (?, ?, ?, 'pending', ?, datetime('now'))");
        $stmt->execute([$buildId, $projectDir, $appName, $logFile]);
        
        return $db->lastInsertId();
    }
    
    /**
     * Start the background build worker for a specific build
     */
    public function startBuildWorker($buildId) {
        $workerScript = __DIR__ . '/build_worker.php';
        $logFile = $this->buildDir . $buildId . '_worker.log';
        
        // Launch build_worker.php as a background process
        $cmd = sprintf(
            'nohup php %s %s > %s 2>&1 &',
            escapeshellarg($workerScript),
            escapeshellarg($buildId),
            escapeshellarg($logFile)
        );
        
        exec($cmd);
        
        return true;
    }
    
    /**
     * Get build status from database
     */
    public function getBuildStatus($buildId) {
        $db = getDB();
        
        $stmt = $db->prepare("SELECT * FROM build_queue WHERE build_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$buildId]);
        $build = $stmt->fetch();
        
        if (!$build) {
            return null;
        }
        
        // Read last few lines of log for progress info
        $logTail = '';
        if ($build['log_file'] && file_exists($build['log_file'])) {
            $logTail = $this->tailFile($build['log_file'], 20);
        }
        
        // Determine progress percentage from status
        $progress = 0;
        switch ($build['status']) {
            case 'pending': $progress = 5; break;
            case 'building': $progress = $this->parseProgress($logTail); break;
            case 'completed': $progress = 100; break;
            case 'failed': $progress = $build['progress'] ?: 0; break;
        }
        
        return [
            'build_id' => $build['build_id'],
            'status' => $build['status'],
            'progress' => $progress,
            'apk_path' => $build['apk_path'],
            'error_message' => $build['error_message'],
            'log_tail' => $logTail,
            'started_at' => $build['started_at'],
            'completed_at' => $build['completed_at'],
            'created_at' => $build['created_at'],
        ];
    }
    
    /**
     * Get environment variables string for build commands
     */
    public function getEnvString() {
        $gradleBin = $this->gradleHome . '/bin';
        $sdkTools = $this->androidSdkPath . '/cmdline-tools/latest/bin';
        $platformTools = $this->androidSdkPath . '/platform-tools';
        $javaBin = $this->javaHome . '/bin';
        
        $path = implode(':', [$gradleBin, $javaBin, $sdkTools, $platformTools, '/usr/local/sbin', '/usr/local/bin', '/usr/sbin', '/usr/bin', '/sbin', '/bin']);
        
        return implode(' ', [
            'JAVA_HOME=' . escapeshellarg($this->javaHome),
            'ANDROID_HOME=' . escapeshellarg($this->androidSdkPath),
            'ANDROID_SDK_ROOT=' . escapeshellarg($this->androidSdkPath),
            'GRADLE_HOME=' . escapeshellarg($this->gradleHome),
            'GRADLE_USER_HOME=' . escapeshellarg($this->buildDir . '.gradle'),
            'PATH=' . escapeshellarg($path),
        ]);
    }
    
    /**
     * Parse Gradle output to estimate progress
     */
    private function parseProgress($logOutput) {
        if (empty($logOutput)) return 10;
        
        // Look for Gradle task progress indicators
        if (strpos($logOutput, 'BUILD SUCCESSFUL') !== false) return 95;
        if (strpos($logOutput, 'BUILD FAILED') !== false) return 0;
        if (strpos($logOutput, ':app:package') !== false) return 85;
        if (strpos($logOutput, ':app:merge') !== false) return 70;
        if (strpos($logOutput, ':app:process') !== false) return 55;
        if (strpos($logOutput, ':app:compile') !== false) return 45;
        if (strpos($logOutput, ':app:generate') !== false) return 35;
        if (strpos($logOutput, ':app:pre') !== false) return 25;
        if (strpos($logOutput, 'Download') !== false) return 20;
        if (strpos($logOutput, 'Configure') !== false) return 15;
        
        return 10;
    }
    
    /**
     * Read last N lines from a file
     */
    private function tailFile($file, $lines = 20) {
        if (!file_exists($file)) return '';
        
        $result = shell_exec("tail -n " . intval($lines) . " " . escapeshellarg($file) . " 2>/dev/null");
        return $result ?: '';
    }
    
    /**
     * Check if command exists on system
     */
    private function commandExists($command) {
        $result = shell_exec("which " . escapeshellarg($command) . " 2>/dev/null");
        return !empty(trim($result ?? ''));
    }
}
