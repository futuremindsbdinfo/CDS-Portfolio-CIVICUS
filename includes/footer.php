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
                <div class="font-serif-bn text-lg font-bold">সিটিজেন ডেভেলপমেন্ট সোসাইটি</div>
              </div>
              <p class="mt-4 text-sm leading-relaxed text-white/80">
                সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস) — সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক ও উন্নত বাংলাদেশ গড়ার
                লক্ষ্যে কাজ করা একটি অলাভজনক সংগঠন।
              </p>
            </div>
            <div>
              <div class="font-serif-bn text-base font-bold">দ্রুত লিংক</div>
              <ul class="mt-4 space-y-2 text-sm text-white/85">
                  <li><a href="/about.php" class="hover:text-white hover:underline">আমাদের সম্পর্কে</a></li>
                  <li><a href="/index.php#programs" class="hover:text-white hover:underline">আমাদের কার্যক্রম</a></li>
                  <li><a href="/projects.php" class="hover:text-white hover:underline">প্রজেক্টস</a></li>
                  <li><a href="/gallery.php" class="hover:text-white hover:underline">গ্যালারি</a></li>
                  <li><a href="/notice.php" class="hover:text-white hover:underline">নোটিশ</a></li>
                  <li><a href="/index.php#contact" class="hover:text-white hover:underline">যোগাযোগ</a></li>
              </ul>
            </div>
            <div>
              <div class="font-serif-bn text-base font-bold">গুরুত্বপূর্ণ লিংক</div>
              <ul class="mt-4 space-y-2 text-sm text-white/85">
                  <li><a href="/gov-links.php" class="hover:text-white hover:underline">সরকারি লিংক</a></li>
                  <li><a href="/forms.php" class="hover:text-white hover:underline">আবেদন ফরমসমূহ</a></li>
                  <li><a href="/blog.php" class="hover:text-white hover:underline">নিউজ ও ব্লগ</a></li>
                  <li><a href="/publications.php" class="hover:text-white hover:underline">প্রকাশনা ও ম্যাগাজিন</a></li>
              </ul>
            </div>
            <div>
              <div class="font-serif-bn text-base font-bold">যোগাযোগ</div>
              <ul class="mt-4 space-y-2 text-sm text-white/85">
                <li><strong class="text-white">প্রধান কার্যালয়:</strong> কাকরাইল, ঢাকা</li>
                <li><strong class="text-white">কর্ম এলাকা:</strong> নাঙ্গলকোট ও লালমাই, কুমিল্লা</li>
                <li>+৮৮০ ১৭০০-০০০০০০</li>
                <li>info@cds-bd.org</li>
              </ul>
              <a href="/admin/login.php" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-white/70 hover:text-white">
                অ্যাডমিন প্যানেল &rarr;
              </a>
            </div>
            <div>
              <div class="font-serif-bn text-base font-bold">সোশ্যাল মিডিয়া</div>
              <div class="mt-4 flex gap-3">
                  <a href="#" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary">F</a>
                  <a href="#" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary">X</a>
                  <a href="#" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary">in</a>
                  <a href="#" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary">IG</a>
              </div>
            </div>
          </div>
          <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-white/15 pt-6 text-xs text-white/70 sm:flex-row">
            <div>&copy; <?php echo date('Y'); ?> সিটিজেন ডেভেলপমেন্ট সোসাইটি (সিডিএস)। সর্বস্বত্ব সংরক্ষিত।</div>
            <div>Made with &hearts; by <a href="https://fuminds.com" target="_blank" rel="noopener noreferrer" class="hover:underline font-semibold text-white">Future Minds Academy</a></div>
          </div>
        </div>
      </footer>
      
    <!-- Vanilla JS Scripts -->
    <script src="/assets/js/scripts.js"></script>
</body>
</html>
