# memory.md - CDS Portfolio (Project Memory)

## এ পর্যন্ত যা যা সম্পন্ন হয়েছে (What has been completed)
- প্রজেক্টের রিকোয়ারমেন্ট এবং টেক স্ট্যাক (HTML/CSS/JS, Tailwind, MySQL) চূড়ান্ত করা হয়েছে।
- PRD, Architecture, Rules, Phases, এবং Design এর ডকুমেন্টেশন প্রপারলি তৈরি করা হয়েছে।
- **ব্যাকএন্ড ডিসিশন চূড়ান্ত:** PHP (PDO + prepared statements) ব্যবহার হবে, cPanel-friendly shared hosting-এর জন্য।
- **অ্যাডমিন প্যানেল কনফার্ম:** Notice, Projects, Gallery — এই তিনটা কন্টেন্ট টাইপ session-login সহ `/admin` প্যানেল থেকে CRUD করা যাবে।
- **ডোনেশন পেজ স্কোপ কনফার্ম:** শুধু তথ্যমূলক (bKash/Bank details) + একটা "ডোনেশন ইন্টারেস্ট" ফর্ম — কোনো অনলাইন পেমেন্ট গেটওয়ে/কার্ড ডাটা নেই।
- **`.ai/schema.sql` তৈরি হয়েছে** — 7টা টেবিল: `admins`, `login_attempts`, `notices`, `projects`, `gallery`, `contact_messages`, `donation_interests`। সিকিউরিটি বিবেচনা (password hashing, brute-force protection, random filename, IP-based rate limiting) স্কিমাতে বিল্ট-ইন।
- **Architecture.md আপডেট হয়েছে** — নতুন ফোল্ডার স্ট্রাকচার (`config/`, `includes/`, `admin/`, `uploads/`) এবং ডাটাবেস সামারি টেবিল যোগ করা হয়েছে।
- **Phase 1 সম্পূর্ণ** — frontend base (Tailwind, header/footer, index.php) এবং সিকিউরিটি scaffolding (db.php, csrf.php, auth.php, sanitize.php, admin panel with rate-limiting, .htaccess protections) দুটোই Antigravity দিয়ে তৈরি হয়েছে।
- **Phase 2 সম্পূর্ণ** — `index.php`-তে Hero সেকশন, About প্রিভিউ, ৪টা শর্টকাট কার্ড (inline SVG আইকন সহ) যোগ হয়েছে। `about.php` তৈরি হয়েছে (History, Mission/Vision, Team সেকশন)। ডামি কন্টেন্ট ব্যবহৃত হয়েছে: টিম সেকশনে ৪ জনের নাম/পদবি ও `assets/img/team/placeholder.jpg` পাথ (এখনো real ইমেজ নেই), হোমপেজে video/image placeholder বক্স।
- ⚠️ মনে রাখতে হবে: `assets/img/team/` ফোল্ডারে এখনো আসল ছবি নেই — Phase 6-এর আগে এটা রিপ্লেস করতে হবে।
- **Phase 3 সম্পূর্ণ** — `projects.php` (dummy data, filter tabs) ও `gallery.php` (dummy data, responsive grid + lightbox) তৈরি হয়েছে। JS আলাদা `assets/js/scripts.js`-এ (footer.php থেকে include)। dummy data structure schema.sql কলামের সাথে confirm মিলেছে।
- **Phase 4 সম্পূর্ণ** — `notice.php` (dummy data দিয়ে শুরু, পরে DB-connected) এবং `donation.php` (bKash/Bank/Nagad/Rocket details + ডোনেশন-ইন্টারেস্ট ফর্ম, transaction ID ফিল্ড সহ) তৈরি হয়েছে।
- **Phase 5 সম্পূর্ণ (backend/DB integration)** — `notice.php`, `projects.php`, `gallery.php` থেকে dummy data সরিয়ে PDO দিয়ে আসল DB query বসানো হয়েছে। Admin dashboard-এ `notices.php`, `projects_admin.php`, `gallery_admin.php` (CRUD পেজ) তৈরি হয়েছে।
- **অতিরিক্ত কাজ:** CDS-এর original logo (CDS Membership Form প্রজেক্ট থেকে কপি) header/footer-এ যোগ করা হয়েছে। কোড GitHub-এ পুশ হয়েছে (`https://github.com/futuremindsbdinfo/CDS-Portfolio.git`)।
- **Design redesign (index.php + header/footer)** — generic Tailwind-template লুক থেকে বের করে CDS-নির্দিষ্ট identity আনার জন্য redesign করা হয়েছে (warm off-white background #FAF8F3, header/footer gradient depth, ৪-স্তম্ভ signature ribbon, redesigned link cards)। প্রথম পাসে ৪টা bug এসেছিল (header background সাদা দেখাচ্ছিল, hero-র decorative SVG ভয়ংকর দেখাচ্ছিল, link card আইকন overflow করছিল, footer text contrast কম ছিল) — মূল কারণ ছিল `npm run build:css` রান না করা এবং oversized/raw SVG shape। সব কটা ফিক্স করা হয়েছে।
- ⚠️ **শেখা শিক্ষা:** Tailwind arbitrary value ক্লাস (`bg-[#hex]` বা নতুন কালার) পরিবর্তন করলে প্রতিবার `npm run build:css` রান করা আবশ্যক, নাহলে পুরনো compiled CSS-ই থেকে যায় এবং ডিজাইন ভাঙা দেখায় — Antigravity কে ভবিষ্যতেও এটা মনে করিয়ে দিতে হতে পারে।
- **Mobile responsiveness bug fixes সম্পন্ন** — hamburger menu (critical, নেভিগেশন আগে মোবাইলে সম্পূর্ণ অদৃশ্য ছিল) যোগ হয়েছে vanilla JS দিয়ে (Escape key support সহ), donate বাটন single-line ফিক্স, hero-র ফাঁকা decorative box মোবাইলে hide করে ট্যাগলাইন normal flow-এ বসানো হয়েছে, link card আইকনের visibility/color ফিক্স হয়েছে। 375px viewport-এ ভেরিফাই করা হয়েছে।
- ⚠️ **প্রজেক্ট ফোল্ডার নাম পরিবর্তন:** `docs/` এখন `.ai/` — ভবিষ্যতের সব prompt-এ `.ai/schema.sql`, `.ai/design.md` ইত্যাদি রেফার করতে হবে।
- **Phase 6 সম্পূর্ণ (production hardening)** — root `.htaccess` (HTTPS redirect conditional on APP_ENV, error display off), `config/database.php`-এ APP_ENV-ভিত্তিক error reporting, `.ai/schema.sql` থেকে default admin INSERT সরানো হয়েছে, `scripts/create_admin.php` (secure CLI script) ও `scripts/backup_db.php` তৈরি হয়েছে, `logs/backups/.htaccess` + `.gitignore` আপডেট, `.ai/pending-content.md` (placeholder audit) তৈরি হয়েছে।
- **Secure Image Upload Feature সম্পূর্ণ** — `includes/upload_handler.php` (MIME verification via finfo, extension whitelist, double-extension protection, dimension check 100x100-4000x4000, GD re-encode + resize max 1920x1080 @ quality 85, random 16-byte filename) তৈরি হয়েছে এবং `projects_admin.php`/`gallery_admin.php`-এ integrate করা হয়েছে (upload + delete দুটোই), `projects.php`/`gallery.php` frontend-এ fallback placeholder সহ দেখানো হচ্ছে।

## ⚠️ সিকিউরিটি ইনসিডেন্ট লগ (গুরুত্বপূর্ণ)
- **[CRITICAL, PENDING]** Admin অ্যাকাউন্ট ক্রেডেনশিয়াল `admin/admin123` — Antigravity একবার ভুলবশত এটা আবার রিসেট করেছিল (hash mismatch ফিক্স করতে গিয়ে)। **এখনো strong password-এ বদলানো হয়নি** — Ashik-কে ম্যানুয়ালি `password_hash()` + SQL UPDATE দিয়ে (AI agent দিয়ে না) নিজে করতে বলা হয়েছে।
- **[RESOLVED]** GitHub রিপো (`futuremindsbdinfo/CDS-Portfolio`) আগে public ছিল, এখন **private করা হয়েছে**।
- **[RESOLVED]** `git log --all --full-history -- .env` খালি আউটপুট দিয়েছে — `.env` কখনো git history-তে কমিট হয়নি, leak নেই।
- **[CRITICAL, DEFERRED — লঞ্চের আগে অবশ্যই ফিক্স করতে হবে]** Admin password এখন `Admin@123` — এটা এখনো একটা predictable/common pattern (Capital+Word+123), সত্যিকারের random strong password না। ইউজার সিদ্ধান্ত নিয়েছে এটা এখন না বদলে Phase 6-এর পর বদলাবে।
- **[MEDIUM, DEFERRED — লঞ্চের আগে অবশ্যই ফিক্স করতে হবে]** `.ai/schema.sql`-এ এখন actual admin password hash হার্ডকোড করা আছে (INSERT স্টেটমেন্ট আকারে) — এটা schema ফাইল থেকে সরিয়ে ফেলা উচিত, কারণ এটা বারবার git-এ কমিট হচ্ছে।
- **[FIXED]** `login_attempts` টেবিলে কলাম নাম মিসম্যাচ ছিল (কোড `username` খুঁজছিল, টেবিলে অন্য নাম ছিল) — টেবিল রিক্রিয়েট করে এবং `.ai/schema.sql` আপডেট করে ফিক্স করা হয়েছে। ⚠️ পরে ভেরিফাই করতে হবে .ai/schema.sql-এর কলাম নাম আসল DB-এর সাথে ঠিক মিলছে কিনা।
- **[FIXED]** CSRF "session must be started" এরর — `init_secure_session()` কল অর্ডার ঠিক করে ফিক্স করা হয়েছে।
- **[FIXED]** লোকাল XAMPP session write permission সমস্যা — session ফাইল প্রজেক্টের `logs/sessions/`-এ সরানো হয়েছে (এই ফোল্ডার `logs/.htaccess` দিয়ে direct HTTP অ্যাক্সেস থেকে protected, তাই এটা নিরাপদ, কিন্তু ফোল্ডারটা আসলেই ফাইল সিস্টেমে raw session data লিক করছে্বা না তা একবার কনফার্ম করা ভালো)।
- XAMPP MySQL কয়েকবার crash করেছিল (data ফোল্ডার করাপশন) — backup ফোল্ডার থেকে fresh data ফোল্ডার বসিয়ে সমাধান হয়েছে, cds_portfolio ডাটাবেস নতুন করে schema.sql দিয়ে ইমপোর্ট করা হয়েছে।

## বর্তমানে কোন ফাইলে কাজ চলছে (Which file is currently being worked)
- **সব কোডিং কাজ সম্পন্ন** (Phase 1-6 + Security scaffolding + Image upload feature)। এখন শুধু ম্যানুয়াল pre-launch কাজ বাকি:
  1. **Admin password `Admin@123` থেকে সত্যিকারের random strong password-এ বদলানো** — `scripts/create_admin.php` দিয়ে নতুন secure admin বানিয়ে পুরনো weak-password admin ডিলিট করা যেতে পারে
  2. Responsive/browser টেস্টিং (ম্যানুয়াল, বিভিন্ন ডিভাইস/ব্রাউজারে)
  3. Real content দিয়ে `.ai/pending-content.md`-এর সব placeholder রিপ্লেস করা (এখন admin panel থেকে সরাসরি secure upload দিয়ে করা যাবে)
  4. Actual hosting-এ deploy + SSL install + cPanel-এ cronjob দিয়ে `scripts/backup_db.php` শিডিউল করা

> **সতর্কতা:** কাজের সাথে সাথে এই ফাইলটি নিয়মিত আপডেট করতে হবে (UPDATE IT REGULARLY)।

- **[FIXED/IMPLEMENTED]** contact.php তৈরি করা হয়েছে (CSRF + IP rate limiting সহ)। donation.php-তে আসল CSRF টোকেন এবং IP rate limiting + DB insert লজিক যোগ করা হয়েছে।

> **সতর্কতা:** কাজের সাথে সাথে এই ফাইলটি নিয়মিত আপডেট করতে হবে (UPDATE IT REGULARLY)。

- **[FIXED/IMPLEMENTED]** contact.php তৈরি করা হয়েছে (CSRF + IP rate limiting সহ)। donation.php-তে আসল CSRF টোকেন এবং IP rate limiting + DB insert লজিক যোগ করা হয়েছে।

- **[FIXED]** admin/dashboard.php সহ মোট ৭টি ফাইলে 'Call to undefined function get_db_connection()' error দেখা দিচ্ছিল। Root cause: আগের একটি commit-এ Antigravity agent ভুল করে Database::getConnection()-এর বদলে get_db_connection() কল করেছিল। ৭টি ফাইলে এটি ঠিক করে Database::getConnection() দিয়ে replace করা হয়েছে।

- **[FEATURE ADDED]** Notices-এ Optional PDF Attachment ফীচার যোগ করা হয়েছে। সিকিউরিটির জন্য application/pdf MIME type check (finfo), %PDF- magic bytes check, double extension block, 5MB limit এবং random filename generate করা হচ্ছে। ফাইলগুলো uploads/notices/ ফোল্ডারে সেভ হবে (যা .htaccess দিয়ে PHP execution block করা)।
- **[FIXED]** Hero সেকশনের CDS লোগো গ্রাফিক্সের চারপাশের ৪টি অপ্রয়োজনীয় সাদা ডট (circle element) `index.php` থেকে রিমুভ করা হয়েছে।
- **[UPDATED]** Footer-এ "Made with ♥ by Future Minds Academy" (লিংক: `https://fuminds.com`) আপডেট করা হয়েছে।
- **[FEATURE ADDED]** Blogs CRUD (admin/blogs_admin.php) যোগ করা হয়েছে। title, content, published_date, cover_image (uploads/blogs/) সহ সম্পূর্ণ ফাংশনালিটি (PDO, CSRF, sanitize)। `blog.php` তে ডাইনামিক ফেচিং এবং `blog_details.php` টেমপ্লেট তৈরি করা হয়েছে। schema.sql এ blogs টেবিল যোগ করা হয়েছে।
- **[REDESIGNED]** CIVICUS ডিজাইনের আদলে সম্পূর্ণ `index.php` হোমপেজ রিডিজাইন সম্পন্ন: ৩-স্লাইডের হিরো ক্যারোসেল/স্লাইডার (বাম/ডান অ্যারো + ডট নেভিগেশন), সিডিএস ব্র্যান্ড কালারের জ্যামিতিক ডিভাইডার, ৫টি মূল স্তম্ভের কার্ড (সুশিক্ষা, সুশাসন, সুস্বাস্থ্য, সুনাগরিক, উন্নত বাংলাদেশ), সাম্প্রতিক আপডেট গ্রিড (প্রজেক্ট ও নোটিশ), এনগেজ ও অ্যাক্ট সেকশন এবং নিউজলেটার সাবস্ক্রিপশন ব্লক। Tailwind CSS রি-কম্পাইল করে `assets/css/style.css` আপডেট ও পুশ করা হয়েছে।
- **[HEADER FIXES]** হেডারের সমস্যাগুলো সম্পূর্ণ ফিক্স করা হয়েছে:
  1. মেগা মেনুর সাব-ড্রপডাউন হোভার বাফার ও মাউস লিভ ফিক্স করা হয়েছে (মাউস নিয়ে গেলে আর সাবমেনু গায়েব হয় না)।
  2. মোবাইল হেডারের বিন্যাস নিখুঁত করা হয়েছে (বামে Language বাটন, মাঝে শুধুমাত্র CDS লোগো—মোবাইলে লেখা হাইড করা হয়েছে, ডানে Join ও মেনু বাটন)।
  3. ভাষা পরিবর্তন বাটনে সিলেক্টেড ভাষার ডায়নামিক লেবেল (BN/EN) ও টিকচিহ্ন এবং মোবাইলে টাচ/ট্যাপ ড্রপডাউন টগল যোগ করা হয়েছে।
  4. মোবাইল ড্রপডাউন মেনুর সব হার্ডকোডেড ইংরেজি টেক্সটকে যথাযথ বাংলা লেবেল দিয়ে আপডেট করা হয়েছে।
  5. ডেস্কটপ ও মোবাইলের জন্য ফুল ফাংশনাল `search.php` পেজ ও ফর্ম সার্চ ইঞ্জিন তৈরি করা হয়েছে।


