<?php 
$page_title = "সংবাদ ও অভিজ্ঞতা (News & Stories)";
$meta_description = "সিডিএস-এর সর্বশেষ সংবাদ, সফলতার গল্প, ব্লগ এবং প্রেস রিলিজ।";

include 'includes/header.php'; 
?>

<!-- Page Header -->
<div class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16 text-center border-b border-blue-200">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4 text-slate-800">
            <span data-lang="bn">সংবাদ ও <span class="text-blue-600">অভিজ্ঞতা</span></span>
            <span data-lang="en" class="hidden">News & <span class="text-blue-600">Stories</span></span>
        </h1>
        <p class="text-slate-600 max-w-2xl mx-auto text-sm md:text-base">
            <span data-lang="bn">সিডিএস-এর সর্বশেষ সংবাদ, সফলতার গল্প, সচেতনতামূলক ব্লগ এবং আমাদের কাজের বাস্তব অভিজ্ঞতাসমূহ।</span>
            <span data-lang="en" class="hidden">Latest news, success stories, awareness blogs, and real experiences from CDS's work.</span>
        </p>
    </div>
</div>

<!-- Main Content Section -->
<section class="py-16 bg-slate-50 min-h-[50vh]">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <!-- Filter/Tabs -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button class="px-5 py-2 rounded-full bg-blue-600 text-white text-sm font-semibold shadow-sm transition hover:bg-blue-700">
                <span data-lang="bn">সব ক্যাটাগরি</span><span data-lang="en" class="hidden">All Categories</span>
            </button>
            <button class="px-5 py-2 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-medium shadow-sm transition hover:border-blue-300 hover:text-blue-600">
                <span data-lang="bn">সংবাদ (News)</span><span data-lang="en" class="hidden">News</span>
            </button>
            <button class="px-5 py-2 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-medium shadow-sm transition hover:border-blue-300 hover:text-blue-600">
                <span data-lang="bn">ব্লগ (Blog)</span><span data-lang="en" class="hidden">Blog</span>
            </button>
            <button class="px-5 py-2 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-medium shadow-sm transition hover:border-blue-300 hover:text-blue-600">
                <span data-lang="bn">সফলতার গল্প (Stories)</span><span data-lang="en" class="hidden">Success Stories</span>
            </button>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Article Card 1 -->
            <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:border-blue-200 transition-all group flex flex-col">
                <div class="relative h-56 overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="News Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        <span data-lang="bn">সংবাদ</span><span data-lang="en" class="hidden">News</span>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center text-slate-400 text-xs font-medium mb-3 gap-4">
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 12 Aug, 2026</span>
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Admin</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                        <span data-lang="bn">সুবিধাবঞ্চিত শিশুদের জন্য নতুন শিক্ষা প্রকল্প উদ্বোধন</span>
                        <span data-lang="en" class="hidden">New Education Project Launched for Underprivileged Children</span>
                    </h3>
                    <p class="text-slate-600 text-sm mb-6 line-clamp-3">
                        <span data-lang="bn">আজ সিডিএস-এর উদ্যোগে নতুন একটি শিক্ষা প্রকল্প শুরু হয়েছে, যা দেশের সুবিধাবঞ্চিত শিশুদের আধুনিক শিক্ষার আলোয় আলোকিত করতে সাহায্য করবে।</span>
                        <span data-lang="en" class="hidden">Today CDS has launched a new education project which will help illuminate underprivileged children with modern education.</span>
                    </p>
                    <a href="#" class="mt-auto inline-flex items-center font-bold text-sm text-blue-600 hover:text-blue-800 transition-colors">
                        <span data-lang="bn">বিস্তারিত পড়ুন</span><span data-lang="en" class="hidden">Read More</span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </article>

            <!-- Article Card 2 -->
            <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:border-emerald-200 transition-all group flex flex-col">
                <div class="relative h-56 overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1593113565694-c7faa1451f2b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="News Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        <span data-lang="bn">সফলতার গল্প</span><span data-lang="en" class="hidden">Success Story</span>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center text-slate-400 text-xs font-medium mb-3 gap-4">
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 05 Aug, 2026</span>
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> PR Team</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-emerald-600 transition-colors line-clamp-2">
                        <span data-lang="bn">রহিমার ঘুরে দাঁড়ানোর গল্প: সিডিএস-এর ক্ষুদ্রঋণ প্রকল্পের প্রভাব</span>
                        <span data-lang="en" class="hidden">Rahima's Comeback: The Impact of CDS Microfinance</span>
                    </h3>
                    <p class="text-slate-600 text-sm mb-6 line-clamp-3">
                        <span data-lang="bn">মাত্র কয়েক বছর আগেও রহিমা ছিলেন নিঃস্ব। আজ সিডিএস-এর ক্ষুদ্রঋণ প্রকল্পের সহায়তায় তিনি একজন সফল উদ্যোক্তা।</span>
                        <span data-lang="en" class="hidden">Just a few years ago Rahima was destitute. Today, with the help of CDS's microfinance, she is a successful entrepreneur.</span>
                    </p>
                    <a href="#" class="mt-auto inline-flex items-center font-bold text-sm text-emerald-600 hover:text-emerald-800 transition-colors">
                        <span data-lang="bn">বিস্তারিত পড়ুন</span><span data-lang="en" class="hidden">Read More</span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </article>

            <!-- Article Card 3 -->
            <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:border-purple-200 transition-all group flex flex-col">
                <div class="relative h-56 overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1555448248-2571daf6344b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="News Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        <span data-lang="bn">ব্লগ</span><span data-lang="en" class="hidden">Blog</span>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center text-slate-400 text-xs font-medium mb-3 gap-4">
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 28 Jul, 2026</span>
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Researcher</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-purple-600 transition-colors line-clamp-2">
                        <span data-lang="bn">জলবায়ু পরিবর্তন ও গ্রামীণ নারী: সিডিএস-এর পর্যবেক্ষণ</span>
                        <span data-lang="en" class="hidden">Climate Change and Rural Women: Observations by CDS</span>
                    </h3>
                    <p class="text-slate-600 text-sm mb-6 line-clamp-3">
                        <span data-lang="bn">জলবায়ু পরিবর্তনের ফলে সবচেয়ে বেশি ক্ষতিগ্রস্ত হচ্ছেন গ্রামীণ প্রান্তিক নারীরা। এই বিষয়ে আমাদের সাম্প্রতিক গবেষণা কী বলছে তা জানুন।</span>
                        <span data-lang="en" class="hidden">Rural marginalized women are most affected by climate change. Find out what our recent research says about this.</span>
                    </p>
                    <a href="#" class="mt-auto inline-flex items-center font-bold text-sm text-purple-600 hover:text-purple-800 transition-colors">
                        <span data-lang="bn">বিস্তারিত পড়ুন</span><span data-lang="en" class="hidden">Read More</span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </article>

        </div>

        <!-- Pagination -->
        <div class="flex justify-center items-center gap-2 mt-12">
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-400 hover:border-blue-300 hover:text-blue-600 transition" disabled>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white font-medium shadow-sm">1</button>
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 font-medium hover:border-blue-300 hover:text-blue-600 transition">2</button>
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 font-medium hover:border-blue-300 hover:text-blue-600 transition">3</button>
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 hover:border-blue-300 hover:text-blue-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
