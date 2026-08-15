<?php
// admin/forms_admin.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$message = '';
$error = '';

// Create upload directory if not exists
$upload_dir = __DIR__ . '/../uploads/forms/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle Add / Edit / Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token mismatch. Please reload the page.';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'add' || $action === 'edit') {
            $title_bn = clean_input($_POST['title_bn'] ?? '');
            $title_en = clean_input($_POST['title_en'] ?? '');
            $description_bn = clean_input($_POST['description_bn'] ?? '');
            $description_en = clean_input($_POST['description_en'] ?? '');
            $category = clean_input($_POST['category'] ?? 'সদস্যপদ ও আবেদন');
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $form_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if (empty($title_bn)) {
                $error = 'ফরমের নাম (বাংলা) আবশ্যক।';
            } else {
                $file_path = null;
                $file_type = 'pdf';
                $file_size = '1.0 MB';

                // Handle file upload
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $file_tmp = $_FILES['file']['tmp_name'];
                    $file_name = $_FILES['file']['name'];
                    $file_bytes = $_FILES['file']['size'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $allowed_exts = ['pdf', 'doc', 'docx', 'zip', 'xlsx', 'xls'];

                    if (in_array($file_ext, $allowed_exts)) {
                        $new_filename = 'form_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                        $dest_path = $upload_dir . $new_filename;
                        if (move_uploaded_file($file_tmp, $dest_path)) {
                            $file_path = 'uploads/forms/' . $new_filename;
                            $file_type = $file_ext;
                            
                            // Format size
                            if ($file_bytes >= 1048576) {
                                $file_size = number_format($file_bytes / 1048576, 1) . ' MB';
                            } else {
                                $file_size = number_format($file_bytes / 1024, 0) . ' KB';
                            }
                        } else {
                            $error = 'ফাইল আপলোড করতে ব্যর্থ হয়েছে।';
                        }
                    } else {
                        $error = 'শুধুমাত্র PDF, DOC, DOCX, XLSX বা ZIP ফাইল সমর্থিত।';
                    }
                }

                if (!$error) {
                    try {
                        if ($action === 'add') {
                            if (!$file_path) {
                                $file_path = 'uploads/forms/sample_form.pdf';
                            }
                            $stmt = $db->prepare("INSERT INTO downloadable_forms (title_bn, title_en, description_bn, description_en, category, file_path, file_type, file_size, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$title_bn, $title_en, $description_bn, $description_en, $category, $file_path, $file_type, $file_size, $is_active]);
                            $message = 'আবেদন ফরম সফলভাবে যুক্ত করা হয়েছে!';
                        } elseif ($action === 'edit' && $form_id > 0) {
                            if ($file_path) {
                                $stmt = $db->prepare("UPDATE downloadable_forms SET title_bn=?, title_en=?, description_bn=?, description_en=?, category=?, file_path=?, file_type=?, file_size=?, is_active=? WHERE id=?");
                                $stmt->execute([$title_bn, $title_en, $description_bn, $description_en, $category, $file_path, $file_type, $file_size, $is_active, $form_id]);
                            } else {
                                $stmt = $db->prepare("UPDATE downloadable_forms SET title_bn=?, title_en=?, description_bn=?, description_en=?, category=?, is_active=? WHERE id=?");
                                $stmt->execute([$title_bn, $title_en, $description_bn, $description_en, $category, $is_active, $form_id]);
                            }
                            $message = 'ফরমের তথ্য সফলভাবে আপডেট করা হয়েছে!';
                        }
                    } catch (PDOException $e) {
                        $error = 'ডাটাবেজ ত্রুটি: ' . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'delete') {
            $form_id = (int)($_POST['id'] ?? 0);
            if ($form_id > 0) {
                try {
                    $stmt = $db->prepare("DELETE FROM downloadable_forms WHERE id = ?");
                    $stmt->execute([$form_id]);
                    $message = 'ফরম মুছে ফেলা হয়েছে!';
                } catch (PDOException $e) {
                    $error = 'মুছে ফেলা যায়নি: ' . $e->getMessage();
                }
            }
        }
    }
}

// Fetch all forms
$forms = [];
if ($db) {
    try {
        $forms = $db->query("SELECT * FROM downloadable_forms ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $forms = [];
    }
}

// Fetch single form for edit
$edit_form = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    foreach ($forms as $f) {
        if ((int)$f['id'] === $edit_id) {
            $edit_form = $f;
            break;
        }
    }
}
?>

<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold font-serif-bn text-slate-800">আবেদন ফরম ও রিসোর্স ম্যানেজমেন্ট</h1>
            <p class="text-sm text-slate-500">বিভিন্ন কার্যক্রমের ডাউনলোডযোগ্য আবেদন ফরম ও ডকুমেন্ট নিয়ন্ত্রণ করুন</p>
        </div>
        <?php if ($edit_form): ?>
            <a href="forms_admin.php" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-300 transition">
                + নতুন ফরম যোগ করুন
            </a>
        <?php endif; ?>
    </div>

    <!-- Alerts -->
    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?php echo e($message); ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-5">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm sticky top-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <?php echo $edit_form ? 'ফরম সম্পাদনা করুন' : 'নতুন আবেদন ফরম আপলোড'; ?>
                </h2>

                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="action" value="<?php echo $edit_form ? 'edit' : 'add'; ?>">
                    <?php if ($edit_form): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_form['id']; ?>">
                    <?php endif; ?>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">ক্যাটাগরি *</label>
                        <select name="category" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none bg-white">
                            <option value="সদস্যপদ ও আবেদন" <?php echo (isset($edit_form) && $edit_form['category'] === 'সদস্যপদ ও আবেদন') ? 'selected' : ''; ?>>সদস্যপদ ও আবেদন</option>
                            <option value="স্বেচ্ছাসেবা" <?php echo (isset($edit_form) && $edit_form['category'] === 'স্বেচ্ছাসেবা') ? 'selected' : ''; ?>>স্বেচ্ছাসেবা ও ইন্টার্নশিপ</option>
                            <option value="শিক্ষা ও বৃত্তি" <?php echo (isset($edit_form) && $edit_form['category'] === 'শিক্ষা ও বৃত্তি') ? 'selected' : ''; ?>>শিক্ষা ও বৃত্তি সহায়তা</option>
                            <option value="অন্যান্য" <?php echo (isset($edit_form) && $edit_form['category'] === 'অন্যান্য') ? 'selected' : ''; ?>>অন্যান্য রিসোর্স</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">ফরমের নাম (বাংলা) *</label>
                        <input type="text" name="title_bn" required value="<?php echo e($edit_form['title_bn'] ?? ''); ?>" placeholder="যেমন: সাধারণ সদস্যপদ আবেদন ফরম" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Form Title (English)</label>
                        <input type="text" name="title_en" value="<?php echo e($edit_form['title_en'] ?? ''); ?>" placeholder="e.g. General Membership Application Form" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">সংক্ষিপ্ত বিবরণ (বাংলা)</label>
                        <textarea name="description_bn" rows="2" placeholder="ফরমের উদ্দেশ্য ও নির্দেশনা..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none"><?php echo e($edit_form['description_bn'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Description (English)</label>
                        <textarea name="description_en" rows="2" placeholder="Brief instruction in English..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none"><?php echo e($edit_form['description_en'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">ফরম ফাইল (PDF / DOCX / ZIP)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.zip,.xls,.xlsx" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-soft file:text-primary hover:file:bg-primary/20">
                        <?php if (!empty($edit_form['file_path'])): ?>
                            <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                                <span>বর্তমান ফাইল:</span>
                                <a href="/<?php echo e($edit_form['file_path']); ?>" target="_blank" class="text-primary font-bold hover:underline">
                                    <?php echo basename($edit_form['file_path']); ?> (<?php echo e($edit_form['file_size']); ?>)
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" <?php echo (!isset($edit_form) || $edit_form['is_active']) ? 'checked' : ''; ?> class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                            <span class="text-xs font-bold text-slate-700">ওয়েবসাইটে ডাউনলোডের জন্য উন্মুক্ত রাখুন</span>
                        </label>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-lg shadow-sm transition">
                            <?php echo $edit_form ? 'আপডেট করুন' : 'ফরম সংরক্ষণ করুন'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Forms List Section -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-800">বিদ্যমান ফরম তালিকা (<?php echo count($forms); ?>)</h2>
                </div>

                <div class="divide-y divide-slate-100">
                    <?php if (!empty($forms)): ?>
                        <?php foreach ($forms as $f): ?>
                            <div class="p-4 flex items-center gap-4 hover:bg-slate-50 transition">
                                <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 grid place-items-center shrink-0 border border-red-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-sm font-bold text-slate-800 truncate"><?php echo e($f['title_bn']); ?></h3>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                            <?php echo strtoupper($f['file_type']); ?> • <?php echo e($f['file_size']); ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 line-clamp-1 mb-1"><?php echo e($f['description_bn']); ?></p>
                                    <p class="text-[11px] text-slate-400">ডাউনলোড হয়েছে: <span class="font-bold text-primary"><?php echo (int)$f['downloads_count']; ?></span> বার • ক্যাটাগরি: <?php echo e($f['category']); ?></p>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a href="/<?php echo e($f['file_path']); ?>" target="_blank" download title="Download" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>

                                    <a href="forms_admin.php?edit=<?php echo $f['id']; ?>" title="Edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত এই ফরমটি মুছে ফেলতে চান?');" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                                        <button type="submit" title="Delete" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-12 text-center text-slate-400">
                            কোন আবেদন ফরম পাওয়া যায়নি।
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
