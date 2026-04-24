<?php
/**
 * Download Handler
 * Serves the generated APK package
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

// Check if ZIP file exists
$zipPath = OUTPUT_DIR . $buildId . '.zip';

if (!file_exists($zipPath)) {
    header('HTTP/1.0 404 Not Found');
    echo 'File not found. The download link may have expired.';
    exit;
}

// Get app name from config
$configPath = OUTPUT_DIR . $buildId . '/config.json';
$appName = 'WebApp';
if (file_exists($configPath)) {
    $config = json_decode(file_get_contents($configPath), true);
    $appName = $config['app_name'] ?? 'WebApp';
}

// Clean app name for filename
$fileName = preg_replace('/[^a-zA-Z0-9\-_]/', '', str_replace(' ', '_', $appName));
$fileName = $fileName . '_Android_Project.zip';

// Send headers for download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Length: ' . filesize($zipPath));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

// Output file
readfile($zipPath);
exit;
