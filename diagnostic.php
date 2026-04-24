<?php
/**
 * Server Diagnostic Tool
 * Upload this file to check server configuration
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Server Diagnostic - WebToAPK</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background: #1a1a2e; color: white; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #6366f1; }
        .check { padding: 15px; margin: 10px 0; border-radius: 8px; display: flex; align-items: center; gap: 15px; }
        .check.pass { background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; }
        .check.fail { background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; }
        .check.warn { background: rgba(245, 158, 11, 0.2); border: 1px solid #f59e0b; }
        .icon { font-size: 24px; }
        .details { font-size: 12px; color: #94a3b8; margin-top: 5px; }
        code { background: #0f172a; padding: 2px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Server Diagnostic</h1>
        <p>Checking server configuration for WebToAPK...</p>
        <hr style="border-color: #333; margin: 20px 0;">
        
        <?php
        $allPassed = true;
        
        // Check PHP Version
        $phpVersion = phpversion();
        $phpOk = version_compare($phpVersion, '7.4', '>=');
        if (!$phpOk) $allPassed = false;
        ?>
        <div class="check <?php echo $phpOk ? 'pass' : 'fail'; ?>">
            <span class="icon"><?php echo $phpOk ? '✅' : '❌'; ?></span>
            <div>
                <strong>PHP Version</strong>
                <div class="details">Current: <?php echo $phpVersion; ?> | Required: 7.4+</div>
            </div>
        </div>
        
        <?php
        // Check ZipArchive
        $zipOk = class_exists('ZipArchive');
        if (!$zipOk) $allPassed = false;
        ?>
        <div class="check <?php echo $zipOk ? 'pass' : 'fail'; ?>">
            <span class="icon"><?php echo $zipOk ? '✅' : '❌'; ?></span>
            <div>
                <strong>ZipArchive Extension</strong>
                <div class="details"><?php echo $zipOk ? 'Installed' : 'NOT INSTALLED - Contact hosting support to enable php-zip'; ?></div>
            </div>
        </div>
        
        <?php
        // Check cURL
        $curlOk = function_exists('curl_init');
        if (!$curlOk) $allPassed = false;
        ?>
        <div class="check <?php echo $curlOk ? 'pass' : 'fail'; ?>">
            <span class="icon"><?php echo $curlOk ? '✅' : '❌'; ?></span>
            <div>
                <strong>cURL Extension</strong>
                <div class="details"><?php echo $curlOk ? 'Installed' : 'NOT INSTALLED - Required for API calls'; ?></div>
            </div>
        </div>
        
        <?php
        // Check output directory
        $outputDir = __DIR__ . '/output/';
        if (!is_dir($outputDir)) {
            @mkdir($outputDir, 0755, true);
        }
        $outputWritable = is_writable($outputDir);
        if (!$outputWritable) $allPassed = false;
        ?>
        <div class="check <?php echo $outputWritable ? 'pass' : 'fail'; ?>">
            <span class="icon"><?php echo $outputWritable ? '✅' : '❌'; ?></span>
            <div>
                <strong>Output Directory</strong>
                <div class="details">
                    Path: <code><?php echo $outputDir; ?></code><br>
                    <?php echo $outputWritable ? 'Writable ✓' : 'NOT WRITABLE - Set permission to 755 or 777'; ?>
                </div>
            </div>
        </div>
        
        <?php
        // Check uploads directory
        $uploadsDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadsDir)) {
            @mkdir($uploadsDir, 0755, true);
        }
        $uploadsWritable = is_writable($uploadsDir);
        if (!$uploadsWritable) $allPassed = false;
        ?>
        <div class="check <?php echo $uploadsWritable ? 'pass' : 'fail'; ?>">
            <span class="icon"><?php echo $uploadsWritable ? '✅' : '❌'; ?></span>
            <div>
                <strong>Uploads Directory</strong>
                <div class="details">
                    Path: <code><?php echo $uploadsDir; ?></code><br>
                    <?php echo $uploadsWritable ? 'Writable ✓' : 'NOT WRITABLE - Set permission to 755 or 777'; ?>
                </div>
            </div>
        </div>
        
        <?php
        // Check template directory
        $templateDir = __DIR__ . '/template/';
        if (!is_dir($templateDir)) {
            @mkdir($templateDir, 0755, true);
        }
        $templateWritable = is_writable($templateDir);
        ?>
        <div class="check <?php echo $templateWritable ? 'pass' : 'warn'; ?>">
            <span class="icon"><?php echo $templateWritable ? '✅' : '⚠️'; ?></span>
            <div>
                <strong>Template Directory</strong>
                <div class="details">
                    Path: <code><?php echo $templateDir; ?></code><br>
                    <?php echo $templateWritable ? 'Writable ✓' : 'Not writable (optional)'; ?>
                </div>
            </div>
        </div>
        
        <?php
        // Test ZIP creation
        $testZipPath = $outputDir . 'test_' . time() . '.zip';
        $zipTestOk = false;
        if ($zipOk && $outputWritable) {
            $testZip = new ZipArchive();
            if ($testZip->open($testZipPath, ZipArchive::CREATE) === true) {
                $testZip->addFromString('test.txt', 'Hello World');
                $testZip->close();
                if (file_exists($testZipPath)) {
                    $zipTestOk = true;
                    @unlink($testZipPath); // Clean up
                }
            }
        }
        if (!$zipTestOk && $zipOk) $allPassed = false;
        ?>
        <div class="check <?php echo $zipTestOk ? 'pass' : ($zipOk ? 'fail' : 'warn'); ?>">
            <span class="icon"><?php echo $zipTestOk ? '✅' : ($zipOk ? '❌' : '⚠️'); ?></span>
            <div>
                <strong>ZIP Creation Test</strong>
                <div class="details">
                    <?php 
                    if ($zipTestOk) {
                        echo 'Successfully created and deleted test ZIP file';
                    } elseif (!$zipOk) {
                        echo 'Skipped - ZipArchive not available';
                    } else {
                        echo 'FAILED - Cannot create ZIP files. Check folder permissions.';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <?php
        // Check GitHub configuration
        if (file_exists(__DIR__ . '/config.php')) {
            require_once __DIR__ . '/config.php';
            $githubConfigured = defined('ENABLE_GITHUB_BUILD') && ENABLE_GITHUB_BUILD && !empty(GITHUB_TOKEN) && !empty(GITHUB_OWNER);
        } else {
            $githubConfigured = false;
        }
        ?>
        <div class="check <?php echo $githubConfigured ? 'pass' : 'warn'; ?>">
            <span class="icon"><?php echo $githubConfigured ? '✅' : '⚠️'; ?></span>
            <div>
                <strong>GitHub Actions</strong>
                <div class="details">
                    <?php 
                    if ($githubConfigured) {
                        echo 'Configured for user: ' . GITHUB_OWNER;
                    } else {
                        echo 'Not configured - Will generate source code only';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <?php
        // Test GitHub API
        if ($githubConfigured && $curlOk) {
            $ch = curl_init('https://api.github.com/user');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . GITHUB_TOKEN,
                    'Accept: application/vnd.github.v3+json',
                    'User-Agent: WebToAPK-Builder'
                ]
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $githubApiOk = $httpCode === 200;
            $userData = json_decode($response, true);
        ?>
        <div class="check <?php echo $githubApiOk ? 'pass' : 'fail'; ?>">
            <span class="icon"><?php echo $githubApiOk ? '✅' : '❌'; ?></span>
            <div>
                <strong>GitHub API Connection</strong>
                <div class="details">
                    <?php 
                    if ($githubApiOk) {
                        echo 'Connected as: ' . ($userData['login'] ?? 'Unknown');
                    } else {
                        echo 'FAILED - HTTP ' . $httpCode . '. Check if token is valid.';
                        if ($httpCode === 401) {
                            echo ' Token may be expired or invalid.';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <hr style="border-color: #333; margin: 20px 0;">
        
        <?php if ($allPassed): ?>
        <div class="check pass">
            <span class="icon">🎉</span>
            <div>
                <strong>All Checks Passed!</strong>
                <div class="details">Your server is ready to run WebToAPK</div>
            </div>
        </div>
        <?php else: ?>
        <div class="check fail">
            <span class="icon">⚠️</span>
            <div>
                <strong>Some Checks Failed</strong>
                <div class="details">Please fix the issues above before using WebToAPK</div>
            </div>
        </div>
        
        <h3 style="margin-top: 30px;">🔧 How to Fix Common Issues:</h3>
        
        <div style="background: #0f172a; padding: 20px; border-radius: 8px; margin-top: 15px;">
            <p><strong>Folder Permissions (cPanel/Hostinger):</strong></p>
            <ol>
                <li>Go to File Manager</li>
                <li>Right-click on <code>output</code> folder → Permissions</li>
                <li>Set to <code>755</code> or <code>777</code></li>
                <li>Repeat for <code>uploads</code> folder</li>
            </ol>
            
            <p style="margin-top: 20px;"><strong>ZipArchive Not Installed:</strong></p>
            <ol>
                <li>Go to cPanel → Select PHP Version</li>
                <li>Enable <code>zip</code> extension</li>
                <li>Save changes</li>
            </ol>
        </div>
        <?php endif; ?>
        
        <p style="margin-top: 30px; text-align: center; color: #64748b;">
            <a href="index.php" style="color: #6366f1;">← Back to WebToAPK</a>
        </p>
    </div>
</body>
</html>
