ALTER TABLE `tbl_cus` ADD `otp` INT NULL DEFAULT NULL AFTER `updated_date`, ADD `otpattempt` INT NULL DEFAULT NULL AFTER `otp`, ADD `otpdatetime` DATETIME NULL DEFAULT NULL AFTER `otpattempt`;
ALTER TABLE `tbl_order` ADD `payment_id` INT NOT NULL;
ALTER TABLE `tbl_order` ADD `payment_id` INT NOT NULL AFTER `amount`;
ALTER TABLE `tbl_order` ADD `shipping_cost` DECIMAL(11,2) NOT NULL AFTER `payment_id`, ADD `tax` DECIMAL(11,2) NOT NULL AFTER `shipping_cost`;
INSERT INTO `menu` (`id`, `menu`, `master_id`, `url`, `icon`, `usage_tables`) VALUES (NULL, 'Pending Orders', '21', 'pending_orders', '', '');
ALTER TABLE `tbl_cus` ADD `email` VARCHAR(200) NOT NULL AFTER `created_at`;
ALTER TABLE `tbl_cus` ADD `country` VARCHAR(50) NOT NULL AFTER `email`;
CREATE TABLE `payment_sts` (
  `id` int(11) NOT NULL,
  `tnx_id` varchar(200) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `sts` enum('P','S','F') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `customer_id` int(11) NOT NULL,
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `q_status` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `payment_sts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `payment_sts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;