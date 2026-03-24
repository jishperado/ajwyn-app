-- ============================================
-- VENDOR SYSTEM MIGRATION
-- Run this SQL on admin_ajwyn database
-- ============================================

-- 1. Add role and vendor fields to admin_log
ALTER TABLE `admin_log`
  ADD COLUMN `role` ENUM('admin','vendor') NOT NULL DEFAULT 'admin' AFTER `password`,
  ADD COLUMN `shop_name` VARCHAR(200) NULL AFTER `name`,
  ADD COLUMN `phone` VARCHAR(15) NULL AFTER `shop_name`,
  ADD COLUMN `email` VARCHAR(200) NULL AFTER `phone`,
  ADD COLUMN `is_active` ENUM('Y','N') NOT NULL DEFAULT 'Y' AFTER `email`,
  ADD COLUMN `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP AFTER `is_active`;

-- 2. Add vendor_id to product table
ALTER TABLE `product`
  ADD COLUMN `vendor_id` INT NULL DEFAULT NULL AFTER `id`;

-- 3. Add index for faster vendor product lookups
ALTER TABLE `product` ADD INDEX `idx_vendor_id` (`vendor_id`);
