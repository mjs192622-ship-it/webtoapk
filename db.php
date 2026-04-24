<?php
/**
 * Database Connection & Migration
 * Uses SQLite — no MySQL server needed
 */

define('DB_PATH', __DIR__ . '/database.sqlite');

function getDB() {
    static $db = null;
    if ($db === null) {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->exec('PRAGMA journal_mode=WAL');
        $db->exec('PRAGMA foreign_keys=ON');
    }
    return $db;
}

function runMigrations() {
    $db = getDB();
    
    // Users table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        avatar TEXT DEFAULT NULL,
        plan TEXT DEFAULT 'free',
        apps_generated INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // User sessions table (for remember me)
    $db->exec("CREATE TABLE IF NOT EXISTS sessions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        token TEXT NOT NULL UNIQUE,
        expires_at DATETIME NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // App builds history
    $db->exec("CREATE TABLE IF NOT EXISTS builds (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER DEFAULT NULL,
        build_id TEXT NOT NULL,
        app_name TEXT NOT NULL,
        package_name TEXT NOT NULL,
        website_url TEXT NOT NULL,
        version TEXT DEFAULT '1.0',
        icon_path TEXT DEFAULT NULL,
        status TEXT DEFAULT 'generated',
        download_url TEXT DEFAULT NULL,
        config_json TEXT DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");

    // Password reset tokens
    $db->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL,
        token TEXT NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Build queue for self-hosted APK builds
    $db->exec("CREATE TABLE IF NOT EXISTS build_queue (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        build_id TEXT NOT NULL,
        project_dir TEXT NOT NULL,
        app_name TEXT DEFAULT '',
        status TEXT DEFAULT 'pending',
        progress INTEGER DEFAULT 0,
        log_file TEXT DEFAULT NULL,
        apk_path TEXT DEFAULT NULL,
        error_message TEXT DEFAULT NULL,
        started_at DATETIME DEFAULT NULL,
        completed_at DATETIME DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Indexes
    $db->exec("CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_sessions_token ON sessions(token)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_builds_user ON builds(user_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_builds_build_id ON builds(build_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_build_queue_build_id ON build_queue(build_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_build_queue_status ON build_queue(status)");
}

// Auto-run migrations on first include
runMigrations();
