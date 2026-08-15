<?php
// admin/sliders_admin.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$message = '';
$error = '';

// Create upload directory if not exists
$upload_dir = __DIR__ . '/../uploads/sliders/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token mismatch. Please reload the page.';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'add' || $action === 'edit') {
            $title_bn = clean_input($_POST['title_bn'] ?? '');
            $title_en = clean_input($_POST['title_en'] ?? '');
            $subtitle_bn = clean_input($_POST['subtitle_bn'] ?? '');
            $subtitle_en = clean_input($_POST['subtitle_en'] ?? '');
            $button_text_bn = clean_input($_POST['button_text_bn'] ?? '');
            $button_text_en = clean_input($_POST['button_text_en'] ?? '');
            $button_url = clean_input($_POST['button_url'] ?? '');
            $display_order = (int)($_POST['display_order'] ?? 0);
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $slider_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if (empty($title_bn) || empty($subtitle_bn)) {
                $error = 'বাংলা টাইটেল এবং সাবটাইটেল আবশ্যক।';
            } else {
                $image_path = null;
                // Handle file upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $file_tmp = $_FILES['image']['tmp_name'];
                    $file_name = $_FILES['image']['name'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

                    if (in_array($file_ext, $allowed_exts)) {
                        $new_filename = 'slide_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                        $dest_path = $upload_dir . $new_filename;
                        if (move_uploaded_file($file_tmp, $dest_path)) {
                            $image_path = 'uploads/sliders/' . $new_filename;
                        } else {
                            $error = 'ছবি আপলোড করতে ব্যর্থ হয়েছে।';
                        }
                    } else {
                        $error = 'শুধুমাত্র JPG, PNG, WEBP বা SVG ফাইল সমর্থিত।';
                    }
                }

                if (!$error) {
                    try {
                        if ($action === 'add') {
                            if (!$image_path) {
                                $image_path = 'assets/img/hero/hero-bg-1.svg'; // Default
                            }
                            $stmt = $db->prepare("INSERT INTO hero_sliders (title_bn, title_en, subtitle_bn, subtitle_en, image_path, button_text_bn, button_text_en, button_url, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$title_bn, $title_en, $subtitle_bn, $subtitle_en, $image_path, $button_text_bn, $button_text_en, $button_url, $display_order, $is_active]);
                            $message = 'স্লাইডার সফলভাবে যুক্ত করা হয়েছে!';
                        } elseif ($action === 'edit' && $slider_id > 0) {
                            if ($image_path) {
                                $stmt = $db->prepare("UPDATE hero_sliders SET title_bn=?, title_en=?, subtitle_bn=?, subtitle_en=?, image_path=?, button_text_bn=?, button_text_en=?, button_url=?, display_order=?, is_active=? WHERE id=?");
                                $stmt->execute([$title_bn, $title_en, $subtitle_bn, $subtitle_en, $image_path, $button_text_bn, $button_text_en, $button_url, $display_order, $is_active, $slider_id]);
                            } else {
                                $stmt = $db->prepare("UPDATE hero_sliders SET title_bn=?, title_en=?, subtitle_bn=?, subtitle_en=?, button_text_bn=?, button_text_en=?, button_url=?, display_order=?, is_active=? WHERE id=?");
                                $stmt->execute([$title_bn, $title_en, $subtitle_bn, $subtitle_en, $button_text_bn, $button_text_en, $button_url, $display_order, $is_active, $slider_id]);
                            }
                            $message = 'স্লাইডার সফলভাবে আপডেট করা হয়েছে!';
                        }
                    } catch (PDOException $e) {
                        $error = 'ডাটাবেজ ত্রুটি: ' . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'delete') {
            $slider_id = (int)($_POST['id'] ?? 0);
            if ($slider_id > 0) {
                try {
                    $stmt = $db->prepare("DELETE FROM hero_sliders WHERE id = ?");
                    $stmt->execute([$slider_id]);
                    $message = 'স্লাইডার মুছে ফেলা হয়েছে!';
                } catch (PDOException $e) {
                    $error = 'মুছে ফেলা যায়নি: ' . $e->getMessage();
                }
            }
        } elseif ($action === 'toggle') {
            $slider_id = (int)($_POST['id'] ?? 0);
            if ($slider_id > 0) {
                try {
                    $stmt = $db->prepare("UPDATE hero_sliders SET is_active = 1 - is_active WHERE id = ?");
                    $stmt->execute([$slider_id]);
                    $message = 'স্লাইডারের স্ট্যাটাস পরিবর্তন করা হয়েছে!';
                } catch (PDOException $e) {
                    $error = 'আপডেট ব্যর্থ: ' . $e->getMessage();
                }
            }
        }
    }
}

// Fetch all sliders
$sliders = [];
if ($db) {
    try {
        $sliders = $db->query("SELECT * FROM hero_sliders ORDER BY display_order ASC, id DESC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $sliders = [];
    }
}

// Fetch single slider for edit
$edit_slide = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    foreach ($sliders as $s) {
        if ((int)$s['id'] === $edit_id) {
            $edit_slide = $s;
            break;
        }
    }
}
?>

<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold font-serif-bn text-slate-800">হোমপেজ হিরো স্লাইডার ম্যানেজমেন্ট</h1>
            <p class="text-sm text-slate-500">হোমপেজের মূল ব্যানারের স্লাইড, টাইটেল, ছবি ও বাটন পরিবর্তন করুন</p>
        </div>
        <?php if ($edit_slide): ?>
            <a href="sliders_admin.php" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-300 transition">
                + নতুন স্লাইড যোগ করুন
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
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <?php echo $edit_slide ? 'স্লাইড সম্পাদনা করুন' : 'নতুন স্লাইড তৈরি করুন'; ?>
                </h2>

                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="action" value="<?php echo $edit_slide ? 'edit' : 'add'; ?>">
                    <?php if ($edit_slide): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_slide['id']; ?>">
                    <?php endif; ?>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">টাইটেল (বাংলা) *</label>
                        <input type="text" name="title_bn" required value="<?php echo e($edit_slide['title_bn'] ?? ''); ?>" placeholder="যেমন: নাগরিক সচেতনতা ও সমাজ উন্নয়নে সিডিএস" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">টাইটেল (English)</label>
                        <input type="text" name="title_en" value="<?php echo e($edit_slide['title_en'] ?? ''); ?>" placeholder="e.g. Citizen Development Society (CDS)" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">সাবটাইটেল / বিবরণ (বাংলা) *</label>
                        <textarea name="subtitle_bn" rows="2" required placeholder="সংক্ষিপ্ত বিবরণ লিখুন..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none"><?php echo e($edit_slide['subtitle_bn'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">সাবটাইটেল / বিবরণ (English)</label>
                        <textarea name="subtitle_en" rows="2" placeholder="Short description in English..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none"><?php echo e($edit_slide['subtitle_en'] ?? ''); ?></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">বাটন টেক্সট (বাংলা)</label>
                            <input type="text" name="button_text_bn" value="<?php echo e($edit_slide['button_text_bn'] ?? 'আমাদের কার্যক্রম'); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">বাটন টেক্সট (English)</label>
                            <input type="text" name="button_text_en" value="<?php echo e($edit_slide['button_text_en'] ?? 'Our Activities'); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">বাটন লিংক (URL)</label>
                        <input type="text" name="button_url" value="<?php echo e($edit_slide['button_url'] ?? '/projects.php'); ?>" placeholder="/projects.php অথবা https://..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">স্লাইডার ব্যাকগ্রাউন্ড ছবি</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-soft file:text-primary hover:file:bg-primary/20">
                        <?php if (!empty($edit_slide['image_path'])): ?>
                            <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                                <span>বর্তমান ছবি:</span>
                                <img src="/<?php echo e($edit_slide['image_path']); ?>" alt="Current" class="h-8 w-16 object-cover rounded border">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-3 items-center pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">ক্রমিক / অর্ডার</label>
                            <input type="number" name="display_order" value="<?php echo (int)($edit_slide['display_order'] ?? 1); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" <?php echo (!isset($edit_slide) || $edit_slide['is_active']) ? 'checked' : ''; ?> class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                                <span class="text-xs font-bold text-slate-700">সক্রিয় রাখুন</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-lg shadow-sm transition">
                            <?php echo $edit_slide ? 'আপডেট করুন' : 'স্লাইডার সংরক্ষণ করুন'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sliders List Section -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-800">বিদ্যমান স্লাইডার তালিকা (<?php echo count($sliders); ?>)</h2>
                </div>

                <div class="divide-y divide-slate-100">
                    <?php if (!empty($sliders)): ?>
                        <?php foreach ($sliders as $s): ?>
                            <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 hover:bg-slate-50 transition">
                                <div class="w-full sm:w-32 h-20 shrink-0 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 relative">
                                    <img src="/<?php echo e($s['image_path']); ?>" alt="<?php echo e($s['title_bn']); ?>" class="w-full h-full object-cover">
                                    <span class="absolute top-1 left-1 px-1.5 py-0.5 rounded bg-black/60 text-[10px] text-white font-mono">#<?php echo (int)$s['display_order']; ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-sm font-bold text-slate-800 truncate"><?php echo e($s['title_bn']); ?></h3>
                                        <?php if ($s['is_active']): ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Active</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xs text-slate-500 line-clamp-1 mb-1"><?php echo e($s['subtitle_bn']); ?></p>
                                    <p class="text-[11px] text-slate-400 font-mono truncate">🔗 <?php echo e($s['button_url']); ?></p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                        <button type="submit" title="Toggle Status" class="p-2 text-slate-500 hover:text-primary hover:bg-slate-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        </button>
                                    </form>

                                    <a href="sliders_admin.php?edit=<?php echo $s['id']; ?>" title="Edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত এই স্লাইডারটি মুছে ফেলতে চান?');" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                        <button type="submit" title="Delete" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-12 text-center text-slate-400">
                            কোন স্লাইডার পাওয়া যায়নি। নতুন স্লাইড যোগ করুন।
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
