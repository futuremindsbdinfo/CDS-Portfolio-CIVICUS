<?php
// projects.php
require_once 'includes/sanitize.php';

// Dummy data structured exactly as `projects` table in schema.sql
$dummy_projects = [
    [
        'id' => 1,
        'title_bn' => 'বিনামূল্যে রক্তদান ক্যাম্পেইন',
        'title_en' => 'Free Blood Donation Campaign',
        'description_bn' => 'সমাজের সুবিধাবঞ্চিত মানুষদের জন্য বিনামূল্যে রক্তদান এবং ব্লাড গ্রুপিং কার্যক্রম। এই ক্যাম্পেইনে ৫০০ জনেরও বেশি মানুষ অংশগ্রহণ করেন।',
        'description_en' => 'Free blood donation and blood grouping activities for underprivileged people. Over 500 people participated in this campaign.',
        'status' => 'completed',
        'cover_image' => 'assets/img/projects/placeholder.jpg',
        'start_date' => '2023-05-10',
        'end_date' => '2023-05-15'
    ],
    [
        'id' => 2,
        'title_bn' => 'দেশব্যাপী বৃক্ষরোপণ কর্মসূচি',
        'title_en' => 'Nationwide Tree Plantation Program',
        'description_bn' => 'পরিবেশ রক্ষায় দেশব্যাপী ১০,০০০ গাছ লাগানোর উদ্যোগ নেওয়া হয়েছে, যা বর্তমানে চলমান।',
        'description_en' => 'An initiative to plant 10,000 trees nationwide to protect the environment, which is currently ongoing.',
        'status' => 'ongoing',
        'cover_image' => 'assets/img/projects/placeholder.jpg',
        'start_date' => '2023-08-01',
        'end_date' => null
    ],
    [
        'id' => 3,
        'title_bn' => 'অসহায় শিশুদের শিক্ষাসামগ্রী বিতরণ',
        'title_en' => 'Educational Materials Distribution to Helpless Children',
        'description_bn' => 'গ্রামের দরিদ্র পরিবারের ১০০ জন শিশুর মাঝে বিনামূল্যে বই, খাতা, ও কলম বিতরণ করা হয়েছে।',
        'description_en' => 'Free books, notebooks, and pens were distributed among 100 children from poor families in the village.',
        'status' => 'completed',
        'cover_image' => 'assets/img/projects/placeholder.jpg',
        'start_date' => '2024-01-05',
        'end_date' => '2024-01-10'
    ],
    [
        'id' => 4,
        'title_bn' => 'শীতবস্ত্র বিতরণ ২০২৪',
        'title_en' => 'Winter Clothes Distribution 2024',
        'description_bn' => 'উত্তরাঞ্চলের শীতার্ত মানুষের মাঝে ৫,০০০ কম্বল ও শীতের পোশাক বিতরণের মেগা প্রজেক্ট।',
        'description_en' => 'Mega project to distribute 5,000 blankets and winter clothes among cold-stricken people in the northern region.',
        'status' => 'completed',
        'cover_image' => 'assets/img/projects/placeholder.jpg',
        'start_date' => '2024-01-15',
        'end_date' => '2024-01-30'
    ],
    [
        'id' => 5,
        'title_bn' => 'তরুণ উদ্যোক্তা প্রশিক্ষণ',
        'title_en' => 'Youth Entrepreneurship Training',
        'description_bn' => 'বেকার যুবকদের স্বাবলম্বী করার জন্য আইটি ও ব্যবসায়িক প্রশিক্ষণ প্রদান করা হচ্ছে।',
        'description_en' => 'Providing IT and business training to unemployed youth to make them self-reliant.',
        'status' => 'ongoing',
        'cover_image' => 'assets/img/projects/placeholder.jpg',
        'start_date' => '2024-03-01',
        'end_date' => null
    ]
];
?>
<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div class="bg-cds-blue py-12 md:py-16 text-white text-center">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">
            <span data-lang="bn">আমাদের প্রজেক্টস</span>
            <span data-lang="en" class="hidden">Our Projects</span>
        </h1>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">
            <span data-lang="bn">সমাজের উন্নয়নে আমাদের চলমান এবং পূর্ববর্তী কার্যক্রমগুলো সম্পর্কে বিস্তারিত জানুন।</span>
            <span data-lang="en" class="hidden">Learn in detail about our ongoing and previous activities for social development.</span>
        </p>
    </div>
</div>

<!-- Projects Section -->
<section class="py-12 md:py-20 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        
        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button class="filter-btn bg-cds-green text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors" data-filter="all">
                <span data-lang="bn">সকল প্রজেক্ট</span>
                <span data-lang="en" class="hidden">All Projects</span>
            </button>
            <button class="filter-btn bg-white text-gray-700 border border-gray-300 font-bold py-2 px-6 rounded shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors" data-filter="ongoing">
                <span data-lang="bn">চলমান প্রজেক্ট</span>
                <span data-lang="en" class="hidden">Ongoing Projects</span>
            </button>
            <button class="filter-btn bg-white text-gray-700 border border-gray-300 font-bold py-2 px-6 rounded shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors" data-filter="completed">
                <span data-lang="bn">সম্পন্ন প্রজেক্ট</span>
                <span data-lang="en" class="hidden">Completed Projects</span>
            </button>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($dummy_projects as $project): ?>
                <div class="project-card bg-white rounded-lg shadow-sm hover:shadow-md border border-gray-200 overflow-hidden transition-all duration-300" data-status="<?php echo e($project['status']); ?>">
                    <!-- Cover Image -->
                    <div class="relative">
                        <img src="<?php echo e($project['cover_image']); ?>" alt="<?php echo e($project['title_bn']); ?> - Cover" class="w-full h-56 object-cover bg-gray-200" loading="lazy">
                        <!-- Status Badge -->
                        <?php if ($project['status'] === 'ongoing'): ?>
                            <span class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded shadow">
                                <span data-lang="bn">চলমান</span>
                                <span data-lang="en" class="hidden">Ongoing</span>
                            </span>
                        <?php else: ?>
                            <span class="absolute top-4 right-4 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded shadow">
                                <span data-lang="bn">সম্পন্ন</span>
                                <span data-lang="en" class="hidden">Completed</span>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-3">
                            <span data-lang="bn"><?php echo e($project['title_bn']); ?></span>
                            <span data-lang="en" class="hidden"><?php echo e($project['title_en']); ?></span>
                        </h2>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <span data-lang="bn"><?php echo e($project['description_bn']); ?></span>
                            <span data-lang="en" class="hidden"><?php echo e($project['description_en']); ?></span>
                        </p>
                        <div class="flex items-center text-sm text-gray-500 font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>
                                <?php 
                                    $start = $project['start_date'] ? date('d M, Y', strtotime($project['start_date'])) : 'N/A';
                                    echo e($start); 
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
