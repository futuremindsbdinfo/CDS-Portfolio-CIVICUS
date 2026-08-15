<?php 
// blog_details.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db = Database::getConnection();

$blog = null;
$recent_blogs = [];

if ($db && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch other recent blogs
        $r_stmt = $db->prepare("SELECT id, title, title_bn, title_en, content, content_bn, content_en, category, image_path, cover_image, published_date, created_at FROM blogs WHERE id != ? AND (status = 'published' OR status IS NULL) ORDER BY created_at DESC LIMIT 3");
        $r_stmt->execute([$id]);
        $recent_blogs = $r_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $blog = null;
    }
}

if (!$blog) {
    echo "<div class='py-24 text-center min-h-[50vh] flex flex-col items-center justify-center'>
            <div class='w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4'>
                <svg class='w-8 h-8' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>
            </div>
            <h2 class='text-2xl font-bold text-slate-800 mb-2 font-serif-bn'>
                <span data-lang='bn'>ব্লগ বা সংবাদটি পাওয়া যায়নি।</span>
                <span data-lang='en' class='hidden'>Article not found.</span>
            </h2>
            <p class='text-sm text-slate-500 mb-6'>
                <span data-lang='bn'>হয়তো এটি মুছে ফেলা হয়েছে বা লিংকটি ভুল।</span>
                <span data-lang='en' class='hidden'>It may have been removed or the URL is invalid.</span>
            </p>
            <a href='news-and-stories.php' class='cds-btn-primary inline-flex items-center gap-2'>
                <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 19l-7-7m0 0l7-7m-7 7h18'/></svg>
                <span data-lang='bn'>সকল সংবাদ ও গল্পে ফিরে যান</span>
                <span data-lang='en' class='hidden'>Back to News & Stories</span>
            </a>
          </div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$title_bn = !empty($blog['title_bn']) ? $blog['title_bn'] : ($blog['title'] ?? '');
$title_en = !empty($blog['title_en']) ? $blog['title_en'] : $title_bn;

$content_bn = !empty($blog['content_bn']) ? $blog['content_bn'] : ($blog['content'] ?? '');
$content_en = !empty($blog['content_en']) ? $blog['content_en'] : $content_bn;

$category = $blog['category'] ?? 'news';
$cat_label_bn = 'সংবাদ';
$cat_badge_color = 'bg-blue-600';
if ($category === 'blog') {
    $cat_label_bn = 'ব্লগ';
    $cat_badge_color = 'bg-purple-600';
} elseif ($category === 'stories') {
    $cat_label_bn = 'সফলতার গল্প';
    $cat_badge_color = 'bg-emerald-600';
}

$image_src = '';
if (!empty($blog['image_path'])) {
    $image_src = '/' . ltrim($blog['image_path'], '/');
} elseif (!empty($blog['cover_image'])) {
    $image_src = '/uploads/blogs/' . $blog['cover_image'];
} else {
    $image_src = 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
}

$author = !empty($blog['author_name']) ? $blog['author_name'] : 'CDS Team';
$date_val = !empty($blog['published_date']) ? $blog['published_date'] : ($blog['created_at'] ?? 'now');
$date_str = date('d M, Y', strtotime($date_val));
?>

<!-- Page Banner -->
<div class="bg-[#0e1b64] py-14 text-white relative overflow-hidden">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2 <?php echo $cat_badge_color; ?> text-white text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider mb-4">
                <span><?php echo $cat_label_bn; ?></span>
            </div>
            <h1 class="font-serif-bn font-black text-2xl sm:text-4xl lg:text-5xl leading-tight mb-4">
                <span data-lang="bn"><?php echo e($title_bn); ?></span>
                <span data-lang="en" class="hidden"><?php echo e($title_en); ?></span>
            </h1>
            <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-blue-200">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <?php echo $date_str; ?>
                </span>
                <span>&bull;</span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <?php echo e($author); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Main Details Layout -->
<section class="py-12 sm:py-16 bg-slate-50 min-h-[60vh]">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left: Article Content (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm p-6 sm:p-10">
                    
                    <!-- Cover Image -->
                    <?php if (!empty($image_src)): ?>
                    <div class="rounded-2xl overflow-hidden mb-8 border border-slate-100 shadow-inner bg-slate-100 max-h-[480px]">
                        <img src="<?php echo e($image_src); ?>" alt="<?php echo e($title_bn); ?>" class="w-full h-full object-cover">
                    </div>
                    <?php endif; ?>

                    <!-- Body Content -->
                    <div class="text-slate-700 font-sans-bn leading-relaxed text-base sm:text-lg space-y-4">
                        <div data-lang="bn" class="space-y-4">
                            <?php echo nl2br($content_bn); ?>
                        </div>
                        <div data-lang="en" class="hidden space-y-4">
                            <?php echo nl2br($content_en); ?>
                        </div>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="mt-12 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <a href="news-and-stories.php" class="inline-flex items-center gap-2 text-primary font-bold text-sm hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span data-lang="bn">সকল সংবাদ ও গল্পে ফিরে যান</span>
                            <span data-lang="en" class="hidden">Back to All Stories</span>
                        </a>

                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('লিংক কপি করা হয়েছে!');" class="inline-flex items-center gap-1.5 text-xs font-bold px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            <span data-lang="bn">শেয়ার করুন</span>
                            <span data-lang="en" class="hidden">Share Link</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Right: Sidebar (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Recent Stories Box -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="font-serif-bn font-bold text-lg text-[#0e1b64] mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span data-lang="bn">অন্যান্য সংবাদ ও গল্প</span>
                        <span data-lang="en" class="hidden">Recent Stories</span>
                    </h3>

                    <div class="space-y-4">
                        <?php if (!empty($recent_blogs)): ?>
                            <?php foreach ($recent_blogs as $rb): 
                                $rb_title_bn = !empty($rb['title_bn']) ? $rb['title_bn'] : ($rb['title'] ?? '');
                                $rb_title_en = !empty($rb['title_en']) ? $rb['title_en'] : $rb_title_bn;
                                $rb_date = date('d M, Y', strtotime($rb['published_date'] ?? ($rb['created_at'] ?? 'now')));
                            ?>
                                <a href="blog_details.php?id=<?php echo $rb['id']; ?>" class="block group p-3 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                    <span class="text-[11px] font-bold text-primary block mb-1"><?php echo $rb_date; ?></span>
                                    <h4 class="text-sm font-bold text-slate-800 group-hover:text-primary transition-colors line-clamp-2">
                                        <span data-lang="bn"><?php echo e($rb_title_bn); ?></span>
                                        <span data-lang="en" class="hidden"><?php echo e($rb_title_en); ?></span>
                                    </h4>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-xs text-slate-400">আর কোনো সাম্প্রতিক গল্প পাওয়া যায়নি।</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Call to action card -->
                <div class="bg-gradient-to-br from-[#0e1b64] to-[#1e3a8a] text-white rounded-3xl p-6 sm:p-8 shadow-md">
                    <h3 class="font-serif-bn font-bold text-xl mb-2">সিডিএস-এর সদস্য হোন</h3>
                    <p class="text-xs text-blue-200 leading-relaxed mb-5">নাগরিক ক্ষমতায়ন ও সামাজিক উন্নয়নে আমাদের সাথে অংশ নিন।</p>
                    <a href="https://membership.fuminds.com/" target="_blank" class="block w-full text-center py-3 bg-[#3A7D5C] hover:bg-[#2d6248] text-white text-xs font-bold rounded-xl shadow-md transition">
                        অনলাইনে আবেদন করুন &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
