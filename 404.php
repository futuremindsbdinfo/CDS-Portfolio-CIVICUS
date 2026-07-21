<?php
// 404.php
http_response_code(404);
require_once __DIR__ . '/includes/header.php';
?>

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-8 text-center">
        <div class="flex justify-center">
            <!-- 404 SVG Illustration -->
            <svg class="h-48 w-48 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                <path d="M9 14s1.5 2 3 2 3-2 3-2"></path>
            </svg>
        </div>
        
        <div>
            <h1 class="mt-6 text-6xl font-extrabold text-slate-900 font-serif-bn tracking-tight">
                ৪<span class="text-primary">০</span>৪
            </h1>
            <h2 class="mt-4 text-3xl font-bold text-slate-800 font-serif-bn">
                পেজটি খুঁজে পাওয়া যায়নি
            </h2>
            <p class="mt-2 text-base text-slate-600 font-serif-bn">
                আপনি যে পেজটি খুঁজছেন তা মুছে ফেলা হয়েছে, নাম পরিবর্তন করা হয়েছে অথবা সাময়িকভাবে অনুপলব্ধ।
            </p>
        </div>
        
        <div class="mt-8 flex justify-center">
            <a href="/" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary hover:brightness-110 shadow-sm transition-all duration-200">
                <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                হোমপেজে ফিরে যান
            </a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
