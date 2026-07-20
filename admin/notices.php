<?php
// admin/notices.php
require_once __DIR__ . '/includes/header.php';

$db = get_db_connection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    verify_csrf_token();
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("DELETE FROM notices WHERE id = ?");
        if ($stmt->execute([$id])) {
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
    verify_csrf_token();
    $title_bn = clean_input($_POST['title_bn']);
    $title_en = clean_input($_POST['title_en'] ?? '');
    $content_bn = clean_input($_POST['content_bn']);
    $content_en = clean_input($_POST['content_en'] ?? '');
    $file_path = clean_input($_POST['file_path'] ?? ''); // In a real app, handle file upload

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

<div class="bg-white p-6 rounded-lg shadow-sm mb-8">
    <h2 class="text-xl font-bold mb-4">Add New Notice</h2>
    <form action="notices.php" method="POST" class="space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="action" value="add">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Title (Bengali) *</label>
                <input type="text" name="title_bn" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-blue">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Title (English)</label>
                <input type="text" name="title_en" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-blue">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Content (Bengali) *</label>
                <textarea name="content_bn" required rows="4" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-blue"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Content (English)</label>
                <textarea name="content_en" rows="4" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-blue"></textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">File URL / Link (Optional)</label>
            <input type="text" name="file_path" placeholder="e.g. https://example.com/file.pdf" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-blue">
        </div>

        <button type="submit" class="bg-cds-blue text-white px-6 py-2 rounded font-bold hover:bg-blue-800 transition">Publish Notice</button>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow-sm">
    <h2 class="text-xl font-bold mb-4">Existing Notices</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-3 font-semibold">ID</th>
                    <th class="p-3 font-semibold">Title (BN)</th>
                    <th class="p-3 font-semibold">Published At</th>
                    <th class="p-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($notices as $notice): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3"><?php echo $notice['id']; ?></td>
                    <td class="p-3"><?php echo e($notice['title_bn']); ?></td>
                    <td class="p-3"><?php echo date('d M Y, h:i A', strtotime($notice['published_at'])); ?></td>
                    <td class="p-3 flex gap-2">
                        <form action="notices.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this notice?');">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $notice['id']; ?>">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($notices)): ?>
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">No notices found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
