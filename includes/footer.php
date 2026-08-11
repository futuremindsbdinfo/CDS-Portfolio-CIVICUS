<?php
// includes/footer.php
?>
    <footer id="contact" class="relative overflow-hidden bg-secondary text-white mt-auto">
        <div
          class="absolute inset-0 opacity-90"
          style="background: linear-gradient(135deg,#1e3a8a 0%,#1e40af 55%,#0f2a6b 100%);"
        ></div>
        <svg
          class="absolute inset-x-0 top-0 h-24 w-full text-background"
          viewBox="0 0 1440 100"
          preserveAspectRatio="none"
        >
          <path fill="currentColor" d="M0 0h1440v40c-240 60-480 60-720 30S240 20 0 60z" />
        </svg>
        <div class="relative mx-auto max-w-7xl px-4 pb-10 pt-32 sm:px-6 lg:px-8">
          <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-5">
            <div>
              <div class="flex items-center gap-3">
                <img src="assets/img/cds-logo.png" alt="CDS Logo" class="h-10 w-auto shrink-0 drop-shadow-sm">
                <div class="font-serif-bn text-lg font-bold">
                  <span data-lang="bn"><?php echo htmlspecialchars(get_setting('site_title', 'সিটিজেন ডেভেলপমেন্ট সোসাইটি')); ?></span>
                  <span data-lang="en" class="hidden">Citizen Development Society</span>
                </div>
              </div>
              <p class="mt-4 text-sm leading-relaxed text-white/80">
                <span data-lang="bn"><?php echo htmlspecialchars(get_setting('site_description', 'সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস) — সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক ও উন্নত বাংলাদেশ গড়ার লক্ষ্যে কাজ করা একটি অলাভজনক সংগঠন।')); ?></span>
                <span data-lang="en" class="hidden">Citizen Development Society (CDS) - A non-profit organization working towards Quality Education, good governance, Health and Well-being, Active Citizenships, and a Prosperous Bangladesh.</span>
              </p>
            </div>
            <div>
              <div class="font-serif-bn text-base font-bold">
                  <span data-lang="bn">দ্রুত লিংক</span><span data-lang="en" class="hidden">Quick Links</span>
              </div>
              <ul class="mt-4 space-y-2 text-sm text-white/85">
                  <li><a href="about.php" class="hover:text-white hover:underline"><span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">About Us</span></a></li>
                  <li><a href="index.php#programs" class="hover:text-white hover:underline"><span data-lang="bn">আমাদের কার্যক্রম</span><span data-lang="en" class="hidden">Our Programs</span></a></li>
                  <li><a href="projects.php" class="hover:text-white hover:underline"><span data-lang="bn">প্রজেক্টস</span><span data-lang="en" class="hidden">Projects</span></a></li>
                  <li><a href="gallery.php" class="hover:text-white hover:underline"><span data-lang="bn">গ্যালারি</span><span data-lang="en" class="hidden">Gallery</span></a></li>
                  <li><a href="notice.php" class="hover:text-white hover:underline"><span data-lang="bn">নোটিশ</span><span data-lang="en" class="hidden">Notice</span></a></li>
                  <li><a href="contact.php" class="hover:text-white hover:underline"><span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Contact</span></a></li>
              </ul>
            </div>
            <div>
              <div class="font-serif-bn text-base font-bold">
                  <span data-lang="bn">গুরুত্বপূর্ণ লিংক</span><span data-lang="en" class="hidden">Important Links</span>
              </div>
              <ul class="mt-4 space-y-2 text-sm text-white/85">
                  <li><a href="gov-links.php" class="hover:text-white hover:underline"><span data-lang="bn">সরকারি লিংক</span><span data-lang="en" class="hidden">Govt Links</span></a></li>
                  <li><a href="forms.php" class="hover:text-white hover:underline"><span data-lang="bn">আবেদন ফরমসমূহ</span><span data-lang="en" class="hidden">Application Forms</span></a></li>
                  <li><a href="blog.php" class="hover:text-white hover:underline"><span data-lang="bn">নিউজ ও ব্লগ</span><span data-lang="en" class="hidden">News & Blogs</span></a></li>
                  <li><a href="publications.php" class="hover:text-white hover:underline"><span data-lang="bn">প্রকাশনা ও ম্যাগাজিন</span><span data-lang="en" class="hidden">Publications</span></a></li>
              </ul>
            </div>
            <div>
              <div class="font-serif-bn text-base font-bold">
                  <span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Contact</span>
              </div>
              <ul class="mt-4 space-y-2 text-sm text-white/85">
                <li>
                    <span data-lang="bn"><?php echo nl2br(htmlspecialchars(get_setting('site_address', 'ঢাকা, বাংলাদেশ'))); ?></span>
                    <span data-lang="en" class="hidden"><?php echo nl2br(htmlspecialchars(get_setting('site_address_en', 'Dhaka, Bangladesh'))); ?></span>
                </li>
                <li><?php echo htmlspecialchars(get_setting('site_phone', '+880 1234-567890')); ?></li>
                <li><?php echo htmlspecialchars(get_setting('site_email', 'contact@cdsbangladesh.org')); ?></li>
              </ul>
              <a href="admin/login.php" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-white/70 hover:text-white">
                <span data-lang="bn">অ্যাডমিন প্যানেল</span><span data-lang="en" class="hidden">Admin Panel</span> &rarr;
              </a>
            </div>
            <div>
              <div class="font-serif-bn text-base font-bold">
                  <span data-lang="bn">সোশ্যাল মিডিয়া</span><span data-lang="en" class="hidden">Social Media</span>
              </div>
              <div class="mt-4 flex gap-3">
                  <?php if(get_setting('social_facebook')): ?>
                  <a href="<?php echo htmlspecialchars(get_setting('social_facebook')); ?>" target="_blank" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary">F</a>
                  <?php endif; ?>
                  
                  <?php if(get_setting('social_twitter')): ?>
                  <a href="<?php echo htmlspecialchars(get_setting('social_twitter')); ?>" target="_blank" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary">X</a>
                  <?php endif; ?>
                  
                  <?php if(get_setting('social_linkedin')): ?>
                  <a href="<?php echo htmlspecialchars(get_setting('social_linkedin')); ?>" target="_blank" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary">in</a>
                  <?php endif; ?>
                  
                  <?php if(get_setting('social_youtube')): ?>
                  <a href="<?php echo htmlspecialchars(get_setting('social_youtube')); ?>" target="_blank" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary">YT</a>
                  <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-white/15 pt-6 text-xs text-white/70 sm:flex-row">
            <div>
                <span data-lang="bn">&copy; <?php echo date('Y'); ?> সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)। সর্বস্বত্ব সংরক্ষিত।</span>
                <span data-lang="en" class="hidden">&copy; <?php echo date('Y'); ?> Citizen Development Society (CDS). All rights reserved.</span>
            </div>
            <div>Made with &hearts; by <a href="https://fuminds.com" target="_blank" rel="noopener noreferrer" class="hover:underline font-semibold text-white">Future Minds Academy</a></div>
          </div>
        </div>
      </footer>
      
    <!-- Vanilla JS Scripts -->
    <script src="assets/js/scripts.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/scripts.js'); ?>"></script>
</body>
</html>
