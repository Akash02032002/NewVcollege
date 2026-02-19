-- ============================================
-- Top Colleges India - Database Schema
-- ============================================
-- Database: college
-- Created: February 17, 2026
-- ============================================

-- Create Database (if not exists)
CREATE DATABASE IF NOT EXISTS `college` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `college`;

-- ============================================
-- Students Table
-- ============================================
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Admins Table
-- ============================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'admin',
  `state` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `assigned_student_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Applications (Enquiries) Table
-- ============================================
CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `course_interest` varchar(255) DEFAULT NULL,
  `college_id` varchar(50) DEFAULT NULL,
  `college_name` varchar(255) DEFAULT NULL,
  `assigned_role` varchar(50) DEFAULT NULL,
  `assigned_admin_id` int(11) DEFAULT NULL,
  `assigned_at` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_state` (`state`),
  KEY `idx_region` (`region`),
  KEY `idx_district` (`district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Sample Data (Optional - for testing)
-- ============================================
-- Password for both test accounts: password123

-- Test Admin Account
-- Email: admin@test.com
-- Password: password123
-- Sample admin users (passwords use bcrypt hash for 'password123')
INSERT INTO `admins` (`name`, `mobile`, `email`, `password`, `role`, `state`, `region`, `district`, `assigned_student_email`) VALUES
('Super Admin','1234567890','admin@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',NULL,NULL,NULL,NULL),
('GM Maharashtra','1222333444','gm@maha.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','gm','Maharashtra',NULL,NULL,NULL),
('AGM Pune','1333444555','agm.pune@test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','agm',NULL,'Western','Pune',NULL),
('Counsellor A','1444555666','counselor@test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','counselor',NULL,NULL,NULL,'student1@example.com')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `role` = VALUES(`role`), `state` = VALUES(`state`), `region` = VALUES(`region`), `district` = VALUES(`district`), `assigned_student_email` = VALUES(`assigned_student_email`);

-- Test Student Account
-- Email: student@test.com
-- Password: password123
INSERT INTO `students` (`name`, `mobile`, `email`, `password`) VALUES
('Test Student', '0987654321', 'student@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Sample applications
INSERT INTO `applications` (`name`,`email`,`phone`,`state`,`region`,`district`,`course_interest`,`college_name`) VALUES
('Alice Student','student1@example.com','9999990001','Maharashtra','Western','Pune','B.Tech','XYZ College'),
('Bob Student','student2@example.com','9999990002','Karnataka','South','Bengaluru','MBA','ABC College'),
('Carol Student','student3@example.com','9999990003','Maharashtra','Western','Mumbai','BBA','LMN College')
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- ============================================
-- End of Schema
-- ============================================

-- ============================================
-- Migrations (safe ALTERs for existing installations)
-- Ensure `status` column exists on `applications`
-- ============================================
ALTER TABLE `applications` ADD COLUMN IF NOT EXISTS `status` varchar(20) NOT NULL DEFAULT 'pending';





