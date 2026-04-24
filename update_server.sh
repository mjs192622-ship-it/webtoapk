#!/bin/bash
# WebToAPK Server Update Script
# Run this on your Google Cloud VM

echo "==================================="
echo "WebToAPK - Updating Server Files"
echo "==================================="

# Backup existing files
echo "📦 Creating backups..."
sudo cp /var/www/html/api.php /var/www/html/api.php.backup.$(date +%Y%m%d_%H%M%S)
sudo cp /var/www/html/index.php /var/www/html/index.php.backup.$(date +%Y%m%d_%H%M%S)

# Create temporary directory
mkdir -p /tmp/webtoapp_update

# Download updated api.php
echo "📥 Downloading updated api.php..."
cat > /tmp/webtoapp_update/api.php << 'APIEOF'
<?php
/**
 * API Endpoint for Website to APK Converter
 * Provides REST API access to the converter
 */

require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'validate':
        handleValidate();
        break;
        
    case 'analyze':
        handleAnalyze();
        break;
        
    case 'suggest':
        handleSuggest();
        break;
        
    case 'status':
        handleStatus();
        break;
    
    case 'github_status':
        handleGithubStatus();
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action. Available actions: validate, analyze, suggest, status, github_status'
        ]);
}

/**
 * Validate website URL
 */
function handleValidate() {
    $url = $_POST['url'] ?? '';
    
    if (empty($url)) {
        echo json_encode(['success' => false, 'message' => 'URL is required']);
        return;
    }
    
    if (!isValidUrl($url)) {
        echo json_encode(['success' => false, 'message' => 'Invalid URL format']);
        return;
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 400) {
        echo json_encode([
            'success' => true,
            'message' => 'URL is valid and accessible',
            'http_code' => $httpCode
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'URL is not accessible',
            'http_code' => $httpCode
        ]);
    }
}

/**
 * Analyze website using AI
 */
function handleAnalyze() {
    $url = $_POST['url'] ?? '';
    
    if (empty($url) || !isValidUrl($url)) {
        echo json_encode(['success' => false, 'message' => 'Valid URL is required']);
        return;
    }
    
    $prompt = "Analyze this website URL: $url
    
    Please provide:
    1. A suggested app name (short and catchy)
    2. A suitable package name (com.example.appname format)
    3. A brief description for the app
    4. Recommended screen orientation (portrait/landscape/both)
    5. Whether fullscreen mode is recommended
    
    Format your response as JSON with keys: app_name, package_name, description, orientation, fullscreen";
    
    $response = callGroqAPI($prompt);
    
    if ($response) {
        preg_match('/\{[^{}]*\}/', $response, $matches);
        if (!empty($matches)) {
            $data = json_decode($matches[0], true);
            if ($data) {
                echo json_encode([
                    'success' => true,
                    'data' => $data
                ]);
                return;
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => $response
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to analyze website'
        ]);
    }
}

/**
 * Get AI suggestions for app
 */
function handleSuggest() {
    $appName = $_POST['app_name'] ?? '';
    $type = $_POST['type'] ?? 'description';
    
    if (empty($appName)) {
        echo json_encode(['success' => false, 'message' => 'App name is required']);
        return;
    }
    
    $prompts = [
        'description' => "Generate a short, professional app description (2-3 sentences) for an Android app called '$appName'. Make it engaging and suitable for Google Play Store.",
        'features' => "List 5 key features for an Android app called '$appName'. Keep each feature to one short sentence.",
        'keywords' => "Suggest 10 relevant keywords/tags for an Android app called '$appName' for app store optimization. Return as comma-separated list."
    ];
    
    $prompt = $prompts[$type] ?? $prompts['description'];
    $response = callGroqAPI($prompt);
    
    if ($response) {
        echo json_encode([
            'success' => true,
            'suggestion' => trim($response)
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to generate suggestion'
        ]);
    }
}

/**
 * Check build status
 */
function handleStatus() {
    $buildId = $_POST['build_id'] ?? $_GET['build_id'] ?? '';
    
    if (empty($buildId)) {
        echo json_encode(['success' => false, 'message' => 'Build ID is required']);
        return;
    }
    
    $buildId = preg_replace('/[^a-zA-Z0-9_]/', '', $buildId);
    $zipPath = OUTPUT_DIR . $buildId . '.zip';
    $configPath = OUTPUT_DIR . $buildId . '/config.json';
    
    if (file_exists($zipPath)) {
        $config = [];
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
        }
        
        echo json_encode([
            'success' => true,
            'status' => 'completed',
            'download_url' => 'download.php?id=' . $buildId,
            'config' => $config
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'status' => 'not_found',
            'message' => 'Build not found or expired'
        ]);
    }
}

/**
 * Check GitHub Actions build status and get APK download URL
 */
function handleGithubStatus() {
    $buildId = $_POST['build_id'] ?? $_GET['build_id'] ?? '';
    
    if (empty($buildId)) {
        echo json_encode(['success' => false, 'message' => 'Build ID is required']);
        return;
    }
    
    $buildId = preg_replace('/[^a-zA-Z0-9_]/', '', $buildId);
    $configPath = OUTPUT_DIR . $buildId . '/config.json';
    
    if (!file_exists($configPath)) {
        echo json_encode(['success' => false, 'message' => 'Build not found']);
        return;
    }
    
    $config = json_decode(file_get_contents($configPath), true);
    
    if (empty($config['github_repo'])) {
        echo json_encode(['success' => false, 'message' => 'Not a GitHub build']);
        return;
    }
    
    $repoName = $config['github_repo'];
    $owner = GITHUB_OWNER;
    $token = GITHUB_TOKEN;
    
    $workflowUrl = "https://api.github.com/repos/{$owner}/{$repoName}/actions/runs?per_page=1";
    $workflowResponse = githubApiRequest($workflowUrl, $token);
    
    $workflowStatus = 'unknown';
    $workflowConclusion = null;
    $workflowHtmlUrl = null;
    
    if (isset($workflowResponse['workflow_runs'][0])) {
        $run = $workflowResponse['workflow_runs'][0];
        $workflowStatus = $run['status'];
        $workflowConclusion = $run['conclusion'];
        $workflowHtmlUrl = $run['html_url'];
    }
    
    $apkDownloadUrl = null;
    $apkFileName = null;
    $apkSize = null;
    
    if ($workflowConclusion === 'success') {
        $releasesUrl = "https://api.github.com/repos/{$owner}/{$repoName}/releases?per_page=1";
        $releasesResponse = githubApiRequest($releasesUrl, $token);
        
        if (!empty($releasesResponse) && isset($releasesResponse[0]['assets'])) {
            foreach ($releasesResponse[0]['assets'] as $asset) {
                if (strpos($asset['name'], '.apk') !== false) {
                    $apkDownloadUrl = $asset['browser_download_url'];
                    $apkFileName = $asset['name'];
                    $apkSize = $asset['size'];
                    break;
                }
            }
        }
        
        if (!$apkDownloadUrl) {
            $artifactsUrl = "https://api.github.com/repos/{$owner}/{$repoName}/actions/artifacts?per_page=5";
            $artifactsResponse = githubApiRequest($artifactsUrl, $token);
            
            if (isset($artifactsResponse['artifacts'])) {
                foreach ($artifactsResponse['artifacts'] as $artifact) {
                    if (strpos($artifact['name'], 'apk') !== false) {
                        $apkDownloadUrl = "https://github.com/{$owner}/{$repoName}/releases";
                        $apkFileName = 'Check Releases page';
                        break;
                    }
                }
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'build_id' => $buildId,
        'github_repo' => $repoName,
        'github_url' => "https://github.com/{$owner}/{$repoName}",
        'workflow_status' => $workflowStatus,
        'workflow_conclusion' => $workflowConclusion,
        'workflow_url' => $workflowHtmlUrl,
        'apk_ready' => !empty($apkDownloadUrl),
        'apk_download_url' => $apkDownloadUrl,
        'apk_file_name' => $apkFileName,
        'apk_size' => $apkSize,
        'releases_url' => "https://github.com/{$owner}/{$repoName}/releases"
    ]);
}

/**
 * Make GitHub API request
 */
function githubApiRequest($url, $token) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github.v3+json',
            'User-Agent: WebToAPK-Builder'
        ]
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
APIEOF

# Copy to server
echo "📝 Installing updated api.php..."
sudo cp /tmp/webtoapp_update/api.php /var/www/html/api.php
sudo chown www-data:www-data /var/www/html/api.php
sudo chmod 644 /var/www/html/api.php

# Test the API
echo "🧪 Testing GitHub status API..."
curl -s "http://localhost/api.php?action=github_status&build_id=test" | head -c 200

echo ""
echo "==================================="
echo "✅ Update Complete!"
echo "==================================="
echo ""
echo "Now test with a real build ID:"
echo "curl 'http://YOUR-DOMAIN/api.php?action=github_status&build_id=BUILD_ID'"
