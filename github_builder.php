<?php
/**
 * GitHub Actions APK Builder
 * Uses GitHub Actions as a FREE build server for APK compilation
 * 
 * NOTE: This requires shell_exec() which is disabled on shared hosting.
 * For shared hosting, use the ZIP download option instead.
 */

class GitHubAPKBuilder {
    private $token;
    private $owner;
    private $repo;
    
    public function __construct($token, $owner, $repo = '') {
        $this->token = $token;
        $this->owner = $owner;
        $this->repo = $repo;
    }
    
    /**
     * Check if shell commands are available
     */
    public static function isAvailable() {
        if (!function_exists('shell_exec')) {
            return false;
        }
        $disabled = explode(',', ini_get('disable_functions'));
        $disabled = array_map('trim', $disabled);
        return !in_array('shell_exec', $disabled);
    }
    
    /**
     * Initialize repository with Android project
     */
    public function initializeRepo($projectPath, $buildId) {
        // Create .github/workflows directory
        $workflowDir = $projectPath . '/.github/workflows';
        if (!is_dir($workflowDir)) {
            mkdir($workflowDir, 0755, true);
        }
        
        // Create GitHub Actions workflow
        $workflow = $this->generateWorkflow($buildId);
        file_put_contents($workflowDir . '/build.yml', $workflow);
        
        // Create .gitignore
        $gitignore = "# Build outputs\n*.apk\n*.aab\n/build\n/app/build\n.gradle\nlocal.properties\n\n# IDE\n.idea\n*.iml\n.vscode\n\n# OS\n.DS_Store\nThumbs.db\n";
        file_put_contents($projectPath . '/.gitignore', $gitignore);
        
        return true;
    }
    
    /**
     * Generate GitHub Actions workflow file
     */
    private function generateWorkflow($buildId) {
        $yaml = "name: Build APK\n\n";
        $yaml .= "on:\n";
        $yaml .= "  push:\n";
        $yaml .= "    branches: [ main ]\n";
        $yaml .= "  workflow_dispatch:\n\n";
        $yaml .= "permissions:\n";
        $yaml .= "  contents: write\n\n";
        $yaml .= "jobs:\n";
        $yaml .= "  build:\n";
        $yaml .= "    runs-on: ubuntu-latest\n\n";
        $yaml .= "    steps:\n";
        $yaml .= "    - name: Checkout code\n";
        $yaml .= "      uses: actions/checkout@v4\n\n";
        $yaml .= "    - name: Set up JDK 17\n";
        $yaml .= "      uses: actions/setup-java@v4\n";
        $yaml .= "      with:\n";
        $yaml .= "        java-version: '17'\n";
        $yaml .= "        distribution: 'temurin'\n\n";
        $yaml .= "    - name: Setup Android SDK\n";
        $yaml .= "      uses: android-actions/setup-android@v3\n\n";
        $yaml .= "    - name: Setup Gradle\n";
        $yaml .= "      uses: gradle/actions/setup-gradle@v4\n";
        $yaml .= "      with:\n";
        $yaml .= "        gradle-version: '8.11.1'\n\n";
        $yaml .= "    - name: Build Debug APK\n";
        $yaml .= "      run: gradle assembleDebug --no-daemon\n\n";
        $yaml .= "    - name: Build Release AAB (for Play Store)\n";
        $yaml .= "      run: gradle bundleRelease --no-daemon\n";
        $yaml .= "      continue-on-error: true\n\n";
        $yaml .= "    - name: Upload Debug APK\n";
        $yaml .= "      uses: actions/upload-artifact@v4\n";
        $yaml .= "      with:\n";
        $yaml .= "        name: debug-apk\n";
        $yaml .= "        path: app/build/outputs/apk/debug/*.apk\n\n";
        $yaml .= "    - name: Upload Release AAB\n";
        $yaml .= "      uses: actions/upload-artifact@v4\n";
        $yaml .= "      if: success()\n";
        $yaml .= "      continue-on-error: true\n";
        $yaml .= "      with:\n";
        $yaml .= "        name: release-aab\n";
        $yaml .= "        path: app/build/outputs/bundle/release/*.aab\n\n";
        $yaml .= "    - name: Create Release\n";
        $yaml .= "      uses: softprops/action-gh-release@v2\n";
        $yaml .= "      if: success()\n";
        $yaml .= "      with:\n";
        $yaml .= "        tag_name: build-\${{ github.run_number }}\n";
        $yaml .= "        name: Build #\${{ github.run_number }}\n";
        $yaml .= "        files: |\n";
        $yaml .= "          app/build/outputs/apk/debug/*.apk\n";
        $yaml .= "          app/build/outputs/bundle/release/*.aab\n";
        $yaml .= "      env:\n";
        $yaml .= "        GITHUB_TOKEN: \${{ secrets.GITHUB_TOKEN }}\n";
        
        return $yaml;
    }
    
    /**
     * Create a new GitHub repository
     */
    public function createRepository($name, $description = '') {
        $url = 'https://api.github.com/user/repos';
        
        $data = [
            'name' => $name,
            'description' => $description,
            'private' => false,
            'auto_init' => false
        ];
        
        return $this->apiRequest($url, 'POST', $data);
    }
    
    /**
     * Push project to GitHub
     * Note: Requires shell_exec() which may be disabled on shared hosting
     */
    public function pushProject($projectPath, $repoName) {
        // Check if shell_exec is available
        if (!self::isAvailable()) {
            return [
                'success' => false,
                'output' => 'shell_exec is disabled on this server'
            ];
        }
        
        // Set git config for this repo
        $configEmail = 'git config user.email "webtoappk@builder.com"';
        $configName = 'git config user.name "WebToAPK Builder"';
        $configSafe = 'git config --global --add safe.directory ' . escapeshellarg($projectPath);
        
        // Detect OS
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        // Set HOME environment variable for www-data user (fixes "fatal: $HOME not set" error)
        $homeDir = '/tmp';
        if (!$isWindows) {
            putenv("HOME=$homeDir");
            $_ENV['HOME'] = $homeDir;
        }
        
        if ($isWindows) {
            $commands = [
                'cd /d "' . $projectPath . '"',
                $configSafe,
                'git init',
                $configEmail,
                $configName,
                'git add .',
                'git commit -m "Initial commit"',
                'git branch -M main',
                'git remote add origin https://' . $this->token . '@github.com/' . $this->owner . '/' . $repoName . '.git',
                'git push -u origin main'
            ];
            $command = implode(' && ', $commands);
        } else {
            $commands = [
                'export HOME=' . escapeshellarg($homeDir),
                'cd ' . escapeshellarg($projectPath),
                $configSafe,
                'git init',
                $configEmail,
                $configName,
                'git add .',
                'git commit -m "Initial commit"',
                'git branch -M main',
                'git remote add origin https://' . $this->token . '@github.com/' . $this->owner . '/' . $repoName . '.git',
                'git push -u origin main 2>&1'
            ];
            $command = implode(' && ', $commands);
        }
        
        $output = @shell_exec($command . ' 2>&1');
        
        // Check for success indicators
        $success = ($output && strpos($output, 'main') !== false && strpos($output, '->') !== false) 
                   || ($output && strpos($output, 'Writing objects') !== false)
                   || ($output && strpos($output, 'branch') !== false);
        
        return [
            'success' => $success,
            'output' => $output ?: 'No output'
        ];
    }
    
    /**
     * Trigger workflow run
     */
    public function triggerWorkflow($workflow = 'build.yml') {
        $url = "https://api.github.com/repos/{$this->owner}/{$this->repo}/actions/workflows/{$workflow}/dispatches";
        
        $data = ['ref' => 'main'];
        
        return $this->apiRequest($url, 'POST', $data);
    }
    
    /**
     * Check workflow status
     */
    public function getWorkflowStatus() {
        $url = "https://api.github.com/repos/{$this->owner}/{$this->repo}/actions/runs?per_page=1";
        
        $response = $this->apiRequest($url, 'GET');
        
        if (isset($response['workflow_runs'][0])) {
            return [
                'status' => $response['workflow_runs'][0]['status'],
                'conclusion' => $response['workflow_runs'][0]['conclusion'],
                'html_url' => $response['workflow_runs'][0]['html_url']
            ];
        }
        
        return null;
    }
    
    /**
     * Get download URL for built APK
     */
    public function getLatestRelease() {
        $url = "https://api.github.com/repos/{$this->owner}/{$this->repo}/releases/latest";
        
        $response = $this->apiRequest($url, 'GET');
        
        if (isset($response['assets'])) {
            $apks = [];
            foreach ($response['assets'] as $asset) {
                if (strpos($asset['name'], '.apk') !== false) {
                    $apks[] = [
                        'name' => $asset['name'],
                        'download_url' => $asset['browser_download_url'],
                        'size' => $asset['size']
                    ];
                }
            }
            return $apks;
        }
        
        return null;
    }
    
    /**
     * Make GitHub API request
     */
    private function apiRequest($url, $method = 'GET', $data = null) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                'Accept: application/vnd.github.v3+json',
                'User-Agent: WebToAPK-Builder',
                'Content-Type: application/json'
            ],
            CURLOPT_CUSTOMREQUEST => $method
        ]);
        
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
}
?>
