
-- Al Foz Islamic Institute - Online Quran Academy ERP Database Schema
-- Compatible with MySQL 5.7+ and MySQL 8.0 (XAMPP & cPanel Hosting)
-- Date: July 2026

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `formulas`;
DROP TABLE IF EXISTS `login_history`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `progress_reports`;
DROP TABLE IF EXISTS `salary`;
DROP TABLE IF EXISTS `fees`;
DROP TABLE IF EXISTS `homework`;
DROP TABLE IF EXISTS `exams`;
DROP TABLE IF EXISTS `timers`;
DROP TABLE IF EXISTS `rescheduled_classes`;
DROP TABLE IF EXISTS `classes`;
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `parents`;
DROP TABLE IF EXISTS `teachers`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. Users Table
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) NOT NULL,
  `status` ENUM('Active', 'Suspended', 'Deleted') DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Admins Table
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `employee_code` VARCHAR(50) UNIQUE NOT NULL,
  `branch_assigned` VARCHAR(100) DEFAULT 'Headquarters',
  `whatsapp` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Teachers Table (Matched with teachers_data.php)
CREATE TABLE `teachers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `employee_id` VARCHAR(50) UNIQUE NOT NULL,
  `name` VARCHAR(255) DEFAULT '',
  `father_name` VARCHAR(255) DEFAULT '',
  `gender` VARCHAR(20) DEFAULT '',
  `dob` DATE DEFAULT NULL,
  `marital_status` VARCHAR(50) DEFAULT '',
  `nationality` VARCHAR(100) DEFAULT '',
  `country` VARCHAR(100) DEFAULT 'Pakistan',
  `city` VARCHAR(100) DEFAULT 'Lahore',
  `timezone` VARCHAR(50) DEFAULT 'PKT',
  `phone` VARCHAR(50) DEFAULT '',
  `whatsapp` VARCHAR(50) DEFAULT '',
  `email` VARCHAR(255) DEFAULT '',
  `address` TEXT DEFAULT NULL,
  `emergency_contact` VARCHAR(255) DEFAULT '',
  `qualification` VARCHAR(255) DEFAULT 'Shahadat-ul-Alimia',
  `experience` VARCHAR(100) DEFAULT '5 Years',
  `specialization` VARCHAR(255) DEFAULT 'Tajweed & Quran Hifz',
  `joining_date` DATE DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'Permanent',
  `minute_rate` DECIMAL(10,2) DEFAULT 8.50,
  `salary` DECIMAL(10,2) DEFAULT 45000,
  `allowances` DECIMAL(10,2) DEFAULT 0,
  `deductions` DECIMAL(10,2) DEFAULT 0,
  `extra_classes` DECIMAL(10,2) DEFAULT 0,
  `bank_name` VARCHAR(255) DEFAULT 'Meezan Bank Limited',
  `account_title` VARCHAR(255) DEFAULT '',
  `account_number` VARCHAR(100) DEFAULT '',
  `payment_method` VARCHAR(100) DEFAULT 'Bank Transfer',
  `iban` VARCHAR(100) DEFAULT '',
  `portal_email` VARCHAR(255) DEFAULT '',
  `portal_password` VARCHAR(255) DEFAULT '',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Parents Table (Matched with parents_data.php)
CREATE TABLE `parents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `name` VARCHAR(255) DEFAULT '',
  `relation` VARCHAR(100) DEFAULT 'Father',
  `whatsapp` VARCHAR(50) DEFAULT '',
  `country` VARCHAR(100) DEFAULT 'Pakistan',
  `timezone` VARCHAR(50) DEFAULT 'PKT',
  `status` VARCHAR(50) DEFAULT 'Active',
  `student_roll_no` VARCHAR(50) DEFAULT '',
  `portal_email` VARCHAR(255) DEFAULT '',
  `portal_password` VARCHAR(255) DEFAULT '',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Students Table (Matched with students_data.php)
CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` VARCHAR(50) DEFAULT NULL,
  `roll_no` VARCHAR(50) DEFAULT NULL,
  `reg_no` VARCHAR(50) DEFAULT NULL,
  `name` VARCHAR(255) DEFAULT '',
  `gender` VARCHAR(20) DEFAULT '',
  `dob` DATE DEFAULT NULL,
  `age` INT DEFAULT 0,
  `country` VARCHAR(100) DEFAULT '',
  `city` VARCHAR(100) DEFAULT 'Lahore',
  `timezone` VARCHAR(50) DEFAULT 'PKT',
  `currency` VARCHAR(10) DEFAULT 'PKR',
  `whatsapp` VARCHAR(50) DEFAULT '',
  `email` VARCHAR(255) DEFAULT '',
  `address` TEXT DEFAULT NULL,
  `admission_type` VARCHAR(100) DEFAULT 'For Myself',
  `father_name` VARCHAR(255) DEFAULT '',
  `teacher_name` VARCHAR(255) DEFAULT 'Maulana Bilal Siddique',
  `course` VARCHAR(255) DEFAULT 'Quran Hifz Program',
  `joining_date` DATE DEFAULT NULL,

  `monday_enabled` TINYINT(1) DEFAULT 0,
  `monday_time` VARCHAR(50) DEFAULT '',
  `monday_duration` VARCHAR(50) DEFAULT '30',
  `monday_pkt` VARCHAR(50) DEFAULT '',

  `tuesday_enabled` TINYINT(1) DEFAULT 0,
  `tuesday_time` VARCHAR(50) DEFAULT '',
  `tuesday_duration` VARCHAR(50) DEFAULT '30',
  `tuesday_pkt` VARCHAR(50) DEFAULT '',

  `wednesday_enabled` TINYINT(1) DEFAULT 0,
  `wednesday_time` VARCHAR(50) DEFAULT '',
  `wednesday_duration` VARCHAR(50) DEFAULT '30',
  `wednesday_pkt` VARCHAR(50) DEFAULT '',

  `thursday_enabled` TINYINT(1) DEFAULT 0,
  `thursday_time` VARCHAR(50) DEFAULT '',
  `thursday_duration` VARCHAR(50) DEFAULT '30',
  `thursday_pkt` VARCHAR(50) DEFAULT '',

  `friday_enabled` TINYINT(1) DEFAULT 0,
  `friday_time` VARCHAR(50) DEFAULT '',
  `friday_duration` VARCHAR(50) DEFAULT '30',
  `friday_pkt` VARCHAR(50) DEFAULT '',

  `saturday_enabled` TINYINT(1) DEFAULT 0,
  `saturday_time` VARCHAR(50) DEFAULT '',
  `saturday_duration` VARCHAR(50) DEFAULT '30',
  `saturday_pkt` VARCHAR(50) DEFAULT '',

  `sunday_enabled` TINYINT(1) DEFAULT 0,
  `sunday_time` VARCHAR(50) DEFAULT '',
  `sunday_duration` VARCHAR(50) DEFAULT '30',
  `sunday_pkt` VARCHAR(50) DEFAULT '',

  `schedule` JSON DEFAULT NULL,
  `monthly_fee` DECIMAL(10,2) DEFAULT 0,
  `discount` DECIMAL(10,2) DEFAULT 0,
  `registration_fee` DECIMAL(10,2) DEFAULT 0,
  `scholarship` VARCHAR(50) DEFAULT 'No',
  `fee_status` VARCHAR(50) DEFAULT 'Pending',
  `attendance_status` VARCHAR(50) DEFAULT 'Present',
  `status` VARCHAR(50) DEFAULT 'Active',
  `portal_email` VARCHAR(255) DEFAULT '',
  `portal_password` VARCHAR(255) DEFAULT '',
  `makeup_rules` VARCHAR(100) DEFAULT 'Allowed',
  `attendance_trial` VARCHAR(100) DEFAULT 'Mandatory',

  `parent_info` JSON DEFAULT NULL,
  `academic` JSON DEFAULT NULL,
  `performance` JSON DEFAULT NULL,
  `attendance` JSON DEFAULT NULL,
  `fees` JSON DEFAULT NULL,
  `exams` JSON DEFAULT NULL,
  `documents` JSON DEFAULT NULL,
  `timeline` JSON DEFAULT NULL,
  `notes` JSON DEFAULT NULL,

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 6. Attendance Table
CREATE TABLE `attendance` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `date` DATE NOT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_by` VARCHAR(100) DEFAULT 'System',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6b. Teacher Attendance Table
CREATE TABLE `teacher_attendance` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `employee_id` VARCHAR(50) NOT NULL,
  `date` DATE NOT NULL,
  `check_in` VARCHAR(50) DEFAULT '09:00 AM',
  `check_out` VARCHAR(50) DEFAULT '05:00 PM',
  `hours` DECIMAL(4,1) DEFAULT 8.0,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Present',
  `remarks` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Classes Table
CREATE TABLE `classes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `teacher_id` INT NOT NULL,
  `class_name` VARCHAR(255) NOT NULL,
  `schedule_time` VARCHAR(100) NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Scheduled',
  `duration_minutes` INT DEFAULT 30,
  `join_link` VARCHAR(500) DEFAULT '',
  `date` DATE NOT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Rescheduled Classes Table
CREATE TABLE `rescheduled_classes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  `teacher_id` INT NOT NULL,
  `original_date` DATE NOT NULL,
  `new_date` DATE NOT NULL,
  `original_time` VARCHAR(100) NOT NULL,
  `new_time` VARCHAR(100) NOT NULL,
  `reason` TEXT DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'Pending Approval',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Timers Table
CREATE TABLE `timers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class_id` INT NOT NULL,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME DEFAULT NULL,
  `duration_seconds` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Exams Table
CREATE TABLE `exams` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `exam_name` VARCHAR(255) NOT NULL,
  `marks_obtained` DECIMAL(5,2) NOT NULL,
  `total_marks` DECIMAL(5,2) NOT NULL,
  `exam_date` DATE NOT NULL,
  `grade` VARCHAR(10) DEFAULT 'A',
  `notes` TEXT DEFAULT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Homework Table
CREATE TABLE `homework` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `teacher_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `due_date` DATE NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Assigned',
  `feedback` TEXT DEFAULT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Fees Table
CREATE TABLE `fees` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `invoice_number` VARCHAR(100) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(10) DEFAULT 'PKR',
  `due_date` DATE NOT NULL,
  `paid_date` DATE DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'Unpaid',
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Salary Table
CREATE TABLE `salary` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `teacher_id` INT NOT NULL,
  `slip_number` VARCHAR(100) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Paid',
  `paid_date` DATE DEFAULT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Progress Reports Table
CREATE TABLE `progress_reports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `report_date` DATE NOT NULL,
  `surah` VARCHAR(255) DEFAULT 'Noorani Qaida',
  `lessons_learnt` VARCHAR(255) DEFAULT 'Lesson 1',
  `juz` INT DEFAULT 0,
  `grade` VARCHAR(10) DEFAULT 'A',
  `attendance_percentage` DECIMAL(5,2) DEFAULT 100.00,
  `behavior` VARCHAR(100) DEFAULT 'Excellent',
  `teacher_comments` TEXT DEFAULT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Notifications Table
CREATE TABLE `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `recipient_role` VARCHAR(100) DEFAULT 'All',
  `channels` VARCHAR(255) DEFAULT 'Portal Banner',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Activity Logs Table
CREATE TABLE `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `details` TEXT DEFAULT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Formulas Table
CREATE TABLE `formulas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `days` INT NOT NULL,
  `minutes` INT NOT NULL,
  `payout` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Login History Table
CREATE TABLE `login_history` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `role` VARCHAR(100) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `login_date` DATE NOT NULL,
  `login_time` TIME NOT NULL,
  `logout_time` TIME DEFAULT NULL,
  `ip_address` VARCHAR(255) DEFAULT NULL,
  `browser` VARCHAR(100) DEFAULT NULL,
  `device` VARCHAR(100) DEFAULT NULL,
  `os` VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- SEED INITIAL RECORDS (Clean Setup for Mubashir Ul Islam Awan)
-- ==========================================

-- Seed Users
INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `role`, `status`) VALUES
(1, 'admin', 'Mubashir Ul Islam Awan', 'alfozislamicinstitute@gmail.com', '$2y$10$TdG71zM8I.AsPq3vCW6mdezKoD8Va4DxY18YM/rkT4sR6r42ajYTK', 'Super Admin', 'Active');

-- Seed Admins
INSERT INTO `admins` (`id`, `user_id`, `employee_code`, `branch_assigned`, `whatsapp`) VALUES
(1, 1, 'ADM-101', 'Headquarters (Islamabad)', '+92 300 1112223');

-- Seed Activity Logs
INSERT INTO `activity_logs` (`user_id`, `action`, `details`) VALUES
(1, 'System Initialization', 'Completed secure ERP system initialization with clean database.');


