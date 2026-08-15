<?php
// includes/auto_migrate.php
// Automatically ensures all required tables exist in the database with default seed data

function ensure_database_tables_exist(PDO $pdo) {
    static $executed = false;
    if ($executed) return;
    $executed = true;

    try {
        // 1. HERO SLIDERS TABLE
        $pdo->exec("CREATE TABLE IF NOT EXISTS hero_sliders (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 2. TEAM MEMBERS / COMMITTEE TABLE
        $pdo->exec("CREATE TABLE IF NOT EXISTS team_members (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 3. DOWNLOADABLE FORMS TABLE
        $pdo->exec("CREATE TABLE IF NOT EXISTS downloadable_forms (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 4. NEWSLETTER SUBSCRIBERS TABLE
        $pdo->exec("CREATE TABLE IF NOT EXISTS newsletter_subscribers (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(150) NOT NULL UNIQUE,
            ip_address VARCHAR(45) NULL,
            status ENUM('active', 'unsubscribed') NOT NULL DEFAULT 'active',
            subscribed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 5. BLOGS TABLE
        $pdo->exec("CREATE TABLE IF NOT EXISTS blogs (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NULL,
            title_bn VARCHAR(255) NULL,
            title_en VARCHAR(255) NULL,
            content TEXT NULL,
            content_bn TEXT NULL,
            content_en TEXT NULL,
            category VARCHAR(50) NOT NULL DEFAULT 'news',
            image_path VARCHAR(255) NULL,
            cover_image VARCHAR(255) NULL,
            author_name VARCHAR(100) NULL DEFAULT 'Admin',
            status ENUM('published', 'draft', 'archived') NOT NULL DEFAULT 'published',
            published_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 6. SETTINGS TABLE
        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(100) NOT NULL UNIQUE,
            `value` TEXT NULL,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 7. SEED DEFAULT HERO SLIDERS IF EMPTY
        $sliderCount = (int)$pdo->query("SELECT COUNT(*) FROM hero_sliders")->fetchColumn();
        if ($sliderCount === 0) {
            $pdo->exec("INSERT INTO hero_sliders (id, title_bn, title_en, subtitle_bn, subtitle_en, image_path, button_text_bn, button_text_en, button_url, display_order, is_active) VALUES 
            (1, 'নাগরিক ক্ষমতায়নে একটি সমৃদ্ধ ও মানবিক সমাজ বিনির্মাণে', 'Empowering Citizens to Build an Inclusive & Just Society', 'সিডিএস একটি অরাজনৈতিক, অলাভজনক ও স্বেচ্ছাসেবী সামাজিক সংস্থা। সুশিক্ষা, সুশাসন, সুস্বাস্থ্য ও সুনাগরিক গড়ে তোলাই আমাদের অঙ্গীকার।', 'CDS is a non-political, non-profit and voluntary civil society organization dedicated to quality education, good governance, and active citizenship.', 'assets/img/hero-slide-1.jpg', 'সদস্য হোন', 'JOIN CDS', 'https://membership.fuminds.com/', 1, 1),
            (2, 'সিডিএস-এর সদস্য হয়ে সমাজের ইতিবাচক পরিবর্তনে যুক্ত হোন', 'Become a Member & Drive Meaningful Social Change', 'সাধারণ, আজীবন, দাতা, উপদেষ্টা ও ছাত্র/যুব ক্যাটাগরিতে সদস্য হতে পারেন। আপনার সক্রিয় অংশগ্রহণ আমাদের শক্তি।', 'Join under General, Life, Donor, Advisor or Youth categories and be a key part of our grassroots impact.', 'assets/img/hero-slide-2.jpg', 'অনলাইনে ফরম পূরণ করুন', 'APPLY ONLINE', 'https://membership.fuminds.com/', 2, 1),
            (3, 'শিক্ষা, স্বাস্থ্য ও সুশাসনের লক্ষ্যে মাঠপর্যায়ে বাস্তবসম্মত উদ্যোগ', 'Real Grassroots Initiatives in Education, Health & Governance', 'কুমিল্লা নাঙ্গলকোট ও লালমাই উপজেলাসহ সমগ্র বাংলাদেশে প্রান্তিক জনগোষ্ঠীকে এগিয়ে নিতে আমরা নিবেদিতপ্রাণ।', 'Dedicated to uplifting underprivileged communities across Nangalkot, Lalmai and all over Bangladesh.', 'assets/img/cds-logo.png', 'চলমান প্রজেক্টসমূহ', 'VIEW PROJECTS', '/projects.php', 3, 1);");
        }

        // 8. SEED DEFAULT FORMS IF EMPTY
        $formCount = (int)$pdo->query("SELECT COUNT(*) FROM downloadable_forms")->fetchColumn();
        if ($formCount === 0) {
            $pdo->exec("INSERT INTO downloadable_forms (id, title_bn, title_en, description_bn, description_en, category, file_path, file_type, file_size) VALUES
            (1, 'সাধারণ সদস্যপদ আবেদন ফরম', 'General Membership Application Form', 'সিডিএস-এর সাধারণ সদস্যপদ গ্রহণের জন্য নির্ধারিত আবেদন ফরম।', 'Official application form for obtaining general membership of CDS.', 'সদস্যপদ ও আবেদন', 'uploads/forms/CDS_Membership_Form.pdf', 'pdf', '1.4 MB'),
            (2, 'স্বেচ্ছাসেবক নিবন্ধন ফরম', 'Volunteer Registration Form', 'সিডিএস-এর বিভিন্ন সামাজিক উন্নয়নমূলক কার্যক্রমে ভলান্টিয়ার হিসেবে অংশ নিতে ফরমটি পূরণ করুন।', 'Fill out this form to join CDS social initiatives as a volunteer.', 'স্বেচ্ছাসেবা', 'uploads/forms/CDS_Volunteer_Registration.pdf', 'pdf', '980 KB'),
            (3, 'শিক্ষা সহায়তা আবেদন ফরম', 'Educational Aid Application Form', 'দরিদ্র ও মেধাবী শিক্ষার্থীদের শিক্ষা বৃত্তির জন্য আবেদন ফরম।', 'Scholarship and financial aid application form for meritorious underprivileged students.', 'শিক্ষা ও বৃত্তি', 'uploads/forms/CDS_Scholarship_Form.pdf', 'pdf', '1.1 MB');");
        }

    } catch (Throwable $e) {
        // Log safely without interrupting execution
        error_log("Database Auto-Migration Notice: " . $e->getMessage());
    }
}
