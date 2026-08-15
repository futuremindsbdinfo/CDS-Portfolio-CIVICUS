<?php
require_once __DIR__ . '/includes/auth.php';
init_secure_session();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';
require_once __DIR__ . '/includes/csrf.php';

$pdo = Database::getConnection();

// Fetch latest 3 projects
$stmt = $pdo->prepare("SELECT * FROM projects ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$latest_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch latest 4 notices
$stmt = $pdo->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 4");
$stmt->execute();
$latest_notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>
<!-- Custom Styles for CIVICUS theme -->
<style>
    .bg-cyan-light { background-color: #d1f4f9; }
    .text-cds-blue { color: #0e1b64; }
    .text-cds-inverse { color: #0345bf; }
    .bg-cds-dark { background-color: #1a1a1a; }
    
    .geometric-divider {
        width: 100%;
        height: 48px;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="48" viewBox="0 0 400 48"><rect x="0" y="8" width="32" height="32" fill="%231e3a8a" rx="4"/><circle cx="64" cy="24" r="16" fill="%2316a34a"/><path d="M96 40 L112 8 L128 40 Z" fill="%230ea5e9"/><rect x="144" y="8" width="32" height="32" fill="%2322c55e" rx="16" stroke-width="4" stroke="%2386efac"/><circle cx="208" cy="24" r="16" fill="%231e40af"/><path d="M240 8 h32 v32 h-32 Z" fill="%23047857"/><circle cx="304" cy="24" r="16" fill="%2338bdf8"/><rect x="336" y="8" width="32" height="32" fill="%2315803d" rx="8"/><circle cx="384" cy="24" r="8" fill="%231e3a8a"/></svg>');
        background-repeat: repeat-x;
        background-position: center;
        background-size: 400px 48px;
    }
</style>


<!-- 1. HERO SLIDER (CIVICUS Style) -->
<style>
    /* Hero Slider */
    .hero-slider-wrapper {
        position: relative;
        overflow: hidden;
        width: 100%;
    }
    .hero-slides-track {
        display: flex;
        transition: transform 0.6s cubic-bezier(0.4,0,0.2,1);
        will-change: transform;
    }
    .hero-slide {
        min-width: 100%;
        flex-shrink: 0;
    }
    .hero-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 16px 0 24px;
    }
    .hero-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #1e3a8a30;
        border: none;
        cursor: pointer;
        transition: background 0.3s, transform 0.3s;
        padding: 0;
    }
    .hero-dot.active {
        background: #1e3a8a;
        transform: scale(1.3);
    }
</style>

<section class="hero-slider-wrapper bg-white border-b border-slate-100">
    <div class="hero-slides-track" id="heroTrack">

        <!-- Slide 1: Main CDS Banner -->
        <div class="hero-slide bg-[#d1f4f9]">
            <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8 grid items-center gap-8 lg:grid-cols-2 py-16 lg:py-20">
                <div class="flex flex-col items-start text-left">
                    <h1 class="font-serif-bn text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl text-cds-blue">
                        <span data-lang="bn">সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)</span>
                        <span data-lang="en" class="hidden">Citizen Development Society (CDS)</span>
                    </h1>
                    <p class="mt-4 text-base sm:text-lg leading-relaxed text-slate-700 max-w-lg">
                        <span data-lang="bn">অরাজনৈতিক, অলাভজনক ও স্বেচ্ছাসেবী নাগরিকভিত্তিক সিভিল সোসাইটি সংগঠন।</span>
                        <span data-lang="en" class="hidden">Non-political, non-profit and voluntary citizen-based civil society organization.</span>
                    </p>
                    <a href="#about" class="mt-8 inline-flex items-center gap-2 rounded-full bg-cds-blue px-6 py-3 text-sm font-bold text-white shadow-lg hover:bg-blue-800 transition">
                        <span data-lang="bn">আরও জানুন</span><span data-lang="en" class="hidden">LEARN MORE</span>
                    </a>
                </div>
                <div class="hidden lg:flex justify-end">
                    <div class="w-[420px] h-[280px] bg-white rounded-2xl shadow-xl flex items-center justify-center p-8">
                        <img src="/assets/img/cds-logo.png" alt="CDS Logo" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Member Drive -->
        <div class="hero-slide bg-[#e0f2e9]">
            <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8 grid items-center gap-8 lg:grid-cols-2 py-16 lg:py-20">
                <div class="flex flex-col items-start text-left">
                    <h2 class="font-serif-bn text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl text-cds-blue">
                        <span data-lang="bn">সদস্য নিবন্ধন চলছে!</span>
                        <span data-lang="en" class="hidden">Membership Registration Open!</span>
                    </h2>
                    <p class="mt-4 text-base sm:text-lg leading-relaxed text-slate-700 max-w-lg">
                        <span data-lang="bn">সাধারণ, আজীবন, দাতা, উপদেষ্টা এবং যুব/ছাত্র ক্যাটাগরিতে সদস্য হিসেবে যুক্ত হোন।</span>
                        <span data-lang="en" class="hidden">Join as a General, Life, Donor, Advisor or Youth/Student member today.</span>
                    </p>
                    <a href="https://membership.fuminds.com/" target="_blank" class="mt-8 inline-flex items-center gap-2 rounded-full bg-[#15803d] px-6 py-3 text-sm font-bold text-white shadow-lg hover:bg-green-800 transition">
                        <span data-lang="bn">এখনই যোগ দিন</span><span data-lang="en" class="hidden">JOIN NOW</span>
                    </a>
                </div>
                <div class="hidden lg:flex justify-end">
                    <div class="w-[420px] h-[280px] bg-white rounded-2xl shadow-xl flex items-center justify-center p-8">
                        <svg viewBox="0 0 200 200" class="w-48 h-48 text-green-600" fill="none" stroke="currentColor" stroke-width="4">
                            <circle cx="80" cy="70" r="35"/><path d="M20 180c10-40 35-60 60-60s50 20 60 60"/><path d="M130 40 l50 0 M155 15 l0 50" stroke-width="6" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3: Latest Projects -->
        <div class="hero-slide bg-[#e8eaf6]">
            <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8 grid items-center gap-8 lg:grid-cols-2 py-16 lg:py-20">
                <div class="flex flex-col items-start text-left">
                    <h2 class="font-serif-bn text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl text-cds-blue">
                        <span data-lang="bn">আমাদের চলমান উদ্যোগ দেখুন</span>
                        <span data-lang="en" class="hidden">Explore Our Active Programs</span>
                    </h2>
                    <p class="mt-4 text-base sm:text-lg leading-relaxed text-slate-700 max-w-lg">
                        <span data-lang="bn">শিক্ষা, স্বাস্থ্য, পরিবেশ ও সামাজিক উন্নয়নে আমাদের বিভিন্ন কার্যক্রম জানুন।</span>
                        <span data-lang="en" class="hidden">Discover our initiatives in education, health, environment and social development.</span>
                    </p>
                    <a href="/projects.php" class="mt-8 inline-flex items-center gap-2 rounded-full bg-[#1e3a8a] px-6 py-3 text-sm font-bold text-white shadow-lg hover:bg-blue-900 transition">
                        <span data-lang="bn">সকল প্রজেক্ট দেখুন</span><span data-lang="en" class="hidden">VIEW ALL PROJECTS</span>
                    </a>
                </div>
                <div class="hidden lg:flex justify-end">
                    <div class="w-[420px] h-[280px] bg-white rounded-2xl shadow-xl flex items-center justify-center p-8">
                        <svg viewBox="0 0 200 200" class="w-48 h-48 text-indigo-600" fill="none" stroke="currentColor" stroke-width="4">
                            <rect x="20" y="60" width="160" height="120" rx="8"/><path d="M60 60 V40 a40 40 0 0 1 80 0 V60"/><path d="M60 110 h80 M60 140 h50" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Dots Navigation -->
    <div class="hero-dots" id="heroDots">
        <button class="hero-dot active" data-slide="0" aria-label="Slide 1"></button>
        <button class="hero-dot" data-slide="1" aria-label="Slide 2"></button>
        <button class="hero-dot" data-slide="2" aria-label="Slide 3"></button>
    </div>
</section>

<script>
(function() {
    const track = document.getElementById('heroTrack');
    const dots = document.querySelectorAll('.hero-dot');
    let current = 0;
    const total = dots.length;
    let autoplayTimer;

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    function startAutoplay() {
        autoplayTimer = setInterval(() => goTo(current + 1), 5000);
    }

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            clearInterval(autoplayTimer);
            goTo(parseInt(dot.dataset.slide));
            startAutoplay();
        });
    });

    startAutoplay();
})();
</script>



<!-- 2. ABOUT / MISSION SECTION -->
<section class="py-16 sm:py-24 bg-white relative">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 items-start">
            <div>
                <h2 class="font-serif-bn text-3xl font-bold sm:text-4xl text-cds-blue leading-snug">
                    <span data-lang="bn">সিডিএস নাগরিক উদ্যোগ ও সিভিল সোসাইটিকে শক্তিশালী করে দেশজুড়ে।</span>
                    <span data-lang="en" class="hidden">CDS civil society alliance strengthens citizen action and civil society around the country</span>
                </h2>
            </div>
            <div class="space-y-6 text-slate-700 text-lg leading-relaxed">
                <p>
                    <span data-lang="bn">কুমিল্লার নাঙ্গলকোট ও লালমাই উপজেলা থেকে শুরু করে সমগ্র বাংলাদেশে প্রান্তিক জনপদের উন্নয়নে আমরা কাজ করে যাচ্ছি। আমাদের লক্ষ্য — একটি অন্তর্ভুক্তিমূলক, ন্যায়ভিত্তিক ও টেকসই সমাজ।</span>
                    <span data-lang="en" class="hidden">Working for the development of marginalized communities across Bangladesh, starting from Nangalkot and Lalmai upazilas of Cumilla. Our goal is an inclusive, just, and sustainable society.</span>
                </p>
                <p>
                    <span data-lang="bn">সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক এবং উন্নত বাংলাদেশ বিনির্মাণে আমরা ঐক্যবদ্ধ।</span>
                    <span data-lang="en" class="hidden">We are united to build quality education, good governance, health, active citizenship, and a prosperous Bangladesh.</span>
                </p>
            </div>
        </div>
        
        <!-- Large Featured Image -->
        <div class="mt-16 text-center">
             <div class="inline-block relative">
                 <!-- Hands Image Placeholder -->
                 <div class="w-full max-w-[600px] aspect-[16/9] mx-auto bg-slate-100 rounded-2xl overflow-hidden shadow-md flex items-center justify-center relative">
                     <img src="/assets/img/gallery-placeholder.jpg" alt="Our lives in your hands" class="w-full h-full object-cover opacity-80 mix-blend-multiply" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400'><rect width='100%' height='100%' fill='%23f1f5f9'/><text x='50%' y='50%' font-family='sans-serif' font-size='24' fill='%2394a3b8' text-anchor='middle'>CDS Community</text></svg>'">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                 </div>
             </div>
        </div>
    </div>
    
    <!-- Geometric Divider -->
    <div class="mt-16 geometric-divider"></div>
</section>

<!-- 3. WHAT WE DO (5 Pillars) -->
<section class="py-16 bg-slate-50 relative">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="font-serif-bn text-3xl font-bold sm:text-4xl text-cds-blue">
                <span data-lang="bn">আমাদের মূলমন্ত্র (What We Do)</span>
                <span data-lang="en" class="hidden">What We Do</span>
            </h2>
        </div>
        
        <!-- 5 Cards Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col border border-slate-100">
                <div class="aspect-video bg-blue-100 flex items-center justify-center text-blue-500">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-12 h-12"><path d="M3 8l9-4 9 4-9 4-9-4z"></path><path d="M7 10v5c0 1.5 2.5 3 5 3s5-1.5 5-3v-5"></path><path d="M21 8v6"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-cds-blue mb-2 text-lg"><span data-lang="bn">সুশিক্ষা</span><span data-lang="en" class="hidden">Quality Education</span></h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow"><span data-lang="bn">শিক্ষাকে সবার জন্য সহজলভ্য ও মানসম্পন্ন করে তোলা।</span><span data-lang="en" class="hidden">Making education accessible and qualitative for everyone.</span></p>
                    <a href="#" class="font-bold text-cds-inverse text-sm hover:underline"><span data-lang="bn">বিস্তারিত &rarr;</span><span data-lang="en" class="hidden">Read More &rarr;</span></a>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col border border-slate-100">
                <div class="aspect-video bg-green-100 flex items-center justify-center text-green-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-12 h-12"><path d="M4 21h16M6 21V10M18 21V10M4 10l8-6 8 6"></path><path d="M10 21v-6h4v6"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-cds-blue mb-2 text-lg"><span data-lang="bn">সুশাসন</span><span data-lang="en" class="hidden">Good Governance</span></h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow"><span data-lang="bn">স্বচ্ছতা, জবাবদিহিতা ও ন্যায়ের চর্চা।</span><span data-lang="en" class="hidden">Practice of transparency, accountability and justice.</span></p>
                    <a href="#" class="font-bold text-cds-inverse text-sm hover:underline"><span data-lang="bn">বিস্তারিত &rarr;</span><span data-lang="en" class="hidden">Read More &rarr;</span></a>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col border border-slate-100">
                <div class="aspect-video bg-rose-100 flex items-center justify-center text-rose-500">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-12 h-12"><path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-cds-blue mb-2 text-lg"><span data-lang="bn">সুস্বাস্থ্য</span><span data-lang="en" class="hidden">Health & Well-being</span></h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow"><span data-lang="bn">প্রতিটি পরিবারে সুস্থ ও নিরাপদ জীবনের নিশ্চয়তা।</span><span data-lang="en" class="hidden">Ensuring healthy and safe life for every family.</span></p>
                    <a href="#" class="font-bold text-cds-inverse text-sm hover:underline"><span data-lang="bn">বিস্তারিত &rarr;</span><span data-lang="en" class="hidden">Read More &rarr;</span></a>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col border border-slate-100">
                <div class="aspect-video bg-amber-100 flex items-center justify-center text-amber-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-12 h-12"><circle cx="12" cy="8" r="3.2"></circle><path d="M5 20c1.5-3.5 4.2-5 7-5s5.5 1.5 7 5"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-cds-blue mb-2 text-lg"><span data-lang="bn">সুনাগরিক</span><span data-lang="en" class="hidden">Active Citizenship</span></h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow"><span data-lang="bn">দায়িত্বশীল ও সচেতন নাগরিক গড়ে তোলা।</span><span data-lang="en" class="hidden">Building responsible and conscious citizens.</span></p>
                    <a href="#" class="font-bold text-cds-inverse text-sm hover:underline"><span data-lang="bn">বিস্তারিত &rarr;</span><span data-lang="en" class="hidden">Read More &rarr;</span></a>
                </div>
            </div>
            <!-- Card 5 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col border border-slate-100">
                <div class="aspect-video bg-purple-100 flex items-center justify-center text-purple-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-12 h-12"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-cds-blue mb-2 text-lg"><span data-lang="bn">উন্নত বাংলাদেশ</span><span data-lang="en" class="hidden">Prosperous BD</span></h3>
                    <p class="text-sm text-slate-600 mb-4 flex-grow"><span data-lang="bn">সমৃদ্ধ, স্বনির্ভর ও আধুনিক বাংলাদেশ বিনির্মাণ।</span><span data-lang="en" class="hidden">Building a prosperous, self-reliant and modern Bangladesh.</span></p>
                    <a href="#" class="font-bold text-cds-inverse text-sm hover:underline"><span data-lang="bn">বিস্তারিত &rarr;</span><span data-lang="en" class="hidden">Read More &rarr;</span></a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 border-t border-slate-200 pt-6">
             <div class="flex items-center gap-4 text-sm text-slate-600 justify-center">
                 <span><span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span></span> / 
                 <span><span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">About Us</span></span> / 
                 <span><span data-lang="bn">আমাদের লক্ষ্য</span><span data-lang="en" class="hidden">Our Mission</span></span>
                 <a href="/about.php" class="ml-4 rounded-full bg-cds-blue text-white px-4 py-1 font-bold text-xs"><span data-lang="bn">সদস্য হোন</span><span data-lang="en" class="hidden">JOIN</span></a>
             </div>
        </div>
    </div>
</section>

<!-- 4. LATEST UPDATES (Projects & Notices) -->
<section class="py-16 bg-white">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <h2 class="font-serif-bn text-3xl font-bold text-cds-blue">
                <span data-lang="bn">সাম্প্রতিক আপডেট</span>
                <span data-lang="en" class="hidden">Latest Updates</span>
            </h2>
            <a href="/news-and-stories.php" class="inline-flex items-center gap-2 rounded-full bg-cds-blue px-5 py-2 text-sm font-bold text-white transition hover:bg-blue-800">
                <span data-lang="bn">সব আপডেট দেখুন</span><span data-lang="en" class="hidden">View all updates</span>
            </a>
        </div>
        
        <div class="grid lg:grid-cols-[1.5fr_1fr] gap-6">
            <!-- Left Column: 2 Large Cards (Projects) -->
            <div class="grid sm:grid-cols-2 gap-6">
                <?php $count = 0; foreach ($latest_projects as $p): if($count >= 2) break; $count++; ?>
                <a href="/projects.php" class="block group">
                    <div class="aspect-[16/10] bg-slate-100 rounded-xl overflow-hidden relative mb-4">
                        <?php 
                            $img_path = 'uploads/projects/' . $p['cover_image'];
                            if (!empty($p['cover_image']) && file_exists(__DIR__ . '/' . $img_path)): 
                        ?>
                            <img src="/<?php echo e($img_path); ?>" alt="Project" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-green-500 to-blue-600"></div>
                        <?php endif; ?>
                        <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur text-cds-inverse text-xs font-bold px-3 py-1 rounded-md uppercase">
                             <?php echo $p['status'] === 'completed' ? 'Completed' : 'Ongoing'; ?>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg text-cds-blue mb-2 line-clamp-2"><?php echo e($p['title_bn']); ?></h3>
                    <p class="text-sm text-slate-500 mb-2"><?php echo date('d M, Y', strtotime($p['created_at'])); ?> <span class="mx-2">|</span> <span class="bg-cyan-100 text-cyan-800 px-2 py-0.5 rounded text-xs">Projects</span></p>
                </a>
                <?php endforeach; ?>
            </div>
            
            <!-- Right Column: 2 Stacked Cards (Notices) -->
            <div class="flex flex-col gap-6">
                <?php $count = 0; foreach ($latest_notices as $n): if($count >= 2) break; $count++; ?>
                <a href="/notice.php" class="flex gap-4 group">
                    <div class="w-32 h-32 shrink-0 bg-slate-100 rounded-xl overflow-hidden relative">
                         <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center text-slate-400">
                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                         </div>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h3 class="font-bold text-md text-cds-blue mb-2 line-clamp-2 group-hover:text-blue-700 transition"><?php echo e($n['title_bn']); ?></h3>
                        <p class="text-sm text-slate-500 mb-2"><?php echo date('d M, Y', strtotime($n['created_at'])); ?> <span class="mx-2">|</span> <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">Notice</span></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- 5. ENGAGE AND ACT -->
<section class="py-16 bg-slate-50 border-t border-slate-200">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h2 class="font-serif-bn text-3xl font-bold text-cds-blue mb-2">
                <span data-lang="bn">যোগদান করুন</span>
                <span data-lang="en" class="hidden">Engage and Act</span>
            </h2>
            <p class="text-slate-600 max-w-2xl mb-6">
                <span data-lang="bn">সিডিএস-এর বিভিন্ন কার্যক্রমে অংশ নিন এবং সমাজের ইতিবাচক পরিবর্তনে ভূমিকা রাখুন।</span>
                <span data-lang="en" class="hidden">Be part of the change. Stand in solidarity with citizen action and civil society around the world.</span>
            </p>
            <a href="https://membership.fuminds.com/" class="inline-flex items-center gap-2 rounded-full bg-cds-blue px-5 py-2 text-sm font-bold text-white transition hover:bg-blue-800">
                <span data-lang="bn">সকল কার্যক্রম দেখুন</span><span data-lang="en" class="hidden">View all Campaigns</span>
            </a>
        </div>
        
        <div class="grid sm:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <a href="https://membership.fuminds.com/" class="group block relative rounded-2xl overflow-hidden aspect-[4/3] bg-cds-blue">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900 to-blue-700 p-6 flex flex-col justify-center items-center text-center">
                    <h3 class="font-bold text-2xl text-yellow-400 mb-2">Volunteer Challenge</h3>
                    <p class="text-white/80 text-sm">Join our network of changemakers</p>
                </div>
                <div class="absolute bottom-0 w-full bg-white p-4 font-bold text-cds-blue text-sm transition group-hover:bg-slate-50">
                    <span data-lang="bn">ভলান্টিয়ার চ্যালেঞ্জ</span><span data-lang="en" class="hidden">Volunteer Challenge</span>
                </div>
            </a>
            <!-- Card 2 -->
            <a href="/projects.php" class="group block relative rounded-2xl overflow-hidden aspect-[4/3] bg-slate-200">
                <div class="absolute inset-0 p-6 flex flex-col justify-center items-center text-center">
                     <img src="/assets/img/gallery-placeholder.jpg" alt="Innovation" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-50" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='300'><rect width='100%' height='100%' fill='%23e2e8f0'/></svg>'">
                </div>
                <div class="absolute bottom-0 w-full bg-white p-4 font-bold text-cds-blue text-sm transition group-hover:bg-slate-50 z-10">
                    <span data-lang="bn">সিডিএস ইনোভেশন অ্যাওয়ার্ডস</span><span data-lang="en" class="hidden">CDS Innovation Awards</span>
                </div>
            </a>
            <!-- Card 3 -->
            <a href="/contact.php" class="group block relative rounded-2xl overflow-hidden aspect-[4/3] bg-emerald-50">
                <div class="absolute inset-0 p-6 flex flex-col justify-center items-center text-center">
                     <h3 class="font-bold text-4xl text-emerald-700 tracking-tighter leading-none mb-2 uppercase">STAND<br>AS MY<br>WITNESS</h3>
                </div>
                <div class="absolute bottom-0 w-full bg-white p-4 font-bold text-cds-blue text-sm transition group-hover:bg-slate-50 z-10">
                    <span data-lang="bn">আমাদের সাথে কাজ করুন</span><span data-lang="en" class="hidden">Stand As My Witness</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- 6. NEWSLETTER / BOTTOM CTA -->
<section class="bg-amber-100 py-16">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-8 items-center">
        <div>
            <h2 class="font-serif-bn text-3xl font-bold text-slate-900 mb-6">
                <span data-lang="bn">আমাদের নিউজলেটার সাইন আপ করুন</span>
                <span data-lang="en" class="hidden">Sign up for our newsletters</span>
            </h2>
            <a href="https://membership.fuminds.com/" class="inline-flex items-center gap-2 rounded-full bg-cds-blue px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                <span data-lang="bn">নিউজলেটার পান</span><span data-lang="en" class="hidden">Get Newsletters</span>
            </a>
        </div>
        <div class="flex justify-center md:justify-end relative">
            <div class="w-[300px] h-[200px] bg-cds-blue rounded-2xl overflow-hidden relative shadow-lg rotate-3 transform">
                <img src="/assets/img/gallery-placeholder.jpg" alt="Hands" class="absolute inset-0 w-full h-full object-cover mix-blend-screen opacity-80" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='300' height='200'><rect width='100%' height='100%' fill='%230e1b64'/></svg>'">
                <!-- geometric accent -->
                <div class="absolute top-4 right-4 w-6 h-6 rounded-full bg-amber-400"></div>
                <div class="absolute bottom-10 -left-10 w-40 h-4 bg-cyan-400"></div>
                <div class="absolute -top-4 left-10 w-0 h-0 border-l-[15px] border-l-transparent border-r-[15px] border-r-transparent border-b-[25px] border-b-rose-500 rotate-45"></div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
