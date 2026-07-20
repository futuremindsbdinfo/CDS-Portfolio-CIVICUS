<?php
// admin/includes/header.php
require_once __DIR__ . '/../../includes/auth.php';
init_secure_session();

require_once __DIR__ . '/../../includes/sanitize.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/db.php'; // Required for all CRUD
require_admin_login();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CDS</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- Summernote / WYSIWYG could be added here if needed, but for now we use plain textareas -->
</head>
<body class="bg-gray-100 flex min-h-screen font-sans text-gray-800">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-cds-blue text-white flex flex-col shrink-0 sticky top-0 h-screen">
        <div class="p-6 text-center border-b border-blue-800">
            <h2 class="text-2xl font-bold font-serif text-yellow-400">CDS Admin</h2>
        </div>
        <nav class="flex-grow p-4 space-y-2 overflow-y-auto">
            <a href="dashboard.php" class="block px-4 py-2 rounded <?php echo $current_page == 'dashboard.php' ? 'bg-blue-800 font-semibold' : 'hover:bg-blue-700'; ?>">Dashboard</a>
            <a href="notices.php" class="block px-4 py-2 rounded <?php echo $current_page == 'notices.php' ? 'bg-blue-800 font-semibold' : 'hover:bg-blue-700'; ?>">Manage Notices</a>
            <a href="projects_admin.php" class="block px-4 py-2 rounded <?php echo $current_page == 'projects_admin.php' ? 'bg-blue-800 font-semibold' : 'hover:bg-blue-700'; ?>">Manage Projects</a>
            <a href="gallery_admin.php" class="block px-4 py-2 rounded <?php echo $current_page == 'gallery_admin.php' ? 'bg-blue-800 font-semibold' : 'hover:bg-blue-700'; ?>">Manage Gallery</a>
            <a href="../index.php" target="_blank" class="block px-4 py-2 rounded hover:bg-blue-700 mt-6 border border-blue-600 text-sm text-center">View Website ↗</a>
        </nav>
        <div class="p-4 border-t border-blue-800">
            <a href="logout.php" class="block px-4 py-2 bg-red-600 rounded text-center hover:bg-red-700 transition font-bold shadow">Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 min-w-0">
        <!-- Top bar -->
        <header class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800">
                <?php
                if($current_page == 'dashboard.php') echo 'Dashboard';
                elseif($current_page == 'notices.php') echo 'Notices';
                elseif($current_page == 'projects_admin.php') echo 'Projects';
                elseif($current_page == 'gallery_admin.php') echo 'Gallery';
                ?>
            </h1>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-cds-green text-white flex items-center justify-center font-bold">A</div>
                <span class="text-gray-700 font-medium">Admin</span>
            </div>
        </header>

        <!-- Flash Message Placeholder -->
        <?php if(isset($_SESSION['flash_message'])): ?>
            <div class="mb-6 px-4 py-3 rounded text-white font-medium shadow-sm <?php echo $_SESSION['flash_type'] === 'error' ? 'bg-red-500' : 'bg-green-500'; ?>">
                <?php 
                    echo e($_SESSION['flash_message']); 
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                ?>
            </div>
        <?php endif; ?>
