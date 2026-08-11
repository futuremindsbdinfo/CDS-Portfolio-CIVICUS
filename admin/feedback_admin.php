<?php
// admin/feedback_admin.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("DELETE FROM feedback WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['flash_message'] = "Feedback deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete feedback.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: feedback_admin.php");
    exit;
}

// Handle Bulk Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'bulk_delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids) && is_array($ids) && $db) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("DELETE FROM feedback WHERE id IN ($placeholders)");
        if ($stmt->execute($ids)) {
            $_SESSION['flash_message'] = count($ids) . " feedback(s) deleted successfully.";
            $_SESSION['flash_type'] = "success";
        }
    }
    header("Location: feedback_admin.php");
    exit;
}

// Fetch feedback with pagination
$feedbacks = [];
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$limit = 20;
$offset = ($page - 1) * $limit;
$total_count = 0;
$rating_filter = isset($_GET['rating']) && is_numeric($_GET['rating']) ? (int)$_GET['rating'] : 0;

if ($db) {
    try {
        if ($rating_filter > 0) {
            $count_stmt = $db->prepare("SELECT COUNT(*) FROM feedback WHERE rating = ?");
            $count_stmt->execute([$rating_filter]);
        } else {
            $count_stmt = $db->query("SELECT COUNT(*) FROM feedback");
        }
        $total_count = (int)$count_stmt->fetchColumn();
        $total_pages = ceil($total_count / $limit);

        if ($rating_filter > 0) {
            $stmt = $db->prepare("SELECT * FROM feedback WHERE rating = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $stmt->execute([$rating_filter, $limit, $offset]);
        } else {
            $stmt = $db->prepare("SELECT * FROM feedback ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $stmt->execute([$limit, $offset]);
        }
        $feedbacks = $stmt->fetchAll();

        // Average rating
        $avg_stmt = $db->query("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM feedback");
        $stats = $avg_stmt->fetch();
    } catch (PDOException $e) {
        // Table might not exist yet
        $_SESSION['flash_message'] = "Please create the 'feedback' table in the database first.";
        $_SESSION['flash_type'] = "error";
        $feedbacks = [];
        $total_pages = 1;
        $stats = ['avg_rating' => 0, 'total' => 0];
    }
}

$total_pages = isset($total_pages) ? $total_pages : 1;
$stats = isset($stats) ? $stats : ['avg_rating' => 0, 'total' => 0];

$flash_message = $_SESSION['flash_message'] ?? '';
$flash_type = $_SESSION['flash_type'] ?? '';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);
?>

<div class="p-4 lg:p-8">

    <!-- Flash Message -->
    <?php if ($flash_message): ?>
    <div class="mb-6 rounded-lg px-4 py-3 text-sm font-medium <?php echo $flash_type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'; ?>">
        <?php echo htmlspecialchars($flash_message); ?>
    </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Feedback</h1>
            <p class="text-sm text-slate-500 mt-1">User feedback from the homepage.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm text-center">
            <div class="text-3xl font-bold text-primary"><?php echo number_format($stats['total']); ?></div>
            <div class="text-xs text-slate-500 mt-1">Total Feedbacks</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm text-center">
            <div class="text-3xl font-bold text-amber-500"><?php echo number_format((float)$stats['avg_rating'], 1); ?></div>
            <div class="text-xs text-slate-500 mt-1">Average Rating ⭐</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm text-center col-span-2 sm:col-span-1">
            <div class="flex justify-center gap-1 text-amber-400 text-2xl">
                <?php 
                $avg = round((float)$stats['avg_rating']);
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $avg ? '⭐' : '☆';
                }
                ?>
            </div>
            <div class="text-xs text-slate-500 mt-1">Avg Stars</div>
        </div>
    </div>

    <!-- Filter by Rating -->
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="feedback_admin.php" class="rounded-full px-4 py-1.5 text-xs font-semibold border transition <?php echo $rating_filter === 0 ? 'bg-primary text-white border-primary' : 'bg-white text-slate-700 border-slate-300 hover:border-primary'; ?>">All</a>
        <?php for ($r = 5; $r >= 1; $r--): ?>
        <a href="feedback_admin.php?rating=<?php echo $r; ?>" class="rounded-full px-4 py-1.5 text-xs font-semibold border transition <?php echo $rating_filter === $r ? 'bg-primary text-white border-primary' : 'bg-white text-slate-700 border-slate-300 hover:border-primary'; ?>"><?php echo str_repeat('⭐', $r); ?> (<?php echo $r; ?>)</a>
        <?php endfor; ?>
    </div>

    <!-- Feedback Table -->
    <form id="bulk-delete-form" action="feedback_admin.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="action" value="bulk_delete">

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                <p class="text-sm text-slate-500">Total: <strong><?php echo $total_count; ?></strong> feedback(s)</p>
                <button type="button" onclick="bulkDeleteFb()" id="bulk-delete-btn-fb" class="hidden inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:brightness-110">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round"/></svg>
                    Bulk Delete
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-xs text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left w-8"><input type="checkbox" onchange="toggleAllFb(this)" class="rounded border-slate-300 text-primary focus:ring-primary"></th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Rating</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Message</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($feedbacks)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400">No feedback found.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($feedbacks as $fb): ?>
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3"><input type="checkbox" name="ids[]" value="<?php echo $fb['id']; ?>" class="fb-checkbox rounded border-slate-300 text-primary focus:ring-primary" onchange="checkBulkFb()"></td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-800 text-sm"><?php echo htmlspecialchars($fb['name']); ?></div>
                                <?php if ($fb['email']): ?>
                                <div class="text-xs text-slate-400"><?php echo htmlspecialchars($fb['email']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-amber-400 text-base"><?php echo str_repeat('⭐', (int)$fb['rating']); ?></span>
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <p class="text-sm text-slate-600 line-clamp-2"><?php echo htmlspecialchars($fb['message']); ?></p>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap"><?php echo date('d M Y, h:i A', strtotime($fb['created_at'])); ?></td>
                            <td class="px-4 py-3">
                                <form method="POST" action="feedback_admin.php" onsubmit="return confirm('Delete this feedback?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $fb['id']; ?>">
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-100 transition">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3">
                <p class="text-xs text-slate-500">Page <?php echo $page; ?> of <?php echo $total_pages; ?></p>
                <div class="flex gap-2">
                    <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&rating=<?php echo $rating_filter; ?>" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">← Prev</a>
                    <?php endif; ?>
                    <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&rating=<?php echo $rating_filter; ?>" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">Next →</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<script>
function toggleAllFb(source) {
    document.querySelectorAll('.fb-checkbox').forEach(cb => { cb.checked = source.checked; });
    checkBulkFb();
}
function checkBulkFb() {
    const anyChecked = document.querySelectorAll('.fb-checkbox:checked').length > 0;
    const btn = document.getElementById('bulk-delete-btn-fb');
    btn ? (anyChecked ? btn.classList.remove('hidden') : btn.classList.add('hidden')) : null;
}
function bulkDeleteFb() {
    if (confirm('Delete selected feedbacks?')) {
        document.getElementById('bulk-delete-form').submit();
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
