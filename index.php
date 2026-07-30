<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';

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

<!-- HERO -->
<section class="relative overflow-hidden pt-2 lg:pt-4">
<div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:gap-8 lg:px-8 lg:py-16">
    <div class="relative flex flex-col items-center text-center lg:items-start lg:text-left">
    <h1 class="mt-5 font-serif-bn text-4xl font-bold leading-[1.15] tracking-tight sm:text-5xl lg:text-6xl">
        <span class="bg-gradient-to-br from-primary to-secondary bg-clip-text text-transparent">
            সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)
        </span>
    </h1>
    <span class="inline-flex items-center rounded-full border border-primary/20 bg-primary-soft px-4 py-1.5 text-sm font-semibold text-primary mt-3">
        অরাজনৈতিক, অলাভজনক ও স্বেচ্ছাসেবী সংগঠন
    </span>
    <p class="mt-5 max-w-xl text-base leading-relaxed text-muted-foreground sm:text-lg">
        কুমিল্লার নাঙ্গলকোট ও লালমাই উপজেলা থেকে শুরু করে সমগ্র বাংলাদেশে প্রান্তিক জনপদের উন্নয়নে আমরা কাজ করে যাচ্ছি।
    </p>
    <div class="mt-8 flex flex-wrap justify-center lg:justify-start gap-3">
        <a href="#about" class="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110">
        আমাদের সম্পর্কে জানুন
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        </a>
        <a href="https://membership.fuminds.com/" target="_blank" class="inline-flex items-center gap-2 rounded-full border border-border bg-surface px-5 py-3 text-sm font-semibold text-foreground transition hover:bg-primary-soft hover:text-primary">
        সদস্য হোন
        </a>
    </div>
    </div>

    <!-- Organic SVG art -->
    <div class="relative hidden lg:block">
    <div class="relative aspect-square animate-float">
        <svg viewBox="0 0 500 500" class="absolute inset-0 h-full w-full">
        <defs>
            <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#3A7D5C" />
            <stop offset="100%" stop-color="#1e3a8a" />
            <linearGradient id="g2" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#3A7D5C" stop-opacity="0.15" />
            <stop offset="100%" stop-color="#1e3a8a" stop-opacity="0.15" />
            </linearGradient>
        </defs>
        <path fill="url(#g2)" d="M460 240c0 118-90 210-208 210S48 358 48 240 138 30 256 30s204 92 204 210z" />
        <g fill="white">
            <image href="/assets/img/cds-logo.png" x="114" y="100" width="280" height="280" class="animate-float drop-shadow-2xl" />
        </g>
        </svg>
        <div class="absolute bottom-0 -left-2 rounded-2xl bg-primary p-4 shadow-card animate-[float_5s_ease-in-out_infinite]">
        <div class="text-xs text-white/80">সক্রিয় জেলা</div>
        <div class="font-serif-bn text-2xl font-bold text-white">১</div>
        </div>
        <div class="absolute -right-2 top-8 rounded-2xl bg-primary p-4 shadow-card animate-[float_6s_ease-in-out_infinite]" style="animation-delay: 1s;">
        <div class="text-xs text-white/80">চলমান প্রকল্প</div>
        <div class="font-serif-bn text-2xl font-bold text-white">১</div>
        </div>
    </div>
    </div>
</div>
</section>

<!-- ABOUT PREVIEW -->
<section id="about" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="grid items-center gap-10 lg:grid-cols-[1fr_1.2fr]">
    <div class="relative">
    <div class="aspect-[4/3] overflow-hidden rounded-3xl p-4 sm:p-8 shadow-card bg-surface border border-border flex items-center justify-center">
        <img src="/assets/img/cds-logo.png" alt="CDS Logo" class="h-full w-full object-contain p-2 sm:p-4 opacity-90 drop-shadow-lg" />
    </div>
    </div>
    <div>
    <div class="text-base font-bold uppercase tracking-widest text-primary">আমাদের সম্পর্কে</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
        মানুষের জন্য, মানবতার জন্য
    </h2>
    <p class="mt-4 text-base leading-relaxed text-muted-foreground">
        সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস) একটি অরাজনৈতিক, অলাভজনক, স্বেচ্ছাসেবী ও নাগরিকভিত্তিক সিভিল সোসাইটি সংগঠন। আমাদের লক্ষ্য — একটি অন্তর্ভুক্তিমূলক, ন্যায়ভিত্তিক ও টেকসই সমাজ।

          <a
            href="about.php"
            class="mt-8 inline-flex items-center gap-2 font-semibold text-primary transition-colors hover:text-primary-soft group"
          >
            বিস্তারিত জানুন
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:translate-x-1">
              <path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
          
          <!-- CDS Signature Pillars Ribbon -->
          <div class="mt-8 pt-6 border-t border-border/40 flex flex-wrap gap-3">
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default">সুশিক্ষা</span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default">সুশাসন</span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default">সুস্বাস্থ্য</span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default">সুনাগরিক</span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default">উন্নত বাংলাদেশ</span>
          </div>
        </div>
      </div>
    </section>

<!-- PILLARS -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 text-center">
    <h2 class="font-serif-bn text-3xl font-bold sm:text-4xl">
    আমাদের মূলমন্ত্র
    </h2>
</div>
<style>
    @media (min-width: 768px) {
        .custom-grid-5 {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }
    }
</style>
<div class="grid gap-4 sm:grid-cols-2 custom-grid-5">
    <!-- 1. সুশিক্ষা -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M3 8l9-4 9 4-9 4-9-4z" stroke-linejoin="round"></path><path d="M7 10v5c0 1.5 2.5 3 5 3s5-1.5 5-3v-5" stroke-linecap="round"></path><path d="M21 8v6" stroke-linecap="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold">সুশিক্ষা</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">শিক্ষাকে সবার জন্য সহজলভ্য ও মানসম্পন্ন করে তোলা।</p>
        </div>
    </div>
    
    <!-- 2. সুশাসন -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M4 21h16M6 21V10M18 21V10M4 10l8-6 8 6" stroke-linejoin="round"></path><path d="M10 21v-6h4v6"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold">সুশাসন</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">স্বচ্ছতা, জবাবদিহিতা ও ন্যায়ের চর্চা।</p>
        </div>
    </div>
    
    <!-- 3. সুস্বাস্থ্য -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold">সুস্বাস্থ্য</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">প্রতিটি পরিবারে সুস্থ ও নিরাপদ জীবনের নিশ্চয়তা।</p>
        </div>
    </div>

    <!-- 4. সুনাগরিক -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><circle cx="12" cy="8" r="3.2"></circle><path d="M5 20c1.5-3.5 4.2-5 7-5s5.5 1.5 7 5" stroke-linecap="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold">সুনাগরিক</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">দায়িত্বশীল ও সচেতন নাগরিক গড়ে তোলা।</p>
        </div>
    </div>
    
    <!-- 5. উন্নত বাংলাদেশ -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold">উন্নত বাংলাদেশ</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">সমৃদ্ধ, স্বনির্ভর ও আধুনিক বাংলাদেশ বিনির্মাণ।</p>
        </div>
    </div>
</div>
</section>

<!-- PROGRAMS -->
<section id="programs" class="bg-gray-50 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl text-gray-900">আমাদের কার্যক্রম</h2>
        </div>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            <!-- Program 1 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                </div>
                <h3 class="font-serif-bn text-xl font-bold mb-2">শিক্ষা সহায়তা</h3>
                <p class="text-sm text-gray-600">শিক্ষার্থীদের জন্য শিক্ষা উপকরণ, বৃত্তি, কোচিং সহায়তা ও ক্যারিয়ার গাইডলাইন প্রদান।</p>
            </div>
            <!-- Program 2 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="font-serif-bn text-xl font-bold mb-2">স্বাস্থ্য ক্যাম্প</h3>
                <p class="text-sm text-gray-600">স্বাস্থ্য ক্যাম্প, রক্তদান কর্মসূচি, বিনামূল্যে চিকিৎসা পরামর্শ ও স্বাস্থ্য সচেতনতা কার্যক্রম।</p>
            </div>
            <!-- Program 3 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-serif-bn text-xl font-bold mb-2">পরিবেশ রক্ষা</h3>
                <p class="text-sm text-gray-600">পরিবেশ রক্ষা, বৃক্ষরোপণ, পরিচ্ছন্নতা অভিযান ও জলবায়ু সচেতনতা কর্মসূচি।</p>
            </div>
            <!-- Program 4 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="font-serif-bn text-xl font-bold mb-2">সামাজিক উন্নয়ন</h3>
                <p class="text-sm text-gray-600">যুব নেতৃত্ব উন্নয়ন, মাদকবিরোধী প্রচারণা ও প্রান্তিক জনগোষ্ঠীর উন্নয়নমূলক প্রকল্প গ্রহণ।</p>
            </div>
        </div>
    </div>
</section>


<!-- STATS BAND -->
<section id="impact-stats" class="relative mx-4 my-10 overflow-hidden rounded-3xl bg-brand-gradient px-6 py-14 shadow-card-hover sm:mx-6 lg:mx-auto lg:max-w-7xl lg:px-12">
<svg class="absolute inset-0 h-full w-full opacity-20" viewBox="0 0 800 300" preserveAspectRatio="none">
    <path d="M0 200 Q200 100 400 200 T800 200 V300 H0Z" fill="white" />
</svg>
<div class="relative grid grid-cols-2 gap-8 lg:grid-cols-4">
    <div class="text-center">
        <div class="count-up font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl" data-target="0" data-suffix="">০</div>
        <div class="mt-2 text-sm font-medium text-white/80">স্বেচ্ছাসেবী</div>
    </div>
    <div class="text-center">
        <div class="count-up font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl" data-target="0" data-suffix="">০</div>
        <div class="mt-2 text-sm font-medium text-white/80">সম্পন্ন প্রজেক্ট</div>
    </div>
    <div class="text-center">
        <div class="count-up font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl" data-target="0" data-suffix="">০</div>
        <div class="mt-2 text-sm font-medium text-white/80">উপকারভোগী পরিবার</div>
    </div>
    <div class="text-center">
        <div class="count-up font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl" data-target="1" data-suffix="">১</div>
        <div class="mt-2 text-sm font-medium text-white/80">সক্রিয় জেলা</div>
    </div>
</div>
</section>

<!-- SHORTCUTS -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 flex items-end justify-between gap-4">
    <div>
    <div class="text-base font-bold uppercase tracking-widest text-primary">গুরুত্বপূর্ণ লিংক</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">দ্রুত পৌঁছান</h2>
    </div>
</div>
<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    <a href="/projects.php" class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-card-hover">
        <div class="pointer-events-none absolute -bottom-6 -right-6 h-32 w-32 text-primary/10 transition group-hover:scale-110 group-hover:text-primary/20">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-full w-full"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path d="M3 9h18M8 14h5" stroke-linecap="round"></path></svg>
        </div>
        <div class="relative">
            <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary-soft text-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M7 17L17 7M9 7h8v8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-lg font-bold">প্রজেক্টস</h3>
            <p class="mt-1 text-sm text-muted-foreground">আমাদের চলমান ও সম্পন্ন সকল উন্নয়ন প্রকল্প।</p>
        </div>
    </a>
    <a href="/gallery.php" class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-card-hover">
        <div class="pointer-events-none absolute -bottom-6 -right-6 h-32 w-32 text-primary/10 transition group-hover:scale-110 group-hover:text-primary/20">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-full w-full"><rect x="3" y="5" width="18" height="14" rx="2"></rect><circle cx="9" cy="11" r="2"></circle><path d="M3 17l5-4 5 4 3-3 5 4" stroke-linejoin="round"></path></svg>
        </div>
        <div class="relative">
            <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary-soft text-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M7 17L17 7M9 7h8v8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-lg font-bold">গ্যালারি</h3>
            <p class="mt-1 text-sm text-muted-foreground">সংগঠনের কর্মকাণ্ডের মুহূর্তগুলি।</p>
        </div>
    </a>
    <a href="/notice.php" class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-card-hover">
        <div class="pointer-events-none absolute -bottom-6 -right-6 h-32 w-32 text-primary/10 transition group-hover:scale-110 group-hover:text-primary/20">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-full w-full"><path d="M6 3h9l5 5v13H6z" stroke-linejoin="round"></path><path d="M14 3v6h6M9 13h8M9 17h5" stroke-linecap="round"></path></svg>
        </div>
        <div class="relative">
            <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary-soft text-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M7 17L17 7M9 7h8v8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-lg font-bold">নোটিশ বোর্ড</h3>
            <p class="mt-1 text-sm text-muted-foreground">গুরুত্বপূর্ণ ঘোষণা ও পরিপত্র।</p>
        </div>
    </a>
    <a href="https://membership.fuminds.com/" target="_blank" class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-card-hover">
        <div class="pointer-events-none absolute -bottom-6 -right-6 h-32 w-32 text-primary/10 transition group-hover:scale-110 group-hover:text-primary/20">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-full w-full"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M20 8v6M23 11h-6" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </div>
        <div class="relative">
            <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary-soft text-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M7 17L17 7M9 7h8v8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-lg font-bold">সদস্য ফর্ম</h3>
            <p class="mt-1 text-sm text-muted-foreground">সাধারণ, আজীবন, দাতা, উপদেষ্টা বা ছাত্র সদস্য হিসেবে যুক্ত হোন।</p>
        </div>
    </a>
    <a href="/blogs.php" class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-card-hover">
        <div class="pointer-events-none absolute -bottom-6 -right-6 h-32 w-32 text-primary/10 transition group-hover:scale-110 group-hover:text-primary/20">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-full w-full"><path d="M4 19h16v2H4zm14-4H6V5h12v10z"></path></svg>
        </div>
        <div class="relative">
            <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary-soft text-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M7 17L17 7M9 7h8v8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-lg font-bold">ব্লগ</h3>
            <p class="mt-1 text-sm text-muted-foreground">আমাদের সর্বশেষ খবর ও চিন্তাধারা।</p>
        </div>
    </a>
    <a href="/publications.php" class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-card-hover">
        <div class="pointer-events-none absolute -bottom-6 -right-6 h-32 w-32 text-primary/10 transition group-hover:scale-110 group-hover:text-primary/20">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-full w-full"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
        </div>
        <div class="relative">
            <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary-soft text-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M7 17L17 7M9 7h8v8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-lg font-bold">প্রকাশনা</h3>
            <p class="mt-1 text-sm text-muted-foreground">গবেষণা, ম্যাগাজিন এবং প্রতিবেদন।</p>
        </div>
    </a>
</div>
</section>

<!-- PROJECTS -->
<section id="projects" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 flex flex-wrap items-end justify-between gap-4">
    <div>
    <div class="text-base font-bold uppercase tracking-widest text-primary">ফিচার্ড প্রজেক্ট</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">আমাদের সাম্প্রতিক কাজ</h2>
    </div>
    <a href="/projects.php" class="text-sm font-semibold text-primary hover:underline">
    সব প্রজেক্ট দেখুন &rarr;
    </a>
</div>
<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($latest_projects as $p): ?>
    <article class="group overflow-hidden rounded-2xl border border-border bg-surface shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="relative aspect-[16/10] overflow-hidden">
          <?php 
              $img_path = 'uploads/projects/' . $p['cover_image'];
              if (!empty($p['cover_image']) && file_exists(__DIR__ . '/' . $img_path)): 
          ?>
              <img src="/<?php echo e($img_path); ?>" alt="Project Image" class="absolute inset-0 h-full w-full object-cover">
          <?php else: ?>
              <div class="absolute inset-0 h-full w-full bg-brand-gradient">
                  <svg class="absolute inset-0 h-full w-full opacity-30" viewBox="0 0 400 250">
                    <circle cx="80" cy="60" r="80" fill="white" />
                    <circle cx="320" cy="200" r="120" fill="white" />
                  </svg>
              </div>
          <?php endif; ?>
        <span class="absolute right-3 top-3 rounded-full px-3 py-1 text-xs font-semibold <?php echo $p['status'] === 'completed' ? 'bg-success text-success-foreground' : 'bg-warning text-warning-foreground'; ?>">
            <?php echo $p['status'] === 'completed' ? 'সম্পন্ন' : 'চলমান'; ?>
        </span>
        </div>
        <div class="p-5">
        <div class="text-xs font-medium text-muted-foreground"><?php echo e($p['title_en'] ?? ''); ?></div>
        <h3 class="mt-1 font-serif-bn text-lg font-bold"><?php echo e($p['title_bn']); ?></h3>
        <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><?php echo mb_substr(e($p['description_bn']), 0, 100) . '...'; ?></p>
        <a href="/projects.php" class="mt-4 inline-flex text-sm font-semibold text-primary hover:underline">
            বিস্তারিত &rarr;
        </a>
        </div>
    </article>
    <?php endforeach; ?>
    <?php if(empty($latest_projects)): ?>
        <p class="text-muted-foreground">কোন প্রজেক্ট পাওয়া যায়নি।</p>
    <?php endif; ?>
</div>
</section>

<!-- NOTICES -->
<section id="notices" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="grid gap-10 lg:grid-cols-[1fr_2fr]">
    <div>
    <h2 class="font-serif-bn text-3xl font-bold sm:text-4xl">সাম্প্রতিক নোটিশ</h2>
    <a href="/notice.php" class="mt-6 inline-flex items-center gap-2 rounded-full border border-border bg-surface px-4 py-2 text-sm font-semibold text-primary hover:bg-primary-soft">
        সব নোটিশ দেখুন &rarr;
    </a>
    </div>
    <ul class="divide-y divide-border overflow-hidden rounded-2xl border border-border bg-surface shadow-card">
    <?php foreach ($latest_notices as $n): ?>
        <li class="group flex gap-4 p-5 transition hover:bg-primary-soft/40">
        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
            <path d="M6 3h9l5 5v13H6z M14 3v6h6" stroke-linejoin="round" />
            </svg>
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
            <h3 class="font-serif-bn text-base font-bold"><?php echo e($n['title_bn']); ?></h3>
            <span class="text-xs text-muted-foreground"><?php echo date('d M, Y', strtotime($n['created_at'])); ?></span>
            </div>
            <p class="mt-1 text-sm text-muted-foreground"><?php echo mb_substr(e($n['content_bn']), 0, 80) . '...'; ?></p>
        </div>
        </li>
    <?php endforeach; ?>
    <?php if(empty($latest_notices)): ?>
        <li class="p-5 text-muted-foreground">কোন নোটিশ পাওয়া যায়নি।</li>
    <?php endif; ?>
    </ul>
</div>
</section>

<!-- FAQ -->
<section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 text-center">
    <div class="text-base font-bold uppercase tracking-widest text-primary">প্রশ্নোত্তর</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">প্রায়শই জিজ্ঞাসিত প্রশ্ন</h2>
</div>
<div class="space-y-3">
    <?php
    $faqs = [
        ["q" => "সিডিএস কী ধরনের সংগঠন?", "a" => "এটি একটি অরাজনৈতিক, অলাভজনক, স্বেচ্ছাসেবী ও নাগরিকভিত্তিক সিভিল সোসাইটি সংগঠন (ধারা ২)।"],
        ["q" => "কারা সিডিএস-এর সদস্য হতে পারবেন?", "a" => "যারা সমাজের কল্যাণে কাজ করতে ইচ্ছুক এবং কোনো রাষ্ট্রবিরোধী কর্মকাণ্ডে জড়িত নন (ধারা ১১)। সাধারণ, আজীবন, দাতা, উপদেষ্টা এবং যুব/ছাত্র ক্যাটাগরিতে সদস্য হওয়া যাবে।"],
        ["q" => "সিডিএস-এর সাধারণ সদস্য হওয়ার যোগ্যতা কী?", "a" => "ন্যূনতম ১৮ বছর বয়সী (ধারা ১১)।"],
        ["q" => "কার্যনির্বাহী কমিটি কত সদস্য বিশিষ্ট?", "a" => "২১ সদস্য বিশিষ্ট (ধারা ১৪)।"],
        ["q" => "সিডিএস-এর আয়ের প্রধান উৎস কী?", "a" => "সদস্যদের চাঁদা, এককালীন অনুদান ও অনুদান (ধারা ২২)।"],
        ["q" => "সিডিএস-এর কোনো শাখা কমিটি গঠন করা যাবে কি?", "a" => "হ্যাঁ, দেশের যেকোনো উপজেলা বা শিক্ষাপ্রতিষ্ঠানে শাখা কমিটি গঠন করা যাবে (ধারা ২৫)।"],
        ["q" => "সংগঠনের বিলুপ্তি ঘটলে তহবিলের কী হবে?", "a" => "সমমনা অন্য কোনো সংগঠন বা সরকারি অধিদপ্তরে হস্তান্তর করা হবে (ধারা ২৯)।"],
        ["q" => "সিডিএস কি কোনো রাজনৈতিক দলের সাথে যুক্ত?", "a" => "না, এটি সম্পূর্ণ অরাজনৈতিক ও স্বেচ্ছাসেবী সংগঠন।"],
        ["q" => "সিডিএস এর কর্ম এলাকা কোথায়?", "a" => "প্রাথমিকভাবে কুমিল্লা জেলার নাঙ্গলকোট ও লালমাই উপজেলা। পরবর্তীতে সমগ্র বাংলাদেশে কার্যক্রম পরিচালনা করা হবে (ধারা ৪)।"],
        ["q" => "CDS কীভাবে তহবিল ব্যবহার করে?", "a" => "সকল তহবিল সরাসরি প্রকল্প বাস্তবায়ন, উপকারভোগী সহায়তা ও পরিচালন ব্যয়ে ব্যবহৃত হয়। প্রতি বছর নিরপেক্ষ অডিট কমিটির মাধ্যমে হিসাব নিরীক্ষা করা হয় (ধারা ২৪)।"],
    ];
    foreach($faqs as $i => $f): ?>
    <div class="faq-item overflow-hidden rounded-2xl border bg-surface transition border-border">
        <button class="faq-btn flex w-full items-center justify-between gap-4 px-5 py-4 text-left" aria-expanded="false">
        <span class="font-serif-bn text-base font-semibold"><?php echo $f['q']; ?></span>
        <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-primary-soft text-primary transition">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-4 w-4 faq-icon-open">
            <path d="M12 5v14M5 12h14" stroke-linecap="round" />
            </svg>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-4 w-4 hidden faq-icon-close rotate-45">
            <path d="M12 5v14M5 12h14" stroke-linecap="round" />
            </svg>
        </span>
        </button>
        <div class="faq-content grid transition-[grid-template-rows] duration-300 grid-rows-[0fr] opacity-0">
        <div class="overflow-hidden">
            <p class="px-5 pb-5 text-sm leading-relaxed text-muted-foreground"><?php echo $f['a']; ?></p>
        </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</section>

<!-- DONATE CALLOUT -->
<section id="donate" class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
<div class="relative overflow-hidden rounded-3xl border border-primary/20 bg-surface p-8 shadow-card sm:p-12">
    <div class="absolute -right-16 -top-16 h-72 w-72 rounded-full bg-primary-soft"></div>
    <div class="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-secondary/10"></div>
    <div class="relative grid gap-6 lg:grid-cols-[2fr_1fr] lg:items-center">
    <div>
        <div class="text-base font-bold uppercase tracking-widest text-primary">আপনার সহায়তায়</div>
        <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
        একটি ছোট অনুদান, বদলে দিতে পারে একটি জীবন
        </h2>
        <p class="mt-3 max-w-2xl text-base leading-relaxed text-muted-foreground">
        আপনার প্রতিটি অবদান শিশুর শিক্ষা, মায়ের স্বাস্থ্যসেবা ও তরুণদের প্রশিক্ষণে
        সরাসরি ব্যবহৃত হয়। আজই যুক্ত হোন।
        </p>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
        <a href="/donation.php" class="inline-flex items-center justify-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-card hover:brightness-110">
        অনুদান
        </a>
        <a href="/contact.php" class="inline-flex items-center justify-center gap-2 rounded-full border border-border bg-surface px-6 py-3 text-sm font-semibold text-foreground hover:bg-primary-soft hover:text-primary">
        যোগাযোগ করুন
        </a>
    </div>
    </div>
</div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const convertToBengaliNumber = (num) => {
        const banglaDigits = {
            '0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪',
            '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'
        };
        let numStr = num.toString();
        if (num >= 1000) {
            numStr = new Intl.NumberFormat('en-IN').format(num);
        }
        return numStr.replace(/\d/g, match => banglaDigits[match]);
    };

    const animateCountUp = (el, target, duration, suffix) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const currentCount = Math.floor(easeProgress * target);
            
            el.innerText = convertToBengaliNumber(currentCount) + (suffix || '');
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                el.innerText = convertToBengaliNumber(target) + (suffix || '');
            }
        };
        window.requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.count-up');
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'), 10);
                    const suffix = counter.getAttribute('data-suffix') || '';
                    animateCountUp(counter, target, 2000, suffix);
                });
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    const statsSection = document.getElementById('impact-stats');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
