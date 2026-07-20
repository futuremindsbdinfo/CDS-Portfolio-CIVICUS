# memory.md - CDS Portfolio (Project Memory)

## এ পর্যন্ত যা যা সম্পন্ন হয়েছে (What has been completed)
- প্রজেক্টের রিকোয়ারমেন্ট এবং টেক স্ট্যাক (HTML/CSS/JS, Tailwind, MySQL) চূড়ান্ত করা হয়েছে।
- PRD, Architecture, Rules, Phases, এবং Design এর ডকুমেন্টেশন প্রপারলি তৈরি করা হয়েছে।
- **ব্যাকএন্ড ডিসিশন চূড়ান্ত:** PHP (PDO + prepared statements) ব্যবহার হবে, cPanel-friendly shared hosting-এর জন্য।
- **অ্যাডমিন প্যানেল কনফার্ম:** Notice, Projects, Gallery — এই তিনটা কন্টেন্ট টাইপ session-login সহ `/admin` প্যানেল থেকে CRUD করা যাবে।
- **ডোনেশন পেজ স্কোপ কনফার্ম:** শুধু তথ্যমূলক (bKash/Bank details) + একটা "ডোনেশন ইন্টারেস্ট" ফর্ম — কোনো অনলাইন পেমেন্ট গেটওয়ে/কার্ড ডাটা নেই।
- **`docs/schema.sql` তৈরি হয়েছে** — 7টা টেবিল: `admins`, `login_attempts`, `notices`, `projects`, `gallery`, `contact_messages`, `donation_interests`। সিকিউরিটি বিবেচনা (password hashing, brute-force protection, random filename, IP-based rate limiting) স্কিমাতে বিল্ট-ইন।
- **Architecture.md আপডেট হয়েছে** — নতুন ফোল্ডার স্ট্রাকচার (`config/`, `includes/`, `admin/`, `uploads/`) এবং ডাটাবেস সামারি টেবিল যোগ করা হয়েছে।
- **Phase 1 সম্পূর্ণ** — frontend base (Tailwind, header/footer, index.php) এবং সিকিউরিটি scaffolding (db.php, csrf.php, auth.php, sanitize.php, admin panel with rate-limiting, .htaccess protections) দুটোই Antigravity দিয়ে তৈরি হয়েছে।

## বর্তমানে কোন ফাইলে কাজ চলছে (Which file is currently being worked)
- **Phase 1 সম্পূর্ণ সম্পন্ন** (frontend + security scaffolding দুটোই):
  - Frontend: package.json, tailwind.config.js, assets/css/input.css + style.css, includes/header.php, includes/footer.php, index.php
  - Security: config/.htaccess, config/database.php (.env-based), .env.example, .gitignore, includes/db.php, includes/csrf.php, includes/auth.php, includes/sanitize.php, admin/.htaccess, admin/login.php (rate-limited + CSRF + password_verify + session_regenerate_id), admin/logout.php, admin/dashboard.php (protected), uploads/.htaccess (PHP execution blocked), logs/.htaccess, docs/schema.sql
- ⚠️ **ম্যানুয়াল অ্যাকশন বাকি:** রুটে `.env` ফাইল বানিয়ে DB_HOST/DB_NAME/DB_USER/DB_PASS বসাতে হবে (এটা git-এ যাবে না)।
- এরপর Phase 2 (Home + About পেজ) শুরু হবে।

> **সতর্কতা:** কাজের সাথে সাথে এই ফাইলটি নিয়মিত আপডেট করতে হবে (UPDATE IT REGULARLY)।
