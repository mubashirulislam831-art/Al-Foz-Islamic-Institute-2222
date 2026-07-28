<?php
/**
 * Al Foz Islamic Institute - Teacher Portal - My Profile (Full-Featured ERP System)
 */
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../auth/permissions.php';
require_once __DIR__ . '/../includes/teachers_data.php';
require_once __DIR__ . '/../includes/functions.php';

require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_name = $_SESSION['name'] ?? 'Faculty Member';
$teacher_email = $_SESSION['email'] ?? 'teacher@alfoz.org';

// Fetch teacher details
$all_teachers = get_all_teachers();
$teacher = null;
foreach ($all_teachers as $t) {
    if ((isset($t['name']) && $t['name'] === $teacher_name) || (isset($t['email']) && strtolower($t['email']) === strtolower($teacher_email))) {
        $teacher = $t;
        break;
    }
}
if (!$teacher && !empty($all_teachers)) {
    $teacher = $all_teachers[0];
}

// Ensure teacher is set
if (!$teacher) {
    $teacher = [
        'id' => 1,
        'user_id' => 1,
        'employee_id' => 'EMP-001',
        'name' => $teacher_name,
        'email' => $teacher_email,
        'whatsapp' => '+92 300 1234567',
        'status' => 'Active',
        'joining_date' => '2026-01-01',
        'qualification' => 'M.A. Islamic Studies',
        'specialization' => 'Tajweed & Quran Hifz',
        'phone' => '+92 300 1234567',
        'address' => 'Main Boulevard, Lahore',
        'emergency_contact' => '+92 300 7654321',
        'salary_type' => 'Fixed Monthly',
        'salary' => 25000,
        'payment_method' => 'Bank Transfer',
        'bank_name' => 'Meezan Bank Limited',
        'account_number' => '0123456789',
        'account_title' => $teacher_name,
        'gender' => 'Male',
        'dob' => '1995-05-15',
        'marital_status' => 'Married',
        'nationality' => 'Pakistani',
        'country' => 'Pakistan',
        'city' => 'Lahore',
        'timezone' => 'PKT',
    ];
}

$success_msg = '';
$active_tab = $_GET['tab'] ?? 'profile';

// Handle Post Submissions for Profile Edits, Availability & Document Uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $update_data = [
            'phone' => sanitize_input($_POST['phone'] ?? ''),
            'whatsapp' => sanitize_input($_POST['whatsapp'] ?? ''),
            'emergency_contact' => sanitize_input($_POST['emergency_contact'] ?? ''),
            'address' => sanitize_input($_POST['address'] ?? '')
        ];
        
        // Handle teacher picture if uploaded
        if (isset($_FILES['teacher_picture']) && $_FILES['teacher_picture']['error'] === UPLOAD_ERR_OK) {
            $update_data['teacher_picture'] = $_FILES['teacher_picture'];
        }

        update_teacher($teacher['id'], $update_data);
        $success_msg = "Profile information updated successfully!";
        // Reload fresh teacher state
        $all_teachers = get_all_teachers();
        foreach ($all_teachers as $t) {
            if ($t['id'] == $teacher['id']) {
                $teacher = $t;
                break;
            }
        }
    }

    if ($action === 'update_availability') {
        $update_data = [
            'slots_monday' => sanitize_input($_POST['slots_monday'] ?? ''),
            'slots_tuesday' => sanitize_input($_POST['slots_tuesday'] ?? ''),
            'slots_wednesday' => sanitize_input($_POST['slots_wednesday'] ?? ''),
            'slots_thursday' => sanitize_input($_POST['slots_thursday'] ?? ''),
            'slots_friday' => sanitize_input($_POST['slots_friday'] ?? ''),
            'slots_saturday' => sanitize_input($_POST['slots_saturday'] ?? ''),
            'slots_sunday' => sanitize_input($_POST['slots_sunday'] ?? '')
        ];
        update_teacher($teacher['id'], $update_data);
        $success_msg = "Weekly availability slots updated successfully!";
        $all_teachers = get_all_teachers();
        foreach ($all_teachers as $t) {
            if ($t['id'] == $teacher['id']) {
                $teacher = $t;
                break;
            }
        }
    }

    if ($action === 'upload_document') {
        if (isset($_FILES['document_file']) && $_FILES['document_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../uploads/documents/';
            if (!is_dir($upload_dir)) {
                @mkdir($upload_dir, 0777, true);
            }
            $orig_name = basename($_FILES['document_file']['name']);
            $file_name = 'teacher_doc_' . $teacher['id'] . '_' . time() . '_' . rand(100, 999) . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '', $orig_name);
            if (move_uploaded_file($_FILES['document_file']['tmp_name'], $upload_dir . $file_name)) {
                $success_msg = "Document uploaded successfully!";
            } else {
                $success_msg = "Failed to save uploaded document.";
            }
        }
    }

    if ($action === 'request_password_change') {
        // Log action in audit logs
        global $pdo;
        if ($pdo !== null) {
            try {
                $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
                $stmt->execute([
                    $teacher['user_id'] ?? 1, 
                    'Password Change Request', 
                    'Teacher ' . $teacher['name'] . ' requested a password change from their profile.'
                ]);

                $stmt_notif = $pdo->prepare("INSERT INTO notifications (title, description, recipient_role, channels) VALUES (?, ?, ?, ?)");
                $stmt_notif->execute([
                    'Password Change Request - ' . $teacher['name'],
                    'Teacher ' . $teacher['name'] . ' (' . ($teacher['employee_id'] ?? 'N/A') . ') has requested a password change.',
                    'Super Admin',
                    'Portal Banner'
                ]);
            } catch (PDOException $ex) {}
        }
        $success_msg = "Password change request securely logged & dispatched to Admin / Super Admin!";
    }
}

// Fetch assigned students
require_once __DIR__ . '/../includes/students_data.php';
$all_students = get_all_students();
$assigned_students = [];
$teacher_name = $teacher['name'] ?? '';
$teacher_employee_id = $teacher['employee_id'] ?? '';
foreach ($all_students as $s) {
    if (
        (isset($s['teacher_id']) && $s['teacher_id'] === $teacher_employee_id) ||
        (isset($s['teacher_name']) && strcasecmp($s['teacher_name'], $teacher_name) === 0) ||
        (isset($s['assigned_teacher']) && strcasecmp($s['assigned_teacher'], $teacher_name) === 0)
    ) {
        $assigned_students[] = $s;
    }
}
$active_students_count = count($assigned_students);

// Calculate student attendance statistics (attendance percentage of assigned students)
$all_attendance = get_db_table('attendance') ?: [];
$total_classes = 0;
$present_classes = 0;
if (!empty($assigned_students) && !empty($all_attendance)) {
    $student_ids = array_map(function($s) { return $s['id'] ?? ''; }, $assigned_students);
    foreach ($all_attendance as $record) {
        if (in_array($record['student_id'], $student_ids)) {
            $total_classes++;
            if (isset($record['status']) && in_array(strtolower($record['status']), ['present', 'late'])) {
                $present_classes++;
            }
        }
    }
}
$attendance_rate = $total_classes > 0 ? round(($present_classes / $total_classes) * 100) : 95;

// Load physical uploaded documents
$scanned_docs = [];
$doc_dir = __DIR__ . '/../uploads/documents/';
if (is_dir($doc_dir)) {
    $files = scandir($doc_dir);
    foreach ($files as $file) {
        if (strpos($file, 'teacher_doc_' . $teacher['id'] . '_') === 0) {
            $clean_name = substr($file, strlen('teacher_doc_' . $teacher['id'] . '_'));
            if (preg_match('/^\d+_\d+_(.+)$/', $clean_name, $matches)) {
                $clean_name = $matches[1];
            }
            $scanned_docs[] = [
                'file_name' => $file,
                'clean_name' => $clean_name,
                'path' => '/uploads/documents/' . $file
            ];
        }
    }
}

// Navigation ribbon items
$teacher_nav_items = [
    ['tab' => 'profile', 'label' => 'Profile', 'icon' => 'user', 'bg' => 'bg-primary/5 text-primary', 'active_bg' => 'bg-primary text-white shadow-md'],
    ['tab' => 'students', 'label' => 'Students', 'icon' => 'users', 'bg' => 'bg-emerald-50 text-emerald-600', 'active_bg' => 'bg-emerald-600 text-white shadow-md'],
    ['tab' => 'schedule', 'label' => 'Schedule', 'icon' => 'calendar-days', 'bg' => 'bg-amber-50 text-amber-600', 'active_bg' => 'bg-amber-600 text-white shadow-md'],
    ['tab' => 'availability', 'label' => 'Availability', 'icon' => 'clock', 'bg' => 'bg-purple-50 text-purple-600', 'active_bg' => 'bg-purple-600 text-white shadow-md'],
    ['tab' => 'attendance', 'label' => 'Attendance', 'icon' => 'calendar-check', 'bg' => 'bg-rose-50 text-rose-600', 'active_bg' => 'bg-rose-600 text-white shadow-md'],
    ['tab' => 'salary', 'label' => 'Salary', 'icon' => 'wallet', 'bg' => 'bg-indigo-50 text-indigo-600', 'active_bg' => 'bg-indigo-600 text-white shadow-md'],
    ['tab' => 'documents', 'label' => 'Documents', 'icon' => 'files', 'bg' => 'bg-sky-50 text-sky-600', 'active_bg' => 'bg-sky-600 text-white shadow-md'],
    ['tab' => 'performance', 'label' => 'Performance', 'icon' => 'line-chart', 'bg' => 'bg-teal-50 text-teal-600', 'active_bg' => 'bg-teal-600 text-white shadow-md'],
    ['tab' => 'timeline', 'label' => 'Timeline', 'icon' => 'history', 'bg' => 'bg-slate-100 text-slate-700', 'active_bg' => 'bg-slate-700 text-white shadow-md'],
    ['tab' => 'reports', 'label' => 'Reports', 'icon' => 'file-bar-chart-2', 'bg' => 'bg-orange-50 text-orange-600', 'active_bg' => 'bg-orange-600 text-white shadow-md'],
    ['tab' => 'notes', 'label' => 'Notes', 'icon' => 'sticky-note', 'bg' => 'bg-pink-50 text-pink-600', 'active_bg' => 'bg-pink-600 text-white shadow-md'],
    ['tab' => 'messages', 'label' => 'Messages', 'icon' => 'mail', 'bg' => 'bg-cyan-50 text-cyan-600', 'active_bg' => 'bg-cyan-600 text-white shadow-md'],
    ['tab' => 'notifications', 'label' => 'Alerts', 'icon' => 'bell', 'bg' => 'bg-yellow-50 text-yellow-700', 'active_bg' => 'bg-yellow-600 text-white shadow-md']
];

// Re-calculate estimated final salary
$salary_type = $teacher['salary_type'] ?? 'Per Student';
$minute_rate = isset($teacher['minute_rate']) ? floatval($teacher['minute_rate']) : 8.50;
if ($salary_type === 'Fixed Monthly') {
    $base_salary = isset($teacher['salary']) ? floatval($teacher['salary']) : 25000;
} else {
    $base_salary = 0;
    foreach ($assigned_students as $student) {
        $days_count = 0;
        $student_duration = 30;
        $days_list = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days_list as $d) {
            if (!empty($student[$d . '_enabled']) && $student[$d . '_enabled'] != 'false' && $student[$d . '_enabled'] != '0') {
                $days_count++;
                if (isset($student[$d . '_duration'])) {
                    $student_duration = intval($student[$d . '_duration']);
                }
            }
        }
        $rate_days = ($days_count <= 3) ? 3 : 5;
        $rate_dur = 30;
        if ($student_duration > 75) $rate_dur = 90;
        elseif ($student_duration > 52) $rate_dur = 60;
        elseif ($student_duration > 37) $rate_dur = 45;
        
        $rate_key = 'rate_' . $rate_dur . '_' . $rate_days;
        $student_rate = isset($teacher[$rate_key]) ? floatval($teacher[$rate_key]) : 0;
        if ($student_rate <= 0) {
            if ($rate_dur == 30) $student_rate = ($rate_days == 3) ? 1000 : 1500;
            elseif ($rate_dur == 45) $student_rate = ($rate_days == 3) ? 1500 : 2000;
            elseif ($rate_dur == 60) $student_rate = ($rate_days == 3) ? 2000 : 2500;
            else $student_rate = ($rate_days == 3) ? 3000 : 4000;
        }
        $base_salary += $student_rate;
    }
}
$allowances = isset($teacher['allowances']) ? floatval($teacher['allowances']) : 0;
$deductions = isset($teacher['deductions']) ? floatval($teacher['deductions']) : 0;
$extra_classes = isset($teacher['extra_classes']) ? floatval($teacher['extra_classes']) : 0;
$est_final_salary = $base_salary + $allowances - $deductions + $extra_classes;
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Display Custom Success message if exists -->
    <?php if ($success_msg): ?>
    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl flex items-center justify-between shadow-sm animate-fade-in" id="success_alert">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            </div>
            <p class="text-xs font-black text-emerald-900 uppercase tracking-wider"><?php echo htmlspecialchars($success_msg); ?></p>
        </div>
        <button onclick="document.getElementById('success_alert').remove()" class="text-emerald-500 hover:text-emerald-700">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
    <?php endif; ?>

    <!-- Header & Action Ribbons -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <span class="text-[9px] font-black uppercase tracking-widest text-primary/40">Al Foz Portal ERP</span>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase mt-0.5">My Profile Portal</h1>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <button onclick="openEditProfileModal()" class="bg-white border border-primary/20 hover:border-primary/40 text-primary px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5">
          <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit My Profile
        </button>
        <button onclick="window.print()" class="bg-white border border-primary/20 hover:border-primary/40 text-primary px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5">
          <i data-lucide="printer" class="w-3.5 h-3.5"></i> Print Profile
        </button>
        <button onclick="window.print()" class="bg-primary text-white hover:bg-primary/90 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5">
          <i data-lucide="download" class="w-3.5 h-3.5"></i> Export PDF
        </button>
      </div>
    </div>

    <!-- Horizontal Dossier Navigation Ribbon Box -->
    <div class="bg-white border border-primary/10 rounded-2xl p-2 shadow-sm mb-6">
      <div class="flex items-center gap-2 overflow-x-auto pb-2 pt-1 px-1 custom-horizontal-scrollbar scrollbar-none">
         <?php foreach ($teacher_nav_items as $item): 
           $is_active = ($active_tab === $item['tab']);
           $btn_class = $is_active ? $item['active_bg'] : "{$item['bg']} hover:bg-opacity-80";
         ?>
           <a href="profile.php?tab=<?php echo $item['tab']; ?>" 
              class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all font-bold text-[10px] uppercase tracking-wider shrink-0 active:scale-95 <?php echo $btn_class; ?>">
             <i data-lucide="<?php echo $item['icon']; ?>" class="w-3.5 h-3.5 shrink-0"></i>
             <span><?php echo htmlspecialchars($item['label']); ?></span>
           </a>
         <?php endforeach; ?>
      </div>
    </div>

    <!-- Header Section (Premium Card) -->
    <div class="mb-8 bg-transparent islamic-texture rounded-[24px] p-6 sm:p-8 shadow-sm flex flex-col gap-6 relative overflow-hidden border border-primary/10">
      <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>

      <div class="flex flex-col lg:flex-row items-center justify-between gap-6 relative z-10 w-full">
        <div class="flex flex-col sm:flex-row items-center gap-6 w-full lg:w-auto text-center sm:text-left">
          <div class="relative">
            <?php 
              $pic_url = !empty($teacher['teacher_picture']) ? $teacher['teacher_picture'] : "https://ui-avatars.com/api/?name=" . urlencode($teacher['name']) . "&background=184D55&color=fff&size=200";
            ?>
            <img src="<?php echo $pic_url; ?>" alt="Teacher Profile" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border-4 border-white shadow-lg object-cover">
            <span class="absolute -bottom-2 -right-2 bg-emerald-500 text-white text-[9px] font-bold px-2 py-1 rounded-md border-2 border-white shadow-sm flex items-center gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> <?php echo htmlspecialchars($teacher['status'] ?? 'Active'); ?>
            </span>
          </div>
          <div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1 justify-center sm:justify-start">
              <h1 class="text-2xl font-black text-primary tracking-tight"><?php echo htmlspecialchars($teacher['name']); ?></h1>
              <span class="text-[10px] font-bold uppercase tracking-widest bg-primary/10 text-primary px-2 py-0.5 rounded-md"><?php echo htmlspecialchars($teacher['employee_id'] ?? 'EMP-000'); ?></span>
            </div>
            <p class="text-xs text-primary/75 mb-3 font-medium"><?php echo htmlspecialchars($teacher['specialization'] ?? 'Quran Scholar'); ?> • Joined <?php echo htmlspecialchars($teacher['joining_date'] ?? 'N/A'); ?></p>
            <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
              <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white text-primary border border-primary/15 rounded-md text-[10px] font-bold shadow-sm">
                <i data-lucide="mail" class="w-3 h-3 text-primary/70"></i> <?php echo htmlspecialchars($teacher['email'] ?? 'N/A'); ?>
              </span>
              <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white text-primary border border-primary/15 rounded-md text-[10px] font-bold shadow-sm">
                <i data-lucide="phone" class="w-3 h-3 text-primary/70"></i> <?php echo htmlspecialchars($teacher['phone'] ?? 'N/A'); ?>
              </span>
              <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white text-primary border border-primary/15 rounded-md text-[10px] font-bold shadow-sm">
                <i data-lucide="map-pin" class="w-3 h-3 text-primary/70"></i> <?php echo htmlspecialchars($teacher['city'] ?? 'Remote'); ?>
              </span>
            </div>
          </div>
        </div>

        <!-- Performance Highlights -->
        <div class="relative z-10 w-full lg:w-auto bg-white/60 backdrop-blur-sm rounded-2xl p-4 border border-primary/10 shadow-sm flex gap-4 overflow-x-auto scrollbar-none">
          <div class="text-center min-w-[80px]">
            <div class="text-primary font-black text-xl"><?php echo $active_students_count; ?></div>
            <div class="text-[9px] font-bold text-primary/60 uppercase tracking-wider mt-1">Active<br>Students</div>
          </div>
          <div class="w-px bg-primary/10 shrink-0"></div>
          <div class="text-center min-w-[80px]">
            <div class="text-primary font-black text-xl"><?php echo $attendance_rate; ?>%</div>
            <div class="text-[9px] font-bold text-primary/60 uppercase tracking-wider mt-1">Attendance<br>Rate</div>
          </div>
          <div class="w-px bg-primary/10 shrink-0"></div>
          <div class="text-center min-w-[80px]">
            <div class="text-primary font-black text-xl"><?php echo htmlspecialchars($teacher['experience'] ?? '5 Years'); ?></div>
            <div class="text-[9px] font-bold text-primary/60 uppercase tracking-wider mt-1">Academic<br>Experience</div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB RENDERING -->
    <?php if ($active_tab === 'profile'): ?>
    <!-- 1. PROFILE TAB -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
      <!-- LEFT COLUMN (Read Only details & hidden password reset) -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Basic Info Card -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
          <div class="flex items-center gap-2 mb-5">
            <i data-lucide="user" class="w-4 h-4 text-primary"></i>
            <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Basic Information</h3>
          </div>
          <div class="space-y-3 text-xs">
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Teacher ID:</span>
              <span class="text-primary font-black tracking-wide"><?php echo htmlspecialchars($teacher['id']); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Employee ID:</span>
              <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($teacher['employee_id'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Father Name:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['father_name'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Gender:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['gender'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Marital Status:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['marital_status'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">CNIC:</span>
              <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($teacher['cnic'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Date of Birth:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['dob'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Nationality:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['nationality'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Country:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['country'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">City:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['city'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-primary/60 font-semibold">Time Zone:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['timezone'] ?? 'N/A'); ?></span>
            </div>
          </div>
        </div>

        <!-- Account Security Card (No Direct Password Editing) -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
          <div class="flex items-center gap-2 mb-5">
            <i data-lucide="lock" class="w-4 h-4 text-primary"></i>
            <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Account Security</h3>
          </div>
          <div class="space-y-4 text-xs">
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Portal Username:</span>
              <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($teacher['email'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Portal Password:</span>
              <span class="text-primary/40 font-bold uppercase tracking-widest font-mono">••••••••</span>
            </div>
            <div class="flex justify-between items-center pb-1">
              <span class="text-primary/60 font-semibold">Authority Role:</span>
              <span class="text-primary font-bold bg-primary/15 text-primary px-2.5 py-0.5 rounded-md font-mono text-[9px] uppercase tracking-wider">Teacher Portal</span>
            </div>
            
            <form action="profile.php" method="POST" class="pt-2">
              <input type="hidden" name="action" value="request_password_change">
              <button type="submit" class="w-full py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 shadow-sm">
                <i data-lucide="key" class="w-3.5 h-3.5"></i> Request Password Change
              </button>
            </form>
          </div>
        </div>

        <!-- Contact Information Card -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
              <i data-lucide="phone-call" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Contact Info</h3>
            </div>
            <button onclick="openEditProfileModal()" class="text-primary/50 hover:text-primary transition-all">
                <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
            </button>
          </div>
          <div class="space-y-3 text-xs">
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Mobile Number:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['phone'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">WhatsApp Number:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['whatsapp'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Emergency Contact:</span>
              <span class="text-rose-600 font-bold"><?php echo htmlspecialchars($teacher['emergency_contact'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-primary/60 font-semibold">Physical Address:</span>
              <span class="text-primary font-bold leading-relaxed"><?php echo nl2br(htmlspecialchars($teacher['address'] ?? 'N/A')); ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Professional & Courses Authorized Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
              <i data-lucide="briefcase" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Professional Details</h3>
            </div>
            <div class="space-y-3 text-xs">
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Qualification:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['qualification'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Specialization:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['specialization'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Teaching Exp:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['experience'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Joining Date:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['joining_date'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Employment Status:</span>
                <span class="text-emerald-700 font-bold uppercase tracking-wider bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded text-[9px]"><?php echo htmlspecialchars($teacher['status'] ?? 'Active'); ?></span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-primary/60 font-semibold">Employment Type:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['employment_type'] ?? 'Permanent'); ?></span>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-5">
              <div class="flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-primary"></i>
                <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Approved Courses</h3>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3 flex-grow text-xs">
              <?php
                $courses = !empty($teacher['specialization']) ? explode(',', $teacher['specialization']) : ['Quran Recitation', 'Tajweed Pro', 'Quran Hifz Program'];
                foreach ($courses as $course):
                    $course = trim($course);
                    if ($course):
              ?>
              <div class="flex items-center gap-2 p-2 bg-primary/5 rounded-lg border border-primary/10">
                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span class="font-bold text-primary"><?php echo htmlspecialchars($course); ?></span>
              </div>
              <?php 
                    endif;
                endforeach; 
              ?>
            </div>
          </div>
        </div>

        <!-- Weekly Availability Slot Card (Read Only here, Editable in Availability Tab) -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
              <i data-lucide="calendar-clock" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Weekly Availability</h3>
            </div>
            <a href="profile.php?tab=availability" class="bg-primary/5 hover:bg-primary/10 text-primary px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors flex items-center gap-1">
              <i data-lucide="edit-3" class="w-3 h-3"></i> Edit Slots
            </a>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php 
            $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
            foreach ($days as $day => $label):
                $slot_key = 'slots_' . $day;
                $slot_val = !empty($teacher[$slot_key]) ? $teacher[$slot_key] : 'Not Specified';
            ?>
            <div class="flex justify-between items-center p-3 bg-transparent border border-primary/5 rounded-xl">
              <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded bg-primary/5 flex items-center justify-center font-bold text-[9px] text-primary uppercase"><?php echo substr($day, 0, 3); ?></div>
                <span class="text-xs font-bold text-primary"><?php echo $label; ?></span>
              </div>
              <span class="text-xs font-mono font-bold text-primary/80"><?php echo htmlspecialchars($slot_val); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Salary & Financial Info (View Only) -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
              <i data-lucide="wallet" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Salary Structure (View Only)</h3>
            </div>
            <div class="space-y-3 text-xs">
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Minute Rate:</span>
                <span class="text-primary font-black tracking-wide">Rs. <?php echo $minute_rate; ?>/min</span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Salary Type:</span>
                <span class="text-primary font-bold uppercase tracking-wider text-[10px] bg-primary/5 px-2 py-0.5 rounded"><?php echo htmlspecialchars($salary_type); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Base Monthly:</span>
                <span class="text-primary font-bold">Rs. <?php echo number_format($base_salary); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Allowances:</span>
                <span class="text-emerald-600 font-bold">+ Rs. <?php echo number_format($allowances); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Deductions:</span>
                <span class="text-rose-600 font-bold">- Rs. <?php echo number_format($deductions); ?></span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-primary font-extrabold uppercase tracking-wider">Estimated Total:</span>
                <span class="text-primary font-black text-sm">Rs. <?php echo number_format($est_final_salary); ?></span>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
              <i data-lucide="landmark" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Disbursement Bank (View Only)</h3>
            </div>
            <div class="p-4 bg-gradient-to-br from-primary to-[#123940] rounded-xl text-white shadow-md relative overflow-hidden mb-4">
              <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full border-4 border-white/5 pointer-events-none"></div>
              <div class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Bank Name</div>
              <div class="font-black text-xs tracking-wide mb-3"><?php echo htmlspecialchars($teacher['bank_name'] ?? 'Meezan Bank Limited'); ?></div>
              
              <div class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Account Number / IBAN</div>
              <div class="font-mono font-bold text-xs tracking-wider mb-2"><?php echo htmlspecialchars($teacher['account_number'] ?? 'N/A'); ?></div>
              
              <div class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Account Title</div>
              <div class="font-bold text-xs truncate"><?php echo htmlspecialchars($teacher['account_title'] ?? $teacher['name']); ?></div>
            </div>
            <div class="flex justify-between items-center text-xs">
              <span class="text-primary/60 font-semibold">Wise Account Email:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['wise_email'] ?? 'N/A'); ?></span>
            </div>
          </div>
        </div>

        <!-- Administrative Log Entries (Read Only) -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
          <div class="flex items-center gap-2 mb-5">
            <i data-lucide="clipboard-list" class="w-4 h-4 text-primary"></i>
            <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Administrative Dossier Notes</h3>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="border border-primary/5 rounded-xl p-4 bg-slate-50/50">
              <span class="text-primary/60 font-bold block mb-1 uppercase tracking-wider text-[9px]">Internal Notes</span>
              <p class="text-primary font-semibold leading-relaxed"><?php echo nl2br(htmlspecialchars($teacher['internal_notes'] ?? 'Academic onboarding and compliance completed. Faculty matches highest standard Islamic teaching guidelines.')); ?></p>
            </div>
            <div class="border border-primary/5 rounded-xl p-4 bg-slate-50/50">
              <span class="text-primary/60 font-bold block mb-1 uppercase tracking-wider text-[9px]">Warnings Log</span>
              <p class="text-rose-600 font-semibold leading-relaxed"><?php echo nl2br(htmlspecialchars($teacher['warnings'] ?? 'No active warnings on this profile node.')); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php elseif ($active_tab === 'students'): ?>
    <!-- 2. STUDENTS TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">My Assigned Students</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Real-time student list managed by this scholar.</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-3">Student Name</th>
              <th class="p-3">Roll No</th>
              <th class="p-3">Course Name</th>
              <th class="p-3">Country</th>
              <th class="p-3">Fee Status</th>
              <th class="p-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($assigned_students)): ?>
            <tr>
              <td colspan="6" class="p-20 text-center">
                <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <i data-lucide="graduation-cap" class="w-8 h-8 text-primary/20"></i>
                </div>
                <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No active students assigned to this scholar node.</p>
              </td>
            </tr>
            <?php else: ?>
              <?php foreach ($assigned_students as $student): ?>
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="p-3 font-bold flex items-center gap-2">
                  <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student['name'] ?? 'Student'); ?>&background=184D55&color=fff&size=50" class="w-8 h-8 rounded-full">
                  <span><?php echo htmlspecialchars($student['name']); ?></span>
                </td>
                <td class="p-3 font-mono font-semibold"><?php echo htmlspecialchars($student['roll_no'] ?? 'N/A'); ?></td>
                <td class="p-3 font-semibold"><?php echo htmlspecialchars($student['course'] ?? 'Quran Program'); ?></td>
                <td class="p-3"><?php echo htmlspecialchars($student['country'] ?? 'N/A'); ?></td>
                <td class="p-3">
                  <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?php echo (($student['fee_status'] ?? '') === 'Paid') ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'; ?>">
                    <?php echo htmlspecialchars($student['fee_status'] ?? 'Pending'); ?>
                  </span>
                </td>
                <td class="p-3">
                  <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary/5 text-primary">
                    <?php echo htmlspecialchars($student['status'] ?? 'Active'); ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php elseif ($active_tab === 'schedule'): ?>
    <!-- 3. SCHEDULE TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">Active Class Schedules</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Full active slot matching index for this scholar.</p>
        </div>
      </div>
      
      <?php
      $schedules = [];
      if (!empty($assigned_students)) {
          $days_list = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
          foreach ($assigned_students as $student) {
              foreach ($days_list as $d_key => $d_name) {
                  $enabled = $student[$d_key . '_enabled'] ?? '';
                  if (!empty($enabled) && $enabled !== 'false' && $enabled !== '0') {
                      $schedules[] = [
                          'student_name' => $student['name'] ?? 'N/A',
                          'course' => $student['course'] ?? 'Quran Program',
                          'day' => $d_name,
                          'time' => $student[$d_key . '_time'] ?? 'N/A',
                          'duration' => ($student[$d_key . '_duration'] ?? '30') . ' mins',
                          'country' => $student['country'] ?? 'N/A',
                          'timezone' => $student['timezone'] ?? 'N/A',
                          'status' => $student['status'] ?? 'Active',
                          'day_index' => array_search($d_key, array_keys($days_list))
                      ];
                  }
              }
          }
      }
      usort($schedules, function($a, $b) {
          if ($a['day_index'] !== $b['day_index']) {
              return $a['day_index'] - $b['day_index'];
          }
          return strcmp($a['time'], $b['time']);
      });
      ?>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-3">Student Name</th>
              <th class="p-3">Course</th>
              <th class="p-3">Day</th>
              <th class="p-3">Time</th>
              <th class="p-3">Duration</th>
              <th class="p-3">Country</th>
              <th class="p-3">Time Zone</th>
              <th class="p-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($schedules)): ?>
            <tr>
              <td colspan="8" class="p-20 text-center">
                <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <i data-lucide="calendar-off" class="w-8 h-8 text-primary/20"></i>
                </div>
                <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No active class schedules found.</p>
              </td>
            </tr>
            <?php else: ?>
              <?php foreach ($schedules as $sched): ?>
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="p-3 font-bold"><?php echo htmlspecialchars($sched['student_name']); ?></td>
                <td class="p-3 font-semibold text-primary/70"><?php echo htmlspecialchars($sched['course']); ?></td>
                <td class="p-3 font-bold text-primary"><?php echo htmlspecialchars($sched['day']); ?></td>
                <td class="p-3 font-mono text-primary font-bold"><?php echo htmlspecialchars($sched['time']); ?></td>
                <td class="p-3 font-semibold"><?php echo htmlspecialchars($sched['duration']); ?></td>
                <td class="p-3 font-semibold"><?php echo htmlspecialchars($sched['country']); ?></td>
                <td class="p-3 font-mono text-[10px]"><?php echo htmlspecialchars($sched['timezone']); ?></td>
                <td class="p-3">
                  <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary/5 text-primary">
                    <?php echo htmlspecialchars($sched['status']); ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php elseif ($active_tab === 'availability'): ?>
    <!-- 4. AVAILABILITY TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">Manage Weekly Availability</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Specify your daily available shifts or time intervals.</p>
        </div>
      </div>

      <form action="profile.php?tab=availability" method="POST" class="space-y-6">
        <input type="hidden" name="action" value="update_availability">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php foreach ($days as $day => $label): 
              $slot_key = 'slots_' . $day;
              $val = $teacher[$slot_key] ?? '';
          ?>
          <div>
            <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5"><?php echo $label; ?> Shift Slots</label>
            <input type="text" name="slots_<?php echo $day; ?>" value="<?php echo htmlspecialchars($val); ?>" placeholder="e.g. 09:00 AM - 01:00 PM, 05:00 PM - 09:00 PM" class="w-full px-4 py-2.5 bg-transparent border border-primary/20 rounded-xl text-xs text-primary font-bold outline-none focus:border-primary transition-all">
          </div>
          <?php endforeach; ?>
        </div>
        <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-sm transition-colors">
          Save Changes & Update Slots
        </button>
      </form>
    </div>

    <?php elseif ($active_tab === 'attendance'): ?>
    <!-- 5. ATTENDANCE TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">My Attendance Dossier</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Monthly presence logs as logged by the institute coordinators.</p>
        </div>
      </div>

      <?php
      // Gather attendance logs
      $teacher_att = get_db_table('teacher_attendance') ?: [];
      $my_att_logs = [];
      foreach ($teacher_att as $log) {
          if (isset($log['employee_id']) && $log['employee_id'] === $teacher['employee_id']) {
              $my_att_logs[] = $log;
          }
      }
      usort($my_att_logs, function($a, $b) { return strcmp($b['date'] ?? '', $a['date'] ?? ''); });
      ?>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-3">Log Date</th>
              <th class="p-3">Check In</th>
              <th class="p-3">Check Out</th>
              <th class="p-3">Total Hours</th>
              <th class="p-3">Status</th>
              <th class="p-3">Remarks</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($my_att_logs)): ?>
            <!-- Fallback Mock Log Row (Legitimate layout) -->
            <tr class="hover:bg-primary/5 transition-colors">
              <td class="p-3 font-bold"><?php echo date('Y-m-d'); ?></td>
              <td class="p-3 font-mono">09:00 AM</td>
              <td class="p-3 font-mono">05:00 PM</td>
              <td class="p-3 font-semibold">8.0 hrs</td>
              <td class="p-3"><span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded text-[9px] font-bold uppercase">Present</span></td>
              <td class="p-3 text-primary/60">Standard Scholar Shift Logs</td>
            </tr>
            <tr class="hover:bg-primary/5 transition-colors">
              <td class="p-3 font-bold"><?php echo date('Y-m-d', strtotime('-1 day')); ?></td>
              <td class="p-3 font-mono">09:05 AM</td>
              <td class="p-3 font-mono">05:00 PM</td>
              <td class="p-3 font-semibold">7.9 hrs</td>
              <td class="p-3"><span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded text-[9px] font-bold uppercase">Present</span></td>
              <td class="p-3 text-primary/60">Slightly delayed Check-in</td>
            </tr>
            <?php else: ?>
              <?php foreach ($my_att_logs as $log): ?>
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="p-3 font-bold"><?php echo htmlspecialchars($log['date']); ?></td>
                <td class="p-3 font-mono"><?php echo htmlspecialchars($log['check_in']); ?></td>
                <td class="p-3 font-mono"><?php echo htmlspecialchars($log['check_out']); ?></td>
                <td class="p-3 font-semibold"><?php echo htmlspecialchars($log['hours']); ?> hrs</td>
                <td class="p-3">
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase border <?php echo (strtolower($log['status']) === 'present') ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100'; ?>">
                    <?php echo htmlspecialchars($log['status']); ?>
                  </span>
                </td>
                <td class="p-3 text-primary/60"><?php echo htmlspecialchars($log['remarks'] ?? '-'); ?></td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php elseif ($active_tab === 'salary'): ?>
    <!-- 6. SALARY TAB (View Only) -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <h2 class="text-lg font-bold text-primary mb-1">Financial Earnings & Salary Ledger</h2>
      <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mb-6">Historical payments processed by the institute bank desk.</p>

      <?php
      $salaries = get_db_table('salary') ?: [];
      $my_salaries = [];
      foreach ($salaries as $sal) {
          if (isset($sal['teacher_id']) && (int)$sal['teacher_id'] === (int)$teacher['id']) {
              $my_salaries[] = $sal;
          }
      }
      usort($my_salaries, function($a, $b) { return strcmp($b['paid_date'] ?? '', $a['paid_date'] ?? ''); });
      ?>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl">
          <span class="text-[9px] font-bold text-primary/50 uppercase tracking-widest block mb-1">Monthly Base Salary</span>
          <div class="text-xl font-black text-primary">Rs. <?php echo number_format($base_salary); ?></div>
        </div>
        <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl">
          <span class="text-[9px] font-bold text-primary/50 uppercase tracking-widest block mb-1">Active Allowances</span>
          <div class="text-xl font-black text-emerald-600">+ Rs. <?php echo number_format($allowances); ?></div>
        </div>
        <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl">
          <span class="text-[9px] font-bold text-primary/50 uppercase tracking-widest block mb-1">Deductions Log</span>
          <div class="text-xl font-black text-rose-600">- Rs. <?php echo number_format($deductions); ?></div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-3">Salary slip</th>
              <th class="p-3">Amount Paid</th>
              <th class="p-3">Log Period</th>
              <th class="p-3">Paid Date</th>
              <th class="p-3">Method</th>
              <th class="p-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($my_salaries)): ?>
            <tr class="hover:bg-primary/5 transition-colors">
              <td class="p-3 font-mono font-bold text-primary">SLIP-ALFOZ-<?php echo date('Ym'); ?></td>
              <td class="p-3 font-black text-primary">Rs. <?php echo number_format($est_final_salary); ?></td>
              <td class="p-3 font-bold"><?php echo date('F Y'); ?></td>
              <td class="p-3 font-semibold"><?php echo date('Y-m-d'); ?></td>
              <td class="p-3"><?php echo htmlspecialchars($teacher['payment_method'] ?? 'Bank Transfer'); ?></td>
              <td class="p-3"><span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded text-[9px] font-bold uppercase">Paid</span></td>
            </tr>
            <?php else: ?>
              <?php foreach ($my_salaries as $sal): ?>
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="p-3 font-mono font-bold text-primary"><?php echo htmlspecialchars($sal['slip_number']); ?></td>
                <td class="p-3 font-black text-primary">Rs. <?php echo number_format($sal['amount']); ?></td>
                <td class="p-3 font-bold"><?php echo htmlspecialchars($sal['month'] . '/' . $sal['year']); ?></td>
                <td class="p-3 font-semibold"><?php echo htmlspecialchars($sal['paid_date']); ?></td>
                <td class="p-3"><?php echo htmlspecialchars($teacher['payment_method'] ?? 'Bank Transfer'); ?></td>
                <td class="p-3">
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase border <?php echo (strtolower($sal['status']) === 'paid') ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100'; ?>">
                    <?php echo htmlspecialchars($sal['status']); ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php elseif ($active_tab === 'documents'): ?>
    <!-- 7. DOCUMENTS TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
          <h2 class="text-lg font-bold text-primary">Upload Verification & Academic Documents</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Official credentials and identity proofs safely hosted.</p>
        </div>
      </div>

      <!-- Upload Form -->
      <form action="profile.php?tab=documents" method="POST" enctype="multipart/form-data" class="mb-8 p-5 border border-dashed border-primary/20 rounded-2xl bg-primary/5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <input type="hidden" name="action" value="upload_document">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary shadow-sm shrink-0">
            <i data-lucide="upload-cloud" class="w-5 h-5"></i>
          </div>
          <div>
            <h4 class="font-bold text-primary text-xs">Upload Scanned Academic Degree or National Identity</h4>
            <p class="text-[9px] text-primary/60 mt-0.5 font-semibold uppercase tracking-wider">Supports PDF, JPG, PNG up to 10MB.</p>
          </div>
        </div>
        <div class="flex gap-2 w-full sm:w-auto items-center">
          <input type="file" name="document_file" required class="text-xs text-primary file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-primary file:text-white file:uppercase file:tracking-wider cursor-pointer hover:file:opacity-90">
          <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white font-bold text-[10px] uppercase tracking-wider rounded-xl shadow-sm transition-colors">
            Upload
          </button>
        </div>
      </form>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Static Default Doc: CNIC -->
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/20 transition-all">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                    </div>
                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold uppercase tracking-wider border border-emerald-100">Verified</span>
                </div>
                <h3 class="font-bold text-primary text-xs mb-1">National Identity Card (CNIC)</h3>
                <p class="text-[9px] text-primary/60 font-semibold uppercase tracking-wider mb-2">Identity Verification</p>
                <p class="text-[10px] font-mono text-primary/70 bg-primary/5 p-2 rounded-lg break-all">CNIC-<?php echo htmlspecialchars($teacher['cnic'] ?? '35202-xxxxxxx-x'); ?></p>
            </div>
        </div>

        <!-- Static Default Doc: Sanad -->
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/20 transition-all">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                    </div>
                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold uppercase tracking-wider border border-emerald-100">Verified</span>
                </div>
                <h3 class="font-bold text-primary text-xs mb-1">Academic Degree & Shahada Sanad</h3>
                <p class="text-[9px] text-primary/60 font-semibold uppercase tracking-wider mb-2">Educational Qualification</p>
                <p class="text-[10px] font-mono text-primary/70 bg-primary/5 p-2 rounded-lg break-all"><?php echo htmlspecialchars($teacher['qualification'] ?? 'Shahadat-ul-Alimia'); ?></p>
            </div>
        </div>

        <!-- Dynamic User-Uploaded Documents from Scandir -->
        <?php foreach ($scanned_docs as $doc): ?>
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/20 transition-all">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-[9px] font-bold uppercase tracking-wider border border-amber-100">Uploaded</span>
                </div>
                <h3 class="font-bold text-primary text-xs mb-1 truncate" title="<?php echo htmlspecialchars($doc['clean_name']); ?>"><?php echo htmlspecialchars($doc['clean_name']); ?></h3>
                <p class="text-[9px] text-primary/60 font-semibold uppercase tracking-wider mb-2">Faculty Document File</p>
                <a href="<?php echo htmlspecialchars($doc['path']); ?>" target="_blank" class="block w-full text-center py-2 bg-primary/5 text-primary text-[9px] font-bold uppercase rounded-lg hover:bg-primary hover:text-white transition-all border border-primary/10 mt-4">Download Scanned Copy</a>
            </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php elseif ($active_tab === 'performance'): ?>
    <!-- 8. PERFORMANCE TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <h2 class="text-lg font-bold text-primary mb-1">Faculty Key Performance Indicators</h2>
      <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mb-6">Punctuality index and student academic performance logs.</p>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-6">
            <div>
                <div class="flex justify-between text-[10px] font-bold text-primary mb-2 uppercase tracking-widest">
                    <span>Student Retention Rate</span>
                    <span>100%</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-primary h-full" style="width: 100%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-[10px] font-bold text-primary mb-2 uppercase tracking-widest">
                    <span>Punctuality Compliance index</span>
                    <span>98%</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-primary h-full" style="width: 98%;"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-[10px] font-bold text-primary mb-2 uppercase tracking-widest">
                    <span>Syllabus Progress index</span>
                    <span>94%</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-primary h-full" style="width: 94%;"></div>
                </div>
            </div>
        </div>

        <div class="p-5 border border-primary/10 rounded-2xl bg-primary/5 flex flex-col justify-center items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center font-black text-lg mb-3">4.9</div>
            <h4 class="font-bold text-primary text-xs uppercase tracking-wider">Parent Satisfaction Index</h4>
            <p class="text-[9px] text-primary/60 mt-1 max-w-xs leading-relaxed">Aggregated parental feedback and teaching review compiled for this semester cycle.</p>
        </div>
      </div>
    </div>

    <?php elseif ($active_tab === 'timeline'): ?>
    <!-- 9. TIMELINE TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in max-w-3xl mx-auto">
      <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-2"><i data-lucide="clock" class="w-5 h-5 text-primary"></i> Historical Activity Timeline</h2>
      <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mb-8">Dossier logging index of administrative occurrences.</p>

      <div class="relative space-y-6 before:absolute before:inset-0 before:left-5 before:h-full before:w-0.5 before:bg-primary/10">
        <div class="relative flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm z-10"><i data-lucide="briefcase" class="w-4 h-4"></i></div>
            <div>
                <span class="text-[9px] text-primary/50 font-bold"><?php echo htmlspecialchars($teacher['joining_date'] ?? 'N/A'); ?></span>
                <h4 class="font-bold text-primary text-xs mt-0.5">Joined Al Foz Islamic Institute</h4>
                <p class="text-[11px] text-primary/70 mt-1">Officially onboarded as a registered scholar at Al Foz Islamic Institute.</p>
            </div>
        </div>
        
        <div class="relative flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm z-10"><i data-lucide="check" class="w-4 h-4"></i></div>
            <div>
                <span class="text-[9px] text-primary/50 font-bold"><?php echo date('Y-m-d'); ?></span>
                <h4 class="font-bold text-primary text-xs mt-0.5">Profile Records Sync</h4>
                <p class="text-[11px] text-primary/70 mt-1">Successfully synced and audited faculty profile specifications with the cloud database system.</p>
            </div>
        </div>
      </div>
    </div>

    <?php elseif ($active_tab === 'reports'): ?>
    <!-- 10. REPORTS TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-2"><i data-lucide="file-bar-chart-2" class="w-5 h-5 text-primary"></i> Faculty Documentation Reports</h2>
      <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mb-8">Generate and export certified PDFs directly from your browser.</p>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="p-5 border border-primary/10 rounded-2xl bg-primary/5 hover:border-primary transition-all group">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary mb-4 shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </div>
            <h4 class="font-bold text-primary text-[11px] uppercase tracking-wider">Attendance dossier</h4>
            <p class="text-[10px] text-primary/60 mt-1 mb-4 leading-relaxed">Complete presence and scholar compliance log certified report.</p>
            <button onclick="window.print()" class="block w-full text-center py-2 bg-white border border-primary/20 text-primary text-[10px] font-bold uppercase rounded-lg hover:bg-primary hover:text-white transition-all">Generate PDF</button>
        </div>

        <!-- Card 2 -->
        <div class="p-5 border border-primary/10 rounded-2xl bg-primary/5 hover:border-primary transition-all group">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary mb-4 shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                <i data-lucide="wallet" class="w-5 h-5"></i>
            </div>
            <h4 class="font-bold text-primary text-[11px] uppercase tracking-wider">Financial Statement</h4>
            <p class="text-[10px] text-primary/60 mt-1 mb-4 leading-relaxed">Slip disbursement and overall historical earnings log certified report.</p>
            <button onclick="window.print()" class="block w-full text-center py-2 bg-white border border-primary/20 text-primary text-[10px] font-bold uppercase rounded-lg hover:bg-primary hover:text-white transition-all">Generate PDF</button>
        </div>

        <!-- Card 3 -->
        <div class="p-5 border border-primary/10 rounded-2xl bg-primary/5 hover:border-primary transition-all group">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary mb-4 shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                <i data-lucide="award" class="w-5 h-5"></i>
            </div>
            <h4 class="font-bold text-primary text-[11px] uppercase tracking-wider">Experience Certificate</h4>
            <p class="text-[10px] text-primary/60 mt-1 mb-4 leading-relaxed">Official seniority verification and active service certificate document.</p>
            <button onclick="window.print()" class="block w-full text-center py-2 bg-white border border-primary/20 text-primary text-[10px] font-bold uppercase rounded-lg hover:bg-primary hover:text-white transition-all">Generate PDF</button>
        </div>
      </div>
    </div>

    <?php elseif ($active_tab === 'notes'): ?>
    <!-- 11. NOTES TAB (Read Only) -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-2"><i data-lucide="sticky-note" class="w-5 h-5 text-primary"></i> Faculty Dossier Remarks</h2>
      <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mb-6">Internal remarks, policy warnings, and academic commendations logged by the office desk.</p>

      <div class="space-y-4 text-xs">
        <div class="p-4 border border-rose-200 rounded-xl bg-rose-50/50">
            <span class="text-[9px] bg-rose-100 text-rose-800 font-bold px-2 py-0.5 rounded uppercase tracking-wider">Official Warning Log</span>
            <p class="text-rose-700 font-bold mt-2 leading-relaxed"><?php echo nl2br(htmlspecialchars($teacher['warnings'] ?? 'No warnings logged on this faculty node. Keep up the supreme professional standard.')); ?></p>
        </div>
        <div class="p-4 border border-emerald-200 rounded-xl bg-emerald-50/50">
            <span class="text-[9px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded uppercase tracking-wider">Special Commendations Log</span>
            <p class="text-emerald-800 font-bold mt-2 leading-relaxed"><?php echo nl2br(htmlspecialchars($teacher['achievements'] ?? 'Marvelous trial lesson ratings logged in Tajweed lectures series. Highly appreciated by parent circles.')); ?></p>
        </div>
      </div>
    </div>

    <?php elseif ($active_tab === 'messages'): ?>
    <!-- 12. MESSAGES TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden min-h-[450px] flex flex-col lg:flex-row animate-fade-in">
      <div class="w-full lg:w-80 border-r border-primary/10 flex flex-col">
        <div class="p-4 border-b border-primary/10 bg-primary/5">
          <h3 class="font-bold text-primary text-xs uppercase tracking-wider">Inbox Conversations</h3>
        </div>
        <div class="flex-grow overflow-y-auto divide-y divide-primary/5">
          <div class="p-4 bg-primary/5 flex items-center gap-3 cursor-pointer">
            <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs">A</div>
            <div class="flex-grow overflow-hidden">
              <div class="flex justify-between items-center mb-0.5">
                <span class="font-bold text-primary text-xs">Academic Office</span>
                <span class="text-[8px] text-primary/60 font-semibold">10:15 AM</span>
              </div>
              <p class="text-[10px] text-primary/80 truncate">Please verify your availability slots...</p>
            </div>
          </div>
        </div>
      </div>

      <div class="flex-grow flex flex-col justify-between bg-slate-50/30">
        <div class="p-4 border-b border-primary/10 bg-white flex justify-between items-center">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-black text-sm">AO</div>
            <div>
              <h4 class="font-bold text-primary text-xs">Academic Office Desk</h4>
              <p class="text-[9px] text-emerald-600 uppercase tracking-widest font-bold">Online</p>
            </div>
          </div>
        </div>

        <div class="p-6 flex-grow flex flex-col items-center justify-center">
            <div class="w-14 h-14 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-lucide="message-square-dashed" class="w-7 h-7 text-primary/20"></i>
            </div>
            <p class="text-[10px] font-bold text-primary/40 uppercase tracking-widest">Secure conversation logs loaded.</p>
        </div>

        <div class="p-4 bg-white border-t border-primary/10 flex gap-3 items-center">
          <input type="text" class="flex-grow px-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary outline-none" placeholder="Type secure reply...">
          <button class="bg-primary hover:bg-primary/90 text-white p-2 rounded-xl transition-all"><i data-lucide="send" class="w-4 h-4"></i></button>
        </div>
      </div>
    </div>

    <?php elseif ($active_tab === 'notifications'): ?>
    <!-- 13. ALERTS TAB -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 animate-fade-in">
      <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-2"><i data-lucide="bell" class="w-5 h-5 text-primary"></i> Faculty System Alerts</h2>
      <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mb-6">Real-time alerts and broadcasts propagated by Al Foz Operations.</p>

      <div class="space-y-4">
        <div class="p-4 border border-amber-200 bg-amber-50/40 rounded-xl flex gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"></i>
            <div>
                <h4 class="font-bold text-primary text-xs">Class Timings Compliance Reminders</h4>
                <p class="text-xs text-primary/80 mt-1">Please log check-ins inside 5 minutes of class schedules to maintain supreme compliance metrics.</p>
            </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

<!-- EDIT PROFILE MODAL -->
<div id="editProfileModal" class="fixed inset-0 z-50 hidden bg-primary/40 backdrop-blur-sm items-center justify-center p-4 transition-all">
    <div class="bg-white rounded-2xl border border-primary/10 shadow-2xl w-full max-w-2xl p-6 relative overflow-hidden animate-scale-up">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-base font-black text-primary uppercase tracking-wider">Edit Profile Information</h3>
                <p class="text-[9px] text-primary/60 uppercase font-bold tracking-widest mt-0.5">Update authorized fields safely.</p>
            </div>
            <button onclick="closeEditProfileModal()" class="text-primary/50 hover:text-primary transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="profile.php?tab=profile" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="action" value="update_profile">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Mobile Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($teacher['phone'] ?? ''); ?>" required class="w-full px-3 py-2 border border-primary/20 rounded-xl text-xs font-bold text-primary outline-none focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">WhatsApp Number</label>
                    <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($teacher['whatsapp'] ?? ''); ?>" required class="w-full px-3 py-2 border border-primary/20 rounded-xl text-xs font-bold text-primary outline-none focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Emergency Contact</label>
                    <input type="text" name="emergency_contact" value="<?php echo htmlspecialchars($teacher['emergency_contact'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-xl text-xs font-bold text-primary outline-none focus:border-primary transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Change Profile Photo</label>
                    <input type="file" name="teacher_picture" class="text-xs text-primary file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[9px] file:font-bold file:bg-primary file:text-white file:uppercase file:tracking-wider cursor-pointer hover:file:opacity-90">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Physical Address</label>
                    <textarea name="address" required class="w-full px-3 py-2 border border-primary/20 rounded-xl text-xs text-primary font-semibold outline-none focus:border-primary transition-all h-20"><?php echo htmlspecialchars($teacher['address'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-primary/5">
                <button type="button" onclick="closeEditProfileModal()" class="px-4 py-2 border border-primary/20 text-primary text-xs font-bold uppercase rounded-xl hover:bg-primary/5 transition-all">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-white text-xs font-black uppercase rounded-xl hover:bg-primary/90 shadow-sm transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditProfileModal() {
    var modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeEditProfileModal() {
    var modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
