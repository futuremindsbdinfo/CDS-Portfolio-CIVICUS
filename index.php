<?php
require_once __DIR__ . '/includes/auth.php';
init_secure_session();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';
require_once __DIR__ . '/includes/csrf.php';
$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT * FROM projects ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$latest_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 4");
$stmt->execute();
$latest_notices = $stmt->fetchAll(PDO::FETCH_ASSOC);
require_once __DIR__ . '/includes/header.php';
?>

<style>
.hero-slider { position: relative; overflow: hidden; }
.hero-track { display: flex; transition: transform 0.6s cubic-bezier(0.4,0,0.2,1); }
.hero-slide { min-width: 100%; flex-shrink: 0; }
.hero-dots { display:flex; justify-content:center; gap:10px; padding:20px 0 30px; }
.hero-dot { width:14px; height:14px; border-radius:50%; background:rgba(0,0,0,0.15); border:none; cursor:pointer; transition:all 0.3s; }
.hero-dot.active { background:#0e1b64; transform:scale(1.2); }
</style>

<!-- SECTION 1: HERO SLIDER -->
<div class="hero-slider min-h-[500px] bg-slate-50">
    <div class="hero-track" id="heroTrack">
        <!-- Slide 1 -->
        <div class="hero-slide bg-[#b0e4d2]">
            <div class="container mx-auto px-4 py-16 lg:py-24 h-full flex flex-col lg:flex-row items-center gap-10">
                <div class="flex-1 space-y-6">
                    <h1 class="font-serif-bn font-bold text-4xl lg:text-6xl text-[#0e1b64] leading-tight">
                        <span data-lang="bn">সিটিজেন ডেভেলপমেন্ট সোসাইটি</span>
                        <span data-lang="en" class="hidden">Citizen Development Society</span>
                    </h1>
                    <p class="text-lg text-slate-700 max-w-xl">
                        <span data-lang="bn">সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক এবং উন্নত বাংলাদেশ বিনির্মাণে আমরা ঐক্যবদ্ধ।</span>
                        <span data-lang="en" class="hidden">We are united to build good education, governance, health, citizens and a prosperous Bangladesh.</span>
                    </p>
                    <div>
                        <a href="#" class="inline-block rounded-lg bg-[#0e1b64] hover:bg-[#0345bf] transition text-white px-8 py-4 font-bold uppercase shadow-lg">
                            <span data-lang="bn">আরও জানুন</span>
                            <span data-lang="en" class="hidden">Learn More</span>
                        </a>
                    </div>
                </div>
                <div class="flex-1 flex justify-center">
                    <img src="/assets/img/hero-slide-1.jpg" alt="Slide 1" class="w-full max-w-[500px] rounded-2xl shadow-xl object-cover aspect-video">
                </div>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="hero-slide bg-[#B4DCF1]">
            <div class="container mx-auto px-4 py-16 lg:py-24 h-full flex flex-col lg:flex-row items-center gap-10">
                <div class="flex-1 space-y-6">
                    <h1 class="font-serif-bn font-bold text-4xl lg:text-6xl text-[#0e1b64] leading-tight">
                        <span data-lang="bn">সদস্য নিবন্ধন চলছে!</span>
                        <span data-lang="en" class="hidden">Membership Registration Open!</span>
                    </h1>
                    <p class="text-lg text-slate-700 max-w-xl">
                        <span data-lang="bn">আজই আমাদের সাথে যোগ দিন এবং সমাজের পরিবর্তনে অংশীদার হোন।</span>
                        <span data-lang="en" class="hidden">Join us today and be a partner in changing society.</span>
                    </p>
                    <div>
                        <a href="/register.php" class="inline-block rounded-lg bg-[#0e1b64] hover:bg-[#0345bf] transition text-white px-8 py-4 font-bold uppercase shadow-lg">
                            <span data-lang="bn">নিবন্ধন করুন</span>
                            <span data-lang="en" class="hidden">Register Now</span>
                        </a>
                    </div>
                </div>
                <div class="flex-1 flex justify-center">
                    <img src="/assets/img/hero-slide-2.jpg" alt="Slide 2" class="w-full max-w-[500px] rounded-2xl shadow-xl object-cover aspect-video">
                </div>
            </div>
        </div>
        <!-- Slide 3 -->
        <div class="hero-slide bg-[#e8eaf6]">
            <div class="container mx-auto px-4 py-16 lg:py-24 h-full flex flex-col lg:flex-row items-center gap-10">
                <div class="flex-1 space-y-6">
                    <h1 class="font-serif-bn font-bold text-4xl lg:text-6xl text-[#0e1b64] leading-tight">
                        <span data-lang="bn">আমাদের চলমান উদ্যোগ দেখুন</span>
                        <span data-lang="en" class="hidden">Explore Our Active Programs</span>
                    </h1>
                    <p class="text-lg text-slate-700 max-w-xl">
                        <span data-lang="bn">প্রান্তিক জনগোষ্ঠীর উন্নয়নে আমাদের নানামুখী উদ্যোগ সম্পর্কে জানুন।</span>
                        <span data-lang="en" class="hidden">Learn about our multidimensional initiatives for the development of marginalized communities.</span>
                    </p>
                    <div>
                        <a href="/projects.php" class="inline-block rounded-lg bg-[#0e1b64] hover:bg-[#0345bf] transition text-white px-8 py-4 font-bold uppercase shadow-lg">
                            <span data-lang="bn">প্রজেক্ট দেখুন</span>
                            <span data-lang="en" class="hidden">View Projects</span>
                        </a>
                    </div>
                </div>
                <div class="flex-1 flex justify-center">
                    <img src="/assets/img/cds-logo.png" alt="CDS Logo" class="w-full max-w-[500px] rounded-2xl shadow-xl object-contain aspect-square bg-white/50 p-8">
                </div>
            </div>
        </div>
    </div>
    <div class="hero-dots absolute bottom-0 left-0 w-full z-10">
        <button class="hero-dot active" data-slide="0" aria-label="Slide 1"></button>
        <button class="hero-dot" data-slide="1" aria-label="Slide 2"></button>
        <button class="hero-dot" data-slide="2" aria-label="Slide 3"></button>
    </div>
</div>

<script>
(function() {
    const track = document.getElementById('heroTrack');
    const dots = document.querySelectorAll('.hero-dot');
    let current = 0;
    const total = dots.length;
    let timer;
    function goTo(i) {
        current = (i + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach((d, j) => d.classList.toggle('active', j === current));
    }
    function auto() { timer = setInterval(() => goTo(current + 1), 5000); }
    dots.forEach(d => d.addEventListener('click', () => { clearInterval(timer); goTo(+d.dataset.slide); auto(); }));
    auto();
})();
</script>

<!-- SECTION 2: ABOUT / MISSION -->
<section class="bg-white py-20">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="font-serif-bn font-black text-3xl lg:text-4xl text-[#0e1b64] leading-snug">
                    <span data-lang="bn">সিডিএস নাগরিক উদ্যোগ ও সিভিল সোসাইটিকে শক্তিশালী করে দেশজুড়ে।</span>
                    <span data-lang="en" class="hidden">CDS civil society alliance strengthens citizen action and civil society around the country</span>
                </h2>
            </div>
            <div class="space-y-6">
                <p class="text-lg text-slate-700 leading-relaxed">
                    <span data-lang="bn">কুমিল্লার নাঙ্গলকোট ও লালমাই উপজেলা থেকে শুরু করে সমগ্র বাংলাদেশে প্রান্তিক জনপদের উন্নয়নে আমরা কাজ করে যাচ্ছি। আমাদের লক্ষ্য — একটি অন্তর্ভুক্তিমূলক, ন্যায়ভিত্তিক ও টেকসই সমাজ।</span>
                    <span data-lang="en" class="hidden">From Nangalkot and Lalmai upazilas of Cumilla to the entire Bangladesh, we are working for the development of marginalized communities. Our goal — an inclusive, just, and sustainable society.</span>
                </p>
                <p class="text-lg text-slate-700 leading-relaxed">
                    <span data-lang="bn">সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক এবং উন্নত বাংলাদেশ বিনির্মাণে আমরা ঐক্যবদ্ধ।</span>
                    <span data-lang="en" class="hidden">We are united to build good education, governance, health, active citizenship and a prosperous Bangladesh.</span>
                </p>
            </div>
        </div>
        <div class="mt-16 mx-auto max-w-5xl rounded-2xl overflow-hidden shadow-2xl relative aspect-[16/9]">
            <img src="/assets/img/hero-slide-1.jpg" alt="Featured Image" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0e1b64]/80 to-transparent"></div>
        </div>
    </div>
</section>

<!-- SECTION 3: GEOMETRIC DIVIDER -->
<div class="py-6 flex items-center justify-center gap-3 flex-wrap bg-white border-b border-slate-100">
    <svg width="36" height="36"><rect width="36" height="36" rx="4" fill="#1e3a8a"/></svg>
    <svg width="36" height="36"><circle cx="18" cy="18" r="18" fill="#16a34a"/></svg>
    <svg width="36" height="36"><polygon points="18,0 36,36 0,36" fill="#0ea5e9"/></svg>
    <svg width="36" height="36"><rect width="36" height="36" rx="18" fill="#22c55e" style="border-radius:50%"/></svg>
    <svg width="36" height="36"><polygon points="18,0 36,18 18,36 0,18" fill="#1e40af"/></svg>
    <svg width="36" height="36"><circle cx="18" cy="18" r="18" fill="#047857"/></svg>
    <svg width="36" height="36"><rect width="36" height="36" rx="4" fill="#f59e0b"/></svg>
    <svg width="36" height="36"><polygon points="0,0 36,0 18,36" fill="#ec4899"/></svg>
    <svg width="36" height="36"><circle cx="18" cy="18" r="18" fill="#0d9488"/></svg>
    <svg width="36" height="36"><rect width="36" height="36" rx="4" fill="#8b5cf6"/></svg>
    <svg width="36" height="36"><polygon points="18,0 36,36 0,36" fill="#1e3a8a"/></svg>
    <svg width="36" height="36"><circle cx="18" cy="18" r="18" fill="#16a34a"/></svg>
    <svg width="36" height="36"><rect width="36" height="36" rx="4" fill="#0ea5e9"/></svg>
    <svg width="36" height="36"><polygon points="0,0 36,0 18,36" fill="#22c55e"/></svg>
    <svg width="36" height="36"><circle cx="18" cy="18" r="18" fill="#1e40af"/></svg>
    <svg width="36" height="36"><rect width="36" height="36" rx="4" fill="#047857"/></svg>
</div>

<!-- SECTION 4: WHAT WE DO (5 Pillars) -->
<section class="bg-slate-50 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-center font-serif-bn font-bold text-3xl lg:text-4xl text-[#0e1b64] mb-12">
            <span data-lang="bn">আমাদের কার্যক্রম</span>
            <span data-lang="en" class="hidden">What We Do</span>
        </h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Pillar 1 -->
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col">
                <div class="aspect-[4/3] bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center p-6">
                    <svg class="w-16 h-16 text-white opacity-90 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-[#0e1b64] text-lg mb-2">
                        <span data-lang="bn">সুশিক্ষা</span>
                        <span data-lang="en" class="hidden">Quality Education</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow">
                        <span data-lang="bn">শিক্ষাকে সবার জন্য সহজলভ্য ও মানসম্পন্ন করে তোলা।</span>
                        <span data-lang="en" class="hidden">Making education accessible and quality for all.</span>
                    </p>
                    <a href="#" class="font-bold text-[#0345bf] text-sm group-hover:text-[#0e1b64] transition flex items-center">
                        <span data-lang="bn">বিস্তারিত <span class="ml-1">→</span></span>
                        <span data-lang="en" class="hidden">Details <span class="ml-1">→</span></span>
                    </a>
                </div>
            </div>
            <!-- Pillar 2 -->
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col">
                <div class="aspect-[4/3] bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center p-6">
                    <svg class="w-16 h-16 text-white opacity-90 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-[#0e1b64] text-lg mb-2">
                        <span data-lang="bn">সুশাসন</span>
                        <span data-lang="en" class="hidden">Good Governance</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow">
                        <span data-lang="bn">স্বচ্ছতা, জবাবদিহিতা ও ন্যায়ের চর্চা।</span>
                        <span data-lang="en" class="hidden">Practice of transparency, accountability and justice.</span>
                    </p>
                    <a href="#" class="font-bold text-[#0345bf] text-sm group-hover:text-[#0e1b64] transition flex items-center">
                        <span data-lang="bn">বিস্তারিত <span class="ml-1">→</span></span>
                        <span data-lang="en" class="hidden">Details <span class="ml-1">→</span></span>
                    </a>
                </div>
            </div>
            <!-- Pillar 3 -->
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col">
                <div class="aspect-[4/3] bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center p-6">
                    <svg class="w-16 h-16 text-white opacity-90 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-[#0e1b64] text-lg mb-2">
                        <span data-lang="bn">সুস্বাস্থ্য</span>
                        <span data-lang="en" class="hidden">Health & Well-being</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow">
                        <span data-lang="bn">প্রতিটি পরিবারে সুস্থ ও নিরাপদ জীবনের নিশ্চয়তা।</span>
                        <span data-lang="en" class="hidden">Ensuring healthy and safe life in every family.</span>
                    </p>
                    <a href="#" class="font-bold text-[#0345bf] text-sm group-hover:text-[#0e1b64] transition flex items-center">
                        <span data-lang="bn">বিস্তারিত <span class="ml-1">→</span></span>
                        <span data-lang="en" class="hidden">Details <span class="ml-1">→</span></span>
                    </a>
                </div>
            </div>
            <!-- Pillar 4 -->
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col">
                <div class="aspect-[4/3] bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center p-6">
                    <svg class="w-16 h-16 text-white opacity-90 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-[#0e1b64] text-lg mb-2">
                        <span data-lang="bn">সুনাগরিক</span>
                        <span data-lang="en" class="hidden">Active Citizenship</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow">
                        <span data-lang="bn">দায়িত্বশীল ও সচেতন নাগরিক গড়ে তোলা।</span>
                        <span data-lang="en" class="hidden">Building responsible and aware citizens.</span>
                    </p>
                    <a href="#" class="font-bold text-[#0345bf] text-sm group-hover:text-[#0e1b64] transition flex items-center">
                        <span data-lang="bn">বিস্তারিত <span class="ml-1">→</span></span>
                        <span data-lang="en" class="hidden">Details <span class="ml-1">→</span></span>
                    </a>
                </div>
            </div>
            <!-- Pillar 5 -->
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 flex flex-col">
                <div class="aspect-[4/3] bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center p-6">
                    <svg class="w-16 h-16 text-white opacity-90 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-[#0e1b64] text-lg mb-2">
                        <span data-lang="bn">উন্নত বাংলাদেশ</span>
                        <span data-lang="en" class="hidden">Prosperous Bangladesh</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow">
                        <span data-lang="bn">সমৃদ্ধ, স্বনির্ভর ও আধুনিক বাংলাদেশ বিনির্মাণ।</span>
                        <span data-lang="en" class="hidden">Building a prosperous, self-reliant and modern Bangladesh.</span>
                    </p>
                    <a href="#" class="font-bold text-[#0345bf] text-sm group-hover:text-[#0e1b64] transition flex items-center">
                        <span data-lang="bn">বিস্তারিত <span class="ml-1">→</span></span>
                        <span data-lang="en" class="hidden">Details <span class="ml-1">→</span></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 5: LATEST UPDATES -->
<section class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-10 border-b-2 border-[#0e1b64] pb-4">
            <h2 class="text-3xl font-serif-bn font-bold text-[#0e1b64]">
                <span data-lang="bn">সাম্প্রতিক আপডেট</span>
                <span data-lang="en" class="hidden">Latest Updates</span>
            </h2>
            <a href="/updates.php" class="rounded-lg bg-[#0e1b64] hover:bg-[#0345bf] text-white px-6 py-3 font-bold transition shadow-md whitespace-nowrap">
                <span data-lang="bn">সব আপডেট দেখুন</span>
                <span data-lang="en" class="hidden">View all updates</span>
            </a>
        </div>

        <div class="grid lg:grid-cols-[1.5fr_1fr] gap-8">
            <!-- Projects Block -->
            <div class="grid sm:grid-cols-2 gap-6">
                <?php $p_count = 0; foreach ($latest_projects as $p): if($p_count >= 2) break; ?>
                <a href="/project-single.php?id=<?= $p['id'] ?>" class="group block">
                    <div class="aspect-[16/10] rounded-xl overflow-hidden relative mb-4 shadow-md bg-gradient-to-br from-[#3A7D5C] to-[#1e3a8a]">
                        <?php if (!empty($p['cover_image'])): ?>
                            <img src="<?= htmlspecialchars($p['cover_image']) ?>" alt="Project Image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <?php endif; ?>
                        <div class="absolute bottom-3 left-3 bg-white/95 text-[#0e1b64] text-xs font-bold px-3 py-1.5 rounded-md shadow uppercase tracking-wider">
                            <span data-lang="bn">প্রজেক্ট</span>
                            <span data-lang="en" class="hidden">Project</span>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg text-[#0e1b64] mb-2 line-clamp-2 group-hover:text-[#0345bf] transition">
                        <span data-lang="bn"><?= htmlspecialchars($p['title_bn'] ?? '') ?></span>
                        <span data-lang="en" class="hidden"><?= htmlspecialchars($p['title_en'] ?? $p['title_bn'] ?? '') ?></span>
                    </h3>
                    <p class="text-sm text-slate-500 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?= date('d M Y', strtotime($p['created_at'])) ?>
                    </p>
                </a>
                <?php $p_count++; endforeach; ?>
            </div>

            <!-- Notices Block -->
            <div class="flex flex-col gap-6">
                <?php $n_count = 0; foreach ($latest_notices as $n): if($n_count >= 2) break; ?>
                <a href="/notice-single.php?id=<?= $n['id'] ?>" class="group flex gap-4 bg-slate-50 p-3 rounded-xl hover:bg-slate-100 transition shadow-sm border border-slate-100">
                    <div class="w-32 h-32 shrink-0 rounded-xl bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center relative overflow-hidden">
                        <?php if (!empty($n['image'])): ?>
                            <img src="<?= htmlspecialchars($n['image']) ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition">
                        <?php else: ?>
                            <svg class="w-12 h-12 text-slate-400 group-hover:text-slate-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h3 class="font-bold text-[#0e1b64] mb-2 line-clamp-2 group-hover:text-[#0345bf] transition">
                            <span data-lang="bn"><?= htmlspecialchars($n['title_bn'] ?? '') ?></span>
                            <span data-lang="en" class="hidden"><?= htmlspecialchars($n['title_en'] ?? $n['title_bn'] ?? '') ?></span>
                        </h3>
                        <p class="text-sm text-slate-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <?= date('d M Y', strtotime($n['created_at'])) ?>
                        </p>
                    </div>
                </a>
                <?php $n_count++; endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 6: ENGAGE AND ACT -->
<section class="bg-slate-50 py-16 border-t border-slate-200">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif-bn font-bold text-[#0e1b64] mb-4">
                <span data-lang="bn">যোগদান করুন</span>
                <span data-lang="en" class="hidden">Engage and Act</span>
            </h2>
            <p class="text-slate-600 mb-6 max-w-2xl mx-auto">
                <span data-lang="bn">সমাজের পরিবর্তনে আমাদের সাথে যুক্ত হোন। আপনার একটি ছোট উদ্যোগ আনতে পারে বড় পরিবর্তন।</span>
                <span data-lang="en" class="hidden">Join us in changing society. Your small initiative can bring big changes.</span>
            </p>
            <a href="/initiatives.php" class="inline-block border-2 border-[#0e1b64] text-[#0e1b64] hover:bg-[#0e1b64] hover:text-white rounded-lg px-6 py-2.5 font-bold transition">
                <span data-lang="bn">সকল কার্যক্রম দেখুন</span>
                <span data-lang="en" class="hidden">View all activities</span>
            </a>
        </div>
        
        <div class="grid sm:grid-cols-3 gap-6">
            <a href="#" class="group block rounded-2xl overflow-hidden aspect-[4/3] bg-gradient-to-br from-[#1e3a8a] to-[#0345bf] relative shadow hover:shadow-xl transition duration-300">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 text-white group-hover:scale-105 transition duration-500">
                    <h3 class="text-2xl font-black tracking-wider mb-2">VOLUNTEER CHALLENGE</h3>
                    <p class="opacity-90 font-medium">Join our network of changemakers</p>
                </div>
                <div class="absolute bottom-0 left-0 w-full bg-white py-3 text-center">
                    <span class="font-bold text-[#1e3a8a] uppercase tracking-wide text-sm">স্বেচ্ছাসেবক হোন</span>
                </div>
            </a>
            
            <a href="#" class="group block rounded-2xl overflow-hidden aspect-[4/3] bg-gradient-to-br from-[#3A7D5C] to-[#047857] relative shadow hover:shadow-xl transition duration-300">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 text-white group-hover:scale-105 transition duration-500">
                    <h3 class="text-2xl font-black tracking-wider mb-2">INNOVATION AWARDS</h3>
                    <p class="opacity-90 font-medium">Recognizing local solutions</p>
                </div>
                <div class="absolute bottom-0 left-0 w-full bg-white py-3 text-center">
                    <span class="font-bold text-[#047857] uppercase tracking-wide text-sm">উদ্যোগ সম্মাননা</span>
                </div>
            </a>
            
            <a href="#" class="group block rounded-2xl overflow-hidden aspect-[4/3] bg-gradient-to-br from-[#0ea5e9] to-[#0284c7] relative shadow hover:shadow-xl transition duration-300">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 text-white group-hover:scale-105 transition duration-500">
                    <h3 class="text-2xl font-black tracking-wider mb-2">STAND AS MY WITNESS</h3>
                    <p class="opacity-90 font-medium">Support human rights defenders</p>
                </div>
                <div class="absolute bottom-0 left-0 w-full bg-white py-3 text-center">
                    <span class="font-bold text-[#0284c7] uppercase tracking-wide text-sm">অধিকার সুরক্ষা</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- SECTION 7: NEWSLETTER CTA -->
<section class="bg-[#fef3c7] py-16 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div class="space-y-6 max-w-lg relative z-10">
                <h2 class="text-3xl lg:text-4xl font-serif-bn font-black text-[#0e1b64] leading-tight">
                    <span data-lang="bn">আমাদের নিউজলেটার সাইন আপ করুন</span>
                    <span data-lang="en" class="hidden">Sign up for our newsletters</span>
                </h2>
                <p class="text-slate-700 text-lg">
                    <span data-lang="bn">সিডিএস-এর সর্বশেষ খবর, ইভেন্ট এবং প্রতিবেদন পেতে সাবস্ক্রাইব করুন।</span>
                    <span data-lang="en" class="hidden">Subscribe to get the latest news, events and reports from CDS.</span>
                </p>
                <form action="/subscribe.php" method="POST" class="flex flex-col sm:flex-row gap-3 mt-4">
                    <input type="email" name="email" placeholder="Email Address" required class="flex-1 rounded-lg border-2 border-transparent focus:border-[#0e1b64] focus:ring-0 px-4 py-3 shadow-sm outline-none">
                    <button type="submit" class="bg-[#0e1b64] hover:bg-[#0345bf] text-white rounded-lg px-8 py-3 font-bold transition shadow-lg whitespace-nowrap">
                        <span data-lang="bn">সাবস্ক্রাইব</span>
                        <span data-lang="en" class="hidden">Subscribe</span>
                    </button>
                </form>
            </div>
            
            <div class="relative flex justify-center lg:justify-end pb-8 lg:pb-0">
                <div class="relative">
                    <div class="absolute -inset-4 bg-yellow-200/50 rounded-3xl -rotate-6 transform"></div>
                    <img src="/assets/img/hero-slide-1.jpg" alt="Newsletter" class="relative z-10 w-[320px] h-[220px] object-cover rounded-2xl shadow-xl rotate-3 border-4 border-white transform hover:rotate-0 transition duration-500">
                    
                    <!-- Decorative Shapes -->
                    <div class="absolute -top-6 -right-6 z-20">
                        <svg width="40" height="40" class="text-[#0e1b64] animate-spin-slow" style="animation: spin 10s linear infinite;"><path fill="currentColor" d="M20 0l2.5 13.5L36 16l-13.5 2.5L20 32l-2.5-13.5L4 16l13.5-2.5z"></path></svg>
                    </div>
                    <div class="absolute -bottom-8 -left-8 z-0">
                        <svg width="60" height="60" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="#3A7D5C" stroke-width="8" stroke-dasharray="10 10"></circle></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
