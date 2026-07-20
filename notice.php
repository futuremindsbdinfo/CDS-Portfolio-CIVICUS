<?php
// notice.php
require_once 'includes/sanitize.php';

require_once 'includes/db.php';

$db = get_db_connection();
$notices = [];
if ($db) {
    $notices = $db->query("SELECT * FROM notices ORDER BY published_at DESC")->fetchAll();
}
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div class="bg-yellow-500 py-12 md:py-16 text-cds-blue text-center">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">
            <span data-lang="bn">নোটিশ বোর্ড</span>
            <span data-lang="en" class="hidden">Notice Board</span>
        </h1>
        <p class="text-lg text-blue-900 max-w-2xl mx-auto font-medium">
            <span data-lang="bn">সোসাইটির লেটেস্ট আপডেট, ঘোষণা এবং ইভেন্টের নোটিশ।</span>
            <span data-lang="en" class="hidden">Latest updates, announcements, and event notices of the society.</span>
        </p>
    </div>
</div>

<!-- Notices Section -->
<section class="py-12 md:py-20 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="space-y-6">
            <?php foreach ($notices as $notice): ?>
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                        <h2 class="text-xl md:text-2xl font-bold text-cds-blue">
                            <span data-lang="bn"><?php echo e($notice['title_bn']); ?></span>
                            <span data-lang="en" class="hidden"><?php echo e($notice['title_en']); ?></span>
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-cds-blue whitespace-nowrap">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span data-lang="bn"><?php echo e(date('d M, Y', strtotime($notice['published_at']))); ?></span>
                            <span data-lang="en" class="hidden"><?php echo e(date('d M, Y', strtotime($notice['published_at']))); ?></span>
                        </span>
                    </div>
                    
                    <p class="text-gray-700 leading-relaxed mb-6">
                        <span data-lang="bn"><?php echo nl2br(e($notice['content_bn'])); ?></span>
                        <span data-lang="en" class="hidden"><?php echo nl2br(e($notice['content_en'])); ?></span>
                    </p>

                    <?php if (!empty($notice['file_path'])): ?>
                        <a href="<?php echo e($notice['file_path']); ?>" class="inline-flex items-center text-cds-green font-bold hover:text-green-800 transition-colors focus:outline-none focus:underline" target="_blank">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span data-lang="bn">ফাইল ডাউনলোড করুন</span>
                            <span data-lang="en" class="hidden">Download File</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php if(empty($notices)): ?>
                <div class="bg-white p-8 rounded-lg shadow-sm text-center">
                    <p class="text-gray-500 font-medium">কোনো নোটিশ পাওয়া যায়নি। / No notices found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
