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
      .custom-dropdown { display: none; }
      .group:hover .custom-dropdown {
        display: block;
        animation: dropdownFadeIn 0.18s ease-out forwards;
      }
      @keyframes dropdownFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
      }
      .sub-dropdown { display: none; }
      .group-sub:hover > .sub-dropdown {
        display: block;
        animation: subDropdownFadeIn 0.15s ease-out forwards;
      }
      @keyframes subDropdownFadeIn {
        from { opacity: 0; transform: translateX(-4px); }
        to   { opacity: 1; transform: translateX(0); }
      }
      /* Mega menu column heading style */
      .mega-col-heading {
        font-size: 0.78rem;
        font-weight: 700;
        color: #111827;
        padding-bottom: 0.5rem;
        margin-bottom: 0.6rem;
        border-bottom: 1px solid #e5e7eb;
      }
      /* Sub-item link style */
      .mega-link {
        display: block;
        font-size: 0.82rem;
        color: #4b5563;
        padding: 0.28rem 0;
        transition: color 0.15s;
        background: transparent !important;
      }
      .mega-link:hover { color: var(--color-primary, #1a6b3c); }
      /* Sub-dropdown panel */
      .sub-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        min-width: 200px;
      }
      .sub-panel a {
        display: block;
        padding: 0.45rem 1.2rem;
        font-size: 0.82rem;
        color: #4b5563;
        transition: color 0.15s;
        background: transparent !important;
      }
      .sub-panel a:hover { color: var(--color-primary, #1a6b3c); }
      /* Full-width mega panel */
      .mega-full-panel {
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        background: #fff;
        border-top: 3px solid var(--color-primary, #1a6b3c);
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        z-index: 100;
      }
      header.sticky { position: relative; }
    </style>

            <!-- HEADER -->
    <header class="sticky relative top-0 z-50 border-b border-border/60 bg-background/95 backdrop-blur-md">
        <div class="mx-auto flex justify-between max-w-[1400px] items-center gap-2 px-4 py-3 sm:px-6 lg:px-8">
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

                    <nav class="hidden items-center gap-0.5 xl:gap-1 lg:flex">
              <a href="index.php" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('index.php', $current_page); ?>">
                  <span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span>
              </a>
              
              <!-- Who We Are Mega Menu -->
              <div class="group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">Who We Are</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>

                  <div class="mega-full-panel custom-dropdown">
                      <div class="mx-auto max-w-7xl px-8 py-7 flex">
                          <!-- Col 1 -->
                          <div class="flex-1 pr-8">
                              <div class="mega-col-heading"><span data-lang="bn">আমাদের প্রভাব</span><span data-lang="en" class="hidden">Our Impact Stories</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">জেনারেল কমেন্ট ৩৭</span><span data-lang="en" class="hidden">General Comment 37</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">সহ-সৃষ্টি</span><span data-lang="en" class="hidden">Co-Creation</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">জাম্বিয়া: ১৫+ বছরের অধিকার আন্দোলন</span><span data-lang="en" class="hidden">Zambia: 15+ year campaign</span></a>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 2 -->
                          <div class="flex-1 px-8">
                              <div class="mega-col-heading"><span data-lang="bn">মূল্যবোধ ও জবাবদিহিতা</span><span data-lang="en" class="hidden">Values & Accountability</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">বৈচিত্র্য ও অন্তর্ভুক্তি</span><span data-lang="en" class="hidden">Diversity and Inclusion</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">আমাদের জবাবদিহি করুন</span><span data-lang="en" class="hidden">Hold Us to Account</span></a>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 3 -->
                          <div class="flex-1 px-8">
                              <div class="mega-col-heading"><span data-lang="bn">সংগঠন</span><span data-lang="en" class="hidden">Organization</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">বার্ষিক প্রতিবেদন</span><span data-lang="en" class="hidden">Annual Reports</span></a>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between">
                                      <span><span data-lang="bn">বোর্ড</span><span data-lang="en" class="hidden">Board</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg>
                                  </a>
                                  <div class="absolute left-full top-0 sub-dropdown z-50" style="min-width:190px">
                                      <div class="sub-panel"><a href="#"><span data-lang="bn">বোর্ড নির্বাচন ২০২৬</span><span data-lang="en" class="hidden">Board Elections 2026</span></a></div>
                                  </div>
                              </div>
                              <a href="#" class="mega-link"><span data-lang="bn">সদস্যবৃন্দ</span><span data-lang="en" class="hidden">Members</span></a>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 4 -->
                          <div class="flex-1 pl-8">
                              <div class="mega-col-heading opacity-0">x</div>
                              <a href="#" class="mega-link"><span data-lang="bn">নেটওয়ার্ক</span><span data-lang="en" class="hidden">Networks</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">কর্মকর্তাবৃন্দ</span><span data-lang="en" class="hidden">Staff</span></a>
                              <a href="contact.php" class="mega-link"><span data-lang="bn">যোগাযোগ করুন</span><span data-lang="en" class="hidden">Contact Us</span></a>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- What We Do Mega Menu -->
              <div class="group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">কার্যক্রম সমূহ</span><span data-lang="en" class="hidden">What We Do</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>

                  <div class="mega-full-panel custom-dropdown">
                      <div class="mx-auto max-w-7xl px-8 py-7 flex">
                          <!-- Col 1 -->
                          <div class="flex-1 pr-8">
                              <div class="mega-col-heading"><span data-lang="bn">গবেষণা ও তথ্য</span><span data-lang="en" class="hidden">Co-creating Knowledge</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">সিডিএস মনিটর রেটিং</span><span data-lang="en" class="hidden">CDS Monitor Ratings</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">সিডিএস লেন্স বিশ্লেষণ</span><span data-lang="en" class="hidden">CDS Lens Analysis</span></a>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between">
                                      <span><span data-lang="bn">আমাদের প্রতিবেদন</span><span data-lang="en" class="hidden">Our Reports</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg>
                                  </a>
                                  <div class="absolute left-full top-0 sub-dropdown z-50" style="min-width:220px">
                                      <div class="sub-panel">
                                          <a href="#"><span data-lang="bn">সিভিল সোসাইটি রিপোর্ট</span><span data-lang="en" class="hidden">State of Civil Society Reports</span></a>
                                          <a href="#"><span data-lang="bn">পিপল পাওয়ার আন্ডার অ্যাটাক</span><span data-lang="en" class="hidden">People Power Under Attack</span></a>
                                          <a href="#"><span data-lang="bn">অন্যান্য প্রকাশনা</span><span data-lang="en" class="hidden">Other Publications</span></a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 2 -->
                          <div class="flex-1 px-8">
                              <div class="mega-col-heading"><span data-lang="bn">পরিবর্তনের জন্য সংগ্রাম</span><span data-lang="en" class="hidden">Advocating for Change</span></div>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between">
                                      <span><span data-lang="bn">ক্যাম্পেইনসমূহ</span><span data-lang="en" class="hidden">Campaigns</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg>
                                  </a>
                              </div>
                              <a href="#" class="mega-link"><span data-lang="bn">জাতিসংঘে সিডিএস</span><span data-lang="en" class="hidden">CDS at the UN</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">নাগরিক স্থান প্রকল্প</span><span data-lang="en" class="hidden">Civic Space Project in Central America</span></a>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 3 -->
                          <div class="flex-1 px-8">
                              <div class="mega-col-heading"><span data-lang="bn">সক্ষমতা বৃদ্ধি</span><span data-lang="en" class="hidden">Enabling and Resourcing</span></div>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between"><span><span data-lang="bn">লোকাল লিডারশিপ ল্যাবস</span><span data-lang="en" class="hidden">Local Leadership Labs</span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg></a>
                              </div>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between"><span><span data-lang="bn">ডিজিটাল ডেমোক্রেসি ইনিশিয়েটিভ</span><span data-lang="en" class="hidden">Digital Democracy Initiative</span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg></a>
                              </div>
                              <a href="#" class="mega-link"><span data-lang="bn">সিডিএস যুব</span><span data-lang="en" class="hidden">CDS Youth</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">CHARM Africa</span><span data-lang="en" class="hidden">CHARM Africa</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">FoPA</span><span data-lang="en" class="hidden">FoPA</span></a>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between"><span><span data-lang="bn">সম্পন্ন প্রকল্পসমূহ</span><span data-lang="en" class="hidden">Completed Projects</span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg></a>
                                  <div class="absolute left-full top-0 sub-dropdown z-50" style="min-width:240px">
                                      <div class="sub-panel">
                                          <a href="#"><span data-lang="bn">Stand As My Witness</span><span data-lang="en" class="hidden">Stand As My Witness</span></a>
                                          <a href="#"><span data-lang="bn">Donor Challenge</span><span data-lang="en" class="hidden">Donor Challenge</span></a>
                                          <a href="#"><span data-lang="bn">আন্তর্জাতিক সিভিল সোসাইটি সপ্তাহ ২০২৫</span><span data-lang="en" class="hidden">International Civil Society Week 2025</span></a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 4 -->
                          <div class="flex-1 pl-8">
                              <div class="mega-col-heading"><span data-lang="bn">নেটওয়ার্ক গঠন</span><span data-lang="en" class="hidden">Building Networks</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">ভুকা! সিভিক অ্যাকশন কোয়ালিশন</span><span data-lang="en" class="hidden">Vuka! Coalition for Civic Action</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">AGNA</span><span data-lang="en" class="hidden">AGNA</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">পরিবর্তনের জন্য উদ্ভাবন</span><span data-lang="en" class="hidden">Innovation for Change</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">সিডিএস যুব</span><span data-lang="en" class="hidden">CDS Youth</span></a>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between"><span><span data-lang="bn">সম্পন্ন প্রকল্পসমূহ</span><span data-lang="en" class="hidden">Completed Projects</span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg></a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Engage & Act -->
              <div class="relative group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">অংশগ্রহণ করুন</span><span data-lang="en" class="hidden">Engage & Act</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  <div class="absolute left-0 top-full pt-1 custom-dropdown z-50" style="min-width:180px">
                      <div class="bg-white shadow-2xl border border-gray-100">
                          <a href="gov-links.php" class="mega-link px-5 py-2.5"><span data-lang="bn">সরকারি লিংকসমূহ</span><span data-lang="en" class="hidden">Govt Links</span></a>
                          <a href="forms.php" class="mega-link px-5 py-2.5"><span data-lang="bn">আবেদন ফরম</span><span data-lang="en" class="hidden">Application Forms</span></a>
                          <a href="contact.php" class="mega-link px-5 py-2.5"><span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Contact</span></a>
                      </div>
                  </div>
              </div>

              <!-- Publications -->
              <a href="#" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                  <span data-lang="bn">প্রতিবেদন ও প্রকাশনা</span><span data-lang="en" class="hidden">Publications</span>
              </a>

              <!-- News & Stories -->
              <a href="#" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                  <span data-lang="bn">সংবাদ ও অভিজ্ঞতা</span><span data-lang="en" class="hidden">News & Stories</span>
              </a>

              <!-- Desktop Search Bar (Right aligned) -->
              <div class="relative ml-auto flex items-center">
                  <input type="text" placeholder="Search..." class="w-32 xl:w-48 rounded-full border border-border bg-surface px-4 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute right-3 h-4 w-4 text-foreground/50"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
              </div>

              <!-- CTA Button -->
              <a href="donation.php" class="ml-2 whitespace-nowrap rounded-full bg-primary/10 px-4 py-1.5 text-sm font-bold text-primary transition hover:bg-primary hover:text-white <?php echo isActiveLink('donation.php', $current_page); ?>">
                  <span data-lang="bn">অনুদান</span><span data-lang="en" class="hidden">Donate</span>
              </a>
          </nav>

          <div class="flex items-center gap-2 sm:gap-3">
            <!-- Language Switcher -->
            <div class="hidden sm:flex shrink-0 items-center rounded-full border border-border bg-surface p-0.5 shadow-sm">
              <button data-set-lang="bn" class="lang-toggle-btn rounded-full px-3 py-1.5 text-xs font-medium transition bg-primary/20 text-primary font-bold">BN</button>
              <button data-set-lang="en" class="lang-toggle-btn rounded-full px-3 py-1.5 text-xs font-medium transition text-foreground/70 hover:text-primary">EN</button>
            </div>

            <a href="https://membership.fuminds.com/" target="_blank" class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-primary px-4 py-1.5 sm:px-5 sm:py-2 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
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
              
              <!-- Mobile Search -->
              <div class="relative my-2">
                  <input type="text" placeholder="Search..." class="w-full rounded-full border border-border bg-surface px-4 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute right-4 top-2.5 h-4 w-4 text-foreground/50"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
              </div>

              <a href="index.php" class="rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('index.php', $current_page); ?>">
                  <span data-lang="bn">Home</span><span data-lang="en" class="hidden">Home</span>
              </a>
              
              <!-- Who We Are (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">Who We Are</span><span data-lang="en" class="hidden">Who We Are</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-2 space-y-1">
                      
                      <!-- Our Impact Stories (Mobile nested) -->
                      <details class="group">
                          <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                              <span><span data-lang="bn">Our Impact Stories</span><span data-lang="en" class="hidden">Our Impact Stories</span></span>
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                          </summary>
                          <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-1 space-y-1">
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">General Comment 37</span><span data-lang="en" class="hidden">General Comment 37</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Co-Creation</span><span data-lang="en" class="hidden">Co-Creation</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Zambia: 15+ year campaign for rights</span><span data-lang="en" class="hidden">Zambia: 15+ year campaign for rights</span></a>
                          </div>
                      </details>
                      
                      <!-- Values and accountability (Mobile nested) -->
                      <details class="group">
                          <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                              <span><span data-lang="bn">Values and accountability</span><span data-lang="en" class="hidden">Values and accountability</span></span>
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                          </summary>
                          <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-1 space-y-1">
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Diversity and Inclusion</span><span data-lang="en" class="hidden">Diversity and Inclusion</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Hold Us to Account</span><span data-lang="en" class="hidden">Hold Us to Account</span></a>
                          </div>
                      </details>

                      <a href="#" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary"><span data-lang="bn">Annual Reports</span><span data-lang="en" class="hidden">Annual Reports</span></a>
                      
                      <!-- Board (Mobile nested) -->
                      <details class="group">
                          <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                              <span><span data-lang="bn">Board</span><span data-lang="en" class="hidden">Board</span></span>
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                          </summary>
                          <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-1 space-y-1">
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Board Elections 2026</span><span data-lang="en" class="hidden">Board Elections 2026</span></a>
                          </div>
                      </details>

                      <a href="#" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary"><span data-lang="bn">Members</span><span data-lang="en" class="hidden">Members</span></a>
                      <a href="#" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary"><span data-lang="bn">Networks</span><span data-lang="en" class="hidden">Networks</span></a>
                      <a href="#" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary"><span data-lang="bn">Staff</span><span data-lang="en" class="hidden">Staff</span></a>
                      <a href="contact.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary"><span data-lang="bn">Contact Us</span><span data-lang="en" class="hidden">Contact Us</span></a>
                  </div>
              </details>

              <!-- What We Do (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">What We Do</span><span data-lang="en" class="hidden">What We Do</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-2 space-y-1">
                      
                      <!-- Co-creating Knowledge -->
                      <details class="group">
                          <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                              <span><span data-lang="bn">Co-creating Knowledge</span><span data-lang="en" class="hidden">Co-creating Knowledge</span></span>
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                          </summary>
                          <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-1 space-y-1">
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">CDS Monitor Ratings</span><span data-lang="en" class="hidden">CDS Monitor Ratings</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">CDS Lens Analysis</span><span data-lang="en" class="hidden">CDS Lens Analysis</span></a>
                              <!-- Our Reports Nested -->
                              <details class="group">
                                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-1.5 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/70 [&::-webkit-details-marker]:hidden">
                                      <span><span data-lang="bn">Our Reports</span><span data-lang="en" class="hidden">Our Reports</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                  </summary>
                                  <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-1 space-y-1">
                                      <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">State of Civil Society Reports</span><span data-lang="en" class="hidden">State of Civil Society Reports</span></a>
                                      <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">People Power Under Attack</span><span data-lang="en" class="hidden">People Power Under Attack</span></a>
                                      <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Other Publications</span><span data-lang="en" class="hidden">Other Publications</span></a>
                                  </div>
                              </details>
                          </div>
                      </details>

                      <!-- Advocating for Change -->
                      <details class="group">
                          <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                              <span><span data-lang="bn">Advocating for Change</span><span data-lang="en" class="hidden">Advocating for Change</span></span>
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                          </summary>
                          <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-1 space-y-1">
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Campaigns</span><span data-lang="en" class="hidden">Campaigns</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">CDS at the UN</span><span data-lang="en" class="hidden">CDS at the UN</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Civic Space Project in Central America (ES)</span><span data-lang="en" class="hidden">Civic Space Project in Central America (ES)</span></a>
                          </div>
                      </details>

                      <!-- Enabling and Resourcing -->
                      <details class="group">
                          <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                              <span><span data-lang="bn">Enabling and Resourcing</span><span data-lang="en" class="hidden">Enabling and Resourcing</span></span>
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                          </summary>
                          <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-1 space-y-1">
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Local Leadership Labs</span><span data-lang="en" class="hidden">Local Leadership Labs</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Digital Democracy Initiative</span><span data-lang="en" class="hidden">Digital Democracy Initiative</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">CDS Youth</span><span data-lang="en" class="hidden">CDS Youth</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">CHARM Africa</span><span data-lang="en" class="hidden">CHARM Africa</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">FoPA</span><span data-lang="en" class="hidden">FoPA</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Completed Projects</span><span data-lang="en" class="hidden">Completed Projects</span></a>
                          </div>
                      </details>

                      <!-- Building Networks -->
                      <details class="group">
                          <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                              <span><span data-lang="bn">Building Networks</span><span data-lang="en" class="hidden">Building Networks</span></span>
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                          </summary>
                          <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-1 space-y-1">
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Vuka! Coalition for Civic Action</span><span data-lang="en" class="hidden">Vuka! Coalition for Civic Action</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">AGNA</span><span data-lang="en" class="hidden">AGNA</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Innovation for Change</span><span data-lang="en" class="hidden">Innovation for Change</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">CDS Youth</span><span data-lang="en" class="hidden">CDS Youth</span></a>
                              <a href="#" class="block rounded-md px-3 py-1.5 text-sm font-medium hover:text-primary text-foreground/70"><span data-lang="bn">Completed Projects</span><span data-lang="en" class="hidden">Completed Projects</span></a>
                          </div>
                      </details>
                  </div>
              </details>
              
              <!-- Engage & Act (Mobile) -->
              <details class="group">
                  <summary class="flex cursor-pointer list-none items-center justify-between rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary text-foreground/80 [&::-webkit-details-marker]:hidden">
                      <span><span data-lang="bn">Engage & Act</span><span data-lang="en" class="hidden">Engage & Act</span></span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-open:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </summary>
                  <div class="pl-4 border-l-2 border-primary/20 ml-3 mb-2 space-y-1">
                      <a href="gov-links.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary"><span data-lang="bn">Govt Links</span><span data-lang="en" class="hidden">Govt Links</span></a>
                      <a href="forms.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary"><span data-lang="bn">Application Forms</span><span data-lang="en" class="hidden">Application Forms</span></a>
                      <a href="contact.php" class="block rounded-md px-3 py-2 text-sm font-medium hover:bg-primary-soft hover:text-primary"><span data-lang="bn">Contact</span><span data-lang="en" class="hidden">Contact</span></a>
                  </div>
              </details>
              
              <a href="#" class="rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary">
                  <span data-lang="bn">Publications</span><span data-lang="en" class="hidden">Publications</span>
              </a>

              <a href="#" class="rounded-md px-3 py-3 text-sm font-medium hover:bg-primary-soft hover:text-primary">
                  <span data-lang="bn">News & Stories</span><span data-lang="en" class="hidden">News & Stories</span>
              </a>

              <!-- Mobile Language Switcher -->
              <div class="mt-4 mb-2 flex sm:hidden shrink-0 items-center justify-center rounded-full border border-border bg-surface p-1 shadow-sm">
                <button data-set-lang="bn" class="lang-toggle-btn w-1/2 rounded-full px-4 py-2 text-sm font-bold transition bg-primary/20 text-primary">BN</button>
                <button data-set-lang="en" class="lang-toggle-btn w-1/2 rounded-full px-4 py-2 text-sm font-medium transition text-foreground/70 hover:text-primary">EN</button>
              </div>
          </nav>
        </div>
    </header>
