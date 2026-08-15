<?php
// admin/admins_admin.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$message = '';
$error = '';
$current_admin_id = (int)($_SESSION['admin_id'] ?? 0);

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token mismatch. Please reload the page.';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'add') {
            $username = clean_input($_POST['username'] ?? '');
            $email = clean_input($_POST['email'] ?? '');
            $full_name = clean_input($_POST['full_name'] ?? '');
            $password = $_POST['password'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
                $error = 'সকল ফিল্ড পূরণ করা আবশ্যক।';
            } elseif (!validate_email($email)) {
                $error = 'সঠিক ইমেইল অ্যাড্রেস প্রদান করুন।';
            } elseif (strlen($password) < 6) {
                $error = 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
            } else {
                try {
                    // Check duplicate username or email
                    $checkStmt = $db->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
                    $checkStmt->execute([$username, $email]);
                    if ($checkStmt->fetch()) {
                        $error = 'এই ইউজারনেম বা ইমেইল ইতিমধ্যে ব্যবহৃত হয়েছে।';
                    } else {
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        $stmt = $db->prepare("INSERT INTO admins (username, email, full_name, password_hash, is_active) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$username, $email, $full_name, $hash, $is_active]);
                        $message = 'নতুন এডমিন সফলভাবে তৈরি করা হয়েছে!';
                    }
                } catch (PDOException $e) {
                    $error = 'ডাটাবেজ ত্রুটি: ' . $e->getMessage();
                }
            }
        } elseif ($action === 'edit') {
            $admin_id = (int)($_POST['id'] ?? 0);
            $full_name = clean_input($_POST['full_name'] ?? '');
            $email = clean_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if ($admin_id > 0) {
                if (empty($full_name) || empty($email)) {
                    $error = 'নাম এবং ইমেইল প্রদান করুন।';
                } else {
                    try {
                        if (!empty($password)) {
                            if (strlen($password) < 6) {
                                $error = 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
                            } else {
                                $hash = password_hash($password, PASSWORD_BCRYPT);
                                $stmt = $db->prepare("UPDATE admins SET full_name = ?, email = ?, password_hash = ?, is_active = ? WHERE id = ?");
                                $stmt->execute([$full_name, $email, $hash, $is_active, $admin_id]);
                                $message = 'এডমিনের তথ্য ও পাসওয়ার্ড আপডেট হয়েছে!';
                            }
                        } else {
                            $stmt = $db->prepare("UPDATE admins SET full_name = ?, email = ?, is_active = ? WHERE id = ?");
                            $stmt->execute([$full_name, $email, $is_active, $admin_id]);
                            $message = 'এডমিনের তথ্য আপডেট করা হয়েছে!';
                        }
                    } catch (PDOException $e) {
                        $error = 'আপডেট ব্যর্থ: ' . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'delete') {
            $admin_id = (int)($_POST['id'] ?? 0);
            if ($admin_id === $current_admin_id) {
                $error = 'নিজে নিজের অ্যাকাউন্ট মুছে ফেলা সম্ভব নয়!';
            } elseif ($admin_id > 0) {
                try {
                    $stmt = $db->prepare("DELETE FROM admins WHERE id = ?");
                    $stmt->execute([$admin_id]);
                    $message = 'এডমিন মুছে ফেলা হয়েছে!';
                } catch (PDOException $e) {
                    $error = 'মুছে ফেলা যায়নি: ' . $e->getMessage();
                }
            }
        } elseif ($action === 'toggle') {
            $admin_id = (int)($_POST['id'] ?? 0);
            if ($admin_id === $current_admin_id) {
                $error = 'নিজের অ্যাকাউন্ট নিষ্ক্রিয় করা সম্ভব নয়!';
            } elseif ($admin_id > 0) {
                try {
                    $stmt = $db->prepare("UPDATE admins SET is_active = 1 - is_active WHERE id = ?");
                    $stmt->execute([$admin_id]);
                    $message = 'স্ট্যাটাস আপডেট হয়েছে!';
                } catch (PDOException $e) {
                    $error = 'আপডেট ব্যর্থ: ' . $e->getMessage();
                }
            }
        }
    }
}

// Fetch all admins
$admins = [];
if ($db) {
    try {
        $admins = $db->query("SELECT id, username, email, full_name, is_active, last_login_at, created_at FROM admins ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $admins = [];
    }
}

// Fetch edit admin
$edit_admin = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    foreach ($admins as $a) {
        if ((int)$a['id'] === $edit_id) {
            $edit_admin = $a;
            break;
        }
    }
}
?>

<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold font-serif-bn text-slate-800">এডমিন ইউজার ও একাউন্টস</h1>
            <p class="text-sm text-slate-500">প্যানেল ব্যবহারের জন্য সাব-এডমিন তৈরি, পারমিশন ও পাসওয়ার্ড নিয়ন্ত্রণ</p>
        </div>
        <?php if ($edit_admin): ?>
            <a href="admins_admin.php" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-300 transition">
                + নতুন এডমিন তৈরি করুন
            </a>
        <?php endif; ?>
    </div>

    <!-- Alerts -->
    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?php echo e($message); ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-5">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm sticky top-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <?php echo $edit_admin ? 'এডমিন তথ্য সম্পাদনা' : 'নতুন এডমিন তৈরি করুন'; ?>
                </h2>

                <form method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="action" value="<?php echo $edit_admin ? 'edit' : 'add'; ?>">
                    <?php if ($edit_admin): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_admin['id']; ?>">
                    <?php endif; ?>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">পূর্ণ নাম *</label>
                        <input type="text" name="full_name" required value="<?php echo e($edit_admin['full_name'] ?? ''); ?>" placeholder="যেমন: মোহাম্মদ আরিফ" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <?php if (!$edit_admin): ?>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">ইউজারনেম (Login Username) *</label>
                            <input type="text" name="username" required placeholder="যেমন: arif_admin" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                    <?php else: ?>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">ইউজারনেম (অপরিবর্তনীয়)</label>
                            <input type="text" disabled value="<?php echo e($edit_admin['username']); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">ইমেইল ঠিকানা *</label>
                        <input type="email" name="email" required value="<?php echo e($edit_admin['email'] ?? ''); ?>" placeholder="admin@fuminds.com" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            <?php echo $edit_admin ? 'নতুন পাসওয়ার্ড (পরিবর্তন না করতে চাইলে খালি রাখুন)' : 'লগইন পাসওয়ার্ড *'; ?>
                        </label>
                        <input type="password" name="password" <?php echo $edit_admin ? '' : 'required'; ?> placeholder="••••••••" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div class="flex items-center pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" <?php echo (!isset($edit_admin) || $edit_admin['is_active']) ? 'checked' : ''; ?> class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                            <span class="text-xs font-bold text-slate-700">অ্যাকাউন্ট সক্রিয় রাখুন</span>
                        </label>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-lg shadow-sm transition">
                            <?php echo $edit_admin ? 'আপডেট করুন' : 'এডমিন তৈরি করুন'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admin Users List Section -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-800">বিদ্যমান এডমিন তালিকা (<?php echo count($admins); ?>)</h2>
                </div>

                <div class="divide-y divide-slate-100">
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $a): ?>
                            <div class="p-4 flex items-center gap-4 hover:bg-slate-50 transition">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 grid place-items-center shrink-0 font-bold text-sm border border-indigo-100">
                                    <?php echo strtoupper(substr($a['username'], 0, 2)); ?>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <h3 class="text-sm font-bold text-slate-800 truncate"><?php echo e($a['full_name']); ?></h3>
                                        <span class="font-mono text-xs text-slate-400">(@<?php echo e($a['username']); ?>)</span>
                                        <?php if ((int)$a['id'] === $current_admin_id): ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary-soft text-primary">You</span>
                                        <?php endif; ?>
                                        <?php if ($a['is_active']): ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Active</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xs text-slate-500 truncate"><?php echo e($a['email']); ?></p>
                                    <p class="text-[11px] text-slate-400">সর্বশেষ লগইন: <?php echo !empty($a['last_login_at']) ? date('d M, Y h:i A', strtotime($a['last_login_at'])) : 'কখনই না'; ?></p>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a href="admins_admin.php?edit=<?php echo $a['id']; ?>" title="Edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <?php if ((int)$a['id'] !== $current_admin_id): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                            <button type="submit" title="Toggle Status" class="p-2 text-slate-500 hover:text-primary hover:bg-slate-100 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                            </button>
                                        </form>

                                        <form method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত এই এডমিন একাউন্টটি মুছে ফেলতে চান?');" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                            <button type="submit" title="Delete" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
