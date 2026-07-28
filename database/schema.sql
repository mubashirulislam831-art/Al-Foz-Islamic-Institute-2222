-- Al Foz Islamic Institute - Online Quran Academy ERP Database Schema
-- Compatible with MySQL 5.7+ and MySQL 8.0 (XAMPP & cPanel Hosting)
-- Date: June 2026

SET FOREIGN_KEY_CHECKS = 0;
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

-- 1. Users Table (Core Auth with Role-Based Access Control)
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('Super Admin', 'Admin', 'Teacher', 'Student', 'Parent') NOT NULL,
  `status` ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_role` (`role`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Admins Table
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `employee_code` VARCHAR(50) UNIQUE NOT NULL,
  `branch_assigned` VARCHAR(100) DEFAULT 'Main Islamabad',
  `whatsapp` VARCHAR(50) DEFAULT '',
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Teachers Table
CREATE TABLE `teachers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `employee_id` VARCHAR(50) UNIQUE NOT NULL,
  `name` VARCHAR(255) DEFAULT '',
  `portal_email` VARCHAR(255) DEFAULT '',
  `portal_password` VARCHAR(255) DEFAULT '',
  `phone` VARCHAR(50) DEFAULT '',
  `email` VARCHAR(255) DEFAULT '',
  `address` TEXT DEFAULT NULL,
  `emergency_contact` VARCHAR(50) DEFAULT '',
  `marital_status` VARCHAR(50) DEFAULT '',
  `nationality` VARCHAR(100) DEFAULT '',
  `father_name` VARCHAR(255) DEFAULT '',
  `gender` ENUM('Male', 'Female') NOT NULL,
  `dob` DATE DEFAULT NULL,
  `whatsapp` VARCHAR(50) NOT NULL,
  `country` VARCHAR(100) DEFAULT 'Pakistan',
  `city` VARCHAR(100) DEFAULT 'Lahore',
  `timezone` VARCHAR(50) DEFAULT 'PKT',
  `qualification` VARCHAR(255) DEFAULT 'Shahadat-ul-Alimia',
  `experience` VARCHAR(100) DEFAULT '5 Years',
  `specialization` VARCHAR(255) DEFAULT 'Tajweed & Quran Hifz',
  `joining_date` DATE NOT NULL,
  `salary` DECIMAL(10,2) NOT NULL DEFAULT 25000.00,
  `minute_rate` DECIMAL(10,2) DEFAULT 8.50,
  `allowances` DECIMAL(10,2) DEFAULT 0.00,
  `deductions` DECIMAL(10,2) DEFAULT 0.00,
  `extra_classes` DECIMAL(10,2) DEFAULT 0.00,
  `payment_method` VARCHAR(100) DEFAULT 'Bank Transfer',
  `teacher_picture` TEXT DEFAULT NULL,
  `bank_name` VARCHAR(255) DEFAULT 'Meezan Bank',
  `account_title` VARCHAR(255) DEFAULT '',
  `account_number` VARCHAR(100) DEFAULT '',
  `iban` VARCHAR(100) DEFAULT '',
  `status` ENUM('Active', 'On Leave', 'Inactive') DEFAULT 'Active',
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  INDEX `idx_teacher_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Parents Table
CREATE TABLE `parents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `relation` VARCHAR(100) DEFAULT 'Father',
  `whatsapp` VARCHAR(50) NOT NULL,
  `country` VARCHAR(100) DEFAULT 'Pakistan',
  `timezone` VARCHAR(50) DEFAULT 'PKT',
  `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
  `student_roll_no` VARCHAR(50) DEFAULT '',
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Students Table
CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `parent_id` INT DEFAULT NULL,
  `student_id` VARCHAR(50) DEFAULT '',
  `roll_no` VARCHAR(50) UNIQUE NOT NULL,
  `reg_no` VARCHAR(50) DEFAULT '',
  `name` VARCHAR(255) DEFAULT '',
  `gender` ENUM('Male', 'Female') NOT NULL,
  `dob` DATE NOT NULL,
  `age` INT DEFAULT 0,
  `whatsapp` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) DEFAULT '',
  `country` VARCHAR(100) DEFAULT 'Pakistan',
  `city` VARCHAR(100) DEFAULT 'Lahore',
  `timezone` VARCHAR(50) DEFAULT 'PKT',
  `currency` VARCHAR(10) DEFAULT 'PKR',
  `address` TEXT DEFAULT NULL,
  `admission_type` VARCHAR(100) DEFAULT 'Self',
  `father_name` VARCHAR(255) DEFAULT '',
  `teacher_name` VARCHAR(255) DEFAULT 'Unassigned',
  `course` VARCHAR(255) DEFAULT 'Quran Hifz Program',
  `class_level` VARCHAR(255) DEFAULT 'Quran Hifz Program',
  
  -- Monday schedule
  `monday_enabled` TINYINT(1) DEFAULT 0,
  `monday_time` VARCHAR(50) DEFAULT '',
  `monday_duration` VARCHAR(50) DEFAULT '30',
  `monday_pkt` VARCHAR(50) DEFAULT '',
  
  -- Tuesday schedule
  `tuesday_enabled` TINYINT(1) DEFAULT 0,
  `tuesday_time` VARCHAR(50) DEFAULT '',
  `tuesday_duration` VARCHAR(50) DEFAULT '30',
  `tuesday_pkt` VARCHAR(50) DEFAULT '',
  
  -- Wednesday schedule
  `wednesday_enabled` TINYINT(1) DEFAULT 0,
  `wednesday_time` VARCHAR(50) DEFAULT '',
  `wednesday_duration` VARCHAR(50) DEFAULT '30',
  `wednesday_pkt` VARCHAR(50) DEFAULT '',
  
  -- Thursday schedule
  `thursday_enabled` TINYINT(1) DEFAULT 0,
  `thursday_time` VARCHAR(50) DEFAULT '',
  `thursday_duration` VARCHAR(50) DEFAULT '30',
  `thursday_pkt` VARCHAR(50) DEFAULT '',
  
  -- Friday schedule
  `friday_enabled` TINYINT(1) DEFAULT 0,
  `friday_time` VARCHAR(50) DEFAULT '',
  `friday_duration` VARCHAR(50) DEFAULT '30',
  `friday_pkt` VARCHAR(50) DEFAULT '',
  
  -- Saturday schedule
  `saturday_enabled` TINYINT(1) DEFAULT 0,
  `saturday_time` VARCHAR(50) DEFAULT '',
  `saturday_duration` VARCHAR(50) DEFAULT '30',
  `saturday_pkt` VARCHAR(50) DEFAULT '',
  
  -- Sunday schedule
  `sunday_enabled` TINYINT(1) DEFAULT 0,
  `sunday_time` VARCHAR(50) DEFAULT '',
  `sunday_duration` VARCHAR(50) DEFAULT '30',
  `sunday_pkt` VARCHAR(50) DEFAULT '',
  
  `schedule` TEXT DEFAULT NULL,
  `monthly_fee` DECIMAL(10,2) NOT NULL DEFAULT 5000.00,
  `discount` DECIMAL(10,2) DEFAULT 0.00,
  `registration_fee` DECIMAL(10,2) DEFAULT 1000.00,
  `scholarship` VARCHAR(50) DEFAULT 'No',
  `joining_date` DATE NOT NULL,
  `trial_date` DATE DEFAULT NULL,
  `activation_date` DATE DEFAULT NULL,
  `fee_status` ENUM('Paid', 'Unpaid', 'Pending') DEFAULT 'Pending',
  `attendance_status` VARCHAR(50) DEFAULT 'Present',
  `status` ENUM('Active', 'Trial', 'On Leave', 'Suspended', 'Completed') DEFAULT 'Active',
  `portal_email` VARCHAR(255) DEFAULT '',
  `portal_password` VARCHAR(255) DEFAULT '',
  `makeup_rules` VARCHAR(50) DEFAULT 'Allowed',
  `attendance_trial` VARCHAR(50) DEFAULT 'Mandatory',
  
  -- JSON structures fallback
  `parent_info` LONGTEXT DEFAULT NULL,
  `academic` LONGTEXT DEFAULT NULL,
  `performance` LONGTEXT DEFAULT NULL,
  `attendance` LONGTEXT DEFAULT NULL,
  `fees` LONGTEXT DEFAULT NULL,
  `exams` LONGTEXT DEFAULT NULL,
  `documents` LONGTEXT DEFAULT NULL,
  `timeline` LONGTEXT DEFAULT NULL,
  `notes` LONGTEXT DEFAULT NULL,
  
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE SET NULL,
  INDEX `idx_student_status` (`status`),
  INDEX `idx_student_roll` (`roll_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Attendance Table (Saves permanently by Year, Month, Date)
CREATE TABLE `attendance` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `date` DATE NOT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `status` ENUM('Present', 'Absent', 'Leave', 'Late') NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_by` VARCHAR(100) DEFAULT 'System',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  INDEX `idx_att_date` (`date`),
  INDEX `idx_att_year_month` (`year`, `month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Classes Table (Active Lessons & Links)
CREATE TABLE `classes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `teacher_id` INT NOT NULL,
  `class_name` VARCHAR(255) DEFAULT 'Quran Recitation Class',
  `schedule_time` VARCHAR(100) NOT NULL,
  `status` ENUM('Scheduled', 'Completed', 'Rescheduled', 'Cancelled') DEFAULT 'Scheduled',
  `duration_minutes` INT DEFAULT 30,
  `join_link` VARCHAR(500) DEFAULT '',
  `date` DATE NOT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  INDEX `idx_class_date` (`date`),
  INDEX `idx_class_year_month` (`year`, `month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Rescheduled Classes
CREATE TABLE `rescheduled_classes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class_id` INT NOT NULL,
  `original_date` DATE NOT NULL,
  `new_date` DATE NOT NULL,
  `new_time` VARCHAR(100) NOT NULL,
  `reason` TEXT DEFAULT NULL,
  `status` ENUM('Pending Approval', 'Approved', 'Declined') DEFAULT 'Pending Approval',
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Timers Table (Real-time Session Logs)
CREATE TABLE `timers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class_id` INT NOT NULL,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME DEFAULT NULL,
  `duration_seconds` INT DEFAULT 0,
  FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Exams Table
CREATE TABLE `exams` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `exam_name` VARCHAR(255) NOT NULL,
  `marks_obtained` DECIMAL(5,2) NOT NULL,
  `total_marks` DECIMAL(5,2) NOT NULL DEFAULT 100.00,
  `exam_date` DATE NOT NULL,
  `grade` VARCHAR(10) DEFAULT 'A',
  `notes` TEXT DEFAULT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  INDEX `idx_exam_year_month` (`year`, `month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Homework Table
CREATE TABLE `homework` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `teacher_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `due_date` DATE NOT NULL,
  `status` ENUM('Assigned', 'Submitted', 'Evaluated', 'Overdue') DEFAULT 'Assigned',
  `feedback` TEXT DEFAULT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  INDEX `idx_hw_year_month` (`year`, `month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Fees Table (Invoices & Billing History)
CREATE TABLE `fees` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `invoice_number` VARCHAR(100) UNIQUE NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(10) DEFAULT 'PKR',
  `due_date` DATE NOT NULL,
  `paid_date` DATE DEFAULT NULL,
  `status` ENUM('Paid', 'Unpaid', 'Pending') DEFAULT 'Unpaid',
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  INDEX `idx_fee_year_month` (`year`, `month`),
  INDEX `idx_fee_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Salary Table (Teachers' Payroll)
CREATE TABLE `salary` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `teacher_id` INT NOT NULL,
  `slip_number` VARCHAR(100) UNIQUE NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('Paid', 'Processing', 'Unpaid') DEFAULT 'Paid',
  `paid_date` DATE DEFAULT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  INDEX `idx_sal_year_month` (`year`, `month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Progress Reports (Academic Hifz Ledger)
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
  `month` INT NOT NULL,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  INDEX `idx_prog_year_month` (`year`, `month`)
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

-- 16. Activity Logs Table (Permanent Security Log)
CREATE TABLE `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `details` TEXT DEFAULT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  INDEX `idx_act_time` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- SEED INITIAL RECORDS (Clean Setup for Mubashir Ul Islam Awan)
-- ==========================================

-- Seed Users
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`) VALUES
(1, 'Mubashir Ul Islam Awan', 'alfozislamicinstitute@gmail.com', '$2y$10$TdG71zM8I.AsPq3vCW6mdezKoD8Va4DxY18YM/rkT4sR6r42ajYTK', 'Super Admin', 'Active');

-- Seed Admins
INSERT INTO `admins` (`id`, `user_id`, `employee_code`, `branch_assigned`, `whatsapp`) VALUES
(1, 1, 'ADM-101', 'Headquarters (Islamabad)', '+92 300 1112223');

-- Seed Activity Logs
INSERT INTO `activity_logs` (`user_id`, `action`, `details`) VALUES
(1, 'System Initialization', 'Completed secure ERP system initialization with clean database.');

CREATE TABLE teacher_attendance...
CREATE TABLE IF NOT EXISTS `teacher_attendance` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `teacher_id` INT NOT NULL,
  `date` DATE NOT NULL,
  `status` ENUM('Present', 'Absent', 'Leave') NOT NULL DEFAULT 'Present',
  `login_time` DATETIME DEFAULT NULL,
  `first_class_time` DATETIME DEFAULT NULL,
  `logout_time` DATETIME DEFAULT NULL,
  `total_teaching_hours` DECIMAL(5,2) DEFAULT 0.00,
  `leave_reason` TEXT DEFAULT NULL,
  `leave_status` ENUM('Pending', 'Approved', 'Rejected') DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `idx_teacher_date` (`teacher_id`, `date`),
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
