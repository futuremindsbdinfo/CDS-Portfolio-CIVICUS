<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';

$pdo = Database::getConnection();
$forms = [];
$categories = [];

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM downloadable_forms WHERE is_active = 1 ORDER BY id DESC");
        $forms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Collect unique categories
        foreach ($forms as $f) {
            if (!empty($f['category']) && !in_array($f['category'], $categories)) {
                $categories[] = $f['category'];
            }
        }
    } catch (PDOException $e) {
        $forms = [];
    }
}

$page_title = "আবেদন ফরম ও রিসোর্স (Application Forms)";
$meta_description = "সিডিএস এর বিভিন্ন কার্যক্রমে অংশগ্রহণের জন্য প্রয়োজনীয় আবেদন ফরম ও ডকুমেন্টস ডাউনলোড করুন।";
include 'includes/header.php'; 
?>

<!-- Page Header -->
<div class="bg-cds-blue py-12 md:py-16 text-white text-center">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">
            <span data-lang="bn">আবেদন ফরম ও রিসোর্স সেন্টার</span>
            <span data-lang="en" class="hidden">Application Forms & Resource Center</span>
        </h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">
            <span data-lang="bn">সিডিএস-এর সদস্যপদ, স্বেচ্ছাসেবা ও উন্নয়ন কার্যক্রমে অংশগ্রহণের প্রয়োজনীয় ফরমসমূহ।</span>
            <span data-lang="en" class="hidden">Download required application forms for CDS membership, volunteering, and programs.</span>
        </p>
    </div>
</div>

<!-- Forms Section -->
<section class="py-16 bg-slate-50 min-h-[60vh]">
    <div class="container mx-auto px-4 max-w-6xl">
        
        <?php if (!empty($forms)): ?>
            <!-- Category Tabs -->
            <?php if (count($categories) > 1): ?>
                <div class="flex flex-wrap justify-center gap-2 mb-10">
                    <button class="form-filter-btn active px-4 py-2 rounded-full text-xs font-bold bg-[#0e1b64] text-white shadow-sm transition" data-category="all">
                        <span data-lang="bn">সকল ফরম</span>
                        <span data-lang="en" class="hidden">All Forms</span>
                    </button>
                    <?php foreach ($categories as $cat): ?>
                        <button class="form-filter-btn px-4 py-2 rounded-full text-xs font-bold bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 shadow-sm transition" data-category="<?php echo e($cat); ?>">
                            <?php echo e($cat); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Forms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="formsGrid">
                <?php foreach ($forms as $form): 
                    $file_ext = strtolower($form['file_type'] ?? 'pdf');
                    $is_pdf = $file_ext === 'pdf';
                ?>
                    <div class="form-card bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group" data-category="<?php echo e($form['category']); ?>">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="w-12 h-12 rounded-xl <?php echo $is_pdf ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-blue-50 text-blue-600 border border-blue-100'; ?> grid place-items-center shrink-0">
                                    <?php if ($is_pdf): ?>
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <?php else: ?>
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <?php endif; ?>
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                    <?php echo e($form['category']); ?>
                                </span>
                            </div>

                            <h3 class="font-serif-bn font-bold text-lg text-[#0e1b64] group-hover:text-primary transition mb-2">
                                <span data-lang="bn"><?php echo e($form['title_bn']); ?></span>
                                <span data-lang="en" class="hidden"><?php echo e(!empty($form['title_en']) ? $form['title_en'] : $form['title_bn']); ?></span>
                            </h3>

                            <?php if (!empty($form['description_bn'])): ?>
                                <p class="text-xs text-slate-500 line-clamp-2 mb-4">
                                    <span data-lang="bn"><?php echo e($form['description_bn']); ?></span>
                                    <span data-lang="en" class="hidden"><?php echo e(!empty($form['description_en']) ? $form['description_en'] : $form['description_bn']); ?></span>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                            <span class="text-xs text-slate-400 font-mono">
                                <?php echo strtoupper($form['file_type']); ?> • <?php echo e($form['file_size']); ?>
                            </span>

                            <div class="flex items-center gap-2">
                                <?php if (!empty($form['file_path'])): ?>
                                    <a href="/download.php?id=<?php echo $form['id']; ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#0e1b64] hover:bg-[#0345bf] text-white text-xs font-bold shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span data-lang="bn">ডাউনলোড</span>
                                        <span data-lang="en" class="hidden">Download</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="p-12 bg-white rounded-2xl border border-slate-200 text-center max-w-xl mx-auto shadow-sm">
                <div class="w-16 h-16 bg-primary-soft text-primary rounded-full grid place-items-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">
                    <span data-lang="bn">কোন আবেদন ফরম পাওয়া যায়নি</span>
                    <span data-lang="en" class="hidden">No Application Forms Found</span>
                </h3>
                <p class="text-sm text-slate-500 mb-6">
                    <span data-lang="bn">খুব শীঘ্রই প্রয়োজনীয় সকল আবেদন ফরম এখানে সংযুক্ত করা হবে।</span>
                    <span data-lang="en" class="hidden">All necessary application forms will be uploaded here shortly.</span>
                </p>
                <a href="index.php" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-dark transition">
                    <span data-lang="bn">হোমপেইজে ফিরে যান</span>
                    <span data-lang="en" class="hidden">Back to Home</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.form-filter-btn');
    const formCards = document.querySelectorAll('.form-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => {
                b.classList.remove('bg-[#0e1b64]', 'text-white');
                b.classList.add('bg-white', 'text-slate-700');
            });
            btn.classList.add('bg-[#0e1b64]', 'text-white');
            btn.classList.remove('bg-white', 'text-slate-700');

            const cat = btn.getAttribute('data-category');
            formCards.forEach(card => {
                if (cat === 'all' || card.getAttribute('data-category') === cat) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
