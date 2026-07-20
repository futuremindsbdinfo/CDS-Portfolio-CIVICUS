<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="bg-cds-bg py-16 md:py-24 border-b border-gray-200">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-cds-blue mb-6 leading-tight">
            <span data-lang="bn">স্বাগতম সিটিজেন ডেভেলপমেন্ট সোসাইটি (CDS)-তে</span>
            <span data-lang="en" class="hidden">Welcome to Citizen Development Society (CDS)</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-700 mb-10 max-w-3xl mx-auto leading-relaxed">
            <span data-lang="bn">সুশিক্ষা, সুস্বাস্থ্য, সুনাগরিক এবং সুশাসন নিশ্চিতে কাজ করে যাচ্ছে CDS। আসুন, একটি সুন্দর সমাজ বিনির্মাণে আমরা একসাথে কাজ করি।</span>
            <span data-lang="en" class="hidden">CDS is working to ensure good education, health, citizenship, and governance. Let's work together to build a beautiful society.</span>
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="about.php" class="bg-cds-green text-white font-bold px-8 py-3 rounded-md shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all">
                <span data-lang="bn">আমাদের সম্পর্কে জানুন</span>
                <span data-lang="en" class="hidden">Learn About Us</span>
            </a>
            <a href="donation.php" class="bg-yellow-400 text-cds-blue font-bold px-8 py-3 rounded-md shadow-md hover:bg-yellow-300 focus:outline-none focus:ring-4 focus:ring-yellow-200 transition-all">
                <span data-lang="bn">ডোনেট করুন</span>
                <span data-lang="en" class="hidden">Donate Now</span>
            </a>
        </div>
    </div>
</section>

<!-- About Preview Section -->
<section class="py-16 md:py-24 bg-white">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center gap-10">
        <div class="md:w-1/2">
            <!-- Placeholder for a related image or illustration in the future -->
            <div class="bg-gray-200 aspect-video rounded-lg flex items-center justify-center shadow-inner">
                <span class="text-gray-400 font-semibold" data-lang="bn">ছবি/ভিডিও প্লেসহোল্ডার</span>
                <span class="text-gray-400 font-semibold hidden" data-lang="en">Image/Video Placeholder</span>
            </div>
        </div>
        <div class="md:w-1/2">
            <h2 class="text-sm font-bold text-cds-green tracking-wider uppercase mb-2">
                <span data-lang="bn">আমাদের পরিচিতি</span>
                <span data-lang="en" class="hidden">Who We Are</span>
            </h2>
            <h3 class="text-3xl font-serif font-bold text-cds-blue mb-4">
                <span data-lang="bn">এক নজরে CDS</span>
                <span data-lang="en" class="hidden">CDS at a Glance</span>
            </h3>
            <p class="text-gray-700 mb-6 leading-relaxed">
                <span data-lang="bn">সিটিজেন ডেভেলপমেন্ট সোসাইটি (CDS) একটি অলাভজনক ও স্বেচ্ছাসেবী প্রতিষ্ঠান যা সমাজে সুশিক্ষা, সুস্বাস্থ্য, এবং সুশাসনের আলো ছড়িয়ে দিতে বদ্ধপরিকর। আমাদের মূল লক্ষ্য হলো এমন এক সমাজ গঠন করা যেখানে প্রতিটি নাগরিক তার অধিকার ও দায়িত্ব সম্পর্কে সচেতন থাকবে।</span>
                <span data-lang="en" class="hidden">Citizen Development Society (CDS) is a non-profit voluntary organization dedicated to spreading the light of good education, health, and governance. Our main goal is to build a society where every citizen is aware of their rights and responsibilities.</span>
            </p>
            <a href="about.php" class="text-cds-green font-bold hover:text-green-800 flex items-center gap-2 group transition-colors focus:outline-none focus:underline">
                <span data-lang="bn">আরও পড়ুন</span>
                <span data-lang="en" class="hidden">Read More</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</section>

<!-- Shortcut Links Section -->
<section class="py-16 md:py-24 bg-gray-50 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-serif font-bold text-center text-cds-blue mb-12">
            <span data-lang="bn">গুরুত্বপূর্ণ লিংকসমূহ</span>
            <span data-lang="en" class="hidden">Important Links</span>
        </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Projects Card -->
            <a href="projects.php" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md border border-gray-100 hover:border-cds-green transition-all group focus:outline-none focus:ring-2 focus:ring-cds-green">
                <div class="w-12 h-12 bg-green-50 text-cds-green rounded-full flex items-center justify-center mb-4 group-hover:bg-cds-green group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    <span data-lang="bn">আমাদের প্রজেক্টস</span>
                    <span data-lang="en" class="hidden">Our Projects</span>
                </h3>
                <p class="text-gray-600 text-sm">
                    <span data-lang="bn">আমাদের চলমান এবং পূর্ববর্তী কার্যক্রমগুলো সম্পর্কে বিস্তারিত জানুন।</span>
                    <span data-lang="en" class="hidden">Learn in detail about our ongoing and past activities.</span>
                </p>
            </a>
            
            <!-- Gallery Card -->
            <a href="gallery.php" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md border border-gray-100 hover:border-cds-blue transition-all group focus:outline-none focus:ring-2 focus:ring-cds-blue">
                <div class="w-12 h-12 bg-blue-50 text-cds-blue rounded-full flex items-center justify-center mb-4 group-hover:bg-cds-blue group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    <span data-lang="bn">ফটো গ্যালারি</span>
                    <span data-lang="en" class="hidden">Photo Gallery</span>
                </h3>
                <p class="text-gray-600 text-sm">
                    <span data-lang="bn">আমাদের বিভিন্ন ইভেন্ট ও কাজের সুন্দর মুহূর্তগুলোর ছবি দেখুন।</span>
                    <span data-lang="en" class="hidden">See photos of beautiful moments from our events and work.</span>
                </p>
            </a>

            <!-- Notice Board Card -->
            <a href="notice.php" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md border border-gray-100 hover:border-yellow-500 transition-all group focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    <span data-lang="bn">নোটিশ বোর্ড</span>
                    <span data-lang="en" class="hidden">Notice Board</span>
                </h3>
                <p class="text-gray-600 text-sm">
                    <span data-lang="bn">সোসাইটির লেটেস্ট আপডেট, ঘোষণা এবং ইভেন্টের নোটিশ জানুন।</span>
                    <span data-lang="en" class="hidden">Get the latest updates, announcements, and event notices.</span>
                </p>
            </a>

            <!-- Contact Card -->
            <a href="contact.php" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md border border-gray-100 hover:border-gray-500 transition-all group focus:outline-none focus:ring-2 focus:ring-gray-500">
                <div class="w-12 h-12 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    <span data-lang="bn">যোগাযোগ করুন</span>
                    <span data-lang="en" class="hidden">Contact Us</span>
                </h3>
                <p class="text-gray-600 text-sm">
                    <span data-lang="bn">আমাদের সাথে যোগাযোগ করতে বা আপনার মতামত জানাতে ফর্মটি পূরণ করুন।</span>
                    <span data-lang="en" class="hidden">Fill out the form to contact us or share your feedback.</span>
                </p>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
