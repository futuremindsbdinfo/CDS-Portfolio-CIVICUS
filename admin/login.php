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
        } else {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT id, password_hash, is_active FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            $stmt->closeCursor();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                if ($admin['is_active']) {
                    log_login_attempt($username, $ip_address, true);
                    
                    // Clear failed attempts upon successful login
                    try {
                        $clearStmt = $pdo->prepare("DELETE FROM login_attempts WHERE username = ? OR ip_address = ?");
                        $clearStmt->execute([$username, $ip_address]);
                    } catch (Exception $e) {}

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
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>এডমিন লগইন — সিডিএস পোর্টফোলিও</title>
    <link rel="icon" type="image/png" href="/assets/img/cds-logo.png">
    
    <!-- Bengali Fonts: Kalpurush + SolaimanLipi -->
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    
    <!-- Tailwind CSS CDN (Guarantees styles work independently) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans-bn': ['Kalpurush', 'SolaimanLipi', 'Arial', 'sans-serif'],
                        'serif-bn': ['SolaimanLipi', 'Kalpurush', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 font-sans-bn text-slate-100">

    <!-- Background Decorative Glowing Orbs -->
    <div class="pointer-events-none absolute -left-20 -top-20 h-96 w-96 rounded-full bg-emerald-600/25 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 -right-20 h-96 w-96 rounded-full bg-blue-700/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-1/2 left-1/2 h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-emerald-500/10 blur-[120px]"></div>

    <!-- Login Card Container -->
    <div class="relative z-10 mx-4 w-full max-w-md">
        <div class="overflow-hidden rounded-3xl border border-white/15 bg-white/95 p-8 text-slate-800 shadow-2xl backdrop-blur-xl sm:p-10">
            
            <!-- Logo & Title -->
            <div class="text-center">
                <a href="/index.php" class="inline-block transition-transform hover:scale-105">
                    <img src="/assets/img/cds-logo.png" alt="CDS Logo" class="mx-auto h-16 w-auto drop-shadow-md">
                </a>
                <h1 class="mt-4 font-serif-bn text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    এডমিন প্যানেল
                </h1>
                <p class="mt-1 text-xs font-medium text-slate-500">
                    সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)
                </p>
            </div>

            <!-- Error Alerts -->
            <?php if ($rate_limit_error): ?>
                <div class="mt-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-medium text-rose-700">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 shrink-0 text-rose-600">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                    </svg>
                    <div><?php echo e($rate_limit_error); ?></div>
                </div>
            <?php elseif ($error): ?>
                <div class="mt-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-medium text-rose-700">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 shrink-0 text-rose-600">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                    </svg>
                    <div><?php echo e($error); ?></div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <?php if (!$rate_limit_error): ?>
            <form method="POST" action="" class="mt-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?php echo e(generate_csrf_token()); ?>">
                
                <!-- Username Input -->
                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                        ইউজারনেম (Username)
                    </label>
                    <div class="relative mt-2">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <circle cx="12" cy="8" r="4"/><path d="M6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                            </svg>
                        </div>
                        <input type="text" id="username" name="username" required
                            placeholder="আপনার ইউজারনেম দিন"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-3 pl-11 pr-4 text-sm font-medium text-slate-900 transition focus:border-emerald-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                    </div>
                </div>
                
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                        পাসওয়ার্ড (Password)
                    </label>
                    <div class="relative mt-2">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-3 pl-11 pr-11 text-sm font-medium text-slate-900 transition focus:border-emerald-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/20">
                        
                        <!-- Toggle Password Button -->
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="group relative flex w-full items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3.5 text-sm font-bold text-white shadow-lg transition-all hover:from-emerald-700 hover:to-emerald-800 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-emerald-600/30">
                    <span>লগইন করুন</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:translate-x-1">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </form>
            <?php endif; ?>

            <!-- Footer Link -->
            <div class="mt-8 border-t border-slate-200/80 pt-5 text-center text-xs font-medium text-slate-500">
                <a href="../index.php" class="inline-flex items-center gap-1 text-slate-600 transition-colors hover:text-emerald-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5">
                        <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>ওয়েবসাইটে ফিরে যান</span>
                </a>
            </div>

        </div>
        
        <!-- Bottom Copyright -->
        <p class="mt-6 text-center text-xs font-medium text-slate-400">
            &copy; <?php echo date('Y'); ?> সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)
        </p>
    </div>

    <!-- Vanilla JS Toggle Password script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('togglePassword');
            const pwdInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (toggleBtn && pwdInput) {
                toggleBtn.addEventListener('click', function() {
                    const isPwd = pwdInput.type === 'password';
                    pwdInput.type = isPwd ? 'text' : 'password';
                    
                    if (isPwd) {
                        eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8"/>';
                    } else {
                        eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
                    }
                });
            }
        });
    </script>
</body>
</html>
