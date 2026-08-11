<?php
// gallery.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';

$db = Database::getConnection();
$photos = [];

if ($db) {
    try {
        $photos = $db->query("
            SELECT g.*, p.title_bn as project_title 
            FROM gallery g 
            LEFT JOIN projects p ON g.project_id = p.id 
            ORDER BY g.created_at DESC
        ")->fetchAll();
    } catch (PDOException $e) {
        $photos = [];
    }
}

$page_title = "গ্যালারি (Gallery)";
$meta_description = "সংগঠনের চলমান ও সম্পন্ন কর্মকাণ্ডের কিছু নির্বাচিত মুহূর্ত ও ছবি দেখুন।";
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-warm-grain min-h-screen font-sans-bn text-foreground">
  <div class="bg-secondary px-4 py-12 text-center sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
      <h1 class="font-serif-bn text-3xl font-bold text-white sm:text-4xl lg:text-5xl leading-tight">
          <span data-lang="bn">গ্যালারি</span>
          <span data-lang="en" class="hidden">Gallery</span>
      </h1>
      <p class="mt-4 text-base text-white/80 sm:text-lg">
          <span data-lang="bn">সংগঠনের চলমান ও সম্পন্ন কর্মকাণ্ডের কিছু নির্বাচিত মুহূর্ত।</span>
          <span data-lang="en" class="hidden">Selected moments of the organization's ongoing and completed activities.</span>
      </p>
      <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-sm text-white/80">
        <a href="index.php" class="hover:text-white hover:underline">
            <span data-lang="bn">হোম</span>
            <span data-lang="en" class="hidden">Home</span>
        </a>
        <span class="opacity-50">/</span>
        <span>
            <span data-lang="bn">গ্যালারি</span>
            <span data-lang="en" class="hidden">Gallery</span>
        </span>
      </div>
    </div>
  </div>

  <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    <!-- Masonry-like columns -->
    <?php if (empty($photos)): ?>
      <div class="p-8 text-center text-muted-foreground bg-surface rounded-2xl border border-border shadow-card">
        <span data-lang="bn">কোনো ছবি পাওয়া যায়নি।</span>
        <span data-lang="en" class="hidden">No photos found.</span>
      </div>
    <?php else: ?>
      <div class="columns-2 gap-4 lg:columns-3 xl:columns-4 [&>*]:mb-4 [&>*]:break-inside-avoid">
        <?php foreach ($photos as $photo): 
            $imgSrc = '/uploads/gallery/' . e($photo['image_path']);
        ?>
          <a href="<?php echo $imgSrc; ?>" 
             data-caption-bn="<?php echo e($photo['caption_bn']); ?>"
             data-caption-en="<?php echo e($photo['caption_en']); ?>"
             class="gallery-item group relative block overflow-hidden rounded-2xl border border-border bg-surface shadow-card transition hover:-translate-y-0.5 hover:shadow-card-hover">
            <img src="<?php echo $imgSrc; ?>" alt="<?php echo e($photo['caption_bn']); ?>" class="block w-full h-auto transition-transform duration-500 group-hover:scale-105" loading="lazy">
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                <p class="text-white font-serif-bn font-medium text-sm lg:text-base line-clamp-2">
                    <span data-lang="bn"><?php echo e($photo['caption_bn']); ?></span>
                    <span data-lang="en" class="hidden"><?php echo !empty($photo['caption_en']) ? e($photo['caption_en']) : e($photo['caption_bn']); ?></span>
                </p>
            </div>
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
      <div class="bg-white p-4 text-center border-t border-slate-100">
        <p id="lightbox-caption" class="font-serif-bn text-lg text-slate-800"></p>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const items = Array.from(document.querySelectorAll('.gallery-item'));
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxCaption = document.getElementById('lightbox-caption');
        const closeBtn = document.getElementById('lightbox-close');
        const prevBtn = document.getElementById('lightbox-prev');
        const nextBtn = document.getElementById('lightbox-next');
        let currentIndex = 0;

        function openLightbox(index) {
            if (index < 0 || index >= items.length) return;
            currentIndex = index;
            const item = items[currentIndex];
            lightboxImg.src = item.href;
            
            const currentLang = document.documentElement.lang || 'bn';
            const hiddenBn = currentLang === 'bn' ? '' : 'hidden';
            const hiddenEn = currentLang === 'en' ? '' : 'hidden';
            
            lightboxCaption.innerHTML = `
                <span data-lang="bn" class="${hiddenBn}">${item.getAttribute('data-caption-bn') || ''}</span>
                <span data-lang="en" class="${hiddenEn}">${item.getAttribute('data-caption-en') || item.getAttribute('data-caption-bn') || ''}</span>
            `;
            
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            document.body.style.overflow = '';
        }

        items.forEach((item, index) => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                openLightbox(index);
            });
        });

        closeBtn.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', closeLightbox);

        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            openLightbox((currentIndex - 1 + items.length) % items.length);
        });

        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            openLightbox((currentIndex + 1) % items.length);
        });
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
