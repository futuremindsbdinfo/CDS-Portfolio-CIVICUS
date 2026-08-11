<?php
// includes/header.php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/settings_helper.php';
init_secure_session();

$site_title = get_setting('site_title', 'সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)');
$site_slogan = get_setting('site_slogan', 'সুশিক্ষা • সুশাসন • সুস্বাস্থ্য • সুনাগরিক • উন্নত বাংলাদেশ');
$site_desc = get_setting('site_description', 'সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক ও উন্নত বাংলাদেশ গড়ার লক্ষ্যে কাজ করা একটি স্বেচ্ছাসেবী সংগঠন।');

$current_page = basename($_SERVER['PHP_SELF']);
function isActiveLink($page, $current_page) {
    if ($page === 'index.php' && ($current_page === 'index.php' || $current_page === '')) {
        return 'bg-primary-soft text-primary font-bold';
    }
    return $page === $current_page ? 'bg-primary-soft text-primary font-bold' : 'text-foreground/80';
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title . ' | ' . $site_title) : htmlspecialchars($site_title); ?></title>
    <link rel="icon" type="image/png" href="assets/img/cds-logo.png">
    <meta name="description" content="<?php echo isset($meta_description) ? htmlspecialchars($meta_description) : htmlspecialchars($site_desc); ?>">
    <!-- Google Fonts for Bengali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;700&display=swap" rel="stylesheet">
    <!-- Compiled Tailwind CSS -->
    <link href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>" rel="stylesheet">
</head>
<body class="bg-warm-grain min-h-screen font-sans-bn text-foreground">

    <!-- Dropdown Animation Styles -->
    <style>
      .custom-dropdown {
        display: none;
      }
      .group:hover .custom-dropdown {
        display: block;
        animation: dropdownFadeIn 0.2s ease-out forwards;
      }
      @keyframes dropdownFadeIn {
        from { opacity: 0; transform: translateY(4px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
      }
    </style>

    <!-- HEADER -->
    <header class="sticky top-0 z-50 border-b border-border/60 bg-background/85 backdrop-blur-md">
        <div class="mx-auto flex justify-between max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
          <a href="index.php" class="flex min-w-0 items-center gap-3">
            <img src="assets/img/cds-logo.png" alt="CDS Logo" class="h-10 w-auto shrink-0 drop-shadow-sm">
            <span class="min-w-0 hidden sm:block">
              <div class="truncate font-serif-bn text-base font-bold leading-tight sm:text-lg">
                <span data-lang="bn">সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)</span>
                <span data-lang="en" class="hidden">Citizen Development Society (CDS)</span>
              </div>
              <div class="truncate text-xs font-medium text-slate-500">
                <span data-lang="bn"><?php echo htmlspecialchars($site_slogan); ?></span>
                <span data-lang="en" class="hidden">Quality Education • Good Governance • Health and Well-being • Active Citizenship • Prosperous Bangladesh</span>
              </div>
            </span>
          </a>

          <nav class="hidden items-center gap-1 lg:flex">
              <a href="index.php" class="whitespace-nowrap rounded-full px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('index.php', $current_page); ?>">
                  <span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span>
              </a>
              
              <!-- Who We Are Dropdown -->
              <div class="relative group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary <?php echo (in_array($current_page, ['about.php', 'notice.php', 'gallery.php'])) ? 'bg-primary-soft text-primary font-bold' : 'text-foreground/80'; ?>">
                      <span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">Who We Are</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  <div class="absolute left-0 top-full pt-1 w-72 custom-dropdown z-50">
                      <div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">
                          <a href="about.php" class="flex items-start gap-3 rounded-lg p-3 hover:bg-primary-soft transition group/item <?php echo isActiveLink('about.php', $current_page); ?>">
                              <div class="mt-0.5 rounded-md bg-primary/10 p-2 text-primary group-hover/item:bg-primary group-hover/item:text-white transition">
                                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                              </div>
                              <div>
                                  <div class="font-semibold text-foreground"><span data-lang="bn">এক নজরে</span><span data-lang="en" class="hidden">At a Glance</span></div>
                                  <div class="mt-1 text-xs text-foreground/70"><span data-lang="bn">আমাদের লক্ষ্য ও উদ্দেশ্য</span><span data-lang="en" class="hidden">Our vision and mission</span></div>
                              </div>
                          </a>
                          <a href="committee.php" class="flex items-start gap-3 rounded-lg p-3 hover:bg-primary-soft transition group/item <?php echo isActiveLink('committee.php', $current_page); ?>">
                              <div class="mt-0.5 rounded-md bg-primary/10 p-2 text-primary group-hover/item:bg-primary group-hover/item:text-white transition">
                                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                              </div>
                              <div>
                                  <div class="font-semibold text-foreground"><span data-lang="bn">কমিটি</span><span data-lang="en" class="hidden">Committee</span></div>
                                  <div class="mt-1 text-xs text-foreground/70"><span data-lang="bn">আমাদের বর্তমান কমিটি</span><span data-lang="en" class="hidden">Our current committee</span></div>
                              </div>
                          </a>
                          <a href="constitution.php" class="flex items-start gap-3 rounded-lg p-3 hover:bg-primary-soft transition group/item <?php echo isActiveLink('constitution.php', $current_page); ?>">
                              <div class="mt-0.5 rounded-md bg-primary/10 p-2 text-primary group-hover/item:bg-primary group-hover/item:text-white transition">
                                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                              </div>
                              <div>
                                  <div class="font-semibold text-foreground"><span data-lang="bn">গঠনতন্ত্র</span><span data-lang="en" class="hidden">Constitution</span></div>
                                  <div class="mt-1 text-xs text-foreground/70"><span data-lang="bn">সংগঠনের নীতিমালা ও গঠনতন্ত্র</span><span data-lang="en" class="hidden">Rules and regulations</span></div>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>

              <!-- What We Do Dropdown -->
              <div class="relative group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary <?php echo (in_array($current_page, ['projects.php', 'publications.php', 'blog.php'])) ? 'bg-primary-soft text-primary font-bold' : 'text-foreground/80'; ?>">
                      <span data-lang="bn">আমাদের কার্যক্রম</span><span data-lang="en" class="hidden">What We Do</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  <div class="absolute left-0 top-full pt-1 w-72 custom-dropdown z-50">
                      <div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">
                          <a href="projects.php" class="flex items-start gap-3 rounded-lg p-3 hover:bg-primary-soft transition group/item <?php echo isActiveLink('projects.php', $current_page); ?>">
                              <div class="mt-0.5 rounded-md bg-primary/10 p-2 text-primary group-hover/item:bg-primary group-hover/item:text-white transition">
                                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                              </div>
                              <div>
                                  <div class="font-semibold text-foreground"><span data-lang="bn">প্রজেক্টসমূহ</span><span data-lang="en" class="hidden">Projects</span></div>
                                  <div class="mt-1 text-xs text-foreground/70"><span data-lang="bn">চলমান ও সম্পন্ন প্রজেক্ট</span><span data-lang="en" class="hidden">Ongoing & completed</span></div>
                              </div>
                          </a>
                          <a href="gallery.php" class="flex items-start gap-3 rounded-lg p-3 hover:bg-primary-soft transition group/item <?php echo isActiveLink('gallery.php', $current_page); ?>">
                              <div class="mt-0.5 rounded-md bg-primary/10 p-2 text-primary group-hover/item:bg-primary group-hover/item:text-white transition">
                                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                              </div>
                              <div>
                                  <div class="font-semibold text-foreground"><span data-lang="bn">গ্যালারি</span><span data-lang="en" class="hidden">Gallery</span></div>
                                  <div class="mt-1 text-xs text-foreground/70"><span data-lang="bn">কার্যক্রমের ছবি ও ভিডিও</span><span data-lang="en" class="hidden">Photos and videos</span></div>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>

              <!-- Engage & Act Dropdown -->
              <div class="relative group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary <?php echo (in_array($current_page, ['gov-links.php', 'forms.php', 'contact.php'])) ? 'bg-primary-soft text-primary font-bold' : 'text-foreground/80'; ?>">
                      <span data-lang="bn">গুরুত্বপূর্ণ লিংক</span><span data-lang="en" class="hidden">Important Links</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  <div class="absolute left-0 top-full pt-1 w-72 custom-dropdown z-50">
                      <div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">
                          <a href="gov-links.php" class="flex items-start gap-3 rounded-lg p-3 hover:bg-primary-soft transition group/item <?php echo isActiveLink('gov-links.php', $current_page); ?>">
                              <div class="mt-0.5 rounded-md bg-primary/10 p-2 text-primary group-hover/item:bg-primary group-hover/item:text-white transition">
                                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                              </div>
                              <div>
                                  <div class="font-semibold text-foreground"><span data-lang="bn">সরকারি লিংক</span><span data-lang="en" class="hidden">Govt. Links</span></div>
                                  <div class="mt-1 text-xs text-foreground/70"><span data-lang="bn">গুরুত্বপূর্ণ সরকারি ওয়েবসাইট</span><span data-lang="en" class="hidden">Important govt websites</span></div>
                              </div>
                          </a>
                          <a href="forms.php" class="flex items-start gap-3 rounded-lg p-3 hover:bg-primary-soft transition group/item <?php echo isActiveLink('forms.php', $current_page); ?>">
                              <div class="mt-0.5 rounded-md bg-primary/10 p-2 text-primary group-hover/item:bg-primary group-hover/item:text-white transition">
                                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                              </div>
                              <div>
                                  <div class="font-semibold text-foreground"><span data-lang="bn">আবেদন ফর্ম</span><span data-lang="en" class="hidden">Application Forms</span></div>
                                  <div class="mt-1 text-xs text-foreground/70"><span data-lang="bn">সদস্য ও অন্যান্য ফর্ম</span><span data-lang="en" class="hidden">Membership & others</span></div>
                              </div>
                          </a>
                          <a href="contact.php" class="flex items-start gap-3 rounded-lg p-3 hover:bg-primary-soft transition group/item <?php echo isActiveLink('contact.php', $current_page); ?>">
                              <div class="mt-0.5 rounded-md bg-primary/10 p-2 text-primary group-hover/item:bg-primary group-hover/item:text-white transition">
                                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                              </div>
                              <div>
                                  <div class="font-semibold text-foreground"><span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Contact Us</span></div>
                                  <div class="mt-1 text-xs text-foreground/70"><span data-lang="bn">আমাদের সাথে যোগাযোগ করুন</span><span data-lang="en" class="hidden">Get in touch with us</span></div>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>
              
              <!-- CTA Button -->
              <a href="donation.php" class="ml-2 whitespace-nowrap rounded-full bg-primary/10 px-4 py-2 text-sm font-bold text-primary transition hover:bg-primary hover:text-white <?php echo isActiveLink('donation.php', $current_page); ?>">
                  <span data-lang="bn">অনুদান</span><span data-lang="en" class="hidden">Donate</span>
              </a>
          </nav>

          <div class="flex items-center gap-2 sm:gap-3">
            <!-- Language Switcher -->
            <div class="hidden sm:flex shrink-0 items-center rounded-full border border-border bg-surface p-0.5 shadow-sm">
              <button data-set-lang="bn" class="lang-toggle-btn rounded-full px-3 py-1.5 text-xs font-medium transition bg-primary/20 text-primary font-bold">BN</button>
              <button data-set-lang="en" class="lang-toggle-btn rounded-full px-3 py-1.5 text-xs font-medium transition text-foreground/70 hover:text-primary">EN</button>
            </div>

            <a href="https://membership.fuminds.com/" target="_blank" class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-primary px-4 py-2 sm:px-6 sm:py-2.5 text-sm sm:text-base font-semibold text-primary-foreground shadow-card transition hover:brightness-110">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 sm:h-5 sm:w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M8.5 7a4 4 0 100 8 4 4 0 000-8zM20 8v6M23 11h-6"></path>
              </svg>
              <span data-lang="bn">যোগদান</span>
              <span data-lang="en" class="hidden">JOIN</span>
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
        </div>

        <!-- mobile menu -->
        <div id="mobile-menu" class="overflow-y-auto border-t border-border bg-background/95 backdrop-blur transition-[max-height] duration-300 lg:hidden max-h-0">
          <nav class="mx-auto flex max-w-7xl flex-col px-4 py-2 sm:px-6">
              <a href="index.php" class="rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('index.php', $current_page); ?>">
                  <span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span>
              </a>
              
              <!-- Who We Are (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo (in_array($current_page, ['about.php', 'notice.php', 'gallery.php'])) ? 'text-primary font-bold' : 'text-foreground/80'; ?> [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">Who We Are</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-2 space-y-1">
                      <a href="about.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('about.php', $current_page); ?>">
                          <span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">About Us</span>
                      </a>
                      <a href="notice.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('notice.php', $current_page); ?>">
                          <span data-lang="bn">নোটিশ বোর্ড</span><span data-lang="en" class="hidden">Notice Board</span>
                      </a>
                      <a href="gallery.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('gallery.php', $current_page); ?>">
                          <span data-lang="bn">গ্যালারি</span><span data-lang="en" class="hidden">Gallery</span>
                      </a>
                  </div>
              </details>

              <!-- What We Do (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo (in_array($current_page, ['projects.php', 'publications.php', 'blog.php'])) ? 'text-primary font-bold' : 'text-foreground/80'; ?> [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">আমাদের কার্যক্রম</span><span data-lang="en" class="hidden">What We Do</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-2 space-y-1">
                      <a href="projects.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('projects.php', $current_page); ?>">
                          <span data-lang="bn">প্রজেক্টস</span><span data-lang="en" class="hidden">Projects</span>
                      </a>
                      <a href="publications.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('publications.php', $current_page); ?>">
                          <span data-lang="bn">প্রকাশনা</span><span data-lang="en" class="hidden">Publications</span>
                      </a>
                      <a href="blog.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('blog.php', $current_page); ?>">
                          <span data-lang="bn">নিউজ ও ব্লগ</span><span data-lang="en" class="hidden">News & Blog</span>
                      </a>
                  </div>
              </details>

              <!-- Engage & Act (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo (in_array($current_page, ['gov-links.php', 'forms.php', 'contact.php'])) ? 'text-primary font-bold' : 'text-foreground/80'; ?> [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">যোগদান করুন</span><span data-lang="en" class="hidden">Engage & Act</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-2 space-y-1">
                      <a href="gov-links.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('gov-links.php', $current_page); ?>">
                          <span data-lang="bn">সরকারি লিংক</span><span data-lang="en" class="hidden">Govt Links</span>
                      </a>
                      <a href="forms.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('forms.php', $current_page); ?>">
                          <span data-lang="bn">আবেদন ফরমসমূহ</span><span data-lang="en" class="hidden">Application Forms</span>
                      </a>
                      <a href="contact.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('contact.php', $current_page); ?>">
                          <span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Contact</span>
                      </a>
                  </div>
              </details>

              <!-- Mobile CTA -->
              <a href="donation.php" class="mt-2 text-center rounded-md bg-primary/10 px-3 py-3 text-sm font-bold text-primary hover:bg-primary hover:text-white transition <?php echo isActiveLink('donation.php', $current_page); ?>">
                  <span data-lang="bn">অনুদান</span><span data-lang="en" class="hidden">Donate</span>
              </a>

              <!-- Mobile Language Switcher -->
              <div class="mt-4 mb-2 flex sm:hidden shrink-0 items-center justify-center rounded-full border border-border bg-surface p-1 shadow-sm">
                <button data-set-lang="bn" class="lang-toggle-btn w-1/2 rounded-full px-4 py-2 text-sm font-bold transition bg-primary/20 text-primary">BN</button>
                <button data-set-lang="en" class="lang-toggle-btn w-1/2 rounded-full px-4 py-2 text-sm font-medium transition text-foreground/70 hover:text-primary">EN</button>
              </div>
          </nav>
        </div>
    </header>
