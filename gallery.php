<?php
// gallery.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';

$pdo = Database::getConnection();

// Handle Pagination
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$perPage = 12; // Show 12 images per page for gallery
$offset = ($page - 1) * $perPage;

// Handle Project Filter
$projectId = isset($_GET['project_id']) && is_numeric($_GET['project_id']) ? (int)$_GET['project_id'] : null;

// Build query
$countQuery = "SELECT COUNT(*) FROM gallery";
$dataQuery = "
    SELECT g.*, p.title_bn as project_title 
    FROM gallery g
    LEFT JOIN projects p ON g.project_id = p.id
";
$params = [];

if ($projectId) {
    $countQuery .= " WHERE project_id = :project_id";
    $dataQuery .= " WHERE g.project_id = :project_id";
    $params[':project_id'] = $projectId;
}

$dataQuery .= " ORDER BY g.created_at DESC LIMIT :limit OFFSET :offset";

// Get total count
$countStmt = $pdo->prepare($countQuery);
if ($projectId) {
    $countStmt->bindValue(':project_id', $projectId, PDO::PARAM_INT);
}
$countStmt->execute();
$totalPhotos = $countStmt->fetchColumn();
$totalPages = ceil($totalPhotos / $perPage);

// Fetch paginated gallery
$stmt = $pdo->prepare($dataQuery);
if ($projectId) {
    $stmt->bindValue(':project_id', $projectId, PDO::PARAM_INT);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch projects for filter tabs
$projectsStmt = $pdo->query("SELECT id, title_bn FROM projects ORDER BY title_bn ASC");
$projects = $projectsStmt->fetchAll(PDO::FETCH_ASSOC);

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
    <!-- Filter tabs -->
    <div class="mb-8 flex flex-wrap gap-2">
      <a href="/gallery.php" class="rounded-full border px-4 py-2 text-sm font-medium transition <?php echo !$projectId ? 'border-primary bg-primary text-primary-foreground shadow-card' : 'border-border bg-surface text-foreground/80 hover:border-primary/40 hover:text-primary'; ?>">
        সব
      </a>
      <?php foreach ($projects as $p): ?>
        <a href="/gallery.php?project_id=<?php echo $p['id']; ?>" class="rounded-full border px-4 py-2 text-sm font-medium transition <?php echo $projectId == $p['id'] ? 'border-primary bg-primary text-primary-foreground shadow-card' : 'border-border bg-surface text-foreground/80 hover:border-primary/40 hover:text-primary'; ?>">
          <?php echo e($p['title_bn']); ?>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Masonry-like columns -->
    <?php if (empty($photos)): ?>
      <div class="p-8 text-center text-muted-foreground bg-surface rounded-2xl border border-border shadow-card">
        কোনো ছবি পাওয়া যায়নি।
      </div>
    <?php else: ?>
      <div class="columns-2 gap-4 lg:columns-3 xl:columns-4 [&>*]:mb-4 [&>*]:break-inside-avoid">
        <?php foreach ($photos as $photo): 
            $img_path = 'uploads/gallery/' . $photo['image_path'];
            $has_image = !empty($photo['image_path']) && file_exists(__DIR__ . '/' . $img_path);
            $imgSrc = $has_image ? '/' . e($img_path) : '/assets/img/gallery/placeholder.jpg';
            $captionBn = e($photo['caption_bn']);
            $captionEn = e($photo['caption_en']);
            $date = $photo['event_date'] ? date('d M, Y', strtotime($photo['event_date'])) : date('d M, Y', strtotime($photo['created_at']));
            $projectTitle = $photo['project_title'] ? e($photo['project_title']) : 'সাধারণ';
            
            // Randomly assign a class for masonry look if desired, or let standard sizing handle it.
            $ratios = ['aspect-[3/4]', 'aspect-[4/3]', 'aspect-square'];
            $ratioClass = $ratios[array_rand($ratios)];
        ?>
          <a href="<?php echo $imgSrc; ?>" 
             data-caption-bn="<?php echo $captionBn; ?>" 
             data-caption-en="<?php echo $captionEn; ?>"
             data-project="<?php echo $projectTitle; ?>"
             data-date="<?php echo $date; ?>"
             class="gallery-item group relative block overflow-hidden rounded-2xl border border-border bg-surface shadow-card transition hover:-translate-y-0.5 hover:shadow-card-hover <?php echo $ratioClass; ?>">
            <img src="<?php echo $imgSrc; ?>" alt="<?php echo $captionBn; ?>" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
            <span class="absolute left-3 top-3 rounded-full bg-black/40 px-2.5 py-1 text-[10px] font-semibold text-white backdrop-blur">
              <?php echo $date; ?>
            </span>
            <div class="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-black/80 to-transparent p-4 text-left transition group-hover:translate-y-0">
              <div class="text-xs font-medium text-white/70"><?php echo $projectTitle; ?></div>
              <div class="mt-0.5 font-serif-bn text-sm font-semibold text-white"><?php echo $captionBn; ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <div class="mt-10 flex items-center justify-center gap-2">
        <?php 
        $queryStr = $projectId ? "&project_id=" . $projectId : ""; 
        ?>
        <a href="<?php echo $page > 1 ? '?page=' . ($page - 1) . $queryStr : '#'; ?>" class="grid h-9 w-9 place-items-center rounded-full border border-border bg-surface text-sm <?php echo $page == 1 ? 'opacity-40 pointer-events-none' : ''; ?>">‹</a>
        
        <?php for($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?php echo $i . $queryStr; ?>" class="grid h-9 min-w-[2.25rem] place-items-center rounded-full px-3 text-sm font-semibold transition <?php echo $i == $page ? 'bg-primary text-primary-foreground shadow-card' : 'border border-border bg-surface hover:bg-primary-soft hover:text-primary'; ?>">
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>
        
        <a href="<?php echo $page < $totalPages ? '?page=' . ($page + 1) . $queryStr : '#'; ?>" class="grid h-9 w-9 place-items-center rounded-full border border-border bg-surface text-sm <?php echo $page == $totalPages ? 'opacity-40 pointer-events-none' : ''; ?>">›</a>
      </div>
    <?php endif; ?>
  </section>

  <!-- Lightbox Modal -->
  <div id="lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/85 p-4 backdrop-blur-sm">
    <button id="lightbox-close" aria-label="Close" class="absolute right-4 top-4 grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M6 6l12 12M18 6L6 18" /></svg>
    </button>
    <div class="relative flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-surface shadow-card-hover" onclick="event.stopPropagation()">
      <div class="relative w-full overflow-hidden bg-black flex justify-center items-center" style="height: 70vh;">
        <img id="lightbox-img" src="" alt="Gallery Image" class="max-w-full max-h-full object-contain">
      </div>
      <div class="flex flex-wrap items-center justify-between gap-3 border-t border-border p-5 bg-surface">
        <div class="min-w-0">
          <div id="lightbox-project" class="text-xs font-semibold uppercase tracking-widest text-primary"></div>
          <div id="lightbox-caption-bn" class="mt-1 font-serif-bn text-lg font-bold"></div>
          <div id="lightbox-caption-en" class="hidden"></div>
        </div>
        <span id="lightbox-date" class="rounded-full bg-primary-soft px-3 py-1 text-xs font-semibold text-primary"></span>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
