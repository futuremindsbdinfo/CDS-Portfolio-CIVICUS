<?php
// admin/change_password.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$admin_id = $_SESSION['admin_id'];

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

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Admin Settings</h1>
        <p class="mt-1 text-sm text-slate-500">আপনার অ্যাকাউন্টের সেটিংস পরিবর্তন করুন</p>
    </div>

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
                <input type="password" name="new_password" required minlength="8" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
