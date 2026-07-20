<?php
// includes/auth.php
require_once __DIR__ . '/db.php';

function init_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
}

function require_admin_login() {
    if (empty($_SESSION['admin_id'])) {
        header("Location: ../admin/login.php");
        exit();
    }
}

function log_login_attempt($username, $ip, $success) {
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("INSERT INTO login_attempts (username, ip_address, success, attempted_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$username, $ip, $success ? 1 : 0]);
}

function is_rate_limited($username, $ip) {
    $pdo = Database::getConnection();
    // Check if more than 5 failed attempts in the last 15 minutes
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM login_attempts 
        WHERE (username = ? OR ip_address = ?) 
        AND success = 0 
        AND attempted_at >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
    ");
    $stmt->execute([$username, $ip]);
    $failed_attempts = $stmt->fetchColumn();
    
    return $failed_attempts >= 5;
}
