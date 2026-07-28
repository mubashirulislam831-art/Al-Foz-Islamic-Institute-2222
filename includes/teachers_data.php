<?php
/**
 * Al Foz Islamic Institute - Teacher Management System Data Engine
 * Integrated with Dual-Mode DB Bridge (MySQL Strict Mode)
 */
require_once __DIR__ . '/db_bridge.php';

// Sync session teachers with persistent database
$db_teachers = get_db_table('teachers');
$_SESSION['teachers'] = [];
foreach ($db_teachers as $t) {
    $id = intval($t['id']);
    if (isset($t['form_data']) && is_string($t['form_data'])) {
        $form_decoded = json_decode($t['form_data'], true);
        if (is_array($form_decoded)) {
            $t = array_merge($form_decoded, $t);
        }
    }
    $_SESSION['teachers'][$id] = $t;
}

/**
 * Get all teacher records
 */
function get_all_teachers() {
    return $_SESSION['teachers'];
}

/**
 * Get a single teacher record
 */
function get_teacher_by_id($id) {
    return $_SESSION['teachers'][$id] ?? null;
}

/**
 * Add a new teacher record
 */
function add_teacher($data) {
    $next_id = 1;
    $teachers = get_db_table('teachers');
    if (!empty($teachers)) {
        $ids = array_map(function($t) { return intval($t['id']); }, $teachers);
        $next_id = max($ids) + 1;
    }
    
    $employee_id = !empty($data['employee_id']) ? $data['employee_id'] : ('EMP-' . date('ymd') . rand(10,99));
    
    global $pdo;
    if ($pdo !== null) {
        try {
            $stmt_emp = $pdo->prepare("SELECT id FROM teachers WHERE employee_id = ? LIMIT 1");
            $stmt_emp->execute([$employee_id]);
            if ($stmt_emp->fetch()) {
                $employee_id = 'EMP-' . date('ymd') . rand(100, 999);
            }
        } catch (PDOException $ex) {}
    }

    $portal_email = !empty($data['portal_email']) ? strtolower(trim($data['portal_email'])) : '';
    if (empty($portal_email) && !empty($data['email'])) {
        $portal_email = strtolower(trim($data['email']));
    }
    if (empty($portal_email)) {
        $portal_email = 'teacher_' . time() . '_' . rand(100, 999) . '@alfoz.edu';
    }
    $raw_password = !empty($data['portal_password']) ? $data['portal_password'] : 'teacher123';
    
    // Check if user already exists with this email or create new User record
    $user_id = null;
    if ($pdo !== null && !empty($portal_email)) {
        try {
            $stmt_user = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt_user->execute([$portal_email]);
            $existing_user = $stmt_user->fetch(PDO::FETCH_ASSOC);
            if ($existing_user && !empty($existing_user['id'])) {
                $user_id = intval($existing_user['id']);
                update_db_record('users', 'id', $user_id, [
                    'name' => $data['name'] ?? 'Teacher',
                    'password' => password_hash($raw_password, PASSWORD_DEFAULT),
                    'role' => 'Teacher',
                    'status' => 'Active'
                ]);
            }
        } catch (PDOException $ex) {}
    }

    if (!$user_id) {
        $user_inserted = insert_db_record('users', [
            'name' => $data['name'] ?? 'Teacher',
            'email' => $portal_email,
            'password' => password_hash($raw_password, PASSWORD_DEFAULT),
            'role' => 'Teacher',
            'status' => 'Active'
        ]);
        if (!empty($user_inserted['id'])) {
            $user_id = intval($user_inserted['id']);
        } else if ($pdo !== null) {
            try {
                $stmt_user2 = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
                $stmt_user2->execute([$portal_email]);
                $existing_user2 = $stmt_user2->fetch(PDO::FETCH_ASSOC);
                if ($existing_user2 && !empty($existing_user2['id'])) {
                    $user_id = intval($existing_user2['id']);
                }
            } catch (PDOException $ex) {}
        }
    }

    // Handle image upload
    $teacher_picture = $data['teacher_picture'] ?? '';
    if (isset($_FILES['teacher_picture']) && $_FILES['teacher_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/teachers/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }
        $file_ext = strtolower(pathinfo($_FILES['teacher_picture']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed_exts)) {
            $file_name = 'teacher_' . time() . '_' . rand(100, 999) . '.' . $file_ext;
            $target_path = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES['teacher_picture']['tmp_name'], $target_path)) {
                $teacher_picture = '/uploads/teachers/' . $file_name;
            }
        }
    }

    $dob = (!empty($data['dob']) && $data['dob'] !== '') ? $data['dob'] : null;
    $joining_date = (!empty($data['joining_date']) && $data['joining_date'] !== '') ? $data['joining_date'] : date('Y-m-d');

    $record = [
        'user_id' => $user_id,
        'employee_id' => $employee_id,
        'name' => $data['name'] ?? '',
        'teacher_picture' => $teacher_picture,
        'father_name' => $data['father_name'] ?? '',
        'mother_name' => $data['mother_name'] ?? '',
        'gender' => $data['gender'] ?? '',
        'dob' => $dob,
        'marital_status' => $data['marital_status'] ?? '',
        'nationality' => $data['nationality'] ?? '',
        'country' => $data['country'] ?? 'Pakistan',
        'city' => $data['city'] ?? 'Lahore',
        'timezone' => $data['timezone'] ?? 'PKT',
        'passport_number' => $data['passport_number'] ?? '',
        'religion' => $data['religion'] ?? 'Islam',
        
        'phone' => $data['phone'] ?? '',
        'whatsapp' => $data['whatsapp'] ?? '',
        'email' => $data['email'] ?? '',
        'address' => $data['address'] ?? '',
        'emergency_contact' => $data['emergency_contact'] ?? '',
        
        'qualification' => $data['qualification'] ?? 'Shahadat-ul-Alimia',
        'experience' => $data['experience'] ?? '5 Years',
        'specialization' => isset($data['specialization']) ? (is_array($data['specialization']) ? implode(', ', $data['specialization']) : $data['specialization']) : 'Tajweed & Quran Hifz',
        'joining_date' => $joining_date,
        'status' => $data['status'] ?? 'Permanent',
        'languages' => isset($data['languages']) ? (is_array($data['languages']) ? implode(', ', $data['languages']) : $data['languages']) : '',
        'teaching_level' => isset($data['teaching_level']) ? (is_array($data['teaching_level']) ? implode(', ', $data['teaching_level']) : $data['teaching_level']) : 'N/A',
        'department' => $data['department'] ?? 'N/A',
        'designation' => $data['designation'] ?? 'N/A',
        'joining_type' => $data['joining_type'] ?? 'N/A',
        'employment_type' => $data['employment_type'] ?? 'N/A',
        
        'salary_type' => $data['salary_type'] ?? 'Per Student',
        'minute_rate' => floatval($data['minute_rate'] ?? 8.50),
        'salary' => floatval($data['salary'] ?? 0),
        'rate_30_3' => floatval($data['rate_30_3'] ?? 0),
        'rate_30_5' => floatval($data['rate_30_5'] ?? 0),
        'rate_45_3' => floatval($data['rate_45_3'] ?? 0),
        'rate_45_5' => floatval($data['rate_45_5'] ?? 0),
        'rate_60_3' => floatval($data['rate_60_3'] ?? 0),
        'rate_60_5' => floatval($data['rate_60_5'] ?? 0),
        'rate_90_3' => floatval($data['rate_90_3'] ?? 0),
        'rate_90_5' => floatval($data['rate_90_5'] ?? 0),
        'allowances' => floatval($data['allowances'] ?? 0),
        'deductions' => floatval($data['deductions'] ?? 0),
        'extra_classes' => floatval($data['extra_classes'] ?? 0),
        
        'bank_name' => $data['bank_name'] ?? 'Meezan Bank Limited',
        'account_title' => $data['account_title'] ?? ($data['name'] ?? ''),
        'account_number' => $data['account_number'] ?? '',
        'payment_method' => $data['payment_method'] ?? 'Bank Transfer',
        'iban' => $data['iban'] ?? '',
        'wise_email' => $data['wise_email'] ?? '',
        'mobile_wallet' => $data['mobile_wallet'] ?? '',
        
        'slots_monday' => $data['slots_monday'] ?? '',
        'slots_tuesday' => $data['slots_tuesday'] ?? '',
        'slots_wednesday' => $data['slots_wednesday'] ?? '',
        'slots_thursday' => $data['slots_thursday'] ?? '',
        'slots_friday' => $data['slots_friday'] ?? '',
        'slots_saturday' => $data['slots_saturday'] ?? '',
        'slots_sunday' => $data['slots_sunday'] ?? '',
        
        'internal_notes' => $data['internal_notes'] ?? '',
        'performance_notes' => $data['performance_notes'] ?? '',
        'warnings' => $data['warnings'] ?? '',
        'achievements' => $data['achievements'] ?? '',
        'private_notes' => $data['private_notes'] ?? '',
        
        'portal_email' => $portal_email,
        'portal_password' => !empty($raw_password) ? password_hash($raw_password, PASSWORD_DEFAULT) : '',
        'form_data' => json_encode($data)
    ];
    
    $inserted = insert_db_record('teachers', $record);
    $real_id = (!empty($inserted['id'])) ? intval($inserted['id']) : $next_id;
    $record['id'] = $real_id;
    
    $_SESSION['teachers'][$real_id] = $record;
    return $real_id;
}

/**
 * Update teacher record
 */
function update_teacher($id, $data) {
    $id = intval($id);
    $existing = $_SESSION['teachers'][$id] ?? null;
    if (!$existing) {
        $all = get_all_teachers();
        $existing = $all[$id] ?? [];
    }
    
    $teacher_picture = $data['teacher_picture'] ?? ($existing['teacher_picture'] ?? '');
    if (isset($_FILES['teacher_picture']) && $_FILES['teacher_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/teachers/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }
        $file_ext = strtolower(pathinfo($_FILES['teacher_picture']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed_exts)) {
            $file_name = 'teacher_' . time() . '_' . rand(100, 999) . '.' . $file_ext;
            $target_path = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES['teacher_picture']['tmp_name'], $target_path)) {
                $teacher_picture = '/uploads/teachers/' . $file_name;
            }
        }
    }

    $dob = (!empty($data['dob']) && $data['dob'] !== '') ? $data['dob'] : ($existing['dob'] ?? null);
    if ($dob === '') $dob = null;
    $joining_date = (!empty($data['joining_date']) && $data['joining_date'] !== '') ? $data['joining_date'] : ($existing['joining_date'] ?? date('Y-m-d'));

    $update_fields = [
        'name' => $data['name'] ?? ($existing['name'] ?? ''),
        'teacher_picture' => $teacher_picture,
        'father_name' => $data['father_name'] ?? ($existing['father_name'] ?? ''),
        'mother_name' => $data['mother_name'] ?? ($existing['mother_name'] ?? ''),
        'gender' => $data['gender'] ?? ($existing['gender'] ?? ''),
        'dob' => $dob,
        'marital_status' => $data['marital_status'] ?? ($existing['marital_status'] ?? ''),
        'nationality' => $data['nationality'] ?? ($existing['nationality'] ?? ''),
        'country' => $data['country'] ?? ($existing['country'] ?? 'Pakistan'),
        'city' => $data['city'] ?? ($existing['city'] ?? ''),
        'timezone' => $data['timezone'] ?? ($existing['timezone'] ?? 'PKT'),
        'passport_number' => $data['passport_number'] ?? ($existing['passport_number'] ?? ''),
        'religion' => $data['religion'] ?? ($existing['religion'] ?? 'Islam'),
        
        'phone' => $data['phone'] ?? ($existing['phone'] ?? ''),
        'whatsapp' => $data['whatsapp'] ?? ($existing['whatsapp'] ?? ''),
        'email' => $data['email'] ?? ($existing['email'] ?? ''),
        'address' => $data['address'] ?? ($existing['address'] ?? ''),
        'emergency_contact' => $data['emergency_contact'] ?? ($existing['emergency_contact'] ?? ''),
        
        'qualification' => $data['qualification'] ?? ($existing['qualification'] ?? ''),
        'experience' => $data['experience'] ?? ($existing['experience'] ?? ''),
        'specialization' => isset($data['specialization']) ? (is_array($data['specialization']) ? implode(', ', $data['specialization']) : $data['specialization']) : ($existing['specialization'] ?? ''),
        'joining_date' => $joining_date,
        'status' => $data['status'] ?? ($existing['status'] ?? 'Permanent'),
        'languages' => isset($data['languages']) ? (is_array($data['languages']) ? implode(', ', $data['languages']) : $data['languages']) : ($existing['languages'] ?? ''),
        'teaching_level' => isset($data['teaching_level']) ? (is_array($data['teaching_level']) ? implode(', ', $data['teaching_level']) : $data['teaching_level']) : ($existing['teaching_level'] ?? 'N/A'),
        'department' => $data['department'] ?? ($existing['department'] ?? 'N/A'),
        'designation' => $data['designation'] ?? ($existing['designation'] ?? 'N/A'),
        'joining_type' => $data['joining_type'] ?? ($existing['joining_type'] ?? 'N/A'),
        'employment_type' => $data['employment_type'] ?? ($existing['employment_type'] ?? 'N/A'),
        
        'salary_type' => $data['salary_type'] ?? ($existing['salary_type'] ?? 'Per Student'),
        'minute_rate' => floatval($data['minute_rate'] ?? ($existing['minute_rate'] ?? 8.50)),
        'salary' => floatval($data['salary'] ?? ($existing['salary'] ?? 0)),
        'rate_30_3' => floatval($data['rate_30_3'] ?? ($existing['rate_30_3'] ?? 0)),
        'rate_30_5' => floatval($data['rate_30_5'] ?? ($existing['rate_30_5'] ?? 0)),
        'rate_45_3' => floatval($data['rate_45_3'] ?? ($existing['rate_45_3'] ?? 0)),
        'rate_45_5' => floatval($data['rate_45_5'] ?? ($existing['rate_45_5'] ?? 0)),
        'rate_60_3' => floatval($data['rate_60_3'] ?? ($existing['rate_60_3'] ?? 0)),
        'rate_60_5' => floatval($data['rate_60_5'] ?? ($existing['rate_60_5'] ?? 0)),
        'rate_90_3' => floatval($data['rate_90_3'] ?? ($existing['rate_90_3'] ?? 0)),
        'rate_90_5' => floatval($data['rate_90_5'] ?? ($existing['rate_90_5'] ?? 0)),
        'allowances' => floatval($data['allowances'] ?? ($existing['allowances'] ?? 0)),
        'deductions' => floatval($data['deductions'] ?? ($existing['deductions'] ?? 0)),
        'extra_classes' => floatval($data['extra_classes'] ?? ($existing['extra_classes'] ?? 0)),
        
        'bank_name' => $data['bank_name'] ?? ($existing['bank_name'] ?? 'Meezan Bank Limited'),
        'account_title' => $data['account_title'] ?? ($existing['account_title'] ?? ''),
        'account_number' => $data['account_number'] ?? ($existing['account_number'] ?? ''),
        'payment_method' => $data['payment_method'] ?? ($existing['payment_method'] ?? 'Bank Transfer'),
        'iban' => $data['iban'] ?? ($existing['iban'] ?? ''),
        'wise_email' => $data['wise_email'] ?? ($existing['wise_email'] ?? ''),
        'mobile_wallet' => $data['mobile_wallet'] ?? ($existing['mobile_wallet'] ?? ''),
        
        'slots_monday' => $data['slots_monday'] ?? ($existing['slots_monday'] ?? ''),
        'slots_tuesday' => $data['slots_tuesday'] ?? ($existing['slots_tuesday'] ?? ''),
        'slots_wednesday' => $data['slots_wednesday'] ?? ($existing['slots_wednesday'] ?? ''),
        'slots_thursday' => $data['slots_thursday'] ?? ($existing['slots_thursday'] ?? ''),
        'slots_friday' => $data['slots_friday'] ?? ($existing['slots_friday'] ?? ''),
        'slots_saturday' => $data['slots_saturday'] ?? ($existing['slots_saturday'] ?? ''),
        'slots_sunday' => $data['slots_sunday'] ?? ($existing['slots_sunday'] ?? ''),
        
        'internal_notes' => $data['internal_notes'] ?? ($existing['internal_notes'] ?? ''),
        'performance_notes' => $data['performance_notes'] ?? ($existing['performance_notes'] ?? ''),
        'warnings' => $data['warnings'] ?? ($existing['warnings'] ?? ''),
        'achievements' => $data['achievements'] ?? ($existing['achievements'] ?? ''),
        'private_notes' => $data['private_notes'] ?? ($existing['private_notes'] ?? '')
    ];
    
    if (isset($data['portal_email'])) {
        $update_fields['portal_email'] = $data['portal_email'];
    }
    if (isset($data['portal_password']) && !empty($data['portal_password'])) {
        $update_fields['portal_password'] = password_hash($data['portal_password'], PASSWORD_DEFAULT);
    }
    
    $update_fields['form_data'] = json_encode(array_merge(is_array($existing) ? $existing : [], $data));
    
    update_db_record('teachers', 'id', $id, $update_fields);
    
    // Sync with users table
    if (isset($data['portal_email']) || (isset($data['portal_password']) && !empty($data['portal_password']))) {
        $old_email = $existing['portal_email'] ?? '';
        $new_email = $data['portal_email'] ?? $old_email;
        
        $user_updates = [];
        if (isset($data['portal_email'])) $user_updates['email'] = strtolower(trim($new_email));
        if (isset($data['name'])) $user_updates['name'] = $data['name'];
        if (isset($data['portal_password']) && !empty($data['portal_password'])) {
            $user_updates['password'] = password_hash($data['portal_password'], PASSWORD_DEFAULT);
        }
        
        if (!empty($old_email) && !empty($user_updates)) {
            update_db_record('users', 'email', $old_email, $user_updates);
        }
    }
    
    if (!isset($_SESSION['teachers'][$id])) {
        $_SESSION['teachers'][$id] = [];
    }
    $_SESSION['teachers'][$id] = array_merge($_SESSION['teachers'][$id], $update_fields);
    return true;
}
?>
