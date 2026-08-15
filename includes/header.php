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
    <link rel="icon" type="image/png" href="/assets/img/cds-logo.png">
    <meta name="description" content="<?php echo isset($meta_description) ? htmlspecialchars($meta_description) : htmlspecialchars($site_desc); ?>">
    <!-- Google Fonts for Bengali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;700&display=swap" rel="stylesheet">
    <!-- Compiled Tailwind CSS -->
    <link href="/assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>" rel="stylesheet">
</head>
<body class="bg-warm-grain min-h-screen font-sans-bn text-foreground flex flex-col">

    <!-- Header Navigation & Dropdown Styles -->
    <style>
      /* Main Header Sticky Container */
      header.cds-main-header {
        position: sticky;
        top: 0;
        z-index: 50;
        background-color: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
      }

      /* Desktop Mega Menu & Dropdown Logic */
      .nav-item-group {
        position: static;
      }
      .nav-item-group.relative-dropdown {
        position: relative;
      }

      /* Dropdown Arrow rotation */
      .nav-item-group.active-menu > button svg.nav-arrow,
      .nav-item-group:hover > button svg.nav-arrow,
      .nav-sub-item.active-sub > a svg.sub-arrow,
      .nav-sub-item:hover > a svg.sub-arrow {
        transform: rotate(180deg);
      }

      /* Mega Full Panel */
      .mega-full-panel {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        width: 100%;
        background: #ffffff;
        border-top: 3px solid #0e1b64;
        box-shadow: 0 12px 36px rgba(14, 27, 100, 0.12);
        z-index: 100;
      }
      .nav-item-group.active-menu > .mega-full-panel,
      .nav-item-group:hover > .mega-full-panel {
        display: block;
        animation: dropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }

      /* Regular Dropdown */
      .simple-dropdown {
        display: none;
        position: absolute;
        left: 0;
        top: 100%;
        min-width: 220px;
        background: #ffffff;
        border-top: 3px solid #0e1b64;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 12px 32px rgba(14, 27, 100, 0.12);
        padding: 8px 0;
        z-index: 100;
      }
      .nav-item-group.active-menu > .simple-dropdown,
      .nav-item-group:hover > .simple-dropdown {
        display: block;
        animation: dropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }

      /* Sub Dropdown inside Mega Menu */
      .nav-sub-item {
        position: relative;
      }
      .sub-dropdown-menu {
        display: none;
        position: absolute;
        left: 100%;
        top: 0;
        min-width: 230px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        padding: 6px 0;
        z-index: 110;
        margin-left: 2px;
      }
      /* Invisible hit-box bridge to prevent mouse leaving on gap */
      .sub-dropdown-menu::before {
        content: '';
        position: absolute;
        top: 0;
        left: -12px;
        width: 14px;
        height: 100%;
      }
      .nav-sub-item.active-sub > .sub-dropdown-menu,
      .nav-sub-item:hover > .sub-dropdown-menu {
        display: block;
        animation: subDropdownFadeIn 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }

      @keyframes dropdownFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
      }
      @keyframes subDropdownFadeIn {
        from { opacity: 0; transform: translateX(-4px); }
        to   { opacity: 1; transform: translateX(0); }
      }

      /* Typography for Mega Menu */
      .mega-col-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: #0e1b64;
        padding-bottom: 0.5rem;
        margin-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
        letter-spacing: 0.02em;
      }
      .mega-item-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        padding: 0.45rem 0.5rem;
        border-radius: 6px;
        transition: all 0.15s ease;
      }
      .mega-item-link:hover {
        color: #0e1b64;
        background-color: #f1f5f9;
        padding-left: 0.75rem;
      }
      .sub-menu-link {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        padding: 0.5rem 1rem;
        transition: all 0.15s ease;
      }
      .sub-menu-link:hover {
        color: #0e1b64;
        background-color: #f8fafc;
        padding-left: 1.25rem;
      }
    </style>

    <!-- HEADER -->
    <header class="cds-main-header">
        <div class="mx-auto flex justify-between max-w-[1400px] items-center gap-3 px-4 py-2.5 sm:px-6 lg:px-8 relative">
          
          <!-- LOGO (Left on Desktop, Far Left on Mobile with Lang Button) -->
          <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 min-w-0">
            <!-- Logo -->
            <a href="/index.php" class="flex min-w-0 items-center gap-2.5 shrink-0">
              <img src="/assets/img/cds-logo.png" alt="CDS Logo" class="h-9 sm:h-10 w-auto shrink-0 drop-shadow-sm">
              <span class="min-w-0 text-left hidden lg:block">
                <div class="truncate font-serif-bn text-base lg:text-lg font-bold leading-tight text-[#0e1b64]">
                  <span data-lang="bn">সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)</span>
                  <span data-lang="en" class="hidden">Citizen Development Society (CDS)</span>
                </div>
                <div class="truncate text-xs font-medium text-slate-500">
                  <span data-lang="bn"><?php echo htmlspecialchars($site_slogan); ?></span>
                  <span data-lang="en" class="hidden">Quality Education • Good Governance • Health & Well-being • Active Citizenship</span>
                </div>
              </span>
            </a>

            <!-- Mobile Language Switcher (Only visible on Mobile right next to Logo) -->
            <div class="relative group/mobile-lang lg:hidden z-50 shrink-0">
                <button id="mobile-lang-btn" class="flex items-center gap-1 rounded-full border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-bold text-[#0e1b64] shadow-sm active:bg-slate-100 transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-3.5 w-3.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <span class="active-lang-text uppercase">BN</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div id="mobile-lang-dropdown" class="hidden absolute left-0 top-full pt-1.5 z-50 min-w-[130px]">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xl py-1 overflow-hidden">
                        <button data-set-lang="bn" class="lang-toggle-btn w-full text-left px-4 py-2 text-xs font-bold text-[#0e1b64] hover:bg-slate-50 flex items-center justify-between">
                            বাংলা
                            <svg class="w-3.5 h-3.5 text-[#3A7D5C] check-bn" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button data-set-lang="en" class="lang-toggle-btn w-full text-left px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 flex items-center justify-between">
                            English
                            <svg class="w-3.5 h-3.5 text-[#3A7D5C] check-en hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
          </div>

          <!-- DESKTOP NAVIGATION -->
          <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5 flex-1 justify-center">
              <a href="index.php" class="whitespace-nowrap rounded-full px-3 py-2 text-sm font-semibold transition hover:bg-slate-100 text-[#0e1b64] <?php echo isActiveLink('index.php', $current_page); ?>">
                  <span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span>
              </a>
              
              <!-- 1. Who We Are Mega Menu -->
              <div class="nav-item-group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-2 text-sm font-semibold transition hover:bg-slate-100 text-slate-700 hover:text-[#0e1b64]">
                      <span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">Who We Are</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 nav-arrow transition-transform duration-200"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>

                  <div class="mega-full-panel">
                      <div class="mx-auto max-w-7xl px-8 py-8 grid grid-cols-4 gap-8">
                          <!-- Col 1 -->
                          <div>
                              <div class="mega-col-title"><span data-lang="bn">আমাদের পরিচিতি</span><span data-lang="en" class="hidden">About CDS</span></div>
                              <a href="about.php" class="mega-item-link"><span data-lang="bn">সংগঠন ও ইতিহাস</span><span data-lang="en" class="hidden">History & Mission</span></a>
                              <a href="about.php#mission" class="mega-item-link"><span data-lang="bn">লক্ষ্য ও উদ্দেশ্য</span><span data-lang="en" class="hidden">Vision & Objectives</span></a>
                              <a href="about.php#team" class="mega-item-link"><span data-lang="bn">কর্মকর্তাবৃন্দ ও টিম</span><span data-lang="en" class="hidden">Leadership & Team</span></a>
                          </div>
                          <!-- Col 2 -->
                          <div>
                              <div class="mega-col-title"><span data-lang="bn">মূল্যবোধ ও পরিচালনা</span><span data-lang="en" class="hidden">Governance</span></div>
                              <a href="about.php" class="mega-item-link"><span data-lang="bn">মূল্যবোধ ও জবাবদিহিতা</span><span data-lang="en" class="hidden">Values & Ethics</span></a>
                              <!-- Sub Item -->
                              <div class="nav-sub-item">
                                  <a href="javascript:void(0)" class="mega-item-link">
                                      <span><span data-lang="bn">পরিচালনা পর্ষদ</span><span data-lang="en" class="hidden">Executive Board</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5 sub-arrow"><path d="M9 5l7 7-7 7"/></svg>
                                  </a>
                                  <div class="sub-dropdown-menu">
                                      <a href="about.php#board" class="sub-menu-link"><span data-lang="bn">বর্তমান বোর্ড ২০২৬</span><span data-lang="en" class="hidden">Board Members 2026</span></a>
                                      <a href="member-criteria.php" class="sub-menu-link"><span data-lang="bn">বোর্ড নির্বাচনের নিয়ম</span><span data-lang="en" class="hidden">Election Rules</span></a>
                                  </div>
                              </div>
                              <a href="publications.php" class="mega-item-link"><span data-lang="bn">বার্ষিক প্রতিবেদন</span><span data-lang="en" class="hidden">Annual Reports</span></a>
                          </div>
                          <!-- Col 3 -->
                          <div>
                              <div class="mega-col-title"><span data-lang="bn">সদস্য ও নেটওয়ার্ক</span><span data-lang="en" class="hidden">Members & Network</span></div>
                              <a href="https://membership.fuminds.com/" target="_blank" class="mega-item-link"><span data-lang="bn">সদস্য নিবন্ধন</span><span data-lang="en" class="hidden">Join Membership</span></a>
                              <a href="member-criteria.php" class="mega-item-link"><span data-lang="bn">সদস্যতার ক্যাটাগরি</span><span data-lang="en" class="hidden">Membership Types</span></a>
                              <a href="gallery.php" class="mega-item-link"><span data-lang="bn">ফটো গ্যালারি</span><span data-lang="en" class="hidden">Photo Gallery</span></a>
                          </div>
                          <!-- Col 4 -->
                          <div>
                              <div class="mega-col-title"><span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Connect</span></div>
                              <a href="contact.php" class="mega-item-link"><span data-lang="bn">সরাসরি যোগাযোগ</span><span data-lang="en" class="hidden">Contact Us</span></a>
                              <a href="donation.php" class="mega-item-link"><span data-lang="bn">অনুদানের তথ্য</span><span data-lang="en" class="hidden">Donation Info</span></a>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- 2. What We Do Mega Menu -->
              <div class="nav-item-group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-2 text-sm font-semibold transition hover:bg-slate-100 text-slate-700 hover:text-[#0e1b64]">
                      <span data-lang="bn">কার্যক্রম সমূহ</span><span data-lang="en" class="hidden">What We Do</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 nav-arrow transition-transform duration-200"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>

                  <div class="mega-full-panel">
                      <div class="mx-auto max-w-7xl px-8 py-8 grid grid-cols-4 gap-8">
                          <!-- Col 1 -->
                          <div>
                              <div class="mega-col-title"><span data-lang="bn">মূল স্তম্ভসমূহ (Pillars)</span><span data-lang="en" class="hidden">Strategic Pillars</span></div>
                              <a href="index.php#programs" class="mega-item-link"><span data-lang="bn">সুশিক্ষা কার্যক্রম</span><span data-lang="en" class="hidden">Quality Education</span></a>
                              <a href="index.php#programs" class="mega-item-link"><span data-lang="bn">সুশাসন ও ন্যায়বিচার</span><span data-lang="en" class="hidden">Good Governance</span></a>
                              <a href="index.php#programs" class="mega-item-link"><span data-lang="bn">সুস্বাস্থ্য ও পুষ্টি</span><span data-lang="en" class="hidden">Community Health</span></a>
                              <a href="index.php#programs" class="mega-item-link"><span data-lang="bn">সুনাগরিক তৈরি</span><span data-lang="en" class="hidden">Active Citizenship</span></a>
                              <a href="index.php#programs" class="mega-item-link"><span data-lang="bn">উন্নত বাংলাদেশ</span><span data-lang="en" class="hidden">Prosperous BD</span></a>
                          </div>
                          <!-- Col 2 -->
                          <div>
                              <div class="mega-col-title"><span data-lang="bn">প্রকল্প ও কার্যক্রম</span><span data-lang="en" class="hidden">Projects</span></div>
                              <a href="projects.php" class="mega-item-link"><span data-lang="bn">সকল প্রকল্প</span><span data-lang="en" class="hidden">All Projects</span></a>
                              <!-- Sub Item -->
                              <div class="nav-sub-item">
                                  <a href="javascript:void(0)" class="mega-item-link">
                                      <span><span data-lang="bn">প্রকল্পের ধরন</span><span data-lang="en" class="hidden">Project Status</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5 sub-arrow"><path d="M9 5l7 7-7 7"/></svg>
                                  </a>
                                  <div class="sub-dropdown-menu">
                                      <a href="projects.php" class="sub-menu-link"><span data-lang="bn">চলমান প্রকল্পসমূহ</span><span data-lang="en" class="hidden">Ongoing Projects</span></a>
                                      <a href="projects.php" class="sub-menu-link"><span data-lang="bn">সম্পন্ন প্রকল্পসমূহ</span><span data-lang="en" class="hidden">Completed Projects</span></a>
                                  </div>
                              </div>
                          </div>
                          <!-- Col 3 -->
                          <div>
                              <div class="mega-col-title"><span data-lang="bn">ক্যাম্পেইন ও যুব উদ্যোগ</span><span data-lang="en" class="hidden">Youth & Campaigns</span></div>
                              <a href="projects.php" class="mega-item-link"><span data-lang="bn">ভলান্টিয়ার চ্যালেঞ্জ</span><span data-lang="en" class="hidden">Volunteer Challenge</span></a>
                              <a href="projects.php" class="mega-item-link"><span data-lang="bn">উদ্ভাবনী সম্মাননা</span><span data-lang="en" class="hidden">Innovation Awards</span></a>
                              <a href="projects.php" class="mega-item-link"><span data-lang="bn">অধিকার সুরক্ষা ক্যাম্পেইন</span><span data-lang="en" class="hidden">Rights Campaigns</span></a>
                          </div>
                          <!-- Col 4 -->
                          <div>
                              <div class="mega-col-title"><span data-lang="bn">গবেষণা ও প্রকাশনা</span><span data-lang="en" class="hidden">Research & Reports</span></div>
                              <a href="publications.php" class="mega-item-link"><span data-lang="bn">মাঠপর্যায়ের প্রতিবেদন</span><span data-lang="en" class="hidden">Field Reports</span></a>
                              <a href="publications.php" class="mega-item-link"><span data-lang="bn">সামাজিক সমীক্ষা</span><span data-lang="en" class="hidden">Social Surveys</span></a>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- 3. Engage & Act Simple Dropdown -->
              <div class="nav-item-group relative-dropdown">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-3 py-2 text-sm font-semibold transition hover:bg-slate-100 text-slate-700 hover:text-[#0e1b64]">
                      <span data-lang="bn">অংশগ্রহণ করুন</span><span data-lang="en" class="hidden">Engage & Act</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 nav-arrow transition-transform duration-200"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  <div class="simple-dropdown">
                      <a href="gov-links.php" class="sub-menu-link"><span data-lang="bn">সরকারি লিংকসমূহ</span><span data-lang="en" class="hidden">Govt Links</span></a>
                      <a href="forms.php" class="sub-menu-link"><span data-lang="bn">আবেদন ফরম</span><span data-lang="en" class="hidden">Application Forms</span></a>
                      <a href="contact.php" class="sub-menu-link"><span data-lang="bn">যোগাযোগ করুন</span><span data-lang="en" class="hidden">Contact Us</span></a>
                  </div>
              </div>

              <!-- 4. Publications -->
              <a href="publications.php" class="whitespace-nowrap rounded-full px-3 py-2 text-sm font-semibold transition hover:bg-slate-100 text-slate-700 hover:text-[#0e1b64] <?php echo isActiveLink('publications.php', $current_page); ?>">
                  <span data-lang="bn">প্রকাশনা ও প্রতিবেদন</span><span data-lang="en" class="hidden">Publications</span>
              </a>

              <!-- 5. News & Stories / Blogs -->
              <a href="news-and-stories.php" class="whitespace-nowrap rounded-full px-3 py-2 text-sm font-semibold transition hover:bg-slate-100 text-slate-700 hover:text-[#0e1b64] <?php echo isActiveLink('news-and-stories.php', $current_page); ?>">
                  <span data-lang="bn">সংবাদ ও অভিজ্ঞতা</span><span data-lang="en" class="hidden">News & Stories</span>
              </a>
          </nav>

          <!-- DESKTOP & MOBILE RIGHT ACTIONS -->
          <div class="flex items-center gap-2 sm:gap-3">
            
            <!-- Desktop Search Bar -->
            <form action="search.php" method="GET" class="relative hidden xl:flex items-center">
                <input type="text" name="q" placeholder="অনুসন্ধান..." data-en-placeholder="Search..." class="w-36 2xl:w-48 rounded-full border border-slate-300 bg-slate-50 px-3.5 py-1.5 text-xs text-slate-800 focus:bg-white focus:border-[#0e1b64] focus:outline-none focus:ring-1 focus:ring-[#0e1b64] transition">
                <button type="submit" aria-label="Search" class="absolute right-2.5 text-slate-400 hover:text-[#0e1b64]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                </button>
            </form>

            <!-- Desktop Language Dropdown (Right side on Desktop) -->
            <div class="relative group/desktop-lang hidden lg:block z-50 shrink-0">
                <button id="desktop-lang-btn" class="flex items-center gap-1.5 rounded-full border border-slate-300 bg-white px-3 py-1.5 text-xs font-bold text-[#0e1b64] shadow-sm hover:border-[#0e1b64]/40 transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <span class="active-lang-text uppercase">BN</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div id="desktop-lang-dropdown" class="hidden absolute right-0 top-full pt-1.5 z-50 min-w-[130px]">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xl py-1 overflow-hidden">
                        <button data-set-lang="bn" class="lang-toggle-btn w-full text-left px-4 py-2 text-xs font-bold text-[#0e1b64] hover:bg-slate-50 flex items-center justify-between">
                            বাংলা
                            <svg class="w-3.5 h-3.5 text-[#3A7D5C] check-bn" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button data-set-lang="en" class="lang-toggle-btn w-full text-left px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 flex items-center justify-between">
                            English
                            <svg class="w-3.5 h-3.5 text-[#3A7D5C] check-en hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Join Button (Desktop & Mobile) -->
            <a href="https://membership.fuminds.com/" target="_blank" class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-[#0e1b64] hover:bg-[#0345bf] px-3.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-bold text-white shadow-sm transition">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3.5 w-3.5 sm:h-4 sm:w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M8.5 7a4 4 0 100 8 4 4 0 000-8zM20 8v6M23 11h-6"></path>
              </svg>
              <span data-lang="bn">যোগদান</span>
              <span data-lang="en" class="hidden">JOIN</span>
            </a>

            <!-- Mobile Hamburger Menu Button -->
            <button id="mobile-menu-btn" aria-label="Toggle Menu" class="grid h-9 w-9 sm:h-10 sm:w-10 shrink-0 place-items-center rounded-lg border border-slate-300 bg-white lg:hidden text-[#0e1b64]">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5 menu-open-icon">
                <path d="M4 7h16M4 12h16M4 17h16" />
              </svg>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5 menu-close-icon hidden">
                <path d="M6 6l12 12M18 6L6 18" />
              </svg>
            </button>
          </div>
        </div>

        <!-- MOBILE MENU OVERLAY & DRAWER -->
        <div id="mobile-menu" class="overflow-y-auto border-t border-slate-200 bg-white/98 backdrop-blur-lg transition-[max-height] duration-300 lg:hidden max-h-0">
          <nav class="mx-auto flex max-w-7xl flex-col px-4 py-3 sm:px-6 space-y-1">
              
              <!-- Mobile Search -->
              <form action="search.php" method="GET" class="relative my-2">
                  <input type="text" name="q" placeholder="অনুসন্ধান করুন..." data-en-placeholder="Search..." class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-[#0e1b64] focus:outline-none focus:ring-1 focus:ring-[#0e1b64]">
                  <button type="submit" aria-label="Search" class="absolute right-3 top-3 text-slate-400">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
                  </button>
              </form>

              <!-- Home -->
              <a href="index.php" class="rounded-lg px-3 py-2.5 text-sm font-bold text-[#0e1b64] hover:bg-slate-100 <?php echo isActiveLink('index.php', $current_page); ?>">
                  <span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span>
              </a>
              
              <!-- Who We Are (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100 [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">Who We Are</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180 text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-[#0e1b64]/20 ml-3 mb-2 mt-1 space-y-1">
                      <a href="about.php" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">সংগঠন ও ইতিহাস</span><span data-lang="en" class="hidden">History & Mission</span></a>
                      <a href="about.php#mission" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">লক্ষ্য ও উদ্দেশ্য</span><span data-lang="en" class="hidden">Vision & Objectives</span></a>
                      <a href="about.php#team" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">কর্মকর্তাবৃন্দ ও টিম</span><span data-lang="en" class="hidden">Leadership & Team</span></a>
                      <a href="member-criteria.php" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">সদস্যতার নিয়মাবলী</span><span data-lang="en" class="hidden">Membership Rules</span></a>
                      <a href="contact.php" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Contact Us</span></a>
                  </div>
              </details>

              <!-- What We Do (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100 [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">কার্যক্রম সমূহ</span><span data-lang="en" class="hidden">What We Do</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180 text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-[#0e1b64]/20 ml-3 mb-2 mt-1 space-y-1">
                      <a href="index.php#programs" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">সুশিক্ষা কার্যক্রম</span><span data-lang="en" class="hidden">Quality Education</span></a>
                      <a href="index.php#programs" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">সুশাসন ও মানবাধিকার</span><span data-lang="en" class="hidden">Good Governance</span></a>
                      <a href="index.php#programs" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">সুস্বাস্থ্য ও পুষ্টি</span><span data-lang="en" class="hidden">Community Health</span></a>
                      <a href="index.php#programs" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">সুনাগরিক তৈরি</span><span data-lang="en" class="hidden">Active Citizenship</span></a>
                      <a href="projects.php" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">সকল প্রকল্পসমূহ</span><span data-lang="en" class="hidden">All Projects</span></a>
                  </div>
              </details>
              
              <!-- Engage & Act (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100 [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">অংশগ্রহণ করুন</span><span data-lang="en" class="hidden">Engage & Act</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180 text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-[#0e1b64]/20 ml-3 mb-2 mt-1 space-y-1">
                      <a href="gov-links.php" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">সরকারি লিংকসমূহ</span><span data-lang="en" class="hidden">Govt Links</span></a>
                      <a href="forms.php" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">আবেদন ফরম</span><span data-lang="en" class="hidden">Application Forms</span></a>
                      <a href="contact.php" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0e1b64]"><span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Contact</span></a>
                  </div>
              </details>
              
              <!-- Publications -->
              <a href="publications.php" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100 <?php echo isActiveLink('publications.php', $current_page); ?>">
                  <span data-lang="bn">প্রকাশনা ও প্রতিবেদন</span><span data-lang="en" class="hidden">Publications</span>
              </a>

              <!-- News & Stories -->
              <a href="news-and-stories.php" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-100 <?php echo isActiveLink('news-and-stories.php', $current_page); ?>">
                  <span data-lang="bn">সংবাদ ও অভিজ্ঞতা</span><span data-lang="en" class="hidden">News & Stories</span>
              </a>

              <!-- Donate -->
              <div class="pt-2">
                <a href="donation.php" class="flex items-center justify-center gap-2 rounded-lg bg-[#3A7D5C] text-white px-4 py-2.5 text-sm font-bold shadow-sm">
                    <span data-lang="bn">অনুদান দিন</span>
                    <span data-lang="en" class="hidden">Donate</span>
                </a>
              </div>
          </nav>
        </div>
    
<script>
// Mega menu and Sub-dropdown robust hover delay logic
document.addEventListener('DOMContentLoaded', () => {
    // 1. Desktop Mega Menu hover handling
    const navItemGroups = document.querySelectorAll('.nav-item-group');
    navItemGroups.forEach(group => {
        let timer;
        group.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            // close other active groups
            navItemGroups.forEach(other => {
                if (other !== group) other.classList.remove('active-menu');
            });
            group.classList.add('active-menu');
        });
        group.addEventListener('mouseleave', () => {
            timer = setTimeout(() => {
                group.classList.remove('active-menu');
            }, 250);
        });
    });

    // 2. Sub-dropdown items inside mega menu
    const subItems = document.querySelectorAll('.nav-sub-item');
    subItems.forEach(sub => {
        let subTimer;
        sub.addEventListener('mouseenter', () => {
            clearTimeout(subTimer);
            sub.classList.add('active-sub');
        });
        sub.addEventListener('mouseleave', () => {
            subTimer = setTimeout(() => {
                sub.classList.remove('active-sub');
            }, 200);
        });
    });

    // 3. Language Dropdown Toggle (Desktop & Mobile Touch support)
    function setupLangDropdown(btnId, dropdownId) {
        const btn = document.getElementById(btnId);
        const dropdown = document.getElementById(dropdownId);
        if (!btn || !dropdown) return;

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = !dropdown.classList.contains('hidden');
            // Close all
            document.querySelectorAll('#desktop-lang-dropdown, #mobile-lang-dropdown').forEach(d => d.classList.add('hidden'));
            if (!isOpen) {
                dropdown.classList.remove('hidden');
            }
        });
    }

    setupLangDropdown('desktop-lang-btn', 'desktop-lang-dropdown');
    setupLangDropdown('mobile-lang-btn', 'mobile-lang-dropdown');

    // Close language dropdowns on click outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#desktop-lang-btn') && !e.target.closest('#desktop-lang-dropdown') &&
            !e.target.closest('#mobile-lang-btn') && !e.target.closest('#mobile-lang-dropdown')) {
            document.querySelectorAll('#desktop-lang-dropdown, #mobile-lang-dropdown').forEach(d => d.classList.add('hidden'));
        }
    });
});
</script>

</header>
