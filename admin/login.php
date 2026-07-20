<?php
// admin/login.php
require_once __DIR__ . '/../includes/auth.php';
init_secure_session();

require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/sanitize.php';

if (!empty($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$rate_limit_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    if (!verify_csrf_token($csrf_token)) {
        $error = "CSRF token validation failed.";
    } elseif (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        if (is_rate_limited($username, $ip_address)) {
            $rate_limit_error = "Too many failed login attempts. Please try again after 15 minutes.";
            log_login_attempt($username, $ip_address, false);
        } else {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT id, password_hash, is_active FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                if ($admin['is_active']) {
                    log_login_attempt($username, $ip_address, true);
                    
                    // Update last login
                    $updateStmt = $pdo->prepare("UPDATE admins SET last_login_at = NOW() WHERE id = ?");
                    $updateStmt->execute([$admin['id']]);

                    // Secure session regeneration
                    session_regenerate_id(true);
                    $_SESSION['admin_id'] = $admin['id'];
                    
                    header("Location: dashboard.php");
                    exit();
                } else {
                    log_login_attempt($username, $ip_address, false);
                    $error = "Your account has been deactivated.";
                }
            } else {
                log_login_attempt($username, $ip_address, false);
                $error = "Invalid username or password.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CDS Portfolio</title>
    <!-- Use basic styles to remain self-contained for admin -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96 text-center">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Admin Login</h2>
        
        <?php if ($rate_limit_error): ?>
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded text-sm"><?php echo e($rate_limit_error); ?></div>
        <?php elseif ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded text-sm"><?php echo e($error); ?></div>
        <?php endif; ?>

        <?php if (!$rate_limit_error): ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo e(generate_csrf_token()); ?>">
            
            <div class="mb-4 text-left">
                <label for="username" class="block text-sm font-semibold mb-1">Username</label>
                <input type="text" id="username" name="username" class="w-full border p-2 rounded" required>
            </div>
            
            <div class="mb-6 text-left">
                <label for="password" class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full border p-2 rounded" required>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Login</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
