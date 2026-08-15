<?php
// unsubscribe.php
// Public Unsubscribe Handler for CDS Newsletter

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/mailer.php';

$email = clean_input($_GET['email'] ?? ($_POST['email'] ?? ''));
$token = clean_input($_GET['token'] ?? ($_POST['token'] ?? ''));
$action = clean_input($_POST['action'] ?? '');

$pdo = Database::getConnection();
$status = 'initial';
$message = '';

if (!empty($email) && !empty($token)) {
    $expected_token = get_unsubscribe_token($email);
    
    if (hash_equals($expected_token, $token)) {
        // Resubscribe action
        if ($action === 'resubscribe') {
            try {
                $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = 'active' WHERE email = ?");
                $stmt->execute([$email]);
                $status = 'resubscribed';
                $message = 'আপনাকে পুনরায় সিডিএস নিউজলেটারে সক্রিয় করা হয়েছে! ধন্যবাদ।';
            } catch (PDOException $e) {
                $status = 'error';
                $message = 'ডাটাবেজ ত্রুটি: ' . $e->getMessage();
            }
        } 
        // Confirm Unsubscribe action
        else if ($action === 'confirm_unsub' || isset($_GET['auto'])) {
            try {
                $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = 'unsubscribed' WHERE email = ?");
                $stmt->execute([$email]);
                $status = 'unsubscribed';
                $message = 'আপনার ইমেইলটি সফলভাবে নিউজলেটার তালিকা থেকে আনসাবস্ক্রাইব করা হয়েছে।';
            } catch (PDOException $e) {
                $status = 'error';
                $message = 'ডাটাবেজ ত্রুটি: ' . $e->getMessage();
            }
        } else {
            $status = 'confirm';
        }
    } else {
        $status = 'invalid';
        $message = 'নিরাপত্তা টোকেন সঠিক নয় বা লিংকটির মেয়াদ শেষ হয়েছে।';
    }
} else {
    $status = 'invalid';
    $message = 'ইমেইল অথবা নিরাপত্তা টোকেন পাওয়া যায়নি।';
}
?>

<main class="flex-grow flex items-center justify-center py-20 px-4 bg-slate-50">
    <div class="max-w-lg w-full bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-xl text-center">
        
        <!-- Icon -->
        <div class="mx-auto w-16 h-16 rounded-2xl flex items-center justify-center mb-6 <?php 
            echo ($status === 'unsubscribed') ? 'bg-amber-100 text-amber-700' : 
                 (($status === 'resubscribed') ? 'bg-emerald-100 text-emerald-700' : 
                 (($status === 'confirm') ? 'bg-blue-100 text-blue-700' : 'bg-rose-100 text-rose-700')); 
        ?>">
            <?php if ($status === 'unsubscribed'): ?>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            <?php elseif ($status === 'resubscribed'): ?>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?php elseif ($status === 'confirm'): ?>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?php else: ?>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <?php endif; ?>
        </div>

        <?php if ($status === 'confirm'): ?>
            <h1 class="text-2xl font-bold font-serif-bn text-slate-800 mb-2">আপনি কি আনসাবস্ক্রাইব করতে নিশ্চিত?</h1>
            <p class="text-sm text-slate-600 mb-6">
                ইমেইল: <strong class="text-slate-800 font-mono"><?php echo htmlspecialchars($email); ?></strong><br>
                আনসাবস্ক্রাইব করলে আপনি সিডিএস-এর ভবিষ্যত প্রকল্প আপডেট ও নোটিশ ইমেইলে পাবেন না।
            </p>
            <form method="POST" class="flex flex-col sm:flex-row gap-3 justify-center">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="action" value="confirm_unsub">
                <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold shadow-md transition">
                    হ্যাঁ, আনসাবস্ক্রাইব করুন
                </button>
                <a href="/index.php" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold transition">
                    বাতিল
                </a>
            </form>

        <?php elseif ($status === 'unsubscribed'): ?>
            <h1 class="text-2xl font-bold font-serif-bn text-slate-800 mb-2">আনসাবস্ক্রিপশন সম্পন্ন হয়েছে</h1>
            <p class="text-sm text-slate-600 mb-6"><?php echo htmlspecialchars($message); ?></p>
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl mb-6 text-xs text-slate-500">
                ভুলবশত আনসাবস্ক্রাইব করে থাকলে নিচে ক্লিক করে আবার সাবস্ক্রাইব করতে পারেন:
            </div>
            <form method="POST" class="inline-block mb-4">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="action" value="resubscribe">
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                    পুনরায় সাবস্ক্রাইব করুন
                </button>
            </form>
            <div class="block">
                <a href="/index.php" class="text-xs font-bold text-primary hover:underline">হোমপেজে ফিরে যান &rarr;</a>
            </div>

        <?php elseif ($status === 'resubscribed'): ?>
            <h1 class="text-2xl font-bold font-serif-bn text-emerald-800 mb-2">পুনরায় স্বাগতম!</h1>
            <p class="text-sm text-slate-600 mb-6"><?php echo htmlspecialchars($message); ?></p>
            <a href="/index.php" class="inline-block px-6 py-3 bg-primary hover:brightness-110 text-white rounded-xl text-sm font-bold shadow-md transition">
                হোমপেজে ফিরে যান
            </a>

        <?php else: ?>
            <h1 class="text-2xl font-bold font-serif-bn text-rose-800 mb-2">অনুরোধটি ব্যর্থ হয়েছে</h1>
            <p class="text-sm text-slate-600 mb-6"><?php echo htmlspecialchars($message); ?></p>
            <a href="/index.php" class="inline-block px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-bold transition">
                হোমপেজে ফিরে যান
            </a>
        <?php endif; ?>

    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
