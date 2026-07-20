-- ============================================
-- CDS Portfolio — MySQL Database Schema
-- Engine: InnoDB (row-level locking, FK support)
-- Charset: utf8mb4 (বাংলা টেক্সট ঠিকভাবে সাপোর্ট করার জন্য)
-- ============================================

CREATE DATABASE IF NOT EXISTS cds_portfolio
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE cds_portfolio;

-- ============================================
-- 1. ADMINS (Admin Panel Login)
-- ============================================
CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,   -- password_hash() দিয়ে bcrypt/argon2 hash, plaintext কখনোই না
    email VARCHAR(150) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- 2. LOGIN_ATTEMPTS (Brute-force protection)
-- ============================================
CREATE TABLE login_attempts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,       -- IPv4/IPv6 দুটোর জন্যই যথেষ্ট length
    success TINYINT(1) NOT NULL DEFAULT 0,
    attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username_time (username, attempted_at),
    INDEX idx_ip_time (ip_address, attempted_at)
) ENGINE=InnoDB;

-- ============================================
-- 3. NOTICES (নোটিশ বোর্ড)
-- ============================================
CREATE TABLE notices (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_bn VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    content_bn TEXT NOT NULL,
    content_en TEXT NOT NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE RESTRICT,
    INDEX idx_published (is_published, published_at)
) ENGINE=InnoDB;

-- ============================================
-- 4. PROJECTS (প্রজেক্টস)
-- ============================================
CREATE TABLE projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_bn VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    description_bn TEXT NOT NULL,
    description_en TEXT NOT NULL,
    status ENUM('ongoing', 'completed') NOT NULL DEFAULT 'ongoing',
    cover_image VARCHAR(255) NULL,          -- শুধু ফাইলের নাম রাখা হবে, পাথ না (path traversal এড়ানোর জন্য)
    start_date DATE NULL,
    end_date DATE NULL,
    created_by INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================
-- 5. GALLERY (গ্যালারি — প্রজেক্টের সাথে সংযুক্ত হতে পারে, নাও পারে)
-- ============================================
CREATE TABLE gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id INT UNSIGNED NULL,
    image_path VARCHAR(255) NOT NULL,       -- শুধু র্যান্ডম জেনারেটেড ফাইলনেম, ইউজার ইনপুট না
    caption_bn VARCHAR(255) NULL,
    caption_en VARCHAR(255) NULL,
    event_date DATE NULL,
    uploaded_by INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
    FOREIGN KEY (uploaded_by) REFERENCES admins(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================
-- 6. CONTACT_MESSAGES (যোগাযোগ ফর্ম)
-- ============================================
CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    submitted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_submitted (submitted_at),
    INDEX idx_ip_time (ip_address, submitted_at)   -- rate limiting চেক করার জন্য
) ENGINE=InnoDB;

-- ============================================
-- 7. DONATION_INTERESTS (ডোনেশন ইন্টারেস্ট ফর্ম — informational, no payment data stored)
-- ============================================
CREATE TABLE donation_interests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(150) NULL,
    intended_amount DECIMAL(10,2) NULL,
    message TEXT NULL,
    ip_address VARCHAR(45) NOT NULL,
    is_contacted TINYINT(1) NOT NULL DEFAULT 0,
    submitted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_submitted (submitted_at),
    INDEX idx_ip_time (ip_address, submitted_at)
) ENGINE=InnoDB;
