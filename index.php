<?php
require_once __DIR__ . '/includes/auth.php';
init_secure_session();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';
require_once __DIR__ . '/includes/csrf.php';

$pdo = Database::getConnection();

// Fetch latest 2 projects for featured updates
$stmt = $pdo->prepare("SELECT * FROM projects ORDER BY created_at DESC LIMIT 2");
$stmt->execute();
$latest_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch latest 3 notices for sidebar updates
$stmt = $pdo->prepare("SELECT * FROM notices ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$latest_notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Embedded Styles for Guaranteed Pixel-Perfect Rendering -->
<style>
    /* Hero Slider Container */
    .cds-hero-slider {
        position: relative;
        overflow: hidden;
        width: 100%;
        background-color: #f8fafc;
    }
    .cds-hero-track {
        display: flex;
        transition: transform 0.65s cubic-bezier(0.25, 1, 0.5, 1);
        width: 100%;
    }
    .cds-hero-slide {
        min-width: 100%;
        width: 100%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
    }
    .cds-hero-dots {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        position: absolute;
        bottom: 24px;
        left: 0;
        right: 0;
        z-index: 20;
    }
    .cds-hero-dot {
        width: 12px;
        height: 12px;
        border-radius: 9999px;
        background-color: rgba(14, 27, 100, 0.25);
        border: none;
        cursor: pointer;
        padding: 0;
        transition: all 0.3s ease;
    }
    .cds-hero-dot.active {
        width: 36px;
        background-color: #0e1b64;
        border-radius: 9999px;
    }
    .cds-slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(4px);
        color: #0e1b64;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(14, 27, 100, 0.1);
        cursor: pointer;
        z-index: 20;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .cds-slider-arrow:hover {
        background-color: #0e1b64;
        color: #ffffff;
        transform: translateY(-50%) scale(1.08);
    }
    .cds-slider-arrow.prev { left: 16px; }
    .cds-slider-arrow.next { right: 16px; }

    /* Button Styles */
    .cds-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #0e1b64;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.9375rem;
        padding: 14px 28px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(14, 27, 100, 0.2);
    }
    .cds-btn-primary:hover {
        background-color: #0345bf;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(14, 27, 100, 0.3);
    }
    .cds-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #3A7D5C;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.9375rem;
        padding: 14px 28px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(58, 125, 92, 0.25);
    }
    .cds-btn-secondary:hover {
        background-color: #2b5f45;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(58, 125, 92, 0.35);
    }
    .cds-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 2px solid #0e1b64;
        color: #0e1b64 !important;
        font-weight: 700;
        font-size: 0.875rem;
        padding: 10px 22px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        transition: all 0.25s ease;
        background-color: transparent;
    }
    .cds-btn-outline:hover {
        background-color: #0e1b64;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    /* Pillar Card */
    .cds-pillar-card {
        background-color: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .cds-pillar-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px -8px rgba(14, 27, 100, 0.12);
        border-color: #cbd5e1;
    }
    .cds-pillar-top {
        aspect-ratio: 4 / 3;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    /* Section Geometric Divider */
    .cds-geometric-ribbon {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 24px 16px;
    }
</style>

<!-- 1. HERO SLIDER SECTION -->
<section class="cds-hero-slider">
    <div class="cds-hero-track" id="heroTrack">
        <!-- Slide 1: Welcome & Mission -->
        <div class="cds-hero-slide bg-[#c7edf3]">
            <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24 w-full">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="space-y-6 text-left">
                        <div class="inline-flex items-center gap-2 bg-[#0e1b64]/10 text-[#0e1b64] px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-[#0e1b64] animate-pulse"></span>
                            <span data-lang="bn">সিটিজেন ডেভেলপমেন্ট সোসাইটি</span>
                            <span data-lang="en" class="hidden">Citizen Development Society</span>
                        </div>
                        <h1 class="font-serif-bn font-black text-3xl sm:text-4xl lg:text-5xl xl:text-6xl text-[#0e1b64] leading-[1.15]">
                            <span data-lang="bn">নাগরিক ক্ষমতায়নে একটি সমৃদ্ধ ও মানবিক সমাজ বিনির্মাণে</span>
                            <span data-lang="en" class="hidden">Empowering Citizens to Build an Inclusive & Just Society</span>
                        </h1>
                        <p class="text-base sm:text-lg text-slate-700 max-w-xl leading-relaxed">
                            <span data-lang="bn">সিডিএস একটি অরাজনৈতিক, অলাভজনক ও স্বেচ্ছাসেবী সামাজিক সংস্থা। সুশিক্ষা, সুশাসন, সুস্বাস্থ্য ও সুনাগরিক গড়ে তোলাই আমাদের অঙ্গীকার।</span>
                            <span data-lang="en" class="hidden">CDS is a non-political, non-profit and voluntary civil society organization dedicated to quality education, good governance, and active citizenship.</span>
                        </p>
                        <div class="pt-2 flex flex-wrap gap-4 items-center">
                            <a href="https://membership.fuminds.com/" target="_blank" class="cds-btn-primary">
                                <span data-lang="bn">সদস্য হোন</span>
                                <span data-lang="en" class="hidden">JOIN CDS</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="#about" class="cds-btn-outline">
                                <span data-lang="bn">আমাদের লক্ষ্য</span>
                                <span data-lang="en" class="hidden">OUR MISSION</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-center lg:justify-end">
                        <div class="relative w-full max-w-[540px]">
                            <div class="absolute -inset-2 bg-white/40 rounded-3xl blur-md -rotate-1"></div>
                            <img src="/assets/img/hero-slide-1.jpg" alt="CDS Community" class="relative z-10 w-full h-[280px] sm:h-[340px] lg:h-[380px] object-cover rounded-2xl shadow-xl border-4 border-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Membership Open -->
        <div class="cds-hero-slide bg-[#d7f1e5]">
            <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24 w-full">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="space-y-6 text-left">
                        <div class="inline-flex items-center gap-2 bg-[#3A7D5C]/15 text-[#245b3f] px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-[#3A7D5C] animate-pulse"></span>
                            <span data-lang="bn">সদস্য নিবন্ধন চলছে</span>
                            <span data-lang="en" class="hidden">Membership Open</span>
                        </div>
                        <h1 class="font-serif-bn font-black text-3xl sm:text-4xl lg:text-5xl xl:text-6xl text-[#0e1b64] leading-[1.15]">
                            <span data-lang="bn">সিডিএস-এর সদস্য হয়ে সমাজের ইতিবাচক পরিবর্তনে যুক্ত হোন</span>
                            <span data-lang="en" class="hidden">Become a Member & Drive Meaningful Social Change</span>
                        </h1>
                        <p class="text-base sm:text-lg text-slate-700 max-w-xl leading-relaxed">
                            <span data-lang="bn">সাধারণ, আজীবন, দাতা, উপদেষ্টা ও ছাত্র/যুব ক্যাটাগরিতে সদস্য হতে পারেন। আপনার সক্রিয় অংশগ্রহণ আমাদের শক্তি।</span>
                            <span data-lang="en" class="hidden">Join under General, Life, Donor, Advisor or Youth categories and be a key part of our grassroots impact.</span>
                        </p>
                        <div class="pt-2 flex flex-wrap gap-4 items-center">
                            <a href="https://membership.fuminds.com/" target="_blank" class="cds-btn-secondary">
                                <span data-lang="bn">অনলাইনে ফরম পূরণ করুন</span>
                                <span data-lang="en" class="hidden">APPLY ONLINE</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="/member-criteria.php" class="cds-btn-outline">
                                <span data-lang="bn">সদস্যতার নিয়মাবলী</span>
                                <span data-lang="en" class="hidden">MEMBERSHIP RULES</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-center lg:justify-end">
                        <div class="relative w-full max-w-[540px]">
                            <div class="absolute -inset-2 bg-white/40 rounded-3xl blur-md rotate-1"></div>
                            <img src="/assets/img/hero-slide-2.jpg" alt="Membership Drive" class="relative z-10 w-full h-[280px] sm:h-[340px] lg:h-[380px] object-cover rounded-2xl shadow-xl border-4 border-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3: Active Initiatives -->
        <div class="cds-hero-slide bg-[#e3e8f8]">
            <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24 w-full">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="space-y-6 text-left">
                        <div class="inline-flex items-center gap-2 bg-[#0e1b64]/10 text-[#0e1b64] px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-[#0e1b64] animate-pulse"></span>
                            <span data-lang="bn">আমাদের উদ্যোগ ও প্রকল্প</span>
                            <span data-lang="en" class="hidden">Our Projects</span>
                        </div>
                        <h1 class="font-serif-bn font-black text-3xl sm:text-4xl lg:text-5xl xl:text-6xl text-[#0e1b64] leading-[1.15]">
                            <span data-lang="bn">শিক্ষা, স্বাস্থ্য ও সুশাসনের লক্ষ্যে মাঠপর্যায়ে বাস্তবসম্মত উদ্যোগ</span>
                            <span data-lang="en" class="hidden">Real Grassroots Initiatives in Education, Health & Governance</span>
                        </h1>
                        <p class="text-base sm:text-lg text-slate-700 max-w-xl leading-relaxed">
                            <span data-lang="bn">কুমিল্লা নাঙ্গলকোট ও লালমাই উপজেলাসহ সমগ্র বাংলাদেশে প্রান্তিক জনগোষ্ঠীকে এগিয়ে নিতে আমরা নিবেদিতপ্রাণ।</span>
                            <span data-lang="en" class="hidden">Dedicated to uplifting underprivileged communities across Nangalkot, Lalmai and all over Bangladesh.</span>
                        </p>
                        <div class="pt-2 flex flex-wrap gap-4 items-center">
                            <a href="/projects.php" class="cds-btn-primary">
                                <span data-lang="bn">চলমান প্রজেক্টসমূহ</span>
                                <span data-lang="en" class="hidden">VIEW PROJECTS</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="/publications.php" class="cds-btn-outline">
                                <span data-lang="bn">প্রকাশনা ও প্রতিবেদন</span>
                                <span data-lang="en" class="hidden">PUBLICATIONS</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-center lg:justify-end">
                        <div class="relative w-full max-w-[540px] flex items-center justify-center p-8 bg-white rounded-2xl shadow-xl border-4 border-white min-h-[280px] sm:min-h-[340px] lg:min-h-[380px]">
                            <img src="/assets/img/cds-logo.png" alt="CDS Bangladesh" class="max-h-[200px] w-auto object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Arrows -->
    <button class="cds-slider-arrow prev" id="sliderPrev" aria-label="Previous Slide">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button class="cds-slider-arrow next" id="sliderNext" aria-label="Next Slide">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
    </button>

    <!-- Dots Indicators -->
    <div class="cds-hero-dots" id="heroDots">
        <button class="cds-hero-dot active" data-slide="0" aria-label="Slide 1"></button>
        <button class="cds-hero-dot" data-slide="1" aria-label="Slide 2"></button>
        <button class="cds-hero-dot" data-slide="2" aria-label="Slide 3"></button>
    </div>
</section>

<!-- 2. ABOUT / MISSION SECTION (CIVICUS 2-Column Style) -->
<section id="about" class="bg-white py-20 lg:py-24">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">
            <div class="lg:col-span-6 space-y-4">
                <div class="w-12 h-1 bg-[#3A7D5C] rounded-full mb-4"></div>
                <h2 class="font-serif-bn font-black text-3xl sm:text-4xl lg:text-5xl text-[#0e1b64] leading-[1.2]">
                    <span data-lang="bn">সিডিএস নাগরিক উদ্যোগ ও সিভিল সোসাইটিকে শক্তিশালী করে দেশজুড়ে।</span>
                    <span data-lang="en" class="hidden">CDS strengthens citizen action and civil society across Bangladesh.</span>
                </h2>
            </div>
            <div class="lg:col-span-6 space-y-6 text-slate-700 text-lg leading-relaxed pt-2">
                <p>
                    <span data-lang="bn">কুমিল্লার নাঙ্গলকোট ও লালমাই উপজেলা থেকে শুরু করে সমগ্র বাংলাদেশে প্রান্তিক জনপদের উন্নয়নে আমরা কাজ করে যাচ্ছি। আমাদের লক্ষ্য — একটি অন্তর্ভুক্তিমূলক, ন্যায়ভিত্তিক ও টেকসই সমাজ।</span>
                    <span data-lang="en" class="hidden">Starting from Nangalkot and Lalmai upazilas in Cumilla to across Bangladesh, we work for the development of marginalized communities. Our goal is an inclusive, equitable, and sustainable society.</span>
                </p>
                <p>
                    <span data-lang="bn">সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক এবং উন্নত বাংলাদেশ বিনির্মাণে আমরা সকল শ্রেণি-পেশার মানুষকে সাথে নিয়ে ঐক্যবদ্ধভাবে কাজ করছি।</span>
                    <span data-lang="en" class="hidden">We unite with people from all walks of life to build quality education, good governance, community health, active citizenship, and a prosperous nation.</span>
                </p>
                <div class="pt-4 flex items-center gap-6">
                    <a href="/about.php" class="cds-btn-outline">
                        <span data-lang="bn">আমাদের সম্পর্কে আরও জানুন</span>
                        <span data-lang="en" class="hidden">READ ABOUT US</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Featured Banner -->
        <div class="mt-16 rounded-2xl overflow-hidden shadow-2xl relative aspect-[21/9] max-h-[480px]">
            <img src="/assets/img/hero-slide-1.jpg" alt="CDS Community" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0e1b64]/90 via-[#0e1b64]/30 to-transparent flex items-end p-6 sm:p-10">
                <div class="text-white max-w-2xl">
                    <p class="text-sm uppercase tracking-widest text-[#38bdf8] font-bold mb-1">
                        <span data-lang="bn">আমাদের মূল শক্তি</span>
                        <span data-lang="en" class="hidden">OUR CORE STRENGTH</span>
                    </p>
                    <h3 class="font-serif-bn font-bold text-2xl sm:text-3xl text-white">
                        <span data-lang="bn">সচেতন নাগরিকের সক্রিয় অংশগ্রহণে বদলে যায় সমাজ</span>
                        <span data-lang="en" class="hidden">Active participation of conscious citizens transforms society</span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. GEOMETRIC DIVIDER (CDS Brand Colors) -->
<div class="bg-white border-y border-slate-100">
    <div class="cds-geometric-ribbon max-w-[1360px] mx-auto">
        <svg width="32" height="32" viewBox="0 0 32 32"><rect width="32" height="32" rx="4" fill="#0e1b64"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#3A7D5C"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><polygon points="16,0 32,32 0,32" fill="#0284c7"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><rect width="32" height="32" rx="16" fill="#10b981"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><polygon points="16,0 32,16 16,32 0,16" fill="#1e3a8a"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#047857"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><rect width="32" height="32" rx="4" fill="#f59e0b"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><polygon points="0,0 32,0 16,32" fill="#0345bf"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#0d9488"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><rect width="32" height="32" rx="8" fill="#15803d"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><polygon points="16,0 32,32 0,32" fill="#0e1b64"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#3A7D5C"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><rect width="32" height="32" rx="4" fill="#0284c7"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><polygon points="0,0 32,0 16,32" fill="#10b981"/></svg>
        <svg width="32" height="32" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#1e3a8a"/></svg>
    </div>
</div>

<!-- 4. WHAT WE DO (5 PILLARS / মূলমন্ত্র) -->
<section class="bg-slate-50 py-20">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-block bg-[#0e1b64]/10 text-[#0e1b64] font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider mb-3">
                <span data-lang="bn">আমাদের ৫টি মূল স্তম্ভ</span>
                <span data-lang="en" class="hidden">OUR 5 PILLARS</span>
            </div>
            <h2 class="font-serif-bn font-black text-3xl sm:text-4xl lg:text-5xl text-[#0e1b64]">
                <span data-lang="bn">আমাদের কার্যক্রম ও মূলমন্ত্র</span>
                <span data-lang="en" class="hidden">What We Do</span>
            </h2>
            <p class="text-slate-600 mt-4 text-base sm:text-lg">
                <span data-lang="bn">সিডিএস-এর ৫টি প্রধান স্তম্ভের মাধ্যমে আমরা টেকসই সামাজিক পরিবর্তন ও উন্নয়ন নিশ্চিত করি।</span>
                <span data-lang="en" class="hidden">Through our five strategic pillars, we ensure sustainable social progress and empowerment.</span>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- 1. সুশিক্ষা -->
            <div class="cds-pillar-card group">
                <div class="cds-pillar-top bg-gradient-to-br from-blue-500 to-indigo-700 text-white p-6">
                    <svg class="w-16 h-16 opacity-95 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                </div>
                <div class="p-6 flex flex-col flex-grow text-left">
                    <h3 class="font-serif-bn font-bold text-xl text-[#0e1b64] mb-2">
                        <span data-lang="bn">সুশিক্ষা</span>
                        <span data-lang="en" class="hidden">Quality Education</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-6 flex-grow leading-relaxed">
                        <span data-lang="bn">শিক্ষাকে সবার জন্য সহজলভ্য, আধুনিক ও মানসম্পন্ন করে তোলা।</span>
                        <span data-lang="en" class="hidden">Making quality, modern education accessible to all learners.</span>
                    </p>
                    <a href="/about.php" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#0345bf] group-hover:text-[#0e1b64] transition">
                        <span data-lang="bn">বিস্তারিত জানুন</span>
                        <span data-lang="en" class="hidden">Read More</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- 2. সুশাসন -->
            <div class="cds-pillar-card group">
                <div class="cds-pillar-top bg-gradient-to-br from-emerald-500 to-green-700 text-white p-6">
                    <svg class="w-16 h-16 opacity-95 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                </div>
                <div class="p-6 flex flex-col flex-grow text-left">
                    <h3 class="font-serif-bn font-bold text-xl text-[#0e1b64] mb-2">
                        <span data-lang="bn">সুশাসন</span>
                        <span data-lang="en" class="hidden">Good Governance</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-6 flex-grow leading-relaxed">
                        <span data-lang="bn">স্বচ্ছতা, জবাবদিহিতা, মানবাধিকার ও সামাজিক ন্যায়ের চর্চা প্রতিষ্ঠা।</span>
                        <span data-lang="en" class="hidden">Promoting transparency, accountability, human rights and justice.</span>
                    </p>
                    <a href="/about.php" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#0345bf] group-hover:text-[#0e1b64] transition">
                        <span data-lang="bn">বিস্তারিত জানুন</span>
                        <span data-lang="en" class="hidden">Read More</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- 3. সুস্বাস্থ্য -->
            <div class="cds-pillar-card group">
                <div class="cds-pillar-top bg-gradient-to-br from-rose-500 to-pink-700 text-white p-6">
                    <svg class="w-16 h-16 opacity-95 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <div class="p-6 flex flex-col flex-grow text-left">
                    <h3 class="font-serif-bn font-bold text-xl text-[#0e1b64] mb-2">
                        <span data-lang="bn">সুস্বাস্থ্য</span>
                        <span data-lang="en" class="hidden">Health & Well-being</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-6 flex-grow leading-relaxed">
                        <span data-lang="bn">প্রতিটি পরিবার ও প্রান্তিক মানুষের সুস্থ এবং নিরাপদ জীবনের নিশ্চয়তা।</span>
                        <span data-lang="en" class="hidden">Ensuring healthy, safe, and dignified lives for every family.</span>
                    </p>
                    <a href="/about.php" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#0345bf] group-hover:text-[#0e1b64] transition">
                        <span data-lang="bn">বিস্তারিত জানুন</span>
                        <span data-lang="en" class="hidden">Read More</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- 4. সুনাগরিক -->
            <div class="cds-pillar-card group">
                <div class="cds-pillar-top bg-gradient-to-br from-amber-500 to-orange-600 text-white p-6">
                    <svg class="w-16 h-16 opacity-95 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="p-6 flex flex-col flex-grow text-left">
                    <h3 class="font-serif-bn font-bold text-xl text-[#0e1b64] mb-2">
                        <span data-lang="bn">সুনাগরিক</span>
                        <span data-lang="en" class="hidden">Active Citizenship</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-6 flex-grow leading-relaxed">
                        <span data-lang="bn">দায়িত্বশীল, সমাজসচেতন ও দেশপ্রেমিক তরুণ নেতৃত্ব গড়ে তোলা।</span>
                        <span data-lang="en" class="hidden">Fostering responsible, conscious, and empowered youth leaders.</span>
                    </p>
                    <a href="/about.php" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#0345bf] group-hover:text-[#0e1b64] transition">
                        <span data-lang="bn">বিস্তারিত জানুন</span>
                        <span data-lang="en" class="hidden">Read More</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- 5. উন্নত বাংলাদেশ -->
            <div class="cds-pillar-card group">
                <div class="cds-pillar-top bg-gradient-to-br from-purple-500 to-violet-700 text-white p-6">
                    <svg class="w-16 h-16 opacity-95 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="p-6 flex flex-col flex-grow text-left">
                    <h3 class="font-serif-bn font-bold text-xl text-[#0e1b64] mb-2">
                        <span data-lang="bn">উন্নত বাংলাদেশ</span>
                        <span data-lang="en" class="hidden">Prosperous BD</span>
                    </h3>
                    <p class="text-sm text-slate-600 mb-6 flex-grow leading-relaxed">
                        <span data-lang="bn">সমৃদ্ধ, স্বনির্ভর ও আধুনিক মানবিক বাংলাদেশ বিনির্মাণে অবদান রাখা।</span>
                        <span data-lang="en" class="hidden">Contributing towards a prosperous, self-reliant and modern Bangladesh.</span>
                    </p>
                    <a href="/about.php" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#0345bf] group-hover:text-[#0e1b64] transition">
                        <span data-lang="bn">বিস্তারিত জানুন</span>
                        <span data-lang="en" class="hidden">Read More</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. LATEST UPDATES (Projects & Notices) -->
<section class="bg-white py-20">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-12 border-b-2 border-[#0e1b64]/10 pb-6">
            <div>
                <div class="text-[#3A7D5C] font-bold text-xs uppercase tracking-wider mb-1">
                    <span data-lang="bn">তাজা খবর ও নোটিশ</span>
                    <span data-lang="en" class="hidden">NEWS & ANNOUNCEMENTS</span>
                </div>
                <h2 class="font-serif-bn font-black text-3xl sm:text-4xl text-[#0e1b64]">
                    <span data-lang="bn">সাম্প্রতিক আপডেট</span>
                    <span data-lang="en" class="hidden">Latest Updates</span>
                </h2>
            </div>
            <a href="/news-and-stories.php" class="cds-btn-outline">
                <span data-lang="bn">সব আপডেট দেখুন</span>
                <span data-lang="en" class="hidden">VIEW ALL UPDATES</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid lg:grid-cols-12 gap-8">
            <!-- Left Side: Featured Projects (2 Cards) -->
            <div class="lg:col-span-7 grid sm:grid-cols-2 gap-6">
                <?php if (!empty($latest_projects)): ?>
                    <?php foreach ($latest_projects as $p): ?>
                        <a href="/projects.php" class="group block bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition duration-300 flex flex-col">
                            <div class="aspect-[16/10] bg-slate-100 overflow-hidden relative">
                                <?php 
                                    $img_path = 'uploads/projects/' . ($p['cover_image'] ?? '');
                                    if (!empty($p['cover_image']) && file_exists(__DIR__ . '/' . $img_path)): 
                                ?>
                                    <img src="/<?php echo e($img_path); ?>" alt="Project" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-[#3A7D5C] to-[#0e1b64] flex items-center justify-center p-6 text-white text-center">
                                        <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute top-3 left-3 bg-white/95 backdrop-blur text-[#0e1b64] text-xs font-bold px-3 py-1 rounded-md shadow-sm uppercase">
                                    <span data-lang="bn">প্রজেক্ট</span>
                                    <span data-lang="en" class="hidden">Project</span>
                                </div>
                            </div>
                            <div class="p-5 flex flex-col flex-grow text-left">
                                <p class="text-xs text-slate-500 mb-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <?php echo date('d M, Y', strtotime($p['created_at'])); ?>
                                </p>
                                <?php $p_title = get_bilingual_title($p); ?>
                                <h3 class="font-serif-bn font-bold text-lg text-[#0e1b64] group-hover:text-[#0345bf] transition line-clamp-2 mb-3">
                                    <span data-lang="bn"><?php echo e($p_title['bn']); ?></span>
                                    <span data-lang="en" class="hidden"><?php echo e($p_title['en']); ?></span>
                                </h3>
                                <span class="mt-auto text-xs font-bold text-[#3A7D5C] group-hover:underline flex items-center gap-1">
                                    <span data-lang="bn">বিস্তারিত দেখুন</span>
                                    <span data-lang="en" class="hidden">Learn More</span>
                                    →
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="sm:col-span-2 p-8 text-center bg-slate-50 rounded-xl text-slate-500">
                        <span data-lang="bn">বর্তমানে কোন প্রজেক্ট পাওয়া যায়নি।</span>
                        <span data-lang="en" class="hidden">No projects found.</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Side: Notices List (3 Cards) -->
            <div class="lg:col-span-5 flex flex-col gap-4">
                <?php if (!empty($latest_notices)): ?>
                    <?php foreach ($latest_notices as $n): ?>
                        <?php $n_title = get_bilingual_title($n); ?>
                        <a href="/notice.php" class="group flex gap-4 bg-slate-50 hover:bg-slate-100 p-4 rounded-xl transition duration-200 border border-slate-200 shadow-sm items-center">
                            <div class="w-20 h-20 shrink-0 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-100 border border-blue-200/60 flex items-center justify-center text-[#0e1b64]">
                                <svg class="w-9 h-9 opacity-80 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="flex-grow text-left">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="bg-blue-100 text-blue-800 text-[11px] font-bold px-2 py-0.5 rounded">
                                        <span data-lang="bn">নোটিশ</span>
                                        <span data-lang="en" class="hidden">Notice</span>
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        <?php echo date('d M, Y', strtotime($n['created_at'])); ?>
                                    </span>
                                </div>
                                <h4 class="font-serif-bn font-bold text-base text-[#0e1b64] group-hover:text-[#0345bf] transition line-clamp-2">
                                    <span data-lang="bn"><?php echo e($n_title['bn']); ?></span>
                                    <span data-lang="en" class="hidden"><?php echo e($n_title['en']); ?></span>
                                </h4>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-[#0e1b64] group-hover:translate-x-1 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-8 text-center bg-slate-50 rounded-xl text-slate-500">
                        <span data-lang="bn">বর্তমানে কোন নোটিশ নেই।</span>
                        <span data-lang="en" class="hidden">No notices available.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- 6. ENGAGE AND ACT SECTION (CIVICUS Style 3-Box Feature) -->
<section class="bg-slate-50 py-20 border-t border-slate-200">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-block bg-[#3A7D5C]/15 text-[#2b5f45] font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider mb-3">
                <span data-lang="bn">নাগরিক উদ্যোগ</span>
                <span data-lang="en" class="hidden">ENGAGE & ACT</span>
            </div>
            <h2 class="font-serif-bn font-black text-3xl sm:text-4xl lg:text-5xl text-[#0e1b64]">
                <span data-lang="bn">যোগদান করুন ও সক্রিয় হোন</span>
                <span data-lang="en" class="hidden">Engage and Act</span>
            </h2>
            <p class="text-slate-600 mt-4 text-base sm:text-lg">
                <span data-lang="bn">সিডিএস-এর বিভিন্ন ক্যাম্পেইন ও কার্যক্রমে অংশ নিয়ে সমাজ গঠনে অগ্রণী ভূমিকা রাখুন।</span>
                <span data-lang="en" class="hidden">Stand together with grassroots communities and take meaningful citizen action.</span>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Box 1: Volunteer Challenge -->
            <a href="https://membership.fuminds.com/" target="_blank" class="group block rounded-2xl overflow-hidden aspect-[4/3] bg-gradient-to-br from-[#0e1b64] to-[#1e3a8a] relative shadow-lg hover:shadow-2xl transition duration-300">
                <div class="absolute inset-0 p-8 flex flex-col justify-center items-center text-center text-white">
                    <h3 class="font-serif-bn font-black text-2xl sm:text-3xl text-white mb-3 leading-tight">
                        <span data-lang="bn">স্বেচ্ছাসেবা</span>
                        <span data-lang="en" class="hidden">VOLUNTEER CHALLENGE</span>
                    </h3>
                    <p class="text-sm text-slate-200 max-w-xs leading-relaxed">
                        <span data-lang="bn">পরিবর্তনের কারিগর হিসেবে আমাদের সাথে স্বেচ্ছাসেবায় যোগ দিন</span>
                        <span data-lang="en" class="hidden">Join our network of grassroots changemakers</span>
                    </p>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-white py-3.5 px-6 flex items-center justify-between text-[#0e1b64] font-bold text-sm group-hover:bg-slate-50 transition">
                    <span data-lang="bn">স্বেচ্ছাসেবী হিসেবে যোগ দিন</span>
                    <span data-lang="en" class="hidden">Join as a Volunteer</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- Box 2: Innovation Awards -->
            <a href="/projects.php" class="group block rounded-2xl overflow-hidden aspect-[4/3] bg-gradient-to-br from-[#3A7D5C] to-[#047857] relative shadow-lg hover:shadow-2xl transition duration-300">
                <div class="absolute inset-0 p-8 flex flex-col justify-center items-center text-center text-white">
                    <h3 class="font-serif-bn font-black text-2xl sm:text-3xl text-white mb-3 leading-tight">
                        <span data-lang="bn">সামাজিক উদ্ভাবন</span>
                        <span data-lang="en" class="hidden">INNOVATION AWARDS</span>
                    </h3>
                    <p class="text-sm text-slate-100 max-w-xs leading-relaxed">
                        <span data-lang="bn">স্থানীয় সমস্যা সমাধানে তরুণদের উদ্ভাবনী আইডিয়াকে স্বীকৃতি</span>
                        <span data-lang="en" class="hidden">Recognizing and scaling youth-led social solutions</span>
                    </p>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-white py-3.5 px-6 flex items-center justify-between text-[#3A7D5C] font-bold text-sm group-hover:bg-slate-50 transition">
                    <span data-lang="bn">উদ্ভাবনী উদ্যোগ দেখুন</span>
                    <span data-lang="en" class="hidden">Explore Innovation</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- Box 3: Stand As My Witness -->
            <a href="/about.php" class="group block rounded-2xl overflow-hidden aspect-[4/3] bg-gradient-to-br from-[#0284c7] to-[#0369a1] relative shadow-lg hover:shadow-2xl transition duration-300">
                <div class="absolute inset-0 p-8 flex flex-col justify-center items-center text-center text-white">
                    <h3 class="font-serif-bn font-black text-2xl sm:text-3xl text-white mb-3 leading-tight">
                        <span data-lang="bn">অধিকার সুরক্ষা</span>
                        <span data-lang="en" class="hidden">STAND AS MY WITNESS</span>
                    </h3>
                    <p class="text-sm text-slate-100 max-w-xs leading-relaxed">
                        <span data-lang="bn">সামাজিক নিরাপত্তা ও মানবাধিকার সুরক্ষায় সচেতন অবস্থান</span>
                        <span data-lang="en" class="hidden">Standing for citizen rights and community justice</span>
                    </p>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-white py-3.5 px-6 flex items-center justify-between text-[#0284c7] font-bold text-sm group-hover:bg-slate-50 transition">
                    <span data-lang="bn">ক্যাম্পেইনে যুক্ত হন</span>
                    <span data-lang="en" class="hidden">Join the Campaign</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- 7. NEWSLETTER / BOTTOM CTA SECTION -->
<section class="bg-[#fef3c7] py-20 border-t border-amber-200">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6 text-left">
                <div class="inline-block bg-amber-200/80 text-amber-900 font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider">
                    <span data-lang="bn">যুক্ত থাকুন আমাদের সাথে</span>
                    <span data-lang="en" class="hidden">STAY CONNECTED</span>
                </div>
                <h2 class="font-serif-bn font-black text-3xl sm:text-4xl lg:text-5xl text-[#0e1b64] leading-[1.2]">
                    <span data-lang="bn">আমাদের নিউজলেটারে সাইন আপ করুন</span>
                    <span data-lang="en" class="hidden">Sign Up for Our Newsletters</span>
                </h2>
                <p class="text-base sm:text-lg text-slate-700 max-w-xl leading-relaxed">
                    <span data-lang="bn">সিডিএস-এর সর্বশেষ কার্যক্রম, প্রকল্প আপডেট, ইভেন্ট ও সমাজ উন্নয়নমূলক প্রতিবেদন সরাসরি আপনার ইনবক্সে পেতে সাবস্ক্রাইব করুন।</span>
                    <span data-lang="en" class="hidden">Get the latest updates, field reports, and publications directly in your inbox.</span>
                </p>
                <form action="https://membership.fuminds.com/" method="GET" class="flex flex-col sm:flex-row gap-3 max-w-md pt-2">
                    <input type="email" placeholder="আপনার ইমেইল অ্যাড্রেস লিখুন..." required class="flex-grow px-4 py-3.5 rounded-lg border border-amber-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0e1b64] text-sm shadow-sm">
                    <button type="submit" class="cds-btn-primary whitespace-nowrap">
                        <span data-lang="bn">সাবস্ক্রাইব</span>
                        <span data-lang="en" class="hidden">SUBSCRIBE</span>
                    </button>
                </form>
            </div>
            <div class="lg:col-span-5 flex justify-center lg:justify-end">
                <div class="relative">
                    <div class="absolute -inset-4 bg-amber-300/40 rounded-3xl -rotate-3 blur-sm"></div>
                    <img src="/assets/img/hero-slide-2.jpg" alt="Join Newsletter" class="relative z-10 w-full max-w-[380px] h-[260px] object-cover rounded-2xl shadow-xl border-4 border-white rotate-2 hover:rotate-0 transition duration-300">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Slider JavaScript Logic -->
<script>
(function() {
    const track = document.getElementById('heroTrack');
    const dots = document.querySelectorAll('.cds-hero-dot');
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');
    let current = 0;
    const total = dots.length;
    let autoplayTimer;

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach((d, i) => {
            d.classList.toggle('active', i === current);
        });
    }

    function startAutoplay() {
        stopAutoplay();
        autoplayTimer = setInterval(() => {
            goTo(current + 1);
        }, 5500);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            clearInterval(autoplayTimer);
        }
    }

    if (dots.length > 0) {
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                stopAutoplay();
                goTo(parseInt(dot.dataset.slide));
                startAutoplay();
            });
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            stopAutoplay();
            goTo(current - 1);
            startAutoplay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            stopAutoplay();
            goTo(current + 1);
            startAutoplay();
        });
    }

    // Pause on hover
    const sliderContainer = document.querySelector('.cds-hero-slider');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', stopAutoplay);
        sliderContainer.addEventListener('mouseleave', startAutoplay);
    }

    startAutoplay();
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
