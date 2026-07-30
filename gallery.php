<?php
// gallery.php
require_once __DIR__ . '/includes/sanitize.php';

// Directory containing gallery images
$galleryDir = __DIR__ . '/assets/img/gallery/';
$imageFiles = [];

if (is_dir($galleryDir)) {
    // Scan directory for image files
    $files = scandir($galleryDir);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            // Sort to ensure consistent order, or we can just append
            $imageFiles[] = $file;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-warm-grain min-h-screen font-sans-bn text-foreground">
  <div class="bg-secondary px-4 py-12 text-center sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
      <h1 class="font-serif-bn text-3xl font-bold text-white sm:text-4xl lg:text-5xl leading-tight">গ্যালারি</h1>
      <p class="mt-4 text-base text-white/80 sm:text-lg">সংগঠনের চলমান ও সম্পন্ন কর্মকাণ্ডের কিছু নির্বাচিত মুহূর্ত।</p>
      <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-sm text-white/80">
        <a href="/index.php" class="hover:text-white hover:underline">হোম</a>
        <span class="opacity-50">/</span>
        <span>গ্যালারি</span>
      </div>
    </div>
  </div>

  <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    <!-- Masonry-like columns -->
    <?php if (empty($imageFiles)): ?>
      <div class="p-8 text-center text-muted-foreground bg-surface rounded-2xl border border-border shadow-card">
        কোনো ছবি পাওয়া যায়নি।
      </div>
    <?php else: ?>
      <div class="columns-2 gap-4 lg:columns-3 xl:columns-4 [&>*]:mb-4 [&>*]:break-inside-avoid">
        <?php foreach ($imageFiles as $filename): 
            $imgSrc = '/assets/img/gallery/' . e($filename);
        ?>
          <a href="<?php echo $imgSrc; ?>" 
             class="gallery-item group relative block overflow-hidden rounded-2xl border border-border bg-surface shadow-card transition hover:-translate-y-0.5 hover:shadow-card-hover">
            <img src="<?php echo $imgSrc; ?>" alt="Gallery Image" class="block w-full h-auto transition-transform duration-500 group-hover:scale-105" loading="lazy">
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </section>

  <!-- Lightbox Modal -->
  <div id="lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/85 p-4 backdrop-blur-sm">
    <button id="lightbox-close" aria-label="Close" class="absolute right-4 top-4 grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M6 6l12 12M18 6L6 18" /></svg>
    </button>
    
    <button id="lightbox-prev" aria-label="Previous" class="absolute left-4 top-1/2 -translate-y-1/2 grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20 z-10 hidden sm:grid">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6"><path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" /></svg>
    </button>
    
    <button id="lightbox-next" aria-label="Next" class="absolute right-4 top-1/2 -translate-y-1/2 grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20 z-10 hidden sm:grid">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-6 w-6"><path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round" /></svg>
    </button>

    <div class="relative flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-surface shadow-card-hover" onclick="event.stopPropagation()">
      <div class="relative w-full overflow-hidden bg-black flex justify-center items-center" style="height: 70vh;">
        <img id="lightbox-img" src="" alt="Gallery Image" class="max-w-full max-h-full object-contain">
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
