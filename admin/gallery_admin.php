<?php
// admin/gallery_admin.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/upload_handler.php';

$db = Database::getConnection();

// Fetch projects for dropdown
$projects = [];
if ($db) {
    $projects = $db->query("SELECT id, title_bn FROM projects ORDER BY created_at DESC")->fetchAll();
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
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

// Handle Add (Bulk Upload)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $project_id = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $caption_bn = clean_input($_POST['caption_bn']);
    $caption_en = clean_input($_POST['caption_en'] ?? '');
    $event_date = !empty($_POST['event_date']) ? clean_input($_POST['event_date']) : null;
    
    $success_count = 0;
    $error_messages = [];

    if (isset($_FILES['image_path']) && is_array($_FILES['image_path']['name'])) {
        $file_count = count($_FILES['image_path']['name']);
        
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['image_path']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue; // Skip empty slots
            }
            
            // Reconstruct single file array for the handler
            $single_file = [
                'name' => $_FILES['image_path']['name'][$i],
                'type' => $_FILES['image_path']['type'][$i],
                'tmp_name' => $_FILES['image_path']['tmp_name'][$i],
                'error' => $_FILES['image_path']['error'][$i],
                'size' => $_FILES['image_path']['size'][$i]
            ];
            
            $upload_result = handle_image_upload($single_file, 'gallery');
            if (!$upload_result['success']) {
                $error_messages[] = $single_file['name'] . ': ' . $upload_result['error'];
                continue;
            }
            
            $image_path = $upload_result['filename'];
            
            if ($db && !empty($image_path)) {
                $stmt = $db->prepare("INSERT INTO gallery (project_id, image_path, caption_bn, caption_en, event_date) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$project_id, $image_path, $caption_bn, $caption_en, $event_date])) {
                    $success_count++;
                }
            }
        }
    }

    if ($success_count > 0) {
        $_SESSION['flash_message'] = "$success_count photo(s) added successfully." . (!empty($error_messages) ? " Some failed: " . implode(', ', $error_messages) : "");
        $_SESSION['flash_type'] = empty($error_messages) ? "success" : "warning";
    } else {
        $_SESSION['flash_message'] = "Failed to add photos. " . implode(', ', $error_messages);
        $_SESSION['flash_type'] = "error";
    }
    
    header("Location: gallery_admin.php");
    exit;
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
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

    if ($db && !empty($caption_bn) && $id > 0) {
        if ($image_path) {
            // Get old image to delete
            $stmt = $db->prepare("SELECT image_path FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            $old_photo = $stmt->fetch();
            if ($old_photo && $old_photo['image_path'] && file_exists(__DIR__ . '/../uploads/gallery/' . $old_photo['image_path'])) {
                @unlink(__DIR__ . '/../uploads/gallery/' . $old_photo['image_path']);
            }

            $stmt = $db->prepare("UPDATE gallery SET project_id = ?, image_path = ?, caption_bn = ?, caption_en = ?, event_date = ? WHERE id = ?");
            $success = $stmt->execute([$project_id, $image_path, $caption_bn, $caption_en, $event_date, $id]);
        } else {
            $stmt = $db->prepare("UPDATE gallery SET project_id = ?, caption_bn = ?, caption_en = ?, event_date = ? WHERE id = ?");
            $success = $stmt->execute([$project_id, $caption_bn, $caption_en, $event_date, $id]);
        }

        if ($success) {
            $_SESSION['flash_message'] = "Photo updated successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to update photo.";
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

// Fetch item to edit
$edit_photo = null;
if (isset($_GET['edit_id']) && $db) {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_id']]);
    $edit_photo = $stmt->fetch();
}
?>

<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Gallery Management</h1>
            <p class="mt-1 text-sm text-slate-500">গ্যালারির সকল ছবি পরিচালনা করুন</p>
        </div>
        <button onclick="document.getElementById('add-photo-form').classList.toggle('hidden')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg>
            নতুন ছবি
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-photo-form" class="mb-8 hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 font-serif-bn text-lg font-bold text-slate-900">Upload New Photo</div>
        <form action="gallery_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Caption (Bengali) *</span>
                    <input type="text" name="caption_bn" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Caption (English)</span>
                    <input type="text" name="caption_en" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Link to Project (Optional)</span>
                    <select name="project_id" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <option value="">None</option>
                        <?php foreach($projects as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo e($p['title_bn']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Event Date</span>
                    <input type="date" name="event_date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block md:col-span-2">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Photo Image(s) (JPG, PNG, WEBP - Max 5MB per file) *</span>
                    <input type="file" name="image_path[]" accept="image/jpeg,image/png,image/webp" multiple required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <span class="text-xs text-slate-500 mt-1 block">You can select multiple photos at once (Bulk Upload). They will all share the same caption and project tag.</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('add-photo-form').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Save Photo</button>
            </div>
        </form>
    </div>

    <?php if ($edit_photo): ?>
    <!-- Edit Form -->
    <div id="edit-photo-form" class="mb-8 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 font-serif-bn text-lg font-bold text-slate-900">Edit Photo</div>
        <form action="gallery_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo $edit_photo['id']; ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Caption (Bengali) *</span>
                    <input type="text" name="caption_bn" value="<?php echo e($edit_photo['caption_bn']); ?>" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Caption (English)</span>
                    <input type="text" name="caption_en" value="<?php echo e($edit_photo['caption_en']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Link to Project (Optional)</span>
                    <select name="project_id" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <option value="">None</option>
                        <?php foreach($projects as $p): ?>
                            <option value="<?php echo $p['id']; ?>" <?php echo $edit_photo['project_id'] == $p['id'] ? 'selected' : ''; ?>><?php echo e($p['title_bn']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Event Date</span>
                    <input type="date" name="event_date" value="<?php echo e($edit_photo['event_date']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block md:col-span-2">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Photo Image (Optional - Max 5MB)</span>
                    <div class="mb-2 h-16 w-24 rounded-md bg-slate-200 bg-cover bg-center overflow-hidden">
                        <img src="../uploads/gallery/<?php echo e($edit_photo['image_path']); ?>" alt="current" class="h-full w-full object-cover">
                    </div>
                    <input type="file" name="image_path" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <span class="text-xs text-slate-500 mt-1 block">Leave empty to keep the current image.</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="gallery_admin.php" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Update Photo</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Gallery List -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-16">Preview</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Caption</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Linked Project</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Event Date</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(empty($photos)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center">
                            <div class="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <div class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><path d="M21 15l-5-5L5 21" /></svg>
                                </div>
                                <div class="font-serif-bn text-sm font-semibold text-slate-700">কোনো ছবি পাওয়া যায়নি</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($photos as $photo): ?>
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3">
                                <div class="h-10 w-16 rounded-md bg-slate-200 bg-cover bg-center overflow-hidden">
                                    <img src="../uploads/gallery/<?php echo e($photo['image_path']); ?>" alt="preview" class="h-full w-full object-cover">
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-slate-900"><?php echo e($photo['caption_bn']); ?></td>
                            <td class="px-4 py-3">
                                <?php if($photo['project_title']): ?>
                                    <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200"><?php echo e($photo['project_title']); ?></span>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-slate-600"><?php echo $photo['event_date'] ? date('d M Y', strtotime($photo['event_date'])) : 'N/A'; ?></td>
                            <td class="px-4 py-3 flex gap-2 h-full items-center justify-center">
                                <a href="gallery_admin.php?edit_id=<?php echo $photo['id']; ?>" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-primary transition mt-1" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                                <form action="gallery_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?');" class="inline-block mt-1">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $photo['id']; ?>">
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
