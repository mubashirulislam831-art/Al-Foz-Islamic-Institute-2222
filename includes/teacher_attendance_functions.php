<?php
require_once __DIR__ . '/db_bridge.php';

function get_teacher_id_by_email($email) {
    $teachers = get_db_table('teachers');
    foreach ($teachers as $t) {
        if (trim(strtolower($t['email'])) === trim(strtolower($email)) || 
            trim(strtolower($t['portal_email'])) === trim(strtolower($email))) {
            return $t['id'];
        }
    }
    return null;
}

function mark_teacher_present_auto($teacher_email) {
    global $pdo;
    if ($pdo === null) return false;
    
    $teacher_id = get_teacher_id_by_email($teacher_email);
    if (!$teacher_id) return false;
    
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');
    
    // Check if already present
    $stmt = $pdo->prepare("SELECT * FROM teacher_attendance WHERE teacher_id = ? AND date = ?");
    $stmt->execute([$teacher_id, $today]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record) {
        // Update first_class_time if null
        if (empty($record['first_class_time'])) {
            $stmt = $pdo->prepare("UPDATE teacher_attendance SET first_class_time = ? WHERE id = ?");
            $stmt->execute([$now, $record['id']]);
        }
    } else {
        // Insert new record
        $stmt = $pdo->prepare("INSERT INTO teacher_attendance (teacher_id, date, status, login_time, first_class_time) VALUES (?, ?, 'Present', ?, ?)");
        $stmt->execute([$teacher_id, $today, $now, $now]);
    }
    
    return true;
}

function update_teacher_logout($teacher_email) {
    global $pdo;
    if ($pdo === null) return false;
    
    $teacher_id = get_teacher_id_by_email($teacher_email);
    if (!$teacher_id) return false;
    
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');
    
    $stmt = $pdo->prepare("SELECT * FROM teacher_attendance WHERE teacher_id = ? AND date = ?");
    $stmt->execute([$teacher_id, $today]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record && !empty($record['login_time'])) {
        $login = strtotime($record['login_time']);
        $logout = strtotime($now);
        $hours = round(($logout - $login) / 3600, 2);
        
        $stmt = $pdo->prepare("UPDATE teacher_attendance SET logout_time = ?, total_teaching_hours = ? WHERE id = ?");
        $stmt->execute([$now, $hours, $record['id']]);
    }
    
    return true;
}

function request_teacher_leave($teacher_email, $reason, $date) {
    global $pdo;
    if ($pdo === null) return false;
    
    $teacher_id = get_teacher_id_by_email($teacher_email);
    if (!$teacher_id) return false;
    
    $stmt = $pdo->prepare("SELECT * FROM teacher_attendance WHERE teacher_id = ? AND date = ?");
    $stmt->execute([$teacher_id, $date]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record) {
        $stmt = $pdo->prepare("UPDATE teacher_attendance SET status = 'Leave', leave_reason = ?, leave_status = 'Pending' WHERE id = ?");
        $stmt->execute([$reason, $record['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO teacher_attendance (teacher_id, date, status, leave_reason, leave_status) VALUES (?, ?, 'Leave', ?, 'Pending')");
        $stmt->execute([$teacher_id, $date, $reason]);
    }
    
    return true;
}
