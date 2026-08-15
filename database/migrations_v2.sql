-- ============================================================
-- CDS Portfolio — Database Migrations v2
-- Extends the database with Slider, Team, Forms, Subscribers,
-- and Donation Payment Settings
-- ============================================================

-- 1. HERO SLIDERS TABLE
CREATE TABLE IF NOT EXISTS hero_sliders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_bn VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    subtitle_bn TEXT NOT NULL,
    subtitle_en TEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    button_text_bn VARCHAR(100) NULL DEFAULT 'আমাদের সম্পর্কে জানুন',
    button_text_en VARCHAR(100) NULL DEFAULT 'Learn About Us',
    button_url VARCHAR(255) NULL DEFAULT '/about.php',
    display_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TEAM MEMBERS / COMMITTEE TABLE
CREATE TABLE IF NOT EXISTS team_members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_bn VARCHAR(150) NOT NULL,
    name_en VARCHAR(150) NOT NULL,
    designation_bn VARCHAR(150) NOT NULL,
    designation_en VARCHAR(150) NOT NULL,
    category ENUM('governing_body', 'advisors', 'general_members', 'volunteers') NOT NULL DEFAULT 'governing_body',
    photo_path VARCHAR(255) NULL,
    bio_bn TEXT NULL,
    bio_en TEXT NULL,
    email VARCHAR(150) NULL,
    phone VARCHAR(30) NULL,
    facebook_url VARCHAR(255) NULL,
    linkedin_url VARCHAR(255) NULL,
    display_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. DOWNLOADABLE FORMS TABLE
CREATE TABLE IF NOT EXISTS downloadable_forms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_bn VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    description_bn TEXT NULL,
    description_en TEXT NULL,
    category VARCHAR(100) NOT NULL DEFAULT 'সদস্যপদ ও আবেদন',
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL DEFAULT 'pdf',
    file_size VARCHAR(50) NULL DEFAULT '1.2 MB',
    downloads_count INT UNSIGNED NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. NEWSLETTER SUBSCRIBERS TABLE
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    ip_address VARCHAR(45) NULL,
    status ENUM('active', 'unsubscribed') NOT NULL DEFAULT 'active',
    subscribed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. SEED DEFAULT SLIDERS
INSERT IGNORE INTO hero_sliders (id, title_bn, title_en, subtitle_bn, subtitle_en, image_path, button_text_bn, button_text_en, button_url, display_order)
VALUES 
(1, 'নাগরিক সচেতনতা ও সমাজ উন্নয়নে সিডিএস', 'Citizen Development Society (CDS)', 'সুশিক্ষা, সুস্বাস্থ্য ও সুশাসন প্রতিষ্ঠার মাধ্যমে বৈষম্যহীন এবং সমৃদ্ধ বাংলাদেশ গড়াই আমাদের অঙ্গীকার।', 'Our commitment is to build a discrimination-free and prosperous Bangladesh through quality education, health and good governance.', 'assets/img/hero/hero-bg-2.svg', 'আমাদের কার্যক্রম', 'Our Activities', '/projects.php', 1),
(2, 'সুশিক্ষা ও মানবিক মূল্যবোধের বিকাশ', 'Development of Education & Human Values', 'দরিদ্র ও সুবিধাবঞ্চিত শিশুদের শিক্ষা সহায়তা এবং সামাজিক নেতৃত্ব বিকাশে আমরা নিরলস কাজ করছি।', 'We work relentlessly in educational support for underprivileged children and developing social leadership.', 'assets/img/hero/hero-bg-1.svg', 'যোগ দিন', 'Join Us', 'https://membership.fuminds.com/', 2),
(3, 'স্বেচ্ছাসেবা ও অধিকার সুরক্ষায় ঐক্যবদ্ধ', 'United in Volunteering & Rights Protection', 'তরুণদের সামাজিক সচেতনতা বৃদ্ধি এবং মানবাধিকার সুরক্ষায় কমিউনিটি পর্যায়ে সক্রিয় অংশগ্রহণ।', 'Promoting community youth engagement in social awareness and human rights protection.', 'assets/img/hero/hero-bg-3.svg', 'অনুদান দিন', 'Donate', '/donation.php', 3);

-- 6. SEED DEFAULT TEAM MEMBERS
INSERT IGNORE INTO team_members (id, name_bn, name_en, designation_bn, designation_en, category, photo_path, bio_bn, bio_en, display_order)
VALUES
(1, 'মোহাম্মদ শামসুল আলম', 'Mohammad Shamsul Alam', 'সভাপতি / প্রধান উপদেষ্টা', 'President / Chief Advisor', 'governing_body', 'uploads/team/member-1.jpg', 'সমাজসেবা ও শিক্ষানুরাগী ব্যক্তিত্ব, সিডিএস-এর প্রতিষ্ঠাকালীন নেতৃত্ব।', 'Social worker and educationist, foundational leader of CDS.', 1),
(2, 'ড. তানভীর আহমেদ', 'Dr. Tanvir Ahmed', 'সাধারণ সম্পাদক', 'General Secretary', 'governing_body', 'uploads/team/member-2.jpg', 'উন্নয়নকর্মী ও গবেষক, তৃণমূল পর্যায়ে সমাজকল্যাণমূলক কার্যক্রমে অভিজ্ঞ।', 'Development worker and researcher with extensive grassroots experience.', 2),
(3, 'নাসরিন আক্তার', 'Nasreen Akhter', 'কোষাধ্যক্ষ ও সমাজকল্যাণ সম্পাদক', 'Treasurer & Social Welfare Secretary', 'governing_body', 'uploads/team/member-3.jpg', 'নারী অধিকার ও স্বাস্থ্যসেবা কার্যক্রমে নিবেদিতপ্রাণ সংগঠক।', 'Dedicated organizer for women empowerment and healthcare initiatives.', 3),
(4, 'প্রফেসর ড. রফিকুল ইসলাম', 'Prof. Dr. Rafiqul Islam', 'উপদেষ্টা পরিষদ সদস্য', 'Member, Advisory Council', 'advisors', 'uploads/team/member-4.jpg', 'বিশিষ্ট শিক্ষাবিদ ও সমাজ সংস্কারক।', 'Eminent academician and social reformer.', 1),
(5, 'ব্যারিস্টার ফারহানা হক', 'Barrister Farhana Huq', 'আইনি উপদেষ্টা', 'Legal Advisor', 'advisors', 'uploads/team/member-5.jpg', 'মানবাধিকার ও সুশাসন বিষয়ক আইনি পরামর্শক।', 'Legal consultant specializing in human rights and good governance.', 2);

-- 7. SEED DEFAULT DOWNLOADABLE FORMS
INSERT IGNORE INTO downloadable_forms (id, title_bn, title_en, description_bn, description_en, category, file_path, file_type, file_size)
VALUES
(1, 'সাধারণ সদস্যপদ আবেদন ফরম', 'General Membership Application Form', 'সিডিএস-এর সাধারণ সদস্যপদ গ্রহণের জন্য নির্ধারিত আবেদন ফরম।', 'Official application form for obtaining general membership of CDS.', 'সদস্যপদ ও আবেদন', 'uploads/forms/CDS_Membership_Form.pdf', 'pdf', '1.4 MB'),
(2, 'স্বেচ্ছাসেবক নিবন্ধন ফরম', 'Volunteer Registration Form', 'সিডিএস-এর বিভিন্ন সামাজিক উন্নয়নমূলক কার্যক্রমে ভলান্টিয়ার হিসেবে অংশ নিতে ফরমটি পূরণ করুন।', 'Fill out this form to join CDS social initiatives as a volunteer.', 'স্বেচ্ছাসেবা', 'uploads/forms/CDS_Volunteer_Registration.pdf', 'pdf', '980 KB'),
(3, 'শিক্ষা সহায়তা আবেদন ফরম', 'Educational Aid Application Form', 'দরিদ্র ও মেধাবী শিক্ষার্থীদের শিক্ষা বৃত্তির জন্য আবেদন ফরম।', 'Scholarship and financial aid application form for meritorious underprivileged students.', 'শিক্ষা ও বৃত্তি', 'uploads/forms/CDS_Scholarship_Form.pdf', 'pdf', '1.1 MB');

-- 8. SEED DEFAULT DONATION SETTINGS
INSERT IGNORE INTO settings (`key`, `value`) VALUES
('donation_bkash_personal', '01700000000'),
('donation_bkash_merchant', '01800000000'),
('donation_nagad', '01900000000'),
('donation_rocket', '017000000009'),
('donation_bank_name', 'Islami Bank Bangladesh PLC'),
('donation_bank_account_name', 'Citizen Development Society (CDS)'),
('donation_bank_account_no', '20501234567890100'),
('donation_bank_branch', 'Dhanmondi Branch, Dhaka'),
('donation_bank_routing_no', '125271829');
