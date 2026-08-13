<?php 
$page_title = "প্রতিবেদন ও প্রকাশনা (Publications)";
$meta_description = "সিডিএস-এর বিভিন্ন প্রকাশনা, বার্ষিক প্রতিবেদন, গবেষণা, নিউজলেটার এবং গাইডলাইনসমূহ।";

include 'includes/header.php'; 
?>

<!-- Page Header -->
<div class="bg-gradient-to-br from-green-50 to-green-100 py-16 text-center border-b border-green-200">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4 text-slate-800">
            <span data-lang="bn">প্রতিবেদন ও <span class="text-primary">প্রকাশনা</span></span>
            <span data-lang="en" class="hidden">Publications & <span class="text-primary">Reports</span></span>
        </h1>
        <p class="text-slate-600 max-w-2xl mx-auto text-sm md:text-base">
            <span data-lang="bn">সিডিএস-এর বিভিন্ন কার্যক্রম, গবেষণা, বার্ষিক মূল্যায়ন এবং আর্থিক স্বচ্ছতার প্রতিবেদনসমূহ এখান থেকে দেখুন বা ডাউনলোড করুন।</span>
            <span data-lang="en" class="hidden">View or download CDS activities, research, annual evaluations, and financial transparency reports from here.</span>
        </p>
    </div>
</div>

<!-- Categories Section -->
<section class="py-16 bg-slate-50 min-h-[50vh]">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Category 1: Annual Reports -->
            <a href="#" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 hover:border-primary/30 transition-all overflow-hidden relative">
                <div class="h-2 w-full bg-slate-200 group-hover:bg-primary transition-colors"></div>
                <div class="p-8">
                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">
                        <span data-lang="bn">বার্ষিক প্রতিবেদন</span>
                        <span data-lang="en" class="hidden">Annual Reports</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-4">
                        <span data-lang="bn">প্রতি বছর আমাদের সংস্থা কী কী কাজ করেছে, তার একটি সার্বিক চিত্র ও সারসংক্ষেপ।</span>
                        <span data-lang="en" class="hidden">An overall picture and summary of what our organization has accomplished each year.</span>
                    </p>
                    <span class="inline-flex items-center text-primary font-semibold text-sm group-hover:underline">
                        <span data-lang="bn">ব্রাউজ করুন</span><span data-lang="en" class="hidden">Browse</span> 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>

            <!-- Category 2: Research Reports -->
            <a href="#" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 hover:border-blue-500/30 transition-all overflow-hidden relative">
                <div class="h-2 w-full bg-slate-200 group-hover:bg-blue-500 transition-colors"></div>
                <div class="p-8">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">
                        <span data-lang="bn">গবেষণা ও সার্ভে</span>
                        <span data-lang="en" class="hidden">Research & Surveys</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-4">
                        <span data-lang="bn">শিক্ষা, সুশাসন ও স্বাস্থ্য নিয়ে মাঠ পর্যায়ের গবেষণা ও পলিসি পেপারসমূহ।</span>
                        <span data-lang="en" class="hidden">Field-level research and policy papers on education, governance, and health.</span>
                    </p>
                    <span class="inline-flex items-center text-blue-600 font-semibold text-sm group-hover:underline">
                        <span data-lang="bn">ব্রাউজ করুন</span><span data-lang="en" class="hidden">Browse</span> 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>

            <!-- Category 3: Project Evaluations -->
            <a href="#" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 hover:border-orange-500/30 transition-all overflow-hidden relative">
                <div class="h-2 w-full bg-slate-200 group-hover:bg-orange-500 transition-colors"></div>
                <div class="p-8">
                    <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">
                        <span data-lang="bn">প্রকল্প মূল্যায়ন</span>
                        <span data-lang="en" class="hidden">Project Evaluations</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-4">
                        <span data-lang="bn">বিভিন্ন সম্পন্ন প্রজেক্টের প্রভাব, মূল্যায়ন, এবং শিক্ষণীয় বিষয়ের প্রতিবেদন।</span>
                        <span data-lang="en" class="hidden">Reports on the impact, evaluation, and learnings of various completed projects.</span>
                    </p>
                    <span class="inline-flex items-center text-orange-600 font-semibold text-sm group-hover:underline">
                        <span data-lang="bn">ব্রাউজ করুন</span><span data-lang="en" class="hidden">Browse</span> 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>

            <!-- Category 4: Newsletters -->
            <a href="#" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 hover:border-purple-500/30 transition-all overflow-hidden relative">
                <div class="h-2 w-full bg-slate-200 group-hover:bg-purple-500 transition-colors"></div>
                <div class="p-8">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">
                        <span data-lang="bn">নিউজলেটার ও বুলেটিন</span>
                        <span data-lang="en" class="hidden">Newsletters & Bulletins</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-4">
                        <span data-lang="bn">আমাদের নিয়মিত কার্যক্রম ও আপডেটের মাসিক বা ত্রৈমাসিক ই-নিউজলেটার।</span>
                        <span data-lang="en" class="hidden">Monthly or quarterly e-newsletters of our regular activities and updates.</span>
                    </p>
                    <span class="inline-flex items-center text-purple-600 font-semibold text-sm group-hover:underline">
                        <span data-lang="bn">ব্রাউজ করুন</span><span data-lang="en" class="hidden">Browse</span> 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>

            <!-- Category 5: Financial Reports -->
            <a href="#" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 hover:border-teal-500/30 transition-all overflow-hidden relative">
                <div class="h-2 w-full bg-slate-200 group-hover:bg-teal-500 transition-colors"></div>
                <div class="p-8">
                    <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">
                        <span data-lang="bn">অডিট ও আর্থিক প্রতিবেদন</span>
                        <span data-lang="en" class="hidden">Audit & Financial Reports</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-4">
                        <span data-lang="bn">আমাদের কাজের স্বচ্ছতা ও জবাবদিহিতা নিশ্চিত করার জন্য আর্থিক অডিট রিপোর্ট।</span>
                        <span data-lang="en" class="hidden">Financial audit reports to ensure transparency and accountability of our work.</span>
                    </p>
                    <span class="inline-flex items-center text-teal-600 font-semibold text-sm group-hover:underline">
                        <span data-lang="bn">ব্রাউজ করুন</span><span data-lang="en" class="hidden">Browse</span> 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>

            <!-- Category 6: Manuals & Guidelines -->
            <a href="#" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 hover:border-rose-500/30 transition-all overflow-hidden relative">
                <div class="h-2 w-full bg-slate-200 group-hover:bg-rose-500 transition-colors"></div>
                <div class="p-8">
                    <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">
                        <span data-lang="bn">ম্যানুয়াল ও গাইডলাইন</span>
                        <span data-lang="en" class="hidden">Manuals & Guidelines</span>
                    </h3>
                    <p class="text-slate-500 text-sm mb-4">
                        <span data-lang="bn">প্রাতিষ্ঠানিক পলিসি, চাইল্ড প্রোটেকশন গাইডলাইন এবং বিভিন্ন ট্রেনিং মডিউল।</span>
                        <span data-lang="en" class="hidden">Organizational policies, child protection guidelines and training modules.</span>
                    </p>
                    <span class="inline-flex items-center text-rose-600 font-semibold text-sm group-hover:underline">
                        <span data-lang="bn">ব্রাউজ করুন</span><span data-lang="en" class="hidden">Browse</span> 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </div>
            </a>
            
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
