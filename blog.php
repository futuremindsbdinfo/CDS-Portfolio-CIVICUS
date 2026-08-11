<?php 
require_once 'includes/db.php';
require_once 'includes/sanitize.php';

$db = Database::getConnection();
$blogs = [];
if ($db) {
    try {
        $blogs = $db->query("SELECT * FROM blogs ORDER BY published_date DESC, created_at DESC")->fetchAll();
    } catch (PDOException $e) {
        $blogs = [];
    }
}

$page_title = "নিউজ ও ব্লগ (News & Blog)";
$meta_description = "সিডিএস এর সাম্প্রতিক খবরাখবর এবং গঠনমূলক প্রবন্ধসমূহ পড়ুন।";
require_once 'includes/header.php';
?>

<!-- Page Header -->
<div class="bg-cds-blue py-12 md:py-16 text-white text-center">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">
            <span data-lang="bn">নিউজ ও ব্লগ</span>
            <span data-lang="en" class="hidden">News & Blog</span>
        </h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">
            <span data-lang="bn">সিডিএস এর সাম্প্রতিক খবরাখবর এবং গঠনমূলক প্রবন্ধসমূহ পড়ুন।</span>
            <span data-lang="en" class="hidden">Read the latest news and constructive articles from CDS.</span>
        </p>
    </div>
</div>

<!-- Blog Section -->
<section class="py-16 bg-white min-h-[50vh]">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            
            <?php if(empty($blogs)): ?>
                <div class="col-span-full text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium">
                        <span data-lang="bn">কোনো ব্লগ পাওয়া যায়নি।</span>
                        <span data-lang="en" class="hidden">No blogs found.</span>
                    </p>
                </div>
            <?php else: ?>
                <?php foreach($blogs as $blog): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden transition-all hover:shadow-md group flex flex-col">
                    <div class="h-48 bg-gray-200 overflow-hidden relative">
                        <?php if($blog['cover_image'] && file_exists(__DIR__ . '/uploads/blogs/' . $blog['cover_image'])): ?>
                            <img src="uploads/blogs/<?php echo e($blog['cover_image']); ?>" alt="<?php echo e($blog['title']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center text-xs text-primary mb-3 font-semibold">
                            <span><?php echo $blog['published_date'] ? date('d M Y', strtotime($blog['published_date'])) : date('d M Y', strtotime($blog['created_at'])); ?></span>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors line-clamp-2">
                            <span data-lang="bn"><?php echo e($blog['title']); ?></span>
                            <span data-lang="en" class="hidden"><?php echo !empty($blog['title_en']) ? e($blog['title_en']) : e($blog['title']); ?></span>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">
                            <span data-lang="bn"><?php echo strip_tags($blog['content']); ?></span>
                            <span data-lang="en" class="hidden"><?php echo !empty($blog['content_en']) ? strip_tags($blog['content_en']) : strip_tags($blog['content']); ?></span>
                        </p>
                        <a href="blog_details.php?id=<?php echo $blog['id']; ?>" class="text-primary font-medium text-sm hover:underline flex items-center gap-1 mt-auto">
                            <span data-lang="bn">বিস্তারিত পড়ুন</span>
                            <span data-lang="en" class="hidden">Read more</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
