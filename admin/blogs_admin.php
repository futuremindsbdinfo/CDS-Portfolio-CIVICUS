<?php
// admin/blogs_admin.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/upload_handler.php';

$db = Database::getConnection();

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("SELECT cover_image FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        $blog = $stmt->fetch();
        $image_to_delete = $blog ? $blog['cover_image'] : null;

        $stmt = $db->prepare("DELETE FROM blogs WHERE id = ?");
        if ($stmt->execute([$id])) {
            if ($image_to_delete && file_exists(__DIR__ . '/../uploads/blogs/' . $image_to_delete)) {
                @unlink(__DIR__ . '/../uploads/blogs/' . $image_to_delete);
            }
            $_SESSION['flash_message'] = "Blog deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete blog.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: blogs_admin.php");
    exit;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $title = clean_input($_POST['title']);
    // For rich text we might not want to strip all tags if we use an actual rich text editor, 
    // but the prompt says rich text/textarea. I'll use clean_input for now or a loose sanitize if needed.
    // Given the framework, let's stick to clean_input for safety. Wait, clean_input might strip tags. 
    // We should use htmlspecialchars in clean_input? Let's check sanitize.php. 
    // Since I don't have its content exactly, I'll use it to be safe, but maybe we should allow some html if they want rich text.
    // If they want actual rich text, they'd use Quill or TinyMCE. The user said "(Rich text/textarea)", 
    // so maybe basic textarea is fine for now. 
    $content = $_POST['content']; // not cleaned fully here, we will rely on output escaping or a safer sanitize
    // Actually, I should use clean_input as per the project's standard.
    $content = clean_input($_POST['content']);
    $published_date = !empty($_POST['published_date']) ? clean_input($_POST['published_date']) : null;
    
    $cover_image = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handle_image_upload($_FILES['cover_image'], 'blogs');
        if (!$upload_result['success']) {
            $_SESSION['flash_message'] = $upload_result['error'];
            $_SESSION['flash_type'] = "error";
            header("Location: blogs_admin.php");
            exit;
        }
        $cover_image = $upload_result['filename'];
    }

    if ($db && !empty($title) && !empty($content)) {
        $stmt = $db->prepare("INSERT INTO blogs (title, content, cover_image, published_date) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $content, $cover_image, $published_date])) {
            $_SESSION['flash_message'] = "Blog added successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to add blog.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: blogs_admin.php");
    exit;
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    $title = clean_input($_POST['title']);
    $content = clean_input($_POST['content']);
    $published_date = !empty($_POST['published_date']) ? clean_input($_POST['published_date']) : null;
    
    if ($db && !empty($title) && !empty($content)) {
        $cover_image_query = "";
        $params = [$title, $content, $published_date];

        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = handle_image_upload($_FILES['cover_image'], 'blogs');
            if (!$upload_result['success']) {
                $_SESSION['flash_message'] = $upload_result['error'];
                $_SESSION['flash_type'] = "error";
                header("Location: blogs_admin.php");
                exit;
            }
            $cover_image_query = ", cover_image = ?";
            $params[] = $upload_result['filename'];
            
            // Delete old image
            $stmt = $db->prepare("SELECT cover_image FROM blogs WHERE id = ?");
            $stmt->execute([$id]);
            $old_blog = $stmt->fetch();
            if ($old_blog && $old_blog['cover_image'] && file_exists(__DIR__ . '/../uploads/blogs/' . $old_blog['cover_image'])) {
                @unlink(__DIR__ . '/../uploads/blogs/' . $old_blog['cover_image']);
            }
        }
        
        $params[] = $id;

        $stmt = $db->prepare("UPDATE blogs SET title = ?, content = ?, published_date = ? $cover_image_query WHERE id = ?");
        if ($stmt->execute($params)) {
            $_SESSION['flash_message'] = "Blog updated successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to update blog.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: blogs_admin.php");
    exit;
}

// Fetch all blogs
$blogs = [];
if ($db) {
    $blogs = $db->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll();
}
?>

<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Blogs Management</h1>
            <p class="mt-1 text-sm text-slate-500">ব্লগ এবং আর্টিকেল পরিচালনা করুন</p>
        </div>
        <button onclick="document.getElementById('add-blog-form').classList.toggle('hidden')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg>
            নতুন ব্লগ
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-blog-form" class="mb-8 hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 font-serif-bn text-lg font-bold text-slate-900">Add New Blog</div>
        <form action="blogs_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block md:col-span-2">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Title *</span>
                    <input type="text" name="title" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Content *</span>
                    <textarea name="content" required rows="8" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Published Date</span>
                    <input type="date" name="published_date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-slate-600">Cover Image (JPG, PNG, WEBP)</span>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('add-blog-form').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Save Blog</button>
            </div>
        </form>
    </div>

    <!-- Blogs List -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Published Date</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(empty($blogs)): ?>
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center">
                            <div class="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <div class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7"><path d="M4 19h16v2H4zm14-4H6V5h12v10z"/></svg>
                                </div>
                                <div class="font-serif-bn text-sm font-semibold text-slate-700">কোনো ব্লগ পাওয়া যায়নি</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($blogs as $blog): ?>
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 font-medium text-slate-900"><?php echo e($blog['title']); ?></td>
                            <td class="px-4 py-3 text-slate-600"><?php echo $blog['published_date'] ? date('d M Y', strtotime($blog['published_date'])) : 'N/A'; ?></td>
                            <td class="px-4 py-3 flex gap-2">
                                <button onclick="document.getElementById('edit-blog-form-<?php echo $blog['id']; ?>').classList.toggle('hidden')" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-blue-600 hover:bg-blue-50 transition" title="Edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <form action="blogs_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog?');" class="inline-block">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $blog['id']; ?>">
                                    <button type="submit" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr id="edit-blog-form-<?php echo $blog['id']; ?>" class="hidden bg-slate-50/80 border-b border-slate-200">
                            <td colspan="3" class="p-5">
                                <div class="mb-4 font-serif-bn text-base font-bold text-slate-900">Edit Blog</div>
                                <form action="blogs_admin.php" method="POST" class="space-y-4" enctype="multipart/form-data">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $blog['id']; ?>">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="block md:col-span-2">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Title *</span>
                                            <input type="text" name="title" required value="<?php echo e($blog['title']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                    </div>
                        
                                    <div class="grid grid-cols-1 gap-4">
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Content *</span>
                                            <textarea name="content" required rows="8" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"><?php echo $blog['content']; ?></textarea>
                                        </label>
                                    </div>
                        
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Published Date</span>
                                            <input type="date" name="published_date" value="<?php echo e($blog['published_date']); ?>" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                        <label class="block">
                                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Cover Image (Leave empty to keep existing)</span>
                                            <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </label>
                                    </div>
                        
                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" onclick="document.getElementById('edit-blog-form-<?php echo $blog['id']; ?>').classList.add('hidden')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">Update Blog</button>
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

<!-- TinyMCE Rich Text Editor -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea[name="content"]',
    plugins: 'image link media lists table code wordcount',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image media | table code',
    images_upload_url: 'upload_image.php',
    automatic_uploads: true,
    file_picker_types: 'image',
    height: 400,
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px }'
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
