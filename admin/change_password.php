<?php
// admin/change_password.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$admin_id = $_SESSION['admin_id'];

$profile_stmt = $db->prepare("SELECT full_name, email, username, last_login_at FROM admins WHERE id = ?");
$profile_stmt->execute([$admin_id]);
$admin_profile = $profile_stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['flash_message'] = "All fields are required.";
        $_SESSION['flash_type'] = "error";
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['flash_message'] = "New passwords do not match.";
        $_SESSION['flash_type'] = "error";
    } elseif (strlen($new_password) < 8) {
        $_SESSION['flash_message'] = "New password must be at least 8 characters long.";
        $_SESSION['flash_type'] = "error";
    } else {
        // Verify current password
        $stmt = $db->prepare("SELECT password_hash FROM admins WHERE id = ?");
        $stmt->execute([$admin_id]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($current_password, $admin['password_hash'])) {
            // Update password
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $db->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
            if ($update_stmt->execute([$new_hash, $admin_id])) {
                $_SESSION['flash_message'] = "Password changed successfully.";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "Failed to update password.";
                $_SESSION['flash_type'] = "error";
            }
        } else {
            $_SESSION['flash_message'] = "Current password is incorrect.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: change_password.php");
    exit;
}
?>

<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Admin Settings</h1>
        <p class="mt-1 text-sm text-slate-500">আপনার অ্যাকাউন্টের সেটিংস পরিবর্তন করুন</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Admin Profile Card -->
        <div class="md:col-span-1">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-primary/10 text-primary rounded-full flex items-center justify-center text-4xl font-bold mb-4 uppercase">
                    <?php echo htmlspecialchars(substr($admin_profile['full_name'] ?? $admin_profile['username'] ?? 'A', 0, 1)); ?>
                </div>
                <h3 class="font-serif-bn text-xl font-bold text-slate-900 mb-1">
                    <?php echo htmlspecialchars($admin_profile['full_name'] ?? ''); ?>
                </h3>
                <p class="text-sm text-slate-500 mb-3">@<?php echo htmlspecialchars($admin_profile['username'] ?? ''); ?></p>
                
                <div class="w-full text-left space-y-4 mt-4 pt-5 border-t border-slate-100">
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email Address</span>
                        <p class="text-sm text-slate-700 break-all"><?php echo htmlspecialchars($admin_profile['email'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Last Login</span>
                        <p class="text-sm text-slate-700">
                            <?php 
                            if (!empty($admin_profile['last_login_at'])) {
                                echo date('M j, Y \a\t g:i A', strtotime($admin_profile['last_login_at']));
                            } else {
                                echo 'Never';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="md:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-6 border-b border-slate-100 pb-4">
            <h2 class="font-serif-bn text-lg font-bold text-slate-900">Change Password</h2>
            <p class="text-sm text-slate-500">নতুন পাসওয়ার্ড সেট করুন। এটি অন্তত ৮ ক্যারেক্টার হতে হবে।</p>
        </div>

        <form action="change_password.php" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <label class="block">
                <span class="mb-1.5 block text-sm font-semibold text-slate-700">Current Password</span>
                <input type="password" name="current_password" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
            </label>

            <label class="block">
                <span class="mb-1.5 block text-sm font-semibold text-slate-700">New Password</span>
                <input type="password" id="new_password_input" name="new_password" required minlength="8" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                <div class="mt-2">
                    <div class="flex items-center justify-between mb-1 text-xs">
                        <span id="strength_text" class="font-medium text-slate-500">Password strength</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div id="strength_bar" class="bg-slate-300 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </label>

            <label class="block">
                <span class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm New Password</span>
                <input type="password" name="confirm_password" required minlength="8" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
            </label>

            <div class="pt-2">
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110 sm:w-auto">
                    Update Password
                </button>
            </div>
        </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pwdInput = document.getElementById('new_password_input');
    const strengthText = document.getElementById('strength_text');
    const strengthBar = document.getElementById('strength_bar');

    pwdInput.addEventListener('input', function() {
        const val = pwdInput.value;
        
        if (val.length === 0) {
            strengthBar.style.width = '0%';
            strengthBar.className = 'h-1.5 rounded-full bg-slate-300 transition-all duration-300';
            strengthText.textContent = 'Password strength';
            strengthText.className = 'font-medium text-slate-500';
            return;
        }

        const hasLower = /[a-z]/.test(val);
        const hasUpper = /[A-Z]/.test(val);
        const hasNumber = /[0-9]/.test(val);
        const hasSpecial = /[^A-Za-z0-9]/.test(val);

        let status = 'Weak';
        let barWidth = '33%';
        let barColor = 'bg-red-500';
        let textColor = 'text-red-500';

        if (hasLower && hasUpper && hasNumber && hasSpecial && val.length >= 8) {
            status = 'Strong';
            barWidth = '100%';
            barColor = 'bg-green-500';
            textColor = 'text-green-600';
        } else if ((hasLower || hasUpper) && hasNumber && val.length >= 6) {
            status = 'Medium';
            barWidth = '66%';
            barColor = 'bg-yellow-500';
            textColor = 'text-yellow-600';
        }

        strengthBar.style.width = barWidth;
        strengthBar.className = 'h-1.5 rounded-full transition-all duration-300 ' + barColor;
        strengthText.textContent = status;
        strengthText.className = 'font-medium ' + textColor;
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
