<?php
/**
 * Admin Panel - Build Server Status
 * Check if APK build server is configured correctly
 */

require_once 'config.php';

// Simple authentication (change these credentials!)
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'admin123'; // CHANGE THIS!

// Check authentication
if (!isset($_SERVER['PHP_AUTH_USER']) || 
    $_SERVER['PHP_AUTH_USER'] !== $ADMIN_USER || 
    $_SERVER['PHP_AUTH_PW'] !== $ADMIN_PASS) {
    header('WWW-Authenticate: Basic realm="Admin Panel"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - WebToAPK Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: white;
            min-height: 100vh;
            padding: 40px;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { margin-bottom: 30px; display: flex; align-items: center; gap: 15px; }
        h1 i { color: #6366f1; }
        .card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .card h2 { font-size: 18px; margin-bottom: 20px; color: #94a3b8; }
        .status-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
        .status-item {
            background: rgba(15, 23, 42, 0.8);
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .status-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .status-icon.success { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .status-icon.error { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .status-icon.warning { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
        .status-info h3 { font-size: 14px; color: white; }
        .status-info p { font-size: 12px; color: #64748b; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
        .stat-item { text-align: center; padding: 20px; background: rgba(15, 23, 42, 0.8); border-radius: 10px; }
        .stat-item h3 { font-size: 32px; color: #6366f1; margin-bottom: 5px; }
        .stat-item p { color: #64748b; font-size: 13px; }
        
        .builds-table {
            width: 100%;
            border-collapse: collapse;
        }
        .builds-table th, .builds-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .builds-table th { color: #64748b; font-weight: 500; font-size: 12px; text-transform: uppercase; }
        .builds-table td { font-size: 14px; }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge.success { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .badge.pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-danger { background: #ef4444; color: white; }
        .btn-primary { background: #6366f1; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-cog"></i> Admin Panel</h1>
        
        <!-- Build Server Status -->
        <div class="card">
            <h2><i class="fas fa-server"></i> Build Server Status</h2>
            <div class="status-grid">
                <?php
                $checks = [
                    'PHP Version' => ['status' => version_compare(PHP_VERSION, '8.0', '>='), 'value' => PHP_VERSION],
                    'APK Build Enabled' => ['status' => defined('ENABLE_APK_BUILD') && ENABLE_APK_BUILD, 'value' => (defined('ENABLE_APK_BUILD') && ENABLE_APK_BUILD) ? 'Yes' : 'No'],
                ];
                
                // Check if shell_exec is available
                $shellExecAvailable = function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))));
                $checks['Shell Exec'] = ['status' => $shellExecAvailable, 'value' => $shellExecAvailable ? 'Available' : 'Disabled (Shared Hosting)'];
                
                // Check Java (only if shell_exec available)
                if ($shellExecAvailable) {
                    $javaVersion = @shell_exec('java -version 2>&1');
                    $hasJava = $javaVersion && strpos($javaVersion, 'version') !== false;
                    $checks['Java JDK'] = ['status' => $hasJava, 'value' => $hasJava ? 'Installed' : 'Not Found'];
                    
                    // Check Gradle
                    $gradleVersion = @shell_exec('gradle -v 2>&1');
                    $hasGradle = $gradleVersion && strpos($gradleVersion, 'Gradle') !== false;
                    $checks['Gradle'] = ['status' => $hasGradle, 'value' => $hasGradle ? 'Installed' : 'Not Found'];
                } else {
                    $checks['Java JDK'] = ['status' => false, 'value' => 'N/A (shell disabled)'];
                    $checks['Gradle'] = ['status' => false, 'value' => 'N/A (shell disabled)'];
                }
                
                // Check Android SDK
                $hasAndroidSdk = defined('ANDROID_SDK_PATH') && file_exists(ANDROID_SDK_PATH);
                $checks['Android SDK'] = ['status' => $hasAndroidSdk, 'value' => $hasAndroidSdk ? 'Found' : 'Not Found'];
                
                // Check directories
                $checks['Output Directory'] = ['status' => is_writable(OUTPUT_DIR), 'value' => is_writable(OUTPUT_DIR) ? 'Writable' : 'Not Writable'];
                $checks['Upload Directory'] = ['status' => is_writable(UPLOAD_DIR), 'value' => is_writable(UPLOAD_DIR) ? 'Writable' : 'Not Writable'];
                
                foreach ($checks as $name => $check):
                ?>
                <div class="status-item">
                    <div class="status-icon <?php echo $check['status'] ? 'success' : 'error'; ?>">
                        <i class="fas fa-<?php echo $check['status'] ? 'check' : 'times'; ?>"></i>
                    </div>
                    <div class="status-info">
                        <h3><?php echo $name; ?></h3>
                        <p><?php echo $check['value']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="card">
            <h2><i class="fas fa-chart-bar"></i> Statistics</h2>
            <div class="stats-grid">
                <?php
                // Count builds
                $builds = glob(OUTPUT_DIR . '*', GLOB_ONLYDIR);
                $totalBuilds = count($builds);
                
                // Count APKs
                $apks = glob(OUTPUT_DIR . '*/*.apk');
                $totalApks = count($apks);
                
                // Calculate disk usage
                $diskUsage = 0;
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(OUTPUT_DIR)) as $file) {
                    $diskUsage += $file->getSize();
                }
                $diskUsageMB = round($diskUsage / 1024 / 1024, 2);
                
                // Count uploads
                $uploads = glob(UPLOAD_DIR . '*');
                $totalUploads = count($uploads);
                ?>
                <div class="stat-item">
                    <h3><?php echo $totalBuilds; ?></h3>
                    <p>Total Builds</p>
                </div>
                <div class="stat-item">
                    <h3><?php echo $totalApks; ?></h3>
                    <p>APKs Generated</p>
                </div>
                <div class="stat-item">
                    <h3><?php echo $diskUsageMB; ?> MB</h3>
                    <p>Disk Usage</p>
                </div>
                <div class="stat-item">
                    <h3><?php echo $totalUploads; ?></h3>
                    <p>Uploaded Icons</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Builds -->
        <div class="card">
            <h2><i class="fas fa-history"></i> Recent Builds</h2>
            <table class="builds-table">
                <thead>
                    <tr>
                        <th>Build ID</th>
                        <th>App Name</th>
                        <th>URL</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $builds = glob(OUTPUT_DIR . '*', GLOB_ONLYDIR);
                    usort($builds, function($a, $b) { return filemtime($b) - filemtime($a); });
                    $builds = array_slice($builds, 0, 10);
                    
                    foreach ($builds as $buildPath):
                        $buildId = basename($buildPath);
                        $configFile = $buildPath . '/config.json';
                        $config = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];
                        $hasApk = !empty(glob($buildPath . '/*.apk'));
                    ?>
                    <tr>
                        <td><code style="color: #6366f1;"><?php echo substr($buildId, 0, 12); ?>...</code></td>
                        <td><?php echo htmlspecialchars($config['app_name'] ?? 'Unknown'); ?></td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo htmlspecialchars($config['website_url'] ?? 'N/A'); ?>
                        </td>
                        <td><?php echo $config['created_at'] ?? date('Y-m-d H:i', filemtime($buildPath)); ?></td>
                        <td>
                            <span class="badge <?php echo $hasApk ? 'success' : 'pending'; ?>">
                                <?php echo $hasApk ? 'APK Ready' : 'Source Only'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="download.php?id=<?php echo $buildId; ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($builds)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b; padding: 30px;">
                            No builds yet
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Cleanup -->
        <div class="card">
            <h2><i class="fas fa-trash"></i> Maintenance</h2>
            <p style="color: #64748b; margin-bottom: 15px;">Clean up old builds and free disk space</p>
            <form method="POST" style="display: flex; gap: 10px;">
                <button type="submit" name="cleanup" value="24h" class="btn btn-danger">
                    <i class="fas fa-broom"></i> Delete builds older than 24 hours
                </button>
                <button type="submit" name="cleanup" value="7d" class="btn btn-danger">
                    <i class="fas fa-broom"></i> Delete builds older than 7 days
                </button>
            </form>
            
            <?php
            if (isset($_POST['cleanup'])) {
                $age = $_POST['cleanup'] === '24h' ? 86400 : 604800;
                $deleted = 0;
                
                foreach (glob(OUTPUT_DIR . '*', GLOB_ONLYDIR) as $dir) {
                    if (filemtime($dir) < time() - $age) {
                        // Delete directory recursively
                        $files = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                            RecursiveIteratorIterator::CHILD_FIRST
                        );
                        foreach ($files as $file) {
                            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
                        }
                        rmdir($dir);
                        $deleted++;
                    }
                }
                
                echo "<p style='color: #10b981; margin-top: 15px;'><i class='fas fa-check'></i> Deleted $deleted old build(s)</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
