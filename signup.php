<?php
require_once __DIR__ . '/auth.php';

// Redirect if already logged in
if (getCurrentUser()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($name) || mb_strlen($name) < 2) {
            $error = 'Name must be at least 2 characters.';
        } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (mb_strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            $result = registerUser($name, $email, $password);
            if ($result['success']) {
                header('Location: dashboard.php?welcome=1');
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
    <title>Sign Up - WebTooAPK</title>
    <meta name="description" content="Create your free WebTooAPK account to convert websites to Android APK apps.">
    <link rel="canonical" href="https://webtooapk.com/signup.php">
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

        /* Animated smoke background */
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
        .shape-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(236,72,153,0.2) 0%, transparent 70%); top: -150px; left: -100px; animation: smokeFloat1 22s infinite ease-in-out; }
        .shape-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%); bottom: -100px; right: -100px; animation: smokeFloat2 26s infinite ease-in-out; }
        .shape-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(139,92,246,0.12) 0%, transparent 70%); top: 50%; left: 50%; animation: smokeFloat3 20s infinite ease-in-out; }

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
        .auth-footer a:hover {
            text-decoration: underline;
        }

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
        .alert-success {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            color: #059669;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            background: rgba(148,163,184,0.15);
            margin-top: 8px;
            overflow: hidden;
        }
        .password-strength .bar {
            height: 100%;
            border-radius: 2px;
            width: 0%;
            transition: all 0.3s;
        }
        .strength-text {
            font-size: 12px;
            color: var(--gray);
            margin-top: 4px;
        }

        .terms {
            font-size: 12px;
            color: var(--text-muted);
            text-align: center;
            margin-top: 16px;
            line-height: 1.5;
        }
        .terms a {
            color: var(--primary);
            text-decoration: none;
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
            <h1>Create Account</h1>
            <p class="subtitle">Start converting websites to Android apps for free</p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="signup.php" id="signupForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user"></i>
                        Full Name
                    </label>
                    <input type="text" class="form-input" name="name" id="name" 
                           placeholder="John Doe" required minlength="2" maxlength="100"
                           value="<?= htmlspecialchars($name) ?>" autocomplete="name">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <input type="email" class="form-input" name="email" id="email"
                           placeholder="you@example.com" required maxlength="255"
                           value="<?= htmlspecialchars($email) ?>" autocomplete="email">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" name="password" id="password"
                               placeholder="Min 8 characters" required minlength="8" maxlength="128"
                               autocomplete="new-password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength"><div class="bar" id="strengthBar"></div></div>
                    <div class="strength-text" id="strengthText"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Confirm Password
                    </label>
                    <div class="password-wrapper">
                        <input type="password" class="form-input" name="confirm_password" id="confirm_password"
                               placeholder="Re-enter password" required minlength="8" maxlength="128"
                               autocomplete="new-password">
                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>
            </form>

            <p class="terms">By creating an account, you agree to our Terms of Service and Privacy Policy.</p>

            <div class="divider">or</div>

            <div class="auth-footer">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Password strength meter
        document.getElementById('password').addEventListener('input', function() {
            const pw = this.value;
            let score = 0;
            if (pw.length >= 8) score++;
            if (pw.length >= 12) score++;
            if (/[a-z]/.test(pw) && /[A-Z]/.test(pw)) score++;
            if (/\d/.test(pw)) score++;
            if (/[^a-zA-Z0-9]/.test(pw)) score++;

            const bar = document.getElementById('strengthBar');
            const text = document.getElementById('strengthText');
            const levels = [
                { w: '0%', c: '', t: '' },
                { w: '20%', c: '#ef4444', t: 'Very weak' },
                { w: '40%', c: '#f59e0b', t: 'Weak' },
                { w: '60%', c: '#f59e0b', t: 'Fair' },
                { w: '80%', c: '#10b981', t: 'Strong' },
                { w: '100%', c: '#10b981', t: 'Very strong' }
            ];
            const l = levels[score];
            bar.style.width = l.w;
            bar.style.background = l.c;
            text.textContent = l.t;
            text.style.color = l.c;
        });

        // Form submit protection
        document.getElementById('signupForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
        });
    </script>
</body>
</html>
