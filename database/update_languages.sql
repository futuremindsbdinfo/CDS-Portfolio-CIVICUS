-- Migration to update `blogs` table for bilingual support
-- Run this in phpMyAdmin on the live server

ALTER TABLE blogs 
CHANGE COLUMN title title_bn VARCHAR(255) NOT NULL,
CHANGE COLUMN content content_bn TEXT NOT NULL,
ADD COLUMN title_en VARCHAR(255) NULL AFTER title_bn,
ADD COLUMN content_en TEXT NULL AFTER content_bn;
