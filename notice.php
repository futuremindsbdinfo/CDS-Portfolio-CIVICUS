<?php
// notice.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';

$pdo = Database::getConnection();

// Handle Pagination
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// Get total count
$countStmt = $pdo->query("SELECT COUNT(*) FROM notices");
$totalNotices = $countStmt->fetchColumn();
$totalPages = ceil($totalNotices / $perPage);

// Fetch paginated notices
$stmt = $pdo->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$paged = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Single Notice View
$active = null;
$related_notices = [];
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $activeId = (int)$_GET['id'];
    $activeStmt = $pdo->prepare("SELECT * FROM notices WHERE id = :id");
    $activeStmt->bindValue(':id', $activeId, PDO::PARAM_INT);
    $activeStmt->execute();
    $active = $activeStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($active) {
        $relatedStmt = $pdo->prepare("SELECT * FROM notices WHERE id != :id ORDER BY created_at DESC LIMIT 3");
        $relatedStmt->bindValue(':id', $active['id'], PDO::PARAM_INT);
        $relatedStmt->execute();
        $related_notices = $relatedStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Fetch recent notices for sidebar
$recentStmt = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC LIMIT 5");
$recent_notices = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "নোটিশ বোর্ড (Notice Board)";
$meta_description = "সিডিএস এর যাবতীয় জরুরি নোটিশ ও বিজ্ঞপ্তিগুলো এখান থেকে দেখুন।";
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-warm-grain min-h-screen font-sans-bn text-foreground">
  <?php if ($active): ?>
    <?php 
        $active_title = get_bilingual_title($active);
        $active_content = get_bilingual_content($active);
    ?>
    <!-- Single Notice View -->
    <div class="bg-secondary px-4 py-12 text-center sm:px-6 lg:px-8">
      <div class="mx-auto max-w-3xl">
        <h1 class="font-serif-bn text-3xl font-bold text-white sm:text-4xl lg:text-5xl leading-tight">
          <span data-lang="bn"><?php echo e($active_title['bn']); ?></span>
          <span data-lang="en" class="hidden"><?php echo e($active_title['en']); ?></span>
        </h1>
        <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-sm text-white/80">
          <a href="index.php" class="hover:text-white hover:underline">
              <span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span>
          </a>
          <span class="opacity-50">/</span>
          <a href="notice.php" class="hover:text-white hover:underline">
              <span data-lang="bn">নোটিশ বোর্ড</span><span data-lang="en" class="hidden">Notice Board</span>
          </a>
          <span class="opacity-50">/</span>
          <span>
              <span data-lang="bn"><?php echo mb_substr(e($active_title['bn']), 0, 30) . '...'; ?></span>
              <span data-lang="en" class="hidden"><?php echo mb_substr(e($active_title['en']), 0, 30) . '...'; ?></span>
          </span>
        </div>
      </div>
    </div>

    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_320px] lg:px-8">
      <article>
        <div class="flex flex-wrap items-center gap-2 text-xs">
          <span class="rounded-full bg-primary-soft px-2.5 py-1 font-semibold text-primary">
            <span data-lang="bn">প্রকাশিত: <?php echo date('d M, Y', strtotime($active['created_at'])); ?></span>
            <span data-lang="en" class="hidden">Published: <?php echo date('d M, Y', strtotime($active['created_at'])); ?></span>
          </span>
          <?php if (!empty($active['file_path'])): ?>
            <span class="inline-flex items-center gap-1 rounded-full border border-warning/40 bg-warning/15 px-2 py-0.5 text-[10px] font-semibold text-warning-foreground">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M14 3H6v18h12V7z M14 3v4h4" stroke-linejoin="round" /></svg>
              <span data-lang="bn">PDF সংযুক্ত</span>
              <span data-lang="en" class="hidden">PDF Attached</span>
            </span>
          <?php endif; ?>
        </div>
        
        <div class="mt-6 rounded-2xl border border-border bg-surface p-6 shadow-card sm:p-8">
          <div class="font-serif-bn text-base leading-[1.9] text-foreground sm:text-lg whitespace-pre-wrap">
            <span data-lang="bn"><?php echo e($active_content['bn']); ?></span>
            <span data-lang="en" class="hidden"><?php echo e($active_content['en']); ?></span>
          </div>
          
          <?php if (!empty($active['file_path'])): ?>
            <div class="mt-8 flex flex-wrap items-center gap-4 rounded-2xl border border-primary/20 bg-primary-soft/40 p-4">
              <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-white text-primary shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M14 3H6v18h12V7z M14 3v4h4" stroke-linejoin="round" /><text x="12" y="17" text-anchor="middle" font-size="5" font-weight="700" fill="currentColor" stroke="none">PDF</text></svg>
              </span>
              <div class="min-w-0 flex-1">
                <div class="truncate font-serif-bn text-sm font-bold"><?php echo basename($active['file_path']); ?></div>
                <div class="text-xs text-muted-foreground"><span data-lang="bn">PDF নথি</span><span data-lang="en" class="hidden">PDF Document</span></div>
              </div>
              <div class="flex gap-2">
                <a href="<?php echo e($active['file_path']); ?>" target="_blank" class="inline-flex items-center gap-1.5 rounded-full border border-border bg-surface px-4 py-2 text-xs font-semibold hover:bg-primary-soft hover:text-primary">
                  <span data-lang="bn">দেখুন</span><span data-lang="en" class="hidden">View</span>
                </a>
                <a href="<?php echo e($active['file_path']); ?>" download class="inline-flex items-center gap-1.5 rounded-full bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground shadow-card hover:brightness-110">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5"><path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke-linecap="round" stroke-linejoin="round" /></svg>
                  <span data-lang="bn">ডাউনলোড</span><span data-lang="en" class="hidden">Download</span>
                </a>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <a href="notice.php" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M11 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round" /></svg>
          <span data-lang="bn">সব নোটিশে ফিরে যান</span><span data-lang="en" class="hidden">Back to all notices</span>
        </a>

        <?php if(!empty($related_notices)): ?>
        <div class="mt-12">
          <h3 class="font-serif-bn text-xl font-bold border-b border-border pb-3 mb-6">
              <span data-lang="bn">সাম্প্রতিক অন্যান্য নোটিশ</span>
              <span data-lang="en" class="hidden">Other Recent Notices</span>
          </h3>
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach($related_notices as $rn): ?>
            <?php $rn_title = get_bilingual_title($rn); ?>
            <a href="notice.php?id=<?php echo $rn['id']; ?>" class="group block rounded-2xl border border-border bg-surface p-4 shadow-card transition hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-card-hover">
              <div class="text-[10px] font-semibold text-primary bg-primary-soft w-max px-2 py-0.5 rounded-full mb-2"><?php echo date('d M, Y', strtotime($rn['created_at'])); ?></div>
              <h4 class="font-serif-bn text-sm font-bold leading-snug group-hover:text-primary">
                  <span data-lang="bn"><?php echo e($rn_title['bn']); ?></span>
                  <span data-lang="en" class="hidden"><?php echo e($rn_title['en']); ?></span>
              </h4>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </article>

      <!-- Sidebar -->
      <aside class="space-y-6 lg:sticky lg:top-24 lg:h-fit">
        <div class="rounded-2xl border border-border bg-surface p-5 shadow-card">
          <div class="flex items-center gap-2 border-b border-border pb-3">
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-primary-soft text-primary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M6 3h9l5 5v13H6z" stroke-linejoin="round" /></svg>
            </span>
            <h3 class="font-serif-bn text-base font-bold">
                <span data-lang="bn">সাম্প্রতিক নোটিশ</span><span data-lang="en" class="hidden">Recent Notices</span>
            </h3>
          </div>
          <ul class="mt-3 space-y-3">
            <?php foreach($recent_notices as $rn): ?>
              <?php $rn_title = get_bilingual_title($rn); ?>
              <li>
                <a href="notice.php?id=<?php echo $rn['id']; ?>" class="block w-full text-left transition <?php echo (isset($active) && $active['id'] == $rn['id']) ? 'text-primary' : 'text-foreground/85 hover:text-primary'; ?>">
                  <div class="text-xs font-semibold text-primary"><?php echo date('d M, Y', strtotime($rn['created_at'])); ?></div>
                  <div class="mt-1 font-serif-bn text-sm font-bold leading-snug">
                      <span data-lang="bn"><?php echo e($rn_title['bn']); ?></span>
                      <span data-lang="en" class="hidden"><?php echo e($rn_title['en']); ?></span>
                  </div>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        
        <div class="rounded-2xl border border-border bg-surface p-5 shadow-card">
          <div class="flex items-center gap-2 border-b border-border pb-3">
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-primary-soft text-primary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </span>
            <h3 class="font-serif-bn text-base font-bold">
                <span data-lang="bn">গুরুত্বপূর্ণ লিংক</span><span data-lang="en" class="hidden">Important Links</span>
            </h3>
          </div>
          <ul class="mt-3 space-y-2">
            <li><a href="#" class="flex items-center gap-2 text-sm font-serif-bn font-medium text-foreground/85 transition hover:text-primary"><span class="h-1.5 w-1.5 rounded-full bg-primary/40"></span><span data-lang="bn">বার্ষিক প্রতিবেদন ২০২৫</span><span data-lang="en" class="hidden">Annual Report 2025</span></a></li>
            <li><a href="#" class="flex items-center gap-2 text-sm font-serif-bn font-medium text-foreground/85 transition hover:text-primary"><span class="h-1.5 w-1.5 rounded-full bg-primary/40"></span><span data-lang="bn">সাংগঠনিক গঠনতন্ত্র</span><span data-lang="en" class="hidden">Organizational Constitution</span></a></li>
            <li><a href="#" class="flex items-center gap-2 text-sm font-serif-bn font-medium text-foreground/85 transition hover:text-primary"><span class="h-1.5 w-1.5 rounded-full bg-primary/40"></span><span data-lang="bn">স্বেচ্ছাসেবক নীতিমালা</span><span data-lang="en" class="hidden">Volunteer Policy</span></a></li>
            <li><a href="#" class="flex items-center gap-2 text-sm font-serif-bn font-medium text-foreground/85 transition hover:text-primary"><span class="h-1.5 w-1.5 rounded-full bg-primary/40"></span><span data-lang="bn">স্বচ্ছতা ও নিরীক্ষা</span><span data-lang="en" class="hidden">Transparency & Audit</span></a></li>
          </ul>
        </div>
      </aside>
    </section>

  <?php else: ?>
    <!-- List View -->
    <div class="bg-secondary px-4 py-12 text-center sm:px-6 lg:px-8">
      <div class="mx-auto max-w-3xl">
        <h1 class="font-serif-bn text-3xl font-bold text-white sm:text-4xl lg:text-5xl leading-tight">
            <span data-lang="bn">নোটিশ বোর্ড</span><span data-lang="en" class="hidden">Notice Board</span>
        </h1>
        <p class="mt-4 text-base text-white/80 sm:text-lg">
            <span data-lang="bn">সাংগঠনিক ঘোষণা, পরিপত্র, নিয়োগ ও প্রতিবেদনসমূহ এক জায়গায়।</span>
            <span data-lang="en" class="hidden">Organizational announcements, circulars, recruitments, and reports in one place.</span>
        </p>
        <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-sm text-white/80">
          <a href="index.php" class="hover:text-white hover:underline">
              <span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span>
          </a>
          <span class="opacity-50">/</span>
          <span>
              <span data-lang="bn">নোটিশ বোর্ড</span><span data-lang="en" class="hidden">Notice Board</span>
          </span>
        </div>
      </div>
    </div>

    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_320px] lg:px-8">
      <div class="space-y-4">
        <?php foreach ($paged as $n): ?>
          <?php 
              $n_title = get_bilingual_title($n);
              $n_content = get_bilingual_content($n);
          ?>
          <article class="group rounded-2xl border border-border bg-surface p-5 shadow-card transition hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-card-hover sm:p-6">
            <div class="flex flex-wrap items-center gap-2 text-xs">
              <span class="rounded-full bg-primary-soft px-2.5 py-1 font-semibold text-primary">
                <?php echo date('d M, Y', strtotime($n['created_at'])); ?>
              </span>
              <?php if (!empty($n['file_path'])): ?>
                <span class="inline-flex items-center gap-1 rounded-full border border-warning/40 bg-warning/15 px-2 py-0.5 text-[10px] font-semibold text-warning-foreground">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M14 3H6v18h12V7z M14 3v4h4" stroke-linejoin="round" /></svg>
                  <span data-lang="bn">PDF সংযুক্ত</span>
                  <span data-lang="en" class="hidden">PDF Attached</span>
                </span>
              <?php endif; ?>
            </div>
            <h3 class="mt-3 font-serif-bn text-lg font-bold leading-snug group-hover:text-primary sm:text-xl">
              <span data-lang="bn"><?php echo e($n_title['bn']); ?></span>
              <span data-lang="en" class="hidden"><?php echo e($n_title['en']); ?></span>
            </h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">
                <span data-lang="bn"><?php echo mb_substr(e($n_content['bn']), 0, 120) . '...'; ?></span>
                <span data-lang="en" class="hidden"><?php echo mb_substr(e($n_content['en']), 0, 120) . '...'; ?></span>
            </p>
            <a href="notice.php?id=<?php echo $n['id']; ?>" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:underline">
              <span data-lang="bn">বিস্তারিত দেখুন</span><span data-lang="en" class="hidden">Read more</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
          </article>
        <?php endforeach; ?>

        <?php if(empty($paged)): ?>
            <div class="p-8 text-center text-muted-foreground">
              <span data-lang="bn">কোন নোটিশ পাওয়া যায়নি।</span>
              <span data-lang="en" class="hidden">No notices found.</span>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex items-center justify-center gap-2">
          <a href="<?php echo $page > 1 ? '?page=' . ($page - 1) : '#'; ?>" class="grid h-9 w-9 place-items-center rounded-full border border-border bg-surface text-sm <?php echo $page == 1 ? 'opacity-40 pointer-events-none' : ''; ?>">‹</a>
          
          <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="grid h-9 min-w-[2.25rem] place-items-center rounded-full px-3 text-sm font-semibold transition <?php echo $i == $page ? 'bg-primary text-primary-foreground shadow-card' : 'border border-border bg-surface hover:bg-primary-soft hover:text-primary'; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>
          
          <a href="<?php echo $page < $totalPages ? '?page=' . ($page + 1) : '#'; ?>" class="grid h-9 w-9 place-items-center rounded-full border border-border bg-surface text-sm <?php echo $page == $totalPages ? 'opacity-40 pointer-events-none' : ''; ?>">›</a>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <aside class="space-y-6 lg:sticky lg:top-24 lg:h-fit">
        <div class="rounded-2xl border border-border bg-surface p-5 shadow-card">
          <div class="flex items-center gap-2 border-b border-border pb-3">
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-primary-soft text-primary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M6 3h9l5 5v13H6z" stroke-linejoin="round" /></svg>
            </span>
            <h3 class="font-serif-bn text-base font-bold">
                <span data-lang="bn">সাম্প্রতিক নোটিশ</span><span data-lang="en" class="hidden">Recent Notices</span>
            </h3>
          </div>
          <ul class="mt-3 space-y-3">
            <?php foreach($recent_notices as $rn): ?>
              <?php $rn_title = get_bilingual_title($rn); ?>
              <li>
                <a href="notice.php?id=<?php echo $rn['id']; ?>" class="block w-full text-left transition <?php echo (isset($active) && $active['id'] == $rn['id']) ? 'text-primary' : 'text-foreground/85 hover:text-primary'; ?>">
                  <div class="text-xs font-semibold text-primary"><?php echo date('d M, Y', strtotime($rn['created_at'])); ?></div>
                  <div class="mt-1 font-serif-bn text-sm font-bold leading-snug">
                      <span data-lang="bn"><?php echo e($rn_title['bn']); ?></span>
                      <span data-lang="en" class="hidden"><?php echo e($rn_title['en']); ?></span>
                  </div>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="rounded-2xl border border-border bg-surface p-5 shadow-card">
          <div class="flex items-center gap-2 border-b border-border pb-3">
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-primary-soft text-primary">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </span>
            <h3 class="font-serif-bn text-base font-bold">
                <span data-lang="bn">গুরুত্বপূর্ণ লিংক</span><span data-lang="en" class="hidden">Important Links</span>
            </h3>
          </div>
          <ul class="mt-3 space-y-2">
            <li><a href="#" class="flex items-center gap-2 text-sm font-serif-bn font-medium text-foreground/85 transition hover:text-primary"><span class="h-1.5 w-1.5 rounded-full bg-primary/40"></span><span data-lang="bn">বার্ষিক প্রতিবেদন ২০২৫</span><span data-lang="en" class="hidden">Annual Report 2025</span></a></li>
            <li><a href="#" class="flex items-center gap-2 text-sm font-serif-bn font-medium text-foreground/85 transition hover:text-primary"><span class="h-1.5 w-1.5 rounded-full bg-primary/40"></span><span data-lang="bn">সাংগঠনিক গঠনতন্ত্র</span><span data-lang="en" class="hidden">Organizational Constitution</span></a></li>
            <li><a href="#" class="flex items-center gap-2 text-sm font-serif-bn font-medium text-foreground/85 transition hover:text-primary"><span class="h-1.5 w-1.5 rounded-full bg-primary/40"></span><span data-lang="bn">স্বেচ্ছাসেবক নীতিমালা</span><span data-lang="en" class="hidden">Volunteer Policy</span></a></li>
            <li><a href="#" class="flex items-center gap-2 text-sm font-serif-bn font-medium text-foreground/85 transition hover:text-primary"><span class="h-1.5 w-1.5 rounded-full bg-primary/40"></span><span data-lang="bn">স্বচ্ছতা ও নিরীক্ষা</span><span data-lang="en" class="hidden">Transparency & Audit</span></a></li>
          </ul>
        </div>
      </aside>
    </section>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
