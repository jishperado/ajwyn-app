-- ============================================
-- VENDOR SYSTEM MIGRATION FIX
-- Run this on admin_ajwyn database
-- (adds the columns that were missed because 'role' already existed)
-- ============================================

ALTER TABLE `admin_log` ADD COLUMN `shop_name` VARCHAR(200) NULL AFTER `name`;
ALTER TABLE `admin_log` ADD COLUMN `phone` VARCHAR(15) NULL AFTER `shop_name`;
ALTER TABLE `admin_log` ADD COLUMN `email` VARCHAR(200) NULL AFTER `phone`;
ALTER TABLE `admin_log` ADD COLUMN `is_active` ENUM('Y','N') NOT NULL DEFAULT 'Y' AFTER `email`;
ALTER TABLE `admin_log` ADD COLUMN `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP AFTER `is_active`;
