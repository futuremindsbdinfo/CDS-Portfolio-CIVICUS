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

// Handle Feedback Form Submission
$feedback_success = false;
$feedback_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_submit'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $feedback_error = 'Invalid request. Please try again.';
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        // Rate limit: max 3 feedbacks per hour per IP
        try {
            $check = $pdo->prepare("SELECT COUNT(*) FROM feedback WHERE ip_address = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
            $check->execute([$ip_address]);
            if ($check->fetchColumn() >= 3) {
                $feedback_error = 'আপনি ইতোমধ্যে অনেক ফিডব্যাক দিয়েছেন। পরে চেষ্টা করুন।';
            } else {
                $fb_name    = clean_input($_POST['fb_name'] ?? '');
                $fb_email   = clean_input($_POST['fb_email'] ?? '');
                // Removed rating field, hardcoding it to 5 for db consistency
                $fb_rating  = 5;
                $fb_message = clean_input($_POST['fb_message'] ?? '');

                if (empty($fb_name) || empty($fb_message)) {
                    $feedback_error = 'নাম ও মতামত অবশ্যই দিন।';
                } else {
                    $ins = $pdo->prepare("INSERT INTO feedback (name, email, rating, message, ip_address) VALUES (?, ?, ?, ?, ?)");
                    if ($ins->execute([$fb_name, $fb_email, $fb_rating, $fb_message, $ip_address])) {
                        $feedback_success = true;
                    } else {
                        $feedback_error = 'একটি সমস্যা হয়েছে। পরে চেষ্টা করুন।';
                    }
                }
            }
        } catch (PDOException $e) {
            $feedback_error = 'ডাটাবেজ সমস্যা। পরে চেষ্টা করুন।';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="relative overflow-hidden pt-2 lg:pt-4">
<div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:gap-8 lg:px-8 lg:py-16">
    <div class="relative flex flex-col items-center text-center lg:items-start lg:text-left">
    <h1 class="mt-5 font-serif-bn text-4xl font-bold leading-[1.15] tracking-tight sm:text-5xl lg:text-6xl">
        <span class="bg-gradient-to-br from-primary to-secondary bg-clip-text text-transparent">
            <span data-lang="bn">সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)</span>
            <span data-lang="en" class="hidden">Citizen Development Society (CDS)</span>
        </span>
    </h1>
    <span class="inline-flex items-center rounded-full border border-primary/20 bg-primary-soft px-4 py-1.5 text-sm font-semibold text-primary mt-3">
        <span data-lang="bn">অরাজনৈতিক, অলাভজনক ও স্বেচ্ছাসেবী সংগঠন</span>
        <span data-lang="en" class="hidden">Non-political, Non-profit & Voluntary Organization</span>
    </span>
    <p class="mt-5 max-w-xl text-base leading-relaxed text-muted-foreground sm:text-lg">
        <span data-lang="bn">কুমিল্লার নাঙ্গলকোট ও লালমাই উপজেলা থেকে শুরু করে সমগ্র বাংলাদেশে প্রান্তিক জনপদের উন্নয়নে আমরা কাজ করে যাচ্ছি।</span>
        <span data-lang="en" class="hidden">Working for the development of marginalized communities across Bangladesh, starting from Nangalkot and Lalmai upazilas of Cumilla.</span>
    </p>
    <div class="mt-8 flex flex-wrap justify-center lg:justify-start gap-3">
        <a href="#about" class="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110">
        <span data-lang="bn">আমাদের সম্পর্কে জানুন</span><span data-lang="en" class="hidden">Learn About Us</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        </a>
        <a href="https://membership.fuminds.com/" target="_blank" class="inline-flex items-center gap-2 rounded-full border border-border bg-surface px-5 py-3 text-sm font-semibold text-foreground transition hover:bg-primary-soft hover:text-primary">
        <span data-lang="bn">সদস্য হোন</span><span data-lang="en" class="hidden">Become a Member</span>
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
        <div class="text-xs text-white/80"><span data-lang="bn">সক্রিয় জেলা</span><span data-lang="en" class="hidden">Active Districts</span></div>
        <div class="font-serif-bn text-2xl font-bold text-white"><span data-lang="bn">১</span><span data-lang="en" class="hidden">1</span></div>
        </div>
        <div class="absolute -right-2 top-8 rounded-2xl bg-primary p-4 shadow-card animate-[float_6s_ease-in-out_infinite]" style="animation-delay: 1s;">
        <div class="text-xs text-white/80"><span data-lang="bn">চলমান প্রকল্প</span><span data-lang="en" class="hidden">Ongoing Projects</span></div>
        <div class="font-serif-bn text-2xl font-bold text-white"><span data-lang="bn">১</span><span data-lang="en" class="hidden">1</span></div>
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
    <div class="text-base font-bold uppercase tracking-widest text-primary"><span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">About Us</span></div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
        <span data-lang="bn">মানুষের জন্য, মানবতার জন্য</span><span data-lang="en" class="hidden">For People, For Humanity</span>
    </h2>
    <p class="mt-4 text-base leading-relaxed text-muted-foreground">
        <span data-lang="bn">সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস) একটি অরাজনৈতিক, অলাভজনক, স্বেচ্ছাসেবী ও নাগরিকভিত্তিক সিভিল সোসাইটি সংগঠন। আমাদের লক্ষ্য — একটি অন্তর্ভুক্তিমূলক, ন্যায়ভিত্তিক ও টেকসই সমাজ।</span>
        <span data-lang="en" class="hidden">Citizen Development Society (CDS) is a non-political, non-profit, voluntary and citizen-based civil society organization. Our goal is an inclusive, just, and sustainable society.</span>
    </p>

          <a
            href="about.php"
            class="mt-8 inline-flex items-center gap-2 font-semibold text-primary transition-colors hover:text-primary-soft group"
          >
            <span data-lang="bn">বিস্তারিত জানুন</span><span data-lang="en" class="hidden">Learn More</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:translate-x-1">
              <path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
          
          <!-- CDS Signature Pillars Ribbon -->
          <div class="mt-8 pt-6 border-t border-border/40 flex flex-wrap gap-3">
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default"><span data-lang="bn">সুশিক্ষা</span><span data-lang="en" class="hidden">Quality Education</span></span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default"><span data-lang="bn">সুশাসন</span><span data-lang="en" class="hidden">Good Governance</span></span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default"><span data-lang="bn">সুস্বাস্থ্য</span><span data-lang="en" class="hidden">Health and Well-being</span></span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default"><span data-lang="bn">সুনাগরিক</span><span data-lang="en" class="hidden">Active Citizenship</span></span>
              <span class="px-4 py-1.5 bg-background text-primary text-sm font-semibold rounded-full border border-primary/20 shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground cursor-default"><span data-lang="bn">উন্নত বাংলাদেশ</span><span data-lang="en" class="hidden">Prosperous Bangladesh</span></span>
          </div>
        </div>
      </div>
    </section>

<!-- PILLARS -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 text-center">
    <h2 class="font-serif-bn text-3xl font-bold sm:text-4xl">
    <span data-lang="bn">আমাদের মূলমন্ত্র</span><span data-lang="en" class="hidden">Our Pillars</span>
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
            <h3 class="mt-4 font-serif-bn text-xl font-bold"><span data-lang="bn">সুশিক্ষা</span><span data-lang="en" class="hidden">Quality Education</span></h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><span data-lang="bn">শিক্ষাকে সবার জন্য সহজলভ্য ও মানসম্পন্ন করে তোলা।</span><span data-lang="en" class="hidden">Making education accessible and qualitative for everyone.</span></p>
        </div>
    </div>
    
    <!-- 2. সুশাসন -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M4 21h16M6 21V10M18 21V10M4 10l8-6 8 6" stroke-linejoin="round"></path><path d="M10 21v-6h4v6"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold"><span data-lang="bn">সুশাসন</span><span data-lang="en" class="hidden">Good Governance</span></h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><span data-lang="bn">স্বচ্ছতা, জবাবদিহিতা ও ন্যায়ের চর্চা।</span><span data-lang="en" class="hidden">Practice of transparency, accountability and justice.</span></p>
        </div>
    </div>
    
    <!-- 3. সুস্বাস্থ্য -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold"><span data-lang="bn">সুস্বাস্থ্য</span><span data-lang="en" class="hidden">Health and Well-being</span></h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><span data-lang="bn">প্রতিটি পরিবারে সুস্থ ও নিরাপদ জীবনের নিশ্চয়তা।</span><span data-lang="en" class="hidden">Ensuring healthy and safe life for every family.</span></p>
        </div>
    </div>

    <!-- 4. সুনাগরিক -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><circle cx="12" cy="8" r="3.2"></circle><path d="M5 20c1.5-3.5 4.2-5 7-5s5.5 1.5 7 5" stroke-linecap="round"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold"><span data-lang="bn">সুনাগরিক</span><span data-lang="en" class="hidden">Active Citizenship</span></h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><span data-lang="bn">দায়িত্বশীল ও সচেতন নাগরিক গড়ে তোলা।</span><span data-lang="en" class="hidden">Building responsible and conscious citizens.</span></p>
        </div>
    </div>
    
    <!-- 5. উন্নত বাংলাদেশ -->
    <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125"></div>
        <div class="relative">
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <h3 class="mt-4 font-serif-bn text-xl font-bold"><span data-lang="bn">উন্নত বাংলাদেশ</span><span data-lang="en" class="hidden">Prosperous Bangladesh</span></h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><span data-lang="bn">সমৃদ্ধ, স্বনির্ভর ও আধুনিক বাংলাদেশ বিনির্মাণ।</span><span data-lang="en" class="hidden">Building a prosperous, self-reliant and modern Bangladesh.</span></p>
        </div>
    </div>
</div>
</section>

<!-- PROGRAMS -->
<section id="programs" class="bg-gray-50 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl text-gray-900"><span data-lang="bn">আমাদের কার্যক্রম</span><span data-lang="en" class="hidden">Our Programs</span></h2>
        </div>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            <!-- Program 1 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                </div>
                <h3 class="font-serif-bn text-xl font-bold mb-2"><span data-lang="bn">শিক্ষা সহায়তা</span><span data-lang="en" class="hidden">Education Support</span></h3>
                <p class="text-sm text-gray-600"><span data-lang="bn">শিক্ষার্থীদের জন্য শিক্ষা উপকরণ, বৃত্তি, কোচিং সহায়তা ও ক্যারিয়ার গাইডলাইন প্রদান।</span><span data-lang="en" class="hidden">Providing educational materials, scholarships, coaching support and career guidelines for students.</span></p>
            </div>
            <!-- Program 2 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="font-serif-bn text-xl font-bold mb-2"><span data-lang="bn">স্বাস্থ্য ক্যাম্প</span><span data-lang="en" class="hidden">Health Camp</span></h3>
                <p class="text-sm text-gray-600"><span data-lang="bn">স্বাস্থ্য ক্যাম্প, রক্তদান কর্মসূচি, বিনামূল্যে চিকিৎসা পরামর্শ ও স্বাস্থ্য সচেতনতা কার্যক্রম।</span><span data-lang="en" class="hidden">Health camps, blood donation programs, free medical advice and health awareness activities.</span></p>
            </div>
            <!-- Program 3 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-serif-bn text-xl font-bold mb-2"><span data-lang="bn">পরিবেশ রক্ষা</span><span data-lang="en" class="hidden">Environmental Protection</span></h3>
                <p class="text-sm text-gray-600"><span data-lang="bn">পরিবেশ রক্ষা, বৃক্ষরোপণ, পরিচ্ছন্নতা অভিযান ও জলবায়ু সচেতনতা কর্মসূচি।</span><span data-lang="en" class="hidden">Environmental protection, tree plantation, cleanliness drives and climate awareness programs.</span></p>
            </div>
            <!-- Program 4 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="font-serif-bn text-xl font-bold mb-2"><span data-lang="bn">সামাজিক উন্নয়ন</span><span data-lang="en" class="hidden">Social Development</span></h3>
                <p class="text-sm text-gray-600"><span data-lang="bn">যুব নেতৃত্ব উন্নয়ন, মাদকবিরোধী প্রচারণা ও প্রান্তিক জনগোষ্ঠীর উন্নয়নমূলক প্রকল্প গ্রহণ।</span><span data-lang="en" class="hidden">Youth leadership development, anti-drug campaigns and undertaking developmental projects for marginalized communities.</span></p>
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
        <div class="mt-2 text-sm font-medium text-white/80"><span data-lang="bn">স্বেচ্ছাসেবী</span><span data-lang="en" class="hidden">Volunteers</span></div>
    </div>
    <div class="text-center">
        <div class="count-up font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl" data-target="0" data-suffix="">০</div>
        <div class="mt-2 text-sm font-medium text-white/80"><span data-lang="bn">সম্পন্ন প্রজেক্ট</span><span data-lang="en" class="hidden">Completed Projects</span></div>
    </div>
    <div class="text-center">
        <div class="count-up font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl" data-target="0" data-suffix="">০</div>
        <div class="mt-2 text-sm font-medium text-white/80"><span data-lang="bn">উপকারভোগী পরিবার</span><span data-lang="en" class="hidden">Beneficiary Families</span></div>
    </div>
    <div class="text-center">
        <div class="count-up font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl" data-target="1" data-suffix="">১</div>
        <div class="mt-2 text-sm font-medium text-white/80"><span data-lang="bn">সক্রিয় জেলা</span><span data-lang="en" class="hidden">Active Districts</span></div>
    </div>
</div>
</section>

<!-- SHORTCUTS -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 flex items-end justify-between gap-4">
    <div>
    <div class="text-base font-bold uppercase tracking-widest text-primary"><span data-lang="bn">গুরুত্বপূর্ণ লিংক</span><span data-lang="en" class="hidden">Important Links</span></div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl"><span data-lang="bn">দ্রুত পৌঁছান</span><span data-lang="en" class="hidden">Quick Access</span></h2>
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
            <h3 class="mt-4 font-serif-bn text-lg font-bold"><span data-lang="bn">প্রজেক্টস</span><span data-lang="en" class="hidden">Projects</span></h3>
            <p class="mt-1 text-sm text-muted-foreground"><span data-lang="bn">আমাদের চলমান ও সম্পন্ন সকল উন্নয়ন প্রকল্প।</span><span data-lang="en" class="hidden">All our ongoing and completed development projects.</span></p>
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
            <h3 class="mt-4 font-serif-bn text-lg font-bold"><span data-lang="bn">গ্যালারি</span><span data-lang="en" class="hidden">Gallery</span></h3>
            <p class="mt-1 text-sm text-muted-foreground"><span data-lang="bn">সংগঠনের কর্মকাণ্ডের মুহূর্তগুলি।</span><span data-lang="en" class="hidden">Moments of organizational activities.</span></p>
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
            <h3 class="mt-4 font-serif-bn text-lg font-bold"><span data-lang="bn">নোটিশ বোর্ড</span><span data-lang="en" class="hidden">Notice Board</span></h3>
            <p class="mt-1 text-sm text-muted-foreground"><span data-lang="bn">গুরুত্বপূর্ণ ঘোষণা ও পরিপত্র।</span><span data-lang="en" class="hidden">Important announcements and circulars.</span></p>
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
            <h3 class="mt-4 font-serif-bn text-lg font-bold"><span data-lang="bn">সদস্য ফর্ম</span><span data-lang="en" class="hidden">Membership Form</span></h3>
            <p class="mt-1 text-sm text-muted-foreground"><span data-lang="bn">সাধারণ, আজীবন, দাতা, উপদেষ্টা বা ছাত্র সদস্য হিসেবে যুক্ত হোন।</span><span data-lang="en" class="hidden">Join as a General, Life, Donor, Advisor or Student member.</span></p>
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
            <h3 class="mt-4 font-serif-bn text-lg font-bold"><span data-lang="bn">ব্লগ</span><span data-lang="en" class="hidden">Blog</span></h3>
            <p class="mt-1 text-sm text-muted-foreground"><span data-lang="bn">আমাদের সর্বশেষ খবর ও চিন্তাধারা।</span><span data-lang="en" class="hidden">Our latest news and thoughts.</span></p>
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
            <h3 class="mt-4 font-serif-bn text-lg font-bold"><span data-lang="bn">প্রকাশনা</span><span data-lang="en" class="hidden">Publications</span></h3>
            <p class="mt-1 text-sm text-muted-foreground"><span data-lang="bn">গবেষণা, ম্যাগাজিন এবং প্রতিবেদন।</span><span data-lang="en" class="hidden">Research, magazines and reports.</span></p>
        </div>
    </a>
</div>
</section>

<!-- PROJECTS -->
<section id="projects" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 flex flex-wrap items-end justify-between gap-4">
    <div>
    <div class="text-base font-bold uppercase tracking-widest text-primary"><span data-lang="bn">ফিচার্ড প্রজেক্ট</span><span data-lang="en" class="hidden">Featured Projects</span></div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl"><span data-lang="bn">আমাদের সাম্প্রতিক কাজ</span><span data-lang="en" class="hidden">Our Recent Work</span></h2>
    </div>
    <a href="/projects.php" class="text-sm font-semibold text-primary hover:underline">
    <span data-lang="bn">সব প্রজেক্ট দেখুন &rarr;</span><span data-lang="en" class="hidden">View All Projects &rarr;</span>
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
            <?php echo $p['status'] === 'completed' ? '<span data-lang="bn">সম্পন্ন</span><span data-lang="en" class="hidden">Completed</span>' : '<span data-lang="bn">চলমান</span><span data-lang="en" class="hidden">Ongoing</span>'; ?>
        </span>
        </div>
        <div class="p-5">
        <div class="text-xs font-medium text-muted-foreground"><?php echo e($p['title_en'] ?? ''); ?></div>
        <h3 class="mt-1 font-serif-bn text-lg font-bold"><?php echo e($p['title_bn']); ?></h3>
        <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><?php echo mb_substr(e($p['description_bn']), 0, 100) . '...'; ?></p>
        <a href="/projects.php" class="mt-4 inline-flex text-sm font-semibold text-primary hover:underline">
            <span data-lang="bn">বিস্তারিত &rarr;</span><span data-lang="en" class="hidden">Details &rarr;</span>
        </a>
        </div>
    </article>
    <?php endforeach; ?>
    <?php if(empty($latest_projects)): ?>
        <p class="text-muted-foreground"><span data-lang="bn">কোন প্রজেক্ট পাওয়া যায়নি।</span><span data-lang="en" class="hidden">No projects found.</span></p>
    <?php endif; ?>
</div>
</section>

<!-- NOTICES -->
<section id="notices" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
<div class="grid gap-10 lg:grid-cols-[1fr_2fr]">
    <div>
    <h2 class="font-serif-bn text-3xl font-bold sm:text-4xl"><span data-lang="bn">সাম্প্রতিক নোটিশ</span><span data-lang="en" class="hidden">Recent Notices</span></h2>
    <a href="/notice.php" class="mt-6 inline-flex items-center gap-2 rounded-full border border-border bg-surface px-4 py-2 text-sm font-semibold text-primary hover:bg-primary-soft">
        <span data-lang="bn">সব নোটিশ দেখুন &rarr;</span><span data-lang="en" class="hidden">View All Notices &rarr;</span>
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
        <li class="p-5 text-muted-foreground"><span data-lang="bn">কোন নোটিশ পাওয়া যায়নি।</span><span data-lang="en" class="hidden">No notices found.</span></li>
    <?php endif; ?>
    </ul>
</div>
</section>

<!-- FAQ -->
<section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
<div class="mb-10 text-center">
    <div class="text-base font-bold uppercase tracking-widest text-primary"><span data-lang="bn">প্রশ্নোত্তর</span><span data-lang="en" class="hidden">Q & A</span></div>
    <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl"><span data-lang="bn">প্রায়শই জিজ্ঞাসিত প্রশ্ন</span><span data-lang="en" class="hidden">Frequently Asked Questions</span></h2>
</div>
<div class="space-y-3">
    <?php
    $faqs = [
        [
            "q" => '<span data-lang="bn">সিডিএস কী ধরনের সংগঠন?</span><span data-lang="en" class="hidden">What kind of organization is CDS?</span>', 
            "a" => '<span data-lang="bn">এটি একটি অরাজনৈতিক, অলাভজনক, স্বেচ্ছাসেবী ও নাগরিকভিত্তিক সিভিল সোসাইটি সংগঠন (ধারা ২)।</span><span data-lang="en" class="hidden">It is a non-political, non-profit, voluntary and citizen-based civil society organization (Article 2).</span>'
        ],
        [
            "q" => '<span data-lang="bn">কারা সিডিএস-এর সদস্য হতে পারবেন?</span><span data-lang="en" class="hidden">Who can be a member of CDS?</span>', 
            "a" => '<span data-lang="bn">যারা সমাজের কল্যাণে কাজ করতে ইচ্ছুক এবং কোনো রাষ্ট্রবিরোধী কর্মকাণ্ডে জড়িত নন (ধারা ১১)। সাধারণ, আজীবন, দাতা, উপদেষ্টা এবং যুব/ছাত্র ক্যাটাগরিতে সদস্য হওয়া যাবে।</span><span data-lang="en" class="hidden">Those who are willing to work for the welfare of society and are not involved in any anti-state activities (Article 11). Membership can be in General, Life, Donor, Advisor and Youth/Student categories.</span>'
        ],
        [
            "q" => '<span data-lang="bn">সিডিএস-এর সাধারণ সদস্য হওয়ার যোগ্যতা কী?</span><span data-lang="en" class="hidden">What are the qualifications to become a general member of CDS?</span>', 
            "a" => '<span data-lang="bn">ন্যূনতম ১৮ বছর বয়সী (ধারা ১১)।</span><span data-lang="en" class="hidden">Minimum 18 years of age (Article 11).</span>'
        ],
        [
            "q" => '<span data-lang="bn">কার্যনির্বাহী কমিটি কত সদস্য বিশিষ্ট?</span><span data-lang="en" class="hidden">How many members does the executive committee have?</span>', 
            "a" => '<span data-lang="bn">২১ সদস্য বিশিষ্ট (ধারা ১৪)।</span><span data-lang="en" class="hidden">Consists of 21 members (Article 14).</span>'
        ],
        [
            "q" => '<span data-lang="bn">সিডিএস-এর আয়ের প্রধান উৎস কী?</span><span data-lang="en" class="hidden">What are the main sources of income for CDS?</span>', 
            "a" => '<span data-lang="bn">সদস্যদের চাঁদা, এককালীন অনুদান ও অনুদান (ধারা ২২)।</span><span data-lang="en" class="hidden">Members subscriptions, one-time donations and grants (Article 22).</span>'
        ],
        [
            "q" => '<span data-lang="bn">সিডিএস-এর কোনো শাখা কমিটি গঠন করা যাবে কি?</span><span data-lang="en" class="hidden">Can a branch committee of CDS be formed?</span>', 
            "a" => '<span data-lang="bn">হ্যাঁ, দেশের যেকোনো উপজেলা বা শিক্ষাপ্রতিষ্ঠানে শাখা কমিটি গঠন করা যাবে (ধারা ২৫)।</span><span data-lang="en" class="hidden">Yes, branch committees can be formed in any upazila or educational institution of the country (Article 25).</span>'
        ],
        [
            "q" => '<span data-lang="bn">সংগঠনের বিলুপ্তি ঘটলে তহবিলের কী হবে?</span><span data-lang="en" class="hidden">What will happen to the funds if the organization is dissolved?</span>', 
            "a" => '<span data-lang="bn">সমমনা অন্য কোনো সংগঠন বা সরকারি অধিদপ্তরে হস্তান্তর করা হবে (ধারা ২৯)।</span><span data-lang="en" class="hidden">Will be transferred to any other like-minded organization or government department (Article 29).</span>'
        ],
        [
            "q" => '<span data-lang="bn">সিডিএস কি কোনো রাজনৈতিক দলের সাথে যুক্ত?</span><span data-lang="en" class="hidden">Is CDS associated with any political party?</span>', 
            "a" => '<span data-lang="bn">না, এটি সম্পূর্ণ অরাজনৈতিক ও স্বেচ্ছাসেবী সংগঠন।</span><span data-lang="en" class="hidden">No, it is a completely non-political and voluntary organization.</span>'
        ],
        [
            "q" => '<span data-lang="bn">সিডিএস এর কর্ম এলাকা কোথায়?</span><span data-lang="en" class="hidden">Where is the working area of CDS?</span>', 
            "a" => '<span data-lang="bn">প্রাথমিকভাবে কুমিল্লা জেলার নাঙ্গলকোট ও লালমাই উপজেলা। পরবর্তীতে সমগ্র বাংলাদেশে কার্যক্রম পরিচালনা করা হবে (ধারা ৪)।</span><span data-lang="en" class="hidden">Initially Nangalkot and Lalmai upazilas of Cumilla district. Later operations will be conducted all over Bangladesh (Article 4).</span>'
        ],
        [
            "q" => '<span data-lang="bn">CDS কীভাবে তহবিল ব্যবহার করে?</span><span data-lang="en" class="hidden">How does CDS use funds?</span>', 
            "a" => '<span data-lang="bn">সকল তহবিল সরাসরি প্রকল্প বাস্তবায়ন, উপকারভোগী সহায়তা ও পরিচালন ব্যয়ে ব্যবহৃত হয়। প্রতি বছর নিরপেক্ষ অডিট কমিটির মাধ্যমে হিসাব নিরীক্ষা করা হয় (ধারা ২৪)।</span><span data-lang="en" class="hidden">All funds are used directly in project implementation, beneficiary support and operational costs. Every year accounts are audited through an independent audit committee (Article 24).</span>'
        ],
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

<!-- SUPPORT & FEEDBACK SECTION -->
<section id="support-feedback" class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8 mt-16">
    <div class="grid gap-8 lg:grid-cols-2 items-stretch">
        
        <!-- Left: Donate Callout -->
        <div class="relative overflow-hidden rounded-3xl border border-primary/20 bg-surface p-8 shadow-card sm:p-10 flex flex-col justify-center">
            <div class="absolute -right-16 -top-16 h-72 w-72 rounded-full bg-primary-soft"></div>
            <div class="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-secondary/10"></div>
            
            <div class="relative z-10">
                <div class="text-base font-bold uppercase tracking-widest text-primary"><span data-lang="bn">আপনার সহায়তায়</span><span data-lang="en" class="hidden">With Your Support</span></div>
                <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
                    <span data-lang="bn">একটি ছোট অনুদান, বদলে দিতে পারে একটি জীবন</span>
                    <span data-lang="en" class="hidden">A small donation can change a life</span>
                </h2>
                <p class="mt-4 text-base leading-relaxed text-muted-foreground">
                    <span data-lang="bn">আপনার প্রতিটি অবদান শিশুর শিক্ষা, মায়ের স্বাস্থ্যসেবা ও তরুণদের প্রশিক্ষণে সরাসরি ব্যবহৃত হয়। আজই যুক্ত হোন।</span>
                    <span data-lang="en" class="hidden">Your every contribution is directly used for children's education, maternal healthcare, and youth training. Join today.</span>
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="donation.php" class="inline-flex items-center justify-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-card hover:brightness-110 transition">
                        <span data-lang="bn">অনুদান</span><span data-lang="en" class="hidden">Donate</span>
                    </a>
                    <a href="contact.php" class="inline-flex items-center justify-center gap-2 rounded-full border border-border bg-background px-6 py-3 text-sm font-semibold text-foreground hover:bg-primary-soft hover:text-primary transition">
                        <span data-lang="bn">যোগাযোগ করুন</span><span data-lang="en" class="hidden">Contact Us</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Feedback Form -->
        <div id="feedback" class="rounded-3xl border border-border bg-surface p-6 shadow-card sm:p-10 flex flex-col justify-center">
            
            <div class="mb-6">
                <div class="text-base font-bold uppercase tracking-widest text-primary">
                    <span data-lang="bn">মতামত</span><span data-lang="en" class="hidden">Feedback</span>
                </div>
                <h3 class="mt-1 font-serif-bn text-2xl font-bold">
                    <span data-lang="bn">আপনার মতামত জানান</span><span data-lang="en" class="hidden">Share Your Feedback</span>
                </h3>
            </div>

            <?php if ($feedback_success): ?>
            <div class="flex flex-col items-center justify-center py-8 text-center flex-grow">
                <div class="text-5xl mb-4">🎉</div>
                <h3 class="font-serif-bn text-xl font-bold text-primary">
                    <span data-lang="bn">ধন্যবাদ!</span><span data-lang="en" class="hidden">Thank You!</span>
                </h3>
                <p class="mt-2 text-sm text-muted-foreground">
                    <span data-lang="bn">আপনার মতামত সফলভাবে জমা হয়েছে।</span>
                    <span data-lang="en" class="hidden">Your feedback has been submitted successfully.</span>
                </p>
                <a href="index.php#feedback" class="mt-4 inline-flex rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground hover:brightness-110 transition">
                    <span data-lang="bn">আবার মতামত দিন</span><span data-lang="en" class="hidden">Give Another Feedback</span>
                </a>
            </div>
            <?php else: ?>

            <?php if ($feedback_error): ?>
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <?php echo htmlspecialchars($feedback_error); ?>
            </div>
            <?php endif; ?>

            <form action="index.php#feedback" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="feedback_submit" value="1">

                <!-- Name -->
                <div>
                    <label for="fb_name" class="block text-sm font-semibold text-foreground mb-1.5">
                        <span data-lang="bn">আপনার নাম *</span><span data-lang="en" class="hidden">Your Name *</span>
                    </label>
                    <input type="text" id="fb_name" name="fb_name" required maxlength="100"
                        class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="নাম লিখুন"
                        data-en-placeholder="Enter your name"
                        value="<?php echo htmlspecialchars($_POST['fb_name'] ?? ''); ?>">
                </div>

                <!-- Email (Optional) -->
                <div>
                    <label for="fb_email" class="block text-sm font-semibold text-foreground mb-1.5">
                        <span data-lang="bn">ইমেইল (ঐচ্ছিক)</span><span data-lang="en" class="hidden">Email (Optional)</span>
                    </label>
                    <input type="email" id="fb_email" name="fb_email" maxlength="150"
                        class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="example@email.com"
                        value="<?php echo htmlspecialchars($_POST['fb_email'] ?? ''); ?>">
                </div>

                <!-- Message -->
                <div>
                    <label for="fb_message" class="block text-sm font-semibold text-foreground mb-1.5">
                        <span data-lang="bn">আপনার মতামত *</span><span data-lang="en" class="hidden">Your Message *</span>
                    </label>
                    <textarea id="fb_message" name="fb_message" required rows="3" maxlength="1000"
                        class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none"
                        placeholder="আপনার অভিজ্ঞতা বা পরামর্শ লিখুন..."
                        data-en-placeholder="Write your experience or suggestion..."><?php echo htmlspecialchars($_POST['fb_message'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110 mt-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                        <path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span data-lang="bn">মতামত পাঠান</span><span data-lang="en" class="hidden">Send Feedback</span>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const currentLang = () => localStorage.getItem('cds_lang') || 'bn';

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

    const formatNumber = (num) => {
        if (currentLang() === 'en') {
            return num >= 1000 ? new Intl.NumberFormat('en-IN').format(num) : num.toString();
        }
        return convertToBengaliNumber(num);
    };

    const animateCountUp = (el, target, duration, suffix) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const currentCount = Math.floor(easeProgress * target);
            
            el.innerText = formatNumber(currentCount) + (suffix || '');
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                el.innerText = formatNumber(target) + (suffix || '');
            }
        };
        window.requestAnimationFrame(step);
    };

    // Re-format counters when language changes
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-set-lang]');
        if (btn) {
            setTimeout(() => {
                document.querySelectorAll('.count-up').forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'), 10);
                    const suffix = counter.getAttribute('data-suffix') || '';
                    counter.innerText = formatNumber(target) + suffix;
                });
            }, 50);
        }
    });

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
