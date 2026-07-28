<?php
/**
 * Al Foz Islamic Institute - Student Management System Data Engine
 * Integrated with Dual-Mode DB Bridge (MySQL Strict Mode)
 */
require_once __DIR__ . '/db_bridge.php';

// Global conversion rates to PKR
$conversion_rates = [
    'PKR' => 1.0,
    'GBP' => 385.0,
    'USD' => 280.0,
    'CAD' => 205.0,
    'AUD' => 185.0,
    'SAR' => 74.5,
    'AED' => 76.2
];

// Helper to auto-detect currency based on country
function get_currency_by_country($country) {
    $country = trim(strtolower($country));
    switch ($country) {
        case 'united kingdom':
        case 'uk':
        case 'england':
        case 'gbp':
            return 'GBP';
        case 'usa':
        case 'united states':
        case 'united states of america':
        case 'usd':
            return 'USD';
        case 'canada':
        case 'cad':
            return 'CAD';
        case 'australia':
        case 'aud':
            return 'AUD';
        case 'saudi arabia':
        case 'saudi':
        case 'sar':
            return 'SAR';
        case 'uae':
        case 'united arab emirates':
        case 'dubai':
        case 'aed':
            return 'AED';
        case 'pakistan':
        case 'pkr':
        default:
            return 'PKR';
    }
}

// Sync session students with persistent database
$db_students = get_db_table('students');
$_SESSION['students'] = [];
foreach ($db_students as $s) {
    $id = intval($s['id']);
    
    // Reconstruct nested arrays if saved as JSON strings
    foreach (['parent_info', 'academic', 'performance', 'attendance', 'fees', 'exams', 'documents', 'timeline', 'notes', 'schedule'] as $key) {
        if (isset($s[$key]) && is_string($s[$key])) {
            $decoded = json_decode($s[$key], true);
            if ($decoded !== null) {
                $s[$key] = $decoded;
            }
        }
    }
    
    if (!isset($s['age']) && isset($s['dob'])) {
        $s['age'] = intval(date('Y')) - intval(substr($s['dob'], 0, 4));
    }
    
    if (isset($s['form_data']) && is_string($s['form_data'])) {
        $form_decoded = json_decode($s['form_data'], true);
        if (is_array($form_decoded)) {
            $s = array_merge($form_decoded, $s);
        }
    }
    
    $_SESSION['students'][$id] = $s;
}

/**
 * Get all student records
 */
function get_all_students() {
    return $_SESSION['students'];
}

/**
 * Get a single student record
 */
function get_student_by_id($id) {
    return $_SESSION['students'][$id] ?? null;
}

/**
 * Get all students linked to a specific parent email
 */
function get_students_by_parent_email($email) {
    $email = trim(strtolower($email));
    $results = [];
    foreach ($_SESSION['students'] as $s) {
        $parent_email = trim(strtolower($s['email'] ?? ''));
        $portal_email = trim(strtolower($s['portal_email'] ?? ''));
        if ($parent_email === $email || $portal_email === $email) {
            $results[] = $s;
        }
    }
    return $results;
}

/**
 * Add a new student record
 */
function add_student($data) {
    $next_id = 104;
    $students = get_db_table('students');
    if (!empty($students)) {
        $ids = array_map(function($s) { return intval($s['id']); }, $students);
        $next_id = max($ids) + 1;
    }
    
    $student_id = 'STU-' . $next_id;
    $reg_no = 'REG-2026-0' . ($next_id - 100);
    $currency = get_currency_by_country($data['country']);
    
    $record = [
        'id' => $next_id,
        'student_id' => $student_id,
        'roll_no' => $student_id,
        'reg_no' => $reg_no,
        'name' => $data['name'],
        'gender' => $data['gender'],
        'dob' => $data['dob'],
        'age' => intval(date('Y')) - intval(substr($data['dob'], 0, 4)),
        'country' => $data['country'],
        'city' => $data['city'] ?? 'Lahore',
        'timezone' => $data['timezone'] ?? 'PKT',
        'currency' => $currency,
        'whatsapp' => $data['whatsapp'] ?? '',
        'email' => $data['email'] ?? '',
        'address' => $data['address'] ?? '',
        'admission_type' => $data['admission_type'] ?? 'For Myself',
        'father_name' => $data['father_name'] ?? '',
        'teacher_name' => $data['teacher_name'] ?? '',
        'course' => $data['course'] ?? 'Quran Hifz Program',
        'joining_date' => date('Y-m-d'),
        
        // Schedule Days
        'monday_enabled' => $data['monday_enabled'] ?? false,
        'monday_time' => $data['monday_time'] ?? '',
        'monday_duration' => $data['monday_duration'] ?? '30',
        'monday_pkt' => $data['monday_pkt'] ?? '',
        'tuesday_enabled' => $data['tuesday_enabled'] ?? false,
        'tuesday_time' => $data['tuesday_time'] ?? '',
        'tuesday_duration' => $data['tuesday_duration'] ?? '30',
        'tuesday_pkt' => $data['tuesday_pkt'] ?? '',
        'wednesday_enabled' => $data['wednesday_enabled'] ?? false,
        'wednesday_time' => $data['wednesday_time'] ?? '',
        'wednesday_duration' => $data['wednesday_duration'] ?? '30',
        'wednesday_pkt' => $data['wednesday_pkt'] ?? '',
        'thursday_enabled' => $data['thursday_enabled'] ?? false,
        'thursday_time' => $data['thursday_time'] ?? '',
        'thursday_duration' => $data['thursday_duration'] ?? '30',
        'thursday_pkt' => $data['thursday_pkt'] ?? '',
        'friday_enabled' => $data['friday_enabled'] ?? false,
        'friday_time' => $data['friday_time'] ?? '',
        'friday_duration' => $data['friday_duration'] ?? '30',
        'friday_pkt' => $data['friday_pkt'] ?? '',
        'saturday_enabled' => $data['saturday_enabled'] ?? false,
        'saturday_time' => $data['saturday_time'] ?? '',
        'saturday_duration' => $data['saturday_duration'] ?? '30',
        'saturday_pkt' => $data['saturday_pkt'] ?? '',
        'sunday_enabled' => $data['sunday_enabled'] ?? false,
        'sunday_time' => $data['sunday_time'] ?? '',
        'sunday_duration' => $data['sunday_duration'] ?? '30',
        'sunday_pkt' => $data['sunday_pkt'] ?? '',
        
        'schedule' => json_encode($data['schedule'] ?? []),
        'monthly_fee' => floatval($data['monthly_fee'] ?? 0),
        'discount' => floatval($data['discount'] ?? 0),
        'registration_fee' => floatval($data['registration_fee'] ?? 0),
        'scholarship' => $data['scholarship'] ?? 'No',
        'fee_status' => $data['fee_status'] ?? 'Pending',
        'attendance_status' => 'Present',
        'status' => $data['status'] ?? 'Active',
        'portal_email' => $data['portal_email'] ?? ($data['portal_username'] ?? ''),
        'portal_password' => !empty($data['portal_password']) ? password_hash($data['portal_password'], PASSWORD_DEFAULT) : '',
        'makeup_rules' => $data['makeup_rules'] ?? 'Allowed',
        'attendance_trial' => $data['attendance_trial'] ?? 'Mandatory',
        
        'parent_info' => json_encode([
            'parent_name' => $data['parent_name'] ?? '',
            'parent_mobile' => $data['parent_mobile'] ?? '',
            'parent_whatsapp' => $data['parent_whatsapp'] ?? '',
            'parent_email' => $data['parent_email'] ?? '',
            'emergency_contact' => $data['emergency_contact'] ?? '',
            'parent_address' => $data['parent_address'] ?? '',
            'father_name' => $data['father_name'] ?? '',
            'mother_name' => $data['mother_name'] ?? '',
            'father_whatsapp' => $data['whatsapp'] ?? '',
            'father_email' => $data['email'] ?? '',
            'guardian' => 'Father'
        ]),
        'academic' => json_encode([
            'course' => $data['course'] ?? 'Quran Hifz Program',
            'completed_lessons' => '0',
            'current_lesson' => 'Lesson 1',
            'homework' => 'Review previous lesson',
            'progress_percent' => 5,
        ]),
        'performance' => json_encode([
            'attendance_score' => 95,
            'homework_score' => 85,
            'exam_score' => 80,
            'teacher_feedback' => 'Good progress, needs more practice.',
            'overall_rating' => 4.5
        ]),
        'attendance' => json_encode([
            'present' => 12,
            'absent' => 1,
            'leave' => 1,
            'makeup_classes' => 0,
            'percentage' => 92
        ]),
        'fees' => json_encode([
            'monthly_fee' => floatval($data['monthly_fee'] ?? 0),
            'paid' => 0,
            'pending' => floatval($data['monthly_fee'] ?? 0),
            'discount' => 0,
            'currency' => $currency,
            'history' => []
        ]),
        'exams' => json_encode([
            ['name' => 'Monthly Evaluation (June)', 'marks' => 85, 'percentage' => 85, 'grade' => 'A', 'result' => 'Pass'],
            ['name' => 'Tajweed Quiz 1', 'marks' => 90, 'percentage' => 90, 'grade' => 'A+', 'result' => 'Pass']
        ]),
        'documents' => json_encode([
            ['name' => 'Student Picture', 'type' => 'Image', 'date' => date('Y-m-d')],
            ['name' => 'ID Card Copy', 'type' => 'PDF', 'date' => date('Y-m-d')]
        ]),
        'timeline' => json_encode([
            ['date' => date('Y-m-d'), 'type' => 'Admission', 'title' => 'Enrolled', 'desc' => 'Admitted into Al Foz Islamic Institute.']
        ]),
        'notes' => json_encode([
            'admin' => $data['admin_notes'] ?? 'Enrollment registered.',
            'teacher' => $data['teacher_notes'] ?? 'Welcome to Al Foz!',
            'parent' => $data['parent_notes'] ?? 'Please focus on pronunciation.'
        ]),
        'form_data' => json_encode($data)
    ];

    $inserted = insert_db_record('students', $record);
    $real_id = (!empty($inserted['id'])) ? intval($inserted['id']) : $next_id;
    $record['id'] = $real_id;
    $next_id = $real_id;
    
    global $pdo;
    
    // Auto-create User for Portal Access
    $portal_email = $data['portal_email'] ?? ($data['portal_username'] ?? '');
    $raw_password = $data['portal_password'] ?? '';
    $user_id = null;
    
    if (!empty($portal_email) && !empty($raw_password)) {
        $clean_email = strtolower(trim($portal_email));
        if ($pdo !== null) {
            try {
                $stmt_u = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
                $stmt_u->execute([$clean_email]);
                $ex_u = $stmt_u->fetch(PDO::FETCH_ASSOC);
                if ($ex_u && !empty($ex_u['id'])) {
                    $user_id = intval($ex_u['id']);
                    update_db_record('users', 'id', $user_id, [
                        'name' => $data['name'] ?? 'Student',
                        'password' => password_hash($raw_password, PASSWORD_DEFAULT),
                        'role' => 'Student',
                        'status' => 'Active'
                    ]);
                }
            } catch (PDOException $ex) {}
        }
        if (!$user_id) {
            $user_inserted = insert_db_record('users', [
                'username' => 'student_' . $next_id,
                'name' => $data['name'] ?? 'Student',
                'email' => $clean_email,
                'password' => password_hash($raw_password, PASSWORD_DEFAULT),
                'role' => 'Student',
                'status' => 'Active'
            ]);
            if (!empty($user_inserted['id'])) {
                $user_id = intval($user_inserted['id']);
            }
        }
        
        // Save the user_id to the student record
        if ($user_id && $pdo !== null) {
            try {
                $stmt_up = $pdo->prepare("UPDATE students SET user_id = ? WHERE id = ?");
                $stmt_up->execute([$user_id, $real_id]);
                $record['user_id'] = $user_id;
            } catch (PDOException $ex) {}
        }
    }
    
    // Auto-create Parent Portal Account and link child if credentials are submitted
    $parent_username = $data['parent_portal_username'] ?? '';
    $parent_password = $data['parent_portal_password'] ?? '';
    $parent_name = $data['parent_name'] ?? $data['father_name'] ?? 'Parent';
    
    if (!empty($parent_username) && !empty($parent_password)) {
        $clean_p_email = strtolower(trim($parent_username));
        if (strpos($clean_p_email, '@') === false) {
            $clean_p_email .= '@alfoz.com';
        }
        
        require_once __DIR__ . '/parents_data.php';
        
        $parent_id = null;
        if ($pdo !== null) {
            try {
                $stmt_p = $pdo->prepare("SELECT id FROM parents WHERE LOWER(portal_email) = ? LIMIT 1");
                $stmt_p->execute([$clean_p_email]);
                $ex_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
                if ($ex_p && !empty($ex_p['id'])) {
                    $parent_id = intval($ex_p['id']);
                }
            } catch (PDOException $ex) {}
        }
        
        if (!$parent_id) {
            $parent_id = add_parent([
                'name' => $parent_name,
                'relation' => $data['parent_status'] ?? 'Father',
                'whatsapp' => $data['whatsapp'] ?? '',
                'country' => $data['country'] ?? 'Pakistan',
                'timezone' => $data['timezone'] ?? 'PKT',
                'status' => $data['parent_portal_status'] ?? 'Active',
                'student_roll_no' => $student_id,
                'portal_email' => $clean_p_email,
                'portal_password' => $parent_password
            ]);
        } else {
            // Update existing parent student_roll_no to include this new student roll
            $existing_parent = $_SESSION['parents'][$parent_id] ?? [];
            $existing_rolls = $existing_parent['student_roll_no'] ?? '';
            $rolls_arr = !empty($existing_rolls) ? array_map('trim', explode(',', $existing_rolls)) : [];
            if (!in_array($student_id, $rolls_arr)) {
                $rolls_arr[] = $student_id;
                $new_rolls = implode(', ', $rolls_arr);
                update_db_record('parents', 'id', $parent_id, ['student_roll_no' => $new_rolls]);
                $_SESSION['parents'][$parent_id]['student_roll_no'] = $new_rolls;
            }
        }
        
        // Link the parent_id to the student record
        if ($parent_id && $pdo !== null) {
            try {
                $stmt_up_p = $pdo->prepare("UPDATE students SET parent_id = ? WHERE id = ?");
                $stmt_up_p->execute([$parent_id, $real_id]);
                $record['parent_id'] = $parent_id;
            } catch (PDOException $ex) {}
        }
    }
    
    // Synchronize to session cache
    $_SESSION['students'][$next_id] = $record;
    return $next_id;
}

/**
 * Update student record
 */
function update_student($id, $data) {
    if (isset($_SESSION['students'][$id])) {
        $existing = $_SESSION['students'][$id];
        $country = $data['country'] ?? $existing['country'] ?? 'Pakistan';
        $currency = get_currency_by_country($country);
        $dob = $data['dob'] ?? $existing['dob'] ?? '2010-01-01';
        
        $update_fields = [
            'name' => $data['name'] ?? $existing['name'] ?? '',
            'gender' => $data['gender'] ?? $existing['gender'] ?? 'Male',
            'dob' => $dob,
            'age' => !empty($dob) ? (intval(date('Y')) - intval(substr($dob, 0, 4))) : ($existing['age'] ?? 0),
            'country' => $country,
            'city' => $data['city'] ?? $existing['city'] ?? 'Lahore',
            'timezone' => $data['timezone'] ?? $existing['timezone'] ?? 'PKT',
            'currency' => $currency,
            'whatsapp' => $data['whatsapp'] ?? $existing['whatsapp'] ?? '',
            'email' => $data['email'] ?? $existing['email'] ?? '',
            'address' => $data['address'] ?? $existing['address'] ?? '',
            'admission_type' => $data['admission_type'] ?? $existing['admission_type'] ?? 'Self',
            'father_name' => $data['father_name'] ?? $existing['father_name'] ?? '',
            'teacher_name' => $data['teacher_name'] ?? $existing['teacher_name'] ?? 'Unassigned',
            'course' => $data['course'] ?? $existing['course'] ?? 'Quran Hifz Program',
            'status' => $data['status'] ?? $existing['status'] ?? 'Active',
            'monthly_fee' => floatval($data['monthly_fee'] ?? $existing['monthly_fee'] ?? 0),
            'fee_status' => $data['fee_status'] ?? $existing['fee_status'] ?? 'Pending',
            'discount' => floatval($data['discount'] ?? $existing['discount'] ?? 0),
            'registration_fee' => floatval($data['registration_fee'] ?? $existing['registration_fee'] ?? 0),
            'scholarship' => $data['scholarship'] ?? $existing['scholarship'] ?? 'No',
            'phone' => $data['phone'] ?? $existing['phone'] ?? '',
            'parent_info' => json_encode([
                'father_name' => $data['father_name'] ?? $existing['father_name'] ?? '',
                'mother_name' => $data['mother_name'] ?? '',
                'father_whatsapp' => $data['parent_whatsapp'] ?? $data['whatsapp'] ?? $existing['whatsapp'] ?? '',
                'father_email' => $data['parent_email'] ?? $data['email'] ?? $existing['email'] ?? '',
                'father_mobile' => $data['parent_mobile'] ?? '',
                'guardian' => 'Father'
            ]),
            'notes' => json_encode([
                'admin' => $data['admin_notes'] ?? '',
                'teacher' => $data['teacher_notes'] ?? '',
                'parent' => $data['parent_notes'] ?? ''
            ]),
            'form_data' => json_encode(array_merge(is_array($existing) ? $existing : [], $data))
        ];
        
        if (isset($data['portal_email']) || isset($data['portal_username'])) {
            $update_fields['portal_email'] = !empty($data['portal_email']) ? $data['portal_email'] : ($data['portal_username'] ?? '');
        }
        if (isset($data['portal_password']) && !empty($data['portal_password'])) {
            $update_fields['portal_password'] = password_hash($data['portal_password'], PASSWORD_DEFAULT);
        }
        
        // Document paths
        if (isset($data['student_picture'])) {
            $update_fields['student_picture'] = $data['student_picture'];
        }
        if (isset($data['parent_id_doc'])) {
            $update_fields['parent_id_doc'] = $data['parent_id_doc'];
        }
        if (isset($data['student_id_doc'])) {
            $update_fields['student_id_doc'] = $data['student_id_doc'];
        }
        if (isset($data['birth_certificate'])) {
            $update_fields['birth_certificate'] = $data['birth_certificate'];
        }
        if (isset($data['school_documents'])) {
            $update_fields['school_documents'] = $data['school_documents'];
        }
        if (isset($data['other_documents'])) {
            $update_fields['other_documents'] = $data['other_documents'];
        }
        
        // Days
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach($days as $day) {
            $update_fields[$day.'_enabled'] = $data[$day.'_enabled'] ?? false;
            $update_fields[$day.'_time'] = $data[$day.'_time'] ?? '';
            $update_fields[$day.'_duration'] = $data[$day.'_duration'] ?? '30';
            $update_fields[$day.'_pkt'] = $data[$day.'_pkt'] ?? '';
        }
        
        if (isset($data['schedule'])) {
            $update_fields['schedule'] = json_encode($data['schedule']);
        }
        
        update_db_record('students', 'id', $id, $update_fields);
        
        // Sync with users table
        if (isset($data['portal_email']) || isset($data['portal_username']) || (isset($data['portal_password']) && !empty($data['portal_password']))) {
            $existing_student = $_SESSION['students'][$id] ?? [];
            $old_email = $existing_student['portal_email'] ?? '';
            $new_email = !empty($data['portal_email']) ? $data['portal_email'] : ($data['portal_username'] ?? $old_email);
            
            $user_updates = [];
            if (!empty($new_email)) $user_updates['email'] = strtolower(trim($new_email));
            if (isset($data['name'])) $user_updates['name'] = $data['name'];
            if (isset($data['portal_password']) && !empty($data['portal_password'])) {
                $user_updates['password'] = password_hash($data['portal_password'], PASSWORD_DEFAULT);
            }
            
            if (!empty($old_email) && !empty($user_updates)) {
                update_db_record('users', 'email', $old_email, $user_updates);
            } else if (!empty($new_email)) {
                insert_db_record('users', [
                    'username' => 'student_' . $id,
                    'name' => $data['name'] ?? ($existing_student['name'] ?? 'Student'),
                    'email' => strtolower(trim($new_email)),
                    'password' => password_hash($data['portal_password'] ?? '12345678', PASSWORD_DEFAULT),
                    'role' => 'Student',
                    'status' => 'Active'
                ]);
            }
        }
        
        // Sync back to session cache
        $_SESSION['students'][$id] = array_merge($_SESSION['students'][$id], $update_fields);
        return true;
    }
    return false;
}

/**
 * Perform conversion from student currency to PKR
 */
function convert_to_pkr($amount, $currency) {
    global $conversion_rates;
    $rate = $conversion_rates[$currency] ?? 1.0;
    return $amount * $rate;
}
?>
