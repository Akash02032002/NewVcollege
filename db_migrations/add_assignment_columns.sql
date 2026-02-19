-- Migration: add assignment columns to applications table
-- Backup your DB before running.

-- Option A: MySQL 8.0+ (single statement)
ALTER TABLE applications
  ADD COLUMN IF NOT EXISTS `assigned_role` varchar(50) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `assigned_admin_id` int(11) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `assigned_at` datetime DEFAULT NULL;

-- Option B: For older MySQL (run each ALTER separately). If a column already exists, you'll get an error.
ALTER TABLE applications ADD COLUMN `assigned_role` varchar(50) DEFAULT NULL;
ALTER TABLE applications ADD COLUMN `assigned_admin_id` int(11) DEFAULT NULL;
ALTER TABLE applications ADD COLUMN `assigned_at` datetime DEFAULT NULL;

-- Quick verification
SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'applications' AND COLUMN_NAME IN ('assigned_role','assigned_admin_id','assigned_at');
