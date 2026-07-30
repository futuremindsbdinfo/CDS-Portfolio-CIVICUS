<?php
// admin/projects_admin.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/upload_handler.php';

$db = Database::getConnection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
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
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $title_bn = clean_input($_POST['title_bn']);
    $title_en = clean_input($_POST['title_en'] ?? '');
    $description_bn = clean_input($_POST['description_bn']);
    $description_en = clean_input($_POST['description_en'] ?? '');
    $status = clean_input($_POST['status']);
    $start_date = !empty($_POST['start_date']) ? clean_input($_POST['start_date']) : null;
    $end_date = !empty($_POST['end_date']) ? clean_input($_POST['end_date']) : null;
    $video_embed = !empty($_POST['video_embed']) ? $_POST['video_embed'] : null; // don't fully clean since it's iframe HTML

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
        $stmt = $db->prepare("INSERT INTO projects (title_bn, title_en, description_bn, description_en, status, cover_image, start_date, end_date, video_embed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title_bn, $title_en, $description_bn, $description_en, $status, $cover_image, $start_date, $end_date, $video_embed])) {
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

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    $title_bn = clean_input($_POST['title_bn']);
    $title_en = clean_input($_POST['title_en'] ?? '');
    $description_bn = clean_input($_POST['description_bn']);
    $description_en = clean_input($_POST['description_en'] ?? '');
    $status = clean_input($_POST['status']);
    $start_date = !empty($_POST['start_date']) ? clean_input($_POST['start_date']) : null;
    $end_date = !empty($_POST['end_date']) ? clean_input($_POST['end_date']) : null;
    $video_embed = !empty($_POST['video_embed']) ? $_POST['video_embed'] : null; // don't fully clean since it's iframe HTML

    if ($db && !empty($title_bn) && !empty($description_bn)) {
        $cover_image_query = "";
        $params = [$title_bn, $title_en, $description_bn, $description_en, $status, $start_date, $end_date, $video_embed];

        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = handle_image_upload($_FILES['cover_image'], 'projects');
            if (!$upload_result['success']) {
                $_SESSION['flash_message'] = $upload_result['error'];
                $_SESSION['flash_type'] = "error";
                header("Location: projects_admin.php");
                exit;
            }
            $cover_image_query = ", cover_image = ?";
            $params[] = $upload_result['filename'];
            
            // Delete old image
            $stmt = $db->prepare("SELECT cover_image FROM projects WHERE id = ?");
            $stmt->execute([$id]);
            $old_project = $stmt->fetch();
            if ($old_project && $old_project['cover_image'] && file_exists(__DIR__ . '/../uploads/projects/' . $old_project['cover_image'])) {
                @unlink(__DIR__ . '/../uploads/projects/' . $old_project['cover_image']);
            }
        }
        
        $params[] = $id;

        $stmt = $db->prepare("UPDATE projects SET title_bn = ?, title_en = ?, description_bn = ?, description_en = ?, status = ?, start_date = ?, end_date = ?, video_embed = ? $cover_image_query WHERE id = ?");
        if ($stmt->execute($params)) {
            $_SESSION['flash_message'] = "Project updated successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to update project.";
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

<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Projects Management</h1>
            <p class="mt-1 text-sm text-slate-500">সংগঠনের সকল প্রজেক্ট পরিচালনা করুন</p>
        </div>
        <button onclick="document.getElementById('add-project-form').classList.toggle('hidden')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg>
            নতুন প্রজেক্ট
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-project-form" class="mb-8 hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 font-serif-bn text-lg font-bold text-slate-900">Add New Project</div>
        <form action="projects_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
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
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Description (Bengali) *</span>
                    <textarea name="description_bn" required rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Description (English)</span>
                    <textarea name="description_en" rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Status *</span>
                    <select name="status" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Start Date</span>
                    <input type="date" name="start_date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">End Date</span>
                    <input type="date" name="end_date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block md:col-span-3">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Cover Image (JPG, PNG, WEBP - Max 5MB)</span>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block md:col-span-3">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Video Embed Code (Optional)</span>
                    <textarea name="video_embed" rows="3" placeholder="e.g. <iframe src='https://www.youtube.com/embed/...'></iframe>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 font-mono text-xs"></textarea>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('add-project-form').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Save Project</button>
            </div>
        </form>
    </div>

    <!-- Projects List -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Start Date</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(empty($projects)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center">
                            <div class="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <div class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7"><rect x="3" y="4" width="18" height="16" rx="2" /><path d="M3 10h18M8 4v16" /></svg>
                                </div>
                                <div class="font-serif-bn text-sm font-semibold text-slate-700">কোনো প্রজেক্ট পাওয়া যায়নি</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($projects as $project): ?>
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 font-medium text-slate-900"><?php echo e($project['title_bn']); ?></td>
                            <td class="px-4 py-3">
                                <?php if($project['status'] === 'ongoing'): ?>
                                    <span class="inline-flex items-center gap-1 rounded bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-500/20">Ongoing</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-500/20">Completed</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-slate-600"><?php echo $project['start_date'] ? date('d M Y', strtotime($project['start_date'])) : 'N/A'; ?></td>
                            <td class="px-4 py-3 flex gap-2">
                                <button onclick="document.getElementById('edit-project-form-<?php echo $project['id']; ?>').classList.toggle('hidden')" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-blue-600 hover:bg-blue-50 transition" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <form action="projects_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');" class="inline-block">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                    <button type="submit" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr id="edit-project-form-<?php echo $project['id']; ?>" class="hidden bg-slate-50/80 border-b border-slate-200">
                            <td colspan="4" class="p-5">
                                <div class="mb-4 font-serif-bn text-base font-bold text-slate-900">Edit Project</div>
                                <form action="projects_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Title (Bengali) *</span>
                                            <input type="text" name="title_bn" required value="<?php echo e($project['title_bn']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Title (English)</span>
                                            <input type="text" name="title_en" value="<?php echo e($project['title_en']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                    </div>
                        
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Description (Bengali) *</span>
                                            <textarea name="description_bn" required rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"><?php echo e($project['description_bn']); ?></textarea>
                                        </label>
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Description (English)</span>
                                            <textarea name="description_en" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"><?php echo e($project['description_en']); ?></textarea>
                                        </label>
                                    </div>
                        
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Status *</span>
                                            <select name="status" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="ongoing" <?php echo $project['status'] === 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                                                <option value="completed" <?php echo $project['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            </select>
                                        </label>
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Start Date</span>
                                            <input type="date" name="start_date" value="<?php echo e($project['start_date']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">End Date</span>
                                            <input type="date" name="end_date" value="<?php echo e($project['end_date']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                        <label class="block md:col-span-3">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Cover Image (Leave empty to keep current)</span>
                                            <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                        <label class="block md:col-span-3">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Video Embed Code (Optional)</span>
                                            <textarea name="video_embed" rows="3" placeholder="e.g. <iframe src='https://www.youtube.com/embed/...'></iframe>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 font-mono text-xs"><?php echo e($project['video_embed'] ?? ''); ?></textarea>
                                        </label>
                                    </div>
                        
                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" onclick="document.getElementById('edit-project-form-<?php echo $project['id']; ?>').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Update Project</button>
                                    </div>
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
