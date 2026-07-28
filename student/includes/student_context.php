<?php
/**
 * Shared Student Portal Context
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../auth/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

require_role(['Student', 'Admin', 'Super Admin']);

$student_name = $_SESSION['name'] ?? 'Student';
$student_email = $_SESSION['email'] ?? 'student@alfoz.com';

$all_students = get_all_students();
$student = null;
$logged_in_user_id = $_SESSION['user_id'] ?? null;
$logged_in_email = strtolower(trim($_SESSION['email'] ?? ''));

// 1. Try to find by user_id
foreach ($all_students as $s) {
    if (($s['status'] ?? '') === 'Deleted') continue;
    if (!empty($s['user_id']) && $s['user_id'] == $logged_in_user_id) {
        $student = $s;
        break;
    }
}

// 2. If not found by user_id, try to find by email and automatically link/self-heal!
if (!$student && !empty($logged_in_email)) {
    foreach ($all_students as $s) {
        if (($s['status'] ?? '') === 'Deleted') continue;
        $p_email = strtolower(trim($s['portal_email'] ?? ''));
        $e_email = strtolower(trim($s['email'] ?? ''));
        if (($p_email && $p_email === $logged_in_email) || ($e_email && $e_email === $logged_in_email)) {
            $student = $s;
            if ($logged_in_user_id) {
                global $pdo;
                if ($pdo !== null) {
                    try {
                        $stmt_link = $pdo->prepare("UPDATE students SET user_id = ? WHERE id = ?");
                        $stmt_link->execute([$logged_in_user_id, $s['id']]);
                        $student['user_id'] = $logged_in_user_id;
                        $_SESSION['students'][$s['id']]['user_id'] = $logged_in_user_id;
                    } catch (PDOException $e) {
                        error_log("Failed to self-heal student user_id linkage: " . $e->getMessage());
                    }
                }
            }
            break;
        }
    }
}

$student_id_val = $student['id'] ?? null;
$student_roll = $student['roll_no'] ?? $student['student_id'] ?? '';

// Attendance logs
$all_attendance = get_db_table('attendance');
$student_attendance = array_filter($all_attendance, function($a) use ($student_id_val, $student_roll) {
    return (isset($a['student_id']) && ($a['student_id'] == $student_id_val || $a['student_id'] == $student_roll));
});

// Homework logs
$all_homework = get_db_table('homework');
$student_homework = array_filter($all_homework, function($h) use ($student_id_val, $student_roll) {
    return (isset($h['student_id']) && ($h['student_id'] == $student_id_val || $h['student_id'] == $student_roll));
});

// Exam logs
$all_exams = get_db_table('exams');
$student_exams = array_filter($all_exams, function($e) use ($student_id_val, $student_roll) {
    return (isset($e['student_id']) && ($e['student_id'] == $student_id_val || $e['student_id'] == $student_roll));
});

// Fee invoices
$all_fees = get_db_table('fees');
$student_fees = array_filter($all_fees, function($f) use ($student_id_val, $student_roll) {
    return (isset($f['student_id']) && ($f['student_id'] == $student_id_val || $f['student_id'] == $student_roll));
});

// Days of week array
$days_of_week = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
$current_day_index = intval(date('N')) - 1;
$today_day = $days_of_week[$current_day_index];

$next_class_str = "None scheduled";
if ($student) {
    for ($i = 0; $i < 7; $i++) {
        $check_index = ($current_day_index + $i) % 7;
        $day_name = $days_of_week[$check_index];
        if (isset($student[$day_name . '_enabled']) && $student[$day_name . '_enabled']) {
            $time_val = $student[$day_name . '_time'] ?? '';
            if ($time_val) {
                $formatted_time = date('h:i A', strtotime($time_val));
                if ($i === 0) {
                    $next_class_str = "Today at " . $formatted_time;
                } elseif ($i === 1) {
                    $next_class_str = "Tomorrow at " . $formatted_time;
                } else {
                    $next_class_str = ucfirst($day_name) . " at " . $formatted_time;
                }
                break;
            }
        }
    }
}
