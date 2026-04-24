<?php
/**
 * Debug Test - Check what's causing 500 error
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>System Check</h1>";
echo "<pre>";

// 1. Check PHP version
echo "PHP Version: " . PHP_VERSION . "\n\n";

// 2. Check if config loads
echo "Loading config.php...\n";
try {
    require_once 'config.php';
    echo "✅ config.php loaded successfully\n";
    echo "OUTPUT_DIR: " . OUTPUT_DIR . "\n";
    echo "UPLOAD_DIR: " . UPLOAD_DIR . "\n";
} catch (Exception $e) {
    echo "❌ Error loading config: " . $e->getMessage() . "\n";
}

// 3. Check ZipArchive
echo "\n--- ZipArchive Extension ---\n";
if (class_exists('ZipArchive')) {
    echo "✅ ZipArchive is available\n";
} else {
    echo "❌ ZipArchive NOT available - THIS IS THE PROBLEM!\n";
}

// 4. Check shell_exec
echo "\n--- shell_exec Function ---\n";
if (function_exists('shell_exec')) {
    $disabled = explode(',', ini_get('disable_functions'));
    $disabled = array_map('trim', $disabled);
    if (in_array('shell_exec', $disabled)) {
        echo "❌ shell_exec is DISABLED\n";
    } else {
        echo "✅ shell_exec is available\n";
    }
} else {
    echo "❌ shell_exec function not available\n";
}

// 5. Check folder permissions
echo "\n--- Folder Permissions ---\n";
$outputDir = __DIR__ . '/output/';
$uploadDir = __DIR__ . '/uploads/';

if (is_writable($outputDir)) {
    echo "✅ output/ folder is writable\n";
} else {
    echo "❌ output/ folder NOT writable\n";
}

if (is_writable($uploadDir)) {
    echo "✅ uploads/ folder is writable\n";
} else {
    echo "❌ uploads/ folder NOT writable\n";
}

// 6. Check cURL
echo "\n--- cURL Extension ---\n";
if (function_exists('curl_init')) {
    echo "✅ cURL is available\n";
} else {
    echo "❌ cURL NOT available\n";
}

// 7. Check GitHub token format
echo "\n--- GitHub Token Check ---\n";
if (defined('GITHUB_TOKEN')) {
    $token = GITHUB_TOKEN;
    if (strpos($token, 'ghp_') === 0) {
        echo "✅ Classic token format (ghp_)\n";
    } elseif (strpos($token, 'github_pat_') === 0) {
        echo "✅ Fine-grained token format (github_pat_)\n";
    } else {
        echo "⚠️ Unknown token format\n";
    }
    echo "Token length: " . strlen($token) . " chars\n";
}

// 8. Test GitHub API
echo "\n--- GitHub API Test ---\n";
if (defined('GITHUB_TOKEN') && function_exists('curl_init')) {
    $ch = curl_init('https://api.github.com/user');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . GITHUB_TOKEN,
        'User-Agent: WebToAPK-Builder',
        'Accept: application/vnd.github.v3+json'
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $user = json_decode($response, true);
        echo "✅ GitHub API working! User: " . ($user['login'] ?? 'unknown') . "\n";
    } else {
        echo "❌ GitHub API error. HTTP Code: $httpCode\n";
        echo "Response: " . substr($response, 0, 200) . "\n";
    }
}

echo "\n</pre>";
echo "<hr>";
echo "<p><strong>Agar sab ✅ hai toh APK generate hona chahiye!</strong></p>";
echo "<p><a href='index.php'>Back to Home</a></p>";
