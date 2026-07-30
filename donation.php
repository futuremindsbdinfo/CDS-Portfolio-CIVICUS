<?php
// donation.php
require_once __DIR__ . '/includes/auth.php';
init_secure_session();
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/sanitize.php';
require_once __DIR__ . '/includes/db.php';

$success_message = '';
$error_message = '';
$form_data = [
    'donor_name' => '',
    'donor_phone' => '',
    'donor_email' => '',
    'donation_amount' => '',
    'payment_method' => '',
    'transaction_id' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF
    if (!verify_csrf_token($csrf_token)) {
        $error_message = "নিরাপত্তা ত্রুটি (CSRF)। দয়া করে আবার চেষ্টা করুন।";
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $pdo = Database::getConnection();

        // Rate limit: max 5 requests per hour from same IP
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM donation_interests WHERE ip_address = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $stmt_check->execute([$ip_address]);
        if ($stmt_check->fetchColumn() >= 5) {
            $error_message = "আপনি অনেক বেশিবার চেষ্টা করেছেন। দয়া করে কিছুক্ষণ পর আবার চেষ্টা করুন।";
        } else {
            $form_data['donor_name'] = clean_input($_POST['donor_name'] ?? '');
            $form_data['donor_phone'] = clean_input($_POST['donor_phone'] ?? '');
            $form_data['donor_email'] = clean_input($_POST['donor_email'] ?? '');
            $form_data['donation_amount'] = clean_input($_POST['donation_amount'] ?? '');
            $form_data['payment_method'] = clean_input($_POST['payment_method'] ?? '');
            $form_data['transaction_id'] = clean_input($_POST['transaction_id'] ?? '');

            $amount = floatval($form_data['donation_amount']);

            if (empty($form_data['donor_name']) || empty($form_data['donor_phone']) || empty($form_data['donation_amount']) || empty($form_data['payment_method']) || empty($form_data['transaction_id'])) {
                $error_message = "দয়া করে সব আবশ্যক ফিল্ড পূরণ করুন।";
            } elseif ($amount < 100) {
                $error_message = "ন্যূনতম অনুদানের পরিমাণ ১০০ টাকা।";
            } else {
                $stmt = $pdo->prepare("INSERT INTO donation_interests (donor_name, donor_phone, donor_email, donation_amount, payment_method, transaction_id, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$form_data['donor_name'], $form_data['donor_phone'], $form_data['donor_email'], $amount, $form_data['payment_method'], $form_data['transaction_id'], $ip_address])) {
                    $success_message = "ধন্যবাদ, " . e($form_data['donor_name']) . "! আপনার অনুদানের তথ্য সফলভাবে জমা হয়েছে। ৭২ ঘণ্টার মধ্যে ইমেইলে রশিদ পাঠানো হবে।";
                    // Reset form
                    $form_data = array_fill_keys(array_keys($form_data), '');
                } else {
                    $error_message = "একটি ত্রুটি ঘটেছে। দয়া করে আবার চেষ্টা করুন।";
                }
            }
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-warm-grain min-h-screen font-sans-bn text-foreground">
  <div class="bg-secondary px-4 py-12 text-center sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
      <h1 class="font-serif-bn text-3xl font-bold text-white sm:text-4xl lg:text-5xl leading-tight">ডোনেশন</h1>
      <p class="mt-4 text-base text-white/80 sm:text-lg">আপনার প্রতিটি অবদান একটি শিশুর হাতে বই, একটি মায়ের হাতে সেবা তুলে দেয়।</p>
      <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-sm text-white/80">
        <a href="/index.php" class="hover:text-white hover:underline">হোম</a>
        <span class="opacity-50">/</span>
        <span>ডোনেশন</span>
      </div>
    </div>
  </div>

  <!-- Hero band -->
  <section class="mx-auto max-w-7xl px-4 pt-10 sm:px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-3xl border border-primary/20 bg-surface p-8 shadow-card sm:p-12">
      <div class="absolute -right-16 -top-16 h-72 w-72 rounded-full bg-primary-soft"></div>
      <div class="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-secondary/10"></div>
      <div class="relative grid gap-10 lg:grid-cols-[1.4fr_1fr] lg:items-center">
        <div>
          <span class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">
            <span class="h-1.5 w-1.5 rounded-full bg-primary"></span>
            আপনি পাশে থাকলে সম্ভব
          </span>
          <h2 class="mt-4 font-serif-bn text-3xl font-bold leading-tight sm:text-4xl">
            একটি ছোট অনুদান, <span class="bg-gradient-to-br from-primary to-secondary bg-clip-text text-transparent">বদলে দিতে পারে একটি জীবন</span>
          </h2>
          <p class="mt-4 max-w-xl text-base leading-relaxed text-muted-foreground">
            আপনার অনুদানে চলে গ্রামীণ পাঠাগার, মাতৃস্বাস্থ্য ক্যাম্প, তরুণদের প্রশিক্ষণ ও
            শীতবস্ত্র বিতরণের মতো কর্মসূচি। প্রতিটি টাকা কোথায় গেল — আমরা প্রতি বছর
            নিরীক্ষিত প্রতিবেদনে জানাই।
          </p>
          <div class="mt-6 flex flex-wrap gap-2 text-xs font-semibold">
            <span class="rounded-full border border-border bg-surface px-3 py-1.5 text-foreground/80">৭৫% প্রকল্পে</span>
            <span class="rounded-full border border-border bg-surface px-3 py-1.5 text-foreground/80">১৫% সরাসরি উপকারভোগীতে</span>
            <span class="rounded-full border border-border bg-surface px-3 py-1.5 text-foreground/80">বার্ষিক নিরীক্ষিত</span>
          </div>
        </div>
        <div class="relative hidden aspect-square lg:block">
          <svg viewBox="0 0 400 400" class="absolute inset-0 h-full w-full">
            <defs>
              <linearGradient id="dh" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#3A7D5C" />
                <stop offset="100%" stop-color="#1e3a8a" />
              </linearGradient>
            </defs>
            <circle cx="200" cy="200" r="150" fill="url(#dh)" opacity="0.95" />
            <path d="M200 300s-90-55-90-125a45 45 0 0190-30 45 45 0 0190 30c0 70-90 125-90 125z" fill="#FAF8F3" opacity="0.95" />
            <path d="M200 300s-90-55-90-125a45 45 0 0190-30 45 45 0 0190 30c0 70-90 125-90 125z" fill="none" stroke="#3A7D5C" stroke-width="4" />
            <circle cx="200" cy="200" r="150" fill="none" stroke="#fff" stroke-width="2" stroke-dasharray="4 8" opacity="0.5" />
          </svg>
        </div>
      </div>
    </div>
  </section>

  <!-- Trust Band -->
  <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8 text-center">
      <div class="text-xs font-semibold uppercase tracking-widest text-primary">স্বচ্ছতা ও ভরসা</div>
      <h2 class="mt-2 font-serif-bn text-2xl font-bold sm:text-3xl">আমাদের প্রতি কেন ভরসা রাখবেন</h2>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <div class="rounded-2xl border border-border bg-surface p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-card">
        <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-primary/10 text-primary">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="mt-4 font-serif-bn text-lg font-bold">স্বাধীন নিরীক্ষা</h3>
        <p class="mt-2 text-xs text-muted-foreground">প্রতি বছর সরকার অনুমোদিত ফার্ম দ্বারা হিসাব নিরীক্ষা করা হয়।</p>
      </div>
      <div class="rounded-2xl border border-border bg-surface p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-card">
        <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-primary/10 text-primary">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="mt-4 font-serif-bn text-lg font-bold">১০০% ব্যবহার</h3>
        <p class="mt-2 text-xs text-muted-foreground">আপনার দেওয়া প্রতিটি পয়সা সরাসরি উন্নয়নমূলক কাজে ব্যয় করা হয়।</p>
      </div>
      <div class="rounded-2xl border border-border bg-surface p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-card">
        <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-primary/10 text-primary">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h3 class="mt-4 font-serif-bn text-lg font-bold">নিয়মিত রিপোর্ট</h3>
        <p class="mt-2 text-xs text-muted-foreground">বার্ষিক প্রতিবেদন ও নিয়মিত আপডেটের মাধ্যমে সব তথ্য প্রকাশ করা হয়।</p>
      </div>
      <div class="rounded-2xl border border-border bg-surface p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-card">
        <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-primary/10 text-primary">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <h3 class="mt-4 font-serif-bn text-lg font-bold">নিরাপদ লেনদেন</h3>
        <p class="mt-2 text-xs text-muted-foreground">আপনার তথ্য ও লেনদেন সম্পূর্ণ সুরক্ষিত এবং গোপন রাখা হয়।</p>
      </div>
    </div>
  </section>

  <!-- Payment methods -->
  <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-8 text-center">
      <div class="text-xs font-semibold uppercase tracking-widest text-primary">পেমেন্ট মেথড</div>
      <h2 class="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">যেকোনো একটি মাধ্যমে অনুদান পাঠান</h2>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
      <?php
      $methods = [
        ["name" => "বিকাশ", "type" => "মার্চেন্ট", "number" => "01700-000000", "ref" => "Reference: DONATION", "instructions" => "Payment অপশন থেকে উপরের নম্বরে টাকা পাঠান। পিন দেওয়ার আগে রেফারেন্সে DONATION লিখুন।", "accent" => "linear-gradient(135deg, #e2136e, #be105b)"],
        ["name" => "নগদ", "type" => "মার্চেন্ট", "number" => "01700-000000", "ref" => "Reference: DONATION", "instructions" => "Merchant Pay অপশন থেকে উপরের নম্বরে টাকা পাঠান। রেফারেন্স হিসেবে DONATION ব্যবহার করুন।", "accent" => "linear-gradient(135deg, #ed3b25, #c8321f)"],
        ["name" => "রকেট", "type" => "মার্চেন্ট", "number" => "01700-000000-0", "ref" => "Reference: DONATION", "instructions" => "Merchant Pay অপশন থেকে উপরের নম্বরে টাকা পাঠান।", "accent" => "linear-gradient(135deg, #8c1e82, #6b1763)"],
        ["name" => "সোনালী ব্যাংক", "type" => "চলতি হিসাব", "number" => "0000 1234 5678", "ref" => "Routing: 123456789 (Motijheel)", "instructions" => "ব্যাংক ট্রান্সফার বা BEFTN এর মাধ্যমে সরাসরি একাউন্টে টাকা পাঠাতে পারেন।", "accent" => "linear-gradient(135deg, #0f766e, #0e5e58)"]
      ];
      foreach ($methods as $m):
      ?>
      <div class="group relative overflow-hidden rounded-2xl border border-border bg-surface shadow-card transition hover:-translate-y-1 hover:shadow-card-hover">
        <div class="relative p-5 text-white" style="background: <?php echo $m['accent']; ?>">
          <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
          <div class="absolute -bottom-8 -left-4 h-16 w-16 rounded-full bg-black/10"></div>
          <div class="relative">
            <div class="text-[10px] font-semibold uppercase tracking-widest text-white/80"><?php echo $m['type']; ?></div>
            <div class="mt-1 font-serif-bn text-xl font-bold"><?php echo $m['name']; ?></div>
          </div>
          <div class="relative mt-4 space-y-2">
            <div class="flex items-center justify-between gap-3 rounded-xl bg-white/15 px-3 py-2 text-white backdrop-blur">
              <div class="min-w-0">
                <div class="text-[10px] font-semibold uppercase tracking-widest text-white/70">নম্বর / A/C</div>
                <div class="truncate font-mono text-sm font-bold"><?php echo $m['number']; ?></div>
              </div>
              <button type="button" onclick="copyToClipboard('<?php echo $m['number']; ?>', this)" class="flex shrink-0 items-center justify-center rounded-lg bg-white/20 px-2 py-1.5 transition hover:bg-white/30" title="কপি করুন">
                <span class="icon-wrap">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                  </svg>
                </span>
                <span class="text-wrap hidden ml-1 text-[10px] font-semibold">কপি হয়েছে!</span>
              </button>
            </div>
            <div class="text-[11px] font-medium text-white/80"><?php echo $m['ref']; ?></div>
          </div>
        </div>
        <div class="p-5">
          <p class="text-xs leading-relaxed text-muted-foreground"><?php echo $m['instructions']; ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Interest form -->
  <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-border bg-surface p-6 shadow-card sm:p-10" id="donate-form">
      <div class="mb-6">
        <div class="text-xs font-semibold uppercase tracking-widest text-primary">ডোনেশন-ইন্টারেস্ট ফর্ম</div>
        <h2 class="mt-2 font-serif-bn text-2xl font-bold sm:text-3xl">অনুদান তথ্য নিবন্ধন করুন</h2>
        <p class="mt-2 rounded-xl border border-warning/30 bg-warning/10 p-3 text-xs leading-relaxed text-warning-foreground">
          <strong>নোট:</strong> এই ফর্মটি শুধুমাত্র আপনার অনুদানের তথ্য নিবন্ধন করে — এখানে সরাসরি অনলাইন পেমেন্ট প্রসেস হয় না। উপরের যেকোনো পেমেন্ট মেথডে অনুদান পাঠানোর পর নিচের ট্রানজেকশন আইডি সংযুক্ত করে ফর্মটি পূরণ করুন।
        </p>
      </div>

      <?php if ($success_message): ?>
        <div class="rounded-2xl border border-success/30 bg-success/10 p-6 text-center">
          <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-success text-success-foreground">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="h-6 w-6">
              <path d="M5 12l5 5L20 7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </span>
          <h3 class="mt-3 font-serif-bn text-xl font-bold"><?php echo $success_message; ?></h3>
          <a href="/donation.php#donate-form" class="mt-4 inline-flex items-center gap-2 rounded-full border border-border bg-surface px-5 py-2 text-sm font-semibold hover:bg-primary-soft hover:text-primary">
            নতুন ফর্ম
          </a>
        </div>
      <?php else: ?>
        <?php if ($error_message): ?>
          <div class="mb-6 rounded-2xl border border-destructive/30 bg-destructive/10 p-4 text-sm font-semibold text-destructive">
            <?php echo $error_message; ?>
          </div>
        <?php endif; ?>

        <form action="/donation.php#donate-form" method="POST" class="grid gap-4 sm:grid-cols-2">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-foreground">পূর্ণ নাম <span class="text-destructive">*</span></label>
            <input type="text" name="donor_name" value="<?php echo e($form_data['donor_name']); ?>" required placeholder="আব্দুল করিম" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
          </div>
          
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-foreground">ফোন নম্বর <span class="text-destructive">*</span></label>
            <input type="text" name="donor_phone" value="<?php echo e($form_data['donor_phone']); ?>" required placeholder="01700-000000" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
          </div>
          
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-foreground">ইমেইল (ঐচ্ছিক)</label>
            <input type="email" name="donor_email" value="<?php echo e($form_data['donor_email']); ?>" placeholder="you@example.com" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
          </div>
          
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-foreground">ডোনেশনের পরিমাণ (৳) <span class="text-destructive">*</span></label>
            <input type="number" name="donation_amount" value="<?php echo e($form_data['donation_amount']); ?>" required min="100" placeholder="১০০০" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
          </div>
          
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-foreground">পেমেন্ট মেথড <span class="text-destructive">*</span></label>
            <select name="payment_method" required class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
              <option value="">নির্বাচন করুন</option>
              <option value="bkash" <?php echo $form_data['payment_method'] === 'bkash' ? 'selected' : ''; ?>>বিকাশ</option>
              <option value="nagad" <?php echo $form_data['payment_method'] === 'nagad' ? 'selected' : ''; ?>>নগদ</option>
              <option value="rocket" <?php echo $form_data['payment_method'] === 'rocket' ? 'selected' : ''; ?>>রকেট</option>
              <option value="bank" <?php echo $form_data['payment_method'] === 'bank' ? 'selected' : ''; ?>>সোনালী ব্যাংক</option>
              <option value="cash" <?php echo $form_data['payment_method'] === 'cash' ? 'selected' : ''; ?>>অফিসে ক্যাশ</option>
            </select>
          </div>
          
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-foreground">ট্রানজেকশন আইডি <span class="text-destructive">*</span></label>
            <input type="text" name="transaction_id" value="<?php echo e($form_data['transaction_id']); ?>" required placeholder="TX9A82K1Z" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20">
          </div>

          <div class="sm:col-span-2 mt-2 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-muted-foreground">
              ফর্ম জমা দিয়ে আপনি আমাদের <a href="#" class="font-semibold text-primary hover:underline">গোপনীয়তা নীতিমালা</a> মেনে নিচ্ছেন।
            </p>
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110">
              <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" />
              </svg>
              ফর্ম জমা দিন
            </button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </section>

  <!-- Donation FAQ -->
  <section class="mx-auto max-w-4xl px-4 pb-20 sm:px-6 lg:px-8">
    <div class="mb-8 text-center">
      <div class="text-xs font-semibold uppercase tracking-widest text-primary">প্রশ্নোত্তর</div>
      <h2 class="mt-2 font-serif-bn text-2xl font-bold sm:text-3xl">ডোনেশন সংক্রান্ত সাধারণ প্রশ্ন</h2>
    </div>
    <div class="space-y-3">
      <?php
      $faqs = [
        ["q" => "আমার অনুদানের টাকা কোথায় ব্যয় হবে?", "a" => "CDS-এর মূল ৫টি স্তম্ভ (সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক, উন্নত বাংলাদেশ) ভিত্তিক বিভিন্ন প্রকল্পে আপনার অনুদান ব্যয় হবে।"],
        ["q" => "আমি কি অনুদানের রশিদ পাব?", "a" => "হ্যাঁ, আপনার অনুদানটি যাচাই হওয়ার পর ৭২ ঘণ্টার মধ্যে ইমেইলে সরকারি নিয়ম অনুযায়ী একটি রশিদ পাঠানো হবে।"],
        ["q" => "বিকাশ বা নগদে টাকা পাঠানোর পর কী করতে হবে?", "a" => "টাকা পাঠানোর পর প্রাপ্ত Transaction ID (TrxID) দিয়ে উপরের ফর্মটি পূরণ করে জমা দিন।"],
        ["q" => "নির্দিষ্ট প্রকল্পে অনুদান দেওয়া যাবে কি?", "a" => "হ্যাঁ, ফর্মে মন্তব্যের ঘরে (যদি থাকে, অথবা ইমেইল করে) প্রকল্পের নাম উল্লেখ করলে আপনার অনুদান শুধুমাত্র সেই প্রকল্পে ব্যবহৃত হবে।"]
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
</div>

<script>
function copyToClipboard(text, btn) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            const iconWrap = btn.querySelector('.icon-wrap');
            const textWrap = btn.querySelector('.text-wrap');
            if (iconWrap && textWrap) {
                iconWrap.classList.add('hidden');
                textWrap.classList.remove('hidden');
                setTimeout(() => {
                    iconWrap.classList.remove('hidden');
                    textWrap.classList.add('hidden');
                }, 2000);
            }
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
