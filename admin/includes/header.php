<?php
ob_start();
// admin/includes/header.php
require_once __DIR__ . '/../../includes/auth.php';
init_secure_session();

require_once __DIR__ . '/../../includes/sanitize.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/db.php'; // Required for all CRUD
require_admin_login();

$current_page = basename($_SERVER['PHP_SELF']);

// Define navigation
$nav_items = [
    ['key' => 'dashboard.php', 'label' => 'Dashboard', 'icon' => '<path d="M3 3h7v7H3zm11 0h7v7h-7zm0 11h7v7h-7zm-11 0h7v7H3z"/>'],
    ['key' => 'sliders_admin.php', 'label' => 'Hero Sliders', 'icon' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M10 4v4m4-4v4M4 12h16M7 16h2m4 0h4"/>'],
    ['key' => 'notices.php', 'label' => 'Notices', 'icon' => '<path d="M6 3h9l5 5v13H6z M14 3v6h6" stroke-linejoin="round" />'],
    ['key' => 'projects_admin.php', 'label' => 'Projects', 'icon' => '<path d="M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5M3 18l9 5 9-5" stroke-linejoin="round" />'],
    ['key' => 'blogs_admin.php', 'label' => 'Blogs & News', 'icon' => '<path d="M4 19h16v2H4zm14-4H6V5h12v10z"/>'],
    ['key' => 'team_admin.php', 'label' => 'Committee & Team', 'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
    ['key' => 'forms_admin.php', 'label' => 'Application Forms', 'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>'],
    ['key' => 'gallery_admin.php', 'label' => 'Gallery', 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><path d="M21 15l-5-5L5 21" />'],
    ['key' => 'publications_admin.php', 'label' => 'Publications', 'icon' => '<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>'],
    ['key' => 'gov_links_admin.php', 'label' => 'Gov Links', 'icon' => '<path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-linecap="round" stroke-linejoin="round"/>'],
    ['key' => 'contact_messages.php', 'label' => 'Contact Messages', 'icon' => '<rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 7l9 6 9-6" />'],
    ['key' => 'donation_interests.php', 'label' => 'Donation Interests', 'icon' => '<path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" />'],
    ['key' => 'subscribers_admin.php', 'label' => 'Subscribers', 'icon' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>'],
    ['key' => 'feedback_admin.php', 'label' => 'Feedback', 'icon' => '<path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ['key' => 'admins_admin.php', 'label' => 'Admin Users', 'icon' => '<circle cx="12" cy="7" r="4"/><path d="M5.5 21v-2a6.5 6.5 0 0 1 13 0v2"/>'],
    ['key' => 'settings.php', 'label' => 'Site & Settings', 'icon' => '<circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1A1.7 1.7 0 004.6 9a1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z" />']
];

$page_title = 'Dashboard';
foreach($nav_items as $item) {
    if ($item['key'] === $current_page) {
        $page_title = $item['label'];
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CDS</title>
    <link rel="icon" type="image/png" href="/assets/img/cds-logo.png">
    <!-- Bengali Fonts: Kalpurush + SolaimanLipi -->
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link href="/assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../../assets/css/style.css'); ?>" rel="stylesheet">
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</head>
<body class="min-h-screen bg-slate-100 font-sans-bn text-slate-800">
    
    <!-- Sidebar -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform border-r border-slate-200 bg-white transition-transform lg:translate-x-0 -translate-x-full flex flex-col">
        <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-200 px-5 bg-white">
            <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary text-primary-foreground">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path d="M12 3l3 6 6 .9-4.5 4.3 1.1 6.3L12 17.8 6.4 20.5l1.1-6.3L3 9.9 9 9z" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="min-w-0">
                <div class="font-serif-bn text-sm font-bold text-slate-900">CDS Admin</div>
                <div class="truncate text-[11px] text-slate-500">Control Panel</div>
            </div>
        </div>
        <nav class="p-3 flex-1 overflow-y-auto space-y-0.5">
            <?php foreach($nav_items as $item): 
                $isActive = $item['key'] === $current_page;
            ?>
            <a href="<?php echo $item['key']; ?>" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-xs font-semibold transition <?php echo $isActive ? 'bg-primary text-primary-foreground shadow-sm' : 'text-slate-700 hover:bg-slate-100'; ?>">
                <span class="<?php echo $isActive ? 'text-primary-foreground' : 'text-slate-400'; ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                        <?php echo $item['icon']; ?>
                    </svg>
                </span>
                <?php echo $item['label']; ?>
            </a>
            <?php endforeach; ?>
            
            <div class="pt-3 pb-1 border-t border-slate-200 mt-2">
                <a href="logout.php" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-xs font-semibold transition text-rose-600 hover:bg-rose-50">
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path d="M15 4h4v16h-4M10 8l-4 4 4 4M6 12h10" stroke-linejoin="round" />
                        </svg>
                    </span>
                    Logout
                </a>
                <a href="../index.php" target="_blank" class="mt-1 flex w-full items-center gap-3 rounded-lg px-3 py-2 text-xs font-semibold transition text-slate-700 hover:bg-slate-100 border border-slate-200">
                    View Website ↗
                </a>
            </div>
        </nav>
    </aside>

    <!-- Mobile Overlay -->
    <div id="admin-overlay" class="fixed inset-0 z-30 bg-slate-900/40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div class="lg:pl-64 flex flex-col min-h-screen">
        <!-- Top bar -->
        <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6">
            <button onclick="toggleSidebar()" class="grid h-9 w-9 place-items-center rounded-md border border-slate-200 text-slate-700 lg:hidden" aria-label="Toggle menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <div class="font-serif-bn text-lg font-bold text-slate-900"><?php echo $page_title; ?></div>
            <div class="ml-auto flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-semibold text-slate-900">Admin User</div>
                </div>
                <div class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">A</div>
            </div>
        </header>

        <!-- Flash Message Placeholder -->
        <?php if(isset($_SESSION['flash_message'])): ?>
            <div class="m-6 mb-0 px-4 py-3 rounded text-white font-medium shadow-sm <?php echo $_SESSION['flash_type'] === 'error' ? 'bg-rose-500' : 'bg-emerald-500'; ?>">
                <?php 
                    echo e($_SESSION['flash_message']); 
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Main Inner -->
        <main class="flex-1 p-4 sm:p-6">
