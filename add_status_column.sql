-- ============================================================
-- Add status column to clients table (if not already present)
-- Run this once in phpMyAdmin or cPanel MySQL
-- ============================================================

ALTER TABLE `clients` 
ADD COLUMN IF NOT EXISTS `status` VARCHAR(20) NOT NULL DEFAULT 'Active';

-- Set all existing clients as Active by default
UPDATE `clients` SET `status` = 'Active' WHERE `status` IS NULL OR `status` = '';

-- Verify
SELECT id, client_name, status FROM clients ORDER BY client_name;
