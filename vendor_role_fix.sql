-- Fix: Set role to 'admin' for all existing users who have empty or NULL role
UPDATE `admin_log` SET `role` = 'admin' WHERE `role` IS NULL OR `role` = '' OR `role` = 'admin';
