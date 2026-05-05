<?php
/**
 * GitHub Actions APK Builder
 * Uses GitHub API (no shell_exec needed) to push files and trigger Actions build
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
     * shell_exec availability check (kept for compatibility)
     */
    public static function isAvailable() {
        return true; // We use GitHub API now, no shell_exec needed
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
        $yaml .= "    - name: Generate placeholder launcher icons if missing\n";
        $yaml .= "      run: |\n";
        $yaml .= "        for dir in app/src/main/res/mipmap-mdpi app/src/main/res/mipmap-hdpi app/src/main/res/mipmap-xhdpi app/src/main/res/mipmap-xxhdpi app/src/main/res/mipmap-xxxhdpi; do\n";
        $yaml .= "          mkdir -p \$dir\n";
        $yaml .= "          if [ ! -f \"\$dir/ic_launcher.png\" ]; then\n";
        $yaml .= "            convert -size 192x192 xc:#6366f1 -fill white -font DejaVu-Sans -pointsize 80 -gravity center -annotate 0 'W' \"\$dir/ic_launcher.png\" 2>/dev/null || \\\n";
        $yaml .= "            python3 -c \"import struct,zlib; data=struct.pack('>IHHI',1,192,192,0); open('\$dir/ic_launcher.png','wb').write(b'\\x89PNG\\r\\n\\x1a\\n'+struct.pack('>I',13)+b'IHDR'+struct.pack('>IIBBBBB',192,192,8,2,0,0,0)+struct.pack('>I',zlib.crc32(b'IHDR'+struct.pack('>IIBBBBB',192,192,8,2,0,0,0))&0xffffffff)+b'\\x00'*100)\"\n";
        $yaml .= "            cp \"\$dir/ic_launcher.png\" \"\$dir/ic_launcher_round.png\" 2>/dev/null || true\n";
        $yaml .= "          fi\n";
        $yaml .= "        done\n\n";
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
            'auto_init' => true  // Initialize repo so git objects (blobs) can be created immediately
        ];
        
        return $this->apiRequest($url, 'POST', $data);
    }
    
    /**
     * Push project to GitHub using Git Tree API
     * Creates all files in ONE commit (3-4 API calls total, works within Render 30s timeout)
     */
    public function pushProject($projectPath, $repoName) {
        $this->repo = $repoName;

        // Step 1: Get HEAD ref SHA (auto_init created an initial commit on main)
        $headRef = $this->apiRequest(
            "https://api.github.com/repos/{$this->owner}/{$repoName}/git/ref/heads/main",
            'GET'
        );
        if (!isset($headRef['object']['sha'])) {
            return ['success' => false, 'output' => 'Could not get HEAD ref: ' . json_encode($headRef)];
        }
        $parentSha = $headRef['object']['sha'];

        // Step 2: Collect files and create a blob for each one
        $files = $this->collectFiles($projectPath);
        $treeItems = [];

        foreach ($files as $absolutePath) {
            $relativePath = ltrim(str_replace($projectPath, '', $absolutePath), '/\\');
            $relativePath = str_replace('\\', '/', $relativePath);

            // Skip unwanted files
            $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
            if (in_array($ext, ['apk', 'aab', 'zip', 'sqlite', 'sqlite-shm', 'sqlite-wal'])) continue;
            if ($ext === 'jar') continue; // gradle-wrapper.jar not needed (using system gradle in Actions)
            if (@filesize($absolutePath) > 900000) continue; // skip files >900KB

            // Skip mipmap PNGs — Actions workflow generates placeholder icons
            if ($ext === 'png' && strpos($relativePath, 'mipmap-') !== false) continue;

            $fileContent = file_get_contents($absolutePath);
            if ($fileContent === false) continue;

            // Create blob for every file using base64 (works for text AND binary)
            // This is safe because the repo is now auto_init'd (not empty)
            $blob = $this->apiRequest(
                "https://api.github.com/repos/{$this->owner}/{$repoName}/git/blobs",
                'POST',
                ['content' => base64_encode($fileContent), 'encoding' => 'base64']
            );
            if (!isset($blob['sha'])) {
                error_log("Blob failed for $relativePath: " . json_encode($blob));
                continue; // skip this file but continue with others
            }
            $treeItems[] = [
                'path' => $relativePath,
                'mode' => '100644',
                'type' => 'blob',
                'sha'  => $blob['sha']
            ];
        }

        if (empty($treeItems)) {
            return ['success' => false, 'output' => 'No files collected'];
        }

        // Step 3: Create tree with SHA references (tiny JSON payload — no inline file content)
        $tree = $this->apiRequest(
            "https://api.github.com/repos/{$this->owner}/{$repoName}/git/trees",
            'POST',
            ['tree' => $treeItems]
        );
        if (!isset($tree['sha'])) {
            return ['success' => false, 'output' => 'Tree creation failed: ' . json_encode($tree)];
        }

        // Step 4: Create commit on top of the auto_init commit
        $commit = $this->apiRequest(
            "https://api.github.com/repos/{$this->owner}/{$repoName}/git/commits",
            'POST',
            ['message' => 'Add Android project', 'tree' => $tree['sha'], 'parents' => [$parentSha]]
        );
        if (!isset($commit['sha'])) {
            return ['success' => false, 'output' => 'Commit creation failed: ' . json_encode($commit)];
        }

        // Step 5: PATCH (update) the existing main branch ref — don't create a new one
        $ref = $this->apiRequest(
            "https://api.github.com/repos/{$this->owner}/{$repoName}/git/refs/heads/main",
            'PATCH',
            ['sha' => $commit['sha'], 'force' => false]
        );

        $success = isset($ref['ref']);
        return [
            'success' => $success,
            'output' => $success
                ? 'Pushed ' . count($treeItems) . ' files via Tree API (blob-per-file)'
                : 'Ref update failed: ' . json_encode($ref)
        ];
    }

    /**
     * Recursively collect all file paths under a directory
     */
    private function collectFiles($dir) {
        $result = [];
        $items = @scandir($dir);
        if (!$items) return $result;
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            // Skip git/hidden dirs, sensitive files, and non-Android build artifacts
            if ($item === '.git' || $item === 'node_modules') continue;
            // Skip config.json (contains private keys/credentials) and server-config/ (firebase service account)
            if ($item === 'config.json' || $item === 'server-config') continue;
            $full = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($full)) {
                $result = array_merge($result, $this->collectFiles($full));
            } else {
                $result[] = $full;
            }
        }
        return $result;
    }

    /**
     * Trigger workflow run via workflow_dispatch
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
            CURLOPT_TIMEOUT => 20,
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
