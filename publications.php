<?php 
require_once 'includes/db.php';
require_once 'includes/sanitize.php';
include 'includes/header.php'; 

// Fetch publications
$db = Database::getConnection();
$publications = [];
if ($db) {
    $publications = $db->query("SELECT * FROM publications ORDER BY created_at DESC")->fetchAll();
}
?>

<!-- Page Header -->
<div class="bg-cds-blue py-12 md:py-16 text-white text-center">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">
            <span data-lang="bn">প্রকাশনা ও ম্যাগাজিন</span>
            <span data-lang="en" class="hidden">Publications & Magazine</span>
        </h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">
            <span data-lang="bn">সিডিএস এর নিজস্ব প্রকাশনা, গবেষণা রিপোর্ট এবং ম্যাগাজিনগুলো সংগ্রহ করুন।</span>
            <span data-lang="en" class="hidden">Access CDS's own publications, research reports, and magazines.</span>
        </p>
    </div>
</div>

<!-- Publications Section -->
<section class="py-16 bg-white min-h-[50vh]">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <?php if (empty($publications)): ?>
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <h3 class="text-xl font-serif font-bold text-gray-800 mb-2">কোনো প্রকাশনা পাওয়া যায়নি</h3>
                <p class="text-gray-500">শীঘ্রই নতুন প্রকাশনা যুক্ত করা হবে।</p>
            </div>
        <?php else: ?>
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($publications as $pub): 
                    // Calculate file size if file exists
                    $file_size = "Unknown";
                    $file_path = __DIR__ . '/uploads/publications/' . $pub['file_path'];
                    if ($pub['file_path'] && file_exists($file_path)) {
                        $bytes = filesize($file_path);
                        $file_size = number_format($bytes / 1048576, 2) . ' MB';
                    }
                ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col transition-all hover:-translate-y-1 hover:shadow-lg">
                    <div class="h-56 bg-gray-100 flex items-center justify-center p-4 border-b border-gray-100 relative overflow-hidden">
                        <?php if($pub['cover_image'] && file_exists(__DIR__ . '/uploads/publications/' . $pub['cover_image'])): ?>
                            <img src="uploads/publications/<?php echo e($pub['cover_image']); ?>" alt="<?php echo e($pub['title']); ?>" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 px-2 py-1 bg-white/90 text-primary text-[10px] font-bold uppercase tracking-widest rounded shadow-sm">
                                <?php echo e($pub['type']); ?>
                            </span>
                        <?php else: ?>
                            <div class="w-32 h-44 bg-gray-300 shadow-md flex flex-col items-center justify-center text-center p-2 rounded-sm relative">
                                <span class="absolute top-2 left-2 right-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-tight">
                                    <?php echo e($pub['type']); ?>
                                </span>
                                <div class="w-full h-px bg-gray-400 mt-6 mb-2"></div>
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <div class="w-full h-px bg-gray-400 mt-2"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-serif font-bold text-gray-900 mb-2"><?php echo e($pub['title']); ?></h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">
                            <?php echo e($pub['description']); ?>
                        </p>
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
                            <span class="text-xs font-medium text-gray-500">PDF • <?php echo $file_size; ?></span>
                            <?php if ($pub['file_path']): ?>
                            <a href="uploads/publications/<?php echo e($pub['file_path']); ?>" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded bg-primary-soft text-primary text-sm font-semibold hover:bg-primary hover:text-white transition-colors" target="_blank">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                ডাউনলোড
                            </a>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">ফাইল নেই</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
