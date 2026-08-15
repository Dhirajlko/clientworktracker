-- Run this SQL in phpMyAdmin if needed to add GST credentials columns to the clients table:
ALTER TABLE `clients` ADD COLUMN `gst_username` VARCHAR(255) DEFAULT '' AFTER `gstin`;
ALTER TABLE `clients` ADD COLUMN `gst_password` VARCHAR(255) DEFAULT '' AFTER `gst_username`;
