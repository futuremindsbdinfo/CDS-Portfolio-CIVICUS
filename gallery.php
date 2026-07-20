<?php
// gallery.php
require_once 'includes/sanitize.php';

require_once 'includes/db.php';

$db = get_db_connection();
$gallery = [];
if ($db) {
    $gallery = $db->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();
}
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div class="bg-cds-blue py-12 md:py-16 text-white text-center">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">
            <span data-lang="bn">ফটো গ্যালারি</span>
            <span data-lang="en" class="hidden">Photo Gallery</span>
        </h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">
            <span data-lang="bn">আমাদের বিভিন্ন সামাজিক কার্যক্রম ও ইভেন্টের স্মৃতিময় মুহূর্তগুলো।</span>
            <span data-lang="en" class="hidden">Memorable moments of our various social activities and events.</span>
        </p>
    </div>
</div>

<!-- Gallery Section -->
<section class="py-12 md:py-20 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Responsive Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php foreach ($gallery as $photo): ?>
                <?php 
                    $caption_bn = e($photo['caption_bn']); 
                    $caption_en = e($photo['caption_en']); 
                    $img_src = !empty($photo['image_path']) ? 'uploads/gallery/' . e($photo['image_path']) : 'assets/img/gallery/placeholder.jpg';
                ?>
                <a href="<?php echo $img_src; ?>" 
                   class="gallery-item group relative block overflow-hidden rounded-lg shadow-sm hover:shadow-lg transition-all focus:outline-none focus:ring-4 focus:ring-cds-green"
                   data-caption-bn="<?php echo $caption_bn; ?>"
                   data-caption-en="<?php echo $caption_en; ?>">
                    
                    <img src="<?php echo $img_src; ?>" 
                         alt="<?php echo $caption_bn; ?>" 
                         class="w-full h-48 md:h-64 object-cover bg-gray-200 group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    
                    <!-- Overlay text -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                        <h3 class="text-white font-bold text-sm md:text-base mb-1 line-clamp-2">
                            <span data-lang="bn"><?php echo $caption_bn; ?></span>
                            <span data-lang="en" class="hidden"><?php echo $caption_en; ?></span>
                        </h3>
                        <p class="text-gray-300 text-xs font-medium">
                            <span data-lang="bn"><?php echo e(date('d M, Y', strtotime($photo['event_date']))); ?></span>
                            <span data-lang="en" class="hidden"><?php echo e(date('d M, Y', strtotime($photo['event_date']))); ?></span>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php if(empty($gallery)): ?>
                <div class="col-span-2 md:col-span-3 lg:col-span-4 text-center p-8 bg-white rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium">কোনো ছবি পাওয়া যায়নি। / No photos found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Lightbox Modal (Hidden by default) -->
<div id="lightbox" class="fixed inset-0 z-50 bg-black/95 hidden items-center justify-center p-4">
    <button id="lightbox-close" class="absolute top-6 right-6 text-white hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-white rounded">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    
    <div class="max-w-5xl w-full flex flex-col items-center">
        <img id="lightbox-img" src="" alt="Gallery Full Image" class="max-h-[80vh] w-auto object-contain rounded shadow-2xl">
        <div class="mt-4 text-center">
            <p id="lightbox-caption-bn" class="text-white text-lg font-medium" data-lang="bn"></p>
            <p id="lightbox-caption-en" class="text-white text-lg font-medium hidden" data-lang="en"></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
