<?php require_once __DIR__ . '/auth.php'; $currentUser = getCurrentUser(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web to APK Converter - Convert Any Website to Android App Free | WebTooAPK</title>
    <meta name="description" content="Convert any website to Android APK app free online. WebTooAPK is the best web to APK converter with 50+ features: push notifications, AdMob, splash screen, offline mode, camera, GPS &amp; more. No coding needed!">
    <meta name="keywords" content="web to apk, website to apk, convert website to android app, web to apk converter, website to apk converter, web2apk, webview to apk, url to apk, html to apk, site to app, website to app converter, convert website to app, web to android, webapp to apk, pwa to apk, webtooapk, web too apk">
    <meta name="author" content="WebTooAPK">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="https://webtooapk.com/">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://webtooapk.com/">
    <meta property="og:title" content="Web to APK Converter - Convert Any Website to Android App Free">
    <meta property="og:description" content="Convert any website to Android APK app free online. 50+ features including push notifications, AdMob, offline mode, camera, GPS &amp; more. No coding required!">
    <meta property="og:image" content="https://webtooapk.com/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="WebTooAPK">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://webtooapk.com/">
    <meta name="twitter:title" content="Web to APK Converter - Convert Website to Android App Free">
    <meta name="twitter:description" content="Convert any website to Android APK for free. 50+ features: push notifications, AdMob, splash screen, offline mode &amp; more. No coding needed!">
    <meta name="twitter:image" content="https://webtooapk.com/og-image.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <meta name="theme-color" content="#6366f1">
    <meta name="msapplication-TileColor" content="#6366f1">
    
    <!-- Structured Data / Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "WebTooAPK - Web to APK Converter",
        "url": "https://webtooapk.com",
        "description": "Convert any website to an Android APK app for free. 50+ features including push notifications, AdMob monetization, splash screen, offline mode, camera, GPS, and more.",
        "applicationCategory": "DeveloperApplication",
        "operatingSystem": "Android",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        },
        "featureList": [
            "Website to APK conversion",
            "Push Notifications (FCM)",
            "AdMob integration",
            "Splash Screen",
            "Offline Mode",
            "Camera, Microphone, GPS permissions",
            "File Downloads",
            "Pull to Refresh",
            "Custom JavaScript & CSS injection",
            "Deep Links & URL Schemes",
            "Navigation Drawer & Bottom Navigation",
            "Biometric Authentication",
            "GDPR Consent Dialog",
            "Auto Update Checker",
            "Floating Action Button",
            "Dark Mode Support"
        ],
        "creator": {
            "@type": "Organization",
            "name": "WebTooAPK",
            "url": "https://webtooapk.com"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "How to convert a website to an Android APK app?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Simply enter your website URL on WebTooAPK.com, customize the app name, icon, colors, and features, then click Generate. Your APK will be built automatically using GitHub Actions in 3-5 minutes."
                }
            },
            {
                "@type": "Question",
                "name": "Is WebTooAPK free to use?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes, WebTooAPK is completely free. You can convert unlimited websites to Android APK apps with all 50+ features included at no cost."
                }
            },
            {
                "@type": "Question",
                "name": "Can I add push notifications to my converted app?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes, WebTooAPK supports Firebase Cloud Messaging (FCM) push notifications. Upload your google-services.json file and the app will automatically support push notifications."
                }
            },
            {
                "@type": "Question",
                "name": "What permissions can I add to the APK?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "WebTooAPK supports all major Android permissions including Camera, Microphone, Location/GPS, Contacts, Calendar, Bluetooth, SMS, Call Phone, Call Log, Storage, NFC, Body Sensors, Background Services, Notifications, and Biometric authentication."
                }
            },
            {
                "@type": "Question",
                "name": "Can I monetize my app with ads?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes, WebTooAPK has built-in AdMob integration. You can add banner ads and interstitial ads by simply entering your AdMob App ID and Ad Unit IDs."
                }
            }
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HowTo",
        "name": "How to Convert Website to Android APK",
        "description": "Step-by-step guide to convert any website to an Android APK app using WebTooAPK.",
        "step": [
            {
                "@type": "HowToStep",
                "position": 1,
                "name": "Enter Website URL",
                "text": "Enter the URL of the website you want to convert to an Android app."
            },
            {
                "@type": "HowToStep",
                "position": 2,
                "name": "Configure App Details",
                "text": "Set app name, package name, version, minimum SDK, and orientation."
            },
            {
                "@type": "HowToStep",
                "position": 3,
                "name": "Customize Appearance & Features",
                "text": "Upload app icon, set splash screen, colors, enable features like push notifications, offline mode, camera, GPS, and more."
            },
            {
                "@type": "HowToStep",
                "position": 4,
                "name": "Generate & Download APK",
                "text": "Click Generate and your APK will be built automatically. Download it or install directly on Android devices."
            }
        ]
    }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --accent: #8b5cf6;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --gray: #64748b;
            --light: #f8fafc;
            --white: #ffffff;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-border: rgba(255, 255, 255, 0.5);
            --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            --card-bg: rgba(255, 255, 255, 0.72);
            --input-bg: rgba(255, 255, 255, 0.85);
            --input-border: rgba(148, 163, 184, 0.3);
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: #f0f2f8;
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Smoke Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(145deg, #f0f2f8 0%, #eef1fa 40%, #f5f0f8 70%, #edf4fb 100%);
        }

        .floating-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
        }

        .shape-1 {
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.2) 0%, rgba(244, 114, 182, 0.08) 50%, transparent 70%);
            top: -150px;
            right: -150px;
            filter: blur(60px);
            animation: smokeFloat1 25s infinite ease-in-out;
        }

        .shape-2 {
            width: 650px;
            height: 650px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.18) 0%, rgba(147, 197, 253, 0.1) 50%, transparent 70%);
            bottom: -100px;
            left: -150px;
            filter: blur(60px);
            animation: smokeFloat2 30s infinite ease-in-out;
        }

        .shape-3 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.12) 0%, rgba(196, 181, 253, 0.06) 50%, transparent 70%);
            top: 40%;
            left: 40%;
            filter: blur(80px);
            animation: smokeFloat3 22s infinite ease-in-out;
        }

        @keyframes smokeFloat1 {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.8; }
            25% { transform: translate(-40px, 30px) scale(1.08); opacity: 1; }
            50% { transform: translate(20px, -20px) scale(0.95); opacity: 0.7; }
            75% { transform: translate(-20px, -30px) scale(1.05); opacity: 0.9; }
        }

        @keyframes smokeFloat2 {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.8; }
            33% { transform: translate(50px, -40px) scale(1.1); opacity: 1; }
            66% { transform: translate(-30px, 30px) scale(0.92); opacity: 0.7; }
        }

        @keyframes smokeFloat3 {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
            50% { transform: translate(-40px, 40px) scale(1.15); opacity: 0.9; }
        }

        /* Header */
        .header {
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 10;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 1px 20px rgba(0, 0, 0, 0.04);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
        }

        .logo-text span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 15px;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        /* Header Auth Buttons */
        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .btn-login {
            padding: 9px 22px;
            background: transparent;
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-login:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }
        .btn-signup {
            padding: 9px 22px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            color: white;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(99,102,241,0.2);
        }
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99,102,241,0.35);
        }
        .user-menu-wrap {
            position: relative;
        }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 14px 5px 5px;
            background: var(--glass-bg);
            border: 1px solid var(--input-border);
            border-radius: 50px;
            cursor: pointer;
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 13px;
            transition: all 0.2s;
        }
        .user-pill:hover { background: rgba(255,255,255,0.85); box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .user-pill .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: white;
        }
        .user-pill .caret {
            font-size: 10px;
            color: var(--gray);
            transition: transform 0.2s;
        }
        .user-menu-wrap.open .caret { transform: rotate(180deg); }
        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 200px;
            background: white;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 14px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s;
            z-index: 100;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .user-menu-wrap.open .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            transition: background 0.15s;
        }
        .user-dropdown a:hover {
            background: rgba(99, 102, 241, 0.06);
            color: var(--primary);
        }
        .user-dropdown a i { width: 16px; color: var(--gray); }
        .user-dropdown .dd-divider {
            height: 1px;
            background: rgba(148, 163, 184, 0.15);
            margin: 6px 0;
        }
        .user-dropdown .dd-logout { color: var(--error); }
        .user-dropdown .dd-logout i { color: var(--error); }

        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
            z-index: 10;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            margin-bottom: 50px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(236, 72, 153, 0.08));
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 50px;
            color: var(--primary);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .hero-badge i {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 18px;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* Converter Card */
        .converter-card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 44px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
        }

        /* Steps Indicator */
        .steps {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 40px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 22px;
            border-radius: 50px;
            background: rgba(148, 163, 184, 0.08);
            border: 1px solid rgba(148, 163, 184, 0.12);
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .step.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .step.completed {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(148, 163, 184, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .step.active .step-number {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Form Sections */
        .form-section {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .form-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .section-desc {
            color: var(--text-secondary);
            margin-bottom: 30px;
            font-size: 14px;
        }

        /* Form Groups */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-grid.single {
            grid-template-columns: 1fr;
        }

        .form-group {
            position: relative;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
        }

        .form-label i {
            color: var(--primary);
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 15px;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: white;
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-input option {
            background: white;
            color: var(--text-primary);
        }

        select.form-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 50px;
        }

        /* Icon Upload */
        .icon-upload-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .icon-upload {
            border: 2px dashed rgba(148, 163, 184, 0.3);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.5);
        }

        .icon-upload:hover {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.04);
        }

        .icon-upload.has-image {
            border-color: var(--success);
            background: rgba(16, 185, 129, 0.04);
        }

        .icon-preview {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            border-radius: 18px;
            background: rgba(148, 163, 184, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .icon-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .icon-preview i {
            font-size: 32px;
            color: var(--text-muted);
        }

        .icon-upload-text h4 {
            color: var(--text-primary);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .icon-upload-text p {
            color: var(--text-muted);
            font-size: 12px;
        }

        .icon-upload input[type="file"] {
            display: none;
        }

        /* Color Picker */
        .color-picker-group {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .color-preview {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: 2px solid rgba(148, 163, 184, 0.2);
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .color-preview:hover {
            transform: scale(1.1);
        }

        .color-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Toggle Switch */
        .toggle-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.55);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.2s;
        }

        .toggle-group:hover {
            background: rgba(255, 255, 255, 0.75);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-primary);
        }

        .toggle-label i {
            color: var(--primary);
            font-size: 18px;
        }

        .toggle-label span {
            font-size: 14px;
        }

        .toggle-switch {
            position: relative;
            width: 50px;
            height: 26px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(148, 163, 184, 0.25);
            border-radius: 26px;
            transition: 0.3s;
        }

        .toggle-slider::before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
        }

        .toggle-switch input:checked + .toggle-slider {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }

        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(24px);
        }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 16px 32px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn:hover::after {
            opacity: 1;
        }

        .btn-secondary {
            background: rgba(148, 163, 184, 0.1);
            color: var(--text-secondary);
            border: 1.5px solid var(--input-border);
        }

        .btn-secondary:hover {
            background: rgba(148, 163, 184, 0.15);
            border-color: rgba(148, 163, 184, 0.4);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #8b5cf6, var(--secondary));
            background-size: 200% 200%;
            animation: gradientShift 5s ease infinite;
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
        }

        /* Progress Section */
        .progress-section {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .progress-section.active {
            display: block;
        }

        .progress-circle {
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            position: relative;
        }

        .progress-circle svg {
            transform: rotate(-90deg);
        }

        .progress-circle circle {
            fill: none;
            stroke-width: 8;
        }

        .progress-bg {
            stroke: rgba(148, 163, 184, 0.15);
        }

        .progress-bar {
            stroke: url(#gradient);
            stroke-linecap: round;
            stroke-dasharray: 408;
            stroke-dashoffset: 408;
            transition: stroke-dashoffset 0.5s;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .progress-status {
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }

        .progress-detail {
            font-size: 14px;
            color: var(--primary);
        }

        /* Loading dots animation */
        .loading-dots {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            margin-top: 20px;
        }
        .loading-dots span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary);
            animation: dotPulse 1.4s infinite ease-in-out both;
        }
        .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots span:nth-child(2) { animation-delay: -0.16s; }
        .loading-dots span:nth-child(3) { animation-delay: 0s; }

        @keyframes dotPulse {
            0%, 80%, 100% { transform: scale(0.4); opacity: 0.3; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Result Section */
        .result-section {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .result-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .result-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, var(--success), #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            animation: bounce 1s ease;
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.3);
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .result-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .result-desc {
            color: var(--text-secondary);
            margin-bottom: 30px;
        }

        .download-card {
            background: rgba(16, 185, 129, 0.06);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .download-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .download-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--success), #059669);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .download-details {
            text-align: left;
        }

        .download-details h4 {
            color: var(--text-primary);
            font-size: 18px;
            margin-bottom: 5px;
        }

        .download-details p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .btn-download {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--success), #059669);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-download:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(16, 185, 129, 0.4);
        }

        /* Features Section */
        .features-section {
            margin-top: 60px;
        }

        .features-title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 40px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feature-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(12px);
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.12);
            border-color: rgba(99, 102, 241, 0.2);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);
        }

        .feature-card h3 {
            color: var(--text-primary);
            font-size: 18px;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* AI Suggestion Box */
        .ai-suggestion {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.06), rgba(236, 72, 153, 0.06));
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 12px;
            padding: 15px 20px;
            margin-top: 15px;
            display: none;
        }

        .ai-suggestion.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .ai-suggestion-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .ai-suggestion-header i {
            color: var(--primary);
        }

        .ai-suggestion-header span {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
        }

        .ai-suggestion-text {
            color: var(--text-secondary);
            font-size: 13px;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 32px;
            }

            .converter-card {
                padding: 25px;
            }

            .steps {
                flex-direction: column;
                align-items: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .icon-upload-container {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .btn-group {
                flex-direction: column;
            }
        }

        /* Loader */
        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Smooth scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }

        /* Selection */
        ::selection {
            background: rgba(99, 102, 241, 0.2);
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <a href="https://webtooapk.com/" class="logo" title="WebTooAPK - Free Web to APK Converter">
            <div class="logo-icon">
                <i class="fas fa-rocket"></i>
            </div>
            <div class="logo-text">Web<span>TooAPK</span></div>
        </a>
        <div class="header-right">
            <nav class="nav-links">
                <a href="#features">Features</a>
                <a href="#how-it-works">How it Works</a>
                <a href="#faq">FAQ</a>
            </nav>
            <?php if ($currentUser): ?>
                <div class="user-menu-wrap" id="headerUserMenu">
                    <button class="user-pill" onclick="document.getElementById('headerUserMenu').classList.toggle('open')">
                        <div class="avatar-sm"><?= htmlspecialchars(getInitials($currentUser['name'])) ?></div>
                        <?= htmlspecialchars(explode(' ', $currentUser['name'])[0]) ?>
                        <i class="fas fa-chevron-down caret"></i>
                    </button>
                    <div class="user-dropdown">
                        <a href="dashboard.php"><i class="fas fa-gauge"></i> Dashboard</a>
                        <a href="dashboard.php?tab=settings"><i class="fas fa-gear"></i> Settings</a>
                        <div class="dd-divider"></div>
                        <a href="logout.php" class="dd-logout"><i class="fas fa-right-from-bracket"></i> Sign Out</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn-login">Sign In</a>
                <a href="signup.php" class="btn-signup">Sign Up Free</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="main-container">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-badge">
                <i class="fas fa-bolt"></i>
                <span>Free Web to APK Converter - No Coding Required</span>
            </div>
            <h1>Convert Any Website to <span>Android APK</span> App Free</h1>
            <p>The best <strong>web to APK converter</strong> online. Transform your website into a professional Android app with 50+ features: push notifications, AdMob, camera, GPS, offline mode &amp; more.</p>
        </section>

        <!-- Converter Card -->
        <div class="converter-card">
            <!-- Steps Indicator -->
            <div class="steps">
                <div class="step active" data-step="1">
                    <span class="step-number">1</span>
                    <span>Website Info</span>
                </div>
                <div class="step" data-step="2">
                    <span class="step-number">2</span>
                    <span>App Details</span>
                </div>
                <div class="step" data-step="3">
                    <span class="step-number">3</span>
                    <span>Appearance</span>
                </div>
                <div class="step" data-step="4">
                    <span class="step-number">4</span>
                    <span>Generate</span>
                </div>
            </div>

            <form id="apkForm" enctype="multipart/form-data">
                <!-- Step 1: Website Info -->
                <div class="form-section active" data-section="1">
                    <h2 class="section-title">
                        <i class="fas fa-globe"></i>
                        Website Information
                    </h2>
                    <p class="section-desc">Enter your website URL and we'll analyze it using AI</p>

                    <div class="form-grid single">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-link"></i>
                                Website URL
                            </label>
                            <input type="url" class="form-input" id="website_url" name="website_url" 
                                   placeholder="https://example.com" required>
                        </div>

                        <div class="ai-suggestion" id="urlSuggestion">
                            <div class="ai-suggestion-header">
                                <i class="fas fa-robot"></i>
                                <span>AI Analysis</span>
                            </div>
                            <div class="ai-suggestion-text" id="urlSuggestionText">
                                Analyzing your website...
                            </div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                            Continue
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: App Details -->
                <div class="form-section" data-section="2">
                    <h2 class="section-title">
                        <i class="fas fa-mobile-alt"></i>
                        App Details
                    </h2>
                    <p class="section-desc">Configure your app's basic information</p>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                App Name
                            </label>
                            <input type="text" class="form-input" id="app_name" name="app_name" 
                                   placeholder="My Awesome App" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-box"></i>
                                Package Name
                            </label>
                            <input type="text" class="form-input" id="package_name" name="package_name" 
                                   placeholder="com.myapp.app" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-code-branch"></i>
                                Version
                            </label>
                            <input type="text" class="form-input" id="version" name="version" 
                                   placeholder="1.0.0" value="1.0.0">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-hashtag"></i>
                                Version Code
                            </label>
                            <input type="number" class="form-input" id="version_code" name="version_code" 
                                   placeholder="1" value="1" min="1">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sync-alt"></i>
                                Orientation
                            </label>
                            <select class="form-input" id="orientation" name="orientation">
                                <option value="portrait">Portrait Only</option>
                                <option value="landscape">Landscape Only</option>
                                <option value="both" selected>Both (Auto-rotate)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-expand"></i>
                                Display Mode
                            </label>
                            <select class="form-input" id="fullscreen" name="fullscreen">
                                <option value="yes">Fullscreen</option>
                                <option value="no" selected>With Status Bar</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-android"></i>
                                Min Android Version
                            </label>
                            <select class="form-input" id="min_sdk" name="min_sdk">
                                <option value="21">Android 5.0 (API 21) — Recommended</option>
                                <option value="23">Android 6.0 (API 23)</option>
                                <option value="24">Android 7.0 (API 24)</option>
                                <option value="26">Android 8.0 (API 26)</option>
                                <option value="28">Android 9.0 (API 28)</option>
                                <option value="29">Android 10 (API 29)</option>
                                <option value="30">Android 11 (API 30)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bars"></i>
                                Status Bar Color
                            </label>
                            <div class="color-picker-group">
                                <div class="color-preview" id="statusBarColorPreview" style="background: #4f46e5;" 
                                     onclick="document.getElementById('status_bar_color').click()"></div>
                                <input type="color" class="color-input" id="status_bar_color" name="status_bar_color" value="#4f46e5">
                                <input type="text" class="form-input" id="status_bar_color_text" value="#4f46e5" 
                                       style="flex: 1;" onchange="updateStatusBarColorFromText(this.value)">
                            </div>
                        </div>

                        <!-- Play Store Compliance -->
                        <div style="grid-column: span 2; margin-top:10px; margin-bottom:2px; padding:8px 4px;">
                            <span style="color:var(--success);font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                                <i class="fab fa-google-play"></i> Play Store Settings
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-shield-halved"></i>
                                Privacy Policy URL
                                <span style="font-size:10px;color:var(--error);margin-left:4px;font-weight:600;">Required for Play Store</span>
                            </label>
                            <input type="url" class="form-input" id="privacy_policy_url" name="privacy_policy_url" 
                                   placeholder="https://yoursite.com/privacy-policy">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-layer-group"></i>
                                App Category
                            </label>
                            <select class="form-input" id="app_category" name="app_category">
                                <option value="business">Business</option>
                                <option value="education">Education</option>
                                <option value="entertainment">Entertainment</option>
                                <option value="finance">Finance</option>
                                <option value="food_drink">Food & Drink</option>
                                <option value="health_fitness">Health & Fitness</option>
                                <option value="lifestyle">Lifestyle</option>
                                <option value="music_audio">Music & Audio</option>
                                <option value="news_magazines">News & Magazines</option>
                                <option value="productivity">Productivity</option>
                                <option value="shopping">Shopping</option>
                                <option value="social">Social</option>
                                <option value="tools" selected>Tools</option>
                                <option value="travel_local">Travel & Local</option>
                                <option value="communication">Communication</option>
                                <option value="photography">Photography</option>
                                <option value="sports">Sports</option>
                                <option value="weather">Weather</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                App Description
                                <span style="font-size:11px;color:var(--gray);margin-left:6px;">(auto-filled by AI, editable)</span>
                            </label>
                            <textarea class="form-input" id="app_description" name="app_description" 
                                      rows="3" placeholder="A short description of your app for the Play Store listing..."
                                      style="resize:vertical;font-size:13px;"></textarea>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(1)">
                            <i class="fas fa-arrow-left"></i>
                            Back
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                            Continue
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Appearance -->
                <div class="form-section" data-section="3">
                    <h2 class="section-title">
                        <i class="fas fa-palette"></i>
                        App Appearance
                    </h2>
                    <p class="section-desc">Customize your app's look and feel with icons and splash screen</p>

                    <div class="icon-upload-container">
                        <!-- App Icon -->
                        <div class="icon-upload" id="appIconUpload" onclick="document.getElementById('app_icon').click()">
                            <div class="icon-preview" id="appIconPreview">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="icon-upload-text">
                                <h4>App Icon</h4>
                                <p>512x512 PNG recommended</p>
                            </div>
                            <input type="file" id="app_icon" name="app_icon" accept="image/png,image/jpeg">
                        </div>

                        <!-- Splash Screen Icon -->
                        <div class="icon-upload" id="splashIconUpload" onclick="document.getElementById('splash_icon').click()">
                            <div class="icon-preview" id="splashIconPreview">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="icon-upload-text">
                                <h4>Splash Screen Icon</h4>
                                <p>Center icon when app opens</p>
                            </div>
                            <input type="file" id="splash_icon" name="splash_icon" accept="image/png,image/jpeg">
                        </div>
                    </div>

                    <div class="form-grid" style="margin-top: 25px;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-fill-drip"></i>
                                Splash Background Color
                            </label>
                            <div class="color-picker-group">
                                <div class="color-preview" id="splashColorPreview" style="background: #6366f1;" 
                                     onclick="document.getElementById('splash_color').click()"></div>
                                <input type="color" class="color-input" id="splash_color" name="splash_color" value="#6366f1">
                                <input type="text" class="form-input" id="splash_color_text" value="#6366f1" 
                                       style="flex: 1;" onchange="updateColorFromText(this.value)">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i>
                                Splash Duration (seconds)
                            </label>
                            <select class="form-input" id="splash_duration" name="splash_duration">
                                <option value="1">1 second</option>
                                <option value="2" selected>2 seconds</option>
                                <option value="3">3 seconds</option>
                                <option value="5">5 seconds</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-spinner"></i>
                                Loading Bar Color
                            </label>
                            <div class="color-picker-group">
                                <div class="color-preview" id="loadingBarColorPreview" style="background: #6366f1;" 
                                     onclick="document.getElementById('loading_bar_color').click()"></div>
                                <input type="color" class="color-input" id="loading_bar_color" name="loading_bar_color" value="#6366f1">
                                <input type="text" class="form-input" id="loading_bar_color_text" value="#6366f1" 
                                       style="flex: 1;" onchange="updateLoadingBarColorFromText(this.value)">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-secret"></i>
                                Custom User Agent
                            </label>
                            <input type="text" class="form-input" id="custom_user_agent" name="custom_user_agent" 
                                   placeholder="Leave blank to use default" style="font-size:13px;">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-text-height"></i>
                                WebView Text Size
                            </label>
                            <select class="form-input" id="text_size" name="text_size">
                                <option value="SMALLEST">Smallest</option>
                                <option value="SMALLER">Smaller</option>
                                <option value="NORMAL" selected>Normal</option>
                                <option value="LARGER">Larger</option>
                                <option value="LARGEST">Largest</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-star"></i>
                                Rate App Prompt
                            </label>
                            <select class="form-input" id="rate_app_launches" name="rate_app_launches">
                                <option value="0">Disabled</option>
                                <option value="3">After 3 launches</option>
                                <option value="5" selected>After 5 launches</option>
                                <option value="10">After 10 launches</option>
                                <option value="20">After 20 launches</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-wifi-slash"></i>
                                Custom Offline Page HTML
                                <span style="font-size:11px;color:var(--gray);margin-left:6px;">(blank = use default)</span>
                            </label>
                            <textarea class="form-input" id="offline_page_html" name="offline_page_html" 
                                      rows="3" placeholder="&lt;html&gt;&lt;body&gt;&lt;h1&gt;No Internet&lt;/h1&gt;&lt;p&gt;Please reconnect.&lt;/p&gt;&lt;/body&gt;&lt;/html&gt;" 
                                      style="font-family:monospace;font-size:12px;resize:vertical;"></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-code"></i>
                                JavaScript Injection
                                <span style="font-size:11px;color:var(--gray);margin-left:6px;">(runs on every page load)</span>
                            </label>
                            <textarea class="form-input" id="js_injection" name="js_injection" 
                                      rows="3" placeholder="// Your custom JavaScript here&#10;// e.g. document.body.style.zoom='1.2';" 
                                      style="font-family:monospace;font-size:13px;resize:vertical;"></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-paintbrush"></i>
                                Custom CSS Injection
                                <span style="font-size:11px;color:var(--gray);margin-left:6px;">(injected on every page load)</span>
                            </label>
                            <textarea class="form-input" id="css_injection" name="css_injection" 
                                      rows="3" placeholder="/* Your custom CSS */&#10;body { font-size: 16px !important; }&#10;.header { display: none; }" 
                                      style="font-family:monospace;font-size:13px;resize:vertical;"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-circle-notch"></i>
                                Loading Indicator Style
                            </label>
                            <select class="form-input" id="loading_style" name="loading_style">
                                <option value="linear" selected>Linear Progress Bar</option>
                                <option value="circular">Circular Spinner</option>
                                <option value="both">Both (Bar + Spinner)</option>
                                <option value="none">No Indicator</option>
                            </select>
                        </div>
                    </div>

                    <!-- Custom Deep Links & Actions -->
                    <div style="margin-top:25px; margin-bottom:8px; padding:8px 4px;">
                        <span style="color:#f97316;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                            <i class="fas fa-link"></i> Deep Links & Custom Actions
                        </span>
                        <p style="color:var(--gray);font-size:12px;margin-top:4px;">Create custom URL schemes and native actions for your app</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-at"></i>
                                Custom URL Scheme
                                <span style="font-size:10px;color:var(--gray);margin-left:4px;">(e.g., myapp)</span>
                            </label>
                            <input type="text" class="form-input" id="url_scheme" name="url_scheme" 
                                   placeholder="myapp" style="font-family:monospace;font-size:13px;">
                            <p style="font-size:11px;color:var(--gray);margin-top:4px;">Opens app via <code style="background:rgba(99,102,241,0.08);padding:1px 5px;border-radius:3px;color:var(--text-primary);">myapp://path</code> links</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-globe"></i>
                                App Links Domain
                                <span style="font-size:10px;color:var(--gray);margin-left:4px;">(optional)</span>
                            </label>
                            <input type="text" class="form-input" id="app_links_domain" name="app_links_domain" 
                                   placeholder="yoursite.com" style="font-size:13px;">
                            <p style="font-size:11px;color:var(--gray);margin-top:4px;">Opens app when clicking links from this domain</p>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-comment-dots"></i>
                                Floating Action Button (FAB)
                            </label>
                            <div style="display:grid;grid-template-columns:auto 1fr 1fr;gap:10px;align-items:center;">
                                <label class="toggle-switch" style="margin:0;">
                                    <input type="checkbox" id="fab_enabled" name="fab_enabled">
                                    <span class="toggle-slider"></span>
                                </label>
                                <select class="form-input" id="fab_action" name="fab_action" style="font-size:13px;">
                                    <option value="whatsapp">WhatsApp Chat</option>
                                    <option value="call">Phone Call</option>
                                    <option value="email">Send Email</option>
                                    <option value="url">Open URL</option>
                                    <option value="sms">Send SMS</option>
                                    <option value="telegram">Telegram</option>
                                </select>
                                <input type="text" class="form-input" id="fab_value" name="fab_value" 
                                       placeholder="+923001234567" style="font-size:13px;">
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:8px;">
                                <div>
                                    <label style="color:var(--gray);font-size:11px;">FAB Color</label>
                                    <div class="color-picker-group" style="margin-top:4px;">
                                        <div class="color-preview" id="fabColorPreview" style="background:#25D366;width:36px;height:36px;" 
                                             onclick="document.getElementById('fab_color').click()"></div>
                                        <input type="color" class="color-input" id="fab_color" name="fab_color" value="#25D366">
                                        <input type="text" class="form-input" id="fab_color_text" value="#25D366" 
                                               style="flex:1;font-size:12px;" onchange="updateFabColorFromText(this.value)">
                                    </div>
                                </div>
                                <div>
                                    <label style="color:var(--gray);font-size:11px;">FAB Icon</label>
                                    <select class="form-input" id="fab_icon" name="fab_icon" style="font-size:13px;margin-top:4px;">
                                        <option value="chat">💬 Chat</option>
                                        <option value="phone">📞 Phone</option>
                                        <option value="email">✉️ Email</option>
                                        <option value="whatsapp">🟢 WhatsApp</option>
                                        <option value="help">❓ Help</option>
                                        <option value="cart">🛒 Cart</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-bars-staggered"></i>
                                Custom Toolbar Menu Items
                                <span style="font-size:10px;color:var(--gray);margin-left:4px;">(up to 3 items)</span>
                            </label>
                            <div id="toolbarItemsContainer">
                                <div class="toolbar-item" style="display:grid;grid-template-columns:1fr 1fr auto;gap:8px;margin-bottom:8px;">
                                    <input type="text" class="form-input" name="toolbar_label[]" placeholder="Label (e.g. Home)" style="font-size:13px;padding:10px 14px;">
                                    <input type="text" class="form-input" name="toolbar_url[]" placeholder="URL or path (e.g. /about)" style="font-size:13px;padding:10px 14px;">
                                    <button type="button" onclick="removeToolbarItem(this)" style="background:rgba(239,68,68,0.2);border:none;color:#ef4444;border-radius:8px;padding:10px 14px;cursor:pointer;font-size:14px;">✕</button>
                                </div>
                            </div>
                            <button type="button" onclick="addToolbarItem()" style="background:rgba(99,102,241,0.15);border:1px dashed rgba(99,102,241,0.3);color:var(--primary);border-radius:8px;padding:8px 16px;cursor:pointer;font-size:12px;font-weight:500;width:100%;">
                                <i class="fas fa-plus"></i> Add Menu Item
                            </button>
                        </div>
                    </div>

                    <div style="margin-top: 25px;">
                        <!-- Features Group -->
                        <div style="margin-bottom:8px;padding:8px 4px;">
                            <span style="color:var(--primary);font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                                <i class="fas fa-sliders"></i> App Features
                            </span>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-rocket"></i>
                                <div>
                                    <span>Enable Splash Screen</span>
                                    <div style="font-size:11px;color:var(--gray);">Show branded intro screen on launch</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="enable_splash" name="enable_splash" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-wifi-slash"></i>
                                <div>
                                    <span>Enable Offline Mode</span>
                                    <div style="font-size:11px;color:var(--gray);">Display cached content when offline</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="offline_mode" name="offline_mode">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-arrow-left"></i>
                                <div>
                                    <span>Enable Back Button Navigation</span>
                                    <div style="font-size:11px;color:var(--gray);">Go to previous page on back press</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="back_button" name="back_button" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-download"></i>
                                <div>
                                    <span>Enable File Downloads</span>
                                    <div style="font-size:11px;color:var(--gray);">Save files to device Downloads folder</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="file_downloads" name="file_downloads" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-arrows-rotate"></i>
                                <div>
                                    <span>Enable Pull-to-Refresh</span>
                                    <div style="font-size:11px;color:var(--gray);">Swipe down to reload the page</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="pull_to_refresh" name="pull_to_refresh" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-magnifying-glass-plus"></i>
                                <div>
                                    <span>Enable Zoom Controls</span>
                                    <div style="font-size:11px;color:var(--gray);">Allow pinch-to-zoom in WebView</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="zoom_controls" name="zoom_controls" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-share-nodes"></i>
                                <div>
                                    <span>Enable Share Button</span>
                                    <div style="font-size:11px;color:var(--gray);">Show share icon in app toolbar</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="share_button" name="share_button">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-sun"></i>
                                <div>
                                    <span>Keep Screen Awake</span>
                                    <div style="font-size:11px;color:var(--gray);">Prevent screen from turning off while app is open</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="keep_screen_on" name="keep_screen_on">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-eye-slash"></i>
                                <div>
                                    <span>Prevent Screenshots</span>
                                    <div style="font-size:11px;color:var(--gray);">Block screen capture &amp; app switcher preview</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="prevent_screenshots" name="prevent_screenshots">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-external-link-alt"></i>
                                <div>
                                    <span>Open External Links in Browser</span>
                                    <div style="font-size:11px;color:var(--gray);">Redirect off-domain URLs to Chrome/browser</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="external_links_browser" name="external_links_browser" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-bolt"></i>
                                <div>
                                    <span>Hardware Acceleration</span>
                                    <div style="font-size:11px;color:var(--gray);">GPU-accelerated rendering for smoother UI</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="hardware_acceleration" name="hardware_acceleration" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-cookie-bite"></i>
                                <div>
                                    <span>Accept Third-Party Cookies</span>
                                    <div style="font-size:11px;color:var(--gray);">Required for login on some sites</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="third_party_cookies" name="third_party_cookies" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-bridge"></i>
                                <div>
                                    <span>JavaScript Bridge (Native API)</span>
                                    <div style="font-size:11px;color:var(--gray);">Expose native functions to your website JS</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="js_bridge" name="js_bridge">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-door-open"></i>
                                <div>
                                    <span>Exit Confirmation Dialog</span>
                                    <div style="font-size:11px;color:var(--gray);">Ask &quot;Press back again to exit&quot; before closing</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="exit_confirmation" name="exit_confirmation">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-moon"></i>
                                <div>
                                    <span>Force Dark Mode</span>
                                    <div style="font-size:11px;color:var(--gray);">Apply dark theme to WebView following system setting</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="force_dark" name="force_dark">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-camera-retro"></i>
                                <div>
                                    <span>File Upload + Camera Capture</span>
                                    <div style="font-size:11px;color:var(--gray);">Enable file picker &amp; camera for &lt;input type=file&gt;</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="file_upload_camera" name="file_upload_camera" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-trash-can"></i>
                                <div>
                                    <span>Clear Cache on Start</span>
                                    <div style="font-size:11px;color:var(--gray);">Fresh content on every app launch</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="clear_cache" name="clear_cache">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-expand"></i>
                                <div>
                                    <span>Immersive Sticky Mode</span>
                                    <div style="font-size:11px;color:var(--gray);">Hide navigation bar for true fullscreen experience</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="immersive_mode" name="immersive_mode">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-hand-pointer"></i>
                                <div>
                                    <span>Swipe Gestures (Back/Forward)</span>
                                    <div style="font-size:11px;color:var(--gray);">Swipe left/right to navigate between pages</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="swipe_gestures" name="swipe_gestures">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-table-columns"></i>
                                <div>
                                    <span>Multi-Window / Split Screen</span>
                                    <div style="font-size:11px;color:var(--gray);">Allow app to run in Android split-screen mode</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="multi_window" name="multi_window">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-cookie"></i>
                                <div>
                                    <span>GDPR / Cookie Consent Banner</span>
                                    <div style="font-size:11px;color:var(--gray);">Show EU-compliant cookie consent on first launch</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="gdpr_consent" name="gdpr_consent">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-fingerprint"></i>
                                <div>
                                    <span>App Lock (Biometric / PIN)</span>
                                    <div style="font-size:11px;color:var(--gray);">Require fingerprint or device PIN to open app</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="app_lock" name="app_lock">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Navigation Group -->
                        <div style="margin-top:20px;margin-bottom:8px;padding:8px 4px;">
                            <span style="color:#f97316;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                                <i class="fas fa-compass"></i> Navigation
                            </span>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-bars"></i>
                                <div>
                                    <span>Navigation Drawer (Side Menu)</span>
                                    <div style="font-size:11px;color:var(--gray);">Slide-out side menu with custom links</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="nav_drawer" name="nav_drawer">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Nav Drawer Config -->
                        <div id="nav_drawer_config" style="display:none;margin-top:8px;padding:16px;background:rgba(249,115,22,0.06);border:1px solid rgba(249,115,22,0.15);border-radius:12px;">
                            <h4 style="color:var(--text-primary);margin-bottom:8px;font-size:14px;">
                                <i class="fas fa-bars" style="color:#f97316;"></i> Drawer Menu Items
                                <span style="font-size:10px;color:var(--gray);margin-left:4px;">(up to 6 items)</span>
                            </h4>
                            <div id="drawerItemsContainer">
                                <div class="drawer-item" style="display:grid;grid-template-columns:60px 1fr 1fr auto;gap:8px;margin-bottom:8px;">
                                    <select class="form-input" name="drawer_icon[]" style="font-size:16px;padding:8px;text-align:center;">
                                        <option value="home">🏠</option><option value="info">ℹ️</option><option value="contact">📞</option>
                                        <option value="cart">🛒</option><option value="user">👤</option><option value="settings">⚙️</option>
                                        <option value="star">⭐</option><option value="help">❓</option><option value="link">🔗</option>
                                    </select>
                                    <input type="text" class="form-input" name="drawer_label[]" placeholder="Label (e.g. Home)" style="font-size:13px;padding:10px 14px;">
                                    <input type="text" class="form-input" name="drawer_url[]" placeholder="URL or /path" style="font-size:13px;padding:10px 14px;">
                                    <button type="button" onclick="this.parentElement.remove()" style="background:rgba(239,68,68,0.2);border:none;color:#ef4444;border-radius:8px;padding:10px 14px;cursor:pointer;font-size:14px;">✕</button>
                                </div>
                            </div>
                            <button type="button" onclick="addDrawerItem()" style="background:rgba(249,115,22,0.15);border:1px dashed rgba(249,115,22,0.3);color:#f97316;border-radius:8px;padding:8px 16px;cursor:pointer;font-size:12px;font-weight:500;width:100%;">
                                <i class="fas fa-plus"></i> Add Drawer Item
                            </button>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-ellipsis"></i>
                                <div>
                                    <span>Bottom Navigation Bar</span>
                                    <div style="font-size:11px;color:var(--gray);">Fixed bottom tabs for quick page switching</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="bottom_nav" name="bottom_nav">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Bottom Nav Config -->
                        <div id="bottom_nav_config" style="display:none;margin-top:8px;padding:16px;background:rgba(249,115,22,0.06);border:1px solid rgba(249,115,22,0.15);border-radius:12px;">
                            <h4 style="color:var(--text-primary);margin-bottom:8px;font-size:14px;">
                                <i class="fas fa-ellipsis" style="color:#f97316;"></i> Bottom Tabs
                                <span style="font-size:10px;color:var(--gray);margin-left:4px;">(3-5 tabs)</span>
                            </h4>
                            <div id="bottomNavItemsContainer">
                                <div class="bnav-item" style="display:grid;grid-template-columns:60px 1fr 1fr auto;gap:8px;margin-bottom:8px;">
                                    <select class="form-input" name="bnav_icon[]" style="font-size:16px;padding:8px;text-align:center;">
                                        <option value="home">🏠</option><option value="search">🔍</option><option value="cart">🛒</option>
                                        <option value="user">👤</option><option value="settings">⚙️</option><option value="star">⭐</option>
                                        <option value="bell">🔔</option><option value="chat">💬</option><option value="menu">☰</option>
                                    </select>
                                    <input type="text" class="form-input" name="bnav_label[]" placeholder="Label (e.g. Home)" style="font-size:13px;padding:10px 14px;">
                                    <input type="text" class="form-input" name="bnav_url[]" placeholder="URL or / (main)" style="font-size:13px;padding:10px 14px;">
                                    <button type="button" onclick="this.parentElement.remove()" style="background:rgba(239,68,68,0.2);border:none;color:#ef4444;border-radius:8px;padding:10px 14px;cursor:pointer;font-size:14px;">✕</button>
                                </div>
                            </div>
                            <button type="button" onclick="addBottomNavItem()" style="background:rgba(249,115,22,0.15);border:1px dashed rgba(249,115,22,0.3);color:#f97316;border-radius:8px;padding:8px 16px;cursor:pointer;font-size:12px;font-weight:500;width:100%;">
                                <i class="fas fa-plus"></i> Add Tab
                            </button>
                        </div>

                        <!-- Update Checker -->
                        <div style="margin-top:20px;margin-bottom:8px;padding:8px 4px;">
                            <span style="color:#8b5cf6;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                                <i class="fas fa-cloud-arrow-down"></i> Update Checker
                            </span>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-arrows-rotate"></i>
                                <div>
                                    <span>Auto-Update Checker</span>
                                    <div style="font-size:11px;color:var(--gray);">Check for new version on app launch via JSON URL</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="update_checker" name="update_checker">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Update Checker Config -->
                        <div id="update_checker_config" style="display:none;margin-top:8px;padding:16px;background:rgba(139,92,246,0.06);border:1px solid rgba(139,92,246,0.15);border-radius:12px;">
                            <h4 style="color:var(--text-primary);margin-bottom:8px;font-size:14px;">
                                <i class="fas fa-cloud-arrow-down" style="color:#8b5cf6;"></i> Update Check Configuration
                            </h4>
                            <p style="color:var(--gray);font-size:12px;margin-bottom:12px;">
                                Host a JSON file at this URL with format: <code style="background:rgba(99,102,241,0.08);padding:2px 6px;border-radius:4px;color:var(--text-primary);">{"latest_version": "1.1.0", "update_url": "https://..."}</code>
                            </p>
                            <div class="form-group" style="margin-bottom:0;">
                                <label style="color:var(--gray);font-size:13px;margin-bottom:4px;display:block;">Version Check URL</label>
                                <input type="text" class="form-input" id="update_check_url" name="update_check_url" 
                                       placeholder="https://yoursite.com/version.json" style="font-size:12px;font-family:monospace;">
                            </div>
                        </div>

                        <!-- Monetization Group -->
                        <div style="margin-top:20px;margin-bottom:8px;padding:8px 4px;">
                            <span style="color:#10b981;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                                <i class="fas fa-coins"></i> Monetization
                            </span>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-rectangle-ad"></i>
                                <div>
                                    <span>AdMob Ads</span>
                                    <div style="font-size:11px;color:var(--gray);">Show Google AdMob banner &amp; interstitial ads</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="admob_enabled" name="admob_enabled">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- AdMob Config (shown when admob enabled) -->
                        <div id="admob_config_section" style="display:none;margin-top:8px;padding:16px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.15);border-radius:12px;">
                            <h4 style="color:var(--text-primary);margin-bottom:8px;font-size:14px;">
                                <i class="fas fa-rectangle-ad" style="color:#10b981;"></i> AdMob Configuration
                            </h4>
                            <p style="color:var(--gray);font-size:12px;margin-bottom:12px;">
                                Get your Ad Unit IDs from <a href="https://admob.google.com" target="_blank" style="color:#10b981;">AdMob Console</a>
                            </p>
                            <div class="form-group" style="margin-bottom:12px;">
                                <label style="color:var(--gray);font-size:13px;margin-bottom:4px;display:block;">AdMob App ID</label>
                                <input type="text" class="form-input" id="admob_app_id" name="admob_app_id" 
                                       placeholder="ca-app-pub-xxxxxxxxxxxxxxxx~yyyyyyyyyy" style="font-size:12px;font-family:monospace;">
                            </div>
                            <div class="form-group" style="margin-bottom:12px;">
                                <label style="color:var(--gray);font-size:13px;margin-bottom:4px;display:block;">Banner Ad Unit ID</label>
                                <input type="text" class="form-input" id="admob_banner_id" name="admob_banner_id" 
                                       placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy" style="font-size:12px;font-family:monospace;">
                            </div>
                            <div class="form-group" style="margin-bottom:12px;">
                                <label style="color:var(--gray);font-size:13px;margin-bottom:4px;display:block;">Interstitial Ad Unit ID</label>
                                <input type="text" class="form-input" id="admob_interstitial_id" name="admob_interstitial_id" 
                                       placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy" style="font-size:12px;font-family:monospace;">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label style="color:var(--gray);font-size:13px;margin-bottom:4px;display:block;">Show Interstitial Every N Pages</label>
                                <select class="form-input" id="admob_interstitial_interval" name="admob_interstitial_interval" style="font-size:13px;">
                                    <option value="0">Disabled</option>
                                    <option value="3" selected>Every 3 pages</option>
                                    <option value="5">Every 5 pages</option>
                                    <option value="10">Every 10 pages</option>
                                </select>
                            </div>
                        </div>

                        <!-- Permissions Group -->
                        <div style="margin-top:20px;margin-bottom:8px;padding:8px 4px;">
                            <span style="color:var(--warning);font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                                <i class="fas fa-shield-halved"></i> Permissions
                            </span>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-bell"></i>
                                <div>
                                    <span>Push Notifications</span>
                                    <div style="font-size:11px;color:var(--gray);">FCM-based push notifications via Firebase</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="push_notifications" name="push_notifications" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-camera"></i>
                                <div>
                                    <span>Camera Access</span>
                                    <div style="font-size:11px;color:var(--gray);">For image capture &amp; QR scanning in WebView</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="camera_permission" name="camera_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-location-dot"></i>
                                <div>
                                    <span>Location Access</span>
                                    <div style="font-size:11px;color:var(--gray);">GPS &amp; network location for map features</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="location_permission" name="location_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-microphone"></i>
                                <div>
                                    <span>Microphone Access</span>
                                    <div style="font-size:11px;color:var(--gray);">For voice input, calls &amp; audio recording</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="microphone_permission" name="microphone_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <span>Phone State Access</span>
                                    <div style="font-size:11px;color:var(--gray);">Read device phone number &amp; status</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="phone_state_permission" name="phone_state_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-address-book"></i>
                                <div>
                                    <span>Contacts Access</span>
                                    <div style="font-size:11px;color:var(--gray);">Read &amp; write device contacts</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="contacts_permission" name="contacts_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-calendar"></i>
                                <div>
                                    <span>Calendar Access</span>
                                    <div style="font-size:11px;color:var(--gray);">Read &amp; write device calendar events</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="calendar_permission" name="calendar_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-bluetooth"></i>
                                <div>
                                    <span>Bluetooth Access</span>
                                    <div style="font-size:11px;color:var(--gray);">Scan &amp; connect Bluetooth devices</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="bluetooth_permission" name="bluetooth_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-mobile-screen"></i>
                                <div>
                                    <span>Biometric / Fingerprint</span>
                                    <div style="font-size:11px;color:var(--gray);">Fingerprint &amp; face unlock authentication</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="biometric_permission" name="biometric_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-sms"></i>
                                <div>
                                    <span>SMS Access</span>
                                    <div style="font-size:11px;color:var(--gray);">Send, read &amp; receive SMS messages</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="sms_permission" name="sms_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-phone-volume"></i>
                                <div>
                                    <span>Call Phone</span>
                                    <div style="font-size:11px;color:var(--gray);">Directly make phone calls from app</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="call_phone_permission" name="call_phone_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-list-ol"></i>
                                <div>
                                    <span>Call Log Access</span>
                                    <div style="font-size:11px;color:var(--gray);">Read &amp; write call history logs</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="call_log_permission" name="call_log_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-folder-open"></i>
                                <div>
                                    <span>Full Storage Access</span>
                                    <div style="font-size:11px;color:var(--gray);">Manage all files on device (gallery, downloads etc)</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="storage_permission" name="storage_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-wifi"></i>
                                <div>
                                    <span>NFC Access</span>
                                    <div style="font-size:11px;color:var(--gray);">Near-field communication for contactless</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="nfc_permission" name="nfc_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-heartbeat"></i>
                                <div>
                                    <span>Body Sensors</span>
                                    <div style="font-size:11px;color:var(--gray);">Heart rate, step counter &amp; sensor data</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="body_sensors_permission" name="body_sensors_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-cogs"></i>
                                <div>
                                    <span>Background / Foreground Service</span>
                                    <div style="font-size:11px;color:var(--gray);">Run tasks in background, ignore battery optimization</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="foreground_service_permission" name="foreground_service_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <label class="toggle-label">
                                <i class="fas fa-bell-slash"></i>
                                <div>
                                    <span>Notification Permission</span>
                                    <div style="font-size:11px;color:var(--gray);">Show notifications (Android 13+ requires this)</div>
                                </div>
                            </label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="notification_permission" name="notification_permission">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Firebase Config (shown when push notifications enabled) -->
                        <div id="firebase_config_section" style="margin-top:16px;padding:16px;background:rgba(99,102,241,0.06);border:1px solid rgba(99,102,241,0.15);border-radius:12px;">
                            <h4 style="color:var(--text-primary);margin-bottom:8px;font-size:14px;">
                                <i class="fas fa-fire" style="color:#f59e0b;"></i> Firebase Configuration
                            </h4>
                            <p style="color:var(--gray);font-size:12px;margin-bottom:12px;">
                                Upload your <code style="background:rgba(99,102,241,0.08);padding:2px 6px;border-radius:4px;color:var(--text-primary);">google-services.json</code> from Firebase Console → Project Settings → Your Apps → Download
                            </p>
                            <div class="form-group" style="margin-bottom:12px;">
                                <label style="color:var(--gray);font-size:13px;margin-bottom:4px;display:block;">google-services.json File</label>
                                <input type="file" id="google_services_json" name="google_services_json" accept=".json" 
                                    style="width:100%;padding:10px;background:var(--input-bg);border:1px solid var(--input-border);border-radius:8px;color:var(--text-primary);font-size:13px;">
                            </div>
                            <div class="form-group" style="margin-bottom:12px;">
                                <label style="color:var(--gray);font-size:13px;margin-bottom:4px;display:block;">Firebase Service Account Key (for server-side push)</label>
                                <input type="file" id="firebase_service_account" name="firebase_service_account" accept=".json"
                                    style="width:100%;padding:10px;background:var(--input-bg);border:1px solid var(--input-border);border-radius:8px;color:var(--text-primary);font-size:13px;">
                                <p style="color:var(--gray);font-size:11px;margin-top:4px;">
                                    Firebase Console → Project Settings → Service Accounts → Generate New Private Key
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left"></i>
                            Back
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(4)">
                            Review & Generate
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Review & Generate -->
                <div class="form-section" data-section="4">
                    <h2 class="section-title">
                        <i class="fas fa-check-circle"></i>
                        Review & Generate
                    </h2>
                    <p class="section-desc">Review your settings and generate your Android app</p>

                    <div style="background: rgba(148, 163, 184, 0.06); border-radius: 16px; padding: 25px; margin-bottom: 25px; border: 1px solid rgba(148, 163, 184, 0.1);">
                        <h3 style="color: var(--text-primary); font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-list" style="color: var(--primary);"></i>
                            Configuration Summary
                        </h3>
                        <div id="configSummary" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                            <!-- Will be filled by JS -->
                        </div>
                    </div>

                    <div id="playStoreWarning" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(239, 68, 68, 0.1)); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 16px; padding: 20px; margin-bottom: 25px; display: none;">
                        <h3 style="color: #f59e0b; font-size: 15px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fab fa-google-play"></i>
                            Google Play Store Requirements
                        </h3>
                        <div id="playStoreChecklist" style="font-size: 13px; color: #cbd5e1; line-height: 1.8;"></div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left"></i>
                            Back
                        </button>
                        <button type="submit" class="btn btn-primary" id="generateBtn">
                            <i class="fas fa-magic"></i>
                            Generate APK Project
                        </button>
                    </div>
                </div>
            </form>

            <!-- Progress Section -->
            <div class="progress-section" id="progressSection">
                <div class="progress-circle">
                    <svg width="150" height="150">
                        <defs>
                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#6366f1"/>
                                <stop offset="100%" stop-color="#ec4899"/>
                            </linearGradient>
                        </defs>
                        <circle class="progress-bg" cx="75" cy="75" r="65"/>
                        <circle class="progress-bar" cx="75" cy="75" r="65" id="progressBar"/>
                    </svg>
                    <div class="progress-text" id="progressText">0%</div>
                </div>
                <div class="progress-status" id="progressStatus">Initializing...</div>
                <div class="progress-detail" id="progressDetail">Please wait while we generate your app</div>
            </div>

            <!-- Result Section -->
            <div class="result-section" id="resultSection">
                <div class="result-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h2 class="result-title">App Generated Successfully!</h2>
                <p class="result-desc">Your Android app project is ready for download</p>

                <div class="download-card">
                    <div class="download-info">
                        <div class="download-icon">
                            <i class="fab fa-android"></i>
                        </div>
                        <div class="download-details">
                            <h4 id="downloadAppName">My App</h4>
                            <p id="downloadAppInfo">Android Project • Ready to build</p>
                        </div>
                    </div>
                    <button class="btn-download" id="downloadBtn">
                        <i class="fas fa-download"></i>
                        Download Project ZIP
                    </button>
                </div>

                <p style="color: var(--gray); font-size: 14px; margin-top: 20px;">
                    <i class="fas fa-info-circle"></i>
                    Open in Android Studio and click Build > Build APK to create installable APK
                </p>

                <button class="btn btn-secondary" style="margin-top: 20px;" onclick="resetForm()">
                    <i class="fas fa-plus"></i>
                    Create Another App
                </button>
            </div>
        </div>

        <!-- Features Section -->
        <section class="features-section" id="features">
            <h2 class="features-title">Powerful Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>Custom Splash Screen</h3>
                    <p>Add a beautiful splash screen with your logo when app opens</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Custom Branding</h3>
                    <p>Your own app icon, colors, and branding throughout</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>AI-Powered</h3>
                    <p>Smart suggestions and automatic optimization</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-wifi-slash"></i>
                    </div>
                    <h3>Offline Support</h3>
                    <p>Cache pages for offline viewing</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h3>File Downloads</h3>
                    <p>Handle file downloads within the app</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Native Feel</h3>
                    <p>Full-screen WebView with native navigation</p>
                </div>
            </div>
        </section>
    </main>

    <script>
        let currentStep = 1;
        let downloadUrl = '';

        // Step Navigation
        function nextStep(step) {
            if (step === 2) {
                const url = document.getElementById('website_url').value;
                if (!url) {
                    alert('Please enter a website URL');
                    return;
                }
                analyzeUrl(url);
            }

            if (step === 4) {
                updateSummary();
            }

            showStep(step);
        }

        function prevStep(step) {
            showStep(step);
        }

        function showStep(step) {
            currentStep = step;
            
            // Update step indicators
            document.querySelectorAll('.step').forEach((s, index) => {
                s.classList.remove('active', 'completed');
                if (index + 1 < step) {
                    s.classList.add('completed');
                } else if (index + 1 === step) {
                    s.classList.add('active');
                }
            });

            // Show current section
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            document.querySelector(`[data-section="${step}"]`).classList.add('active');
        }

        // AI URL Analysis
        async function analyzeUrl(url) {
            const suggestion = document.getElementById('urlSuggestion');
            const suggestionText = document.getElementById('urlSuggestionText');
            
            suggestion.classList.add('show');
            suggestionText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analyzing your website with AI...';

            try {
                const response = await fetch('api.php?action=analyze', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'url=' + encodeURIComponent(url)
                });
                const data = await response.json();
                
                if (data.success && data.data) {
                    const d = data.data;
                    suggestionText.innerHTML = `
                        <strong>Suggested App Name:</strong> ${d.app_name || 'N/A'}<br>
                        <strong>Package:</strong> ${d.package_name || 'N/A'}<br>
                        <strong>Orientation:</strong> ${d.orientation || 'portrait'}
                    `;
                    
                    // Auto-fill suggestions
                    if (d.app_name) document.getElementById('app_name').value = d.app_name;
                    if (d.package_name) document.getElementById('package_name').value = d.package_name;
                    if (d.orientation) document.getElementById('orientation').value = d.orientation;
                    if (d.app_name) document.getElementById('app_description').value = 'Mobile app for ' + d.app_name + '. Access all features on your Android device.';
                    // Auto-generate privacy policy suggestion
                    if (!document.getElementById('privacy_policy_url').value) {
                        document.getElementById('privacy_policy_url').placeholder = url.replace(/\/$/, '') + '/privacy-policy';
                    }
                } else {
                    suggestionText.innerHTML = '✓ Website is accessible. Configure your app in the next step.';
                }
            } catch (e) {
                suggestionText.innerHTML = '✓ Ready to continue. Configure your app in the next step.';
            }
        }

        // Auto-generate package name
        document.getElementById('app_name').addEventListener('input', function() {
            const appName = this.value.toLowerCase().replace(/[^a-z0-9]/g, '');
            if (appName) {
                document.getElementById('package_name').value = 'com.' + appName + '.app';
            }
        });

        // Icon Upload Preview
        document.getElementById('app_icon').addEventListener('change', function(e) {
            handleIconUpload(e, 'appIconPreview', 'appIconUpload');
        });

        document.getElementById('splash_icon').addEventListener('change', function(e) {
            handleIconUpload(e, 'splashIconPreview', 'splashIconUpload');
        });

        function handleIconUpload(e, previewId, uploadId) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).innerHTML = `<img src="${e.target.result}" alt="Icon">`;
                    document.getElementById(uploadId).classList.add('has-image');
                };
                reader.readAsDataURL(file);
            }
        }

        // Color Picker
        document.getElementById('splash_color').addEventListener('input', function() {
            document.getElementById('splashColorPreview').style.background = this.value;
            document.getElementById('splash_color_text').value = this.value;
        });

        function updateColorFromText(value) {
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                document.getElementById('splash_color').value = value;
                document.getElementById('splashColorPreview').style.background = value;
            }
        }

        // Status Bar Color Picker
        document.getElementById('status_bar_color').addEventListener('input', function() {
            document.getElementById('statusBarColorPreview').style.background = this.value;
            document.getElementById('status_bar_color_text').value = this.value;
        });

        function updateStatusBarColorFromText(value) {
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                document.getElementById('status_bar_color').value = value;
                document.getElementById('statusBarColorPreview').style.background = value;
            }
        }

        // Loading Bar Color Picker
        document.getElementById('loading_bar_color').addEventListener('input', function() {
            document.getElementById('loadingBarColorPreview').style.background = this.value;
            document.getElementById('loading_bar_color_text').value = this.value;
        });

        function updateLoadingBarColorFromText(value) {
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                document.getElementById('loading_bar_color').value = value;
                document.getElementById('loadingBarColorPreview').style.background = value;
            }
        }

        // FAB Color Picker
        document.getElementById('fab_color').addEventListener('input', function() {
            document.getElementById('fabColorPreview').style.background = this.value;
            document.getElementById('fab_color_text').value = this.value;
        });

        function updateFabColorFromText(value) {
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                document.getElementById('fab_color').value = value;
                document.getElementById('fabColorPreview').style.background = value;
            }
        }

        // FAB action placeholder updater
        document.getElementById('fab_action').addEventListener('change', function() {
            const placeholders = {
                whatsapp: '+923001234567',
                call: '+923001234567',
                email: 'support@yoursite.com',
                url: 'https://yoursite.com/help',
                sms: '+923001234567',
                telegram: 'yourusername'
            };
            document.getElementById('fab_value').placeholder = placeholders[this.value] || '';
        });

        // Toolbar Items Management
        function addToolbarItem() {
            const container = document.getElementById('toolbarItemsContainer');
            if (container.children.length >= 3) { alert('Maximum 3 toolbar items allowed'); return; }
            const div = document.createElement('div');
            div.className = 'toolbar-item';
            div.style.cssText = 'display:grid;grid-template-columns:1fr 1fr auto;gap:8px;margin-bottom:8px;';
            div.innerHTML = `
                <input type="text" class="form-input" name="toolbar_label[]" placeholder="Label" style="font-size:13px;padding:10px 14px;">
                <input type="text" class="form-input" name="toolbar_url[]" placeholder="URL or path" style="font-size:13px;padding:10px 14px;">
                <button type="button" onclick="removeToolbarItem(this)" style="background:rgba(239,68,68,0.2);border:none;color:#ef4444;border-radius:8px;padding:10px 14px;cursor:pointer;font-size:14px;">✕</button>
            `;
            container.appendChild(div);
        }

        function removeToolbarItem(btn) {
            btn.parentElement.remove();
        }

        // Update Summary
        function updateSummary() {
            const summary = document.getElementById('configSummary');
            const features = [];
            if (document.getElementById('enable_splash').checked) features.push('Splash Screen');
            if (document.getElementById('offline_mode').checked) features.push('Offline Mode');
            if (document.getElementById('back_button').checked) features.push('Back Navigation');
            if (document.getElementById('file_downloads').checked) features.push('File Downloads');
            if (document.getElementById('pull_to_refresh').checked) features.push('Pull-to-Refresh');
            if (document.getElementById('zoom_controls').checked) features.push('Zoom');
            if (document.getElementById('share_button').checked) features.push('Share Button');
            if (document.getElementById('push_notifications').checked) features.push('Push Notifications');
            if (document.getElementById('keep_screen_on').checked) features.push('Keep Screen On');
            if (document.getElementById('prevent_screenshots').checked) features.push('Screenshot Prevention');
            if (document.getElementById('external_links_browser').checked) features.push('External Links → Browser');
            if (document.getElementById('hardware_acceleration').checked) features.push('Hardware Accel');
            if (document.getElementById('third_party_cookies').checked) features.push('3rd-Party Cookies');
            if (document.getElementById('js_bridge').checked) features.push('JS Bridge');
            if (document.getElementById('exit_confirmation').checked) features.push('Exit Confirmation');
            if (document.getElementById('force_dark').checked) features.push('Dark Mode');
            if (document.getElementById('file_upload_camera').checked) features.push('File Upload/Camera');
            if (document.getElementById('clear_cache').checked) features.push('Clear Cache');
            if (document.getElementById('immersive_mode').checked) features.push('Immersive Mode');
            if (document.getElementById('admob_enabled').checked) features.push('AdMob Ads');
            if (document.getElementById('swipe_gestures').checked) features.push('Swipe Gestures');
            if (document.getElementById('multi_window').checked) features.push('Multi-Window');
            if (document.getElementById('gdpr_consent').checked) features.push('GDPR Consent');
            if (document.getElementById('app_lock').checked) features.push('App Lock');
            if (document.getElementById('nav_drawer').checked) features.push('Nav Drawer');
            if (document.getElementById('bottom_nav').checked) features.push('Bottom Nav');
            if (document.getElementById('update_checker').checked) features.push('Auto-Update');
            if (document.getElementById('fab_enabled').checked) features.push('FAB (' + document.getElementById('fab_action').value + ')');
            if (document.getElementById('camera_permission').checked) features.push('Camera');
            if (document.getElementById('location_permission').checked) features.push('Location');
            if (document.getElementById('microphone_permission').checked) features.push('Microphone');
            if (document.getElementById('bluetooth_permission').checked) features.push('Bluetooth');
            if (document.getElementById('biometric_permission').checked) features.push('Biometric');
            if (document.getElementById('sms_permission').checked) features.push('SMS');
            if (document.getElementById('call_phone_permission').checked) features.push('Call Phone');
            if (document.getElementById('call_log_permission').checked) features.push('Call Log');
            if (document.getElementById('storage_permission').checked) features.push('Full Storage');
            if (document.getElementById('nfc_permission').checked) features.push('NFC');
            if (document.getElementById('body_sensors_permission').checked) features.push('Body Sensors');
            if (document.getElementById('foreground_service_permission').checked) features.push('Background Service');
            if (document.getElementById('notification_permission').checked) features.push('Notifications');

            const minSdkEl = document.getElementById('min_sdk');
            const minSdkText = minSdkEl.options[minSdkEl.selectedIndex].text;

            const items = [
                { label: 'Website URL', value: document.getElementById('website_url').value },
                { label: 'App Name', value: document.getElementById('app_name').value },
                { label: 'Package Name', value: document.getElementById('package_name').value },
                { label: 'Version', value: document.getElementById('version').value + ' (code: ' + document.getElementById('version_code').value + ')' },
                { label: 'Orientation', value: document.getElementById('orientation').value },
                { label: 'Display Mode', value: document.getElementById('fullscreen').value === 'yes' ? 'Fullscreen' : 'With Status Bar' },
                { label: 'Min Android', value: minSdkText },
                { label: 'Status Bar Color', value: `<span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:${document.getElementById('status_bar_color').value};margin-right:4px;vertical-align:middle;"></span>${document.getElementById('status_bar_color').value}` },
                { label: 'Splash Color', value: `<span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:${document.getElementById('splash_color').value};margin-right:4px;vertical-align:middle;"></span>${document.getElementById('splash_color').value}` },
                { label: 'Loading Bar', value: `<span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:${document.getElementById('loading_bar_color').value};margin-right:4px;vertical-align:middle;"></span>${document.getElementById('loading_bar_color').value}` },
                { label: 'Features & Permissions', value: features.length ? features.join(', ') : 'None' },
                { label: 'Text Size', value: document.getElementById('text_size').value },
                { label: 'Rate App Prompt', value: (() => { const v = document.getElementById('rate_app_launches').value; return v === '0' ? 'Disabled' : 'After ' + v + ' launches'; })() },
                { label: 'Custom User Agent', value: document.getElementById('custom_user_agent').value || 'Default' },
                { label: 'Privacy Policy', value: document.getElementById('privacy_policy_url').value || '<span style="color:var(--error);">Not set</span>' },
                { label: 'App Category', value: document.getElementById('app_category').options[document.getElementById('app_category').selectedIndex].text },
                { label: 'URL Scheme', value: document.getElementById('url_scheme').value ? (document.getElementById('url_scheme').value + '://') : 'None' },
            ];

            summary.innerHTML = items.map(item => `
                <div style="background: rgba(148,163,184,0.06); padding: 12px 15px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.1);">
                    <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 4px;">${item.label}</div>
                    <div style="color: var(--text-primary); font-size: 14px; font-weight: 500; word-break: break-all;">${item.value}</div>
                </div>
            `).join('');

            // Play Store compliance checklist
            const privacyUrl = document.getElementById('privacy_policy_url').value;
            const appName = document.getElementById('app_name').value;
            const pkgName = document.getElementById('package_name').value;
            const checks = [
                { ok: !!privacyUrl, text: 'Privacy Policy URL is set', fail: 'Privacy Policy URL is REQUIRED — add it above' },
                { ok: appName.length >= 2, text: 'App name is set', fail: 'App name is required' },
                { ok: pkgName.includes('.') && pkgName.length >= 5, text: 'Valid package name format', fail: 'Package name should be like com.company.app' },
                { ok: true, text: 'Target SDK 35 (latest, meets Google requirement)' },
                { ok: true, text: 'AAB (App Bundle) build included for Play Store upload' },
                { ok: true, text: 'Android 12+ backup rules configured' },
            ];
            const hasIssues = checks.some(c => !c.ok);
            const warningEl = document.getElementById('playStoreWarning');
            warningEl.style.display = 'block';
            if (!hasIssues) {
                warningEl.style.background = 'linear-gradient(135deg, rgba(34, 197, 94, 0.12), rgba(16, 185, 129, 0.08))';
                warningEl.style.borderColor = 'rgba(34, 197, 94, 0.3)';
                warningEl.querySelector('h3').style.color = '#22c55e';
                warningEl.querySelector('h3').innerHTML = '<i class="fab fa-google-play"></i> Play Store Ready';
            }
            document.getElementById('playStoreChecklist').innerHTML = checks.map(c =>
                `<div>${c.ok ? '✅' : '❌'} ${c.ok ? c.text : (c.fail || c.text)}</div>`
            ).join('') + '<div style="margin-top:10px;font-size:12px;color:#94a3b8;">After downloading, sign with your keystore and build AAB for Play Store submission.</div>';
        }

        // Form Submit
        document.getElementById('apkForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('generateBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Generating...';

            // Hide form sections and show progress
            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            document.querySelector('.steps').style.display = 'none';
            document.getElementById('progressSection').classList.add('active');

            // Simulate progress
            let progress = 0;
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const progressStatus = document.getElementById('progressStatus');
            const progressDetail = document.getElementById('progressDetail');

            const stages = [
                { progress: 20, status: 'Analyzing website...', detail: 'Checking URL accessibility' },
                { progress: 40, status: 'Generating Android project...', detail: 'Creating MainActivity.java' },
                { progress: 60, status: 'Creating splash screen...', detail: 'Setting up SplashActivity.java' },
                { progress: 80, status: 'Configuring resources...', detail: 'Adding icons and layouts' },
                { progress: 95, status: 'Packaging project...', detail: 'Creating ZIP archive' },
            ];

            for (const stage of stages) {
                await new Promise(resolve => setTimeout(resolve, 800));
                progress = stage.progress;
                const offset = 408 - (408 * progress / 100);
                progressBar.style.strokeDashoffset = offset;
                progressText.textContent = progress + '%';
                progressStatus.textContent = stage.status;
                progressDetail.textContent = stage.detail;
            }

            // Actually submit the form
            const formData = new FormData(this);
            
            try {
                const response = await fetch('generate.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                // Complete progress
                progressBar.style.strokeDashoffset = 0;
                progressText.textContent = '100%';
                progressStatus.textContent = 'Complete!';

                await new Promise(resolve => setTimeout(resolve, 500));

                if (data.success) {
                    downloadUrl = data.download_url;
                    const appName = document.getElementById('app_name').value;
                    document.getElementById('downloadAppName').textContent = appName;
                    
                    // Check if APK was built or just source code
                    if (data.type === 'apk') {
                        document.getElementById('downloadAppInfo').textContent = 'Android APK • Ready to install';
                        document.getElementById('downloadBtn').innerHTML = '<i class="fas fa-download"></i> Download APK';
                        document.getElementById('downloadBtn').onclick = () => window.location.href = downloadUrl;
                        
                        // Show source code option too
                        if (data.source_url) {
                            const sourceBtn = document.createElement('button');
                            sourceBtn.className = 'btn-download';
                            sourceBtn.style.marginTop = '10px';
                            sourceBtn.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
                            sourceBtn.innerHTML = '<i class="fas fa-code"></i> Download Source Code';
                            sourceBtn.onclick = () => window.location.href = data.source_url;
                            document.querySelector('.download-card').appendChild(sourceBtn);
                        }
                    } else if (data.type === 'local_build') {
                        // Self-hosted build - show live progress
                        showLocalBuildStatus(data.build_id, downloadUrl);
                    } else if (data.type === 'github') {
                        // GitHub Actions build - show auto-refreshing status
                        showGitHubBuildStatus(data.build_id, data.actions_url, data.releases_url, downloadUrl);
                    } else {
                        document.getElementById('downloadAppInfo').textContent = 'Android Project • Build in Android Studio';
                        document.getElementById('downloadBtn').innerHTML = '<i class="fas fa-download"></i> Download Project ZIP';
                        document.getElementById('downloadBtn').onclick = () => window.location.href = downloadUrl;
                    }
                    
                    document.getElementById('progressSection').classList.remove('active');
                    document.getElementById('resultSection').classList.add('active');
                } else {
                    alert('Error: ' + data.message);
                    resetForm();
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
                resetForm();
            }
        });

        // Reset Form
        function resetForm() {
            currentStep = 1;
            document.getElementById('apkForm').reset();
            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            document.querySelector('[data-section="1"]').classList.add('active');
            document.querySelector('.steps').style.display = 'flex';
            document.getElementById('progressSection').classList.remove('active');
            document.getElementById('resultSection').classList.remove('active');
            document.getElementById('generateBtn').disabled = false;
            document.getElementById('generateBtn').innerHTML = '<i class="fas fa-magic"></i> Generate APK Project';
            
            // Reset icons
            document.getElementById('appIconPreview').innerHTML = '<i class="fas fa-image"></i>';
            document.getElementById('splashIconPreview').innerHTML = '<i class="fas fa-mobile-alt"></i>';
            document.getElementById('appIconUpload').classList.remove('has-image');
            document.getElementById('splashIconUpload').classList.remove('has-image');
            
            // Reset steps
            document.querySelectorAll('.step').forEach((s, i) => {
                s.classList.remove('active', 'completed');
                if (i === 0) s.classList.add('active');
            });

            // Reset suggestion
            document.getElementById('urlSuggestion').classList.remove('show');
            
            // Clear any status check intervals
            if (window.githubStatusInterval) {
                clearInterval(window.githubStatusInterval);
                window.githubStatusInterval = null;
            }
            if (window.buildStatusInterval) {
                clearInterval(window.buildStatusInterval);
                window.buildStatusInterval = null;
            }
        }

        // ===== LOCAL BUILD STATUS (Self-Hosted) =====
        
        function showLocalBuildStatus(buildId, sourceUrl) {
            currentBuildId = buildId;
            
            document.getElementById('downloadAppInfo').innerHTML = `
                <span style="color: #f59e0b;"><i class="fas fa-hammer"></i> Building APK...</span><br>
                <span style="font-size: 12px; color: #94a3b8;">Compiling on our server • Auto-updating</span>
            `;
            
            const downloadCard = document.querySelector('.download-card');
            
            // Clear previous extra buttons
            const existingExtras = downloadCard.querySelectorAll('button:not(#downloadBtn), .status-box, .build-progress-box');
            existingExtras.forEach(el => el.remove());
            
            // Main button - disabled during build
            document.getElementById('downloadBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Building APK...';
            document.getElementById('downloadBtn').disabled = true;
            document.getElementById('downloadBtn').style.opacity = '0.7';
            document.getElementById('downloadBtn').style.cursor = 'wait';
            
            // Add progress container
            const progressBox = document.createElement('div');
            progressBox.className = 'build-progress-box';
            progressBox.id = 'buildProgressBox';
            progressBox.style.cssText = 'margin-top: 20px; padding: 24px; background: rgba(99, 102, 241, 0.06); border-radius: 16px; border: 1px solid rgba(99, 102, 241, 0.15);';
            progressBox.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b, #f97316); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-cog fa-spin" style="color: white; font-size: 18px;" id="buildStatusIcon"></i>
                    </div>
                    <div>
                        <div style="color: var(--text-primary); font-weight: 600; font-size: 15px;" id="buildStatusTitle">Compiling APK</div>
                        <div style="color: var(--text-secondary); font-size: 12px;" id="buildStatusSub">Setting up build environment...</div>
                    </div>
                </div>
                
                <!-- Progress bar -->
                <div style="background: rgba(99, 102, 241, 0.1); border-radius: 8px; height: 8px; overflow: hidden; margin-bottom: 12px;">
                    <div id="buildProgressBar" style="height: 100%; border-radius: 8px; background: linear-gradient(90deg, #6366f1, #8b5cf6, #a78bfa); width: 5%; transition: width 0.8s ease;"></div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span id="buildProgressPercent" style="color: var(--text-primary); font-weight: 600; font-size: 14px;">5%</span>
                    <span id="buildStatusTime" style="color: var(--text-muted); font-size: 11px;">Just started</span>
                </div>
                
                <!-- Build log preview -->
                <div id="buildLogPreview" style="margin-top: 16px; padding: 12px; background: rgba(0,0,0,0.03); border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--text-muted); max-height: 80px; overflow: hidden; display: none;">
                </div>
            `;
            downloadCard.appendChild(progressBox);
            
            // Add source code button
            const sourceBtn = document.createElement('button');
            sourceBtn.className = 'btn-download';
            sourceBtn.style.cssText = 'margin-top: 15px; background: linear-gradient(135deg, #6366f1, #8b5cf6);';
            sourceBtn.innerHTML = '<i class="fas fa-code"></i> Download Source Code';
            sourceBtn.onclick = () => window.location.href = sourceUrl;
            downloadCard.appendChild(sourceBtn);
            
            // Start polling
            checkLocalBuildStatus(buildId, sourceUrl);
            window.buildStatusInterval = setInterval(() => {
                checkLocalBuildStatus(buildId, sourceUrl);
            }, 5000); // Check every 5 seconds
        }
        
        async function checkLocalBuildStatus(buildId, sourceUrl) {
            try {
                const response = await fetch(`api.php?action=build_status&build_id=${buildId}`);
                const data = await response.json();
                
                if (!data.success) {
                    document.getElementById('buildStatusSub').textContent = 'Checking build status...';
                    return;
                }
                
                const progressBar = document.getElementById('buildProgressBar');
                const progressPercent = document.getElementById('buildProgressPercent');
                const statusTitle = document.getElementById('buildStatusTitle');
                const statusSub = document.getElementById('buildStatusSub');
                const statusIcon = document.getElementById('buildStatusIcon');
                const statusTime = document.getElementById('buildStatusTime');
                const logPreview = document.getElementById('buildLogPreview');
                const progressBox = document.getElementById('buildProgressBox');
                
                // Update progress bar
                if (progressBar) progressBar.style.width = data.progress + '%';
                if (progressPercent) progressPercent.textContent = data.progress + '%';
                
                // Update time
                if (statusTime) {
                    statusTime.textContent = 'Last check: ' + new Date().toLocaleTimeString();
                }
                
                // Show log preview
                if (logPreview && data.log_tail) {
                    const lines = data.log_tail.trim().split('\n').slice(-3);
                    logPreview.textContent = lines.join('\n');
                    logPreview.style.display = 'block';
                }
                
                // Status-specific updates
                if (data.status === 'pending') {
                    statusTitle.textContent = 'Queued';
                    statusSub.textContent = 'Waiting to start build...';
                } else if (data.status === 'building') {
                    statusTitle.textContent = 'Compiling APK';
                    
                    if (data.progress < 30) {
                        statusSub.textContent = 'Setting up build environment...';
                    } else if (data.progress < 50) {
                        statusSub.textContent = 'Generating resources...';
                    } else if (data.progress < 70) {
                        statusSub.textContent = 'Compiling Java source code...';
                    } else if (data.progress < 85) {
                        statusSub.textContent = 'Merging & packaging resources...';
                    } else {
                        statusSub.textContent = 'Assembling APK file...';
                    }
                } else if (data.status === 'completed' && data.apk_ready) {
                    // BUILD SUCCESS!
                    if (window.buildStatusInterval) {
                        clearInterval(window.buildStatusInterval);
                        window.buildStatusInterval = null;
                    }
                    
                    // Update header
                    document.getElementById('downloadAppInfo').innerHTML = `
                        <span style="color: #10b981;"><i class="fas fa-check-circle"></i> APK Ready!</span><br>
                        <span style="font-size: 12px; color: #94a3b8;">${data.apk_file_name || 'app-debug.apk'}${data.apk_size ? ' • ' + (data.apk_size / 1024 / 1024).toFixed(2) + ' MB' : ''}</span>
                    `;
                    
                    // Update main button
                    const dlBtn = document.getElementById('downloadBtn');
                    dlBtn.innerHTML = '<i class="fas fa-download"></i> Download APK';
                    dlBtn.disabled = false;
                    dlBtn.style.opacity = '1';
                    dlBtn.style.cursor = 'pointer';
                    dlBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                    dlBtn.onclick = () => window.location.href = data.apk_download_url;
                    
                    // Update progress box
                    if (progressBox) {
                        progressBox.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                        progressBox.style.background = 'rgba(16, 185, 129, 0.08)';
                        progressBox.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-check" style="color: white; font-size: 22px;"></i>
                                </div>
                                <div>
                                    <div style="color: #10b981; font-weight: 700; font-size: 17px;">APK Build Complete!</div>
                                    <div style="color: var(--text-secondary); font-size: 13px; margin-top: 2px;">
                                        Your app is ready to download and install
                                        ${data.apk_size ? ' • ' + (data.apk_size / 1024 / 1024).toFixed(2) + ' MB' : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                } else if (data.status === 'failed') {
                    // BUILD FAILED
                    if (window.buildStatusInterval) {
                        clearInterval(window.buildStatusInterval);
                        window.buildStatusInterval = null;
                    }
                    
                    document.getElementById('downloadAppInfo').innerHTML = `
                        <span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Build Failed</span><br>
                        <span style="font-size: 12px; color: #94a3b8;">You can still download the source code</span>
                    `;
                    
                    // Update button to download source
                    const dlBtn = document.getElementById('downloadBtn');
                    dlBtn.innerHTML = '<i class="fas fa-code"></i> Download Source Code';
                    dlBtn.disabled = false;
                    dlBtn.style.opacity = '1';
                    dlBtn.style.cursor = 'pointer';
                    dlBtn.onclick = () => window.location.href = sourceUrl;
                    
                    if (progressBox) {
                        progressBox.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                        progressBox.style.background = 'rgba(239, 68, 68, 0.08)';
                        progressBox.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                                <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #ef4444, #dc2626); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-times" style="color: white; font-size: 22px;"></i>
                                </div>
                                <div>
                                    <div style="color: #ef4444; font-weight: 700; font-size: 17px;">Build Failed</div>
                                    <div style="color: var(--text-secondary); font-size: 13px; margin-top: 2px;">
                                        ${data.error_message ? data.error_message.substring(0, 150) : 'The APK compilation failed. Download source code and build in Android Studio.'}
                                    </div>
                                </div>
                            </div>
                            ${data.log_tail ? `<pre style="margin: 0; padding: 12px; background: rgba(0,0,0,0.04); border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 10px; color: #94a3b8; max-height: 120px; overflow-y: auto; white-space: pre-wrap;">${data.log_tail.split('\\n').slice(-8).join('\\n')}</pre>` : ''}
                        `;
                    }
                }
                
            } catch (error) {
                console.error('Error checking build status:', error);
            }
        }

        // ===== GitHub Build Status Auto-Refresh =====
        let currentBuildId = null;
        
        function showGitHubBuildStatus(buildId, actionsUrl, releasesUrl, sourceUrl) {
            currentBuildId = buildId;
            
            // Initial UI setup
            document.getElementById('downloadAppInfo').innerHTML = `
                <span style="color: #f59e0b;"><i class="fas fa-spinner fa-spin"></i> Building on GitHub...</span><br>
                <span style="font-size: 12px; color: #94a3b8;">Auto-checking status every 15 seconds</span>
            `;
            
            const downloadCard = document.querySelector('.download-card');
            
            // Clear previous buttons
            const existingBtns = downloadCard.querySelectorAll('button:not(#downloadBtn), .status-box');
            existingBtns.forEach(btn => btn.remove());
            
            // Main button - View Build Progress
            document.getElementById('downloadBtn').innerHTML = '<i class="fab fa-github"></i> View Build Progress';
            document.getElementById('downloadBtn').onclick = () => window.open(actionsUrl, '_blank');
            
            // Add status container
            const statusBox = document.createElement('div');
            statusBox.className = 'status-box';
            statusBox.id = 'githubStatusBox';
            statusBox.style.cssText = 'margin-top: 20px; padding: 20px; background: rgba(99, 102, 241, 0.06); border-radius: 12px; border: 1px solid rgba(99, 102, 241, 0.15);';
            statusBox.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                    <i class="fas fa-cog fa-spin" style="color: #f59e0b; font-size: 20px;"></i>
                    <span style="color: var(--text-primary); font-weight: 600;">Build in Progress</span>
                </div>
                <p style="color: var(--text-secondary); font-size: 13px; margin: 0 0 15px 0;">
                    GitHub Actions is compiling your APK. This usually takes 3-5 minutes.
                    The download button will appear automatically when ready.
                </p>
                <div id="buildStatusDetails" style="color: var(--text-muted); font-size: 12px;">
                    Status: Checking...
                </div>
            `;
            downloadCard.appendChild(statusBox);
            
            // Add source code button
            const sourceBtn = document.createElement('button');
            sourceBtn.className = 'btn-download';
            sourceBtn.style.cssText = 'margin-top: 15px; background: linear-gradient(135deg, #6366f1, #8b5cf6);';
            sourceBtn.innerHTML = '<i class="fas fa-code"></i> Download Source Code (Build Locally)';
            sourceBtn.onclick = () => window.location.href = sourceUrl;
            downloadCard.appendChild(sourceBtn);
            
            // Start auto-refresh
            checkGitHubBuildStatus(buildId, actionsUrl, releasesUrl, sourceUrl);
            
            // Check every 15 seconds
            window.githubStatusInterval = setInterval(() => {
                checkGitHubBuildStatus(buildId, actionsUrl, releasesUrl, sourceUrl);
            }, 15000);
        }
        
        async function checkGitHubBuildStatus(buildId, actionsUrl, releasesUrl, sourceUrl) {
            try {
                const response = await fetch(`api.php?action=github_status&build_id=${buildId}`);
                const data = await response.json();
                
                const statusDetails = document.getElementById('buildStatusDetails');
                const statusBox = document.getElementById('githubStatusBox');
                const downloadCard = document.querySelector('.download-card');
                
                if (!data.success) {
                    if (statusDetails) {
                        statusDetails.innerHTML = `<span style="color: #ef4444;">Error: ${data.message}</span>`;
                    }
                    return;
                }
                
                // Update status display
                let statusIcon, statusColor, statusText;
                
                switch(data.workflow_status) {
                    case 'queued':
                        statusIcon = 'fa-clock';
                        statusColor = '#94a3b8';
                        statusText = 'Queued - Waiting for runner';
                        break;
                    case 'in_progress':
                        statusIcon = 'fa-cog fa-spin';
                        statusColor = '#f59e0b';
                        statusText = 'Building APK...';
                        break;
                    case 'completed':
                        if (data.workflow_conclusion === 'success') {
                            statusIcon = 'fa-check-circle';
                            statusColor = '#10b981';
                            statusText = 'Build Completed Successfully!';
                        } else if (data.workflow_conclusion === 'failure') {
                            statusIcon = 'fa-times-circle';
                            statusColor = '#ef4444';
                            statusText = 'Build Failed - Check GitHub for details';
                        } else {
                            statusIcon = 'fa-exclamation-circle';
                            statusColor = '#f59e0b';
                            statusText = `Build ${data.workflow_conclusion || 'unknown'}`;
                        }
                        break;
                    default:
                        statusIcon = 'fa-spinner fa-spin';
                        statusColor = '#94a3b8';
                        statusText = 'Checking status...';
                }
                
                if (statusDetails) {
                    statusDetails.innerHTML = `
                        <i class="fas ${statusIcon}" style="color: ${statusColor}; margin-right: 8px;"></i>
                        Status: <span style="color: ${statusColor};">${statusText}</span>
                        <br><span style="color: #64748b; font-size: 11px; margin-top: 5px; display: inline-block;">
                            Last checked: ${new Date().toLocaleTimeString()}
                        </span>
                    `;
                }
                
                // If APK is ready, show download button!
                if (data.apk_ready && data.apk_download_url) {
                    // Stop checking
                    if (window.githubStatusInterval) {
                        clearInterval(window.githubStatusInterval);
                        window.githubStatusInterval = null;
                    }
                    
                    // Update header
                    document.getElementById('downloadAppInfo').innerHTML = `
                        <span style="color: #10b981;"><i class="fas fa-check-circle"></i> APK Ready!</span><br>
                        <span style="font-size: 12px; color: #94a3b8;">${data.apk_file_name || 'app-debug.apk'}</span>
                    `;
                    
                    // Update main button to download APK
                    document.getElementById('downloadBtn').innerHTML = '<i class="fas fa-download"></i> Download APK';
                    document.getElementById('downloadBtn').onclick = () => window.open(data.apk_download_url, '_blank');
                    document.getElementById('downloadBtn').style.background = 'linear-gradient(135deg, #10b981, #059669)';
                    
                    // Update status box
                    if (statusBox) {
                        statusBox.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                        statusBox.style.background = 'rgba(16, 185, 129, 0.1)';
                        statusBox.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                                <i class="fas fa-check-circle" style="color: #10b981; font-size: 24px;"></i>
                                <span style="color: #10b981; font-weight: 600; font-size: 18px;">APK Build Complete!</span>
                            </div>
                            <p style="color: #94a3b8; font-size: 13px; margin: 0;">
                                Your APK is ready to download. Click the green button above to download directly.
                            </p>
                            ${data.apk_size ? `<p style="color: #64748b; font-size: 12px; margin-top: 10px;">File size: ${(data.apk_size / 1024 / 1024).toFixed(2)} MB</p>` : ''}
                        `;
                    }
                } else if (data.workflow_conclusion === 'success' && !data.apk_ready) {
                    // Build succeeded but APK not in releases yet, keep checking
                    if (statusDetails) {
                        statusDetails.innerHTML += `<br><span style="color: #f59e0b; font-size: 11px;">Build complete, waiting for release to be created...</span>`;
                    }
                } else if (data.workflow_conclusion === 'failure') {
                    // Stop checking on failure
                    if (window.githubStatusInterval) {
                        clearInterval(window.githubStatusInterval);
                        window.githubStatusInterval = null;
                    }
                    
                    // Show error state
                    document.getElementById('downloadAppInfo').innerHTML = `
                        <span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Build Failed</span><br>
                        <span style="font-size: 12px; color: #94a3b8;">Check GitHub Actions for error details</span>
                    `;
                    
                    if (statusBox) {
                        statusBox.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                        statusBox.style.background = 'rgba(239, 68, 68, 0.1)';
                        statusBox.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                                <i class="fas fa-times-circle" style="color: #ef4444; font-size: 24px;"></i>
                                <span style="color: #ef4444; font-weight: 600;">Build Failed</span>
                            </div>
                            <p style="color: #94a3b8; font-size: 13px; margin: 0 0 15px 0;">
                                The APK build failed on GitHub Actions. This could be due to configuration issues.
                            </p>
                            <a href="${actionsUrl}" target="_blank" style="color: #6366f1; font-size: 13px;">
                                <i class="fas fa-external-link-alt"></i> View error details on GitHub
                            </a>
                        `;
                    }
                }
                
            } catch (error) {
                console.error('Error checking GitHub status:', error);
                const statusDetails = document.getElementById('buildStatusDetails');
                if (statusDetails) {
                    statusDetails.innerHTML = `<span style="color: #f59e0b;">Network error, retrying...</span>`;
                }
            }
        }

        // Firebase config toggle - show/hide based on push notifications checkbox
        (function() {
            const pushToggle = document.getElementById('push_notifications');
            const firebaseSection = document.getElementById('firebase_config_section');
            function toggleFirebase() {
                firebaseSection.style.display = pushToggle.checked ? 'block' : 'none';
            }
            pushToggle.addEventListener('change', toggleFirebase);
            toggleFirebase(); // initial state
        })();

        // AdMob config toggle - show/hide based on admob checkbox
        (function() {
            const admobToggle = document.getElementById('admob_enabled');
            const admobSection = document.getElementById('admob_config_section');
            function toggleAdmob() {
                admobSection.style.display = admobToggle.checked ? 'block' : 'none';
            }
            admobToggle.addEventListener('change', toggleAdmob);
            toggleAdmob(); // initial state
        })();

        // Nav Drawer config toggle
        (function() {
            const toggle = document.getElementById('nav_drawer');
            const section = document.getElementById('nav_drawer_config');
            function t() { section.style.display = toggle.checked ? 'block' : 'none'; }
            toggle.addEventListener('change', t); t();
        })();

        // Bottom Nav config toggle
        (function() {
            const toggle = document.getElementById('bottom_nav');
            const section = document.getElementById('bottom_nav_config');
            function t() { section.style.display = toggle.checked ? 'block' : 'none'; }
            toggle.addEventListener('change', t); t();
        })();

        // Update Checker config toggle
        (function() {
            const toggle = document.getElementById('update_checker');
            const section = document.getElementById('update_checker_config');
            function t() { section.style.display = toggle.checked ? 'block' : 'none'; }
            toggle.addEventListener('change', t); t();
        })();

        // Drawer items helper
        function addDrawerItem() {
            const c = document.getElementById('drawerItemsContainer');
            if (c.children.length >= 6) { alert('Maximum 6 drawer items'); return; }
            const d = document.createElement('div');
            d.className = 'drawer-item';
            d.style.cssText = 'display:grid;grid-template-columns:60px 1fr 1fr auto;gap:8px;margin-bottom:8px;';
            d.innerHTML = '<select class="form-input" name="drawer_icon[]" style="font-size:16px;padding:8px;text-align:center;"><option value="home">\ud83c\udfe0</option><option value="info">\u2139\ufe0f</option><option value="contact">\ud83d\udcde</option><option value="cart">\ud83d\uded2</option><option value="user">\ud83d\udc64</option><option value="settings">\u2699\ufe0f</option><option value="star">\u2b50</option><option value="help">\u2753</option><option value="link">\ud83d\udd17</option></select><input type="text" class="form-input" name="drawer_label[]" placeholder="Label" style="font-size:13px;padding:10px 14px;"><input type="text" class="form-input" name="drawer_url[]" placeholder="URL or /path" style="font-size:13px;padding:10px 14px;"><button type="button" onclick="this.parentElement.remove()" style="background:rgba(239,68,68,0.2);border:none;color:#ef4444;border-radius:8px;padding:10px 14px;cursor:pointer;font-size:14px;">\u2715</button>';
            c.appendChild(d);
        }

        // Bottom nav items helper
        function addBottomNavItem() {
            const c = document.getElementById('bottomNavItemsContainer');
            if (c.children.length >= 5) { alert('Maximum 5 bottom tabs'); return; }
            const d = document.createElement('div');
            d.className = 'bnav-item';
            d.style.cssText = 'display:grid;grid-template-columns:60px 1fr 1fr auto;gap:8px;margin-bottom:8px;';
            d.innerHTML = '<select class="form-input" name="bnav_icon[]" style="font-size:16px;padding:8px;text-align:center;"><option value="home">\ud83c\udfe0</option><option value="search">\ud83d\udd0d</option><option value="cart">\ud83d\uded2</option><option value="user">\ud83d\udc64</option><option value="settings">\u2699\ufe0f</option><option value="star">\u2b50</option><option value="bell">\ud83d\udd14</option><option value="chat">\ud83d\udcac</option><option value="menu">\u2630</option></select><input type="text" class="form-input" name="bnav_label[]" placeholder="Label" style="font-size:13px;padding:10px 14px;"><input type="text" class="form-input" name="bnav_url[]" placeholder="URL or /" style="font-size:13px;padding:10px 14px;"><button type="button" onclick="this.parentElement.remove()" style="background:rgba(239,68,68,0.2);border:none;color:#ef4444;border-radius:8px;padding:10px 14px;cursor:pointer;font-size:14px;">\u2715</button>';
            c.appendChild(d);
        }
    </script>

    <!-- SEO Content Sections -->
    <div class="main-container" style="margin-top: 60px;">
        
        <!-- Features Section -->
        <section id="features" style="margin-bottom: 60px;">
            <h2 style="text-align:center;font-size:36px;font-weight:800;color:var(--text-primary);margin-bottom:16px;">
                Why Choose <span style="background:linear-gradient(135deg,#6366f1,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">WebTooAPK</span>?
            </h2>
            <p style="text-align:center;color:var(--text-secondary);max-width:700px;margin:0 auto 40px;font-size:16px;">
                The most powerful free web to APK converter with 50+ features to create professional Android apps from any website.
            </p>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;">
                <div style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:18px;padding:28px;transition:all 0.3s;">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px;box-shadow:0 4px 12px rgba(99,102,241,0.2);">&#128640;</div>
                    <h3 style="color:var(--text-primary);font-size:18px;margin-bottom:8px;">Instant APK Generation</h3>
                    <p style="color:var(--text-secondary);font-size:14px;line-height:1.6;">Convert any website URL to a fully functional Android APK in minutes. Automatic build via GitHub Actions - no Android Studio needed.</p>
                </div>
                <div style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:18px;padding:28px;transition:all 0.3s;">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#ec4899,#f43f5e);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px;box-shadow:0 4px 12px rgba(236,72,153,0.2);">&#128276;</div>
                    <h3 style="color:var(--text-primary);font-size:18px;margin-bottom:8px;">Push Notifications (FCM)</h3>
                    <p style="color:var(--text-secondary);font-size:14px;line-height:1.6;">Built-in Firebase Cloud Messaging support. Send push notifications to all your app users. Keep them engaged with real-time alerts.</p>
                </div>
                <div style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:18px;padding:28px;transition:all 0.3s;">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#10b981,#14b8a6);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px;box-shadow:0 4px 12px rgba(16,185,129,0.2);">&#128178;</div>
                    <h3 style="color:var(--text-primary);font-size:18px;margin-bottom:8px;">AdMob Monetization</h3>
                    <p style="color:var(--text-secondary);font-size:14px;line-height:1.6;">Earn money with Google AdMob integration. Add banner ads and interstitial ads with just your AdMob IDs. Start monetizing instantly.</p>
                </div>
                <div style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:18px;padding:28px;transition:all 0.3s;">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#f59e0b,#f97316);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px;box-shadow:0 4px 12px rgba(245,158,11,0.2);">&#128274;</div>
                    <h3 style="color:var(--text-primary);font-size:18px;margin-bottom:8px;">All Android Permissions</h3>
                    <p style="color:var(--text-secondary);font-size:14px;line-height:1.6;">Camera, microphone, GPS, contacts, calendar, SMS, call, Bluetooth, NFC, storage, biometric, background services &amp; notifications - all supported.</p>
                </div>
                <div style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:18px;padding:28px;transition:all 0.3s;">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px;box-shadow:0 4px 12px rgba(59,130,246,0.2);">&#127912;</div>
                    <h3 style="color:var(--text-primary);font-size:18px;margin-bottom:8px;">Full Customization</h3>
                    <p style="color:var(--text-secondary);font-size:14px;line-height:1.6;">Custom app icon, splash screen, status bar colors, loading animation, navigation drawer, bottom navigation, floating action button &amp; more.</p>
                </div>
                <div style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:18px;padding:28px;transition:all 0.3s;">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#8b5cf6,#a855f7);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px;box-shadow:0 4px 12px rgba(139,92,246,0.2);">&#128187;</div>
                    <h3 style="color:var(--text-primary);font-size:18px;margin-bottom:8px;">Offline Mode &amp; Deep Links</h3>
                    <p style="color:var(--text-secondary);font-size:14px;line-height:1.6;">Works offline with custom error pages. Support for custom URL schemes and Android App Links for seamless deep linking into your app.</p>
                </div>
            </div>
        </section>

        <!-- How it Works Section -->
        <section id="how-it-works" style="margin-bottom: 60px;">
            <h2 style="text-align:center;font-size:36px;font-weight:800;color:var(--text-primary);margin-bottom:16px;">
                How to Convert <span style="background:linear-gradient(135deg,#6366f1,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Website to APK</span>
            </h2>
            <p style="text-align:center;color:var(--text-secondary);max-width:600px;margin:0 auto 40px;font-size:16px;">
                Convert your website to an Android app in 4 simple steps. No programming knowledge required.
            </p>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px;">
                <div style="text-align:center;padding:24px;">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:white;margin:0 auto 16px;box-shadow:0 6px 20px rgba(99,102,241,0.25);">1</div>
                    <h3 style="color:var(--text-primary);font-size:16px;margin-bottom:8px;">Enter Website URL</h3>
                    <p style="color:var(--text-secondary);font-size:14px;">Paste the URL of the website you want to convert to an Android app.</p>
                </div>
                <div style="text-align:center;padding:24px;">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,#ec4899,#f43f5e);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:white;margin:0 auto 16px;box-shadow:0 6px 20px rgba(236,72,153,0.25);">2</div>
                    <h3 style="color:var(--text-primary);font-size:16px;margin-bottom:8px;">Configure App Details</h3>
                    <p style="color:var(--text-secondary);font-size:14px;">Set app name, package name, version, and minimum Android SDK version.</p>
                </div>
                <div style="text-align:center;padding:24px;">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,#10b981,#14b8a6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:white;margin:0 auto 16px;box-shadow:0 6px 20px rgba(16,185,129,0.25);">3</div>
                    <h3 style="color:var(--text-primary);font-size:16px;margin-bottom:8px;">Customize &amp; Add Features</h3>
                    <p style="color:var(--text-secondary);font-size:14px;">Upload icon, set colors, enable permissions, push notifications, ads &amp; more.</p>
                </div>
                <div style="text-align:center;padding:24px;">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,#f59e0b,#f97316);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:white;margin:0 auto 16px;box-shadow:0 6px 20px rgba(245,158,11,0.25);">4</div>
                    <h3 style="color:var(--text-primary);font-size:16px;margin-bottom:8px;">Download APK</h3>
                    <p style="color:var(--text-secondary);font-size:14px;">Your APK is built automatically. Download and install on any Android device.</p>
                </div>
            </div>
        </section>

        <!-- Permissions Section -->
        <section id="permissions" style="margin-bottom: 60px;">
            <h2 style="text-align:center;font-size:32px;font-weight:800;color:var(--text-primary);margin-bottom:16px;">
                All Android <span style="background:linear-gradient(135deg,#6366f1,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Permissions</span> Supported
            </h2>
            <p style="text-align:center;color:var(--text-secondary);max-width:700px;margin:0 auto 32px;font-size:15px;">
                Create apps with any combination of Android permissions. From camera and GPS to SMS, calls, and background services.
            </p>
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px;max-width:900px;margin:0 auto;">
                <span style="background:rgba(99,102,241,0.15);color:#a5b4fc;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(99,102,241,0.3);"><i class="fas fa-camera" style="margin-right:6px;"></i>Camera</span>
                <span style="background:rgba(236,72,153,0.15);color:#f9a8d4;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(236,72,153,0.3);"><i class="fas fa-microphone" style="margin-right:6px;"></i>Microphone</span>
                <span style="background:rgba(16,185,129,0.15);color:#6ee7b7;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(16,185,129,0.3);"><i class="fas fa-location-dot" style="margin-right:6px;"></i>GPS / Location</span>
                <span style="background:rgba(245,158,11,0.15);color:#fcd34d;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(245,158,11,0.3);"><i class="fas fa-address-book" style="margin-right:6px;"></i>Contacts</span>
                <span style="background:rgba(139,92,246,0.15);color:#c4b5fd;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(139,92,246,0.3);"><i class="fas fa-calendar" style="margin-right:6px;"></i>Calendar</span>
                <span style="background:rgba(59,130,246,0.15);color:#93c5fd;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(59,130,246,0.3);"><i class="fas fa-bluetooth" style="margin-right:6px;"></i>Bluetooth</span>
                <span style="background:rgba(244,63,94,0.15);color:#fda4af;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(244,63,94,0.3);"><i class="fas fa-sms" style="margin-right:6px;"></i>SMS</span>
                <span style="background:rgba(20,184,166,0.15);color:#5eead4;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(20,184,166,0.3);"><i class="fas fa-phone-volume" style="margin-right:6px;"></i>Call Phone</span>
                <span style="background:rgba(168,85,247,0.15);color:#d8b4fe;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(168,85,247,0.3);"><i class="fas fa-list-ol" style="margin-right:6px;"></i>Call Log</span>
                <span style="background:rgba(34,211,238,0.15);color:#67e8f9;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(34,211,238,0.3);"><i class="fas fa-folder-open" style="margin-right:6px;"></i>Storage / Gallery</span>
                <span style="background:rgba(251,146,60,0.15);color:#fdba74;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(251,146,60,0.3);"><i class="fas fa-bell" style="margin-right:6px;"></i>Notifications</span>
                <span style="background:rgba(74,222,128,0.15);color:#86efac;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(74,222,128,0.3);"><i class="fas fa-wifi" style="margin-right:6px;"></i>NFC</span>
                <span style="background:rgba(248,113,113,0.15);color:#fca5a5;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(248,113,113,0.3);"><i class="fas fa-heartbeat" style="margin-right:6px;"></i>Body Sensors</span>
                <span style="background:rgba(96,165,250,0.15);color:#bfdbfe;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(96,165,250,0.3);"><i class="fas fa-cogs" style="margin-right:6px;"></i>Background Service</span>
                <span style="background:rgba(163,230,53,0.15);color:#bef264;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(163,230,53,0.3);"><i class="fas fa-fingerprint" style="margin-right:6px;"></i>Biometric</span>
                <span style="background:rgba(217,70,239,0.15);color:#e879f9;padding:8px 16px;border-radius:20px;font-size:13px;border:1px solid rgba(217,70,239,0.3);"><i class="fas fa-phone" style="margin-right:6px;"></i>Phone State</span>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" style="margin-bottom: 60px;">
            <h2 style="text-align:center;font-size:36px;font-weight:800;color:var(--text-primary);margin-bottom:40px;">
                Frequently Asked <span style="background:linear-gradient(135deg,#6366f1,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Questions</span>
            </h2>
            <div style="max-width:800px;margin:0 auto;">
                <details style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:14px;padding:20px 24px;margin-bottom:12px;transition:all 0.3s;">
                    <summary style="color:var(--text-primary);font-weight:600;font-size:16px;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;">
                        How to convert a website to an Android APK app?
                        <i class="fas fa-chevron-down" style="color:#6366f1;font-size:12px;"></i>
                    </summary>
                    <p style="color:var(--text-secondary);margin-top:16px;line-height:1.7;font-size:14px;">
                        Simply enter your website URL on WebTooAPK.com, customize the app name, icon, colors, and features, then click Generate. Your APK will be built automatically using GitHub Actions in 3-5 minutes. No Android Studio or coding knowledge required.
                    </p>
                </details>
                <details style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:14px;padding:20px 24px;margin-bottom:12px;transition:all 0.3s;">
                    <summary style="color:var(--text-primary);font-weight:600;font-size:16px;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;">
                        Is WebTooAPK free to use?
                        <i class="fas fa-chevron-down" style="color:#6366f1;font-size:12px;"></i>
                    </summary>
                    <p style="color:var(--text-secondary);margin-top:16px;line-height:1.7;font-size:14px;">
                        Yes, WebTooAPK is completely free. You can convert unlimited websites to Android APK apps with all 50+ features included at no cost. There are no hidden charges or premium tiers.
                    </p>
                </details>
                <details style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:14px;padding:20px 24px;margin-bottom:12px;transition:all 0.3s;">
                    <summary style="color:var(--text-primary);font-weight:600;font-size:16px;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;">
                        Can I add push notifications to my converted app?
                        <i class="fas fa-chevron-down" style="color:#6366f1;font-size:12px;"></i>
                    </summary>
                    <p style="color:var(--text-secondary);margin-top:16px;line-height:1.7;font-size:14px;">
                        Yes! WebTooAPK supports Firebase Cloud Messaging (FCM) push notifications. Just upload your google-services.json file from Firebase Console and your app will automatically support push notifications to engage your users.
                    </p>
                </details>
                <details style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:14px;padding:20px 24px;margin-bottom:12px;transition:all 0.3s;">
                    <summary style="color:var(--text-primary);font-weight:600;font-size:16px;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;">
                        What permissions can I add to the APK?
                        <i class="fas fa-chevron-down" style="color:#6366f1;font-size:12px;"></i>
                    </summary>
                    <p style="color:var(--text-secondary);margin-top:16px;line-height:1.7;font-size:14px;">
                        WebTooAPK supports all major Android permissions: Camera, Microphone, GPS/Location, Contacts, Calendar, Bluetooth, SMS (send/read/receive), Call Phone, Call Log, Storage/Gallery, NFC, Body Sensors, Background/Foreground Services, Notifications, and Biometric/Fingerprint authentication.
                    </p>
                </details>
                <details style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:14px;padding:20px 24px;margin-bottom:12px;transition:all 0.3s;">
                    <summary style="color:var(--text-primary);font-weight:600;font-size:16px;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;">
                        Can I monetize my app with AdMob ads?
                        <i class="fas fa-chevron-down" style="color:#6366f1;font-size:12px;"></i>
                    </summary>
                    <p style="color:var(--text-secondary);margin-top:16px;line-height:1.7;font-size:14px;">
                        Yes! WebTooAPK has built-in Google AdMob integration. Add banner ads and interstitial ads by entering your AdMob App ID and Ad Unit IDs. Configure how often interstitial ads appear between page loads.
                    </p>
                </details>
                <details style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:14px;padding:20px 24px;margin-bottom:12px;transition:all 0.3s;">
                    <summary style="color:var(--text-primary);font-weight:600;font-size:16px;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;">
                        Can I publish the generated APK on Google Play Store?
                        <i class="fas fa-chevron-down" style="color:#6366f1;font-size:12px;"></i>
                    </summary>
                    <p style="color:var(--text-secondary);margin-top:16px;line-height:1.7;font-size:14px;">
                        Yes! The generated project is a full Android Studio project. Open it in Android Studio, generate a signed APK/AAB, and upload directly to Google Play Store. The project includes ProGuard rules, proper SDK targeting, and all required configurations.
                    </p>
                </details>
                <details style="background:var(--glass-bg);backdrop-filter:blur(12px);border:1px solid var(--glass-border);border-radius:14px;padding:20px 24px;margin-bottom:12px;transition:all 0.3s;">
                    <summary style="color:var(--text-primary);font-weight:600;font-size:16px;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;">
                        What features does the converted app support?
                        <i class="fas fa-chevron-down" style="color:#6366f1;font-size:12px;"></i>
                    </summary>
                    <p style="color:var(--text-secondary);margin-top:16px;line-height:1.7;font-size:14px;">
                        50+ features including: Splash Screen, Offline Mode, Push Notifications (FCM), AdMob Ads, File Downloads, Pull to Refresh, Share Button, Custom JavaScript/CSS Injection, Deep Links, URL Schemes, Navigation Drawer, Bottom Navigation, Floating Action Button, Exit Confirmation, Force Dark Mode, File Upload with Camera, Swipe Gestures, Multi-Window, GDPR Consent, App Lock (Biometric), Auto Update Checker, Custom User Agent, and more.
                    </p>
                </details>
            </div>
        </section>

        <!-- SEO Footer Content -->
        <section style="margin-bottom: 40px; text-align: center; padding: 40px 20px; background: var(--glass-bg); backdrop-filter:blur(12px); border-radius: 24px; border: 1px solid var(--glass-border);">
            <h2 style="color:var(--text-primary);font-size:28px;font-weight:700;margin-bottom:16px;">The Best Free Web to APK Converter Online</h2>
            <p style="color:var(--text-secondary);max-width:800px;margin:0 auto 16px;font-size:15px;line-height:1.8;">
                <strong style="color:var(--text-primary);">WebTooAPK</strong> is the ultimate free website to APK converter that transforms any website, web app, or PWA into a native Android application. Whether you need to convert a WordPress site, Shopify store, React app, or any URL into an Android APK - WebTooAPK handles it all with zero coding required.
            </p>
            <p style="color:var(--text-secondary);max-width:800px;margin:0 auto 16px;font-size:15px;line-height:1.8;">
                Unlike other web to app converters, WebTooAPK gives you full control over Android permissions including camera, microphone, GPS, SMS, phone calls, contacts, calendar, Bluetooth, NFC, storage, notifications, background services, and biometric authentication. Create apps that can access every hardware feature of modern Android devices.
            </p>
            <p style="color:var(--text-secondary);max-width:800px;margin:0 auto;font-size:15px;line-height:1.8;">
                With built-in <strong style="color:var(--text-primary);">Firebase push notifications</strong>, <strong style="color:var(--text-primary);">Google AdMob monetization</strong>, <strong style="color:var(--text-primary);">offline mode</strong>, <strong style="color:var(--text-primary);">deep links</strong>, and <strong style="color:var(--text-primary);">50+ customization options</strong>, WebTooAPK produces professional-grade Android apps ready for the Google Play Store.
            </p>
        </section>
    </div>

    <!-- Footer -->
    <footer style="text-align:center;padding:30px 20px;border-top:1px solid rgba(148,163,184,0.15);margin-top:20px;">
        <p style="color:var(--text-secondary);font-size:13px;">&copy; 2024-2026 <a href="https://webtooapk.com" style="color:#6366f1;text-decoration:none;">WebTooAPK.com</a> - Free Web to APK Converter. Convert any website to Android app online.</p>
        <p style="color:var(--text-muted);font-size:12px;margin-top:8px;">
            <a href="#" style="color:var(--text-muted);text-decoration:none;margin:0 8px;">Web to APK</a> |
            <a href="#" style="color:var(--text-muted);text-decoration:none;margin:0 8px;">Website to App</a> |
            <a href="#" style="color:var(--text-muted);text-decoration:none;margin:0 8px;">URL to APK</a> |
            <a href="#" style="color:var(--text-muted);text-decoration:none;margin:0 8px;">HTML to APK</a> |
            <a href="#features" style="color:var(--text-muted);text-decoration:none;margin:0 8px;">Features</a> |
            <a href="#faq" style="color:var(--text-muted);text-decoration:none;margin:0 8px;">FAQ</a>
        </p>
    </footer>

    <script>
    // Close user dropdown on outside click
    document.addEventListener('click', function(e) {
        var menu = document.getElementById('headerUserMenu');
        if (menu && !menu.contains(e.target)) menu.classList.remove('open');
    });
    </script>
</body>
</html>
