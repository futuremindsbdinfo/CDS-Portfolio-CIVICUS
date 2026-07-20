<?php
// includes/csrf.php

if (session_status() === PHP_SESSION_NONE) {
    throw new Exception("Session must be started before using CSRF functions.");
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token); // Prevent timing attacks
}
