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
