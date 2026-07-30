<?php
// admin/gov_links_admin.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/upload_handler.php';

$db = Database::getConnection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("SELECT logo_image FROM gov_links WHERE id = ?");
        $stmt->execute([$id]);
        $link_data = $stmt->fetch();
        $image_to_delete = $link_data ? $link_data['logo_image'] : null;

        $stmt = $db->prepare("DELETE FROM gov_links WHERE id = ?");
        if ($stmt->execute([$id])) {
            if ($image_to_delete && file_exists(__DIR__ . '/../uploads/gov_links/' . $image_to_delete)) {
                @unlink(__DIR__ . '/../uploads/gov_links/' . $image_to_delete);
            }
            $_SESSION['flash_message'] = "Link deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete link.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: gov_links_admin.php");
    exit;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $title = clean_input($_POST['title']);
    $url = clean_input($_POST['url']);
    $category = !empty($_POST['category']) ? clean_input($_POST['category']) : null;
    $logo_url = !empty($_POST['logo_url']) ? clean_input($_POST['logo_url']) : null;
    
    $logo_image = null;
    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handle_image_upload($_FILES['logo_image'], 'gov_links');
        if (!$upload_result['success']) {
            $_SESSION['flash_message'] = $upload_result['error'];
            $_SESSION['flash_type'] = "error";
            header("Location: gov_links_admin.php");
            exit;
        }
        $logo_image = $upload_result['filename'];
    }

    if ($db && !empty($title) && !empty($url)) {
        $stmt = $db->prepare("INSERT INTO gov_links (title, url, logo_image, logo_url, category) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $url, $logo_image, $logo_url, $category])) {
            $_SESSION['flash_message'] = "Link added successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to add link.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: gov_links_admin.php");
    exit;
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    $title = clean_input($_POST['title']);
    $url = clean_input($_POST['url']);
    $category = !empty($_POST['category']) ? clean_input($_POST['category']) : null;
    $logo_url = !empty($_POST['logo_url']) ? clean_input($_POST['logo_url']) : null;
    
    $logo_image = null;
    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handle_image_upload($_FILES['logo_image'], 'gov_links');
        if (!$upload_result['success']) {
            $_SESSION['flash_message'] = $upload_result['error'];
            $_SESSION['flash_type'] = "error";
            header("Location: gov_links_admin.php");
            exit;
        }
        $logo_image = $upload_result['filename'];
    }

    if ($db && !empty($title) && !empty($url) && $id > 0) {
        if ($logo_image) {
            // Get old image to delete
            $stmt = $db->prepare("SELECT logo_image FROM gov_links WHERE id = ?");
            $stmt->execute([$id]);
            $old_data = $stmt->fetch();
            if ($old_data && $old_data['logo_image'] && file_exists(__DIR__ . '/../uploads/gov_links/' . $old_data['logo_image'])) {
                @unlink(__DIR__ . '/../uploads/gov_links/' . $old_data['logo_image']);
            }

            $stmt = $db->prepare("UPDATE gov_links SET title = ?, url = ?, logo_image = ?, logo_url = ?, category = ? WHERE id = ?");
            $success = $stmt->execute([$title, $url, $logo_image, $logo_url, $category, $id]);
        } else {
            $stmt = $db->prepare("UPDATE gov_links SET title = ?, url = ?, logo_url = ?, category = ? WHERE id = ?");
            $success = $stmt->execute([$title, $url, $logo_url, $category, $id]);
        }

        if ($success) {
            $_SESSION['flash_message'] = "Link updated successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to update link.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: gov_links_admin.php");
    exit;
}

// Fetch all items
$links = [];
if ($db) {
    $links = $db->query("SELECT * FROM gov_links ORDER BY created_at DESC")->fetchAll();
}

// Fetch item to edit
$edit_link = null;
if (isset($_GET['edit_id']) && $db) {
    $stmt = $db->prepare("SELECT * FROM gov_links WHERE id = ?");
    $stmt->execute([(int)$_GET['edit_id']]);
    $edit_link = $stmt->fetch();
}
?>

<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Government Links</h1>
            <p class="mt-1 text-sm text-slate-500">গুরুত্বপূর্ণ সরকারি ওয়েবসাইট লিংক পরিচালনা করুন</p>
        </div>
        <button onclick="document.getElementById('add-link-form').classList.toggle('hidden')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg>
            নতুন লিংক
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-link-form" class="mb-8 hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 font-serif-bn text-lg font-bold text-slate-900">Add New Link</div>
        <form action="gov_links_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Site Title *</span>
                    <input type="text" name="title" required placeholder="e.g. বাংলাদেশ জাতীয় তথ্য বাতায়ন" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">URL *</span>
                    <input type="url" name="url" required placeholder="https://bangladesh.gov.bd/" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Category (Optional)</span>
                    <input type="text" name="category" placeholder="e.g. Education, Health" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Logo Image Link (Optional)</span>
                    <input type="url" name="logo_url" placeholder="https://example.com/logo.png" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">OR Upload Logo (Max 5MB)</span>
                    <input type="file" name="logo_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('add-link-form').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Save Link</button>
            </div>
        </form>
    </div>

    <?php if ($edit_link): ?>
    <!-- Edit Form -->
    <div id="edit-link-form" class="mb-8 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 font-serif-bn text-lg font-bold text-slate-900">Edit Link</div>
        <form action="gov_links_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo $edit_link['id']; ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Site Title *</span>
                    <input type="text" name="title" value="<?php echo e($edit_link['title']); ?>" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">URL *</span>
                    <input type="url" name="url" value="<?php echo e($edit_link['url']); ?>" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Category (Optional)</span>
                    <input type="text" name="category" value="<?php echo e($edit_link['category']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Logo Image Link (Optional)</span>
                    <input type="url" name="logo_url" value="<?php echo e($edit_link['logo_url'] ?? ''); ?>" placeholder="https://example.com/logo.png" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">OR Upload Logo (Max 5MB)</span>
                    <?php if(!empty($edit_link['logo_image'])): ?>
                    <div class="mb-2 h-12 w-12 rounded-md border bg-white flex items-center justify-center overflow-hidden">
                        <img src="../uploads/gov_links/<?php echo e($edit_link['logo_image']); ?>" alt="current" class="max-h-full max-w-full object-contain">
                    </div>
                    <?php elseif(!empty($edit_link['logo_url'])): ?>
                    <div class="mb-2 h-12 w-12 rounded-md border bg-white flex items-center justify-center overflow-hidden">
                        <img src="<?php echo e($edit_link['logo_url']); ?>" alt="current" class="max-h-full max-w-full object-contain">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="logo_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <span class="text-xs text-slate-500 mt-1 block">Leave empty to keep the current logo.</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="gov_links_admin.php" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Update Link</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Links List -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-16">Logo</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">URL</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(empty($links)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center">
                            <div class="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <div class="font-serif-bn text-sm font-semibold text-slate-700">কোনো লিংক পাওয়া যায়নি</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($links as $link): ?>
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3">
                                <?php if(!empty($link['logo_image'])): ?>
                                <div class="h-10 w-10 rounded-md border bg-white flex items-center justify-center overflow-hidden">
                                    <img src="../uploads/gov_links/<?php echo e($link['logo_image']); ?>" alt="logo" class="max-h-full max-w-full object-contain">
                                </div>
                                <?php elseif(!empty($link['logo_url'])): ?>
                                <div class="h-10 w-10 rounded-md border bg-white flex items-center justify-center overflow-hidden">
                                    <img src="<?php echo e($link['logo_url']); ?>" alt="logo" class="max-h-full max-w-full object-contain">
                                </div>
                                <?php else: ?>
                                <div class="h-10 w-10 rounded-md bg-slate-100 flex items-center justify-center text-slate-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 font-medium text-slate-900"><?php echo e($link['title']); ?></td>
                            <td class="px-4 py-3 text-slate-500"><a href="<?php echo e($link['url']); ?>" target="_blank" class="hover:text-blue-600 hover:underline"><?php echo e($link['url']); ?></a></td>
                            <td class="px-4 py-3 text-slate-600"><?php echo e($link['category'] ?: '—'); ?></td>
                            <td class="px-4 py-3 flex gap-2 h-full items-center justify-center">
                                <a href="gov_links_admin.php?edit_id=<?php echo $link['id']; ?>" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-primary transition mt-1" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                                <form action="gov_links_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this link?');" class="inline-block mt-1">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $link['id']; ?>">
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
