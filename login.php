<?php
require_once __DIR__ . '/auth.php';

// Redirect if already logged in
if (getCurrentUser()) {
    header('Location: index.php');
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($email) || empty($password)) {
            $error = 'Please enter email and password.';
        } else {
            $result = loginUser($email, $password, $remember);
            if ($result['success']) {
                $redirect = $_GET['redirect'] ?? 'index.php';
                // Only allow relative redirects
                if (strpos($redirect, '://') !== false || strpos($redirect, '//') === 0) {
                    $redirect = 'index.php';
                }
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = $result['error'];
            }
        }
    }
}

$csrf = csrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - WebTooAPK</title>
    <meta name="description" content="Sign in to your WebTooAPK account.">
    <link rel="canonical" href="https://webtooapk.com/login.php">
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
            --input-bg: rgba(255,255,255,0.85);
            --input-border: rgba(148,163,184,0.3);
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f2f8 0%, #e8eaf6 50%, #f0f2f8 100%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .bg-shapes {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }
        .bg-shapes .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
        }
        .shape-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(236,72,153,0.2) 0%, transparent 70%); top: -150px; right: -100px; animation: smokeFloat1 22s infinite ease-in-out; }
        .shape-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%); bottom: -100px; left: -100px; animation: smokeFloat2 26s infinite ease-in-out; }
        .shape-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(139,92,246,0.12) 0%, transparent 70%); top: 40%; left: 40%; animation: smokeFloat3 20s infinite ease-in-out; }

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
        @keyframes smokeFloat3 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.6; }
            50% { transform: translate(-45%, -55%) scale(1.15); opacity: 0.9; }
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 40px;
        }
        .auth-logo .icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }
        .auth-logo .text {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .auth-logo .text span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.04);
        }

        .auth-card h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-align: center;
            color: var(--text-primary);
        }
        .auth-card .subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            text-align: center;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 14px;
        }
        .form-label i { color: var(--primary); font-size: 13px; }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: var(--input-bg);
            border: 2px solid var(--input-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 15px;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            transition: all 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }
        .form-input::placeholder { color: var(--text-muted); }

        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
        }
        .password-toggle:hover { color: var(--text-primary); }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: -8px;
            margin-bottom: 4px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-secondary);
            cursor: pointer;
        }
        .checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6, var(--secondary));
            background-size: 200% 200%;
            animation: gradientShift 5s ease infinite;
            color: white;
            border: none;
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
            margin-top: 24px;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
            position: relative;
            overflow: hidden;
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }
        .btn-submit:hover::after { left: 100%; }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(99,102,241,0.4);
        }
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 28px 0;
            color: var(--text-muted);
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--input-border);
        }

        .auth-footer {
            text-align: center;
            margin-top: 28px;
            color: var(--text-secondary);
            font-size: 14px;
        }
        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .auth-footer a:hover { text-decoration: underline; }

        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #dc2626;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,0.5); }
        ::selection { background: rgba(99,102,241,0.15); color: var(--text-primary); }

        @media (max-width: 520px) {
            .auth-card { padding: 28px 20px; border-radius: 20px; }
            .auth-card h1 { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="auth-container">
        <a href="index.php" class="auth-logo">
            <div class="icon"><i class="fas fa-rocket"></i></div>
            <div class="text">Web<span>TooAPK</span></div>
        </a>

        <div class="auth-card">
            <h1>Welcome Back</h1>
            <p class="subtitle">Sign in to your WebTooAPK account</p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php<?= !empty($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" class="form-input" name="email" id="email"
                           placeholder="you@example.com" required maxlength="255"
                           value="<?= htmlspecialchars($email) ?>" autocomplete="email" autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" name="password" id="password"
                               placeholder="Enter your password" required maxlength="128"
                               autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div class="form-row" style="margin-top: 12px;">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" value="1">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-right-to-bracket"></i>
                    Sign In
                </button>
            </form>

            <div class="divider">or</div>

            <div class="auth-footer">
                Don't have an account? <a href="signup.php">Create Account</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
        });
    </script>
</body>
</html>
