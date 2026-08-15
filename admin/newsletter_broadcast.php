<?php
// admin/newsletter_broadcast.php
// Admin Email Newsletter Broadcast Composer & Dispatcher

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/mailer.php';

$pdo = Database::getConnection();
$message = '';
$error = '';
$stats_info = null;

// Total active subscribers
$active_subscribers = [];
try {
    $active_subscribers = $pdo->query("SELECT id, email, subscribed_at FROM newsletter_subscribers WHERE status = 'active' ORDER BY subscribed_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $active_subscribers = [];
}
$total_active = count($active_subscribers);

// Handle Dispatch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dispatch_newsletter'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'নিরাপত্তা টোকেন মেয়াদোত্তীর্ণ হয়েছে। পুনরায় চেষ্টা করুন।';
    } else {
        $subject = clean_input($_POST['subject'] ?? '');
        $title = clean_input($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $btn_text = clean_input($_POST['btn_text'] ?? '');
        $btn_url = clean_input($_POST['btn_url'] ?? '');
        $target_audience = clean_input($_POST['target_audience'] ?? 'all');
        $test_email = clean_input($_POST['test_email'] ?? '');

        if (empty($subject) || empty($content)) {
            $error = 'অনুগ্রহ করে ইমেইলের বিষয় (Subject) এবং বার্তা (Content) পূরণ করুন।';
        } else {
            $recipients = [];
            if ($target_audience === 'test') {
                if (empty($test_email) || !filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'সঠিক টেস্ট ইমেইল ঠিকানা প্রদান করুন।';
                } else {
                    $recipients[] = ['email' => $test_email];
                }
            } else {
                $recipients = $active_subscribers;
            }

            if (empty($error)) {
                if (empty($recipients)) {
                    $error = 'পাঠানোর মতো কোনো সক্রিয় সাবস্ক্রাইবার পাওয়া যায়নি।';
                } else {
                    $template_file = __DIR__ . '/../templates/email/newsletter_template.html';
                    $template = file_exists($template_file) ? file_get_contents($template_file) : '{{CONTENT}}';

                    $site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'cds.fuminds.com');

                    // Format paragraphs
                    $formatted_content = nl2br(htmlspecialchars($content));

                    // Build optional button block
                    $button_block = '';
                    if (!empty($btn_text) && !empty($btn_url)) {
                        $button_block = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 28px 0 10px 0;">
                            <tr>
                                <td align="center">
                                    <a href="' . htmlspecialchars($btn_url) . '" style="display: inline-block; background-color: #0e1b64; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 700; padding: 13px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(14,27,100,0.2);">' . htmlspecialchars($btn_text) . '</a>
                                </td>
                            </tr>
                        </table>';
                    }

                    $sent_count = 0;
                    $failed_count = 0;
                    $start_time = microtime(true);

                    foreach ($recipients as $r) {
                        $r_email = $r['email'];
                        $unsub_url = get_unsubscribe_url($r_email);

                        $search = [
                            '{{SUBJECT}}',
                            '{{TITLE}}',
                            '{{CONTENT}}',
                            '{{BUTTON_BLOCK}}',
                            '{{SITE_URL}}',
                            '{{UNSUBSCRIBE_URL}}',
                            '{{YEAR}}'
                        ];

                        $replace = [
                            htmlspecialchars($subject),
                            htmlspecialchars($title ?: $subject),
                            $formatted_content,
                            $button_block,
                            $site_url,
                            $unsub_url,
                            date('Y')
                        ];

                        $email_body = str_replace($search, $replace, $template);
                        $res = send_cds_email($r_email, $subject, $email_body);

                        if ($res['success']) {
                            $sent_count++;
                        } else {
                            $failed_count++;
                        }

                        // Slight pause for batch delivery
                        usleep(50000); // 50ms
                    }

                    $duration = round(microtime(true) - $start_time, 2);
                    $stats_info = [
                        'total' => count($recipients),
                        'sent' => $sent_count,
                        'failed' => $failed_count,
                        'time' => $duration
                    ];

                    if ($sent_count > 0) {
                        $message = "নিউজলেটার ক্যাম্পেইন সম্পন্ন হয়েছে! সফল: {$sent_count} জন, ব্যর্থ: {$failed_count} জন (সময়: {$duration} সে.)";
                    } else {
                        $error = "একটি ইমেইলও পাঠানো সম্ভব হয়নি। অনুগ্রহ করে Settings থেকে SMTP সংযোগ চেক করুন।";
                    }
                }
            }
        }
    }
}
?>

<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">নিউজলেটার ব্রডকাস্ট কম্পোজার (Newsletter Campaign)</h1>
            <p class="text-sm text-slate-500 mt-1">ওয়েবসাইটের সকল সক্রিয় সাবস্ক্রাইবারদের কাছে এক ক্লিকে অফিশিয়াল ইমেইল আপডেট প্রেরণ করুন।</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="subscribers_admin.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                গ্রাহক তালিকা (<?php echo $total_active; ?>)
            </a>
            <a href="settings.php?tab=email" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-xs font-bold text-indigo-700 hover:bg-indigo-50 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                SMTP সেটিংস
            </a>
        </div>
    </div>

    <!-- Alerts -->
    <?php if ($message): ?>
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm font-medium flex items-center gap-3 shadow-sm">
            <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?php echo e($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-sm font-medium flex items-center gap-3 shadow-sm">
            <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <!-- Main Grid Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Form Composer (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <form method="POST" id="broadcastForm" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="dispatch_newsletter" value="1">

                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1.5">
                        ইমেইল সাবজেক্ট (Email Subject) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="subject" required placeholder="যেমন: সিডিএস-এর নতুন শিক্ষা প্রকল্প ও মাসিক আপডেট" value="<?php echo e($_POST['subject'] ?? ''); ?>" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 font-medium outline-none focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20 transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1.5">
                        ব্যানার শিরোনাম (Headline Title inside Email)
                    </label>
                    <input type="text" name="title" placeholder="যেমন: প্রান্তিক শিশুদের শিক্ষা সহায়তায় নতুন উদ্যোগ" value="<?php echo e($_POST['title'] ?? ''); ?>" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 font-medium outline-none focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20 transition">
                    <span class="text-xs text-slate-400 mt-1 block">খালি রাখলে ইমেইল সাবজেক্টকেই হেডিং হিসেবে ব্যবহার করা হবে।</span>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1.5">
                        বার্তার বিবরণ (Message Content) <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="content" rows="9" required placeholder="এখানে আপনার পূর্ণাঙ্গ বার্তা লিখুন..." class="w-full rounded-xl border border-slate-300 bg-slate-50 p-4 text-sm text-slate-900 leading-relaxed outline-none focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/20 transition"><?php echo e($_POST['content'] ?? ''); ?></textarea>
                    <span class="text-xs text-slate-400 mt-1 block">প্যারাগ্রাফ ও লাইন ব্রেক স্বয়ংক্রিয়ভাবে ইমেইলে সুন্দরভাবে বিন্যস্ত হবে।</span>
                </div>

                <!-- Call to action button -->
                <div class="p-5 bg-slate-50 rounded-xl border border-slate-200/80 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">কল-টু-অ্যাকশন বাটন (Optional Button)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">বাটনের লেখা (Button Text)</label>
                            <input type="text" name="btn_text" placeholder="যেমন: বিস্তারিত পড়ুন / আবেদন করুন" value="<?php echo e($_POST['btn_text'] ?? ''); ?>" class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs text-slate-800 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">বাটনের লিংক (Button URL)</label>
                            <input type="url" name="btn_url" placeholder="https://cds.fuminds.com/projects.php" value="<?php echo e($_POST['btn_url'] ?? ''); ?>" class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs text-slate-800 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </div>

                <!-- Target Audience -->
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-800">টার্গেট অডিয়েন্স (Target Audience)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                            <input type="radio" name="target_audience" value="all" checked class="h-4 w-4 text-primary focus:ring-primary">
                            <div>
                                <span class="block text-xs font-bold text-slate-800">সকল সক্রিয় সাবস্ক্রাইবার</span>
                                <span class="block text-[11px] text-slate-500">মোট <?php echo $total_active; ?> জনের কাছে যাবে</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                            <input type="radio" name="target_audience" value="test" class="h-4 w-4 text-primary focus:ring-primary">
                            <div>
                                <span class="block text-xs font-bold text-slate-800">শুধুমাত্র টেস্ট ইমেইল</span>
                                <span class="block text-[11px] text-slate-500">একটি নির্দিষ্ট ইনবক্সে পাঠিয়ে চেক করুন</span>
                            </div>
                        </label>
                    </div>
                    
                    <div id="testEmailInputWrapper" class="hidden pt-2">
                        <input type="email" name="test_email" placeholder="টেস্ট প্রাপকের ইমেইল লিখুন..." class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-800 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <button type="button" onclick="previewNewsletter()" class="px-5 py-2.5 rounded-xl border border-slate-300 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        লাইভ প্রিভিউ দেখুন (Preview)
                    </button>

                    <button type="submit" onclick="return confirm('আপনি কি নিশ্চিতভাবে এই নিউজলেটারটি পাঠাতে চান?');" class="px-7 py-3 rounded-xl bg-primary hover:brightness-110 text-white text-sm font-bold shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        নিউজলেটার প্রেরণ করুন (Send Broadcast)
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Guide & Summary (1 col) -->
        <div class="space-y-6">
            <!-- Active stats box -->
            <div class="bg-gradient-to-br from-[#0e1b64] to-[#1e3a8a] text-white rounded-2xl p-6 shadow-md">
                <span class="text-xs font-bold text-blue-200 uppercase tracking-wider block mb-1">সাবস্ক্রিপশন ডাটাবেস</span>
                <div class="text-3xl font-black mb-2"><?php echo $total_active; ?> <span class="text-sm font-normal text-blue-200">জন সক্রিয় গ্রাহক</span></div>
                <p class="text-xs text-blue-100 leading-relaxed">এই গ্রাহকগণ ওয়েবসাইট থেকে সরাসরি নিউজলেটার পেতে সাবস্ক্রাইব করেছেন। প্রতিটি ইমেইলে স্বয়ংক্রিয়ভাবে আনসাবস্ক্রাইব লিংক সংযুক্ত থাকবে।</p>
            </div>

            <!-- Best Practices Guide -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ইমেইল পাঠানোর টিপস
                </h3>
                <ul class="text-xs text-slate-600 space-y-2.5 leading-relaxed list-disc pl-4">
                    <li>প্রথমে <strong>"শুধুমাত্র টেস্ট ইমেইল"</strong> সিলেক্ট করে নিজের ইমেইলে পাঠিয়ে দেখে নিন।</li>
                    <li>সাবজেক্ট লাইনে স্প্যাম শব্দ (যেমন: FREE, 100%, টাকা আয়) এড়িয়ে চলুন।</li>
                    <li>বড় আকারের ইমেজ ইমেইলের ভেতরে এম্বেড না করে ওয়েবসাইটের লিংক দিন।</li>
                    <li>প্রতিটি গ্রাহক তাদের ইনবক্স থেকে ১ ক্লিকে আনসাবস্ক্রাইব করতে পারবেন।</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<!-- Modal for Live Preview -->
<div id="previewModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-sm font-bold text-slate-800">ইমেইল লাইভ প্রিভিউ (Desktop & Mobile Simulation)</h3>
            <button onclick="closePreview()" class="text-slate-400 hover:text-slate-700 text-lg font-bold p-1">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto flex-grow bg-slate-100">
            <div id="previewContent" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-lg mx-auto"></div>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-end bg-white">
            <button onclick="closePreview()" class="px-5 py-2 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-900 transition">বন্ধ করুন</button>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="target_audience"]').forEach(elem => {
    elem.addEventListener('change', function() {
        const testInputWrapper = document.getElementById('testEmailInputWrapper');
        if (this.value === 'test') {
            testInputWrapper.classList.remove('hidden');
        } else {
            testInputWrapper.classList.add('hidden');
        }
    });
});

function previewNewsletter() {
    const subject = document.querySelector('input[name="subject"]').value || 'ইমেইল সাবজেক্ট';
    const title = document.querySelector('input[name="title"]').value || subject;
    const content = document.querySelector('textarea[name="content"]').value || 'এখানে আপনার বার্তার বিবরণ থাকবে...';
    const btnText = document.querySelector('input[name="btn_text"]').value;
    const btnUrl = document.querySelector('input[name="btn_url"]').value;

    let buttonHtml = '';
    if (btnText && btnUrl) {
        buttonHtml = `<div style="text-align:center; margin:24px 0;"><a href="${btnUrl}" target="_blank" style="display:inline-block; background:#0e1b64; color:#fff; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:bold; font-size:14px;">${btnText}</a></div>`;
    }

    const previewHtml = `
        <div style="text-align:center; padding-bottom:15px; border-bottom:1px solid #e2e8f0; margin-bottom:15px;">
            <img src="/assets/img/cds-logo.png" style="height:44px; display:inline-block; margin-bottom:6px;">
            <div style="font-weight:bold; color:#0e1b64; font-size:15px;">সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)</div>
        </div>
        <h2 style="color:#0e1b64; font-size:18px; font-weight:bold; margin:0 0 12px 0;">${title}</h2>
        <div style="font-size:14px; line-height:1.7; color:#334155; white-space:pre-wrap;">${content}</div>
        ${buttonHtml}
        <div style="text-align:center; border-top:1px solid #e2e8f0; padding-top:15px; margin-top:20px; font-size:11px; color:#94a3b8;">
            &copy; ${new Date().getFullYear()} CDS | <a href="#" style="color:#e11d48;">আনসাবস্ক্রাইব করুন</a>
        </div>
    `;

    document.getElementById('previewContent').innerHTML = previewHtml;
    document.getElementById('previewModal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
