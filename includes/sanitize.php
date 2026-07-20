<?php
// includes/sanitize.php

function e($string) {
    if ($string === null) return '';
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

function clean_input($string) {
    if ($string === null) return '';
    $string = trim($string);
    $string = str_replace(chr(0), '', $string); // Strip null bytes
    return $string;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_phone($phone) {
    // Validates BD phone numbers like 01712345678, +8801712345678, 8801712345678
    return preg_match('/^(?:\+88|88)?01[3-9]\d{8}$/', $phone) === 1;
}
