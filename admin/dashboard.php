<?php
// admin/dashboard.php
require_once __DIR__ . '/includes/header.php';

$pdo = Database::getConnection();

$safe_count = function($sql) use ($pdo) {
    try {
        return (int)($pdo->query($sql)->fetchColumn() ?: 0);
    } catch (Throwable $e) {
        return 0;
    }
};

// Fetch stats safely
$stats = [
    'notices' => $safe_count("SELECT COUNT(*) FROM notices"),
    'projects' => $safe_count("SELECT COUNT(*) FROM projects"),
    'gallery' => $safe_count("SELECT COUNT(*) FROM gallery"),
    'unread_messages' => $safe_count("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0"),
    'pending_donations' => $safe_count("SELECT COUNT(*) FROM donation_interests WHERE status = 'pending'"),
    'admins' => $safe_count("SELECT COUNT(*) FROM admins"),
    'subscribers' => $safe_count("SELECT COUNT(*) FROM newsletter_subscribers"),
    'team' => $safe_count("SELECT COUNT(*) FROM team_members"),
    'forms' => $safe_count("SELECT COUNT(*) FROM downloadable_forms"),
    'sliders' => $safe_count("SELECT COUNT(*) FROM hero_sliders"),
];

// Fetch recent activity safely
$activities = [];

try {
    $recent_messages = $pdo->query("SELECT 'message' as type, name, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    foreach($recent_messages as $m) {
        $activities[] = [
            'type' => 'message',
            'text' => 'নতুন মেসেজ — ' . e($m['name']),
            'time' => strtotime($m['created_at'])
        ];
    }
} catch (Throwable $e) {}

try {
    $recent_donations = $pdo->query("SELECT 'donation' as type, donation_amount, payment_method, created_at FROM donation_interests ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    foreach($recent_donations as $d) {
        $activities[] = [
            'type' => 'donation',
            'text' => 'নতুন ডোনেশন — ৳ ' . number_format($d['donation_amount']) . ' (' . e($d['payment_method']) . ')',
            'time' => strtotime($d['created_at'])
        ];
    }
} catch (Throwable $e) {}

try {
    $recent_notices = $pdo->query("SELECT 'notice' as type, title_bn, created_at FROM notices ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    foreach($recent_notices as $n) {
        $activities[] = [
            'type' => 'notice',
            'text' => 'নোটিশ প্রকাশিত — ' . e($n['title_bn']),
            'time' => strtotime($n['created_at'])
        ];
    }
} catch (Throwable $e) {}

// Sort activities by time descending
usort($activities, function($a, $b) {
    return $b['time'] <=> $a['time'];
});
// Take top 6
$activities = array_slice($activities, 0, 6);

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime('@' . $datetime);
    $diff = $now->diff($ago);

    if ($diff->d == 0 && $diff->h == 0 && $diff->i < 5) {
        return 'একটু আগে';
    }
    if ($diff->d == 0 && $diff->h == 0) {
        return $diff->i . ' মিনিট আগে';
    }
    if ($diff->d == 0 && $diff->h > 0) {
        return $diff->h . ' ঘণ্টা আগে';
    }
    if ($diff->d == 1) {
        return 'গতকাল';
    }
    return $diff->d . ' দিন আগে';
}

$activityTone = function($t) {
    if ($t === 'message') return 'bg-rose-100 text-rose-700';
    if ($t === 'donation') return 'bg-violet-100 text-violet-700';
    if ($t === 'notice') return 'bg-emerald-100 text-emerald-700';
    return 'bg-blue-100 text-blue-700';
};
$activityIcon = function($t) {
    if ($t === 'message') return '<path d="M3 7l9 6 9-6" /><rect x="3" y="5" width="18" height="14" rx="2" />';
    if ($t === 'donation') return '<path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" />';
    if ($t === 'notice') return '<path d="M6 3h9l5 5v13H6z M14 3v6h6" stroke-linejoin="round" />';
    return '<circle cx="12" cy="12" r="10" />';
};

// Fetch 7-day chart data
$chart_dates = [];
$chart_labels = [];
$chart_donations = [];
$chart_messages = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('d M', strtotime($date));
    $chart_dates[$date] = ['donations' => 0, 'messages' => 0];
}

$start_date = date('Y-m-d 00:00:00', strtotime('-6 days'));

try {
    $donations_query = $pdo->prepare("SELECT DATE(created_at) as d, COUNT(*) as c FROM donation_interests WHERE created_at >= ? GROUP BY DATE(created_at)");
    $donations_query->execute([$start_date]);
    foreach ($donations_query->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if (isset($chart_dates[$row['d']])) $chart_dates[$row['d']]['donations'] = $row['c'];
    }
} catch (Throwable $e) {}

try {
    $messages_query = $pdo->prepare("SELECT DATE(created_at) as d, COUNT(*) as c FROM contact_messages WHERE created_at >= ? GROUP BY DATE(created_at)");
    $messages_query->execute([$start_date]);
    foreach ($messages_query->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if (isset($chart_dates[$row['d']])) $chart_dates[$row['d']]['messages'] = $row['c'];
    }
} catch (Throwable $e) {}

foreach ($chart_dates as $d => $data) {
    $chart_donations[] = $data['donations'];
    $chart_messages[] = $data['messages'];
}
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Dashboard Overview</h1>
        <p class="mt-1 text-sm text-slate-500">সবকিছু এক নজরে · আজ, <?php echo date('d M Y'); ?></p>
    </div>

    <!-- Stats Grid -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-500/10 to-emerald-500/0 text-emerald-700 ring-emerald-500/20 bg-white p-4 shadow-sm ring-1">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-slate-500">Total Notices</div>
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M6 3h9l5 5v13H6z M14 3v6h6" stroke-linejoin="round" /></svg>
                </span>
            </div>
            <div class="mt-2 font-serif-bn text-3xl font-bold text-slate-900"><?php echo $stats['notices']; ?></div>
            <div class="mt-1 text-[11px] font-medium">প্রকাশিত নোটিশসমূহ</div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500/10 to-blue-500/0 text-blue-700 ring-blue-500/20 bg-white p-4 shadow-sm ring-1">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-slate-500">Total Projects</div>
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5M3 18l9 5 9-5" stroke-linejoin="round" /></svg>
                </span>
            </div>
            <div class="mt-2 font-serif-bn text-3xl font-bold text-slate-900"><?php echo $stats['projects']; ?></div>
            <div class="mt-1 text-[11px] font-medium">সকল প্রকল্প</div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-amber-500/10 to-amber-500/0 text-amber-700 ring-amber-500/20 bg-white p-4 shadow-sm ring-1">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-slate-500">Gallery Images</div>
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><path d="M21 15l-5-5L5 21" /></svg>
                </span>
            </div>
            <div class="mt-2 font-serif-bn text-3xl font-bold text-slate-900"><?php echo $stats['gallery']; ?></div>
            <div class="mt-1 text-[11px] font-medium">মোট ছবি</div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-rose-500/10 to-rose-500/0 text-rose-700 ring-rose-500/20 bg-white p-4 shadow-sm ring-1 <?php echo $stats['unread_messages'] > 0 ? 'ring-2 ring-rose-400/60' : ''; ?>">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-slate-500">Unread Messages</div>
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M3 7l9 6 9-6" /><rect x="3" y="5" width="18" height="14" rx="2" /></svg>
                </span>
            </div>
            <div class="mt-2 font-serif-bn text-3xl font-bold text-slate-900">
                <?php echo $stats['unread_messages']; ?>
                <?php if($stats['unread_messages'] > 0): ?><span class="ml-1.5 inline-block h-2 w-2 rounded-full bg-rose-500 align-middle"></span><?php endif; ?>
            </div>
            <div class="mt-1 text-[11px] font-medium"><?php echo $stats['unread_messages'] > 0 ? 'মনোযোগ প্রয়োজন' : 'কোনো নতুন মেসেজ নেই'; ?></div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-violet-500/10 to-violet-500/0 text-violet-700 ring-violet-500/20 bg-white p-4 shadow-sm ring-1 <?php echo $stats['pending_donations'] > 0 ? 'ring-2 ring-violet-400/60' : ''; ?>">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-slate-500">Pending Donations</div>
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" /></svg>
                </span>
            </div>
            <div class="mt-2 font-serif-bn text-3xl font-bold text-slate-900">
                <?php echo $stats['pending_donations']; ?>
                <?php if($stats['pending_donations'] > 0): ?><span class="ml-1.5 inline-block h-2 w-2 rounded-full bg-violet-500 align-middle"></span><?php endif; ?>
            </div>
            <div class="mt-1 text-[11px] font-medium"><?php echo $stats['pending_donations'] > 0 ? 'মনোযোগ প্রয়োজন' : 'কোনো পেন্ডিং নেই'; ?></div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-indigo-500/10 to-indigo-500/0 text-indigo-700 ring-indigo-500/20 bg-white p-4 shadow-sm ring-1">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-slate-500">Subscribers</div>
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </span>
            </div>
            <div class="mt-2 font-serif-bn text-3xl font-bold text-slate-900"><?php echo $stats['subscribers']; ?></div>
            <div class="mt-1 text-[11px] font-medium">নিউজলেটার গ্রাহক</div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-teal-500/10 to-teal-500/0 text-teal-700 ring-teal-500/20 bg-white p-4 shadow-sm ring-1">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-slate-500">Team & Committee</div>
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </span>
            </div>
            <div class="mt-2 font-serif-bn text-3xl font-bold text-slate-900"><?php echo $stats['team']; ?></div>
            <div class="mt-1 text-[11px] font-medium">মোট সদস্য ও কর্মী</div>
        </div>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-slate-500/10 to-slate-500/0 text-slate-700 ring-slate-500/20 bg-white p-4 shadow-sm ring-1">
            <div class="flex items-center justify-between">
                <div class="text-xs font-medium text-slate-500">Total Admins</div>
                <span class="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1A1.7 1.7 0 004.6 9a1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z" /></svg>
                </span>
            </div>
            <div class="mt-2 font-serif-bn text-3xl font-bold text-slate-900"><?php echo $stats['admins']; ?></div>
            <div class="mt-1 text-[11px] font-medium">সিস্টেম অ্যাডমিন</div>
        </div>

    </div>

    <!-- Activity Chart -->
    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <div class="font-serif-bn text-base font-bold text-slate-900">গত ৭ দিনের কার্যক্রম</div>
        </div>
        <div class="relative w-full h-[300px]">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-[3fr_2fr]">
        <!-- Recent Activity -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <div class="font-serif-bn text-base font-bold text-slate-900">সাম্প্রতিক কার্যক্রম</div>
            </div>
            <?php if(empty($activities)): ?>
                <div class="text-sm text-slate-500">কোনো সাম্প্রতিক কার্যক্রম নেই।</div>
            <?php else: ?>
                <ul class="relative space-y-4 pl-3">
                    <span class="absolute left-[11px] top-1 bottom-1 w-px bg-slate-200"></span>
                    <?php foreach($activities as $a): ?>
                        <li class="relative flex gap-3">
                            <span class="z-10 grid h-6 w-6 shrink-0 place-items-center rounded-full ring-4 ring-white <?php echo $activityTone($a['type']); ?>">
                                <span class="scale-75"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><?php echo $activityIcon($a['type']); ?></svg></span>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm text-slate-800"><?php echo $a['text']; ?></div>
                                <div class="mt-0.5 inline-flex items-center gap-1 text-[11px] text-slate-500">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" stroke-linecap="round" /></svg>
                                    <?php echo time_elapsed_string($a['time']); ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-5">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-3 font-serif-bn text-base font-bold text-slate-900">Quick Actions</div>
                <div class="grid gap-2">
                    <a href="notices.php" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-semibold text-slate-800 hover:bg-primary hover:text-primary-foreground hover:border-primary transition group">
                        <span class="grid h-8 w-8 place-items-center rounded-md bg-primary/10 text-primary group-hover:bg-white/20"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg></span>
                        নতুন নোটিশ যোগ করুন
                    </a>
                    <a href="projects_admin.php" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-semibold text-slate-800 hover:bg-primary hover:text-primary-foreground hover:border-primary transition group">
                        <span class="grid h-8 w-8 place-items-center rounded-md bg-primary/10 text-primary group-hover:bg-white/20"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5M3 18l9 5 9-5" stroke-linejoin="round" /></svg></span>
                        নতুন প্রজেক্ট যোগ করুন
                    </a>
                    <a href="contact_messages.php" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-semibold text-slate-800 hover:bg-primary hover:text-primary-foreground hover:border-primary transition group">
                        <span class="grid h-8 w-8 place-items-center rounded-md bg-primary/10 text-primary group-hover:bg-white/20"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M3 7l9 6 9-6" /><rect x="3" y="5" width="18" height="14" rx="2" /></svg></span>
                        মেসেজ দেখুন
                    </a>
                    <a href="gallery_admin.php" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-semibold text-slate-800 hover:bg-primary hover:text-primary-foreground hover:border-primary transition group">
                        <span class="grid h-8 w-8 place-items-center rounded-md bg-primary/10 text-primary group-hover:bg-white/20"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><path d="M21 15l-5-5L5 21" /></svg></span>
                        গ্যালারিতে ছবি আপলোড
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    // Data from PHP
    const labels = <?php echo json_encode($chart_labels); ?>;
    const donationsData = <?php echo json_encode($chart_donations); ?>;
    const messagesData = <?php echo json_encode($chart_messages); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Donations',
                    data: donationsData,
                    backgroundColor: 'rgba(139, 92, 246, 0.8)', // Violet-500
                    borderRadius: 4,
                },
                {
                    label: 'Messages',
                    data: messagesData,
                    backgroundColor: 'rgba(244, 63, 94, 0.8)', // Rose-500
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 12
                        },
                        usePointStyle: true,
                        boxWidth: 8
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { family: "'Inter', sans-serif", size: 13 },
                    bodyFont: { family: "'Inter', sans-serif", size: 13 },
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: { family: "'Inter', sans-serif", size: 11 }
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)',
                        drawBorder: false,
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 11 }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
