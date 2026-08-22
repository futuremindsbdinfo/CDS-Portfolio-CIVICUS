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
                <img src="/assets/img/cds-logo.png" alt="CDS Logo" class="h-10 w-auto shrink-0 drop-shadow-sm">
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
                  <li><a href="news-and-stories.php" class="hover:text-white hover:underline"><span data-lang="bn">সংবাদ ও গল্প</span><span data-lang="en" class="hidden">News & Stories</span></a></li>
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
                  <?php $fb_url = get_setting('social_facebook', 'https://www.facebook.com/citizendevelopmentsociety'); ?>
                  <?php if(!empty($fb_url)): ?>
                  <a href="<?php echo htmlspecialchars($fb_url); ?>" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-[#1877F2] hover:scale-110" title="Facebook" aria-label="Facebook">
                      <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                  </a>
                  <?php endif; ?>
                  
                  <?php if(get_setting('social_youtube')): ?>
                  <a href="<?php echo htmlspecialchars(get_setting('social_youtube')); ?>" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-[#FF0000] hover:scale-110" title="YouTube" aria-label="YouTube">
                      <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                  </a>
                  <?php endif; ?>

                  <?php if(get_setting('social_linkedin')): ?>
                  <a href="<?php echo htmlspecialchars(get_setting('social_linkedin')); ?>" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-[#0A66C2] hover:scale-110" title="LinkedIn" aria-label="LinkedIn">
                      <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 8.76a1.64 1.64 0 1 0-.02-3.28 1.64 1.64 0 0 0 .02 3.28M5.07 18.5h2.78v-8.37H5.07v8.37z"/></svg>
                  </a>
                  <?php endif; ?>

                  <?php if(get_setting('social_twitter')): ?>
                  <a href="<?php echo htmlspecialchars(get_setting('social_twitter')); ?>" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-[#000000] hover:scale-110" title="X (Twitter)" aria-label="X (Twitter)">
                      <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                  </a>
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
    <script src="/assets/js/scripts.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/scripts.js'); ?>"></script>
</body>
</html>
