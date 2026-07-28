<?php
/**
 * Shared Parent Portal Context
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../auth/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

require_role(['Parent', 'Admin', 'Super Admin']);

$parent_name = $_SESSION['name'] ?? 'Parent';
$parent_email = $_SESSION['email'] ?? 'parent@alfoz.com';
$logged_in_user_id = $_SESSION['user_id'] ?? null;

$children = [];

global $pdo;
$parent_rec = null;

// 1. Try to find parent record by user_id
if ($pdo !== null && $logged_in_user_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM parents WHERE user_id = ? LIMIT 1");
        $stmt->execute([$logged_in_user_id]);
        $parent_rec = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}

// 2. If not found by user_id, look up by portal_email and self-heal!
if (!$parent_rec && $pdo !== null && !empty($parent_email)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM parents WHERE LOWER(portal_email) = ? LIMIT 1");
        $stmt->execute([strtolower(trim($parent_email))]);
        $parent_rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($parent_rec && $logged_in_user_id) {
            $stmt_up = $pdo->prepare("UPDATE parents SET user_id = ? WHERE id = ?");
            $stmt_up->execute([$logged_in_user_id, $parent_rec['id']]);
            $parent_rec['user_id'] = $logged_in_user_id;
        }
    } catch (PDOException $e) {}
}

// 3. Find children linked by parent_id or roll numbers
if ($parent_rec) {
    $parent_id_val = intval($parent_rec['id']);
    $student_rolls = array_map('trim', explode(',', $parent_rec['student_roll_no'] ?? ''));
    
    $all_s = get_all_students();
    foreach ($all_s as $s) {
        if (($s['status'] ?? '') === 'Deleted') continue;
        
        // Match by parent_id column or by student roll number matching the parent record
        $matches_parent_id = isset($s['parent_id']) && intval($s['parent_id']) === $parent_id_val;
        $matches_roll = in_array($s['roll_no'] ?? '', $student_rolls) || in_array($s['student_id'] ?? '', $student_rolls);
        
        if ($matches_parent_id || $matches_roll) {
            $children[$s['id']] = $s;
            
            // Self-heal: update student parent_id column in database if missing
            if (!$matches_parent_id && $pdo !== null) {
                try {
                    $stmt_up_s = $pdo->prepare("UPDATE students SET parent_id = ? WHERE id = ?");
                    $stmt_up_s->execute([$parent_id_val, $s['id']]);
                } catch (PDOException $ex) {}
            }
        }
    }
}

$selected_child_id = isset($_GET['child_id']) ? intval($_GET['child_id']) : null;
$active_child = null;

if ($selected_child_id && isset($children[$selected_child_id])) {
    $active_child = $children[$selected_child_id];
} else {
    $active_child = reset($children) ?: null;
}

$child_id_val = $active_child['id'] ?? null;
$child_roll = $active_child['roll_no'] ?? $active_child['student_id'] ?? '';

// Attendance
$all_attendance = get_db_table('attendance');
$child_attendance = array_filter($all_attendance, function($a) use ($child_id_val, $child_roll) {
    return (isset($a['student_id']) && ($a['student_id'] == $child_id_val || $a['student_id'] == $child_roll));
});

// Homework
$all_homework = get_db_table('homework');
$child_homework = array_filter($all_homework, function($h) use ($child_id_val, $child_roll) {
    return (isset($h['student_id']) && ($h['student_id'] == $child_id_val || $h['student_id'] == $child_roll));
});

// Exams
$all_exams = get_db_table('exams');
$child_exams = array_filter($all_exams, function($e) use ($child_id_val, $child_roll) {
    return (isset($e['student_id']) && ($e['student_id'] == $child_id_val || $e['student_id'] == $child_roll));
});

// Fees
$all_fees = get_db_table('fees');
$child_fees = array_filter($all_fees, function($f) use ($child_id_val, $child_roll) {
    return (isset($f['student_id']) && ($f['student_id'] == $child_id_val || $f['student_id'] == $child_roll));
});

$child_count = count($children);
