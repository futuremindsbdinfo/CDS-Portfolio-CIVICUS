<?php 
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/sanitize.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db = Database::getConnection();

$blog = null;
if ($db && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
    $blog = $stmt->fetch();
}

if (!$blog) {
    echo "<div class='py-20 text-center'><h2 class='text-2xl font-bold text-gray-700'>ব্লগটি পাওয়া যায়নি।</h2><a href='blog.php' class='text-primary hover:underline mt-4 inline-block'>ফিরে যান</a></div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<!-- Page Header -->
<div class="bg-cds-blue py-12 text-white text-center">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4 max-w-4xl mx-auto leading-tight">
            <?php echo e($blog['title']); ?>
        </h1>
        <div class="flex items-center justify-center gap-4 text-sm text-gray-300">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <?php echo $blog['published_date'] ? date('d M Y', strtotime($blog['published_date'])) : date('d M Y', strtotime($blog['created_at'])); ?>
            </span>
        </div>
    </div>
</div>

<!-- Blog Content Section -->
<section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <?php if($blog['cover_image'] && file_exists(__DIR__ . '/uploads/blogs/' . $blog['cover_image'])): ?>
            <div class="w-full max-h-[500px] overflow-hidden">
                <img src="uploads/blogs/<?php echo e($blog['cover_image']); ?>" alt="<?php echo e($blog['title']); ?>" class="w-full h-full object-cover">
            </div>
            <?php endif; ?>

            <div class="p-6 md:p-10 lg:p-12">
                <div class="prose prose-lg max-w-none text-gray-700 font-sans-bn leading-relaxed">
                    <?php 
                    // Use nl2br for basic formatting since we used textarea.
                    // If true rich text was used, this should be output directly (with purifier if needed).
                    echo nl2br($blog['content']); 
                    ?>
                </div>

                <div class="mt-12 pt-8 border-t border-gray-100 flex justify-between items-center">
                    <a href="blog.php" class="inline-flex items-center gap-2 text-primary font-semibold hover:text-cds-blue transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path></svg>
                        সব ব্লগে ফিরে যান
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
