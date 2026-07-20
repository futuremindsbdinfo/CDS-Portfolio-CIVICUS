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
<section class="relative overflow-hidden pt-10">
<div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:gap-8 lg:px-8 lg:pt-28 lg:pb-24">
    <div class="relative">
    <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">
        <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
        জনগণের জন্য · জনগণের সাথে
    </span>
    <h1 class="mt-5 font-serif-bn text-4xl font-bold leading-[1.15] tracking-tight sm:text-5xl lg:text-6xl">
        একটি সুন্দর সমাজ,
        <br />
        <span class="bg-gradient-to-br from-primary to-secondary bg-clip-text text-transparent">
        সবার অংশগ্রহণে গড়ি
        </span>
    </h1>
    <p class="mt-5 max-w-xl text-base leading-relaxed text-muted-foreground sm:text-lg">
        সুশিক্ষা, সুস্বাস্থ্য, সুনাগরিক ও সুশাসন — এই চার স্তম্ভকে সামনে রেখে আমরা কাজ করছি
        দেশের প্রান্তিক জনপদে, প্রতিটি পরিবারের পাশে।
    </p>
    <div class="mt-8 flex flex-wrap gap-3">
        <a href="#about" class="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110">
        আমাদের সম্পর্কে জানুন
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        </a>
        <a href="/donation.php" class="inline-flex items-center gap-2 rounded-full border border-border bg-surface px-5 py-3 text-sm font-semibold text-foreground transition hover:bg-primary-soft hover:text-primary">
        ডোনেট করুন
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
            <circle cx="150" cy="150" r="6" />
            <circle cx="360" cy="180" r="4" />
            <circle cx="380" cy="330" r="7" />
            <circle cx="140" cy="340" r="5" />
        </g>
        </svg>
        <div class="absolute -bottom-4 -left-4 rounded-2xl bg-primary p-4 shadow-card animate-[float_5s_ease-in-out_infinite]">
        <div class="text-xs text-white/80">সক্রিয় জেলা</div>
        <div class="font-serif-bn text-2xl font-bold text-white">১২ +</div>
        </div>
        <div class="absolute -right-2 top-8 rounded-2xl bg-primary p-4 shadow-card animate-[float_6s_ease-in-out_infinite]" style="animation-delay: 1s;">
        <div class="text-xs text-white/80">চলমান প্রকল্প</div>
        <div class="font-serif-bn text-2xl font-bold text-white">১৮</div>
        </div>
    </div>
    </div>
</div>
</section>

<!-- ABOUT PREVIEW -->
<section id="about" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="grid items-center gap-10 lg:grid-cols-[1fr_1.2fr]">
    <div class="relative">
    <div class="aspect-[4/3] overflow-hidden rounded-3xl bg-brand-gradient p-8 shadow-card">
        <svg viewBox="0 0 300 220" class="h-full w-full">
        <g fill="none" stroke="white" stroke-width="1.5" opacity="0.85">
            <circle cx="80" cy="110" r="28" />
            <circle cx="150" cy="110" r="34" />
            <circle cx="220" cy="110" r="28" />
            <path d="M40 180c25-30 70-40 110-40s85 10 110 40" stroke-linecap="round" />
        </g>
        <g fill="white" opacity="0.9">
            <circle cx="80" cy="110" r="6" />
            <circle cx="150" cy="110" r="8" />
            <circle cx="220" cy="110" r="6" />
        </g>
        </svg>
    </div>
    </div>
    <div>
    <div class="text-xs font-semibold uppercase tracking-widest text-primary">আমাদের সম্পর্কে</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
        মানুষের জন্য, মানবতার জন্য
    </h2>
    <p class="mt-4 text-base leading-relaxed text-muted-foreground">
        সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস) ২০১৫ সাল থেকে বাংলাদেশের প্রান্তিক জনপদে
        শিক্ষা, স্বাস্থ্য ও নাগরিক সচেতনতামূলক কার্যক্রম পরিচালনা করছে। আমাদের লক্ষ্য —
        একটি অন্তর্ভুক্তিমূলক, ন্যায়ভিত্তিক ও টেকসই সমাজ।
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
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default">সুস্বাস্থ্য</span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default">সুনাগরিক</span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default">সুশাসন</span>
          </div>
        </div>
      </div>
    </section>

<!-- PILLARS -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 text-center">
    <div class="text-xs font-semibold uppercase tracking-widest text-primary">আমাদের চার স্তম্ভ</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
    যে মূল্যবোধে আমরা বিশ্বাসী
    </h2>
</div>
<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M3 8l9-4 9 4-9 4-9-4z" stroke-linejoin="round"></path><path d="M7 10v5c0 1.5 2.5 3 5 3s5-1.5 5-3v-5" stroke-linecap="round"></path><path d="M21 8v6" stroke-linecap="round"></path></svg>
            </div>
            <div class="mt-4 inline-flex items-center rounded-full bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">Good Education</div>
            <h3 class="mt-2 font-serif-bn text-xl font-bold">সুশিক্ষা</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">শিক্ষাকে সবার জন্য সহজলভ্য ও মানসম্পন্ন করে তোলা।</p>
        </div>
    </div>
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" stroke-linejoin="round"></path></svg>
            </div>
            <div class="mt-4 inline-flex items-center rounded-full bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">Good Health</div>
            <h3 class="mt-2 font-serif-bn text-xl font-bold">সুস্বাস্থ্য</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">প্রতিটি পরিবারে সুস্থ ও নিরাপদ জীবনের নিশ্চয়তা।</p>
        </div>
    </div>
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><circle cx="12" cy="8" r="3.2"></circle><path d="M5 20c1.5-3.5 4.2-5 7-5s5.5 1.5 7 5" stroke-linecap="round"></path></svg>
            </div>
            <div class="mt-4 inline-flex items-center rounded-full bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">Good Citizenship</div>
            <h3 class="mt-2 font-serif-bn text-xl font-bold">সুনাগরিক</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">দায়িত্বশীল ও সচেতন নাগরিক গড়ে তোলা।</p>
        </div>
    </div>
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M4 21h16M6 21V10M18 21V10M4 10l8-6 8 6" stroke-linejoin="round"></path><path d="M10 21v-6h4v6"></path></svg>
            </div>
            <div class="mt-4 inline-flex items-center rounded-full bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">Good Governance</div>
            <h3 class="mt-2 font-serif-bn text-xl font-bold">সুশাসন</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">স্বচ্ছতা, জবাবদিহিতা ও ন্যায়ের চর্চা।</p>
        </div>
    </div>
</div>
</section>

<!-- STATS BAND -->
<section class="relative mx-4 my-10 overflow-hidden rounded-3xl bg-brand-gradient px-6 py-14 shadow-card-hover sm:mx-6 lg:mx-auto lg:max-w-7xl lg:px-12">
<svg class="absolute inset-0 h-full w-full opacity-20" viewBox="0 0 800 300" preserveAspectRatio="none">
    <path d="M0 200 Q200 100 400 200 T800 200 V300 H0Z" fill="white" />
</svg>
<div class="relative grid grid-cols-2 gap-8 lg:grid-cols-4">
    <div class="text-center">
        <div class="font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl">৩২০+</div>
        <div class="mt-2 text-sm font-medium text-white/80">স্বেচ্ছাসেবী</div>
    </div>
    <div class="text-center">
        <div class="font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl">৪৫</div>
        <div class="mt-2 text-sm font-medium text-white/80">সম্পন্ন প্রজেক্ট</div>
    </div>
    <div class="text-center">
        <div class="font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl">১,৫০০+</div>
        <div class="mt-2 text-sm font-medium text-white/80">উপকারভোগী পরিবার</div>
    </div>
    <div class="text-center">
        <div class="font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl">২০১৫</div>
        <div class="mt-2 text-sm font-medium text-white/80">প্রতিষ্ঠার বছর</div>
    </div>
</div>
</section>

<!-- SHORTCUTS -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 flex items-end justify-between gap-4">
    <div>
    <div class="text-xs font-semibold uppercase tracking-widest text-primary">গুরুত্বপূর্ণ লিংক</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">দ্রুত পৌঁছান</h2>
    </div>
</div>
<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
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
    <a href="/donation.php" class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-card-hover">
        <div class="pointer-events-none absolute -bottom-6 -right-6 h-32 w-32 text-primary/10 transition group-hover:scale-110 group-hover:text-primary/20">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="h-full w-full"><path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" stroke-linejoin="round"></path></svg>
        </div>
        <div class="relative">
            <div class="grid h-10 w-10 place-items-center rounded-lg bg-primary-soft text-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M7 17L17 7M9 7h8v8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-lg font-bold">ডোনেশন</h3>
            <p class="mt-1 text-sm text-muted-foreground">আপনার সহায়তা আমাদের পথচলা সহজ করে।</p>
        </div>
    </a>
</div>
</section>

<!-- PROJECTS -->
<section id="projects" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 flex flex-wrap items-end justify-between gap-4">
    <div>
    <div class="text-xs font-semibold uppercase tracking-widest text-primary">ফিচার্ড প্রজেক্ট</div>
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
    <div class="text-xs font-semibold uppercase tracking-widest text-primary">নোটিশ বোর্ড</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">সাম্প্রতিক নোটিশ</h2>
    <p class="mt-3 text-sm text-muted-foreground">
        সংগঠন থেকে প্রকাশিত সর্বশেষ ঘোষণা ও পরিপত্র এখানে পাবেন।
    </p>
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
    <div class="text-xs font-semibold uppercase tracking-widest text-primary">প্রশ্নোত্তর</div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">প্রায়শই জিজ্ঞাসিত প্রশ্ন</h2>
</div>
<div class="space-y-3">
    <?php
    $faqs = [
        ["q" => "CDS কীভাবে অনুদান ব্যবহার করে?", "a" => "সকল অনুদান সরাসরি প্রকল্প বাস্তবায়ন, উপকারভোগী সহায়তা ও পরিচালন ব্যয়ে ব্যবহৃত হয়। প্রতি বছর নিরীক্ষিত প্রতিবেদন প্রকাশ করা হয়।"],
        ["q" => "আমি কীভাবে স্বেচ্ছাসেবক হতে পারি?", "a" => "যোগাযোগ ফর্ম পূরণ করে অথবা সরাসরি অফিসে এসে নিবন্ধন করতে পারেন। আপনার আগ্রহ অনুযায়ী প্রকল্পে যুক্ত করা হবে।"],
        ["q" => "সংগঠনটি কোথায় কাজ করে?", "a" => "প্রাথমিকভাবে বাংলাদেশের গ্রামীণ ও প্রান্তিক জনপদে কাজ করি; বর্তমানে ১২টি জেলায় প্রকল্প চলমান।"],
        ["q" => "ডোনেশনের রশিদ পাওয়া যাবে?", "a" => "হ্যাঁ, প্রতিটি অনুদানের জন্য সরকারি নিয়মে রশিদ প্রদান করা হয়।"],
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
        <div class="text-xs font-semibold uppercase tracking-widest text-primary">আপনার সহায়তায়</div>
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
        এখনই ডোনেট করুন
        </a>
        <a href="/index.php#contact" class="inline-flex items-center justify-center gap-2 rounded-full border border-border bg-surface px-6 py-3 text-sm font-semibold text-foreground hover:bg-primary-soft hover:text-primary">
        যোগাযোগ করুন
        </a>
    </div>
    </div>
</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
