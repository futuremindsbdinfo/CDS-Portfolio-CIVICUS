<?php
require_once __DIR__ . '/includes/auth.php';
init_secure_session();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';
require_once __DIR__ . '/includes/csrf.php';

$pdo = Database::getConnection();
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

$project_results = [];
$notice_results = [];
$blog_results = [];

if (!empty($query)) {
    $search_term = "%{$query}%";
    
    // Search projects
    $stmt = $pdo->prepare("SELECT id, title_bn, title_en, description_bn, created_at, cover_image, 'project' as type FROM projects WHERE title_bn LIKE ? OR title_en LIKE ? OR description_bn LIKE ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$search_term, $search_term, $search_term]);
    $project_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search notices
    $stmt = $pdo->prepare("SELECT id, title_bn, title_en, description_bn, created_at, 'notice' as type FROM notices WHERE title_bn LIKE ? OR title_en LIKE ? OR description_bn LIKE ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$search_term, $search_term, $search_term]);
    $notice_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search blogs if table exists
    try {
        $stmt = $pdo->prepare("SELECT id, title_bn, title_en, content_bn as description_bn, published_date as created_at, cover_image, 'blog' as type FROM blogs WHERE title_bn LIKE ? OR title_en LIKE ? OR content_bn LIKE ? ORDER BY published_date DESC LIMIT 10");
        $stmt->execute([$search_term, $search_term, $search_term]);
        $blog_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $blog_results = [];
    }
}

$total_results = count($project_results) + count($notice_results) + count($blog_results);

$page_title = 'অনুসন্ধান ফলাফল';
require_once __DIR__ . '/includes/header.php';
?>

<div class="py-12 bg-slate-50 min-h-[65vh]">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Header -->
        <div class="max-w-2xl mb-8">
            <h1 class="font-serif-bn font-black text-3xl sm:text-4xl text-[#0e1b64] mb-3">
                <span data-lang="bn">অনুসন্ধান ফলাফল</span>
                <span data-lang="en" class="hidden">Search Results</span>
            </h1>
            <p class="text-slate-600">
                <?php if (!empty($query)): ?>
                    <span data-lang="bn">"<strong><?php echo htmlspecialchars($query); ?></strong>" এর জন্য <?php echo $total_results; ?> টি ফলাফল পাওয়া গেছে</span>
                    <span data-lang="en" class="hidden">Found <?php echo $total_results; ?> results for "<strong><?php echo htmlspecialchars($query); ?></strong>"</span>
                <?php else: ?>
                    <span data-lang="bn">অনুগ্রহ করে কোনো শব্দ লিখে অনুসন্ধান করুন</span>
                    <span data-lang="en" class="hidden">Please enter a keyword to search</span>
                <?php endif; ?>
            </p>
        </div>

        <!-- Search Bar in Page -->
        <div class="mb-10 max-w-xl">
            <form action="search.php" method="GET" class="flex gap-2">
                <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="এখানে অনুসন্ধান করুন..." required class="flex-grow px-4 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0e1b64]">
                <button type="submit" class="bg-[#0e1b64] hover:bg-[#0345bf] text-white px-6 py-2.5 rounded-lg font-bold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                    <span data-lang="bn">খুঁজুন</span>
                    <span data-lang="en" class="hidden">Search</span>
                </button>
            </form>
        </div>

        <?php if (!empty($query) && $total_results > 0): ?>
            <div class="space-y-10">
                <!-- Projects Results -->
                <?php if (!empty($project_results)): ?>
                    <div>
                        <h2 class="font-serif-bn font-bold text-xl text-[#0e1b64] mb-4 pb-2 border-b border-slate-200">
                            <span data-lang="bn">প্রকল্পসমূহ (<?php echo count($project_results); ?>)</span>
                            <span data-lang="en" class="hidden">Projects (<?php echo count($project_results); ?>)</span>
                        </h2>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($project_results as $p): ?>
                                <a href="projects.php" class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-lg transition block">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-bold px-2 py-0.5 rounded mb-2">Project</span>
                                    <h3 class="font-bold text-[#0e1b64] text-lg mb-2 line-clamp-2"><?php echo htmlspecialchars($p['title_bn']); ?></h3>
                                    <p class="text-xs text-slate-500"><?php echo date('d M, Y', strtotime($p['created_at'])); ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Notices Results -->
                <?php if (!empty($notice_results)): ?>
                    <div>
                        <h2 class="font-serif-bn font-bold text-xl text-[#0e1b64] mb-4 pb-2 border-b border-slate-200">
                            <span data-lang="bn">নোটিশ (<?php echo count($notice_results); ?>)</span>
                            <span data-lang="en" class="hidden">Notices (<?php echo count($notice_results); ?>)</span>
                        </h2>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($notice_results as $n): ?>
                                <a href="notice.php" class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-lg transition block">
                                    <span class="inline-block bg-amber-100 text-amber-800 text-xs font-bold px-2 py-0.5 rounded mb-2">Notice</span>
                                    <h3 class="font-bold text-[#0e1b64] text-lg mb-2 line-clamp-2"><?php echo htmlspecialchars($n['title_bn']); ?></h3>
                                    <p class="text-xs text-slate-500"><?php echo date('d M, Y', strtotime($n['created_at'])); ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Blogs Results -->
                <?php if (!empty($blog_results)): ?>
                    <div>
                        <h2 class="font-serif-bn font-bold text-xl text-[#0e1b64] mb-4 pb-2 border-b border-slate-200">
                            <span data-lang="bn">ব্লগ ও সংবাদ (<?php echo count($blog_results); ?>)</span>
                            <span data-lang="en" class="hidden">Blogs & News (<?php echo count($blog_results); ?>)</span>
                        </h2>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($blog_results as $b): ?>
                                <a href="blog.php" class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-lg transition block">
                                    <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-2 py-0.5 rounded mb-2">Blog</span>
                                    <h3 class="font-bold text-[#0e1b64] text-lg mb-2 line-clamp-2"><?php echo htmlspecialchars($b['title_bn']); ?></h3>
                                    <p class="text-xs text-slate-500"><?php echo date('d M, Y', strtotime($b['created_at'])); ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif (!empty($query)): ?>
            <div class="p-12 text-center bg-white rounded-2xl border border-slate-200 text-slate-500">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                <p class="text-lg font-bold text-slate-700">কোন ফলাফল পাওয়া যায়নি</p>
                <p class="text-sm text-slate-500 mt-1">অন্য কোনো শব্দ দিয়ে পুনরায় চেষ্টা করুন।</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
