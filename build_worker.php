#!/usr/bin/env php
<?php
/**
 * APK Build Worker - Background Process
 * 
 * This script is launched by build_server.php to compile APKs in the background.
 * Usage: php build_worker.php <build_id>
 */

// Ensure we're running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

if ($argc < 2) {
    die("Usage: php build_worker.php <build_id>\n");
}

$buildId = $argv[1];

// Validate build ID format
if (!preg_match('/^[a-zA-Z0-9_]+$/', $buildId)) {
    die("Invalid build ID.\n");
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Ensure migrations are run
runMigrations();

$db = getDB();

// Fetch the build from queue
$stmt = $db->prepare("SELECT * FROM build_queue WHERE build_id = ? AND status = 'pending' LIMIT 1");
$stmt->execute([$buildId]);
$build = $stmt->fetch();

if (!$build) {
    // Check if already building/completed
    $stmt2 = $db->prepare("SELECT status FROM build_queue WHERE build_id = ? ORDER BY id DESC LIMIT 1");
    $stmt2->execute([$buildId]);
    $existing = $stmt2->fetch();
    if ($existing) {
        die("Build {$buildId} is already in status: {$existing['status']}\n");
    }
    die("Build {$buildId} not found in queue.\n");
}

$projectDir = $build['project_dir'];
$logFile = $build['log_file'];

// Verify project directory exists
if (!is_dir($projectDir)) {
    updateBuildStatus($db, $buildId, 'failed', 0, null, "Project directory not found: {$projectDir}");
    die("Project directory not found.\n");
}

// Mark as building
updateBuildStatus($db, $buildId, 'building', 10, null, null);
writeLog($logFile, "=== APK Build Started ===");
writeLog($logFile, "Build ID: {$buildId}");
writeLog($logFile, "Project: {$projectDir}");
writeLog($logFile, "Time: " . date('Y-m-d H:i:s'));
writeLog($logFile, "");

// Set up environment
$androidSdk = defined('ANDROID_SDK_PATH') ? ANDROID_SDK_PATH : '/opt/android-build/android-sdk';
$javaHome = defined('JAVA_HOME') ? JAVA_HOME : '/usr/lib/jvm/java-17-openjdk-amd64';
$gradleHome = defined('GRADLE_HOME') ? GRADLE_HOME : '/opt/android-build/gradle/gradle-8.11.1';

$gradleBin = $gradleHome . '/bin/gradle';
$javaBin = $javaHome . '/bin';
$sdkTools = $androidSdk . '/cmdline-tools/latest/bin';
$platformTools = $androidSdk . '/platform-tools';
$gradleUserHome = (defined('BUILD_DIR') ? BUILD_DIR : __DIR__ . '/builds/') . '.gradle';

$envPath = implode(':', [$gradleHome . '/bin', $javaBin, $sdkTools, $platformTools, '/usr/local/sbin', '/usr/local/bin', '/usr/sbin', '/usr/bin', '/sbin', '/bin']);

// Verify environment
writeLog($logFile, "Checking build environment...");

if (!file_exists($gradleBin)) {
    $error = "Gradle not found at: {$gradleBin}";
    writeLog($logFile, "ERROR: {$error}");
    updateBuildStatus($db, $buildId, 'failed', 0, null, $error);
    die("{$error}\n");
}

if (!file_exists($javaHome . '/bin/java')) {
    $error = "Java not found at: {$javaHome}/bin/java";
    writeLog($logFile, "ERROR: {$error}");
    updateBuildStatus($db, $buildId, 'failed', 0, null, $error);
    die("{$error}\n");
}

if (!is_dir($androidSdk)) {
    $error = "Android SDK not found at: {$androidSdk}";
    writeLog($logFile, "ERROR: {$error}");
    updateBuildStatus($db, $buildId, 'failed', 0, null, $error);
    die("{$error}\n");
}

writeLog($logFile, "Environment OK");
writeLog($logFile, "  Java: {$javaHome}");
writeLog($logFile, "  Gradle: {$gradleHome}");
writeLog($logFile, "  Android SDK: {$androidSdk}");
writeLog($logFile, "");

// Create local.properties for Android SDK path
$localProps = "sdk.dir={$androidSdk}\n";
file_put_contents($projectDir . '/local.properties', $localProps);

// Make gradlew executable if it exists
$gradlewPath = $projectDir . '/gradlew';
if (file_exists($gradlewPath)) {
    chmod($gradlewPath, 0755);
}

// Update progress
updateBuildStatus($db, $buildId, 'building', 20, null, null);
writeLog($logFile, "Starting Gradle build...");
writeLog($logFile, "");

// Build the APK using Gradle
$envVars = implode(' ', [
    'JAVA_HOME=' . escapeshellarg($javaHome),
    'ANDROID_HOME=' . escapeshellarg($androidSdk),
    'ANDROID_SDK_ROOT=' . escapeshellarg($androidSdk),
    'GRADLE_HOME=' . escapeshellarg($gradleHome),
    'GRADLE_USER_HOME=' . escapeshellarg($gradleUserHome),
    'PATH=' . escapeshellarg($envPath),
]);

// Use project's gradlew if available, otherwise system gradle
$gradleCmd = file_exists($gradlewPath) ? './gradlew' : escapeshellarg($gradleBin);

$buildCommand = sprintf(
    'cd %s && %s %s assembleDebug --no-daemon --stacktrace 2>&1',
    escapeshellarg($projectDir),
    $envVars,
    $gradleCmd
);

writeLog($logFile, "Command: {$gradleCmd} assembleDebug --no-daemon --stacktrace");
writeLog($logFile, "---");

// Execute build with real-time log capture
$descriptors = [
    0 => ['pipe', 'r'],  // stdin
    1 => ['pipe', 'w'],  // stdout
    2 => ['pipe', 'w'],  // stderr
];

$env = [
    'JAVA_HOME' => $javaHome,
    'ANDROID_HOME' => $androidSdk,
    'ANDROID_SDK_ROOT' => $androidSdk,
    'GRADLE_HOME' => $gradleHome,
    'GRADLE_USER_HOME' => $gradleUserHome,
    'PATH' => $envPath,
    'HOME' => '/tmp',
    'USER' => 'www-data',
];

$process = proc_open($gradleCmd . ' assembleDebug --no-daemon --stacktrace 2>&1', 
    [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['redirect', 1]],
    $pipes,
    $projectDir,
    $env
);

if (!is_resource($process)) {
    $error = "Failed to start Gradle process";
    writeLog($logFile, "ERROR: {$error}");
    updateBuildStatus($db, $buildId, 'failed', 20, null, $error);
    die("{$error}\n");
}

fclose($pipes[0]); // Close stdin

// Read output line by line for progress tracking
$lastProgressUpdate = time();
$currentProgress = 25;

while (!feof($pipes[1])) {
    $line = fgets($pipes[1]);
    if ($line === false) break;
    
    $line = rtrim($line);
    file_put_contents($logFile, $line . "\n", FILE_APPEND);
    
    // Update progress based on Gradle output
    $newProgress = $currentProgress;
    if (strpos($line, 'Download') !== false || strpos($line, 'download') !== false) {
        $newProgress = max($currentProgress, 30);
    } elseif (strpos($line, 'Configure') !== false) {
        $newProgress = max($currentProgress, 35);
    } elseif (strpos($line, ':app:pre') !== false || strpos($line, ':app:check') !== false) {
        $newProgress = max($currentProgress, 40);
    } elseif (strpos($line, ':app:generate') !== false) {
        $newProgress = max($currentProgress, 45);
    } elseif (strpos($line, ':app:compile') !== false || strpos($line, ':app:javaCompile') !== false) {
        $newProgress = max($currentProgress, 55);
    } elseif (strpos($line, ':app:process') !== false) {
        $newProgress = max($currentProgress, 65);
    } elseif (strpos($line, ':app:merge') !== false) {
        $newProgress = max($currentProgress, 75);
    } elseif (strpos($line, ':app:package') !== false) {
        $newProgress = max($currentProgress, 85);
    } elseif (strpos($line, ':app:assemble') !== false) {
        $newProgress = max($currentProgress, 90);
    } elseif (strpos($line, 'BUILD SUCCESSFUL') !== false) {
        $newProgress = 95;
    }
    
    // Update DB every 5 seconds or on progress change
    if ($newProgress > $currentProgress || (time() - $lastProgressUpdate >= 5)) {
        $currentProgress = $newProgress;
        updateBuildStatus($db, $buildId, 'building', $currentProgress, null, null);
        $lastProgressUpdate = time();
    }
}

fclose($pipes[1]);
$exitCode = proc_close($process);

writeLog($logFile, "---");
writeLog($logFile, "Gradle exit code: {$exitCode}");
writeLog($logFile, "");

// Check for APK output
$apkPath = $projectDir . '/app/build/outputs/apk/debug/app-debug.apk';

if ($exitCode === 0 && file_exists($apkPath)) {
    writeLog($logFile, "BUILD SUCCESSFUL!");
    writeLog($logFile, "APK: {$apkPath}");
    writeLog($logFile, "Size: " . number_format(filesize($apkPath) / 1024 / 1024, 2) . " MB");
    
    // Copy APK to the output directory for download
    $outputBuildDir = OUTPUT_DIR . $buildId . '/';
    if (!is_dir($outputBuildDir)) {
        mkdir($outputBuildDir, 0755, true);
    }
    
    // Read app name from config
    $configPath = $outputBuildDir . 'config.json';
    $appName = 'app';
    if (file_exists($configPath)) {
        $config = json_decode(file_get_contents($configPath), true);
        $appName = $config['app_name'] ?? 'app';
    }
    
    $safeAppName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $appName);
    $finalApkPath = $outputBuildDir . $safeAppName . '.apk';
    
    copy($apkPath, $finalApkPath);
    chmod($finalApkPath, 0644);
    
    writeLog($logFile, "APK copied to: {$finalApkPath}");
    
    // Update build status
    updateBuildStatus($db, $buildId, 'completed', 100, $finalApkPath, null);
    
    // Update builds table too
    $stmt = $db->prepare("UPDATE builds SET status = 'completed', download_url = ? WHERE build_id = ?");
    $stmt->execute(['download_apk.php?id=' . $buildId, $buildId]);
    
    writeLog($logFile, "");
    writeLog($logFile, "=== Build Complete ===");
    
} else {
    // Build failed
    $error = "Gradle build failed with exit code: {$exitCode}";
    
    // Try to extract error from log
    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
        if (preg_match('/FAILURE:.*?$/ms', $logContent, $matches)) {
            $error = trim(substr($matches[0], 0, 500));
        }
    }
    
    writeLog($logFile, "BUILD FAILED!");
    writeLog($logFile, $error);
    
    updateBuildStatus($db, $buildId, 'failed', $currentProgress, null, $error);
    
    // Update builds table
    $stmt = $db->prepare("UPDATE builds SET status = 'failed' WHERE build_id = ?");
    $stmt->execute([$buildId]);
    
    writeLog($logFile, "");
    writeLog($logFile, "=== Build Failed ===");
}

// Clean up Gradle daemon
$cleanCmd = sprintf(
    'cd %s && JAVA_HOME=%s GRADLE_HOME=%s GRADLE_USER_HOME=%s %s --stop 2>/dev/null',
    escapeshellarg($projectDir),
    escapeshellarg($javaHome),
    escapeshellarg($gradleHome),
    escapeshellarg($gradleUserHome),
    $gradleCmd
);
exec($cleanCmd);

/**
 * Update build status in database
 */
function updateBuildStatus($db, $buildId, $status, $progress, $apkPath, $errorMessage) {
    $fields = ['status = ?', 'progress = ?'];
    $params = [$status, $progress];
    
    if ($status === 'building' && $progress <= 10) {
        $fields[] = "started_at = datetime('now')";
    }
    
    if ($status === 'completed' || $status === 'failed') {
        $fields[] = "completed_at = datetime('now')";
    }
    
    if ($apkPath !== null) {
        $fields[] = 'apk_path = ?';
        $params[] = $apkPath;
    }
    
    if ($errorMessage !== null) {
        $fields[] = 'error_message = ?';
        $params[] = $errorMessage;
    }
    
    $params[] = $buildId;
    
    $sql = "UPDATE build_queue SET " . implode(', ', $fields) . " WHERE build_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
}

/**
 * Write log message with timestamp
 */
function writeLog($logFile, $message) {
    $timestamp = date('H:i:s');
    file_put_contents($logFile, "[{$timestamp}] {$message}\n", FILE_APPEND);
}
