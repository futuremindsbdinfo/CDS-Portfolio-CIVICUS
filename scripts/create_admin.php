<?php
// scripts/create_admin.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/db.php';

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

echo "=== Create Admin User ===\n";

echo "Enter Username: ";
$username = trim(fgets(STDIN));

echo "Enter Full Name: ";
$full_name = trim(fgets(STDIN));

echo "Enter Email: ";
$email = trim(fgets(STDIN));

echo "Enter Password (will not be hidden): ";
$password = trim(fgets(STDIN));

if (empty($username) || empty($password) || empty($email) || empty($full_name)) {
    die("Error: All fields are required.\n");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo = Database::getConnection();
    
    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        die("Error: Username or Email already exists.\n");
    }

    $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash, email, full_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password_hash, $email, $full_name]);
    
    echo "Success: Admin user '{$username}' created successfully.\n";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
