# tasks.md - CDS Portfolio (Active Task Tracker)

## Pre-Deployment Checklist (Serial অনুযায়ী কাজ হবে: 1 → 2 → 3 → 4)

### 1. Security/Functional Testing
- [ ] uploads/ .php execution block — .htaccess rule যোগ করা হয়েছে, কিন্তু PHP built-in server (php -S) .htaccess process করে না বলে local-এ verify করা যায়নি। XAMPP Apache বা production deploy-এর পর আবার test করতে হবে।
- [x] Admin login brute-force lockout টেস্ট (৫-৬ বার ভুল পাসওয়ার্ড)
- [x] সব ফর্মে CSRF token টেস্ট (contact, donation-interest, admin login)
- [ ] Cross-browser/device responsive টেস্ট
- [ ] Image upload feature end-to-end টেস্ট

### 2. Hosting/Server Prep
- [x] Notices-এ Optional PDF Upload (Secure, with magic bytes and MIME verification)
- [ ] Hosting provider চূড়ান্ত করা
- [ ] Domain + SSL install
- [ ] Production .env বানানো (আলাদা DB credentials)
- [ ] config/database.php production detection কনফার্ম
- [ ] cPanel cronjob দিয়ে scripts/backup_db.php শিডিউল

### 3. Code/Performance Polish
- [x] Session Error and Mobile Responsive CSS Fixed: Replaced naked `session_start()` with `init_secure_session()` directing to protected `logs/sessions/`. Ran `npm run build:css` and added cache-busting to CSS links to fix broken grid/flex layout on mobile viewports.
- [x] dummy data seed করা হয়েছে scripts/seed_dummy_data.sql দিয়ে — launch এর আগে এই ডেটা DB থেকে মুছে ফেলতে হবে
- [ ] Image optimization pipeline ভেরিফাই
- [ ] PageSpeed/Lighthouse দিয়ে load speed চেক
- [ ] about/projects/gallery/notice/donation পেজে design consistency final pass (index.php-এর নতুন design এর সাথে মিলছে কিনা)
- [ ] 404 error page
- [ ] Favicon confirm

### 4. Temporary Placeholder Fix (Content না আসা পর্যন্ত)
- [ ] Team photo placeholder-কে generic avatar/icon দিয়ে বদলানো (real ছবি না আসা পর্যন্ত temporary)

---

## নিয়ম (Antigravity-এর জন্য)
- এখন থেকে এই ফাইলটাই হবে active task tracker — .ai/memory.md শুধু completed-work history/log-এর জন্য থাকবে
- প্রতিটা টাস্ক শেষ হলে এই ফাইলে সাথে সাথে [x] mark করে দিতে হবে
- একটা বড় task (Section 1, 2, 3, 4) সম্পূর্ণ শেষ হলে, তার সংক্ষিপ্ত summary .ai/memory.md-এর "এ পর্যন্ত যা যা সম্পন্ন হয়েছে" সেকশনে যোগ করতে হবে — কিন্তু .ai/tasks.md-এর checklist থেকে সরানো যাবে না, শুধু checkbox tick থাকবে
- কাজ serial অনুযায়ী হবে (Section 1 আগে শেষ, তারপর 2, ইত্যাদি) — user যদি আলাদা order বলে তাহলে সেটাই priority পাবে
