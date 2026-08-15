<?php
// admin/subscribers_admin.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$message = '';
$error = '';

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    if ($db) {
        $subscribers = $db->query("SELECT email, status, subscribed_at, ip_address FROM newsletter_subscribers ORDER BY subscribed_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=cds_newsletter_subscribers_' . date('Y-m-d') . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Email', 'Status', 'Subscribed At', 'IP Address']);
        foreach ($subscribers as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}

// Handle Delete / Status change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token mismatch.';
    } else {
        $action = $_POST['action'] ?? '';
        $sub_id = (int)($_POST['id'] ?? 0);

        if ($action === 'delete' && $sub_id > 0) {
            try {
                $stmt = $db->prepare("DELETE FROM newsletter_subscribers WHERE id = ?");
                $stmt->execute([$sub_id]);
                $message = 'সাবস্ক্রাইবার মুছে ফেলা হয়েছে!';
            } catch (PDOException $e) {
                $error = 'মুছে ফেলা যায়নি: ' . $e->getMessage();
            }
        }
    }
}

// Search
$search = clean_input($_GET['search'] ?? '');
$query = "SELECT * FROM newsletter_subscribers";
$params = [];
if (!empty($search)) {
    $query .= " WHERE email LIKE ?";
    $params[] = '%' . $search . '%';
}
$query .= " ORDER BY subscribed_at DESC";

$subscribers = [];
if ($db) {
    try {
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $subscribers = [];
    }
}
?>

<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold font-serif-bn text-slate-800">নিউজলেটার গ্রাহক তালিকা</h1>
            <p class="text-sm text-slate-500">হোমপেজ ও সাইট থেকে সাবস্ক্রাইব করা ইমেইলের তালিকা ও এক্সপোর্ট</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="newsletter_broadcast.php" class="px-4 py-2 bg-primary hover:brightness-110 text-white rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                নিউজলেটার পাঠান (Compose)
            </a>
            <a href="subscribers_admin.php?export=csv" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                CSV এক্সপোর্ট
            </a>
        </div>
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

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Search Bar -->
        <div class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-base font-bold text-slate-800">মোট গ্রাহক: <?php echo count($subscribers); ?> জন</h2>
            
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="ইমেইল দিয়ে খুঁজুন..." class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-1 focus:ring-primary w-60">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">সার্চ</button>
                <?php if ($search): ?>
                    <a href="subscribers_admin.php" class="text-xs text-rose-600 hover:underline">ক্লিয়ার</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-xs font-bold text-slate-500">
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">ইমেইল ঠিকানা</th>
                        <th class="py-3.5 px-4">স্ট্যাটাস</th>
                        <th class="py-3.5 px-4">তারিখ ও সময়</th>
                        <th class="py-3.5 px-4">আইপি</th>
                        <th class="py-3.5 px-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($subscribers)): ?>
                        <?php foreach ($subscribers as $index => $sub): ?>
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4 text-xs font-mono text-slate-400"><?php echo $index + 1; ?></td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800"><?php echo e($sub['email']); ?></td>
                                <td class="py-3.5 px-4">
                                    <?php if ($sub['status'] === 'active'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Active</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Unsubscribed</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-500"><?php echo date('d M, Y h:i A', strtotime($sub['subscribed_at'])); ?></td>
                                <td class="py-3.5 px-4 text-xs font-mono text-slate-400"><?php echo e($sub['ip_address']); ?></td>
                                <td class="py-3.5 px-4 text-right">
                                    <form method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত এই ইমেইলটি মুছে ফেলতে চান?');" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $sub['id']; ?>">
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">কোন গ্রাহক পাওয়া যায়নি।</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
