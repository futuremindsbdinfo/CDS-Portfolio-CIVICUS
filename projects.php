<?php
// projects.php
require_once 'includes/sanitize.php';

require_once 'includes/db.php';

$db = Database::getConnection();
$projects = [];
if ($db) {
    $projects = $db->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
}
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
            <?php foreach ($projects as $project): ?>
                <div class="project-card bg-white rounded-lg shadow-sm hover:shadow-md border border-gray-200 overflow-hidden transition-all duration-300" data-status="<?php echo e($project['status']); ?>">
                    <!-- Cover Image -->
                    <div class="relative">
                          <?php 
                              $img_path = 'uploads/projects/' . $project['cover_image'];
                              $has_image = !empty($project['cover_image']) && file_exists(__DIR__ . '/' . $img_path);
                          ?>
                          <?php if ($has_image): ?>
                              <img src="<?php echo e($img_path); ?>" alt="<?php echo e($project['title_bn']); ?> - Cover" class="w-full h-56 object-cover bg-gray-200" loading="lazy">
                          <?php else: ?>
                              <div class="w-full h-56 bg-brand-gradient relative overflow-hidden">
                                <svg class="absolute inset-0 h-full w-full opacity-30" viewBox="0 0 400 250">
                                  <circle cx="80" cy="60" r="80" fill="white" />
                                  <circle cx="320" cy="200" r="120" fill="white" />
                                </svg>
                              </div>
                          <?php endif; ?>
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
            <?php if(empty($projects)): ?>
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center p-8 bg-white rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium">কোনো প্রজেক্ট পাওয়া যায়নি। / No projects found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
