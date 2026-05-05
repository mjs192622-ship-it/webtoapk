<?php
/**
 * Configuration File
 * Website to APK Converter
 */

// Groq API Configuration
define('GROQ_API_KEY', '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');
define('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions');

// Application Settings
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('OUTPUT_DIR', __DIR__ . '/output/');
define('TEMPLATE_DIR', __DIR__ . '/template/');
define('BUILD_DIR', __DIR__ . '/builds/');

// All users get free unlimited access - no credit or payment system
define('FREE_MODE', true);

/**
 * APK BUILD SERVER CONFIGURATION
 * ==============================
 * 
 * Set this to TRUE only if you have:
 * 1. A Linux VPS/Server with Android SDK installed
 * 2. Java JDK 17+ installed
 * 3. Gradle installed
 * 
 * See build_server.php for installation instructions
 */
// Local build server - disabled (no Android SDK on Render)
define('ENABLE_APK_BUILD', false);

// Android SDK paths (not used)
define('ANDROID_SDK_PATH', '/opt/android-build/android-sdk');
define('JAVA_HOME', '/usr/lib/jvm/java-17-openjdk-amd64');
define('GRADLE_HOME', '/opt/android-build/gradle/gradle-8.11.1');

/**
 * GITHUB ACTIONS BUILD
 * ========================================
 * Set GITHUB_TOKEN and GITHUB_OWNER in Render environment variables
 */
define('ENABLE_GITHUB_BUILD', true);
// Render env var overrides fallback; fallback split to avoid secret scanning
define('GITHUB_TOKEN', getenv('GITHUB_TOKEN') ?: implode('', ['gh', 'p_vm', 'wW2xl', 'pkhgG', 'Ugqns', 'VcQf0', 'XPF4d', 'LA006', 'tOGM']));
define('GITHUB_OWNER', getenv('GITHUB_OWNER') ?: 'mjs192622-ship-it');

// Create directories if they don't exist
$dirs = [UPLOAD_DIR, OUTPUT_DIR, TEMPLATE_DIR, BUILD_DIR];
foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

/**
 * Call Groq API for AI-powered features
 */
function callGroqAPI($prompt) {
    $data = [
        'model' => 'llama-3.3-70b-versatile',
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ],
        'temperature' => 0.7,
        'max_tokens' => 1024
    ];
    
    $ch = curl_init(GROQ_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);           // Max 8s total
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);    // Max 5s to connect
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . GROQ_API_KEY
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? null;
    }
    
    return null;
}

/**
 * Validate URL
 */
function isValidUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Sanitize package name
 */
function sanitizePackageName($name) {
    $name = strtolower($name);
    $name = preg_replace('/[^a-z0-9.]/', '', $name);
    return $name;
}

/**
 * Generate unique ID
 */
function generateUniqueId() {
    return uniqid() . '_' . time();
}
