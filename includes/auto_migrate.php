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

    } catch (Throwable $e) {
        // Log safely without interrupting execution
        error_log("Database Auto-Migration Notice: " . $e->getMessage());
    }
}
