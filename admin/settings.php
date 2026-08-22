<?php
// admin/settings.php
require_once __DIR__ . '/../includes/auth.php';
init_secure_session();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/sanitize.php';
require_once __DIR__ . '/../includes/settings_helper.php';

require_admin_login();

$db = Database::getConnection();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("CSRF token validation failed.");
    }
    
    $action = $_POST['action'] ?? 'update_settings';

    if ($action === 'test_smtp') {
        require_once __DIR__ . '/../includes/mailer.php';
        $test_recipient = clean_input($_POST['test_recipient'] ?? '');
        if (empty($test_recipient) || !filter_var($test_recipient, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_message'] = "অনুগ্রহ করে একটি সঠিক টেস্ট ইমেইল ঠিকানা দিন।";
            $_SESSION['flash_type'] = "error";
        } else {
            $test_subject = "সিডিএস টেস্ট ইমেইল কনফিগারেশন | CDS SMTP Test Successful";
            $test_body = "<div style='font-family:sans-serif; padding:20px; color:#333; line-height:1.6;'>
                <h2 style='color:#0e1b64;'>সিডিএস মেইল কনফিগারেশন সফল হয়েছে! 🎉</h2>
                <p>এটি একটি স্বয়ংক্রিয় পরীক্ষা ইমেইল। আপনার সিডিএস ওয়েবসাইটের SMTP এবং মেইল ডিসপ্যাচার সফলভাবে কাজ করছে।</p>
                <p style='font-size:12px; color:#777;'>তারিখ ও সময়: " . date('Y-m-d H:i:s') . "</p>
            </div>";
            
            $res = send_cds_email($test_recipient, $test_subject, $test_body, 'Admin Test');
            if ($res['success']) {
                $_SESSION['flash_message'] = "টেস্ট ইমেইল সফলভাবে পাঠানো হয়েছে: {$test_recipient}";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "টেস্ট ইমেইল ব্যর্থ হয়েছে: " . $res['message'];
                $_SESSION['flash_type'] = "error";
            }
        }
        header("Location: settings.php?tab=email");
        exit;
    } elseif ($action === 'change_password') {
        $admin_id = $_SESSION['admin_id'];
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

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
            $stmt = $db->prepare("SELECT password_hash FROM admins WHERE id = ?");
            $stmt->execute([$admin_id]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($current_password, $admin['password_hash'])) {
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
    } else {
        // Ignore csrf_token and action from settings saving
        $settings_to_update = $_POST;
        unset($settings_to_update['csrf_token']);
        unset($settings_to_update['action']);
        
        $success = true;
    
    if ($db) {
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        foreach ($settings_to_update as $key => $value) {
            // Clean input (except for map embed code which might need special handling, but clean_input escapes HTML)
            if ($key === 'google_map_embed') {
                // Keep the iframe HTML but basic sanitization might break it, so allow it but carefully
                // We'll trust admin for map embed
                $val = trim($value);
            } else {
                $val = clean_input($value);
            }
            
            if (!$stmt->execute([$key, $val, $val])) {
                $success = false;
            }
        }
        
        if ($success) {
            $_SESSION['flash_message'] = "Settings updated successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to update some settings.";
            $_SESSION['flash_type'] = "error";
        }
    }
    }
    
    header("Location: settings.php");
    exit;
}

// Get all current settings
$settings = get_all_settings();

include 'includes/header.php';
?>

<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Site Settings</h1>
            <p class="mt-1 text-sm text-slate-500">ওয়েবসাইটের সাধারণ তথ্যাবলী পরিবর্তন করুন</p>
        </div>
    </div>

    <!-- AlpineJS for Tab Switching -->
    <div x-data="{ tab: '<?php echo e($_GET['tab'] ?? 'general'); ?>' }" class="rounded-xl border border-slate-200 bg-white shadow-sm">
        
        <!-- Tabs Header -->
        <div class="flex overflow-x-auto border-b border-slate-200 hide-scrollbar">
            <button @click="tab = 'general'" :class="tab === 'general' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'" class="whitespace-nowrap border-b-2 px-6 py-4 font-semibold text-sm transition-colors">General</button>
            <button @click="tab = 'contact'" :class="tab === 'contact' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'" class="whitespace-nowrap border-b-2 px-6 py-4 font-semibold text-sm transition-colors">Contact Info</button>
            <button @click="tab = 'social'" :class="tab === 'social' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'" class="whitespace-nowrap border-b-2 px-6 py-4 font-semibold text-sm transition-colors">Social Media</button>
            <button @click="tab = 'donation'" :class="tab === 'donation' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'" class="whitespace-nowrap border-b-2 px-6 py-4 font-semibold text-sm transition-colors">Donation & Payment</button>
            <button @click="tab = 'email'" :class="tab === 'email' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'" class="whitespace-nowrap border-b-2 px-6 py-4 font-semibold text-sm transition-colors text-indigo-700">Email & SMTP</button>
            <button @click="tab = 'advanced'" :class="tab === 'advanced' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'" class="whitespace-nowrap border-b-2 px-6 py-4 font-semibold text-sm transition-colors">Advanced</button>
            <button @click="tab = 'security'" :class="tab === 'security' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'" class="whitespace-nowrap border-b-2 px-6 py-4 font-semibold text-sm transition-colors text-rose-600">Security (Password)</button>
        </div>

        <form x-show="tab !== 'security'" action="settings.php" method="POST" class="p-6">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="update_settings">

            <!-- General Settings Tab -->
            <div x-show="tab === 'general'" class="space-y-6">
                <div>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Site Title (ওয়েবসাইটের নাম)</span>
                        <input type="text" name="site_title" value="<?php echo e($settings['site_title'] ?? ''); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>
                <div>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Slogan (স্লোগান)</span>
                        <input type="text" name="site_slogan" value="<?php echo e($settings['site_slogan'] ?? ''); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>
                <div>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Site Description (শর্ট ডেসক্রিপশন - For Footer & SEO)</span>
                        <textarea name="site_description" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20"><?php echo e($settings['site_description'] ?? ''); ?></textarea>
                    </label>
                </div>
                <div class="rounded-lg bg-blue-50 p-4 border border-blue-100 flex items-start gap-3 text-blue-800 text-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <p>লোগো পরিবর্তন করতে চাইলে সরাসরি কোড ডিরেক্টরি <code>/assets/img/cds-logo.png</code> এ ফাইলটি রিপ্লেস করুন। ভবিষ্যতে ডাইনামিক লোগো আপলোড যুক্ত করা হবে।</p>
                </div>
            </div>

            <!-- Contact Settings Tab -->
            <div x-show="tab === 'contact'" class="space-y-6" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Official Email (ইমেইল)</span>
                        <input type="email" name="site_email" value="<?php echo e($settings['site_email'] ?? ''); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Phone Number (ফোন নম্বর)</span>
                        <input type="text" name="site_phone" value="<?php echo e($settings['site_phone'] ?? ''); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>
                <div>
                    <label class="block mb-4">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Office Address (অফিস ঠিকানা - Bangla)</span>
                        <textarea name="site_address" rows="2" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20"><?php echo e($settings['site_address'] ?? ''); ?></textarea>
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Office Address (অফিস ঠিকানা - English)</span>
                        <textarea name="site_address_en" rows="2" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20"><?php echo e($settings['site_address_en'] ?? ''); ?></textarea>
                    </label>
                </div>
                <div>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Google Map Embed Code (অপশনাল)</span>
                        <textarea name="google_map_embed" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-mono text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20" placeholder='<iframe src="https://www.google.com/maps/embed?pb=..."></iframe>'><?php echo htmlspecialchars($settings['google_map_embed'] ?? ''); ?></textarea>
                        <span class="text-xs text-slate-500 mt-1">Google Maps থেকে Share > Embed a map কোডটি এখানে পেস্ট করুন।</span>
                    </label>
                </div>
            </div>

            <!-- Social Media Tab -->
            <div x-show="tab === 'social'" class="space-y-6" style="display: none;">
                <p class="text-sm text-slate-600 mb-4">যে লিংকগুলো খালি রাখবেন, সেগুলো ওয়েবসাইটে দেখাবে না।</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Facebook Page URL</span>
                        <input type="url" name="social_facebook" value="<?php echo e($settings['social_facebook'] ?? 'https://www.facebook.com/citizendevelopmentsociety'); ?>" placeholder="https://www.facebook.com/citizendevelopmentsociety" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">YouTube Channel URL</span>
                        <input type="url" name="social_youtube" value="<?php echo e($settings['social_youtube'] ?? ''); ?>" placeholder="https://youtube.com/..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">LinkedIn URL</span>
                        <input type="url" name="social_linkedin" value="<?php echo e($settings['social_linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">X (Twitter) URL</span>
                        <input type="url" name="social_twitter" value="<?php echo e($settings['social_twitter'] ?? ''); ?>" placeholder="https://x.com/..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>
            </div>

            <!-- Donation & Payment Settings Tab -->
            <div x-show="tab === 'donation'" class="space-y-6" style="display: none;">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-800">মোবাইল ব্যাংকিং অ্যাকাউন্ট</h3>
                    <p class="text-xs text-slate-500">ডোনেশন পেজে দেখানোর জন্য বিকাশ, নগদ ও রকেট নম্বরগুলো দিন।</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">বিকাশ পার্সোনাল নম্বর</span>
                        <input type="text" name="donation_bkash_personal" value="<?php echo e($settings['donation_bkash_personal'] ?? ($settings['donation_bkash'] ?? '')); ?>" placeholder="017xxxxxxxx" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-mono text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">বিকাশ মার্চেন্ট নম্বর</span>
                        <input type="text" name="donation_bkash_merchant" value="<?php echo e($settings['donation_bkash_merchant'] ?? ''); ?>" placeholder="018xxxxxxxx" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-mono text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">নগদ নম্বর</span>
                        <input type="text" name="donation_nagad" value="<?php echo e($settings['donation_nagad'] ?? ''); ?>" placeholder="019xxxxxxxx" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-mono text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">রকেট নম্বর</span>
                        <input type="text" name="donation_rocket" value="<?php echo e($settings['donation_rocket'] ?? ''); ?>" placeholder="017xxxxxxxx9" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-mono text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>

                <div class="border-b border-slate-100 pb-3 pt-4">
                    <h3 class="text-base font-bold text-slate-800">অফিসিয়াল ব্যাংক অ্যাকাউন্ট তথ্য</h3>
                    <p class="text-xs text-slate-500">ব্যাংক ট্রান্সফারের মাধ্যমে অনুদান গ্রহণের বিবরণ।</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">ব্যাংকের নাম (Bank Name)</span>
                        <input type="text" name="donation_bank_name" value="<?php echo e($settings['donation_bank_name'] ?? 'Islami Bank Bangladesh PLC'); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">অ্যাকাউন্টের নাম (Account Name)</span>
                        <input type="text" name="donation_bank_account_name" value="<?php echo e($settings['donation_bank_account_name'] ?? 'Citizen Development Society (CDS)'); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">অ্যাকাউন্ট নম্বর (Account Number)</span>
                        <input type="text" name="donation_bank_account_no" value="<?php echo e($settings['donation_bank_account_no'] ?? '20501234567890100'); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-mono text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">শাখা ও রাউটিং নম্বর (Branch & Routing)</span>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="donation_bank_branch" value="<?php echo e($settings['donation_bank_branch'] ?? 'Dhanmondi Branch, Dhaka'); ?>" placeholder="Branch Name" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                            <input type="text" name="donation_bank_routing_no" value="<?php echo e($settings['donation_bank_routing_no'] ?? '125271829'); ?>" placeholder="Routing No" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-mono text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                        </div>
                    </label>
                </div>
            </div>

            <!-- Email & SMTP Settings Tab -->
            <div x-show="tab === 'email'" class="space-y-6" style="display: none;">
                <div class="mb-4 border-b border-slate-100 pb-3">
                    <h3 class="font-serif-bn text-base font-bold text-slate-800">মেইল ও এসএমটিপি কনফিগারেশন (Email & SMTP Settings)</h3>
                    <p class="text-xs text-slate-500">নিউজলেটার ও অটোমেটিক ইমেইল প্রেরণের জন্য আপনার মেইল সার্ভার সেটিংস প্রদান করুন।</p>
                </div>

                <label class="flex items-center gap-3 rounded-xl border border-indigo-200 bg-indigo-50/70 p-4">
                    <input type="hidden" name="smtp_enabled" value="0">
                    <input type="checkbox" name="smtp_enabled" value="1" <?php echo (isset($settings['smtp_enabled']) && $settings['smtp_enabled'] === '1') ? 'checked' : ''; ?> class="h-5 w-5 rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <span class="block text-sm font-bold text-indigo-950">SMTP মেইল সার্ভার সক্রিয় করুন (Enable Custom SMTP)</span>
                        <span class="block text-xs text-indigo-700 mt-0.5">চেক করা থাকলে নিচের কাস্টম SMTP সার্ভার দিয়ে মেইল যাবে; আনচেক থাকলে পিএইচপি ডিফল্ট mail() ব্যবহার হবে।</span>
                    </div>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">SMTP Host (যেমন: smtp.hostinger.com / smtp.gmail.com)</span>
                        <input type="text" name="smtp_host" value="<?php echo e($settings['smtp_host'] ?? 'smtp.hostinger.com'); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 font-mono outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">SMTP Port (SSL: 465 / TLS: 587)</span>
                        <input type="number" name="smtp_port" value="<?php echo e($settings['smtp_port'] ?? '465'); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 font-mono outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">SMTP Encryption</span>
                        <select name="smtp_secure" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                            <option value="ssl" <?php echo (($settings['smtp_secure'] ?? 'ssl') === 'ssl') ? 'selected' : ''; ?>>SSL (Port 465 - Recommended)</option>
                            <option value="tls" <?php echo (($settings['smtp_secure'] ?? '') === 'tls') ? 'selected' : ''; ?>>TLS / STARTTLS (Port 587)</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Sender Email (From Email)</span>
                        <input type="email" name="smtp_from_email" value="<?php echo e($settings['smtp_from_email'] ?? 'contact@cds.fuminds.com'); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 font-mono outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">SMTP Username (Email Address)</span>
                        <input type="text" name="smtp_user" value="<?php echo e($settings['smtp_user'] ?? 'contact@cds.fuminds.com'); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 font-mono outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">SMTP Password (পাসওয়ার্ড)</span>
                        <input type="password" name="smtp_pass" value="<?php echo e($settings['smtp_pass'] ?? ''); ?>" placeholder="Enter SMTP password" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 font-mono outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>

                <div>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700">Sender Display Name (From Name)</span>
                        <input type="text" name="smtp_from_name" value="<?php echo e($settings['smtp_from_name'] ?? 'Citizen Development Society (CDS)'); ?>" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>
            </div>

            <!-- Advanced Tab -->
            <div x-show="tab === 'advanced'" class="space-y-6" style="display: none;">
                <label class="flex items-center gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <input type="hidden" name="maintenance_mode" value="0">
                    <input type="checkbox" name="maintenance_mode" value="1" <?php echo (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] === '1') ? 'checked' : ''; ?> class="h-5 w-5 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                    <div>
                        <span class="block text-sm font-bold text-amber-900">Maintenance Mode (মেইনটেন্যান্স মোড)</span>
                        <span class="block text-xs text-amber-700 mt-0.5">চালু থাকলে অ্যাডমিন ছাড়া সাধারণ ভিজিটররা ওয়েবসাইট দেখতে পারবে না, 'Coming Soon' বা মেইনটেন্যান্স নোটিশ দেখাবে। (এই ফিচারটি পরে ওয়েবসাইটে ইন্টিগ্রেট করা হবে)</span>
                    </div>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 border-t border-slate-100 pt-5 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-2.5 text-sm font-bold text-primary-foreground shadow-sm hover:brightness-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Save Changes
                </button>
            </div>
        </form>

        <!-- Test Email Sender (Shown only on email tab) -->
        <div x-show="tab === 'email'" class="p-6 border-t border-slate-100 bg-slate-50/70 rounded-b-xl" style="display: none;">
            <h4 class="text-sm font-bold text-slate-800 mb-1">টেস্ট ইমেইল পাঠান (Test SMTP Connection)</h4>
            <p class="text-xs text-slate-500 mb-4">উপরের সেটিংস সংরক্ষণ করার পর আপনার নিজস্ব ইমেইলে একটি টেস্ট মেসেজ পাঠিয়ে কানেকশন যাচাই করুন।</p>
            <form action="settings.php" method="POST" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="action" value="test_smtp">
                <input type="email" name="test_recipient" required placeholder="আপনার টেস্ট ইমেইল ঠিকানা..." class="flex-grow max-w-md rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-800 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 text-sm font-bold shadow-sm transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Send Test Mail
                </button>
            </form>
        </div>

        <!-- Security / Change Password Tab -->
        <form x-show="tab === 'security'" action="settings.php" method="POST" class="p-6 space-y-6" style="display: none;">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="change_password">

            <div class="mb-6 border-b border-slate-100 pb-4">
                <h2 class="font-serif-bn text-lg font-bold text-slate-900">Change Password</h2>
                <p class="text-sm text-slate-500">নতুন পাসওয়ার্ড সেট করুন। এটি অন্তত ৮ ক্যারেক্টার হতে হবে।</p>
            </div>

            <label class="block">
                <span class="mb-1.5 block text-sm font-semibold text-slate-700">Current Password</span>
                <input type="password" name="current_password" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
            </label>

            <label class="block">
                <span class="mb-1.5 block text-sm font-semibold text-slate-700">New Password</span>
                <input type="password" id="new_password_input" name="new_password" required minlength="8" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
            </label>

            <label class="block">
                <span class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm New Password</span>
                <input type="password" name="confirm_password" required minlength="8" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
            </label>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:brightness-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- AlpineJS -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<?php include 'includes/footer.php'; ?>
