<?php
/**
 * APK Download Handler
 * Serves the generated APK file
 */

require_once 'config.php';

$buildId = $_GET['id'] ?? '';

if (empty($buildId)) {
    header('HTTP/1.0 400 Bad Request');
    echo 'Invalid request';
    exit;
}

// Sanitize build ID
$buildId = preg_replace('/[^a-zA-Z0-9_]/', '', $buildId);

// Find APK file
$buildDir = OUTPUT_DIR . $buildId . '/';

if (!is_dir($buildDir)) {
    header('HTTP/1.0 404 Not Found');
    echo 'Build not found. The download link may have expired.';
    exit;
}

// Find APK file
$apkFile = null;
$files = glob($buildDir . '*.apk');

if (!empty($files)) {
    $apkFile = $files[0];
}

if (!$apkFile || !file_exists($apkFile)) {
    header('HTTP/1.0 404 Not Found');
    echo 'APK file not found. Build may still be in progress.';
    exit;
}

// Get app name from config
$configPath = $buildDir . 'config.json';
$appName = 'WebApp';
if (file_exists($configPath)) {
    $config = json_decode(file_get_contents($configPath), true);
    $appName = $config['app_name'] ?? 'WebApp';
}

// Clean app name for filename
$fileName = preg_replace('/[^a-zA-Z0-9\-_]/', '', str_replace(' ', '_', $appName));
$fileName = $fileName . '.apk';

// Send headers for download
header('Content-Type: application/vnd.android.package-archive');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Length: ' . filesize($apkFile));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

// Output file
readfile($apkFile);
exit;
