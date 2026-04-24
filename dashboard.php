<?php
require_once __DIR__ . '/auth.php';

$user = getCurrentUser();
if (!$user) {
    header('Location: login.php?redirect=dashboard.php');
    exit;
}

$welcome = isset($_GET['welcome']);
$profileMsg = '';
$passwordMsg = '';
$tab = $_GET['tab'] ?? 'overview';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $profileMsg = 'error:Invalid request.';
    } else {
        if ($_POST['action'] === 'update_profile') {
            $result = updateProfile($user['id'], [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? '')
            ]);
            if ($result['success'] ?? false) {
                $profileMsg = 'success:Profile updated successfully!';
                $user = getCurrentUser(); // Refresh
            } else {
                $profileMsg = 'error:' . ($result['error'] ?? 'Failed to update profile.');
            }
            $tab = 'settings';
        } elseif ($_POST['action'] === 'change_password') {
            $result = changePassword(
                $user['id'],
                $_POST['current_password'] ?? '',
                $_POST['new_password'] ?? ''
            );
            if ($result['success']) {
                $passwordMsg = 'success:Password changed successfully!';
            } else {
                $passwordMsg = 'error:' . $result['error'];
            }
            $tab = 'settings';
        }
    }
}

$builds = getUserBuilds($user['id']);
$buildCount = getUserBuildCount($user['id']);
$csrf = csrfToken();
$initials = getInitials($user['name']);
$memberSince = date('M Y', strtotime($user['created_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WebTooAPK</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <meta name="theme-color" content="#6366f1">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --accent: #8b5cf6;
            --gray: #64748b;
            --light: #f1f5f9;
            --white: #ffffff;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --glass-bg: rgba(255,255,255,0.65);
            --glass-border: rgba(255,255,255,0.5);
            --glass-shadow: 0 8px 32px rgba(0,0,0,0.08);
            --card-bg: rgba(255,255,255,0.72);
            --input-bg: rgba(255,255,255,0.85);
            --input-border: rgba(148,163,184,0.3);
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f2f8 0%, #e8eaf6 50%, #f0f2f8 100%);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Animated smoke background */
        .bg-shapes {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .bg-shapes .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
        }
        .shape-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(236,72,153,0.2) 0%, transparent 70%); top: -150px; left: -100px; animation: smokeFloat1 22s infinite ease-in-out; }
        .shape-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%); bottom: -100px; right: -100px; animation: smokeFloat2 26s infinite ease-in-out; }

        @keyframes smokeFloat1 {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.8; }
            33% { transform: translate(40px, -40px) scale(1.1); opacity: 1; }
            66% { transform: translate(-30px, 25px) scale(0.9); opacity: 0.7; }
        }
        @keyframes smokeFloat2 {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.7; }
            33% { transform: translate(-35px, 45px) scale(1.08); opacity: 0.9; }
            66% { transform: translate(25px, -30px) scale(0.92); opacity: 0.8; }
        }

        /* Header */
        .dash-header {
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 10;
            border-bottom: 1px solid rgba(148,163,184,0.12);
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }
        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .logo-text span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .btn-create {
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6, var(--secondary));
            background-size: 200% 200%;
            animation: gradientShift 5s ease infinite;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
            position: relative;
            overflow: hidden;
        }
        .btn-create::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }
        .btn-create:hover::after { left: 100%; }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .btn-create:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99,102,241,0.4);
        }
        .user-menu {
            position: relative;
        }
        .user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            background: rgba(255,255,255,0.7);
            border: 1px solid rgba(148,163,184,0.2);
            border-radius: 50px;
            cursor: pointer;
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .user-btn:hover {
            background: rgba(255,255,255,0.9);
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: white;
        }
        .user-btn .name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }
        .user-btn .arrow {
            font-size: 10px;
            color: var(--gray);
            transition: transform 0.2s;
        }
        .user-menu.open .arrow { transform: rotate(180deg); }

        .dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 200px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(148,163,184,0.15);
            border-radius: 14px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s;
            z-index: 100;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .user-menu.open .dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown a, .dropdown button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            border: none;
            background: none;
            cursor: pointer;
            transition: background 0.15s;
            text-align: left;
        }
        .dropdown a:hover, .dropdown button:hover {
            background: rgba(99,102,241,0.06);
            color: var(--text-primary);
        }
        .dropdown a i, .dropdown button i { width: 16px; color: var(--gray); }
        .dropdown .divider {
            height: 1px;
            background: rgba(148,163,184,0.12);
            margin: 6px 0;
        }
        .dropdown .logout-item { color: var(--error); }
        .dropdown .logout-item i { color: var(--error); }

        /* Main Content */
        .main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px;
            position: relative;
            z-index: 10;
        }

        /* Welcome banner */
        .welcome-banner {
            background: rgba(255,255,255,0.72);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
        .welcome-banner .emoji { font-size: 32px; }
        .welcome-banner h2 { font-size: 18px; font-weight: 600; margin-bottom: 4px; color: var(--text-primary); }
        .welcome-banner p { font-size: 13px; color: var(--text-secondary); }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 16px;
            padding: 24px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
        .stat-card .label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .stat-card .icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }
        .stat-card:nth-child(1) .icon { background: rgba(99,102,241,0.1); color: var(--primary); }
        .stat-card:nth-child(2) .icon { background: rgba(16,185,129,0.1); color: var(--success); }
        .stat-card:nth-child(3) .icon { background: rgba(236,72,153,0.1); color: var(--secondary); }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 24px;
            background: rgba(255,255,255,0.5);
            border: 1px solid rgba(148,163,184,0.12);
            padding: 4px;
            border-radius: 12px;
            width: fit-content;
        }
        .tab-btn {
            padding: 10px 20px;
            background: transparent;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tab-btn:hover { color: var(--text-primary); }
        .tab-btn.active {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            box-shadow: 0 2px 10px rgba(99,102,241,0.25);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Builds Table */
        .section-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 16px;
            overflow: hidden;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
        .section-header {
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(148,163,184,0.1);
        }
        .section-header h3 {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-primary);
        }
        .section-header h3 i { color: var(--primary); }

        .builds-list {
            padding: 8px;
        }
        .build-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border-radius: 12px;
            transition: background 0.15s;
        }
        .build-item:hover {
            background: rgba(99,102,241,0.04);
        }
        .build-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(99,102,241,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .build-info {
            flex: 1;
            min-width: 0;
        }
        .build-info .name {
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
            color: var(--text-primary);
        }
        .build-info .meta {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        .build-info .meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .build-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-generated { background: rgba(16,185,129,0.1); color: var(--success); }
        .status-building { background: rgba(245,158,11,0.1); color: var(--warning); }
        .status-failed { background: rgba(239,68,68,0.1); color: var(--error); }

        .build-actions {
            display: flex;
            gap: 8px;
        }
        .build-actions a {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(99,102,241,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.15s;
        }
        .build-actions a:hover {
            background: rgba(99,102,241,0.12);
            color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }
        .empty-state h4 {
            font-size: 16px;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        .empty-state p { font-size: 13px; margin-bottom: 20px; }
        .empty-state a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
            transition: all 0.3s;
        }
        .empty-state a:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99,102,241,0.4);
        }

        /* Settings */
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        .settings-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 16px;
            padding: 28px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
        .settings-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-primary);
        }
        .settings-card h3 i { color: var(--primary); }

        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 13px;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: var(--input-bg);
            border: 2px solid var(--input-border);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            transition: all 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
        .form-input::placeholder { color: var(--text-muted); }

        .btn-save {
            padding: 10px 24px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(99,102,241,0.2);
        }
        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(99,102,241,0.35);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #dc2626;
        }
        .alert-success {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            color: #059669;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,0.5); }
        ::selection { background: rgba(99,102,241,0.15); color: var(--text-primary); }

        @media (max-width: 768px) {
            .dash-header { padding: 12px 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .settings-grid { grid-template-columns: 1fr; }
            .main { padding: 20px 12px; }
            .build-item { flex-wrap: wrap; }
            .build-actions { width: 100%; justify-content: flex-end; }
            .header-actions .btn-create span { display: none; }
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <header class="dash-header">
        <a href="index.php" class="logo">
            <div class="logo-icon"><i class="fas fa-rocket"></i></div>
            <div class="logo-text">Web<span>TooAPK</span></div>
        </a>
        <div class="header-actions">
            <a href="index.php" class="btn-create">
                <i class="fas fa-plus"></i>
                <span>New App</span>
            </a>
            <div class="user-menu" id="userMenu">
                <button class="user-btn" onclick="document.getElementById('userMenu').classList.toggle('open')">
                    <div class="user-avatar"><?= htmlspecialchars($initials) ?></div>
                    <span class="name"><?= htmlspecialchars($user['name']) ?></span>
                    <i class="fas fa-chevron-down arrow"></i>
                </button>
                <div class="dropdown">
                    <a href="dashboard.php"><i class="fas fa-gauge"></i> Dashboard</a>
                    <a href="dashboard.php?tab=settings"><i class="fas fa-gear"></i> Settings</a>
                    <div class="divider"></div>
                    <a href="logout.php" class="logout-item"><i class="fas fa-right-from-bracket"></i> Sign Out</a>
                </div>
            </div>
        </div>
    </header>

    <div class="main">
        <?php if ($welcome): ?>
            <div class="welcome-banner">
                <div class="emoji">&#127881;</div>
                <div>
                    <h2>Welcome to WebTooAPK, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?>!</h2>
                    <p>Your account is ready. Start converting websites to Android apps now.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon"><i class="fas fa-mobile-screen"></i></div>
                <div class="label">Total Apps</div>
                <div class="value"><?= $buildCount ?></div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <div class="label">Access</div>
                <div class="value" style="font-size:24px;">Free Forever</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fas fa-calendar"></i></div>
                <div class="label">Member Since</div>
                <div class="value" style="font-size:24px;"><?= $memberSince ?></div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn <?= $tab === 'overview' ? 'active' : '' ?>" onclick="switchTab('overview')">
                <i class="fas fa-grid-2"></i> My Apps
            </button>
            <button class="tab-btn <?= $tab === 'settings' ? 'active' : '' ?>" onclick="switchTab('settings')">
                <i class="fas fa-gear"></i> Settings
            </button>
        </div>

        <!-- My Apps Tab -->
        <div class="tab-content <?= $tab === 'overview' ? 'active' : '' ?>" id="tab-overview">
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-clock-rotate-left"></i> Build History</h3>
                </div>
                <?php if (empty($builds)): ?>
                    <div class="empty-state">
                        <i class="fas fa-mobile-screen-button"></i>
                        <h4>No apps yet</h4>
                        <p>Create your first Android app from any website in minutes.</p>
                        <a href="index.php"><i class="fas fa-plus"></i> Create Your First App</a>
                    </div>
                <?php else: ?>
                    <div class="builds-list">
                        <?php foreach ($builds as $build): ?>
                            <div class="build-item">
                                <div class="build-icon">
                                    <?php if ($build['icon_path'] && file_exists(__DIR__ . '/' . $build['icon_path'])): ?>
                                        <img src="<?= htmlspecialchars($build['icon_path']) ?>" style="width:100%;height:100%;border-radius:12px;object-fit:cover;">
                                    <?php else: ?>
                                        <i class="fas fa-android" style="color: var(--success);"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="build-info">
                                    <div class="name"><?= htmlspecialchars($build['app_name']) ?></div>
                                    <div class="meta">
                                        <span><i class="fas fa-box"></i> <?= htmlspecialchars($build['package_name']) ?></span>
                                        <span><i class="fas fa-globe"></i> <?= htmlspecialchars(parse_url($build['website_url'], PHP_URL_HOST) ?: $build['website_url']) ?></span>
                                        <span><i class="fas fa-clock"></i> <?= date('M d, Y', strtotime($build['created_at'])) ?></span>
                                    </div>
                                </div>
                                <span class="build-status status-<?= htmlspecialchars($build['status']) ?>"><?= htmlspecialchars($build['status']) ?></span>
                                <div class="build-actions">
                                    <?php if ($build['download_url']): ?>
                                        <a href="<?= htmlspecialchars($build['download_url']) ?>" title="Download"><i class="fas fa-download"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Settings Tab -->
        <div class="tab-content <?= $tab === 'settings' ? 'active' : '' ?>" id="tab-settings">
            <div class="settings-grid">
                <div class="settings-card">
                    <h3><i class="fas fa-user-pen"></i> Profile</h3>
                    
                    <?php if ($profileMsg): ?>
                        <?php [$type, $msg] = explode(':', $profileMsg, 2); ?>
                        <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-input" name="name" required minlength="2" maxlength="100"
                                   value="<?= htmlspecialchars($user['name']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-input" name="email" required maxlength="255"
                                   value="<?= htmlspecialchars($user['email']) ?>">
                        </div>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </form>
                </div>

                <div class="settings-card">
                    <h3><i class="fas fa-key"></i> Change Password</h3>
                    
                    <?php if ($passwordMsg): ?>
                        <?php [$type, $msg] = explode(':', $passwordMsg, 2); ?>
                        <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-input" name="current_password" required
                                   autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-input" name="new_password" required minlength="8" maxlength="128"
                                   autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn-save">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
            history.replaceState(null, '', '?tab=' + tab);
        }

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            if (!menu.contains(e.target)) menu.classList.remove('open');
        });
    </script>
</body>
</html>
