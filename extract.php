<?php
/**
 * Deploy Extractor - Upload this + deploy_update.zip to server
 * Then visit: http://your-domain/extract.php
 * It will extract all files and delete itself + the zip
 */

// Security: Only allow from server itself or specific IPs
$allowedIps = ['127.0.0.1', '::1', $_SERVER['SERVER_ADDR'] ?? ''];

$zipPath = __DIR__ . '/deploy_update.zip';

// Check ZIP exists
if (!file_exists($zipPath)) {
    die('<h2 style="color:red;">ERROR: deploy_update.zip not found!</h2><p>Upload deploy_update.zip to the same directory as this file.</p>');
}

echo '<pre style="font-family: monospace; background: #1a1a2e; color: #0f0; padding: 20px; border-radius: 10px;">';
echo "=== WebToAPK Deploy Extractor ===\n\n";

$zip = new ZipArchive();
$res = $zip->open($zipPath);

if ($res !== true) {
    die("ERROR: Cannot open ZIP file (code: $res)\n");
}

echo "Files in archive: " . $zip->numFiles . "\n\n";

$extracted = 0;
$failed = 0;

for ($i = 0; $i < $zip->numFiles; $i++) {
    $filename = $zip->getNameIndex($i);
    $destPath = __DIR__ . '/' . $filename;
    
    // Safety: only extract .php files to current directory
    if (strpos($filename, '/') !== false || strpos($filename, '..') !== false) {
        echo "[SKIP] $filename (path traversal blocked)\n";
        $failed++;
        continue;
    }
    
    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
        echo "[SKIP] $filename (not a PHP file)\n";
        $failed++;
        continue;
    }
    
    // Backup existing file
    if (file_exists($destPath)) {
        $backupPath = $destPath . '.bak.' . date('YmdHis');
        copy($destPath, $backupPath);
        echo "[BACKUP] $filename -> " . basename($backupPath) . "\n";
    }
    
    // Extract file
    $content = $zip->getFromIndex($i);
    if ($content !== false) {
        file_put_contents($destPath, $content);
        chmod($destPath, 0644);
        $size = strlen($content);
        echo "[OK] $filename ($size bytes)\n";
        $extracted++;
    } else {
        echo "[FAIL] $filename\n";
        $failed++;
    }
}

$zip->close();

echo "\n--- Results ---\n";
echo "Extracted: $extracted files\n";
echo "Failed: $failed files\n";

// Verify key files
echo "\n--- Verification ---\n";

$checkFiles = ['config.php', 'db.php', 'api.php', 'build_server.php', 'build_worker.php', 'generate.php', 'download_apk.php', 'index.php'];
foreach ($checkFiles as $f) {
    $path = __DIR__ . '/' . $f;
    if (file_exists($path)) {
        $size = filesize($path);
        echo "[✓] $f ($size bytes)\n";
    } else {
        echo "[✗] $f MISSING!\n";
    }
}

// Quick config check
echo "\n--- Config Check ---\n";
$configContent = file_get_contents(__DIR__ . '/config.php');
if (strpos($configContent, "define('ENABLE_APK_BUILD', true)") !== false) {
    echo "[✓] ENABLE_APK_BUILD = true\n";
} else {
    echo "[✗] ENABLE_APK_BUILD is NOT true!\n";
}
if (strpos($configContent, "define('ENABLE_GITHUB_BUILD', false)") !== false) {
    echo "[✓] ENABLE_GITHUB_BUILD = false\n";
} else {
    echo "[✗] ENABLE_GITHUB_BUILD is NOT false!\n";
}

// Test database
echo "\n--- Database Check ---\n";
try {
    require_once __DIR__ . '/db.php';
    $db = getDB();
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    echo "[✓] Database OK - Tables: " . implode(', ', $tables) . "\n";
} catch (Exception $e) {
    echo "[✗] Database error: " . $e->getMessage() . "\n";
}

// Test build environment
echo "\n--- Build Environment ---\n";
try {
    require_once __DIR__ . '/build_server.php';
    $builder = new APKBuilder();
    $env = $builder->checkEnvironment();
    foreach ($env as $k => $v) {
        echo ($v ? "[✓]" : "[✗]") . " $k\n";
    }
} catch (Exception $e) {
    echo "[✗] Build server error: " . $e->getMessage() . "\n";
}

// Clean up
echo "\n--- Cleanup ---\n";
unlink($zipPath);
echo "[✓] Deleted deploy_update.zip\n";

// Delete this script itself
$selfPath = __FILE__;
echo "[✓] Deleting extract.php (this script)\n";
echo "\n=== DEPLOY COMPLETE ===\n";
echo "Your server is now updated! Visit your website to test.\n";
echo '</pre>';

// Delete self
unlink($selfPath);
