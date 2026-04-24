<?php
/**
 * Test Generate - Shows actual error
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre style='background:#1a1a2e; color:#fff; padding:20px; font-family:monospace;'>";
echo "=== GENERATE TEST ===\n\n";

require_once 'config.php';

// Simulate POST data
$_POST = [
    'website_url' => 'https://google.com',
    'app_name' => 'Test App',
    'package_name' => 'com.test.app',
    'version' => '1.0.0',
    'orientation' => 'both',
    'fullscreen' => 'no',
    'enable_splash' => '1',
    'splash_color' => '#6366f1',
    'splash_duration' => '2',
    'offline_mode' => '1',
    'back_button' => '1',
    'file_downloads' => '1'
];

$_SERVER['REQUEST_METHOD'] = 'POST';

echo "Step 1: Config loaded ✓\n";
echo "OUTPUT_DIR: " . OUTPUT_DIR . "\n";
echo "UPLOAD_DIR: " . UPLOAD_DIR . "\n\n";

// Generate build ID
$buildId = bin2hex(random_bytes(16));
$buildDir = OUTPUT_DIR . $buildId . '/';

echo "Step 2: Build ID: $buildId\n";
echo "Step 3: Creating build dir: $buildDir\n";

if (!mkdir($buildDir, 0777, true)) {
    echo "ERROR: Cannot create build directory!\n";
    exit;
}
echo "Build dir created ✓\n\n";

// Simple config
$apkConfig = [
    'app_name' => 'Test App',
    'package_name' => 'com.test.app',
    'version' => '1.0.0',
    'version_code' => 1,
    'website_url' => 'https://google.com',
    'orientation' => 'both',
    'fullscreen' => false,
    'enable_splash' => true,
    'splash_color' => '#6366f1',
    'splash_duration' => 2,
    'offline_mode' => true,
    'back_button' => true,
    'file_downloads' => true,
    'description' => 'Test app',
    'build_id' => $buildId,
    'app_icon_path' => null,
    'splash_icon_path' => null,
    'created_at' => date('Y-m-d H:i:s')
];

echo "Step 4: Saving config.json...\n";
$configSaved = file_put_contents($buildDir . 'config.json', json_encode($apkConfig, JSON_PRETTY_PRINT));
if ($configSaved) {
    echo "Config saved ✓ ($configSaved bytes)\n\n";
} else {
    echo "ERROR: Cannot save config!\n";
    exit;
}

echo "Step 5: Including generate functions...\n";

// Get generateAndroidProject function
$generateContent = file_get_contents('generate.php');

// Find and extract the function
preg_match('/function generateAndroidProject\(.+?\n\}/s', $generateContent, $matches);

echo "Step 6: Generating Android project...\n";

try {
    // Create minimal project structure
    $packagePath = str_replace('.', '/', $apkConfig['package_name']);
    
    $dirs = [
        'app/src/main/java/' . $packagePath,
        'app/src/main/res/layout',
        'app/src/main/res/values',
        'app/src/main/res/drawable',
        'app/src/main/res/mipmap-hdpi',
        'gradle/wrapper',
    ];
    
    foreach ($dirs as $dir) {
        $fullPath = $buildDir . $dir;
        if (!mkdir($fullPath, 0777, true)) {
            echo "ERROR: Cannot create $dir\n";
        } else {
            echo "  Created: $dir ✓\n";
        }
    }
    
    // Create simple files
    file_put_contents($buildDir . 'settings.gradle', 'rootProject.name = "TestApp"');
    file_put_contents($buildDir . 'build.gradle', '// Root build.gradle');
    file_put_contents($buildDir . 'app/build.gradle', '// App build.gradle');
    file_put_contents($buildDir . 'app/src/main/AndroidManifest.xml', '<?xml version="1.0"?><manifest/>');
    
    echo "\nStep 7: Creating ZIP...\n";
    
    // Test ZIP creation
    $zipPath = OUTPUT_DIR . $buildId . '.zip';
    $zip = new ZipArchive();
    
    $openResult = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    
    if ($openResult !== true) {
        echo "ERROR: Cannot open ZIP. Code: $openResult\n";
        exit;
    }
    
    echo "ZIP opened ✓\n";
    
    // Add files
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($buildDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    $count = 0;
    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($buildDir));
            $zip->addFile($filePath, 'TestApp/' . $relativePath);
            $count++;
        }
    }
    
    echo "Added $count files to ZIP ✓\n";
    
    $zip->close();
    
    if (file_exists($zipPath)) {
        $size = filesize($zipPath);
        echo "ZIP created ✓ ($size bytes)\n";
        echo "\nSUCCESS! Download: download.php?id=$buildId\n";
    } else {
        echo "ERROR: ZIP file not found after close!\n";
    }
    
} catch (Exception $e) {
    echo "\nEXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString();
}

echo "\n\n=== END TEST ===\n";
echo "</pre>";

// Show error log if exists
if (file_exists(__DIR__ . '/error.log')) {
    echo "<h3 style='color:#ef4444;'>Error Log:</h3>";
    echo "<pre style='background:#300; color:#faa; padding:20px;'>";
    echo htmlspecialchars(file_get_contents(__DIR__ . '/error.log'));
    echo "</pre>";
}
?>
