<?php
// admin/team_admin.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();
$message = '';
$error = '';

// Create upload directory if not exists
$upload_dir = __DIR__ . '/../uploads/team/';
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
            $name_bn = clean_input($_POST['name_bn'] ?? '');
            $name_en = clean_input($_POST['name_en'] ?? '');
            $designation_bn = clean_input($_POST['designation_bn'] ?? '');
            $designation_en = clean_input($_POST['designation_en'] ?? '');
            $category = clean_input($_POST['category'] ?? 'governing_body');
            $bio_bn = clean_input($_POST['bio_bn'] ?? '');
            $bio_en = clean_input($_POST['bio_en'] ?? '');
            $email = clean_input($_POST['email'] ?? '');
            $phone = clean_input($_POST['phone'] ?? '');
            $facebook_url = clean_input($_POST['facebook_url'] ?? '');
            $linkedin_url = clean_input($_POST['linkedin_url'] ?? '');
            $display_order = (int)($_POST['display_order'] ?? 0);
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $member_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if (empty($name_bn) || empty($designation_bn)) {
                $error = 'সদস্যের নাম ও পদবি (বাংলা) আবশ্যক।';
            } else {
                $photo_path = null;
                // Handle photo upload
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $file_tmp = $_FILES['photo']['tmp_name'];
                    $file_name = $_FILES['photo']['name'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];

                    if (in_array($file_ext, $allowed_exts)) {
                        $new_filename = 'team_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                        $dest_path = $upload_dir . $new_filename;
                        if (move_uploaded_file($file_tmp, $dest_path)) {
                            $photo_path = 'uploads/team/' . $new_filename;
                        } else {
                            $error = 'ছবি আপলোড করতে ব্যর্থ হয়েছে।';
                        }
                    } else {
                        $error = 'শুধুমাত্র JPG, PNG বা WEBP ছবি গ্রহণযোগ্য।';
                    }
                }

                if (!$error) {
                    try {
                        if ($action === 'add') {
                            $stmt = $db->prepare("INSERT INTO team_members (name_bn, name_en, designation_bn, designation_en, category, photo_path, bio_bn, bio_en, email, phone, facebook_url, linkedin_url, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$name_bn, $name_en, $designation_bn, $designation_en, $category, $photo_path, $bio_bn, $bio_en, $email, $phone, $facebook_url, $linkedin_url, $display_order, $is_active]);
                            $message = 'সদস্য সফলভাবে যুক্ত করা হয়েছে!';
                        } elseif ($action === 'edit' && $member_id > 0) {
                            if ($photo_path) {
                                $stmt = $db->prepare("UPDATE team_members SET name_bn=?, name_en=?, designation_bn=?, designation_en=?, category=?, photo_path=?, bio_bn=?, bio_en=?, email=?, phone=?, facebook_url=?, linkedin_url=?, display_order=?, is_active=? WHERE id=?");
                                $stmt->execute([$name_bn, $name_en, $designation_bn, $designation_en, $category, $photo_path, $bio_bn, $bio_en, $email, $phone, $facebook_url, $linkedin_url, $display_order, $is_active, $member_id]);
                            } else {
                                $stmt = $db->prepare("UPDATE team_members SET name_bn=?, name_en=?, designation_bn=?, designation_en=?, category=?, bio_bn=?, bio_en=?, email=?, phone=?, facebook_url=?, linkedin_url=?, display_order=?, is_active=? WHERE id=?");
                                $stmt->execute([$name_bn, $name_en, $designation_bn, $designation_en, $category, $bio_bn, $bio_en, $email, $phone, $facebook_url, $linkedin_url, $display_order, $is_active, $member_id]);
                            }
                            $message = 'সদস্যের তথ্য সফলভাবে আপডেট করা হয়েছে!';
                        }
                    } catch (PDOException $e) {
                        $error = 'ডাটাবেজ ত্রুটি: ' . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'delete') {
            $member_id = (int)($_POST['id'] ?? 0);
            if ($member_id > 0) {
                try {
                    $stmt = $db->prepare("DELETE FROM team_members WHERE id = ?");
                    $stmt->execute([$member_id]);
                    $message = 'সদস্য সফলভাবে মুছে ফেলা হয়েছে!';
                } catch (PDOException $e) {
                    $error = 'মুছে ফেলা যায়নি: ' . $e->getMessage();
                }
            }
        } elseif ($action === 'toggle') {
            $member_id = (int)($_POST['id'] ?? 0);
            if ($member_id > 0) {
                try {
                    $stmt = $db->prepare("UPDATE team_members SET is_active = 1 - is_active WHERE id = ?");
                    $stmt->execute([$member_id]);
                    $message = 'স্ট্যাটাস পরিবর্তন করা হয়েছে!';
                } catch (PDOException $e) {
                    $error = 'আপডেট ব্যর্থ: ' . $e->getMessage();
                }
            }
        }
    }
}

// Category filter
$filter_cat = clean_input($_GET['category'] ?? '');
$query = "SELECT * FROM team_members";
$params = [];
if (!empty($filter_cat)) {
    $query .= " WHERE category = ?";
    $params[] = $filter_cat;
}
$query .= " ORDER BY display_order ASC, id DESC";

$members = [];
if ($db) {
    try {
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $members = [];
    }
}

// Fetch single member for edit
$edit_member = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    foreach ($members as $m) {
        if ((int)$m['id'] === $edit_id) {
            $edit_member = $m;
            break;
        }
    }
}

$category_labels = [
    'governing_body' => 'কার্যনির্বাহী পরিষদ / গভর্নিং বডি',
    'advisors' => 'উপদেষ্টা পরিষদ',
    'general_members' => 'সাধারণ সদস্য',
    'volunteers' => 'স্বেচ্ছাসেবক ও টিম'
];
?>

<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold font-serif-bn text-slate-800">কমিটি ও টিম ম্যানেজমেন্ট</h1>
            <p class="text-sm text-slate-500">পরিচালনা পর্ষদ, উপদেষ্টা পরিষদ ও টিম মেম্বারদের তথ্য নিয়ন্ত্রণ করুন</p>
        </div>
        <?php if ($edit_member): ?>
            <a href="team_admin.php" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-300 transition">
                + নতুন সদস্য যোগ করুন
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
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <?php echo $edit_member ? 'সদস্যের তথ্য সম্পাদনা' : 'নতুন সদস্য যুক্ত করুন'; ?>
                </h2>

                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="action" value="<?php echo $edit_member ? 'edit' : 'add'; ?>">
                    <?php if ($edit_member): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_member['id']; ?>">
                    <?php endif; ?>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">ক্যাটাগরি / কমিটি টাইপ *</label>
                        <select name="category" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none bg-white">
                            <?php foreach ($category_labels as $cat_key => $cat_name): ?>
                                <option value="<?php echo $cat_key; ?>" <?php echo (isset($edit_member) && $edit_member['category'] === $cat_key) ? 'selected' : ''; ?>>
                                    <?php echo $cat_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">নাম (বাংলা) *</label>
                            <input type="text" name="name_bn" required value="<?php echo e($edit_member['name_bn'] ?? ''); ?>" placeholder="যেমন: ড. তানভীর আহমেদ" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Name (English)</label>
                            <input type="text" name="name_en" value="<?php echo e($edit_member['name_en'] ?? ''); ?>" placeholder="e.g. Dr. Tanvir Ahmed" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">পদবি (বাংলা) *</label>
                            <input type="text" name="designation_bn" required value="<?php echo e($edit_member['designation_bn'] ?? ''); ?>" placeholder="যেমন: সাধারণ সম্পাদক" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Designation (English)</label>
                            <input type="text" name="designation_en" value="<?php echo e($edit_member['designation_en'] ?? ''); ?>" placeholder="e.g. General Secretary" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">সংক্ষিপ্ত পরিচিতি / বায়ো (বাংলা)</label>
                        <textarea name="bio_bn" rows="2" placeholder="সদস্যের অভিজ্ঞতা ও পরিচিতি..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none"><?php echo e($edit_member['bio_bn'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Short Bio (English)</label>
                        <textarea name="bio_en" rows="2" placeholder="Brief introduction in English..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none"><?php echo e($edit_member['bio_en'] ?? ''); ?></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">ইমেইল</label>
                            <input type="email" name="email" value="<?php echo e($edit_member['email'] ?? ''); ?>" placeholder="member@gmail.com" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">ফোন নম্বর</label>
                            <input type="text" name="phone" value="<?php echo e($edit_member['phone'] ?? ''); ?>" placeholder="01700000000" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">সদস্যের ছবি (Photo)</label>
                        <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-soft file:text-primary hover:file:bg-primary/20">
                        <?php if (!empty($edit_member['photo_path'])): ?>
                            <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                                <span>বর্তমান ছবি:</span>
                                <img src="/<?php echo e($edit_member['photo_path']); ?>" alt="Current" class="h-10 w-10 object-cover rounded-full border">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-3 items-center pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">ডিসপ্লে অর্ডার / ক্রমিক</label>
                            <input type="number" name="display_order" value="<?php echo (int)($edit_member['display_order'] ?? 1); ?>" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" <?php echo (!isset($edit_member) || $edit_member['is_active']) ? 'checked' : ''; ?> class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                                <span class="text-xs font-bold text-slate-700">ওয়েবসাইটে দেখান</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-lg shadow-sm transition">
                            <?php echo $edit_member ? 'আপডেট করুন' : 'সদস্য সংরক্ষণ করুন'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Members List Section -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Filter bar -->
                <div class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-base font-bold text-slate-800">সদস্য তালিকা (<?php echo count($members); ?>)</h2>
                    
                    <form method="GET" class="flex items-center gap-2">
                        <select name="category" onchange="this.form.submit()" class="text-xs px-3 py-1.5 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none">
                            <option value="">সকল ক্যাটাগরি</option>
                            <?php foreach ($category_labels as $ck => $cn): ?>
                                <option value="<?php echo $ck; ?>" <?php echo ($filter_cat === $ck) ? 'selected' : ''; ?>>
                                    <?php echo $cn; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <div class="divide-y divide-slate-100">
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $m): ?>
                            <div class="p-4 flex items-center gap-4 hover:bg-slate-50 transition">
                                <div class="w-12 h-12 rounded-full overflow-hidden bg-slate-200 shrink-0 border border-slate-200">
                                    <?php if (!empty($m['photo_path'])): ?>
                                        <img src="/<?php echo e($m['photo_path']); ?>" alt="<?php echo e($m['name_bn']); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full grid place-items-center text-slate-400 font-bold text-sm">
                                            <?php echo mb_substr($m['name_bn'], 0, 1); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <h3 class="text-sm font-bold text-slate-800 truncate"><?php echo e($m['name_bn']); ?></h3>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">
                                            <?php echo $category_labels[$m['category']] ?? $m['category']; ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-primary font-semibold truncate"><?php echo e($m['designation_bn']); ?></p>
                                    <p class="text-[11px] text-slate-400">অর্ডার: #<?php echo (int)$m['display_order']; ?> <?php echo $m['is_active'] ? '• <span class="text-emerald-600 font-bold">সক্রিয়</span>' : '• <span class="text-slate-400">নিষ্ক্রিয়</span>'; ?></p>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a href="team_admin.php?edit=<?php echo $m['id']; ?>" title="Edit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত এই সদস্যকে মুছে ফেলতে চান?');" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                        <button type="submit" title="Delete" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-12 text-center text-slate-400">
                            কোন সদস্য পাওয়া যায়নি। নতুন সদস্য যোগ করুন।
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
