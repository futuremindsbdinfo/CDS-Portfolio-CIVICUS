-- ============================================
-- CDS Portfolio — MySQL Database Schema
-- ============================================

CREATE DATABASE IF NOT EXISTS cds_portfolio
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE cds_portfolio;

-- 1. admins (Admin Panel Login)
CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Note: There is no default admin user created in this schema for security reasons.
-- To create an admin user, run the CLI script: php scripts/create_admin.php

-- 2. login_attempts (Brute-force protection)
CREATE TABLE login_attempts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    username VARCHAR(50) NOT NULL,
    success TINYINT(1) NOT NULL DEFAULT 0,
    attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 3. notices
CREATE TABLE notices (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_bn VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NULL,
    content_bn TEXT NOT NULL,
    content_en TEXT NULL,
    file_path VARCHAR(255) NULL,
    published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 4. projects
CREATE TABLE projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_bn VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NULL,
    description_bn TEXT NOT NULL,
    description_en TEXT NULL,
    status ENUM('ongoing', 'completed') NOT NULL DEFAULT 'ongoing',
    cover_image VARCHAR(255) NOT NULL,
    video_embed TEXT NULL,
    video_url VARCHAR(255) NULL,
    start_date DATE NULL,
    end_date DATE NULL,
    details TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE gov_links (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    logo_image VARCHAR(255) NULL,
    category VARCHAR(100) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 5. gallery
CREATE TABLE gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id INT UNSIGNED NULL,
    image_path VARCHAR(255) NOT NULL,
    caption_bn VARCHAR(255) NOT NULL,
    caption_en VARCHAR(255) NULL,
    event_date DATE NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- 6. contact_messages
CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 7. donation_interests
CREATE TABLE donation_interests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(100) NOT NULL,
    donor_phone VARCHAR(20) NOT NULL,
    donor_email VARCHAR(150) NULL,
    donation_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(100) NULL,
    ip_address VARCHAR(45) NULL,
    status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 8. blogs
CREATE TABLE IF NOT EXISTS blogs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NULL,
    title_bn VARCHAR(255) NULL,
    title_en VARCHAR(255) NULL,
    content TEXT NULL,
    content_bn TEXT NULL,
    content_en TEXT NULL,
    cover_image VARCHAR(255) NULL,
    published_date DATE NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 9. publications
CREATE TABLE IF NOT EXISTS publications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NULL,
    description TEXT NOT NULL,
    description_en TEXT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'report',
    cover_image VARCHAR(255) NULL,
    file_path VARCHAR(255) NULL,
    published_date DATE NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 10. feedback
CREATE TABLE IF NOT EXISTS feedback (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    rating INT NOT NULL DEFAULT 5,
    comment TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 11. settings
CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` TEXT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 12. hero_sliders
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
);

-- 13. team_members
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
);

-- 14. downloadable_forms
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
);

-- 15. newsletter_subscribers
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    ip_address VARCHAR(45) NULL,
    status ENUM('active', 'unsubscribed') NOT NULL DEFAULT 'active',
    subscribed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
