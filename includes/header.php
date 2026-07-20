<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Development Society (CDS)</title>
    <!-- Google Fonts for Bengali -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;700&display=swap" rel="stylesheet">
    <!-- Compiled Tailwind CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="font-sans antialiased text-gray-800 bg-cds-bg flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-cds-green text-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2">
                <img src="assets/img/cds-logo.png" alt="CDS Logo" class="w-10 h-10 object-contain bg-white rounded-full">
                <div>
                    <h1 class="text-xl font-serif font-bold leading-tight">সিটিজেন ডেভেলপমেন্ট সোসাইটি</h1>
                    <p class="text-xs tracking-wider">Citizen Development Society</p>
                </div>
            </a>
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="hover:text-yellow-300 transition-colors">হোম</a>
                <a href="about.php" class="hover:text-yellow-300 transition-colors">আমাদের সম্পর্কে</a>
                <a href="projects.php" class="hover:text-yellow-300 transition-colors">প্রজেক্টস</a>
                <a href="notice.php" class="hover:text-yellow-300 transition-colors">নোটিশ বোর্ড</a>
                <a href="gallery.php" class="hover:text-yellow-300 transition-colors">গ্যালারি</a>
                <a href="contact.php" class="hover:text-yellow-300 transition-colors">যোগাযোগ</a>
            </div>
            <div>
                <a href="donation.php" class="bg-yellow-400 text-cds-blue font-bold px-4 py-2 rounded shadow hover:bg-yellow-300 transition-colors">ডোনেট করুন</a>
            </div>
        </div>
    </nav>
    <!-- Main Content wrapper -->
    <main class="flex-grow">
