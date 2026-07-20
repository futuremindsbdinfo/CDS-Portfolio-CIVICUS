<?php
// admin/dashboard.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/sanitize.php';

init_secure_session();
require_admin_login();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CDS Portfolio</title>
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Welcome to the Admin Panel</h2>
            <p class="text-gray-600">This is a secure placeholder page. Phase 5 will implement full CRUD operations for Notices, Projects, and Gallery here.</p>
        </div>
    </div>
</body>
</html>
