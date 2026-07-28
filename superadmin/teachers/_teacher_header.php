<?php
require_once __DIR__ . '/../../includes/teachers_data.php';

// Active tab logic based on current file name
$current_page = basename($_SERVER['PHP_SELF']);
$teacher_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$all_teachers = get_all_teachers();
$teacher = null;
foreach ($all_teachers as $t) {
    if ($t['id'] == $teacher_id) {
        $teacher = $t;
        break;
    }
}

// Fallback to first teacher if none found
if (!$teacher && !empty($all_teachers)) {
    $teacher = $all_teachers[0];
    $teacher_id = $teacher['id'];
}

if (!$teacher) {
    echo "<div class='bg-red-50 p-6 rounded-2xl text-red-700 font-bold'>Error: Teacher not found.</div>";
    return;
}

// Load assigned students for this teacher dynamically
require_once __DIR__ . '/../../includes/students_data.php';
$all_students = get_all_students();
$assigned_students = [];
foreach ($all_students as $s) {
    if (
        (isset($s['teacher_id']) && $s['teacher_id'] === $teacher['employee_id']) ||
        (isset($s['teacher_name']) && strcasecmp($s['teacher_name'], $teacher['name']) === 0)
    ) {
        $assigned_students[] = $s;
    }
}


$teacher_nav_items = [
    [
        'file' => 'teacher_profile.php',
        'label' => 'Profile',
        'icon' => 'user',
        'bg' => 'bg-primary/5 text-primary',
        'active_bg' => 'bg-primary text-white shadow-md'
    ],
    [
        'file' => 'teacher_students.php',
        'label' => 'Students',
        'icon' => 'users',
        'bg' => 'bg-emerald-50 text-emerald-600',
        'active_bg' => 'bg-emerald-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_schedule.php',
        'label' => 'Schedule',
        'icon' => 'calendar-days',
        'bg' => 'bg-amber-50 text-amber-600',
        'active_bg' => 'bg-amber-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_availability.php',
        'label' => 'Availability',
        'icon' => 'clock',
        'bg' => 'bg-purple-50 text-purple-600',
        'active_bg' => 'bg-purple-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_attendance.php',
        'label' => 'Attendance',
        'icon' => 'calendar-check',
        'bg' => 'bg-rose-50 text-rose-600',
        'active_bg' => 'bg-rose-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_salary.php',
        'label' => 'Salary',
        'icon' => 'wallet',
        'bg' => 'bg-indigo-50 text-indigo-600',
        'active_bg' => 'bg-indigo-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_documents.php',
        'label' => 'Documents',
        'icon' => 'files',
        'bg' => 'bg-sky-50 text-sky-600',
        'active_bg' => 'bg-sky-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_performance.php',
        'label' => 'Performance',
        'icon' => 'line-chart',
        'bg' => 'bg-teal-50 text-teal-600',
        'active_bg' => 'bg-teal-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_timeline.php',
        'label' => 'Timeline',
        'icon' => 'history',
        'bg' => 'bg-slate-100 text-slate-700',
        'active_bg' => 'bg-slate-700 text-white shadow-md'
    ],
    [
        'file' => 'teacher_reports.php',
        'label' => 'Reports',
        'icon' => 'file-bar-chart-2',
        'bg' => 'bg-orange-50 text-orange-600',
        'active_bg' => 'bg-orange-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_notes.php',
        'label' => 'Notes',
        'icon' => 'sticky-note',
        'bg' => 'bg-pink-50 text-pink-600',
        'active_bg' => 'bg-pink-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_complaints.php',
        'label' => 'Complaints',
        'icon' => 'alert-triangle',
        'bg' => 'bg-red-50 text-red-600',
        'active_bg' => 'bg-red-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_messages.php',
        'label' => 'Messages',
        'icon' => 'mail',
        'bg' => 'bg-cyan-50 text-cyan-600',
        'active_bg' => 'bg-cyan-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_notifications.php',
        'label' => 'Alerts',
        'icon' => 'bell',
        'bg' => 'bg-yellow-50 text-yellow-700',
        'active_bg' => 'bg-yellow-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_trials.php',
        'label' => 'Trials',
        'icon' => 'hourglass',
        'bg' => 'bg-lime-50 text-lime-700',
        'active_bg' => 'bg-lime-600 text-white shadow-md'
    ],
    [
        'file' => 'teacher_exam_results.php',
        'label' => 'Exams',
        'icon' => 'award',
        'bg' => 'bg-violet-50 text-violet-600',
        'active_bg' => 'bg-violet-600 text-white shadow-md'
    ],
];

function get_tab_class($page_name, $current_page) {
    if ($page_name === $current_page) {
        return "px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold uppercase tracking-wider whitespace-nowrap shrink-0 shadow-sm transition-colors";
    }
    return "px-4 py-2 text-primary/70 hover:text-primary hover:bg-primary/5 rounded-lg text-xs font-bold uppercase tracking-wider whitespace-nowrap shrink-0 transition-colors";
}
?>

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <a href="teachers.php" class="text-[10px] font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1 mb-2">
          <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to Teachers
        </a>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Teacher Profile</h1>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <a href="edit_teacher.php?id=<?php echo $teacher_id; ?>" class="bg-white border border-primary/20 hover:border-primary/40 text-primary px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5">
          <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Profile
        </a>
        <button onclick="window.print()" class="bg-white border border-primary/20 hover:border-primary/40 text-primary px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5">
          <i data-lucide="printer" class="w-3.5 h-3.5"></i> Print Profile
        </button>
        <button onclick="window.print()" class="bg-primary text-[#F7FAFF] hover:bg-primary/90 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5">
          <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
        </button>
      </div>
    </div>

    <!-- Horizontal Dossier Navigation Ribbon Box (Completely Above Profile) -->
    <div class="bg-white border border-primary/10 rounded-2xl p-2 shadow-sm mb-6">
      <div class="flex items-center gap-2 custom-horizontal-scrollbar pb-2 pt-1 px-1">
         <?php foreach ($teacher_nav_items as $item): 
           $is_active = ($current_page === $item['file']);
           $btn_class = $is_active ? $item['active_bg'] : "{$item['bg']} hover:bg-opacity-80";
         ?>
           <a href="<?php echo $item['file']; ?>?id=<?php echo $teacher_id; ?>" 
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
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($teacher['name']); ?>&background=184D55&color=fff&size=200" alt="Teacher Profile" class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border-4 border-white shadow-lg object-cover">
            <span class="absolute -bottom-2 -right-2 bg-emerald-500 text-white text-[9px] font-bold px-2 py-1 rounded-md border-2 border-white shadow-sm flex items-center gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> <?php echo htmlspecialchars($teacher['status']); ?>
            </span>
          </div>
          <div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1 justify-center sm:justify-start">
              <h1 class="text-2xl font-black text-primary tracking-tight"><?php echo htmlspecialchars($teacher['name']); ?></h1>
              <span class="text-[10px] font-bold uppercase tracking-widest bg-primary/10 text-primary px-2 py-0.5 rounded-md"><?php echo htmlspecialchars($teacher['id']); ?></span>
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
                <i data-lucide="map-pin" class="w-3 h-3 text-primary/70"></i> <?php echo htmlspecialchars($teacher['location'] ?? 'Remote'); ?>
              </span>
            </div>
          </div>
        </div>
        
        
<?php
// Dynamic Stats Calculation
$all_students = get_db_table('students');
$assigned_students = [];
$teacher_name = $teacher['name'] ?? '';
foreach ($all_students as $s) {
    if ((isset($s['assigned_teacher']) && $s['assigned_teacher'] == $teacher_name) || 
        (isset($s['teacher_id']) && $s['teacher_id'] == $teacher['id'])) {
        $assigned_students[] = $s;
    }
}
$active_students_count = count($assigned_students);

// Calculate Attendance Rate
$all_attendance = get_db_table('attendance');
$total_classes = 0;
$present_classes = 0;

if (!empty($assigned_students) && !empty($all_attendance)) {
    $student_ids = array_map(function($s) { return $s['id'] ?? $s['student_id'] ?? ''; }, $assigned_students);
    foreach ($all_attendance as $record) {
        if (in_array($record['student_id'], $student_ids) || in_array($record['roll_no'] ?? '', $student_ids)) {
            $total_classes++;
            if (isset($record['status']) && in_array(strtolower($record['status']), ['present', 'late'])) {
                $present_classes++;
            }
        }
    }
}
$attendance_rate = $total_classes > 0 ? round(($present_classes / $total_classes) * 100) : 0;
?>
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
            <div class="text-primary font-black text-xl">N/A</div>
            <div class="text-[9px] font-bold text-primary/60 uppercase tracking-wider mt-1">Student<br>Feedback</div>
          </div>
        </div>
      </div>
    </div>
