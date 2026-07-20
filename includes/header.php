<?php
// includes/header.php
require_once __DIR__ . '/auth.php';
init_secure_session();
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)</title>
    <link rel="icon" type="image/png" href="/assets/img/cds-logo.png">
    <meta name="description" content="সুশিক্ষা, সুস্বাস্থ্য, সুনাগরিক ও সুশাসনের লক্ষ্যে কাজ করা একটি স্বেচ্ছাসেবী সংগঠন।">
    <!-- Google Fonts for Bengali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;700&display=swap" rel="stylesheet">
    <!-- Compiled Tailwind CSS -->
    <link href="/assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>" rel="stylesheet">
</head>
<body class="bg-warm-grain min-h-screen font-sans-bn text-foreground">

    <!-- HEADER -->
    <header class="sticky top-0 z-50 border-b border-border/60 bg-background/85 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
          <a href="/index.php" class="flex min-w-0 items-center gap-3">
            <img src="/assets/img/cds-logo.png" alt="CDS Logo" class="h-10 w-auto shrink-0 drop-shadow-sm">
            <span class="min-w-0">
              <div class="truncate font-serif-bn text-base font-bold leading-tight sm:text-lg">
                সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)
              </div>
              <div class="truncate text-xs font-medium text-slate-500">
                সুশিক্ষা • সুস্বাস্থ্য • সুনাগরিক • সুশাসন
              </div>
            </span>
          </a>

          <nav class="ml-auto hidden items-center gap-1 lg:flex">
              <a href="/index.php" class="rounded-full px-3 py-2 text-sm font-medium text-foreground/80 transition hover:bg-primary-soft hover:text-primary">হোম</a>
              <a href="/index.php#about" class="rounded-full px-3 py-2 text-sm font-medium text-foreground/80 transition hover:bg-primary-soft hover:text-primary">আমাদের সম্পর্কে</a>
              <a href="/projects.php" class="rounded-full px-3 py-2 text-sm font-medium text-foreground/80 transition hover:bg-primary-soft hover:text-primary">প্রজেক্টস</a>
              <a href="/gallery.php" class="rounded-full px-3 py-2 text-sm font-medium text-foreground/80 transition hover:bg-primary-soft hover:text-primary">গ্যালারি</a>
              <a href="/notice.php" class="rounded-full px-3 py-2 text-sm font-medium text-foreground/80 transition hover:bg-primary-soft hover:text-primary">নোটিশ</a>
              <a href="https://membership.fuminds.com/" target="_blank" rel="noopener noreferrer" class="rounded-full px-3 py-2 text-sm font-medium text-foreground/80 transition hover:bg-primary-soft hover:text-primary">সিডিএস ফর্ম</a>
              <a href="/index.php#contact" class="rounded-full px-3 py-2 text-sm font-medium text-foreground/80 transition hover:bg-primary-soft hover:text-primary">যোগাযোগ</a>
          </nav>

          <a href="/donation.php" class="ml-auto inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110 lg:ml-3">
            <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
              <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" />
            </svg>
            ডোনেট করুন
          </a>

          <button id="mobile-menu-btn" aria-label="Menu" class="grid h-10 w-10 shrink-0 place-items-center rounded-lg border border-border bg-surface lg:hidden">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 menu-open-icon">
              <path d="M4 7h16M4 12h16M4 17h16" />
            </svg>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 menu-close-icon hidden">
              <path d="M6 6l12 12M18 6L6 18" />
            </svg>
          </button>
        </div>

        <!-- mobile menu -->
        <div id="mobile-menu" class="overflow-hidden border-t border-border bg-background/95 backdrop-blur transition-[max-height] duration-300 lg:hidden max-h-0">
          <nav class="mx-auto flex max-w-7xl flex-col px-4 py-2 sm:px-6">
              <a href="/index.php" class="rounded-md px-3 py-3 text-sm font-medium text-foreground/85 hover:bg-primary-soft hover:text-primary">হোম</a>
              <a href="/index.php#about" class="rounded-md px-3 py-3 text-sm font-medium text-foreground/85 hover:bg-primary-soft hover:text-primary">আমাদের সম্পর্কে</a>
              <a href="/projects.php" class="rounded-md px-3 py-3 text-sm font-medium text-foreground/85 hover:bg-primary-soft hover:text-primary">প্রজেক্টস</a>
              <a href="/gallery.php" class="rounded-md px-3 py-3 text-sm font-medium text-foreground/85 hover:bg-primary-soft hover:text-primary">গ্যালারি</a>
              <a href="/notice.php" class="rounded-md px-3 py-3 text-sm font-medium text-foreground/85 hover:bg-primary-soft hover:text-primary">নোটিশ</a>
              <a href="https://membership.fuminds.com/" target="_blank" rel="noopener noreferrer" class="rounded-md px-3 py-3 text-sm font-medium text-foreground/85 hover:bg-primary-soft hover:text-primary">সিডিএস ফর্ম</a>
              <a href="/index.php#contact" class="rounded-md px-3 py-3 text-sm font-medium text-foreground/85 hover:bg-primary-soft hover:text-primary">যোগাযোগ</a>
          </nav>
        </div>
    </header>
