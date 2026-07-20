# Architecture.md - CDS Portfolio

## ১. অ্যাপ ফ্লো এবং আর্কিটেকচার (App Flow & Architecture)
- **আর্কিটেকচার:** ওয়েবসাইটটি মূলত একটি ক্লাসিক মাল্টি-পেজ ওয়েবসাইট হিসেবে তৈরি করা হবে যা দ্রুত লোড হয় এবং ব্যবহারকারীদের দারুণ অভিজ্ঞতা দেয়। ডাটা ম্যানেজমেন্টের জন্য ব্যাকএন্ডে MySQL ডাটাবেস ব্যবহার করা হবে।

## ২. ফোল্ডার এবং ফাইল স্ট্রাকচার (Folder & File Structure)
```text
cds-portfolio/
│
├── assets/
│   ├── css/          # স্টাইলশিট (Vanilla CSS এবং Tailwind CSS)
│   ├── img/          # ছবি এবং লোগো
│   └── js/           # জাভাস্ক্রিপ্ট ফাইলসমূহ
│
├── docs/             # প্রজেক্ট ডকুমেন্টেশন (PRD, Architecture, Rules ইত্যাদি)
├── index.html        # হোম পেজ
├── about.html        # আমাদের সম্পর্কে
├── projects.html     # প্রজেক্ট পেজ
├── gallery.html      # গ্যালারি পেজ
├── notice.html       # নোটিশ বোর্ড
└── contact.html      # যোগাযোগ পেজ
```

## ৩. টেক স্ট্যাক (Tech Stack)
- **ফ্রন্টএন্ড (Frontend):** শুধুমাত্র HTML, CSS, এবং JavaScript (Vanilla JS)।
- **স্টাইলিং (Styling):** কাস্টম ডিজাইনের জন্য Vanilla CSS এবং ইউটিলিটির জন্য Tailwind CSS।
- **ব্যাকএন্ড (Backend):** PHP (cPanel-friendly shared hosting সাপোর্ট করার জন্য নির্বাচিত) — PDO দিয়ে MySQL কানেক্ট করা হবে, সবসময় prepared statements ব্যবহার করে।
- **ডাটাবেস (Database):** MySQL (`utf8mb4` চার্সেট, বাংলা টেক্সট ঠিকভাবে সাপোর্ট করার জন্য) — নোটিশ, প্রজেক্টস, গ্যালারি, কন্টাক্ট মেসেজ এবং ডোনেশন ইন্টারেস্ট সংরক্ষণের জন্য। বিস্তারিত স্কিমা `docs/schema.sql`-এ আছে।

## ৪. অ্যাডমিন প্যানেল (Admin Panel)
সাইটের কন্টেন্ট (Notice, Projects, Gallery) ম্যানেজ করার জন্য session-based লগইন সহ একটা আলাদা `/admin` প্যানেল থাকবে। সিকিউরিটি ফিচার:
- Login-এ bcrypt/Argon2 password hashing (`password_hash()` / `password_verify()`)।
- Brute-force protection: `login_attempts` টেবিলে ট্র্যাক করে rate-limit / সাময়িক lockout।
- সব ফর্মে CSRF token।
- সেশন কুকিতে `httponly`, `secure`, `samesite` ফ্ল্যাগ।

## ৫. ডাটাবেস ডিজাইন সারসংক্ষেপ (Database Design Summary)
| টেবিল | উদ্দেশ্য |
|---|---|
| `admins` | অ্যাডমিন প্যানেল লগইন |
| `login_attempts` | Brute-force protection / rate limiting |
| `notices` | নোটিশ বোর্ড কন্টেন্ট (বাংলা/ইংরেজি) |
| `projects` | প্রজেক্টস পেজ কন্টেন্ট |
| `gallery` | গ্যালারি ইমেজ (প্রজেক্টের সাথে অপশনালি সংযুক্ত) |
| `contact_messages` | কন্টাক্ট ফর্ম সাবমিশন |
| `donation_interests` | ডোনেশন ইন্টারেস্ট ফর্ম (কোনো পেমেন্ট ডাটা স্টোর হয় না) |

পূর্ণাঙ্গ `CREATE TABLE` স্টেটমেন্ট এবং কলাম-বাই-কলাম কমেন্ট `docs/schema.sql` ফাইলে আছে।

## ৬. আপডেটেড ফোল্ডার স্ট্রাকচার
```text
cds-portfolio/
│
├── assets/
│   ├── css/
│   ├── img/
│   └── js/
│
├── config/
│   └── database.php        # DB credentials — .htaccess দিয়ে সরাসরি অ্যাক্সেস ব্লক করা থাকবে
│
├── includes/
│   ├── db.php               # PDO connection, prepared statements
│   ├── csrf.php             # CSRF token generate/verify
│   ├── auth.php             # Admin session check middleware
│   └── sanitize.php         # Input validation + output escaping helpers
│
├── admin/
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── notices/
│   ├── gallery/
│   └── projects/
│
├── uploads/                  # গ্যালারি/প্রজেক্ট ইমেজ — এখানে PHP execution disable (.htaccess দিয়ে)
│
├── docs/
│   └── schema.sql            # পূর্ণাঙ্গ ডাটাবেস স্কিমা
│
├── index.php                 # ডাইনামিক নোটিশ/প্রজেক্ট দেখানোর জন্য .html থেকে .php
├── about.php
├── projects.php
├── gallery.php
├── notice.php
├── donation.php
└── contact.php
```
> **নোট:** যেসব পেজে ডাটাবেস থেকে কন্টেন্ট লোড হবে (notice, projects, gallery, contact, donation) সেগুলো `.html` থেকে `.php`-তে কনভার্ট করতে হবে।
