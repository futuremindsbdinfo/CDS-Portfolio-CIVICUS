<?php
// includes/auth.php
require_once __DIR__ . '/db.php';

function init_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        // Detect HTTPS across proxies / Cloudflare / cPanel
        $isHttps = (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (!empty($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
        );

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        $session_path = __DIR__ . '/../logs/sessions';
        if (!is_dir($session_path)) {
            @mkdir($session_path, 0777, true);
        }
        if (is_dir($session_path) && is_writable($session_path)) {
            session_save_path($session_path);
        }
        
        session_start();
    }
}

function require_admin_login() {
    if (empty($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

function log_login_attempt($username, $ip, $success) {
    $pdo = Database::getConnection();
    if (!$pdo) return;
    try {
        $stmt = $pdo->prepare("INSERT INTO login_attempts (username, ip_address, success, attempted_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$username, $ip, $success ? 1 : 0]);
    } catch (PDOException $e) {
        // Silently fail if table issue
    }
}

function is_rate_limited($username, $ip) {
    $pdo = Database::getConnection();
    if (!$pdo) return false;
    try {
        // Check if more than 5 failed attempts in the last 15 minutes
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM login_attempts 
            WHERE (username = ? OR ip_address = ?) 
            AND success = 0 
            AND attempted_at >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
        ");
        $stmt->execute([$username, $ip]);
        $failed_attempts = (int)$stmt->fetchColumn();
        $stmt->closeCursor();
        
        return $failed_attempts >= 5;
    } catch (PDOException $e) {
        return false;
    }
}
