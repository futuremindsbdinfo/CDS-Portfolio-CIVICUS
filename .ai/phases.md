# phases.md - CDS Portfolio

পুরো প্রজেক্টটিকে সহজভাবে সম্পন্ন করার জন্য কয়েকটি ছোট ছোট ধাপে (Phases) ভাগ করা হলো:

## Phase 1: প্রজেক্ট সেটআপ এবং ডিজাইন সিস্টেম
- ফোল্ডার স্ট্রাকচার তৈরি।
- Tailwind CSS সেটআপ এবং গ্লোবাল CSS ভেরিয়েবল (কালার, ফন্ট) ডিক্লেয়ার করা।
- হেডার (Navbar) এবং ফুটার (Footer) তৈরি।

## Phase 2: হোম এবং এবাউট পেজ তৈরি
- হোম পেজের ব্যানার এবং অন্যান্য সেকশন ডিজাইন।
- 'আমাদের সম্পর্কে' (About Us) পেজ ডিজাইন এবং কন্টেন্ট বসানো।

## Phase 3: প্রজেক্টস এবং গ্যালারি পেজ
- প্রজেক্ট শোকেস করার জন্য গ্রিড লেআউট তৈরি।
- ইমেজ গ্যালারি পেজ ডিজাইন।
- ⚠️ এই ধাপে **dummy/hardcoded ডাটা** দিয়ে UI বানানো হবে (schema.sql-এর `projects`/`gallery` টেবিলের কলাম স্ট্রাকচার অনুযায়ী static array/object ব্যবহার করে) — আসল DB connection Phase 5-এ হবে।

## Phase 4: নোটিশ বোর্ড এবং ডোনেশন পেজ
- নোটিশ বোর্ডের লেআউট তৈরি।
- ডোনেশন পেজের তথ্য (bKash/Bank details) এবং ডিজাইন সম্পন্ন করা, প্লাস ডোনেশন-ইন্টারেস্ট ফর্মের UI (submit logic ছাড়া)।
- ⚠️ এই ধাপেও নোটিশ **dummy/hardcoded ডাটা** দিয়ে দেখানো হবে; আসল DB connection Phase 5-এ হবে।

## Phase 5: ব্যাকএন্ড এবং ডাটাবেস ইন্টিগ্রেশন (MySQL)
- MySQL ডাটাবেস তৈরি (`docs/schema.sql` রান করে)।
- Admin panel: login + notices/projects/gallery CRUD (PDO prepared statements, CSRF, session security)।
- Public পেজগুলোতে (index/projects.php/gallery.php/notice.php) Phase 3-4 এর dummy ডাটার জায়গায় আসল DB query বসানো।
- Contact form ও Donation-interest form-কে DB-তে সেভ করার লজিক যোগ করা (validation, CSRF, IP-based rate limiting সহ)।
- Upload security: `uploads/.htaccess` দিয়ে PHP execution ব্লক, ফাইল টাইপ/সাইজ ভ্যালিডেশন, র্যান্ডম ফাইলনেম।

## Phase 6: টেস্টিং এবং লাইভ
- রেসপন্সিভনেস এবং ব্রাউজার টেস্টিং।
- পারফরম্যান্স অপটিমাইজেশন এবং সার্ভারে আপলোড।

### লাইভ করার আগে সিকিউরিটি চেকলিস্ট
- [ ] `config/database.php`-এর credentials production DB-এর জন্য আলাদা, আর ফাইলটা `.htaccess`/environment variable দিয়ে protected
- [ ] সব ফর্মে (contact, donation-interest, admin login) CSRF token কাজ করছে কিনা টেস্ট করা
- [ ] `admin/` প্যানেলে ব্রুট-ফোর্স লকআউট আসলেই কাজ করছে কিনা টেস্ট করা (৫-৬ বার ভুল পাসওয়ার্ড দিয়ে)
- [ ] `uploads/` ফোল্ডারে সরাসরি `.php` ফাইল আপলোড/এক্সিকিউট করে দেখা যে ব্লক হচ্ছে কিনা
- [ ] সাইট পুরোপুরি HTTPS-এ ফোর্স হচ্ছে কিনা (HTTP → HTTPS redirect)
- [ ] প্রোডাকশনে PHP error display বন্ধ (`display_errors = Off`), শুধু log ফাইলে যাচ্ছে
- [ ] ডাটাবেস ব্যাকআপ শিডিউল সেট করা আছে কিনা
