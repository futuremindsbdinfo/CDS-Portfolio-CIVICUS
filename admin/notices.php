<?php
// admin/notices.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/pdf_upload_handler.php';

$db = Database::getConnection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    if ($db) {
        // Fetch file path before deleting
        $stmt_file = $db->prepare("SELECT file_path FROM notices WHERE id = ?");
        $stmt_file->execute([$id]);
        $notice = $stmt_file->fetch();

        $stmt = $db->prepare("DELETE FROM notices WHERE id = ?");
        if ($stmt->execute([$id])) {
            // Delete file if exists
            if ($notice && !empty($notice['file_path'])) {
                $absolute_path = __DIR__ . '/../' . ltrim($notice['file_path'], '/');
                if (file_exists($absolute_path)) {
                    @unlink($absolute_path);
                }
            }
            $_SESSION['flash_message'] = "Notice deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete notice.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: notices.php");
    exit;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $title_bn = clean_input($_POST['title_bn']);
    $title_en = clean_input($_POST['title_en'] ?? '');
    $content_bn = clean_input($_POST['content_bn']);
    $content_en = clean_input($_POST['content_en'] ?? '');
    
    $file_path = null;
    if (isset($_FILES['notice_pdf']) && $_FILES['notice_pdf']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handle_pdf_upload($_FILES['notice_pdf']);
        if ($upload_result['success']) {
            $file_path = $upload_result['file_path'];
        } else {
            $_SESSION['flash_message'] = $upload_result['message'];
            $_SESSION['flash_type'] = "error";
            header("Location: notices.php");
            exit;
        }
    }

    if ($db && !empty($title_bn) && !empty($content_bn)) {
        $stmt = $db->prepare("INSERT INTO notices (title_bn, title_en, content_bn, content_en, file_path) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title_bn, $title_en, $content_bn, $content_en, $file_path])) {
            $_SESSION['flash_message'] = "Notice added successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to add notice.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: notices.php");
    exit;
}

// Fetch all notices
$notices = [];
if ($db) {
    $notices = $db->query("SELECT * FROM notices ORDER BY published_at DESC")->fetchAll();
}
?>

<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Notices Management</h1>
            <p class="mt-1 text-sm text-slate-500">সকল নোটিশ ও বিজ্ঞপ্তি পরিচালনা করুন</p>
        </div>
        <button onclick="document.getElementById('add-notice-form').classList.toggle('hidden')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg>
            নতুন নোটিশ
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-notice-form" class="mb-8 hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 font-serif-bn text-lg font-bold text-slate-900">Add New Notice</div>
        <form action="notices.php" method="POST" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Title (Bengali) *</span>
                    <input type="text" name="title_bn" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Title (English)</span>
                    <input type="text" name="title_en" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Content (Bengali) *</span>
                    <textarea name="content_bn" required rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Content (English)</span>
                    <textarea name="content_en" rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                </label>
            </div>

            <label class="block">
                <span class="mb-1.5 block text-xs font-semibold text-slate-600">PDF সংযুক্ত করুন (ঐচ্ছিক)</span>
                <input type="file" name="notice_pdf" accept="application/pdf" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                <p class="text-xs text-slate-500 mt-1">Maximum 5MB, PDF format only.</p>
            </label>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('add-notice-form').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Publish Notice</button>
            </div>
        </form>
    </div>

    <!-- Notices List -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Published Date</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Attachment</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(empty($notices)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center">
                            <div class="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <div class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7"><rect x="3" y="4" width="18" height="16" rx="2" /><path d="M3 10h18M8 4v16" /></svg>
                                </div>
                                <div class="font-serif-bn text-sm font-semibold text-slate-700">কোনো নোটিশ পাওয়া যায়নি</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($notices as $notice): ?>
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 font-medium text-slate-900"><?php echo e($notice['title_bn']); ?></td>
                            <td class="px-4 py-3 text-slate-600"><?php echo date('d M Y, h:i A', strtotime($notice['published_at'])); ?></td>
                            <td class="px-4 py-3">
                                <?php if(!empty($notice['file_path'])): ?>
                                    <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-700">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        PDF
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 flex gap-2">
                                <form action="notices.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this notice?');" class="inline-block">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $notice['id']; ?>">
                                    <button type="submit" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
