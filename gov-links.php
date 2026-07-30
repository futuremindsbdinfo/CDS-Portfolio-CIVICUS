<?php 
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';
$db = Database::getConnection();

$links = [];
$categories = [];
if ($db) {
    $links = $db->query("SELECT * FROM gov_links ORDER BY title ASC")->fetchAll();
    $cats = $db->query("SELECT DISTINCT category FROM gov_links WHERE category IS NOT NULL AND category != ''")->fetchAll(PDO::FETCH_COLUMN);
    $categories = $cats ? $cats : [];
}

include 'includes/header.php'; 
?>

<!-- Page Header -->
<div class="bg-gradient-to-br from-orange-50 to-orange-100 py-12 text-center border-b border-orange-200">
    <div class="container mx-auto px-4">

        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4 text-slate-800">
            <span data-lang="bn">আপনার প্রয়োজনীয় <span class="text-orange-500">সরকারি</span><br> ওয়েবসাইট ও লিংকসমূহ</span>
            <span data-lang="en" class="hidden">Your Necessary <span class="text-orange-500">Government</span><br> Websites & Links</span>
        </h1>
        <p class="text-slate-600 max-w-xl mx-auto mb-8 text-sm md:text-base">
            <span data-lang="bn">সরকারি বিভিন্ন সেবা এবং ওয়েবসাইটের লিংক খুব সহজেই এখান থেকে খুঁজে নিন</span>
            <span data-lang="en" class="hidden">Easily find links to various government services and websites here</span>
        </p>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto relative flex items-center bg-white rounded-full p-2 shadow-sm border border-orange-200 focus-within:ring-2 focus-within:ring-orange-300 focus-within:border-orange-300 transition-all">
            <div class="pl-4 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="gov-link-search" placeholder="সরকারি ওয়েবসাইটের নাম দিয়ে খুঁজুন..." class="w-full bg-transparent border-none focus:ring-0 px-4 py-2 text-slate-700 outline-none">
            <button class="bg-orange-500 text-white px-6 py-2 rounded-full font-medium hover:bg-orange-600 transition-colors shadow-sm whitespace-nowrap">
                খুঁজুন <span class="ml-1">›</span>
            </button>
        </div>
        
        <!-- Category Filter Chips -->
        <?php if(!empty($categories)): ?>
        <div class="flex flex-wrap justify-center gap-2 mt-6" id="category-filters">
            <span class="category-btn active px-4 py-1.5 rounded-full bg-orange-500 text-white text-xs font-semibold shadow-sm cursor-pointer transition" data-category="all">সব</span>
            <?php foreach($categories as $cat): ?>
                <span class="category-btn px-4 py-1.5 rounded-full bg-white text-slate-600 border border-slate-200 text-xs font-semibold shadow-sm hover:border-orange-300 cursor-pointer transition" data-category="<?php echo e($cat); ?>"><?php echo e($cat); ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Links Section -->
<section class="py-12 bg-slate-50 min-h-[50vh]">
    <div class="container mx-auto px-4 max-w-7xl">
        <div id="gov-links-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php if(empty($links)): ?>
                <div class="col-span-full text-center py-12 text-slate-500">
                    কোনো লিংক পাওয়া যায়নি।
                </div>
            <?php else: ?>
                <?php foreach($links as $link): ?>
                <a href="<?php echo e($link['url']); ?>" target="_blank" class="gov-link-card flex flex-col items-center justify-center p-8 rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md hover:border-green-300 transition-all group relative h-48 md:h-56">
                    <div class="absolute top-4 right-4 text-green-500 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </div>
                    <div class="w-16 h-16 md:w-20 md:h-20 mb-4 rounded-full flex items-center justify-center bg-slate-50 border border-slate-100 overflow-hidden">
                        <?php if(!empty($link['logo_image'])): ?>
                            <img src="uploads/gov_links/<?php echo e($link['logo_image']); ?>" alt="Logo" class="max-w-full max-h-full object-contain p-2">
                        <?php elseif(!empty($link['logo_url'])): ?>
                            <img src="<?php echo e($link['logo_url']); ?>" alt="Logo" class="max-w-full max-h-full object-contain p-2">
                        <?php else: ?>
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-serif-bn font-bold text-slate-800 text-sm md:text-base text-center leading-tight line-clamp-2 group-hover:text-green-600 transition-colors link-title"><?php echo e($link['title']); ?></h3>
                    <p class="hidden link-category"><?php echo e($link['category']); ?></p>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div id="no-results" class="hidden text-center py-12 text-slate-500 font-medium">
            আপনার অনুসন্ধানের সাথে মিল পাওয়া যায়নি।
        </div>
    </div>
</section>

<!-- FAQ Section (Placeholder as in image) -->
<section class="py-16 bg-white border-t border-slate-100">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="text-center mb-8">
            <span class="text-orange-500 text-xs font-bold uppercase tracking-wider">FAQ</span>
            <h2 class="text-2xl font-serif-bn font-bold text-slate-900 mt-1">সাধারণ জিজ্ঞাসা (FAQ)</h2>
        </div>
        <div class="space-y-4">
            <div class="border border-orange-200 rounded-lg overflow-hidden">
                <button class="w-full px-5 py-4 text-left bg-orange-50 hover:bg-orange-100 flex justify-between items-center font-bold text-slate-800 transition">
                    সরকারি ওয়েবসাইটগুলো কি নিরাপদ?
                    <span class="text-orange-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg></span>
                </button>
                <div class="px-5 py-4 text-slate-600 text-sm bg-white border-t border-orange-100 leading-relaxed">
                    হ্যাঁ, বাংলাদেশ সরকারের ওয়েবসাইটসমূহ অত্যন্ত নিরাপদ। এসব ওয়েবসাইট সরকারি সার্ভারে হোস্ট করা থাকে এবং এখানে দেওয়া তথ্য সম্পূর্ণ নির্ভরযোগ্য।
                </div>
            </div>
            
            <div class="border border-slate-200 rounded-lg overflow-hidden">
                <button class="w-full px-5 py-4 text-left bg-white hover:bg-slate-50 flex justify-between items-center font-bold text-slate-700 transition">
                    সরকারি ওয়েবসাইটে কি আমার ব্যক্তিগত তথ্য নিরাপদ?
                    <span class="text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></span>
                </button>
            </div>
            
            <div class="border border-slate-200 rounded-lg overflow-hidden">
                <button class="w-full px-5 py-4 text-left bg-white hover:bg-slate-50 flex justify-between items-center font-bold text-slate-700 transition">
                    আমি কি এই ওয়েবসাইটগুলো থেকে সরাসরি সেবা পেতে পারি?
                    <span class="text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></span>
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    // Simple client-side search filtering
    const searchInput = document.getElementById('gov-link-search');
    const cards = document.querySelectorAll('.gov-link-card');
    const noResults = document.getElementById('no-results');
    const categoryBtns = document.querySelectorAll('.category-btn');
    let currentCategory = 'all';

    function filterLinks() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;

        cards.forEach(card => {
            const title = card.querySelector('.link-title').textContent.toLowerCase();
            const category = card.querySelector('.link-category').textContent;
            
            const matchesSearch = title.includes(query);
            const matchesCategory = currentCategory === 'all' || category === currentCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0 && cards.length > 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterLinks);
    }

    if (categoryBtns) {
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                categoryBtns.forEach(b => {
                    b.classList.remove('bg-orange-500', 'text-white');
                    b.classList.add('bg-white', 'text-slate-600');
                });
                this.classList.remove('bg-white', 'text-slate-600');
                this.classList.add('bg-orange-500', 'text-white');

                currentCategory = this.getAttribute('data-category');
                filterLinks();
            });
        });
    }
</script>

<?php include 'includes/footer.php'; ?>
