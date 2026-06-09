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
    public function initializeRepo($projectPath, $buildId, $iconPath = null) {
        // Create .github/workflows directory
        $workflowDir = $projectPath . '/.github/workflows';
        if (!is_dir($workflowDir)) {
            mkdir($workflowDir, 0755, true);
        }
        
        // Create GitHub Actions workflow
        $workflow = $this->generateWorkflow($buildId, $iconPath);
        file_put_contents($workflowDir . '/build.yml', $workflow);
        
        // Create .gitignore
        $gitignore = "# Build outputs\n*.apk\n*.aab\n/build\n/app/build\n.gradle\nlocal.properties\n\n# IDE\n.idea\n*.iml\n.vscode\n\n# OS\n.DS_Store\nThumbs.db\n";
        file_put_contents($projectPath . '/.gitignore', $gitignore);
        
        return true;
    }
    
    /**
     * Generate GitHub Actions workflow file
     */
    private function generateWorkflow($buildId, $iconPath = null) {
        return <<<'YAML'
name: Build APK

on:
  push:
    branches: [ main ]
  workflow_dispatch:

permissions:
  contents: write

env:
  GRADLE_OPTS: -Dorg.gradle.daemon=false -Dorg.gradle.jvmargs="-Xmx3g -Dfile.encoding=UTF-8"

jobs:
  build:
    runs-on: ubuntu-latest
    timeout-minutes: 35

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Verify Android project files
        shell: bash
        run: |
          set -euo pipefail
          test -f settings.gradle || { echo "settings.gradle missing"; exit 1; }
          test -f build.gradle || { echo "project build.gradle missing"; exit 1; }
          test -f app/build.gradle || { echo "app/build.gradle missing"; exit 1; }
          test -f app/src/main/AndroidManifest.xml || { echo "AndroidManifest.xml missing"; exit 1; }
          mkdir -p app/src/main/res/drawable
          if ! ls app/src/main/res/drawable/app_icon.* >/dev/null 2>&1; then
            cat > app/src/main/res/drawable/app_icon.xml <<'EOF'
          <?xml version="1.0" encoding="utf-8"?>
          <vector xmlns:android="http://schemas.android.com/apk/res/android"
              android:width="108dp"
              android:height="108dp"
              android:viewportWidth="108"
              android:viewportHeight="108">
              <path android:fillColor="#4f46e5" android:pathData="M0,0h108v108h-108z"/>
              <path android:fillColor="#ffffff" android:pathData="M24,24h60v60h-60z"/>
              <path android:fillColor="#4f46e5" android:pathData="M32,32h44v44h-44z"/>
              <path android:fillColor="#ffffff" android:pathData="M49,36h10v36h-10zM36,49h36v10h-36z"/>
          </vector>
          EOF
          fi

      - name: Set up JDK 17
        uses: actions/setup-java@v4
        with:
          java-version: '17'
          distribution: 'temurin'

      - name: Setup Android SDK
        uses: android-actions/setup-android@v3

      - name: Install required Android SDK packages
        shell: bash
        run: |
          set -euo pipefail
          yes | sdkmanager --licenses >/dev/null || true
          sdkmanager "platform-tools" "platforms;android-35" "build-tools;35.0.0"

      - name: Setup Gradle
        uses: gradle/actions/setup-gradle@v4
        with:
          gradle-version: '8.11.1'

      - name: Build Debug APK
        shell: bash
        run: |
          set -euo pipefail
          gradle :app:assembleDebug --stacktrace --no-daemon
          mkdir -p dist
          APK_PATH="$(find app/build/outputs/apk/debug -type f -name '*.apk' | head -n 1)"
          test -n "$APK_PATH" || { echo "Debug APK was not produced"; exit 1; }
          cp "$APK_PATH" "dist/${{ github.event.repository.name }}-debug.apk"

      - name: Build Release AAB (optional)
        shell: bash
        continue-on-error: true
        run: |
          gradle :app:bundleRelease --stacktrace --no-daemon
          mkdir -p dist
          AAB_PATH="$(find app/build/outputs/bundle/release -type f -name '*.aab' | head -n 1 || true)"
          if [ -n "$AAB_PATH" ]; then
            cp "$AAB_PATH" "dist/${{ github.event.repository.name }}-release.aab"
          fi

      - name: Upload Debug APK
        uses: actions/upload-artifact@v4
        with:
          name: debug-apk
          path: dist/*-debug.apk
          if-no-files-found: error
          retention-days: 30

      - name: Upload Release AAB
        uses: actions/upload-artifact@v4
        continue-on-error: true
        with:
          name: release-aab
          path: dist/*-release.aab
          if-no-files-found: warn
          retention-days: 30

      - name: Create Release
        uses: softprops/action-gh-release@v2
        with:
          tag_name: build-${{ github.run_number }}
          name: "Build #${{ github.run_number }}"
          make_latest: true
          files: dist/*
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
YAML;
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
        // Retry up to 3 times — GitHub occasionally needs a moment after creation
        $parentSha = null;
        for ($attempt = 0; $attempt < 3; $attempt++) {
            if ($attempt > 0) usleep(600000); // 0.6s between retries
            $headRef = $this->apiRequest(
                "https://api.github.com/repos/{$this->owner}/{$repoName}/git/ref/heads/main",
                'GET'
            );
            if (isset($headRef['object']['sha'])) {
                $parentSha = $headRef['object']['sha'];
                break;
            }
        }
        if (!$parentSha) {
            return ['success' => false, 'output' => 'Could not get HEAD ref after 3 attempts: ' . json_encode($headRef)];
        }

        // Step 2: Collect files and create a blob for each one
        $files = $this->collectFiles($projectPath);
        $treeItems = [];

        foreach ($files as $absolutePath) {
            $relativePath = ltrim(str_replace($projectPath, '', $absolutePath), '/\\');
            $relativePath = str_replace('\\', '/', $relativePath);

            // Skip unwanted files
            $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
            if (in_array($ext, ['apk', 'aab', 'zip', 'sqlite', 'sqlite-shm', 'sqlite-wal'])) continue;
            // Keep generated resources such as custom app icons; only skip unusually large files.
            if (@filesize($absolutePath) > 5 * 1024 * 1024) continue; // skip files >5MB

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
