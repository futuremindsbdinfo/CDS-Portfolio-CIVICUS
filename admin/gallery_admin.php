<?php
// admin/gallery_admin.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/upload_handler.php';

$db = get_db_connection();

// Fetch projects for dropdown
$projects = [];
if ($db) {
    $projects = $db->query("SELECT id, title_bn FROM projects ORDER BY created_at DESC")->fetchAll();
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    verify_csrf_token();
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("SELECT image_path FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $photo = $stmt->fetch();
        $image_to_delete = $photo ? $photo['image_path'] : null;

        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        if ($stmt->execute([$id])) {
            if ($image_to_delete && file_exists(__DIR__ . '/../uploads/gallery/' . $image_to_delete)) {
                @unlink(__DIR__ . '/../uploads/gallery/' . $image_to_delete);
            }
            $_SESSION['flash_message'] = "Photo deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete photo.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: gallery_admin.php");
    exit;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    verify_csrf_token();
    $project_id = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $caption_bn = clean_input($_POST['caption_bn']);
    $caption_en = clean_input($_POST['caption_en'] ?? '');
    $event_date = !empty($_POST['event_date']) ? clean_input($_POST['event_date']) : null;
    
    $image_path = null;
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handle_image_upload($_FILES['image_path'], 'gallery');
        if (!$upload_result['success']) {
            $_SESSION['flash_message'] = $upload_result['error'];
            $_SESSION['flash_type'] = "error";
            header("Location: gallery_admin.php");
            exit;
        }
        $image_path = $upload_result['filename'];
    }

    if ($db && !empty($caption_bn) && !empty($image_path)) {
        $stmt = $db->prepare("INSERT INTO gallery (project_id, image_path, caption_bn, caption_en, event_date) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$project_id, $image_path, $caption_bn, $caption_en, $event_date])) {
            $_SESSION['flash_message'] = "Photo added successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to add photo.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: gallery_admin.php");
    exit;
}

// Fetch all gallery items
$photos = [];
if ($db) {
    $photos = $db->query("
        SELECT g.*, p.title_bn as project_title 
        FROM gallery g 
        LEFT JOIN projects p ON g.project_id = p.id 
        ORDER BY g.created_at DESC
    ")->fetchAll();
}
?>

<div class="bg-white p-6 rounded-lg shadow-sm mb-8">
    <h2 class="text-xl font-bold mb-4">Upload New Photo</h2>
    <form action="gallery_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="action" value="add">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Caption (Bengali) *</label>
                <input type="text" name="caption_bn" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-yellow-400">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Caption (English)</label>
                <input type="text" name="caption_en" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-yellow-400">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Link to Project (Optional)</label>
                <select name="project_id" class="w-full px-4 py-2 border rounded bg-white focus:ring-2 focus:ring-yellow-400">
                    <option value="">None</option>
                    <?php foreach($projects as $p): ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo e($p['title_bn']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Event Date</label>
                <input type="date" name="event_date" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-yellow-400">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">Photo Image (JPG, PNG, WEBP - Max 5MB) *</label>
                <input type="file" name="image_path" accept="image/jpeg,image/png,image/webp" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-yellow-400">
            </div>
        </div>

        <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded font-bold hover:bg-yellow-600 transition">Save Photo</button>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow-sm">
    <h2 class="text-xl font-bold mb-4">Gallery Photos</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-3 font-semibold">ID</th>
                    <th class="p-3 font-semibold">Caption (BN)</th>
                    <th class="p-3 font-semibold">Linked Project</th>
                    <th class="p-3 font-semibold">Event Date</th>
                    <th class="p-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($photos as $photo): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3"><?php echo $photo['id']; ?></td>
                    <td class="p-3"><?php echo e($photo['caption_bn']); ?></td>
                    <td class="p-3 text-sm text-gray-600"><?php echo $photo['project_title'] ? e($photo['project_title']) : '<em>None</em>'; ?></td>
                    <td class="p-3"><?php echo $photo['event_date'] ? date('d M Y', strtotime($photo['event_date'])) : 'N/A'; ?></td>
                    <td class="p-3 flex gap-2">
                        <form action="gallery_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $photo['id']; ?>">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($photos)): ?>
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">No photos found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
