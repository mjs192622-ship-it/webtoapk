<?php
/**
 * APK Generator Script with Splash Screen Support
 * Generates Android APK Project from website URL
 */

// Extend PHP execution limit (Render hard-kills at 30s HTTP, but this helps with PHP's own limit)
set_time_limit(25);

// Enable error reporting to log file
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Buffer ALL output — any stray PHP notice/warning will be captured here
// instead of corrupting the JSON response
ob_start();

require_once 'config.php';
require_once 'auth.php';

// Discard any output from require (e.g. BOM, whitespace, notices)
ob_clean();

header('Content-Type: application/json');

// Custom error handler — log errors but never print them
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno]: $errstr in $errfile on line $errline");
    return true; // true = suppress default PHP error output
});

// Shutdown function: catches PHP FATAL errors that bypass try/catch
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        // Discard any partial output already in buffer
        ob_clean();
        error_log('PHP Fatal in generate.php: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line']);
        echo json_encode([
            'success' => false,
            'message' => 'Server error occurred. Please try again. (Fatal: ' . $error['message'] . ')'
        ]);
    }
    ob_end_flush();
});

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get form data
$websiteUrl = $_POST['website_url'] ?? '';
$appName = $_POST['app_name'] ?? '';
$packageName = $_POST['package_name'] ?? '';
$version = $_POST['version'] ?? '1.0.0';
$versionCode = intval($_POST['version_code'] ?? 1);
$orientation = $_POST['orientation'] ?? 'both';
$fullscreen = $_POST['fullscreen'] ?? 'no';
$minSdk = intval($_POST['min_sdk'] ?? 21);
$statusBarColor = $_POST['status_bar_color'] ?? '#4f46e5';
$loadingBarColor = $_POST['loading_bar_color'] ?? '#6366f1';
$customUserAgent = trim($_POST['custom_user_agent'] ?? '');
$jsInjection = trim($_POST['js_injection'] ?? '');

// Splash screen settings
$enableSplash = isset($_POST['enable_splash']) ? true : false;
$splashColor = $_POST['splash_color'] ?? '#6366f1';
$splashDuration = intval($_POST['splash_duration'] ?? 2);

// Additional features
$offlineMode = isset($_POST['offline_mode']) ? true : false;
$backButton = isset($_POST['back_button']) ? true : false;
$fileDownloads = isset($_POST['file_downloads']) ? true : false;
$pushNotifications = isset($_POST['push_notifications']) ? true : false;
$pullToRefresh = isset($_POST['pull_to_refresh']) ? true : false;
$zoomControls = isset($_POST['zoom_controls']) ? true : false;
$shareButton = isset($_POST['share_button']) ? true : false;
$keepScreenOn = isset($_POST['keep_screen_on']) ? true : false;
$preventScreenshots = isset($_POST['prevent_screenshots']) ? true : false;
$externalLinksBrowser = isset($_POST['external_links_browser']) ? true : false;
$hardwareAcceleration = isset($_POST['hardware_acceleration']) ? true : false;
$thirdPartyCookies = isset($_POST['third_party_cookies']) ? true : false;
$textSize = in_array($_POST['text_size'] ?? 'NORMAL', ['SMALLEST','SMALLER','NORMAL','LARGER','LARGEST']) ? ($_POST['text_size'] ?? 'NORMAL') : 'NORMAL';
$rateAppLaunches = intval($_POST['rate_app_launches'] ?? 0);
$offlinePageHtml = trim($_POST['offline_page_html'] ?? '');

// Play Store fields
$privacyPolicyUrl = trim($_POST['privacy_policy_url'] ?? '');
$appCategory = trim($_POST['app_category'] ?? 'tools');
$appDescriptionInput = trim($_POST['app_description'] ?? '');

// Custom Actions & Deep Links
$urlScheme = preg_replace('/[^a-z0-9]/', '', strtolower(trim($_POST['url_scheme'] ?? '')));
$appLinksDomain = trim($_POST['app_links_domain'] ?? '');
$jsBridge = isset($_POST['js_bridge']) ? true : false;
$fabEnabled = isset($_POST['fab_enabled']) ? true : false;
$fabAction = in_array($_POST['fab_action'] ?? '', ['whatsapp','call','email','url','sms','telegram']) ? $_POST['fab_action'] : 'whatsapp';
$fabValue = trim($_POST['fab_value'] ?? '');
$fabColor = $_POST['fab_color'] ?? '#25D366';
$fabIcon = in_array($_POST['fab_icon'] ?? '', ['chat','phone','email','whatsapp','help','cart']) ? $_POST['fab_icon'] : 'chat';
$toolbarLabels = $_POST['toolbar_label'] ?? [];
$toolbarUrls = $_POST['toolbar_url'] ?? [];
$toolbarItems = [];
if (is_array($toolbarLabels) && is_array($toolbarUrls)) {
    for ($i = 0; $i < min(count($toolbarLabels), count($toolbarUrls), 3); $i++) {
        $label = trim($toolbarLabels[$i]);
        $url = trim($toolbarUrls[$i]);
        if (!empty($label) && !empty($url)) {
            $toolbarItems[] = ['label' => $label, 'url' => $url];
        }
    }
}

// Permission settings
$cameraPermission = isset($_POST['camera_permission']) ? true : false;
$locationPermission = isset($_POST['location_permission']) ? true : false;
$microphonePermission = isset($_POST['microphone_permission']) ? true : false;
$phoneStatePermission = isset($_POST['phone_state_permission']) ? true : false;
$contactsPermission = isset($_POST['contacts_permission']) ? true : false;
$calendarPermission = isset($_POST['calendar_permission']) ? true : false;
$bluetoothPermission = isset($_POST['bluetooth_permission']) ? true : false;
$biometricPermission = isset($_POST['biometric_permission']) ? true : false;
$smsPermission = isset($_POST['sms_permission']) ? true : false;
$callPhonePermission = isset($_POST['call_phone_permission']) ? true : false;
$callLogPermission = isset($_POST['call_log_permission']) ? true : false;
$storagePermission = isset($_POST['storage_permission']) ? true : false;
$nfcPermission = isset($_POST['nfc_permission']) ? true : false;
$bodySensorsPermission = isset($_POST['body_sensors_permission']) ? true : false;
$foregroundServicePermission = isset($_POST['foreground_service_permission']) ? true : false;
$notificationPermission = isset($_POST['notification_permission']) ? true : false;

// New Round 5 features
$exitConfirmation = isset($_POST['exit_confirmation']) ? true : false;
$forceDark = isset($_POST['force_dark']) ? true : false;
$fileUploadCamera = isset($_POST['file_upload_camera']) ? true : false;
$cssInjection = trim($_POST['css_injection'] ?? '');
$clearCache = isset($_POST['clear_cache']) ? true : false;
$loadingStyle = in_array($_POST['loading_style'] ?? 'linear', ['linear','circular','both','none']) ? ($_POST['loading_style'] ?? 'linear') : 'linear';
$immersiveMode = isset($_POST['immersive_mode']) ? true : false;

// AdMob
$admobEnabled = isset($_POST['admob_enabled']) ? true : false;
$admobAppId = trim($_POST['admob_app_id'] ?? '');
$admobBannerId = trim($_POST['admob_banner_id'] ?? '');
$admobInterstitialId = trim($_POST['admob_interstitial_id'] ?? '');
$admobInterstitialInterval = intval($_POST['admob_interstitial_interval'] ?? 3);

// Round 6 features
$swipeGestures = isset($_POST['swipe_gestures']) ? true : false;
$multiWindow = isset($_POST['multi_window']) ? true : false;
$gdprConsent = isset($_POST['gdpr_consent']) ? true : false;
$appLock = isset($_POST['app_lock']) ? true : false;
$navDrawer = isset($_POST['nav_drawer']) ? true : false;
$bottomNav = isset($_POST['bottom_nav']) ? true : false;
$updateChecker = isset($_POST['update_checker']) ? true : false;
$updateCheckUrl = trim($_POST['update_check_url'] ?? '');

// Nav drawer items
$drawerIcons = $_POST['drawer_icon'] ?? [];
$drawerLabels = $_POST['drawer_label'] ?? [];
$drawerUrls = $_POST['drawer_url'] ?? [];
$drawerItems = [];
if ($navDrawer && is_array($drawerLabels) && is_array($drawerUrls)) {
    for ($i = 0; $i < min(count($drawerLabels), count($drawerUrls), 6); $i++) {
        $label = trim($drawerLabels[$i]);
        $url = trim($drawerUrls[$i]);
        $icon = $drawerIcons[$i] ?? 'link';
        if (!empty($label) && !empty($url)) {
            $drawerItems[] = ['label' => $label, 'url' => $url, 'icon' => $icon];
        }
    }
}

// Bottom nav items
$bnavIcons = $_POST['bnav_icon'] ?? [];
$bnavLabels = $_POST['bnav_label'] ?? [];
$bnavUrls = $_POST['bnav_url'] ?? [];
$bnavItems = [];
if ($bottomNav && is_array($bnavLabels) && is_array($bnavUrls)) {
    for ($i = 0; $i < min(count($bnavLabels), count($bnavUrls), 5); $i++) {
        $label = trim($bnavLabels[$i]);
        $url = trim($bnavUrls[$i]);
        $icon = $bnavIcons[$i] ?? 'home';
        if (!empty($label) && !empty($url)) {
            $bnavItems[] = ['label' => $label, 'url' => $url, 'icon' => $icon];
        }
    }
}

// Firebase / FCM config files
$googleServicesJsonContent = null;
$firebaseServiceAccountContent = null;

if ($pushNotifications) {
    // Handle google-services.json upload
    if (isset($_FILES['google_services_json']) && $_FILES['google_services_json']['error'] === UPLOAD_ERR_OK) {
        $gsContent = file_get_contents($_FILES['google_services_json']['tmp_name']);
        $gsData = json_decode($gsContent, true);
        if ($gsData && isset($gsData['project_info']) && isset($gsData['client'])) {
            $googleServicesJsonContent = $gsContent;
        }
    }
    
    // Handle firebase-service-account.json upload
    if (isset($_FILES['firebase_service_account']) && $_FILES['firebase_service_account']['error'] === UPLOAD_ERR_OK) {
        $saContent = file_get_contents($_FILES['firebase_service_account']['tmp_name']);
        $saData = json_decode($saContent, true);
        if ($saData && isset($saData['project_id']) && isset($saData['private_key'])) {
            $firebaseServiceAccountContent = $saContent;
        }
    }
}

// Validate inputs
if (empty($websiteUrl) || empty($appName) || empty($packageName)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

if (!isValidUrl($websiteUrl)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid URL']);
    exit;
}

// Sanitize package name
$packageName = sanitizePackageName($packageName);
if (empty($packageName) || substr_count($packageName, '.') < 1) {
    $packageName = 'com.' . strtolower(preg_replace('/[^a-z0-9]/i', '', $appName)) . '.app';
}

// Generate unique build ID
$buildId = generateUniqueId();
$buildDir = OUTPUT_DIR . $buildId . '/';
mkdir($buildDir, 0777, true);

// Handle icon uploads
$appIconPath = null;
$splashIconPath = null;

if (isset($_FILES['app_icon']) && $_FILES['app_icon']['error'] === UPLOAD_ERR_OK) {
    $appIconName = 'app_icon_' . $buildId . '.png';
    $appIconPath = UPLOAD_DIR . $appIconName;
    move_uploaded_file($_FILES['app_icon']['tmp_name'], $appIconPath);
}

if (isset($_FILES['splash_icon']) && $_FILES['splash_icon']['error'] === UPLOAD_ERR_OK) {
    $splashIconName = 'splash_icon_' . $buildId . '.png';
    $splashIconPath = UPLOAD_DIR . $splashIconName;
    move_uploaded_file($_FILES['splash_icon']['tmp_name'], $splashIconPath);
}

// Use AI to generate app description (user can override)
$appDescription = "";
if (!empty($appDescriptionInput)) {
    $appDescription = $appDescriptionInput;
} else {
    try {
        $prompt = "Generate a short 1-2 sentence app store description for an Android app called '$appName' that shows the website '$websiteUrl'. Be professional and concise. Just give the description, nothing else.";
        $aiDescription = callGroqAPI($prompt);
        if ($aiDescription) {
            $appDescription = trim($aiDescription);
        }
    } catch (Exception $e) {
        $appDescription = "Mobile app for $appName - Access $websiteUrl on your Android device.";
    }
}

// Create the APK configuration
$apkConfig = [
    'app_name' => $appName,
    'package_name' => $packageName,
    'version' => $version,
    'version_code' => $versionCode ?: (intval(str_replace('.', '', $version)) ?: 1),
    'website_url' => $websiteUrl,
    'orientation' => $orientation,
    'fullscreen' => $fullscreen === 'yes',
    'min_sdk' => $minSdk,
    'status_bar_color' => $statusBarColor,
    'loading_bar_color' => $loadingBarColor,
    'custom_user_agent' => $customUserAgent,
    'js_injection' => $jsInjection,
    'enable_splash' => $enableSplash,
    'splash_color' => $splashColor,
    'splash_duration' => $splashDuration,
    'offline_mode' => $offlineMode,
    'back_button' => $backButton,
    'file_downloads' => $fileDownloads,
    'push_notifications' => $pushNotifications,
    'pull_to_refresh' => $pullToRefresh,
    'zoom_controls' => $zoomControls,
    'share_button' => $shareButton,
    'keep_screen_on' => $keepScreenOn,
    'prevent_screenshots' => $preventScreenshots,
    'external_links_browser' => $externalLinksBrowser,
    'hardware_acceleration' => $hardwareAcceleration,
    'third_party_cookies' => $thirdPartyCookies,
    'text_size' => $textSize,
    'rate_app_launches' => $rateAppLaunches,
    'offline_page_html' => $offlinePageHtml,
    'privacy_policy_url' => $privacyPolicyUrl,
    'app_category' => $appCategory,
    'url_scheme' => $urlScheme,
    'app_links_domain' => $appLinksDomain,
    'js_bridge' => $jsBridge,
    'fab_enabled' => $fabEnabled,
    'fab_action' => $fabAction,
    'fab_value' => $fabValue,
    'fab_color' => $fabColor,
    'fab_icon' => $fabIcon,
    'toolbar_items' => $toolbarItems,
    'camera_permission' => $cameraPermission,
    'location_permission' => $locationPermission,
    'microphone_permission' => $microphonePermission,
    'phone_state_permission' => $phoneStatePermission,
    'contacts_permission' => $contactsPermission,
    'calendar_permission' => $calendarPermission,
    'bluetooth_permission' => $bluetoothPermission,
    'biometric_permission' => $biometricPermission,
    'sms_permission' => $smsPermission,
    'call_phone_permission' => $callPhonePermission,
    'call_log_permission' => $callLogPermission,
    'storage_permission' => $storagePermission,
    'nfc_permission' => $nfcPermission,
    'body_sensors_permission' => $bodySensorsPermission,
    'foreground_service_permission' => $foregroundServicePermission,
    'notification_permission' => $notificationPermission,
    'exit_confirmation' => $exitConfirmation,
    'force_dark' => $forceDark,
    'file_upload_camera' => $fileUploadCamera,
    'css_injection' => $cssInjection,
    'clear_cache' => $clearCache,
    'loading_style' => $loadingStyle,
    'immersive_mode' => $immersiveMode,
    'admob_enabled' => $admobEnabled,
    'admob_app_id' => $admobAppId,
    'admob_banner_id' => $admobBannerId,
    'admob_interstitial_id' => $admobInterstitialId,
    'admob_interstitial_interval' => $admobInterstitialInterval,
    'swipe_gestures' => $swipeGestures,
    'multi_window' => $multiWindow,
    'gdpr_consent' => $gdprConsent,
    'app_lock' => $appLock,
    'nav_drawer' => $navDrawer,
    'drawer_items' => $drawerItems,
    'bottom_nav' => $bottomNav,
    'bnav_items' => $bnavItems,
    'update_checker' => $updateChecker,
    'update_check_url' => $updateCheckUrl,
    'has_google_services' => !empty($googleServicesJsonContent),
    'has_firebase_service_account' => !empty($firebaseServiceAccountContent),
    'description' => $appDescription,
    'build_id' => $buildId,
    'app_icon_path' => $appIconPath,
    'splash_icon_path' => $splashIconPath,
    'created_at' => date('Y-m-d H:i:s')
];

// Save firebase files directly to disk NOW (before generateAndroidProject)
// Don't store large binary/key content in $apkConfig to avoid json_encode failure
if (!empty($googleServicesJsonContent)) {
    @mkdir($buildDir . 'app', 0777, true);
    file_put_contents($buildDir . '_google_services_tmp.json', $googleServicesJsonContent);
}
if (!empty($firebaseServiceAccountContent)) {
    @mkdir($buildDir . 'server-config', 0777, true);
    file_put_contents($buildDir . 'server-config/firebase-service-account.json', $firebaseServiceAccountContent);
}

// Save configuration (without large firebase content)
$configEncoded = json_encode($apkConfig, JSON_PRETTY_PRINT);
if ($configEncoded !== false) {
    file_put_contents($buildDir . 'config.json', $configEncoded);
}

// Pass firebase content separately so generateAndroidProject can use it
$apkConfig['google_services_json'] = $googleServicesJsonContent;
$apkConfig['firebase_service_account'] = $firebaseServiceAccountContent;

// Generate the Android project files
try {
    generateAndroidProject($buildDir, $apkConfig);
    // Remove temp firebase file after project generation
    @unlink($buildDir . '_google_services_tmp.json');
    
    // Try to build APK if build server is available
    $apkBuilt = false;
    $apkDownloadUrl = null;
    $githubBuildStarted = false;
    $githubRepoUrl = null;
    $localBuildQueued = false;
    
    // Option 1: Local APK Build (Self-hosted VPS with Android SDK)
    if (defined('ENABLE_APK_BUILD') && ENABLE_APK_BUILD) {
        try {
            require_once 'build_server.php';
            
            $builder = new APKBuilder();
            $envCheck = $builder->checkEnvironment();
            
            if ($envCheck['ready']) {
                // Queue the build for async processing
                $builder->queueBuild($buildId, $buildDir, $appName);
                $builder->startBuildWorker($buildId);
                $localBuildQueued = true;
            }
        } catch (Exception $e) {
            error_log('Local build failed: ' . $e->getMessage());
        }
    }
    
    // Option 2: GitHub Actions Build (fallback)
    if (!$apkBuilt && !$localBuildQueued && defined('ENABLE_GITHUB_BUILD') && ENABLE_GITHUB_BUILD) {
        try {
            require_once 'github_builder.php';
            
            $githubToken = GITHUB_TOKEN;
            $githubOwner = GITHUB_OWNER;
            
            if (empty($githubToken)) {
                error_log("GitHub build skipped: GITHUB_TOKEN env var not set in Render");
            } elseif (!empty($githubToken) && !empty($githubOwner)) {
                $builder = new GitHubAPKBuilder($githubToken, $githubOwner, '');
                
                // Create workflow in project
                $projectPath = $buildDir;
                $builder->initializeRepo($projectPath, $buildId);
                
                // Create repo name
                $repoName = 'apk-' . substr($buildId, 0, 8);
                
                // Create GitHub repo
                $createResult = $builder->createRepository($repoName, "WebView APK for $appName");
                
                if (isset($createResult['id'])) {
                    // Push project to GitHub
                    $pushResult = $builder->pushProject($projectPath, $repoName);
                    
                    if ($pushResult['success']) {
                        $githubBuildStarted = true;
                        $githubRepoUrl = "https://github.com/{$githubOwner}/{$repoName}";
                        
                        // Save GitHub info to config (exclude sensitive data)
                        $configToSave = $apkConfig;
                        unset($configToSave['google_services_json'], $configToSave['firebase_service_account']);
                        $configToSave['github_repo'] = $repoName;
                        $configToSave['github_url'] = $githubRepoUrl;
                        file_put_contents($buildDir . 'config.json', json_encode($configToSave, JSON_PRETTY_PRINT));
                    } else {
                        error_log("GitHub push failed: " . ($pushResult['output'] ?? 'unknown'));
                    }
                } else {
                    error_log("GitHub repo creation failed: " . json_encode($createResult));
                }
            }
        } catch (Exception $e) {
            // GitHub build failed, continue with ZIP download
            error_log("GitHub build failed: " . $e->getMessage());
        }
    }
    
    // Create downloadable package (ZIP with source)
    $zipFile = createDownloadPackage($buildDir, $apkConfig);
    
    // Save build to database for user history
    $buildUser = getCurrentUser();
    if ($buildUser || true) { // Save for all builds, even anonymous
        saveBuild([
            'user_id' => $buildUser ? $buildUser['id'] : null,
            'build_id' => $buildId,
            'app_name' => $appName,
            'package_name' => $packageName,
            'website_url' => $websiteUrl,
            'version' => $version,
            'icon_path' => !empty($_FILES['app_icon']['name']) ? 'uploads/' . $buildId . '_icon.png' : null,
            'status' => ($localBuildQueued || $githubBuildStarted) ? 'building' : 'generated',
            'download_url' => 'download.php?id=' . $buildId,
            'config_json' => json_encode(['features' => array_filter([
                $apkConfig['enable_splash'] ? 'splash' : null,
                $apkConfig['push_notifications'] ? 'push' : null,
                $apkConfig['admob_enabled'] ? 'admob' : null,
                $apkConfig['file_downloads'] ? 'downloads' : null,
                $apkConfig['camera_permission'] ? 'camera' : null,
                $apkConfig['location_permission'] ? 'location' : null,
            ])])
        ]);
    }

    if ($apkBuilt) {
        echo json_encode([
            'success' => true,
            'message' => "APK generated successfully! Your app '$appName' is ready to download.",
            'download_url' => $apkDownloadUrl,
            'source_url' => 'download.php?id=' . $buildId,
            'build_id' => $buildId,
            'type' => 'apk',
            'description' => $appDescription
        ]);
    } elseif ($localBuildQueued) {
        echo json_encode([
            'success' => true,
            'message' => "Build started! Your APK is being compiled on our server. It will be ready in 3-8 minutes.",
            'download_url' => 'download.php?id=' . $buildId,
            'build_id' => $buildId,
            'type' => 'local_build',
            'description' => $appDescription
        ]);
    } elseif ($githubBuildStarted) {
        echo json_encode([
            'success' => true,
            'message' => "Build started! Your APK is being built on GitHub. It will be ready in 3-5 minutes.",
            'download_url' => 'download.php?id=' . $buildId,
            'build_id' => $buildId,
            'type' => 'github',
            'github_url' => $githubRepoUrl,
            'actions_url' => $githubRepoUrl . '/actions',
            'releases_url' => $githubRepoUrl . '/releases',
            'description' => $appDescription
        ]);
    } elseif ($zipFile) {
        echo json_encode([
            'success' => true,
            'message' => "Your app '$appName' has been generated successfully!",
            'download_url' => 'download.php?id=' . $buildId,
            'build_id' => $buildId,
            'type' => 'source',
            'description' => $appDescription
        ]);
    } else {
        throw new Exception('Failed to create download package');
    }
} catch (\Throwable $e) {
    // Catches both Exception AND Error (TypeError, ValueError, etc.) — PHP 8 safety
    error_log('generate.php error: ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    echo json_encode([
        'success' => false,
        'message' => 'Error generating project: ' . $e->getMessage()
    ]);
}

/**
 * Generate Android Project Files with Splash Screen
 */
function generateAndroidProject($buildDir, $config) {
    $packagePath = str_replace('.', '/', $config['package_name']);
    
    // Create directory structure
    $dirs = [
        'app/src/main/java/' . $packagePath,
        'app/src/main/res/layout',
        'app/src/main/res/values',
        'app/src/main/res/values-night',
        'app/src/main/res/drawable',
        'app/src/main/res/drawable-v24',
        'app/src/main/res/mipmap-hdpi',
        'app/src/main/res/mipmap-mdpi',
        'app/src/main/res/mipmap-xhdpi',
        'app/src/main/res/mipmap-xxhdpi',
        'app/src/main/res/mipmap-xxxhdpi',
        'app/src/main/res/mipmap-anydpi-v26',
        'gradle/wrapper',
    ];
    
    foreach ($dirs as $dir) {
        mkdir($buildDir . $dir, 0777, true);
    }
    
    // Generate AndroidManifest.xml
    $orientationValue = match($config['orientation']) {
        'landscape' => 'landscape',
        'portrait' => 'portrait',
        default => 'unspecified'
    };
    
    $fcmServiceXml = $config['push_notifications'] ? '
        <service
            android:name=".BPFirebaseMessagingService"
            android:exported="false">
            <intent-filter>
                <action android:name="com.google.firebase.MESSAGING_EVENT" />
            </intent-filter>
        </service>' : '';

    $splashActivity = $config['enable_splash'] ? 
        '<activity
            android:name=".SplashActivity"
            android:theme="@style/SplashTheme"
            android:exported="true">
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>
        </activity>
        
        <activity
            android:name=".MainActivity"
            android:screenOrientation="' . $orientationValue . '"
            android:configChanges="orientation|screenSize|keyboard|keyboardHidden"
            android:exported="' . (!empty($config['url_scheme']) || !empty($config['app_links_domain']) ? 'true' : 'false') . '"' . (!empty($config['url_scheme']) || !empty($config['app_links_domain']) ? '>
            ' . (!empty($config['url_scheme']) ? '<intent-filter>
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="' . $config['url_scheme'] . '" />
            </intent-filter>' : '') . '
            ' . (!empty($config['app_links_domain']) ? '<intent-filter android:autoVerify="true">
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="https" android:host="' . htmlspecialchars($config['app_links_domain']) . '" />
            </intent-filter>' : '') . '
        </activity>' : ' />') :
        '<activity
            android:name=".MainActivity"
            android:screenOrientation="' . $orientationValue . '"
            android:configChanges="orientation|screenSize|keyboard|keyboardHidden"
            android:exported="true">
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>' . (!empty($config['url_scheme']) ? '
            <intent-filter>
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="' . $config['url_scheme'] . '" />
            </intent-filter>' : '') . (!empty($config['app_links_domain']) ? '
            <intent-filter android:autoVerify="true">
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="https" android:host="' . htmlspecialchars($config['app_links_domain']) . '" />
            </intent-filter>' : '') . '
        </activity>';
    
    $manifest = '<?xml version="1.0" encoding="utf-8"?>
<manifest xmlns:android="http://schemas.android.com/apk/res/android"
    package="' . $config['package_name'] . '">

    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    <uses-permission android:name="android.permission.ACCESS_WIFI_STATE" />
    <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" android:maxSdkVersion="28" />
    <uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" android:maxSdkVersion="32" />
    <uses-permission android:name="android.permission.READ_MEDIA_IMAGES" />
    <uses-permission android:name="android.permission.READ_MEDIA_VIDEO" />
    <uses-permission android:name="android.permission.READ_MEDIA_AUDIO" />
    <uses-permission android:name="android.permission.VIBRATE" />' . ($config['keep_screen_on'] ? '
    <uses-permission android:name="android.permission.WAKE_LOCK" />' : '') . ($config['push_notifications'] ? '
    <uses-permission android:name="android.permission.POST_NOTIFICATIONS" />
    <uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED" />' : '') . ($config['camera_permission'] ? '
    <uses-permission android:name="android.permission.CAMERA" />
    <uses-feature android:name="android.hardware.camera" android:required="false" />
    <uses-feature android:name="android.hardware.camera.autofocus" android:required="false" />' : '') . ($config['location_permission'] ? '
    <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
    <uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />' : '') . ($config['microphone_permission'] ? '
    <uses-permission android:name="android.permission.RECORD_AUDIO" />
    <uses-permission android:name="android.permission.MODIFY_AUDIO_SETTINGS" />' : '') . ($config['phone_state_permission'] ? '
    <uses-permission android:name="android.permission.READ_PHONE_STATE" />' : '') . ($config['contacts_permission'] ? '
    <uses-permission android:name="android.permission.READ_CONTACTS" />
    <uses-permission android:name="android.permission.WRITE_CONTACTS" />' : '') . ($config['calendar_permission'] ? '
    <uses-permission android:name="android.permission.READ_CALENDAR" />
    <uses-permission android:name="android.permission.WRITE_CALENDAR" />' : '') . ($config['bluetooth_permission'] ? '
    <uses-permission android:name="android.permission.BLUETOOTH" />
    <uses-permission android:name="android.permission.BLUETOOTH_ADMIN" />
    <uses-permission android:name="android.permission.BLUETOOTH_CONNECT" />
    <uses-permission android:name="android.permission.BLUETOOTH_SCAN" />' : '') . ($config['biometric_permission'] ? '
    <uses-permission android:name="android.permission.USE_BIOMETRIC" />
    <uses-permission android:name="android.permission.USE_FINGERPRINT" />' : '') . ($config['sms_permission'] ? '
    <uses-permission android:name="android.permission.SEND_SMS" />
    <uses-permission android:name="android.permission.READ_SMS" />
    <uses-permission android:name="android.permission.RECEIVE_SMS" />' : '') . ($config['call_phone_permission'] ? '
    <uses-permission android:name="android.permission.CALL_PHONE" />' : '') . ($config['call_log_permission'] ? '
    <uses-permission android:name="android.permission.READ_CALL_LOG" />
    <uses-permission android:name="android.permission.WRITE_CALL_LOG" />' : '') . ($config['storage_permission'] ? '
    <uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" android:maxSdkVersion="32" />
    <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" android:maxSdkVersion="29" />
    <uses-permission android:name="android.permission.READ_MEDIA_IMAGES" />
    <uses-permission android:name="android.permission.READ_MEDIA_VIDEO" />
    <uses-permission android:name="android.permission.READ_MEDIA_AUDIO" />' : '') . ($config['nfc_permission'] ? '
    <uses-permission android:name="android.permission.NFC" />
    <uses-feature android:name="android.hardware.nfc" android:required="false" />' : '') . ($config['body_sensors_permission'] ? '
    <uses-permission android:name="android.permission.BODY_SENSORS" />' : '') . ($config['foreground_service_permission'] ? '
    <uses-permission android:name="android.permission.FOREGROUND_SERVICE" />
    <uses-permission android:name="android.permission.FOREGROUND_SERVICE_DATA_SYNC" />
    <uses-permission android:name="android.permission.REQUEST_IGNORE_BATTERY_OPTIMIZATIONS" />' : '') . ($config['notification_permission'] ? '
    <uses-permission android:name="android.permission.POST_NOTIFICATIONS" />' : '') . '

    <!-- Required for Android 11+ intent visibility -->
    <queries>
        <intent>
            <action android:name="android.intent.action.VIEW" />
            <data android:scheme="https" />
        </intent>
        <intent>
            <action android:name="android.intent.action.VIEW" />
            <data android:scheme="http" />
        </intent>
        <intent>
            <action android:name="android.intent.action.SEND" />
            <data android:mimeType="text/plain" />
        </intent>
        <intent>
            <action android:name="android.intent.action.DIAL" />
            <data android:scheme="tel" />
        </intent>
        <intent>
            <action android:name="android.intent.action.SENDTO" />
            <data android:scheme="mailto" />
        </intent>' . ($config['sms_permission'] || $config['fab_enabled'] ? '
        <intent>
            <action android:name="android.intent.action.VIEW" />
            <data android:scheme="sms" />
        </intent>' : '') . '
        <intent>
            <action android:name="android.intent.action.VIEW" />
            <data android:scheme="market" />
        </intent>
    </queries>

    <application
        android:allowBackup="true"
        android:icon="@mipmap/ic_launcher"
        android:label="' . htmlspecialchars($config['app_name']) . '"
        android:roundIcon="@mipmap/ic_launcher_round"
        android:supportsRtl="true"
        android:theme="@style/AppTheme"
        android:hardwareAccelerated="' . ($config['hardware_acceleration'] ? 'true' : 'false') . '"
        android:resizeableActivity="' . ($config['multi_window'] ? 'true' : 'false') . '"
        android:usesCleartextTraffic="true"
        android:networkSecurityConfig="@xml/network_security_config"
        android:dataExtractionRules="@xml/data_extraction_rules"
        android:fullBackupContent="@xml/backup_rules">' . (!empty($config['privacy_policy_url']) ? '
        
        <meta-data android:name="privacy_policy_url" android:value="' . htmlspecialchars($config['privacy_policy_url']) . '" />' : '') . ($config['admob_enabled'] && !empty($config['admob_app_id']) ? '
        
        <meta-data android:name="com.google.android.gms.ads.APPLICATION_ID" android:value="' . htmlspecialchars($config['admob_app_id']) . '" />' : '') . '
        
        ' . $splashActivity . '
        
        ' . $fcmServiceXml . '
        
    </application>

</manifest>';
    
    file_put_contents($buildDir . 'app/src/main/AndroidManifest.xml', $manifest);
    
    // Create network security config
    mkdir($buildDir . 'app/src/main/res/xml', 0777, true);
    $networkConfig = '<?xml version="1.0" encoding="utf-8"?>
<network-security-config>
    <base-config cleartextTrafficPermitted="true">
        <trust-anchors>
            <certificates src="system" />
        </trust-anchors>
    </base-config>
</network-security-config>';
    file_put_contents($buildDir . 'app/src/main/res/xml/network_security_config.xml', $networkConfig);
    
    // Create data_extraction_rules.xml (Android 12+ backup rules)
    $dataExtractionRules = '<?xml version="1.0" encoding="utf-8"?>
<data-extraction-rules>
    <cloud-backup>
        <exclude domain="root" />
        <exclude domain="file" />
        <exclude domain="database" />
        <exclude domain="sharedpref" />
        <exclude domain="external" />
    </cloud-backup>
    <device-transfer>
        <exclude domain="root" />
        <exclude domain="file" />
        <exclude domain="database" />
        <exclude domain="sharedpref" />
        <exclude domain="external" />
    </device-transfer>
</data-extraction-rules>';
    file_put_contents($buildDir . 'app/src/main/res/xml/data_extraction_rules.xml', $dataExtractionRules);
    
    // Create backup_rules.xml (Android 11 and below backup rules)
    $backupRules = '<?xml version="1.0" encoding="utf-8"?>
<full-backup-content>
    <exclude domain="sharedpref" path="." />
    <exclude domain="database" path="." />
    <exclude domain="external" path="." />
</full-backup-content>';
    file_put_contents($buildDir . 'app/src/main/res/xml/backup_rules.xml', $backupRules);
    
    // Generate SplashActivity.java (if enabled)
    if ($config['enable_splash']) {
        $splashDurationMs = $config['splash_duration'] * 1000;
        
        $splashActivity = 'package ' . $config['package_name'] . ';

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.view.WindowManager;

public class SplashActivity extends Activity {

    private static final int SPLASH_DURATION = ' . $splashDurationMs . ';

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        // Make fullscreen
        getWindow().setFlags(
            WindowManager.LayoutParams.FLAG_FULLSCREEN,
            WindowManager.LayoutParams.FLAG_FULLSCREEN
        );
        
        setContentView(R.layout.activity_splash);

        new Handler(Looper.getMainLooper()).postDelayed(new Runnable() {
            @Override
            public void run() {
                Intent intent = new Intent(SplashActivity.this, MainActivity.class);
                startActivity(intent);
                finish();
                overridePendingTransition(android.R.anim.fade_in, android.R.anim.fade_out);
            }
        }, SPLASH_DURATION);
    }
}';
        
        file_put_contents($buildDir . 'app/src/main/java/' . $packagePath . '/SplashActivity.java', $splashActivity);
        
        // Generate splash layout
        $splashLayout = '<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:background="' . $config['splash_color'] . '">

    <ImageView
        android:id="@+id/splash_icon"
        android:layout_width="150dp"
        android:layout_height="150dp"
        android:layout_centerInParent="true"
        android:src="@drawable/splash_logo"
        android:contentDescription="@string/app_name" />

    <TextView
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_below="@id/splash_icon"
        android:layout_centerHorizontal="true"
        android:layout_marginTop="24dp"
        android:text="@string/app_name"
        android:textColor="#FFFFFF"
        android:textSize="24sp"
        android:textStyle="bold" />

    <ProgressBar
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_alignParentBottom="true"
        android:layout_centerHorizontal="true"
        android:layout_marginBottom="50dp"
        android:indeterminateTint="#FFFFFF" />

</RelativeLayout>';
        
        file_put_contents($buildDir . 'app/src/main/res/layout/activity_splash.xml', $splashLayout);
    }
    
    // Generate MainActivity.java
    $fullscreenCode = $config['fullscreen'] ? 
        'getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);' : '';
    
    $backButtonCode = $config['back_button'] ? 
        ($config['exit_confirmation'] ? '@Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            if (webView.canGoBack()) {
                webView.goBack();
                return true;
            } else {
                if (backPressedTime + 2000 > System.currentTimeMillis()) {
                    finish();
                } else {
                    android.widget.Toast.makeText(this, "Press back again to exit", android.widget.Toast.LENGTH_SHORT).show();
                    backPressedTime = System.currentTimeMillis();
                }
                return true;
            }
        }
        return super.onKeyDown(keyCode, event);
    }' : '@Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK && webView.canGoBack()) {
            webView.goBack();
            return true;
        }
        return super.onKeyDown(keyCode, event);
    }') : ($config['exit_confirmation'] ? '@Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            if (backPressedTime + 2000 > System.currentTimeMillis()) {
                finish();
            } else {
                android.widget.Toast.makeText(this, "Press back again to exit", android.widget.Toast.LENGTH_SHORT).show();
                backPressedTime = System.currentTimeMillis();
            }
            return true;
        }
        return super.onKeyDown(keyCode, event);
    }' : '');
    
    $downloadCode = $config['file_downloads'] ? 
        'webView.setDownloadListener(new DownloadListener() {
            @Override
            public void onDownloadStart(String url, String userAgent, String contentDisposition, String mimeType, long contentLength) {
                DownloadManager.Request request = new DownloadManager.Request(Uri.parse(url));
                request.setMimeType(mimeType);
                String cookies = CookieManager.getInstance().getCookie(url);
                request.addRequestHeader("cookie", cookies);
                request.addRequestHeader("User-Agent", userAgent);
                request.setDescription("Downloading file...");
                request.setTitle(URLUtil.guessFileName(url, contentDisposition, mimeType));
                request.allowScanningByMediaScanner();
                request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);
                request.setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, URLUtil.guessFileName(url, contentDisposition, mimeType));
                DownloadManager dm = (DownloadManager) getSystemService(DOWNLOAD_SERVICE);
                dm.enqueue(request);
                Toast.makeText(getApplicationContext(), "Downloading File", Toast.LENGTH_LONG).show();
            }
        });' : '';
    
    $downloadImports = $config['file_downloads'] ? 
        'import android.app.DownloadManager;
import android.net.Uri;
import android.os.Environment;
import android.webkit.CookieManager;
import android.webkit.DownloadListener;
import android.webkit.URLUtil;
import android.widget.Toast;' : '';

    $pushImports = $config['push_notifications'] ? 
        'import android.Manifest;
import android.content.pm.PackageManager;
import android.content.SharedPreferences;
import android.os.Build;
import android.widget.Toast;
import android.webkit.CookieManager;
import android.webkit.GeolocationPermissions;
import android.webkit.PermissionRequest;
import android.webkit.ServiceWorkerClient;
import android.webkit.ServiceWorkerController;
import android.webkit.WebResourceRequest;
import android.webkit.WebResourceResponse;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.work.ExistingPeriodicWorkPolicy;
import androidx.work.PeriodicWorkRequest;
import androidx.work.WorkManager;
import java.util.concurrent.TimeUnit;' : (($config['camera_permission'] || $config['location_permission'] || $config['microphone_permission'] || $config['contacts_permission'] || $config['calendar_permission'] || $config['bluetooth_permission'] || $config['biometric_permission'] || $config['phone_state_permission'] || $config['sms_permission'] || $config['call_phone_permission'] || $config['call_log_permission'] || $config['body_sensors_permission'] || $config['notification_permission'] || $config['storage_permission']) ? 
        'import android.Manifest;
import android.content.pm.PackageManager;
import android.os.Build;
import android.webkit.PermissionRequest;
import android.webkit.GeolocationPermissions;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;' : '');

    $pushOnCreate = $config['push_notifications'] ? '
        // Enable Service Worker support (API 24+)
        if (android.os.Build.VERSION.SDK_INT >= 24) {
            ServiceWorkerController swController = ServiceWorkerController.getInstance();
            swController.setServiceWorkerClient(new ServiceWorkerClient() {
                @Override
                public WebResourceResponse shouldInterceptRequest(WebResourceRequest request) {
                    return null;
                }
            });
        }

        // Request POST_NOTIFICATIONS permission on Android 13+
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            if (ContextCompat.checkSelfPermission(this, Manifest.permission.POST_NOTIFICATIONS)
                    != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(this,
                    new String[]{Manifest.permission.POST_NOTIFICATIONS}, 1001);
            }
        }

        // Explicitly fetch and send FCM token at app startup
        try {
            com.google.firebase.messaging.FirebaseMessaging.getInstance().getToken()
                .addOnCompleteListener(task -> {
                    if (task.isSuccessful() && task.getResult() != null) {
                        final String fcmToken = task.getResult();
                        android.util.Log.d("FCM_TOKEN", "Token received: " + fcmToken);

                        // Show token status via JavaScript in WebView (debug)
                        runOnUiThread(() -> {
                            if (webView != null) {
                                webView.evaluateJavascript(
                                    "window.FCM_TOKEN = \'" + fcmToken + "\'; window.dispatchEvent(new Event(\'fcm_token_ready\')); console.log(\'FCM Token set\');", null);
                            }
                        });

                        new Thread(() -> {
                            try {
                                String baseUrl = WEBSITE_URL == null ? "" : WEBSITE_URL.trim();
                                if (!baseUrl.startsWith("http://") && !baseUrl.startsWith("https://")) {
                                    baseUrl = "https://" + baseUrl;
                                }
                                // Extract origin (protocol + host) to avoid path issues
                                java.net.URL parsedUrl = new java.net.URL(baseUrl);
                                String origin = parsedUrl.getProtocol() + "://" + parsedUrl.getHost();
                                if (parsedUrl.getPort() > 0 && parsedUrl.getPort() != 443 && parsedUrl.getPort() != 80) {
                                    origin += ":" + parsedUrl.getPort();
                                }
                                String endpoint = origin + "/admin/fcm_token.php";
                                android.util.Log.d("FCM_TOKEN", "Sending token to: " + endpoint);

                                java.net.URL tokenUrl = new java.net.URL(endpoint);
                                java.net.HttpURLConnection conn = (java.net.HttpURLConnection) tokenUrl.openConnection();
                                conn.setRequestMethod("POST");
                                conn.setRequestProperty("Content-Type", "application/json");
                                conn.setRequestProperty("User-Agent", "BPWalletApp/1.0");
                                conn.setDoOutput(true);
                                conn.setConnectTimeout(15000);
                                conn.setReadTimeout(15000);
                                String body = "{\"token\":\"" + fcmToken + "\"}";
                                try (java.io.OutputStream os = conn.getOutputStream()) {
                                    os.write(body.getBytes(java.nio.charset.StandardCharsets.UTF_8));
                                }
                                int status = conn.getResponseCode();
                                android.util.Log.d("FCM_TOKEN", "POST response: HTTP " + status);
                                conn.disconnect();

                                // Fallback: GET request if POST fails
                                if (status < 200 || status >= 300) {
                                    android.util.Log.d("FCM_TOKEN", "POST failed, trying GET fallback...");
                                    String t = java.net.URLEncoder.encode(fcmToken, "UTF-8");
                                    java.net.URL fallbackUrl = new java.net.URL(origin + "/admin/fcm_token.php?t=" + t);
                                    java.net.HttpURLConnection fallbackConn = (java.net.HttpURLConnection) fallbackUrl.openConnection();
                                    fallbackConn.setRequestMethod("GET");
                                    fallbackConn.setRequestProperty("User-Agent", "BPWalletApp/1.0");
                                    fallbackConn.setConnectTimeout(15000);
                                    fallbackConn.setReadTimeout(15000);
                                    int fbStatus = fallbackConn.getResponseCode();
                                    android.util.Log.d("FCM_TOKEN", "GET fallback response: HTTP " + fbStatus);
                                    fallbackConn.disconnect();
                                }
                            } catch (Exception e) {
                                android.util.Log.e("FCM_TOKEN", "Error sending token: " + e.getMessage(), e);
                            }
                        }).start();
                    } else {
                        android.util.Log.e("FCM_TOKEN", "Failed to get token: " + (task.getException() != null ? task.getException().getMessage() : "unknown error"));
                        // Show error as Toast so user can see
                        runOnUiThread(() -> {
                            String errMsg = task.getException() != null ? task.getException().getMessage() : "Unknown FCM error";
                            Toast.makeText(MainActivity.this, "FCM Error: " + errMsg, Toast.LENGTH_LONG).show();
                        });
                    }
                });
        } catch (Exception e) {
            android.util.Log.e("FCM_TOKEN", "Firebase init error: " + e.getMessage(), e);
            Toast.makeText(this, "Firebase Error: " + e.getMessage(), Toast.LENGTH_LONG).show();
        }

        // Schedule background notification polling every 15 minutes
        PeriodicWorkRequest workRequest = new PeriodicWorkRequest.Builder(
            NotificationWorker.class, 15, TimeUnit.MINUTES)
            .build();
        WorkManager.getInstance(this).enqueueUniquePeriodicWork(
            "notification_check",
            ExistingPeriodicWorkPolicy.KEEP,
            workRequest);' : '';

    // Runtime permission requests for non-push features
    $permissionsOnCreate = '';
    $runtimePermissions = [];
    // Always gather runtime permissions regardless of push notification state
    if ($config['camera_permission'])       $runtimePermissions[] = 'Manifest.permission.CAMERA';
    if ($config['microphone_permission'])   $runtimePermissions[] = 'Manifest.permission.RECORD_AUDIO';
    if ($config['location_permission'])     $runtimePermissions[] = 'Manifest.permission.ACCESS_FINE_LOCATION';
    if ($config['contacts_permission'])     { $runtimePermissions[] = 'Manifest.permission.READ_CONTACTS'; $runtimePermissions[] = 'Manifest.permission.WRITE_CONTACTS'; }
    if ($config['calendar_permission'])     { $runtimePermissions[] = 'Manifest.permission.READ_CALENDAR'; $runtimePermissions[] = 'Manifest.permission.WRITE_CALENDAR'; }
    if ($config['phone_state_permission'])  $runtimePermissions[] = 'Manifest.permission.READ_PHONE_STATE';
    if ($config['bluetooth_permission'])    $runtimePermissions[] = 'Manifest.permission.BLUETOOTH_CONNECT';
    if ($config['biometric_permission'])    $runtimePermissions[] = 'Manifest.permission.USE_BIOMETRIC';
    if ($config['sms_permission'])          { $runtimePermissions[] = 'Manifest.permission.SEND_SMS'; $runtimePermissions[] = 'Manifest.permission.READ_SMS'; }
    if ($config['call_phone_permission'])   $runtimePermissions[] = 'Manifest.permission.CALL_PHONE';
    if ($config['call_log_permission'])     { $runtimePermissions[] = 'Manifest.permission.READ_CALL_LOG'; $runtimePermissions[] = 'Manifest.permission.WRITE_CALL_LOG'; }
    if ($config['body_sensors_permission']) $runtimePermissions[] = 'Manifest.permission.BODY_SENSORS';
    if ($config['storage_permission']) {
        $runtimePermissions[] = 'Manifest.permission.READ_EXTERNAL_STORAGE';
    }
    if (!$config['push_notifications'] && $config['notification_permission']) $runtimePermissions[] = 'Manifest.permission.POST_NOTIFICATIONS';
    if (!empty($runtimePermissions)) {
        $permissionsArray = 'new String[]{' . implode(', ', $runtimePermissions) . '}';
        $permissionsOnCreate = '
        // Request runtime permissions
        java.util.List<String> permissionsNeeded = new java.util.ArrayList<>();
        String[] requiredPerms = ' . $permissionsArray . ';
        for (String perm : requiredPerms) {
            if (ContextCompat.checkSelfPermission(this, perm) != PackageManager.PERMISSION_GRANTED) {
                permissionsNeeded.add(perm);
            }
        }
        if (!permissionsNeeded.isEmpty()) {
            ActivityCompat.requestPermissions(this, permissionsNeeded.toArray(new String[0]), 2001);
        }';
    }

    $pushChromeClient = $config['push_notifications'] ? '
            @Override
            public void onPermissionRequest(PermissionRequest request) {
                request.grant(request.getResources());
            }' : ($config['camera_permission'] || $config['location_permission'] || $config['microphone_permission'] ? '
            @Override
            public void onPermissionRequest(PermissionRequest request) {
                request.grant(request.getResources());
            }' : '');

    $mainActivity = 'package ' . $config['package_name'] . ';

import android.app.Activity;
import android.os.Bundle;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.view.KeyEvent;
import android.view.WindowManager;
import android.view.View;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.os.Build;
import android.widget.ProgressBar;
' . $downloadImports . '
' . $pushImports . ($config['pull_to_refresh'] ? '
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;' : '') . (($config['share_button'] || !empty($config['toolbar_items']) || !empty($config['privacy_policy_url'])) ? '
import android.view.Menu;
import android.view.MenuItem;' : '') . ($config['rate_app_launches'] > 0 ? '
import android.app.AlertDialog;' : '') . (($config['rate_app_launches'] > 0 || $config['fab_enabled']) ? '
import android.net.Uri;' : '') . ($config['fab_enabled'] ? '
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.view.Gravity;' : '') . ($config['js_bridge'] && !$config['file_downloads'] && !$config['push_notifications'] ? '
import android.widget.Toast;' : '') . ($config['file_upload_camera'] ? '
import android.webkit.ValueCallback;
import android.provider.MediaStore;
import android.os.Environment;
import android.content.ContentValues;
import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;' : '') . ($config['admob_enabled'] ? '
import com.google.android.gms.ads.MobileAds;
import com.google.android.gms.ads.AdRequest;
import com.google.android.gms.ads.AdView;
import com.google.android.gms.ads.AdSize;
import com.google.android.gms.ads.interstitial.InterstitialAd;
import com.google.android.gms.ads.interstitial.InterstitialAdLoadCallback;
import com.google.android.gms.ads.LoadAdError;
import com.google.android.gms.ads.FullScreenContentCallback;' : '') . ($config['force_dark'] ? '
import androidx.webkit.WebSettingsCompat;
import androidx.webkit.WebViewFeature;' : '') . ($config['swipe_gestures'] ? '
import android.view.GestureDetector;
import android.view.MotionEvent;' : '') . ($config['app_lock'] ? '
import androidx.biometric.BiometricPrompt;
import androidx.biometric.BiometricManager;
import java.util.concurrent.Executor;' : '') . ($config['update_checker'] && !empty($config['update_check_url']) ? '
import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;' : '') . ($config['nav_drawer'] && !empty($config['drawer_items']) ? '
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.ScrollView;' : '') . ($config['bottom_nav'] && !empty($config['bnav_items']) ? '
import android.widget.LinearLayout;
import android.widget.TextView;' : '') . '

public class MainActivity extends ' . ($config['file_upload_camera'] || $config['app_lock'] ? 'androidx.appcompat.app.AppCompatActivity' : 'Activity') . ' {

    private WebView webView;
    private ProgressBar progressBar;' . (in_array($config['loading_style'], ['circular', 'both']) ? '
    private ProgressBar progressBarCircular;' : '') . '
    private static final String WEBSITE_URL = "' . $config['website_url'] . '";' . ($config['pull_to_refresh'] ? '
    private SwipeRefreshLayout swipeRefresh;' : '') . ($config['file_upload_camera'] ? '
    private ValueCallback<Uri[]> fileUploadCallback;
    private Uri cameraImageUri;' : '') . ($config['admob_enabled'] ? '
    private InterstitialAd mInterstitialAd;
    private int pageLoadCount = 0;' : '') . ($config['exit_confirmation'] ? '
    private long backPressedTime = 0;' : '') . ($config['swipe_gestures'] ? '
    private GestureDetector gestureDetector;' : '') . ($config['nav_drawer'] && !empty($config['drawer_items']) ? '
    private android.widget.FrameLayout drawerOverlay;
    private LinearLayout drawerPanel;
    private boolean drawerOpen = false;' : '') . '

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        ' . $fullscreenCode . ($config['prevent_screenshots'] ? '
        getWindow().setFlags(android.view.WindowManager.LayoutParams.FLAG_SECURE, android.view.WindowManager.LayoutParams.FLAG_SECURE);' : '') . ($config['keep_screen_on'] ? '
        getWindow().addFlags(android.view.WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);' : '') . ($config['immersive_mode'] ? '
        // Immersive sticky mode - hide navigation bar
        getWindow().getDecorView().setSystemUiVisibility(
            View.SYSTEM_UI_FLAG_LAYOUT_STABLE
            | View.SYSTEM_UI_FLAG_LAYOUT_HIDE_NAVIGATION
            | View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN
            | View.SYSTEM_UI_FLAG_HIDE_NAVIGATION
            | View.SYSTEM_UI_FLAG_FULLSCREEN
            | View.SYSTEM_UI_FLAG_IMMERSIVE_STICKY);' : '') . ($config['fullscreen'] ? '' : '
        // Set status bar color
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            getWindow().setStatusBarColor(Color.parseColor("' . $config['status_bar_color'] . '"));
        }') . '
        setContentView(R.layout.activity_main);

        webView = findViewById(R.id.webView);
        progressBar = findViewById(R.id.progressBar);' . (in_array($config['loading_style'], ['circular', 'both']) ? '
        progressBarCircular = findViewById(R.id.progressBarCircular);' : '') . ($config['pull_to_refresh'] ? '
        swipeRefresh = findViewById(R.id.swipeRefresh);
        swipeRefresh.setColorSchemeColors(Color.parseColor("' . $config['loading_bar_color'] . '"));
        swipeRefresh.setOnRefreshListener(() -> {
            webView.reload();
        });' : '') . ($config['rate_app_launches'] > 0 ? '
        checkAndShowRateDialog(' . $config['rate_app_launches'] . ');' : '') . ($config['clear_cache'] ? '
        // Clear cache on start
        webView.clearCache(true);
        webView.clearHistory();' : '') . ($config['admob_enabled'] ? '
        // Initialize AdMob
        MobileAds.initialize(this, initStatus -> {});' . (!empty($config['admob_banner_id']) ? '
        // Setup Banner Ad
        AdView bannerAd = findViewById(R.id.adView);
        if (bannerAd != null) {
            AdRequest adRequest = new AdRequest.Builder().build();
            bannerAd.loadAd(adRequest);
        }' : '') . (!empty($config['admob_interstitial_id']) ? '
        loadInterstitialAd();' : '') : '') . '
        
        setupWebView();
        ' . $pushOnCreate . '
        ' . $permissionsOnCreate . ($config['js_bridge'] ? '
        // Add JavaScript Bridge
        webView.addJavascriptInterface(new NativeBridge(), "AndroidBridge");' : '') . ($config['fab_enabled'] && !empty($config['fab_value']) ? '
        setupFAB();' : '') . ($config['swipe_gestures'] ? '
        // Swipe gesture detector for back/forward
        gestureDetector = new GestureDetector(this, new GestureDetector.SimpleOnGestureListener() {
            private static final int SWIPE_THRESHOLD = 100;
            private static final int SWIPE_VELOCITY = 100;
            @Override
            public boolean onFling(MotionEvent e1, MotionEvent e2, float vX, float vY) {
                float dX = e2.getX() - e1.getX();
                if (Math.abs(dX) > Math.abs(e2.getY() - e1.getY()) && Math.abs(dX) > SWIPE_THRESHOLD && Math.abs(vX) > SWIPE_VELOCITY) {
                    if (dX > 0 && webView.canGoBack()) { webView.goBack(); return true; }
                    else if (dX < 0 && webView.canGoForward()) { webView.goForward(); return true; }
                }
                return false;
            }
        });
        webView.setOnTouchListener((v, event) -> { gestureDetector.onTouchEvent(event); return false; });' : '') . ($config['nav_drawer'] && !empty($config['drawer_items']) ? '
        setupNavDrawer();' : '') . ($config['bottom_nav'] && !empty($config['bnav_items']) ? '
        setupBottomNav();' : '') . ($config['gdpr_consent'] ? '
        showGdprConsentIfNeeded();' : '') . ($config['update_checker'] && !empty($config['update_check_url']) ? '
        checkForUpdate();' : '') . ($config['app_lock'] ? '
        authenticateUser();' : '') . '
        
        // Handle deep link intent
        handleIntent(getIntent());
        
        if (isNetworkAvailable()) {
            webView.loadUrl(WEBSITE_URL);
        } else {
            webView.loadData(
                "' . (!empty($config['offline_page_html']) ? addslashes($config['offline_page_html']) : '<html><body style=\"display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;background:#1e293b;color:white;margin:0;\"><div style=\"text-align:center;\"><div style=\"font-size:64px;margin-bottom:20px;\">📶</div><h2 style=\"margin:0 0 10px;\">No Internet Connection</h2><p style=\"color:#94a3b8;margin:0 0 20px;\">Please check your connection and try again</p><button onclick=\"window.location.reload()\" style=\"padding:12px 24px;background:#6366f1;color:white;border:none;border-radius:8px;font-size:16px;cursor:pointer;\">Retry</button></div></body></html>') . '"' . ',
                "text/html", "UTF-8"
            );
        }
    }' . ($config['rate_app_launches'] > 0 ? '

    private void checkAndShowRateDialog(int targetLaunches) {
        android.content.SharedPreferences prefs = getSharedPreferences("app_prefs", MODE_PRIVATE);
        boolean ratedAlready = prefs.getBoolean("rated", false);
        if (ratedAlready) return;
        int launches = prefs.getInt("launch_count", 0) + 1;
        prefs.edit().putInt("launch_count", launches).apply();
        if (launches == targetLaunches) {
            new AlertDialog.Builder(this)
                .setTitle("Enjoying the app?")
                .setMessage("If you like the app, please take a moment to rate it. It won\'t take more than a minute. Thanks!")
                .setPositiveButton("Rate Now", (d, w) -> {
                    prefs.edit().putBoolean("rated", true).apply();
                    try {
                        startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=" + getPackageName())));
                    } catch (android.content.ActivityNotFoundException e) {
                        startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=" + getPackageName())));
                    }
                })
                .setNeutralButton("Remind Later", null)
                .setNegativeButton("No Thanks", (d, w) -> prefs.edit().putBoolean("rated", true).apply())
                .show();
        }
    }' : '') . '

    private void setupWebView() {
        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setDomStorageEnabled(true);
        webSettings.setLoadWithOverviewMode(true);
        webSettings.setUseWideViewPort(true);
        webSettings.setBuiltInZoomControls(' . ($config['zoom_controls'] ? 'true' : 'false') . ');
        webSettings.setDisplayZoomControls(false);
        webSettings.setSupportZoom(' . ($config['zoom_controls'] ? 'true' : 'false') . ');
        webSettings.setDefaultTextEncodingName("utf-8");
        webSettings.setAllowFileAccess(true);
        webSettings.setAllowContentAccess(true);
        webSettings.setLoadsImagesAutomatically(true);
        webSettings.setMixedContentMode(WebSettings.MIXED_CONTENT_ALWAYS_ALLOW);
        webSettings.setCacheMode(WebSettings.LOAD_DEFAULT);
        webSettings.setDatabaseEnabled(true);' . ($config['location_permission'] ? '
        webSettings.setGeolocationEnabled(true);' : '') . (!empty($config['custom_user_agent']) ? '
        webSettings.setUserAgentString("' . addslashes($config['custom_user_agent']) . '");' : '') . ($config['text_size'] !== 'NORMAL' ? '
        webSettings.setTextSize(WebSettings.TextSize.' . $config['text_size'] . ');' : '') . ($config['third_party_cookies'] ? '
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            android.webkit.CookieManager.getInstance().setAcceptThirdPartyCookies(webView, true);
        }' : '') . ($config['force_dark'] ? '
        // Force dark mode following system setting
        if (WebViewFeature.isFeatureSupported(WebViewFeature.FORCE_DARK)) {
            WebSettingsCompat.setForceDark(webView.getSettings(), WebSettingsCompat.FORCE_DARK_AUTO);
        }
        if (WebViewFeature.isFeatureSupported(WebViewFeature.FORCE_DARK_STRATEGY)) {
            WebSettingsCompat.setForceDarkStrategy(webView.getSettings(), WebSettingsCompat.DARK_STRATEGY_PREFER_WEB_THEME_OVER_USER_AGENT_DARKENING);
        }' : '') . '

        webView.setWebViewClient(new WebViewClient() {
            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                super.onPageStarted(view, url, favicon);
                progressBar.setVisibility(View.VISIBLE);' . (in_array($config['loading_style'], ['circular', 'both']) ? '
                if (progressBarCircular != null) progressBarCircular.setVisibility(View.VISIBLE);' : '') . '
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                progressBar.setVisibility(View.GONE);' . (in_array($config['loading_style'], ['circular', 'both']) ? '
                if (progressBarCircular != null) progressBarCircular.setVisibility(View.GONE);' : '') . ($config['pull_to_refresh'] ? '
                swipeRefresh.setRefreshing(false);' : '') . ($config['push_notifications'] ? '
                // Save cookies for background notification worker
                String cookies = CookieManager.getInstance().getCookie(WEBSITE_URL);
                if (cookies != null && !cookies.isEmpty()) {
                    getSharedPreferences("bp_prefs", MODE_PRIVATE)
                        .edit()
                        .putString("session_cookies", cookies)
                        .putString("website_url", WEBSITE_URL)
                        .apply();
                }' : '') . (!empty($config['js_injection']) ? '
                // Custom JavaScript injection
                view.evaluateJavascript("' . addslashes(str_replace("\n", ' ', $config['js_injection'])) . '", null);' : '') . (!empty($config['css_injection']) ? '
                // Custom CSS injection via Base64 to avoid quote escaping issues
                String cssStr = "' . addslashes(str_replace(["\n", "\r"], [' ', ''], $config['css_injection'])) . '";
                String b64Css = android.util.Base64.encodeToString(cssStr.getBytes(), android.util.Base64.NO_WRAP);
                view.evaluateJavascript("(function(){var s=document.createElement(\'style\');s.textContent=atob(\'" + b64Css + "\');document.head.appendChild(s);})()", null);' : '') . ($config['admob_enabled'] && !empty($config['admob_interstitial_id']) && $config['admob_interstitial_interval'] > 0 ? '
                // Show interstitial ad every N pages
                pageLoadCount++;
                if (pageLoadCount > 1 && pageLoadCount % ' . $config['admob_interstitial_interval'] . ' == 0) {
                    showInterstitialAd();
                }' : '') . '
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {' . ($config['external_links_browser'] ? '
                try {
                    java.net.URL baseUrl = new java.net.URL(WEBSITE_URL);
                    java.net.URL targetUrl = new java.net.URL(url);
                    if (!targetUrl.getHost().equalsIgnoreCase(baseUrl.getHost())) {
                        Intent browserIntent = new Intent(Intent.ACTION_VIEW, android.net.Uri.parse(url));
                        startActivity(browserIntent);
                        return true;
                    }
                } catch (Exception e) { /* ignore, load in webview */ }' : '') . '
                view.loadUrl(url);
                return true;
            }
        });

        webView.setWebChromeClient(new WebChromeClient() {
            @Override
            public void onProgressChanged(WebView view, int newProgress) {
                progressBar.setProgress(newProgress);
            }
            ' . $pushChromeClient . ($config['location_permission'] ? '
            @Override
            public void onGeolocationPermissionsShowPrompt(String origin, GeolocationPermissions.Callback callback) {
                callback.invoke(origin, true, false);
            }' : '') . ($config['file_upload_camera'] ? '
            @Override
            public boolean onShowFileChooser(WebView webView, ValueCallback<Uri[]> filePathCallback, FileChooserParams fileChooserParams) {
                if (fileUploadCallback != null) {
                    fileUploadCallback.onReceiveValue(null);
                }
                fileUploadCallback = filePathCallback;

                // Camera intent
                Intent cameraIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
                ContentValues values = new ContentValues();
                values.put(MediaStore.Images.Media.TITLE, "camera_photo");
                cameraImageUri = getContentResolver().insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT, cameraImageUri);

                // File chooser intent
                Intent fileIntent = new Intent(Intent.ACTION_GET_CONTENT);
                fileIntent.addCategory(Intent.CATEGORY_OPENABLE);
                fileIntent.setType("*/*");

                // Combine into chooser
                Intent chooserIntent = Intent.createChooser(fileIntent, "Select file");
                chooserIntent.putExtra(Intent.EXTRA_INITIAL_INTENTS, new Intent[]{cameraIntent});

                fileUploadLauncher.launch(chooserIntent);
                return true;
            }' : '') . '
        });

        ' . $downloadCode . '
    }

    private boolean isNetworkAvailable() {
        ConnectivityManager connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();
    }

    private void handleIntent(Intent intent) {
        if (intent != null && intent.getData() != null) {
            String deepUrl = intent.getData().toString();' . (!empty($config['url_scheme']) ? '
            // Custom URL scheme: convert myapp://path to website URL
            if (deepUrl.startsWith("' . $config['url_scheme'] . '://")) {
                String path = deepUrl.replace("' . $config['url_scheme'] . '://", "");
                String baseUrl = WEBSITE_URL.endsWith("/") ? WEBSITE_URL : WEBSITE_URL + "/";
                deepUrl = baseUrl + path;
            }' : '') . '
            if (deepUrl.startsWith("http")) {
                webView.loadUrl(deepUrl);
            }
        }
    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        handleIntent(intent);
    }' . ($config['fab_enabled'] && !empty($config['fab_value']) ? '

    private void setupFAB() {
        android.widget.FrameLayout rootLayout = (android.widget.FrameLayout) findViewById(android.R.id.content);
        
        android.widget.FrameLayout fabContainer = new android.widget.FrameLayout(this);
        android.widget.FrameLayout.LayoutParams containerParams = new android.widget.FrameLayout.LayoutParams(
            android.widget.FrameLayout.LayoutParams.WRAP_CONTENT,
            android.widget.FrameLayout.LayoutParams.WRAP_CONTENT);
        containerParams.gravity = android.view.Gravity.BOTTOM | android.view.Gravity.END;
        containerParams.setMargins(0, 0, dpToPx(20), dpToPx(20));
        
        android.widget.Button fabBtn = new android.widget.Button(this);
        fabBtn.setText("' . ($config['fab_icon'] === 'phone' ? '📞' : ($config['fab_icon'] === 'email' ? '✉' : ($config['fab_icon'] === 'whatsapp' ? '💬' : ($config['fab_icon'] === 'help' ? '❓' : ($config['fab_icon'] === 'cart' ? '🛒' : '💬'))))) . '");
        fabBtn.setTextSize(24);
        int size = dpToPx(56);
        android.widget.FrameLayout.LayoutParams btnParams = new android.widget.FrameLayout.LayoutParams(size, size);
        fabBtn.setLayoutParams(btnParams);
        
        android.graphics.drawable.GradientDrawable bg = new android.graphics.drawable.GradientDrawable();
        bg.setShape(android.graphics.drawable.GradientDrawable.OVAL);
        bg.setColor(Color.parseColor("' . $config['fab_color'] . '"));
        fabBtn.setBackground(bg);
        fabBtn.setElevation(dpToPx(6));
        fabBtn.setPadding(0, 0, 0, 0);
        
        fabBtn.setOnClickListener(v -> {' . (
            $config['fab_action'] === 'whatsapp' ? '
            try {
                String waUrl = "https://wa.me/' . preg_replace('/[^0-9]/', '', $config['fab_value']) . '";
                startActivity(new Intent(Intent.ACTION_VIEW, android.net.Uri.parse(waUrl)));
            } catch (Exception e) { }' :
            ($config['fab_action'] === 'call' ? '
            try {
                startActivity(new Intent(Intent.ACTION_DIAL, android.net.Uri.parse("tel:' . addslashes($config['fab_value']) . '")));
            } catch (Exception e) { }' :
            ($config['fab_action'] === 'email' ? '
            try {
                Intent emailIntent = new Intent(Intent.ACTION_SENDTO, android.net.Uri.parse("mailto:' . addslashes($config['fab_value']) . '"));
                startActivity(emailIntent);
            } catch (Exception e) { }' :
            ($config['fab_action'] === 'sms' ? '
            try {
                startActivity(new Intent(Intent.ACTION_VIEW, android.net.Uri.parse("sms:' . addslashes($config['fab_value']) . '")));
            } catch (Exception e) { }' :
            ($config['fab_action'] === 'telegram' ? '
            try {
                String tgUrl = "https://t.me/' . addslashes($config['fab_value']) . '";
                startActivity(new Intent(Intent.ACTION_VIEW, android.net.Uri.parse(tgUrl)));
            } catch (Exception e) { }' :
            '
            try {
                startActivity(new Intent(Intent.ACTION_VIEW, android.net.Uri.parse("' . addslashes($config['fab_value']) . '")));
            } catch (Exception e) { }'))))) . '
        });
        
        fabContainer.addView(fabBtn);
        rootLayout.addView(fabContainer, containerParams);
    }
    
    private int dpToPx(int dp) {
        return Math.round(dp * getResources().getDisplayMetrics().density);
    }' : '') . ($config['admob_enabled'] && !empty($config['admob_interstitial_id']) ? '

    private void loadInterstitialAd() {
        AdRequest adRequest = new AdRequest.Builder().build();
        InterstitialAd.load(this, "' . addslashes($config['admob_interstitial_id']) . '", adRequest,
            new InterstitialAdLoadCallback() {
                @Override
                public void onAdLoaded(InterstitialAd interstitialAd) {
                    mInterstitialAd = interstitialAd;
                    mInterstitialAd.setFullScreenContentCallback(new FullScreenContentCallback() {
                        @Override
                        public void onAdDismissedFullScreenContent() {
                            mInterstitialAd = null;
                            loadInterstitialAd();
                        }
                    });
                }
                @Override
                public void onAdFailedToLoad(LoadAdError loadAdError) {
                    mInterstitialAd = null;
                }
            });
    }

    private void showInterstitialAd() {
        if (mInterstitialAd != null) {
            mInterstitialAd.show(this);
        }
    }' : '') . ($config['file_upload_camera'] ? '

    private final ActivityResultLauncher<Intent> fileUploadLauncher = registerForActivityResult(
        new ActivityResultContracts.StartActivityForResult(), result -> {
            if (fileUploadCallback == null) return;
            if (result.getResultCode() == RESULT_OK && result.getData() != null && result.getData().getData() != null) {
                fileUploadCallback.onReceiveValue(new Uri[]{result.getData().getData()});
            } else if (result.getResultCode() == RESULT_OK && cameraImageUri != null) {
                fileUploadCallback.onReceiveValue(new Uri[]{cameraImageUri});
            } else {
                fileUploadCallback.onReceiveValue(null);
            }
            fileUploadCallback = null;
        });' : '') . ($config['nav_drawer'] && !empty($config['drawer_items']) ? '

    private void setupNavDrawer() {
        android.widget.FrameLayout rootLayout = (android.widget.FrameLayout) findViewById(android.R.id.content);
        
        drawerOverlay = new android.widget.FrameLayout(this);
        drawerOverlay.setBackgroundColor(0x80000000);
        drawerOverlay.setVisibility(View.GONE);
        drawerOverlay.setOnClickListener(v -> closeDrawer());
        rootLayout.addView(drawerOverlay, new android.widget.FrameLayout.LayoutParams(
            android.widget.FrameLayout.LayoutParams.MATCH_PARENT, android.widget.FrameLayout.LayoutParams.MATCH_PARENT));
        
        drawerPanel = new LinearLayout(this);
        drawerPanel.setOrientation(LinearLayout.VERTICAL);
        drawerPanel.setBackgroundColor(Color.parseColor("#1e293b"));
        int drawerW = (int)(getResources().getDisplayMetrics().widthPixels * 0.75f);
        android.widget.FrameLayout.LayoutParams dp = new android.widget.FrameLayout.LayoutParams(drawerW, android.widget.FrameLayout.LayoutParams.MATCH_PARENT);
        dp.gravity = android.view.Gravity.START;
        drawerPanel.setTranslationX(-drawerW);
        drawerPanel.setElevation(8 * getResources().getDisplayMetrics().density);
        drawerPanel.setPadding(0, (int)(48 * getResources().getDisplayMetrics().density), 0, 0);
        
        TextView header = new TextView(this);
        header.setText("' . addslashes($config['app_name']) . '");
        header.setTextColor(Color.WHITE);
        header.setTextSize(20);
        int hPad = (int)(20 * getResources().getDisplayMetrics().density);
        int vPad = (int)(16 * getResources().getDisplayMetrics().density);
        header.setPadding(hPad, vPad, hPad, vPad);
        header.setBackgroundColor(Color.parseColor("' . $config['status_bar_color'] . '"));
        drawerPanel.addView(header);
        
        String[][] drawerItems = {' . implode(',', array_map(function($item) {
            return '{"' . addslashes($item['label']) . '", "' . addslashes($item['url']) . '"}';
        }, $config['drawer_items'])) . '};
        for (String[] item : drawerItems) {
            TextView tv = new TextView(this);
            tv.setText(item[0]);
            tv.setTextColor(Color.parseColor("#e2e8f0"));
            tv.setTextSize(16);
            int iPadH = (int)(20 * getResources().getDisplayMetrics().density);
            int iPadV = (int)(14 * getResources().getDisplayMetrics().density);
            tv.setPadding(iPadH, iPadV, iPadH, iPadV);
            final String url = item[1];
            tv.setOnClickListener(v -> {
                closeDrawer();
                if (url.startsWith("http")) { webView.loadUrl(url); }
                else { webView.loadUrl(WEBSITE_URL + (WEBSITE_URL.endsWith("/") ? "" : "/") + url.replaceFirst("^/", "")); }
            });
            drawerPanel.addView(tv);
        }
        rootLayout.addView(drawerPanel, dp);
    }
    
    private void toggleDrawer() { if (drawerOpen) closeDrawer(); else openDrawer(); }
    private void openDrawer() {
        drawerOpen = true;
        drawerOverlay.setVisibility(View.VISIBLE);
        drawerPanel.animate().translationX(0).setDuration(250).start();
    }
    private void closeDrawer() {
        drawerOpen = false;
        int w = (int)(getResources().getDisplayMetrics().widthPixels * 0.75f);
        drawerPanel.animate().translationX(-w).setDuration(250).withEndAction(() -> drawerOverlay.setVisibility(View.GONE)).start();
    }' : '') . ($config['bottom_nav'] && !empty($config['bnav_items']) ? '

    private void setupBottomNav() {
        android.widget.FrameLayout rootLayout = (android.widget.FrameLayout) findViewById(android.R.id.content);
        LinearLayout bottomBar = new LinearLayout(this);
        bottomBar.setOrientation(LinearLayout.HORIZONTAL);
        bottomBar.setBackgroundColor(Color.parseColor("#1e293b"));
        bottomBar.setElevation(8 * getResources().getDisplayMetrics().density);
        
        android.widget.FrameLayout.LayoutParams bp = new android.widget.FrameLayout.LayoutParams(
            android.widget.FrameLayout.LayoutParams.MATCH_PARENT, (int)(56 * getResources().getDisplayMetrics().density));
        bp.gravity = android.view.Gravity.BOTTOM;
        
        String[][] tabs = {' . implode(',', array_map(function($item) {
            return '{"' . addslashes($item['label']) . '", "' . addslashes($item['url']) . '"}';
        }, $config['bnav_items'])) . '};
        
        for (String[] tab : tabs) {
            TextView tv = new TextView(this);
            tv.setText(tab[0]);
            tv.setTextColor(Color.parseColor("#94a3b8"));
            tv.setTextSize(12);
            tv.setGravity(android.view.Gravity.CENTER);
            LinearLayout.LayoutParams tp = new LinearLayout.LayoutParams(0, LinearLayout.LayoutParams.MATCH_PARENT);
            tp.weight = 1;
            final String url = tab[1];
            tv.setOnClickListener(v -> {
                if (url.equals("/") || url.isEmpty()) { webView.loadUrl(WEBSITE_URL); }
                else if (url.startsWith("http")) { webView.loadUrl(url); }
                else { webView.loadUrl(WEBSITE_URL + (WEBSITE_URL.endsWith("/") ? "" : "/") + url.replaceFirst("^/", "")); }
            });
            bottomBar.addView(tv, tp);
        }
        rootLayout.addView(bottomBar, bp);
        webView.setPadding(0, 0, 0, (int)(56 * getResources().getDisplayMetrics().density));
        webView.setClipToPadding(false);
    }' : '') . ($config['gdpr_consent'] ? '

    private void showGdprConsentIfNeeded() {
        android.content.SharedPreferences prefs = getSharedPreferences("app_prefs", MODE_PRIVATE);
        if (prefs.getBoolean("gdpr_accepted", false)) return;
        
        new android.app.AlertDialog.Builder(this)
            .setTitle("Cookie & Privacy Consent")
            .setMessage("This app uses cookies and similar technologies to provide the best experience. By continuing, you agree to our use of cookies and our Privacy Policy.")
            .setPositiveButton("Accept All", (d, w) -> {
                prefs.edit().putBoolean("gdpr_accepted", true).apply();
            })
            .setNegativeButton("Necessary Only", (d, w) -> {
                prefs.edit().putBoolean("gdpr_accepted", true).putBoolean("gdpr_limited", true).apply();
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                    android.webkit.CookieManager.getInstance().setAcceptThirdPartyCookies(webView, false);
                }
            })
            .setCancelable(false)
            .show();
    }' : '') . ($config['update_checker'] && !empty($config['update_check_url']) ? '

    private void checkForUpdate() {
        new Thread(() -> {
            try {
                java.net.URL url = new java.net.URL("' . addslashes($config['update_check_url']) . '");
                java.net.HttpURLConnection conn = (java.net.HttpURLConnection) url.openConnection();
                conn.setConnectTimeout(5000);
                conn.setReadTimeout(5000);
                java.io.BufferedReader reader = new java.io.BufferedReader(new java.io.InputStreamReader(conn.getInputStream()));
                StringBuilder sb = new StringBuilder();
                String line;
                while ((line = reader.readLine()) != null) sb.append(line);
                reader.close();
                conn.disconnect();
                
                JSONObject json = new JSONObject(sb.toString());
                String latestVersion = json.optString("latest_version", "");
                final String updateUrl = json.optString("update_url", "");
                
                if (!latestVersion.isEmpty() && !latestVersion.equals("' . addslashes($config['version']) . '") && !updateUrl.isEmpty()) {
                    runOnUiThread(() -> {
                        new android.app.AlertDialog.Builder(MainActivity.this)
                            .setTitle("Update Available")
                            .setMessage("A new version (" + latestVersion + ") is available. Would you like to update?")
                            .setPositiveButton("Update", (d, w) -> {
                                startActivity(new Intent(Intent.ACTION_VIEW, android.net.Uri.parse(updateUrl)));
                            })
                            .setNegativeButton("Later", null)
                            .show();
                    });
                }
            } catch (Exception e) { /* silent fail */ }
        }).start();
    }' : '') . ($config['app_lock'] ? '

    private void authenticateUser() {
        BiometricManager biometricManager = BiometricManager.from(this);
        int canAuth = biometricManager.canAuthenticate(BiometricManager.Authenticators.BIOMETRIC_WEAK | BiometricManager.Authenticators.DEVICE_CREDENTIAL);
        if (canAuth != BiometricManager.BIOMETRIC_SUCCESS) { return; }
        
        webView.setVisibility(View.INVISIBLE);
        
        java.util.concurrent.Executor executor = getMainExecutor();
        BiometricPrompt biometricPrompt = new BiometricPrompt(this, executor, new BiometricPrompt.AuthenticationCallback() {
            @Override
            public void onAuthenticationSucceeded(BiometricPrompt.AuthenticationResult result) {
                super.onAuthenticationSucceeded(result);
                webView.setVisibility(View.VISIBLE);
            }
            @Override
            public void onAuthenticationError(int errorCode, CharSequence errString) {
                super.onAuthenticationError(errorCode, errString);
                if (errorCode == BiometricPrompt.ERROR_USER_CANCELED || errorCode == BiometricPrompt.ERROR_NEGATIVE_BUTTON) {
                    finish();
                }
            }
        });
        
        BiometricPrompt.PromptInfo promptInfo = new BiometricPrompt.PromptInfo.Builder()
            .setTitle("Unlock App")
            .setSubtitle("Verify your identity to continue")
            .setAllowedAuthenticators(BiometricManager.Authenticators.BIOMETRIC_WEAK | BiometricManager.Authenticators.DEVICE_CREDENTIAL)
            .build();
        
        biometricPrompt.authenticate(promptInfo);
    }' : '') . '

    ' . $backButtonCode . ($config['share_button'] || !empty($config['toolbar_items']) || !empty($config['privacy_policy_url']) ? '

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {' . ($config['share_button'] ? '
        menu.add(0, 1, 0, "Share").setIcon(android.R.drawable.ic_menu_share)
            .setShowAsAction(MenuItem.SHOW_AS_ACTION_IF_ROOM);' : '') . (!empty($config['privacy_policy_url']) ? '
        menu.add(0, 999, 0, "Privacy Policy");' : '') . implode('', array_map(function($item, $idx) { return '
        menu.add(0, ' . (100 + $idx) . ', 0, "' . addslashes($item['label']) . '");'; }, $config['toolbar_items'], array_keys($config['toolbar_items']))) . '
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {' . ($config['share_button'] ? '
        if (item.getItemId() == 1) {
            Intent shareIntent = new Intent(Intent.ACTION_SEND);
            shareIntent.setType("text/plain");
            shareIntent.putExtra(Intent.EXTRA_TEXT, webView.getUrl());
            startActivity(Intent.createChooser(shareIntent, "Share via"));
            return true;
        }' : '') . (!empty($config['privacy_policy_url']) ? '
        if (item.getItemId() == 999) {
            startActivity(new Intent(Intent.ACTION_VIEW, android.net.Uri.parse("' . addslashes($config['privacy_policy_url']) . '")));
            return true;
        }' : '') . implode('', array_map(function($item, $idx) use ($config) { return '
        if (item.getItemId() == ' . (100 + $idx) . ') {
            String targetUrl = "' . addslashes($item['url']) . '";
            if (targetUrl.startsWith("http")) {
                webView.loadUrl(targetUrl);
            } else {
                String base = WEBSITE_URL.endsWith("/") ? WEBSITE_URL : WEBSITE_URL + "/";
                webView.loadUrl(base + targetUrl.replaceFirst("^/", ""));
            }
            return true;
        }'; }, $config['toolbar_items'], array_keys($config['toolbar_items']))) . '
        return super.onOptionsItemSelected(item);
    }' : '') . ($config['js_bridge'] ? '

    // JavaScript Bridge - access via window.AndroidBridge in your web JS
    private class NativeBridge {
        @android.webkit.JavascriptInterface
        public void showToast(String message) {
            runOnUiThread(() -> Toast.makeText(MainActivity.this, message, Toast.LENGTH_SHORT).show());
        }

        @android.webkit.JavascriptInterface
        public void shareText(String text) {
            Intent shareIntent = new Intent(Intent.ACTION_SEND);
            shareIntent.setType("text/plain");
            shareIntent.putExtra(Intent.EXTRA_TEXT, text);
            startActivity(Intent.createChooser(shareIntent, "Share"));
        }

        @android.webkit.JavascriptInterface
        public void openInBrowser(String url) {
            startActivity(new Intent(Intent.ACTION_VIEW, android.net.Uri.parse(url)));
        }

        @android.webkit.JavascriptInterface
        public void copyToClipboard(String text) {
            android.content.ClipboardManager clipboard = (android.content.ClipboardManager) getSystemService(Context.CLIPBOARD_SERVICE);
            clipboard.setPrimaryClip(android.content.ClipData.newPlainText("text", text));
            runOnUiThread(() -> Toast.makeText(MainActivity.this, "Copied!", Toast.LENGTH_SHORT).show());
        }

        @android.webkit.JavascriptInterface
        public void vibrate(int ms) {
            android.os.Vibrator v = (android.os.Vibrator) getSystemService(Context.VIBRATOR_SERVICE);
            if (v != null) v.vibrate(ms);
        }

        @android.webkit.JavascriptInterface
        public String getAppVersion() {
            try {
                return getPackageManager().getPackageInfo(getPackageName(), 0).versionName;
            } catch (Exception e) { return "unknown"; }
        }

        @android.webkit.JavascriptInterface
        public void dial(String phone) {
            startActivity(new Intent(Intent.ACTION_DIAL, android.net.Uri.parse("tel:" + phone)));
        }

        @android.webkit.JavascriptInterface
        public void sendEmail(String to, String subject, String body) {
            Intent intent = new Intent(Intent.ACTION_SENDTO, android.net.Uri.parse("mailto:" + to));
            intent.putExtra(Intent.EXTRA_SUBJECT, subject);
            intent.putExtra(Intent.EXTRA_TEXT, body);
            startActivity(intent);
        }

        @android.webkit.JavascriptInterface
        public void closeApp() {
            finish();
        }

        @android.webkit.JavascriptInterface
        public String getDeviceInfo() {
            return "{\"brand\":\"" + Build.BRAND + "\",\"model\":\"" + Build.MODEL + "\",\"sdk\":" + Build.VERSION.SDK_INT + ",\"android\":\"" + Build.VERSION.RELEASE + "\"}";
        }
    }' : '') . '

    @Override
    protected void onPause() {
        super.onPause();
        webView.onPause();
    }

    @Override
    protected void onResume() {
        super.onResume();
        webView.onResume();
    }' . ($config['immersive_mode'] ? '

    @Override
    public void onWindowFocusChanged(boolean hasFocus) {
        super.onWindowFocusChanged(hasFocus);
        if (hasFocus) {
            getWindow().getDecorView().setSystemUiVisibility(
                View.SYSTEM_UI_FLAG_LAYOUT_STABLE
                | View.SYSTEM_UI_FLAG_LAYOUT_HIDE_NAVIGATION
                | View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN
                | View.SYSTEM_UI_FLAG_HIDE_NAVIGATION
                | View.SYSTEM_UI_FLAG_FULLSCREEN
                | View.SYSTEM_UI_FLAG_IMMERSIVE_STICKY);
        }
    }' : '') . '

    @Override
    protected void onDestroy() {
        super.onDestroy();
        if (webView != null) {
            webView.destroy();
        }
    }
}';
    
    file_put_contents($buildDir . 'app/src/main/java/' . $packagePath . '/MainActivity.java', $mainActivity);
    
    // Generate activity_main.xml
    $adViewXml = $config['admob_enabled'] && !empty($config['admob_banner_id']) ? '

    <com.google.android.gms.ads.AdView
        android:id="@+id/adView"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_alignParentBottom="true"
        ads:adSize="BANNER"
        ads:adUnitId="' . htmlspecialchars($config['admob_banner_id']) . '" />' : '';
    
    $progressBarXml = '';
    if ($config['loading_style'] === 'linear' || $config['loading_style'] === 'both') {
        $progressBarXml .= '
    <ProgressBar
        android:id="@+id/progressBar"
        style="?android:attr/progressBarStyleHorizontal"
        android:layout_width="match_parent"
        android:layout_height="4dp"
        android:layout_alignParentTop="true"
        android:indeterminate="false"
        android:max="100"
        android:progressTint="@color/loading_bar_color"
        android:visibility="gone" />';
    }
    if ($config['loading_style'] === 'circular' || $config['loading_style'] === 'both') {
        $progressBarXml .= '
    <ProgressBar
        android:id="@+id/progressBarCircular"
        style="?android:attr/progressBarStyle"
        android:layout_width="48dp"
        android:layout_height="48dp"
        android:layout_centerInParent="true"
        android:indeterminateTint="@color/loading_bar_color"
        android:visibility="gone" />';
    }
    if ($config['loading_style'] === 'none') {
        $progressBarXml .= '
    <ProgressBar
        android:id="@+id/progressBar"
        style="?android:attr/progressBarStyleHorizontal"
        android:layout_width="match_parent"
        android:layout_height="0dp"
        android:visibility="gone" />';
    }

    $adsNamespace = $config['admob_enabled'] && !empty($config['admob_banner_id']) ? '
    xmlns:ads="http://schemas.android.com/apk/res-auto"' : '';

    if ($config['pull_to_refresh']) {
        $layout = '<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"' . $adsNamespace . '
    android:layout_width="match_parent"
    android:layout_height="match_parent">
' . $progressBarXml . '

    <androidx.swiperefreshlayout.widget.SwipeRefreshLayout
        android:id="@+id/swipeRefresh"
        android:layout_width="match_parent"
        android:layout_height="match_parent"
        android:layout_below="@id/progressBar"' . ($adViewXml ? '
        android:layout_above="@id/adView"' : '') . '>

        <WebView
            android:id="@+id/webView"
            android:layout_width="match_parent"
            android:layout_height="match_parent" />

    </androidx.swiperefreshlayout.widget.SwipeRefreshLayout>
' . $adViewXml . '
</RelativeLayout>';
    } else {
        $layout = '<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"' . $adsNamespace . '
    android:layout_width="match_parent"
    android:layout_height="match_parent">
' . $progressBarXml . '

    <WebView
        android:id="@+id/webView"
        android:layout_width="match_parent"
        android:layout_height="match_parent"
        android:layout_below="@id/progressBar"' . ($adViewXml ? '
        android:layout_above="@id/adView"' : '') . ' />
' . $adViewXml . '
</RelativeLayout>';
    }
    
    file_put_contents($buildDir . 'app/src/main/res/layout/activity_main.xml', $layout);
    
    // Generate colors.xml
    $colors = '<?xml version="1.0" encoding="utf-8"?>
<resources>
    <color name="colorPrimary">' . $config['splash_color'] . '</color>
    <color name="colorPrimaryDark">' . adjustBrightness($config['splash_color'], -30) . '</color>
    <color name="colorAccent">#ec4899</color>
    <color name="splash_background">' . $config['splash_color'] . '</color>
    <color name="status_bar_color">' . $config['status_bar_color'] . '</color>
    <color name="loading_bar_color">' . $config['loading_bar_color'] . '</color>
    <color name="white">#FFFFFF</color>
    <color name="black">#000000</color>
</resources>';
    
    file_put_contents($buildDir . 'app/src/main/res/values/colors.xml', $colors);
    
    // Generate styles.xml
    $appThemeParent = ($config['file_upload_camera'] || $config['app_lock']) ? 'Theme.AppCompat.Light.NoActionBar' : 'android:Theme.Material.Light.NoActionBar';
    $styles = '<?xml version="1.0" encoding="utf-8"?>
<resources>
    <style name="AppTheme" parent="' . $appThemeParent . '">
        <item name="android:colorPrimary">@color/colorPrimary</item>
        <item name="android:colorPrimaryDark">@color/status_bar_color</item>
        <item name="android:colorAccent">@color/colorAccent</item>
        <item name="android:windowBackground">@color/white</item>
        <item name="android:statusBarColor">@color/status_bar_color</item>
    </style>

    <style name="SplashTheme" parent="android:Theme.Material.Light.NoActionBar">
        <item name="android:windowBackground">@color/splash_background</item>
        <item name="android:windowFullscreen">true</item>
    </style>
</resources>';
    
    file_put_contents($buildDir . 'app/src/main/res/values/styles.xml', $styles);
    
    // Generate strings.xml
    $strings = '<?xml version="1.0" encoding="utf-8"?>
<resources>
    <string name="app_name">' . htmlspecialchars($config['app_name']) . '</string>
</resources>';
    
    file_put_contents($buildDir . 'app/src/main/res/values/strings.xml', $strings);
    
    // Generate build.gradle (app level)
    $buildGradle = 'plugins {
    id \'com.android.application\'
' . ($config['push_notifications'] ? "    id 'com.google.gms.google-services'" : '') . '
}

android {
    namespace \'' . $config['package_name'] . '\'
    compileSdk 35

    defaultConfig {
        applicationId "' . $config['package_name'] . '"
        minSdk ' . $config['min_sdk'] . '
        targetSdk 35
        versionCode ' . $config['version_code'] . '
        versionName "' . $config['version'] . '"
    }

    signingConfigs {
        release {
            // To sign for Play Store, create a keystore and uncomment:
            // storeFile file("release-key.jks")
            // storePassword "your_store_password"
            // keyAlias "your_key_alias"
            // keyPassword "your_key_password"
        }
    }

    buildTypes {
        release {
            minifyEnabled true
            shrinkResources true
            proguardFiles getDefaultProguardFile(\'proguard-android-optimize.txt\'), \'proguard-rules.pro\'
            // Uncomment after setting up signingConfigs above:
            // signingConfig signingConfigs.release
        }
        debug {
            minifyEnabled false
        }
    }
    
    compileOptions {
        sourceCompatibility JavaVersion.VERSION_17
        targetCompatibility JavaVersion.VERSION_17
    }

    bundle {
        language {
            enableSplit = true
        }
        density {
            enableSplit = true
        }
        abi {
            enableSplit = true
        }
    }

    lint {
        abortOnError false
        checkReleaseBuilds false
    }
}

dependencies {
    implementation \'androidx.appcompat:appcompat:1.7.0\'
    implementation \'com.google.android.material:material:1.12.0\'
    implementation \'androidx.core:core:1.15.0\'
    implementation \'androidx.activity:activity:1.9.3\'
    implementation \'androidx.swiperefreshlayout:swiperefreshlayout:1.1.0\'
    ' . ($config['push_notifications'] ? "implementation 'androidx.work:work-runtime:2.10.0'
    implementation platform('com.google.firebase:firebase-bom:33.7.0')
    implementation 'com.google.firebase:firebase-messaging'" : '') . '
    ' . ($config['admob_enabled'] ? "implementation 'com.google.android.gms:play-services-ads:23.6.0'" : '') . '
    ' . ($config['force_dark'] ? "implementation 'androidx.webkit:webkit:1.12.1'" : '') . '
    ' . ($config['app_lock'] ? "implementation 'androidx.biometric:biometric:1.2.0-alpha05'" : '') . '
}';
    
    file_put_contents($buildDir . 'app/build.gradle', $buildGradle);

    // Generate proguard-rules.pro
    $proguard = '# WebView
-keepclassmembers class * extends android.webkit.WebViewClient {
    public void *(android.webkit.WebView, java.lang.String, android.graphics.Bitmap);
    public boolean *(android.webkit.WebView, java.lang.String);
}
-keepclassmembers class * extends android.webkit.WebViewClient {
    public void *(android.webkit.WebView, java.lang.String);
}

# Keep JavaScript interface
-keepattributes JavascriptInterface
-keepclassmembers class * {
    @android.webkit.JavascriptInterface <methods>;
}

# Firebase
-keep class com.google.firebase.** { *; }
-keep class com.google.android.gms.** { *; }
-dontwarn com.google.firebase.**
-dontwarn com.google.android.gms.**

# Firebase Messaging
-keep class com.google.firebase.messaging.** { *; }
-keepclassmembers class * extends com.google.firebase.messaging.FirebaseMessagingService {
    public void onMessageReceived(com.google.firebase.messaging.RemoteMessage);
    public void onNewToken(java.lang.String);
}

# AdMob
-keep class com.google.android.gms.ads.** { *; }
-dontwarn com.google.android.gms.ads.**

# AndroidX
-keep class androidx.** { *; }
-dontwarn androidx.**

# WorkManager
-keep class androidx.work.** { *; }
-dontwarn androidx.work.**

# Keep R8 compatible
-keepattributes Signature
-keepattributes *Annotation*
-keepattributes SourceFile,LineNumberTable
-keepattributes InnerClasses,EnclosingMethod

# Prevent stripping of parcelable classes
-keep class * implements android.os.Parcelable {
    public static final android.os.Parcelable$Creator *;
}

# Keep enums
-keepclassmembers enum * {
    public static **[] values();
    public static ** valueOf(java.lang.String);
}';
    
    file_put_contents($buildDir . 'app/proguard-rules.pro', $proguard);

    // Generate NotificationWorker.java for background polling
    if ($config['push_notifications']) {
        $notifWorker = 'package ' . $config['package_name'] . ';

import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Build;
import androidx.annotation.NonNull;
import androidx.core.app.NotificationCompat;
import androidx.work.Worker;
import androidx.work.WorkerParameters;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import org.json.JSONObject;

public class NotificationWorker extends Worker {

    public NotificationWorker(@NonNull Context context, @NonNull WorkerParameters params) {
        super(context, params);
    }

    @NonNull
    @Override
    public Result doWork() {
        Context ctx = getApplicationContext();
        SharedPreferences prefs = ctx.getSharedPreferences("bp_prefs", Context.MODE_PRIVATE);
        String cookies = prefs.getString("session_cookies", null);
        String websiteUrl = prefs.getString("website_url", null);
        int lastNotifId = prefs.getInt("last_notif_id", 0);

        if (cookies == null || websiteUrl == null) return Result.success();

        try {
            URL url = new URL(websiteUrl + "/notifications.php?ajax=check");
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestProperty("Cookie", cookies);
            conn.setRequestProperty("X-Requested-With", "XMLHttpRequest");
            conn.setConnectTimeout(10000);
            conn.setReadTimeout(10000);

            BufferedReader reader = new BufferedReader(new InputStreamReader(conn.getInputStream()));
            StringBuilder sb = new StringBuilder();
            String line;
            while ((line = reader.readLine()) != null) sb.append(line);
            reader.close();

            JSONObject json = new JSONObject(sb.toString());
            int unread = json.optInt("unread", 0);

            if (json.has("latest") && !json.isNull("latest")) {
                JSONObject latest = json.getJSONObject("latest");
                int notifId = latest.optInt("id", 0);

                if (notifId > lastNotifId && lastNotifId > 0 && unread > 0) {
                    String title = latest.optString("title", "New Notification");
                    String message = latest.optString("message", "");
                    showNotification(ctx, title, message);
                }

                if (notifId > 0) {
                    prefs.edit().putInt("last_notif_id", notifId).apply();
                }
            }
        } catch (Exception e) {
            // Silent fail — best effort
        }

        return Result.success();
    }

    private void showNotification(Context ctx, String title, String body) {
        NotificationManager nm = (NotificationManager) ctx.getSystemService(Context.NOTIFICATION_SERVICE);
        String channelId = "bp_notifications";

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(
                channelId, "App Notifications", NotificationManager.IMPORTANCE_HIGH);
            channel.setDescription("Background notifications");
            nm.createNotificationChannel(channel);
        }

        Intent intent = new Intent(ctx, MainActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
        PendingIntent pi = PendingIntent.getActivity(ctx, 0, intent,
            PendingIntent.FLAG_UPDATE_CURRENT | PendingIntent.FLAG_IMMUTABLE);

        NotificationCompat.Builder builder = new NotificationCompat.Builder(ctx, channelId)
            .setSmallIcon(android.R.drawable.ic_dialog_info)
            .setContentTitle(title)
            .setContentText(body)
            .setStyle(new NotificationCompat.BigTextStyle().bigText(body))
            .setAutoCancel(true)
            .setPriority(NotificationCompat.PRIORITY_HIGH)
            .setContentIntent(pi);

        nm.notify((int) System.currentTimeMillis(), builder.build());
    }
}';
        file_put_contents($buildDir . 'app/src/main/java/' . $packagePath . '/NotificationWorker.java', $notifWorker);

        // Generate BPFirebaseMessagingService.java
        // Pre-calculate server URL safely (avoid inline IIFE which could output PHP notices)
        $parsedUrl = parse_url($config['website_url']);
        $fcmServerOrigin = '';
        if (!empty($parsedUrl['scheme']) && !empty($parsedUrl['host'])) {
            $fcmServerOrigin = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
            if (!empty($parsedUrl['port']) && $parsedUrl['port'] != 443 && $parsedUrl['port'] != 80) {
                $fcmServerOrigin .= ':' . $parsedUrl['port'];
            }
        } elseif (!empty($parsedUrl['path'])) {
            $fcmServerOrigin = 'https://' . $parsedUrl['path'];
        } else {
            $fcmServerOrigin = rtrim($config['website_url'], '/');
        }

        $fcmService = 'package ' . $config['package_name'] . ';

import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import androidx.annotation.NonNull;
import androidx.core.app.NotificationCompat;
import com.google.firebase.messaging.FirebaseMessaging;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;

public class BPFirebaseMessagingService extends FirebaseMessagingService {

    private static final String SERVER_URL = "' . $fcmServerOrigin . '/admin/fcm_token.php";

    @Override
    public void onNewToken(@NonNull String token) {
        super.onNewToken(token);
        sendTokenToServer(token);
    }

    private void sendTokenToServer(final String token) {
        new Thread(() -> {
            try {
                String endpoint = SERVER_URL == null ? "" : SERVER_URL.trim();
                if (!endpoint.startsWith("http://") && !endpoint.startsWith("https://")) {
                    endpoint = "https://" + endpoint;
                }
                URL url = new URL(endpoint);
                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/json");
                conn.setDoOutput(true);
                conn.setConnectTimeout(10000);
                conn.setReadTimeout(10000);
                String body = "{\"token\":\"" + token + "\"}";
                try (OutputStream os = conn.getOutputStream()) {
                    os.write(body.getBytes(StandardCharsets.UTF_8));
                }
                int status = conn.getResponseCode(); // trigger request
                conn.disconnect();

                // Fallback: send as GET query param if POST did not succeed
                if (status < 200 || status >= 300) {
                    String fallback = endpoint + (endpoint.contains("?") ? "&" : "?") + "t=" + java.net.URLEncoder.encode(token, "UTF-8");
                    HttpURLConnection fb = (HttpURLConnection) new URL(fallback).openConnection();
                    fb.setRequestMethod("GET");
                    fb.setConnectTimeout(10000);
                    fb.setReadTimeout(10000);
                    fb.getResponseCode();
                    fb.disconnect();
                }
            } catch (Exception e) {
                // Silent fail
            }
        }).start();
    }

    @Override
    public void onMessageReceived(@NonNull RemoteMessage remoteMessage) {
        super.onMessageReceived(remoteMessage);
        String title = "Notification";
        String body = "";
        if (remoteMessage.getNotification() != null) {
            title = remoteMessage.getNotification().getTitle() != null
                ? remoteMessage.getNotification().getTitle() : title;
            body = remoteMessage.getNotification().getBody() != null
                ? remoteMessage.getNotification().getBody() : body;
        } else if (!remoteMessage.getData().isEmpty()) {
            title = remoteMessage.getData().getOrDefault("title", title);
            body = remoteMessage.getData().getOrDefault("body", body);
        }
        showNotification(title, body);
    }

    private void showNotification(String title, String body) {
        NotificationManager nm = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        String channelId = "bp_fcm_channel";
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(
                channelId, "Push Notifications", NotificationManager.IMPORTANCE_HIGH);
            nm.createNotificationChannel(channel);
        }
        Intent intent = new Intent(this, MainActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
        PendingIntent pi = PendingIntent.getActivity(this, 0, intent,
            PendingIntent.FLAG_UPDATE_CURRENT | PendingIntent.FLAG_IMMUTABLE);
        NotificationCompat.Builder builder = new NotificationCompat.Builder(this, channelId)
            .setSmallIcon(android.R.drawable.ic_dialog_info)
            .setContentTitle(title)
            .setContentText(body)
            .setStyle(new NotificationCompat.BigTextStyle().bigText(body))
            .setAutoCancel(true)
            .setPriority(NotificationCompat.PRIORITY_HIGH)
            .setContentIntent(pi);
        nm.notify((int) System.currentTimeMillis(), builder.build());
    }
}';
        file_put_contents($buildDir . 'app/src/main/java/' . $packagePath . '/BPFirebaseMessagingService.java', $fcmService);

        // Generate google-services.json
        if (!empty($config['google_services_json'])) {
            // Use the uploaded google-services.json (update package name in it)
            $gsData = json_decode($config['google_services_json'], true);
            if ($gsData && isset($gsData['client'][0]['client_info']['android_client_info'])) {
                $gsData['client'][0]['client_info']['android_client_info']['package_name'] = $config['package_name'];
            }
            file_put_contents($buildDir . 'app/google-services.json', json_encode($gsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            // Fallback: default BP Admin Firebase project
            $googleServicesJson = '{
  "project_info": {
    "project_number": "636630779971",
    "project_id": "bp-admin-e5713",
    "storage_bucket": "bp-admin-e5713.firebasestorage.app"
  },
  "client": [
    {
      "client_info": {
        "mobilesdk_app_id": "1:636630779971:android:9028ad5b0cd0d90c722cf6",
        "android_client_info": {
          "package_name": "' . $config['package_name'] . '"
        }
      },
      "oauth_client": [],
      "api_key": [
        {
          "current_key": "AIzaSyCkOXiRqKvy1H_3Ri3AT8-Dx00OvQvf8SI"
        }
      ],
      "services": {
        "appinvite_service": {
          "other_platform_oauth_client": []
        }
      }
    }
  ],
  "configuration_version": "1"
}';
            file_put_contents($buildDir . 'app/google-services.json', $googleServicesJson);
        }

        // Save firebase-service-account.json to the generated project (for server-side push)
        if (!empty($config['firebase_service_account'])) {
            mkdir($buildDir . 'server-config', 0777, true);
            file_put_contents($buildDir . 'server-config/firebase-service-account.json', $config['firebase_service_account']);
        }
    }

    // Generate settings.gradle
    $settingsGradle = 'pluginManagement {
    repositories {
        google()
        mavenCentral()
        gradlePluginPortal()
    }
}
dependencyResolutionManagement {
    repositoriesMode.set(RepositoriesMode.FAIL_ON_PROJECT_REPOS)
    repositories {
        google()
        mavenCentral()
    }
}

rootProject.name = "' . preg_replace('/[^a-zA-Z0-9]/', '', $config['app_name']) . '"
include \':app\'';
    
    file_put_contents($buildDir . 'settings.gradle', $settingsGradle);
    
    // Generate project level build.gradle
    $projectBuildGradle = '// Top-level build file
plugins {
    id \'com.android.application\' version \'8.7.3\' apply false
' . ($config['push_notifications'] ? "    id 'com.google.gms.google-services' version '4.4.2' apply false" : '') . '
}';
    
    file_put_contents($buildDir . 'build.gradle', $projectBuildGradle);
    
    // Generate gradle.properties
    $gradleProperties = 'org.gradle.jvmargs=-Xmx2048m -Dfile.encoding=UTF-8
android.useAndroidX=true
android.nonTransitiveRClass=true
android.defaults.buildfeatures.buildconfig=true';
    
    file_put_contents($buildDir . 'gradle.properties', $gradleProperties);
    
    // Generate gradle wrapper properties
    $gradleWrapperProps = 'distributionBase=GRADLE_USER_HOME
distributionPath=wrapper/dists
distributionUrl=https\\://services.gradle.org/distributions/gradle-8.11.1-bin.zip
zipStoreBase=GRADLE_USER_HOME
zipStorePath=wrapper/dists';
    
    file_put_contents($buildDir . 'gradle/wrapper/gradle-wrapper.properties', $gradleWrapperProps);
    
    // Create default splash logo drawable
    $splashDrawable = '<?xml version="1.0" encoding="utf-8"?>
<vector xmlns:android="http://schemas.android.com/apk/res/android"
    android:width="150dp"
    android:height="150dp"
    android:viewportWidth="150"
    android:viewportHeight="150">
    <path
        android:fillColor="#FFFFFF"
        android:pathData="M75,15 L135,45 L135,105 L75,135 L15,105 L15,45 Z"/>
    <path
        android:fillColor="' . $config['splash_color'] . '"
        android:pathData="M75,30 L115,52 L115,98 L75,120 L35,98 L35,52 Z"/>
    <path
        android:fillColor="#FFFFFF"
        android:pathData="M60,65 L90,65 L90,85 L60,85 Z"/>
</vector>';
    
    file_put_contents($buildDir . 'app/src/main/res/drawable/splash_logo.xml', $splashDrawable);
    
    // Create launcher icon (ic_launcher)
    $launcherForeground = '<?xml version="1.0" encoding="utf-8"?>
<vector xmlns:android="http://schemas.android.com/apk/res/android"
    android:width="108dp"
    android:height="108dp"
    android:viewportWidth="108"
    android:viewportHeight="108">
    <path
        android:fillColor="#FFFFFF"
        android:pathData="M54,30 L78,42 L78,66 L54,78 L30,66 L30,42 Z"/>
</vector>';
    
    file_put_contents($buildDir . 'app/src/main/res/drawable/ic_launcher_foreground.xml', $launcherForeground);
    
    $launcherBackground = '<?xml version="1.0" encoding="utf-8"?>
<vector xmlns:android="http://schemas.android.com/apk/res/android"
    android:width="108dp"
    android:height="108dp"
    android:viewportWidth="108"
    android:viewportHeight="108">
    <path
        android:fillColor="' . $config['splash_color'] . '"
        android:pathData="M0,0h108v108h-108z"/>
</vector>';
    
    file_put_contents($buildDir . 'app/src/main/res/drawable/ic_launcher_background.xml', $launcherBackground);
    
    // Adaptive icon
    $adaptiveIcon = '<?xml version="1.0" encoding="utf-8"?>
<adaptive-icon xmlns:android="http://schemas.android.com/apk/res/android">
    <background android:drawable="@drawable/ic_launcher_background"/>
    <foreground android:drawable="@drawable/ic_launcher_foreground"/>
</adaptive-icon>';
    
    file_put_contents($buildDir . 'app/src/main/res/mipmap-anydpi-v26/ic_launcher.xml', $adaptiveIcon);
    file_put_contents($buildDir . 'app/src/main/res/mipmap-anydpi-v26/ic_launcher_round.xml', $adaptiveIcon);
    
    // Copy icons if provided
    if ($config['app_icon_path'] && file_exists($config['app_icon_path'])) {
        // Remove adaptive icon XMLs so PNG icons in mipmap-* dirs take priority on Android 8+
        @unlink($buildDir . 'app/src/main/res/mipmap-anydpi-v26/ic_launcher.xml');
        @unlink($buildDir . 'app/src/main/res/mipmap-anydpi-v26/ic_launcher_round.xml');
        
        // Resize icon to proper sizes for each density
        $iconSizes = [
            'mipmap-mdpi' => 48,
            'mipmap-hdpi' => 72,
            'mipmap-xhdpi' => 96,
            'mipmap-xxhdpi' => 144,
            'mipmap-xxxhdpi' => 192,
        ];
        
        $sourceIcon = $config['app_icon_path'];
        $imageInfo = @getimagesize($sourceIcon);
        $sourceImage = null;
        
        if ($imageInfo) {
            switch ($imageInfo[2]) {
                case IMAGETYPE_PNG:
                    $sourceImage = @imagecreatefrompng($sourceIcon);
                    break;
                case IMAGETYPE_JPEG:
                    $sourceImage = @imagecreatefromjpeg($sourceIcon);
                    break;
                case IMAGETYPE_WEBP:
                    $sourceImage = @imagecreatefromwebp($sourceIcon);
                    break;
            }
        }
        
        foreach ($iconSizes as $dir => $size) {
            if ($sourceImage) {
                $resized = imagecreatetruecolor($size, $size);
                // Preserve transparency
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                imagefilledrectangle($resized, 0, 0, $size, $size, $transparent);
                imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $size, $size, $imageInfo[0], $imageInfo[1]);
                imagepng($resized, $buildDir . 'app/src/main/res/' . $dir . '/ic_launcher.png');
                imagepng($resized, $buildDir . 'app/src/main/res/' . $dir . '/ic_launcher_round.png');
                imagedestroy($resized);
            } else {
                // Fallback: just copy the original
                copy($sourceIcon, $buildDir . 'app/src/main/res/' . $dir . '/ic_launcher.png');
                copy($sourceIcon, $buildDir . 'app/src/main/res/' . $dir . '/ic_launcher_round.png');
            }
        }
        
        if ($sourceImage) {
            imagedestroy($sourceImage);
        }
    }
    
    // Copy splash icon if provided
    if ($config['splash_icon_path'] && file_exists($config['splash_icon_path'])) {
        // Remove vector XML to avoid duplicate resource error
        @unlink($buildDir . 'app/src/main/res/drawable/splash_logo.xml');
        copy($config['splash_icon_path'], $buildDir . 'app/src/main/res/drawable/splash_logo.png');
    }
    
    return true;
}

/**
 * Adjust color brightness
 */
function adjustBrightness($hex, $steps) {
    $hex = ltrim($hex, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

/**
 * Create Download Package (ZIP)
 */
function createDownloadPackage($buildDir, $config) {
    // Create README with instructions
    $readme = '# ' . $config['app_name'] . ' - Android App Project

## 📱 App Information
| Property | Value |
|----------|-------|
| App Name | ' . $config['app_name'] . ' |
| Package Name | ' . $config['package_name'] . ' |
| Version | ' . $config['version'] . ' (code: ' . $config['version_code'] . ') |
| Website URL | ' . $config['website_url'] . ' |
| Min SDK | API ' . $config['min_sdk'] . ' |
| Target SDK | 35 |
| Splash Screen | ' . ($config['enable_splash'] ? 'Enabled' : 'Disabled') . ' |
| Generated | ' . $config['created_at'] . ' |

## 🏪 Google Play Store Information
| Property | Value |
|----------|-------|
| App Category | ' . ucwords(str_replace('_', ' ', $config['app_category'])) . ' |
| Privacy Policy | ' . (!empty($config['privacy_policy_url']) ? $config['privacy_policy_url'] : '⚠️ Not set — REQUIRED for Play Store') . ' |
| Description | ' . ($config['description'] ?: 'Not set') . ' |
' . (!empty($config['url_scheme']) ? '| Custom URL Scheme | ' . $config['url_scheme'] . ':// |
' : '') . (!empty($config['app_links_domain']) ? '| App Links Domain | ' . $config['app_links_domain'] . ' |
' : '') . '
### Play Store Checklist
- [ ] Upload app icon (512x512) to Play Console
- [ ] Add at least 2 screenshots
- [ ] Set content rating via questionnaire  
- [ ] Complete Data Safety form
- ' . (!empty($config['privacy_policy_url']) ? '✅' : '❌') . ' Privacy Policy URL
- [ ] Set target audience and content
- [ ] Write full store listing description
- [ ] Generate signed AAB (see below)

## 🚀 How to Build

### Debug APK (Testing)
1. Open in Android Studio
2. **Build > Build Bundle(s) / APK(s) > Build APK(s)**
3. APK: `app/build/outputs/apk/debug/app-debug.apk`

### Release AAB (Play Store Upload)
1. In Android Studio: **Build > Generate Signed Bundle / APK**
2. Select **Android App Bundle**
3. Create new keystore (KEEP THIS FILE SAFE!)
4. Fill in key alias, passwords
5. Select **release** build type
6. AAB will be ready for Play Store upload

### Release APK (Direct Install)
1. Same as above but select **APK** instead of Bundle
2. Select **release** build type
3. Share the signed APK directly

## 📁 Project Structure
```
├── app/
│   ├── src/main/
│   │   ├── java/         # Source code
│   │   ├── res/          # Resources (layouts, icons, etc.)
│   │   └── AndroidManifest.xml
│   └── build.gradle
├── build.gradle
└── settings.gradle
```

## ✨ Features Included
- ✅ WebView with JavaScript support
- ✅ Progress bar while loading
- ' . ($config['enable_splash'] ? '✅' : '❌') . ' Splash screen
- ' . ($config['back_button'] ? '✅' : '❌') . ' Back button navigation
- ' . ($config['file_downloads'] ? '✅' : '❌') . ' File download support
- ' . ($config['pull_to_refresh'] ? '✅' : '❌') . ' Pull-to-refresh
- ' . ($config['share_button'] ? '✅' : '❌') . ' Share button
- ' . ($config['external_links_browser'] ? '✅' : '❌') . ' External links open in browser
- ' . ($config['keep_screen_on'] ? '✅' : '❌') . ' Keep screen awake
- ' . ($config['prevent_screenshots'] ? '✅' : '❌') . ' Screenshot prevention
- ' . ($config['hardware_acceleration'] ? '✅' : '❌') . ' Hardware acceleration
- ' . ($config['push_notifications'] ? '✅' : '❌') . ' Push notifications (FCM)
- ' . ($config['js_bridge'] ? '✅' : '❌') . ' JavaScript Bridge
- ' . ($config['fab_enabled'] ? '✅ ' : '❌ ') . 'Floating Action Button' . ($config['fab_enabled'] ? ' (' . $config['fab_action'] . ')' : '') . '
- ' . ($config['exit_confirmation'] ? '✅' : '❌') . ' Exit confirmation dialog
- ' . ($config['force_dark'] ? '✅' : '❌') . ' Force dark mode
- ' . ($config['file_upload_camera'] ? '✅' : '❌') . ' File upload + camera capture
- ' . ($config['clear_cache'] ? '✅' : '❌') . ' Clear cache on start
- ' . ($config['immersive_mode'] ? '✅' : '❌') . ' Immersive sticky mode
- ' . ($config['admob_enabled'] ? '✅' : '❌') . ' AdMob ads (banner + interstitial)
- ✅ Loading style: ' . $config['loading_style'] . '
- ✅ Network status check
- ✅ ProGuard/R8 minification enabled
' . ($config['js_bridge'] ? '
## 🔌 JavaScript Bridge API
When JS Bridge is enabled, your website can call native Android functions:

```javascript
// Show a native Toast message
AndroidBridge.showToast("Hello from web!");

// Share text via Android share sheet
AndroidBridge.shareText("Check this out!");

// Open URL in system browser
AndroidBridge.openInBrowser("https://google.com");

// Copy text to clipboard
AndroidBridge.copyToClipboard("Copied text");

// Vibrate device (milliseconds)
AndroidBridge.vibrate(200);

// Get app version
let version = AndroidBridge.getAppVersion();

// Make a phone call
AndroidBridge.dial("+1234567890");

// Send email
AndroidBridge.sendEmail("to@email.com", "Subject", "Body");

// Get device info (returns JSON string)
let info = JSON.parse(AndroidBridge.getDeviceInfo());
// info.brand, info.model, info.sdk, info.android

// Close the app
AndroidBridge.closeApp();
```
' : '') . (!empty($config['url_scheme']) ? '
## 🔗 Deep Links
Your app responds to custom URL scheme: `' . $config['url_scheme'] . '://`

Example: `' . $config['url_scheme'] . '://products/123` opens your website at `/products/123`

Use in HTML:
```html
<a href="' . $config['url_scheme'] . '://page">Open in App</a>
```
' : '') . '
## 🎨 Customization
- **Splash Color**: ' . $config['splash_color'] . '
- **Status Bar Color**: ' . $config['status_bar_color'] . '
- **Loading Bar Color**: ' . $config['loading_bar_color'] . '
- **App Icon**: Located in `app/src/main/res/mipmap-*/`

---
Generated by WebToAPK Pro
';
    
    @file_put_contents($buildDir . 'README.md', $readme);
    
    // Check if ZipArchive class exists
    if (!class_exists('ZipArchive')) {
        throw new Exception('ZipArchive extension not installed on server');
    }
    
    // Create ZIP file
    $zipPath = OUTPUT_DIR . $config['build_id'] . '.zip';
    $zip = new ZipArchive();
    
    $openResult = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    if ($openResult !== true) {
        $errorMessages = [
            ZipArchive::ER_EXISTS => 'File already exists',
            ZipArchive::ER_INCONS => 'Inconsistent archive',
            ZipArchive::ER_INVAL => 'Invalid argument',
            ZipArchive::ER_MEMORY => 'Memory allocation failure',
            ZipArchive::ER_NOENT => 'No such file',
            ZipArchive::ER_NOZIP => 'Not a zip archive',
            ZipArchive::ER_OPEN => 'Cannot open file',
            ZipArchive::ER_READ => 'Read error',
            ZipArchive::ER_SEEK => 'Seek error',
        ];
        $errorMsg = $errorMessages[$openResult] ?? "Unknown error code: $openResult";
        throw new Exception("Cannot create ZIP: $errorMsg. Check folder permissions for output/");
    }
    
    // Add files recursively
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($buildDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    $appNameClean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $config['app_name']);
    
    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($buildDir));
            // Skip server-side sensitive files and temp files from ZIP
            if (strpos($relativePath, 'server-config') !== false) continue;
            if (strpos($relativePath, '_google_services_tmp') !== false) continue;
            if ($relativePath === 'config.json') continue;
            $zip->addFile($filePath, $appNameClean . '/' . $relativePath);
        }
    }
    
    $zip->close();
    
    return $zipPath;
}
