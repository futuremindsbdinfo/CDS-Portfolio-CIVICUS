<?php
// admin/migrate.php
require_once __DIR__ . '/includes/header.php';

$pdo = Database::getConnection();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_migration'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'নিরাপত্তা টোকেন মেয়াদোত্তীর্ণ হয়েছে। পুনরায় চেষ্টা করুন।';
    } else {
        try {
            ensure_database_tables_exist($pdo);
            $message = 'ডাটাবেস মাইগ্রেশন সফলভাবে সম্পন্ন হয়েছে! সকল টেবিল প্রস্তুত।';
        } catch (Throwable $e) {
            $error = 'মাইগ্রেশন ব্যর্থ হয়েছে: ' . $e->getMessage();
        }
    }
}

// Check status of tables
$tables = [
    'admins',
    'hero_sliders',
    'team_members',
    'downloadable_forms',
    'newsletter_subscribers',
    'blogs',
    'notices',
    'projects',
    'gallery',
    'publications',
    'gov_links',
    'contact_messages',
    'donation_interests',
    'settings'
];

$table_status = [];
foreach ($tables as $t) {
    try {
        $pdo->query("SELECT 1 FROM {$t} LIMIT 1");
        $table_status[$t] = true;
    } catch (Throwable $e) {
        $table_status[$t] = false;
    }
}
?>

<div class="space-y-6">
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h1 class="font-serif-bn text-2xl font-bold text-slate-900">ডাটাবেস মাইগ্রেশন ম্যানেজার (Database Migrations)</h1>
                <p class="text-sm text-slate-500 mt-1">ওয়েবসাইটের সমস্ত প্রয়োজনীয় টেবিল ও স্কিমা স্বয়ংক্রিয়ভাবে তৈরি ও চেক করুন।</p>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <button type="submit" name="run_migration" value="1" class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:brightness-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Run / Sync All Tables
                </button>
            </form>
        </div>

        <?php if ($message): ?>
            <div class="mt-5 rounded-lg bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M5 13l4 4L19 7"/></svg>
                <?php echo e($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mt-5 rounded-lg bg-rose-50 p-4 border border-rose-200 text-rose-800 text-sm font-medium flex items-center gap-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <div class="mt-6">
            <h2 class="text-sm font-bold text-slate-800 mb-3 uppercase tracking-wider">টেবিল স্ট্যাটাস (Database Tables)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($table_status as $tbl => $exists): ?>
                    <div class="flex items-center justify-between p-3.5 rounded-lg border <?php echo $exists ? 'border-emerald-200 bg-emerald-50/50' : 'border-rose-200 bg-rose-50/50'; ?>">
                        <span class="font-mono text-xs font-semibold text-slate-800"><?php echo $tbl; ?></span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold <?php echo $exists ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'; ?>">
                            <?php echo $exists ? 'Active' : 'Missing'; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
