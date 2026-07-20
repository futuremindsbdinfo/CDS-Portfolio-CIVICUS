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
<body class="font-sans antialiased text-gray-800 bg-warm-white flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-cds-green to-green-800 text-white shadow-lg border-b border-green-900 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a href="index.php" class="flex items-center gap-2">
                <img src="assets/img/cds-logo.png" alt="CDS Logo" class="w-10 h-10 object-contain bg-white rounded-full">
                <div>
                    <h1 class="text-lg md:text-xl font-serif font-bold leading-tight">সিটিজেন ডেভেলপমেন্ট সোসাইটি</h1>
                    <p class="text-[10px] md:text-xs tracking-wider">Citizen Development Society</p>
                </div>
            </a>
            
            <!-- Desktop Nav -->
            <div class="hidden md:flex space-x-6 items-center">
                <a href="index.php" class="hover:text-yellow-300 transition-colors">হোম</a>
                <a href="about.php" class="hover:text-yellow-300 transition-colors">আমাদের সম্পর্কে</a>
                <a href="projects.php" class="hover:text-yellow-300 transition-colors">প্রজেক্টস</a>
                <a href="notice.php" class="hover:text-yellow-300 transition-colors">নোটিশ বোর্ড</a>
                <a href="gallery.php" class="hover:text-yellow-300 transition-colors">গ্যালারি</a>
                <a href="contact.php" class="hover:text-yellow-300 transition-colors">যোগাযোগ</a>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
                <a href="donation.php" class="bg-yellow-400 text-cds-blue font-bold px-3 py-1.5 md:px-4 md:py-2 text-sm md:text-base rounded shadow hover:bg-yellow-300 transition-colors whitespace-nowrap">ডোনেট করুন</a>
                
                <!-- Mobile Menu Toggle Button -->
                <button id="mobile-menu-toggle" class="md:hidden text-white focus:outline-none p-1 rounded hover:bg-green-700 transition-colors" aria-label="Toggle Mobile Menu">
                    <svg id="icon-menu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden md:hidden bg-green-800 border-t border-green-700">
            <div class="flex flex-col px-4 py-2">
                <a href="index.php" class="block py-3 border-b border-green-700 hover:text-yellow-300 transition-colors">হোম</a>
                <a href="about.php" class="block py-3 border-b border-green-700 hover:text-yellow-300 transition-colors">আমাদের সম্পর্কে</a>
                <a href="projects.php" class="block py-3 border-b border-green-700 hover:text-yellow-300 transition-colors">প্রজেক্টস</a>
                <a href="notice.php" class="block py-3 border-b border-green-700 hover:text-yellow-300 transition-colors">নোটিশ বোর্ড</a>
                <a href="gallery.php" class="block py-3 border-b border-green-700 hover:text-yellow-300 transition-colors">গ্যালারি</a>
                <a href="contact.php" class="block py-3 hover:text-yellow-300 transition-colors">যোগাযোগ</a>
            </div>
        </div>
    </nav>
    <!-- Main Content wrapper -->
    <main class="flex-grow">
