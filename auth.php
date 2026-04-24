<?php
/**
 * Authentication Functions
 * Handles registration, login, session management
 */

require_once __DIR__ . '/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Generate a cryptographically secure random token
 */
function generateToken($length = 64) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Generate CSRF token
 */
function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = generateToken(32);
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCsrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Register a new user
 */
function registerUser($name, $email, $password) {
    $db = getDB();
    
    // Check if email exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'error' => 'Email already registered'];
    }
    
    // Hash password
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), strtolower(trim($email)), $hash]);
    
    $userId = $db->lastInsertId();
    
    // Auto login after register
    loginSession($userId);
    
    return ['success' => true, 'user_id' => $userId];
}

/**
 * Login user by email/password
 */
function loginUser($email, $password, $remember = false) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([strtolower(trim($email))]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'error' => 'Invalid email or password'];
    }
    
    loginSession($user['id']);
    
    // Remember me cookie
    if ($remember) {
        $token = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $stmt = $db->prepare("INSERT INTO sessions (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], hash('sha256', $token), $expires]);
        
        setcookie('remember_token', $token, [
            'expires' => strtotime('+30 days'),
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    
    return ['success' => true, 'user' => $user];
}

/**
 * Set user session
 */
function loginSession($userId) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();
}

/**
 * Get current logged-in user
 */
function getCurrentUser() {
    // Check session first
    if (!empty($_SESSION['user_id'])) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, name, email, avatar, plan, apps_generated, created_at FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch() ?: null;
        return normalizeUserForFreeMode($user);
    }
    
    // Check remember me cookie
    if (!empty($_COOKIE['remember_token'])) {
        $db = getDB();
        $hash = hash('sha256', $_COOKIE['remember_token']);
        $stmt = $db->prepare("SELECT user_id FROM sessions WHERE token = ? AND expires_at > datetime('now')");
        $stmt->execute([$hash]);
        $session = $stmt->fetch();
        
        if ($session) {
            loginSession($session['user_id']);
            $stmt = $db->prepare("SELECT id, name, email, avatar, plan, apps_generated, created_at FROM users WHERE id = ?");
            $stmt->execute([$session['user_id']]);
            $user = $stmt->fetch() ?: null;
            return normalizeUserForFreeMode($user);
        } else {
            // Expired or invalid — clear cookie
            setcookie('remember_token', '', ['expires' => time() - 3600, 'path' => '/']);
        }
    }
    
    return null;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return getCurrentUser() !== null;
}

/**
 * Logout user
 */
function logoutUser() {
    $db = getDB();
    
    // Delete remember token
    if (!empty($_COOKIE['remember_token'])) {
        $hash = hash('sha256', $_COOKIE['remember_token']);
        $stmt = $db->prepare("DELETE FROM sessions WHERE token = ?");
        $stmt->execute([$hash]);
        setcookie('remember_token', '', ['expires' => time() - 3600, 'path' => '/']);
    }
    
    // Destroy session
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path']);
    }
    session_destroy();
}

/**
 * Update user profile
 */
function updateProfile($userId, $data) {
    $db = getDB();
    $allowed = ['name', 'email', 'avatar'];
    $sets = [];
    $values = [];
    
    foreach ($allowed as $field) {
        if (isset($data[$field])) {
            $sets[] = "$field = ?";
            $values[] = $field === 'email' ? strtolower(trim($data[$field])) : htmlspecialchars($data[$field], ENT_QUOTES, 'UTF-8');
        }
    }
    
    if (empty($sets)) return false;
    
    // Check email uniqueness if email is being changed
    if (isset($data['email'])) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([strtolower(trim($data['email'])), $userId]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'Email already in use'];
        }
    }
    
    $sets[] = "updated_at = CURRENT_TIMESTAMP";
    $values[] = $userId;
    
    $stmt = $db->prepare("UPDATE users SET " . implode(', ', $sets) . " WHERE id = ?");
    $stmt->execute($values);
    
    return ['success' => true];
}

/**
 * Change password
 */
function changePassword($userId, $currentPassword, $newPassword) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($currentPassword, $user['password'])) {
        return ['success' => false, 'error' => 'Current password is incorrect'];
    }
    
    $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $db->prepare("UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$hash, $userId]);
    
    return ['success' => true];
}

/**
 * Save a build record
 */
function saveBuild($data) {
    $db = getDB();
    
    $stmt = $db->prepare("INSERT INTO builds (user_id, build_id, app_name, package_name, website_url, version, icon_path, status, download_url, config_json) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['user_id'] ?? null,
        $data['build_id'],
        $data['app_name'],
        $data['package_name'],
        $data['website_url'],
        $data['version'] ?? '1.0',
        $data['icon_path'] ?? null,
        $data['status'] ?? 'generated',
        $data['download_url'] ?? null,
        $data['config_json'] ?? null
    ]);
    
    // Increment user's app count
    if (!empty($data['user_id'])) {
        $db->exec("UPDATE users SET apps_generated = apps_generated + 1 WHERE id = " . intval($data['user_id']));
    }
    
    return $db->lastInsertId();
}

/**
 * Get user's builds
 */
function getUserBuilds($userId, $limit = 20, $offset = 0) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM builds WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->execute([$userId, $limit, $offset]);
    return $stmt->fetchAll();
}

/**
 * Get user's build count
 */
function getUserBuildCount($userId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM builds WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch()['count'];
}

/**
 * Get user initials for avatar
 */
function getInitials($name) {
    $parts = explode(' ', trim($name));
    $initials = '';
    foreach (array_slice($parts, 0, 2) as $part) {
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
    }
    return $initials ?: '?';
}

/**
 * Normalize user state when free mode is enabled.
 */
function normalizeUserForFreeMode($user) {
    if (!$user) {
        return null;
    }

    if (defined('FREE_MODE') && FREE_MODE) {
        $user['plan'] = 'free';
    }

    return $user;
}
