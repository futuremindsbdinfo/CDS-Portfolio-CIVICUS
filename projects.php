<?php
// projects.php
require_once 'includes/sanitize.php';

require_once 'includes/db.php';

$db = Database::getConnection();
$projects = [];
if ($db) {
    try {
        $projects = $db->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
    } catch (PDOException $e) {
        $projects = [];
    }
}

$page_title = "আমাদের প্রজেক্টস (Our Projects)";
$meta_description = "সমাজের উন্নয়নে আমাদের চলমান এবং পূর্ববর্তী কার্যক্রমগুলো সম্পর্কে বিস্তারিত জানুন।";
include 'includes/header.php';
?>

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
                    <!-- Cover Image / Video -->
                    <div class="relative">
                          <?php 
                              $has_video = !empty($project['video_embed']) || !empty($project['video_url']);
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
                        
                        <?php if (!empty($project['video_embed']) || !empty($project['video_url'])): ?>
                        <div class="mt-4 pt-4 border-t border-gray-100 space-y-4">
                            <?php if (!empty($project['video_embed'])): ?>
                            <div class="video-container rounded overflow-hidden aspect-video relative">
                                <?php echo $project['video_embed']; ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($project['video_url'])): ?>
                            <a href="<?php echo e($project['video_url']); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-full bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold py-2 px-4 rounded transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                                <span>
                                    <span data-lang="bn">ভিডিও দেখুন</span>
                                    <span data-lang="en" class="hidden">Watch Video</span>
                                </span>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
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

<style>
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 0.25rem;
    }
</style>

<?php include 'includes/footer.php'; ?>
