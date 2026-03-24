-- Step 1: Drop the incorrectly typed role column (INT)
ALTER TABLE `admin_log` DROP COLUMN `role`;

-- Step 2: Re-add it as proper ENUM with default 'admin'
ALTER TABLE `admin_log` ADD COLUMN `role` ENUM('admin','vendor') NOT NULL DEFAULT 'admin' AFTER `password`;
