<?php
// admin/dashboard.php
require_once __DIR__ . '/includes/header.php';

// Fetch quick stats
$db = get_db_connection();
$stats = [
    'notices' => 0,
    'projects' => 0,
    'gallery' => 0
];

if ($db) {
    $stats['notices'] = $db->query("SELECT COUNT(*) FROM notices")->fetchColumn() ?: 0;
    $stats['projects'] = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn() ?: 0;
    $stats['gallery'] = $db->query("SELECT COUNT(*) FROM gallery")->fetchColumn() ?: 0;
}
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Notices Stat -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-yellow-500 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 uppercase font-semibold">Total Notices</p>
            <p class="text-3xl font-bold text-gray-800"><?php echo $stats['notices']; ?></p>
        </div>
        <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center text-yellow-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
        </div>
    </div>

    <!-- Projects Stat -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-cds-green flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 uppercase font-semibold">Total Projects</p>
            <p class="text-3xl font-bold text-gray-800"><?php echo $stats['projects']; ?></p>
        </div>
        <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-cds-green">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
    </div>

    <!-- Gallery Stat -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-cds-blue flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 uppercase font-semibold">Gallery Photos</p>
            <p class="text-3xl font-bold text-gray-800"><?php echo $stats['gallery']; ?></p>
        </div>
        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-cds-blue">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
    </div>

</div>

<div class="bg-white p-6 rounded-lg shadow-sm">
    <h2 class="text-xl font-bold mb-4 border-b pb-2">Quick Actions</h2>
    <div class="flex gap-4">
        <a href="notices.php" class="bg-cds-blue text-white px-4 py-2 rounded hover:bg-blue-800 font-medium">Add New Notice</a>
        <a href="projects_admin.php" class="bg-cds-green text-white px-4 py-2 rounded hover:bg-green-700 font-medium">Add New Project</a>
        <a href="gallery_admin.php" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 font-medium">Upload Photo</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
