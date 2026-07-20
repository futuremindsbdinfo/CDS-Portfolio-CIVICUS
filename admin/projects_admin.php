<?php
// admin/projects_admin.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/upload_handler.php';

$db = get_db_connection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    verify_csrf_token();
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("SELECT cover_image FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        $image_to_delete = $project ? $project['cover_image'] : null;

        $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
        if ($stmt->execute([$id])) {
            if ($image_to_delete && file_exists(__DIR__ . '/../uploads/projects/' . $image_to_delete)) {
                @unlink(__DIR__ . '/../uploads/projects/' . $image_to_delete);
            }
            $_SESSION['flash_message'] = "Project deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete project.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: projects_admin.php");
    exit;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    verify_csrf_token();
    $title_bn = clean_input($_POST['title_bn']);
    $title_en = clean_input($_POST['title_en'] ?? '');
    $description_bn = clean_input($_POST['description_bn']);
    $description_en = clean_input($_POST['description_en'] ?? '');
    $status = clean_input($_POST['status']);
    $start_date = !empty($_POST['start_date']) ? clean_input($_POST['start_date']) : null;
    $end_date = !empty($_POST['end_date']) ? clean_input($_POST['end_date']) : null;
    
    $cover_image = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handle_image_upload($_FILES['cover_image'], 'projects');
        if (!$upload_result['success']) {
            $_SESSION['flash_message'] = $upload_result['error'];
            $_SESSION['flash_type'] = "error";
            header("Location: projects_admin.php");
            exit;
        }
        $cover_image = $upload_result['filename'];
    }

    if ($db && !empty($title_bn) && !empty($description_bn)) {
        $stmt = $db->prepare("INSERT INTO projects (title_bn, title_en, description_bn, description_en, status, cover_image, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title_bn, $title_en, $description_bn, $description_en, $status, $cover_image, $start_date, $end_date])) {
            $_SESSION['flash_message'] = "Project added successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to add project.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: projects_admin.php");
    exit;
}

// Fetch all projects
$projects = [];
if ($db) {
    $projects = $db->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
}
?>

<div class="bg-white p-6 rounded-lg shadow-sm mb-8">
    <h2 class="text-xl font-bold mb-4">Add New Project</h2>
    <form action="projects_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="action" value="add">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Title (Bengali) *</label>
                <input type="text" name="title_bn" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-green">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Title (English)</label>
                <input type="text" name="title_en" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-green">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Description (Bengali) *</label>
                <textarea name="description_bn" required rows="4" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-green"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Description (English)</label>
                <textarea name="description_en" rows="4" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-green"></textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Status *</label>
                <select name="status" required class="w-full px-4 py-2 border rounded bg-white focus:ring-2 focus:ring-cds-green">
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Start Date</label>
                <input type="date" name="start_date" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-green">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">End Date</label>
                <input type="date" name="end_date" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-green">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-semibold mb-1">Cover Image (JPG, PNG, WEBP - Max 5MB)</label>
                <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-cds-green">
            </div>
        </div>

        <button type="submit" class="bg-cds-green text-white px-6 py-2 rounded font-bold hover:bg-green-700 transition">Save Project</button>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow-sm">
    <h2 class="text-xl font-bold mb-4">Existing Projects</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-3 font-semibold">ID</th>
                    <th class="p-3 font-semibold">Title (BN)</th>
                    <th class="p-3 font-semibold">Status</th>
                    <th class="p-3 font-semibold">Start Date</th>
                    <th class="p-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($projects as $project): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3"><?php echo $project['id']; ?></td>
                    <td class="p-3"><?php echo e($project['title_bn']); ?></td>
                    <td class="p-3">
                        <?php if($project['status'] === 'ongoing'): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-bold">Ongoing</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-bold">Completed</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-3"><?php echo $project['start_date'] ? date('d M Y', strtotime($project['start_date'])) : 'N/A'; ?></td>
                    <td class="p-3 flex gap-2">
                        <form action="projects_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($projects)): ?>
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">No projects found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
