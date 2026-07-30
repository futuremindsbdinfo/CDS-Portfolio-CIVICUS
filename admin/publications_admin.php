<?php
// admin/publications_admin.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/upload_handler.php';

$db = Database::getConnection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("SELECT cover_image, file_path FROM publications WHERE id = ?");
        $stmt->execute([$id]);
        $publication = $stmt->fetch();
        $image_to_delete = $publication ? $publication['cover_image'] : null;
        $file_to_delete = $publication ? $publication['file_path'] : null;

        $stmt = $db->prepare("DELETE FROM publications WHERE id = ?");
        if ($stmt->execute([$id])) {
            if ($image_to_delete && file_exists(__DIR__ . '/../uploads/publications/' . $image_to_delete)) {
                @unlink(__DIR__ . '/../uploads/publications/' . $image_to_delete);
            }
            if ($file_to_delete && file_exists(__DIR__ . '/../uploads/publications/' . $file_to_delete)) {
                @unlink(__DIR__ . '/../uploads/publications/' . $file_to_delete);
            }
            $_SESSION['flash_message'] = "Publication deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete publication.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: publications_admin.php");
    exit;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $title = clean_input($_POST['title']);
    $description = clean_input($_POST['description']);
    $type = clean_input($_POST['type']);
    
    $cover_image = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handle_image_upload($_FILES['cover_image'], 'publications');
        if (!$upload_result['success']) {
            $_SESSION['flash_message'] = $upload_result['error'];
            $_SESSION['flash_type'] = "error";
            header("Location: publications_admin.php");
            exit;
        }
        $cover_image = $upload_result['filename'];
    }

    $file_path = null;
    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handle_file_upload($_FILES['file_path'], 'publications', ['pdf']);
        if (!$upload_result['success']) {
            $_SESSION['flash_message'] = $upload_result['error'];
            $_SESSION['flash_type'] = "error";
            header("Location: publications_admin.php");
            exit;
        }
        $file_path = $upload_result['filename'];
    } else {
        $_SESSION['flash_message'] = "PDF file is required.";
        $_SESSION['flash_type'] = "error";
        header("Location: publications_admin.php");
        exit;
    }

    if ($db && !empty($title) && !empty($description) && !empty($file_path)) {
        $stmt = $db->prepare("INSERT INTO publications (title, description, type, cover_image, file_path) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $type, $cover_image, $file_path])) {
            $_SESSION['flash_message'] = "Publication added successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to add publication.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: publications_admin.php");
    exit;
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    $title = clean_input($_POST['title']);
    $description = clean_input($_POST['description']);
    $type = clean_input($_POST['type']);
    
    if ($db && !empty($title) && !empty($description)) {
        $update_query = "UPDATE publications SET title = ?, description = ?, type = ?";
        $params = [$title, $description, $type];

        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = handle_image_upload($_FILES['cover_image'], 'publications');
            if (!$upload_result['success']) {
                $_SESSION['flash_message'] = $upload_result['error'];
                $_SESSION['flash_type'] = "error";
                header("Location: publications_admin.php");
                exit;
            }
            $update_query .= ", cover_image = ?";
            $params[] = $upload_result['filename'];
            
            // Delete old image
            $stmt = $db->prepare("SELECT cover_image FROM publications WHERE id = ?");
            $stmt->execute([$id]);
            $old_pub = $stmt->fetch();
            if ($old_pub && $old_pub['cover_image'] && file_exists(__DIR__ . '/../uploads/publications/' . $old_pub['cover_image'])) {
                @unlink(__DIR__ . '/../uploads/publications/' . $old_pub['cover_image']);
            }
        }

        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = handle_file_upload($_FILES['file_path'], 'publications', ['pdf']);
            if (!$upload_result['success']) {
                $_SESSION['flash_message'] = $upload_result['error'];
                $_SESSION['flash_type'] = "error";
                header("Location: publications_admin.php");
                exit;
            }
            $update_query .= ", file_path = ?";
            $params[] = $upload_result['filename'];
            
            // Delete old file
            $stmt = $db->prepare("SELECT file_path FROM publications WHERE id = ?");
            $stmt->execute([$id]);
            $old_pub = $stmt->fetch();
            if ($old_pub && $old_pub['file_path'] && file_exists(__DIR__ . '/../uploads/publications/' . $old_pub['file_path'])) {
                @unlink(__DIR__ . '/../uploads/publications/' . $old_pub['file_path']);
            }
        }
        
        $update_query .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $db->prepare($update_query);
        if ($stmt->execute($params)) {
            $_SESSION['flash_message'] = "Publication updated successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to update publication.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: publications_admin.php");
    exit;
}

// Fetch all publications
$publications = [];
if ($db) {
    $publications = $db->query("SELECT * FROM publications ORDER BY created_at DESC")->fetchAll();
}
?>

<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Publications Management</h1>
            <p class="mt-1 text-sm text-slate-500">সকল প্রকাশনা ও ম্যাগাজিন পরিচালনা করুন</p>
        </div>
        <button onclick="document.getElementById('add-publication-form').classList.toggle('hidden')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg>
            নতুন প্রকাশনা
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-publication-form" class="mb-8 hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 font-serif-bn text-lg font-bold text-slate-900">Add New Publication</div>
        <form action="publications_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Title *</span>
                    <input type="text" name="title" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Type *</span>
                    <select name="type" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <option value="ম্যাগাজিন">ম্যাগাজিন</option>
                        <option value="প্রতিবেদন">প্রতিবেদন</option>
                        <option value="গবেষণা">গবেষণা</option>
                    </select>
                </label>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Description *</span>
                    <textarea name="description" required rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Cover Image (JPG, PNG, WEBP)</span>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Publication PDF *</span>
                    <input type="file" name="file_path" accept="application/pdf" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('add-publication-form').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Save Publication</button>
            </div>
        </form>
    </div>

    <!-- Publications List -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Cover & File</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(empty($publications)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center">
                            <div class="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <div class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7"><rect x="3" y="4" width="18" height="16" rx="2" /><path d="M3 10h18M8 4v16" /></svg>
                                </div>
                                <div class="font-serif-bn text-sm font-semibold text-slate-700">কোনো প্রকাশনা পাওয়া যায়নি</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($publications as $pub): ?>
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 font-medium text-slate-900"><?php echo e($pub['title']); ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-500/20"><?php echo e($pub['type']); ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <?php if($pub['cover_image']): ?>
                                        <img src="../uploads/publications/<?php echo e($pub['cover_image']); ?>" alt="Cover" class="h-10 w-10 rounded object-cover shadow-sm">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded bg-slate-100 flex items-center justify-center text-xs text-slate-400">No Img</div>
                                    <?php endif; ?>
                                    <?php if($pub['file_path']): ?>
                                        <a href="../uploads/publications/<?php echo e($pub['file_path']); ?>" target="_blank" class="text-xs font-medium text-blue-600 hover:underline">View PDF</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 flex gap-2">
                                <button onclick="document.getElementById('edit-publication-form-<?php echo $pub['id']; ?>').classList.toggle('hidden')" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-blue-600 hover:bg-blue-50 transition" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <form action="publications_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this publication?');" class="inline-block">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $pub['id']; ?>">
                                    <button type="submit" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr id="edit-publication-form-<?php echo $pub['id']; ?>" class="hidden bg-slate-50/80 border-b border-slate-200">
                            <td colspan="4" class="p-5">
                                <div class="mb-4 font-serif-bn text-base font-bold text-slate-900">Edit Publication</div>
                                <form action="publications_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $pub['id']; ?>">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Title *</span>
                                            <input type="text" name="title" required value="<?php echo e($pub['title']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Type *</span>
                                            <select name="type" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <option value="ম্যাগাজিন" <?php echo $pub['type'] === 'ম্যাগাজিন' ? 'selected' : ''; ?>>ম্যাগাজিন</option>
                                                <option value="প্রতিবেদন" <?php echo $pub['type'] === 'প্রতিবেদন' ? 'selected' : ''; ?>>প্রতিবেদন</option>
                                                <option value="গবেষণা" <?php echo $pub['type'] === 'গবেষণা' ? 'selected' : ''; ?>>গবেষণা</option>
                                            </select>
                                        </label>
                                    </div>
                        
                                    <div class="grid grid-cols-1 gap-4">
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Description *</span>
                                            <textarea name="description" required rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"><?php echo e($pub['description']); ?></textarea>
                                        </label>
                                    </div>
                        
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Cover Image (Leave empty to keep existing)</span>
                                            <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Publication PDF (Leave empty to keep existing)</span>
                                            <input type="file" name="file_path" accept="application/pdf" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                    </div>
                        
                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" onclick="document.getElementById('edit-publication-form-<?php echo $pub['id']; ?>').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Update Publication</button>
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
