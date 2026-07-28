<?php
$current_page = basename($_SERVER['PHP_SELF']);
$student_id = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';

if (!isset($student) && !empty($student_id)) {
    $students = get_all_students();
    foreach ($students as $s) {
        if ($s['id'] == $student_id || (isset($s['student_id']) && $s['student_id'] == $student_id)) {
            $student = $s;
            break;
        }
    }
}
$initials = strtoupper(substr($student['name'] ?? 'UN', 0, 2));

$student_nav_items = [
    [
        'file' => 'student_profile.php',
        'label' => 'Profile',
        'icon' => 'user',
        'bg' => 'bg-primary/5 text-primary',
        'active_bg' => 'bg-primary text-white shadow-md'
    ],
    [
        'file' => 'student_attendance.php',
        'label' => 'Attendance',
        'icon' => 'calendar-check',
        'bg' => 'bg-emerald-50 text-emerald-600',
        'active_bg' => 'bg-emerald-600 text-white shadow-md'
    ],
    [
        'file' => 'student_fees.php',
        'label' => 'Fees & Finance',
        'icon' => 'wallet',
        'bg' => 'bg-amber-50 text-amber-600',
        'active_bg' => 'bg-amber-600 text-white shadow-md'
    ],
    [
        'file' => 'student_exams.php',
        'label' => 'Exams',
        'icon' => 'award',
        'bg' => 'bg-purple-50 text-purple-600',
        'active_bg' => 'bg-purple-600 text-white shadow-md'
    ],
    [
        'file' => 'student_reports.php',
        'label' => 'Reports',
        'icon' => 'file-bar-chart-2',
        'bg' => 'bg-rose-50 text-rose-600',
        'active_bg' => 'bg-rose-600 text-white shadow-md'
    ],
    [
        'file' => 'student_schedule.php',
        'label' => 'Schedule',
        'icon' => 'calendar-days',
        'bg' => 'bg-indigo-50 text-indigo-600',
        'active_bg' => 'bg-indigo-600 text-white shadow-md'
    ],
    [
        'file' => 'student_teacher.php',
        'label' => 'Teacher',
        'icon' => 'graduation-cap',
        'bg' => 'bg-sky-50 text-sky-600',
        'active_bg' => 'bg-sky-600 text-white shadow-md'
    ],
    [
        'file' => 'student_documents.php',
        'label' => 'Documents',
        'icon' => 'files',
        'bg' => 'bg-slate-100 text-slate-700',
        'active_bg' => 'bg-slate-700 text-white shadow-md'
    ]
];
?>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
  <div>
    <a href="students.php" class="text-[10px] font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1 mb-2">
      <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to Students
    </a>
    <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Student Profile</h1>
  </div>
  <div class="flex flex-wrap items-center gap-2">
    <a href="edit_student.php?id=<?php echo htmlspecialchars($student_id); ?>" class="bg-white border border-primary/20 hover:border-primary/40 text-primary px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5">
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

<!-- Horizontal Dossier Navigation Ribbon Box -->
<div class="bg-white border border-primary/10 rounded-2xl p-2 shadow-sm mb-6">
  <div class="flex items-center gap-2 custom-horizontal-scrollbar pb-2 pt-1 px-1">
     <?php foreach ($student_nav_items as $item):
        $is_active = ($current_page === basename($item['file']));
        $btn_class = $is_active ? $item['active_bg'] : "{$item['bg']} hover:bg-opacity-80";
     ?>
       <a href="<?php echo $item['file']; ?>?id=<?php echo htmlspecialchars($student_id); ?>" 
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
        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border-4 border-white shadow-lg bg-primary flex items-center justify-center text-white text-4xl font-black">
          <?php echo htmlspecialchars($initials); ?>
        </div>
        <span class="absolute -bottom-2 -right-2 bg-emerald-500 text-white text-[9px] font-bold px-2 py-1 rounded-md border-2 border-white shadow-sm flex items-center gap-1">
          <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> <?php echo htmlspecialchars($student['status'] ?? 'Active'); ?>
        </span>
      </div>
      <div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1 justify-center sm:justify-start">
          <h1 class="text-2xl font-black text-primary tracking-tight"><?php echo htmlspecialchars($student['name']); ?></h1>
          <span class="text-[10px] font-bold uppercase tracking-widest bg-primary/10 text-primary px-2 py-0.5 rounded-md"><?php echo htmlspecialchars($student['student_id'] ?? $student['id'] ?? 'N/A'); ?></span>
        </div>
        <p class="text-xs text-primary/75 mb-3 font-medium"><?php echo htmlspecialchars($student['course'] ?? 'Quran Student'); ?> • Joined <?php echo htmlspecialchars($student['joining_date'] ?? 'N/A'); ?></p>
        <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white text-primary border border-primary/15 rounded-md text-[10px] font-bold shadow-sm">
            <i data-lucide="mail" class="w-3 h-3 text-primary/70"></i> <?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?>
          </span>
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white text-primary border border-primary/15 rounded-md text-[10px] font-bold shadow-sm">
            <i data-lucide="phone" class="w-3 h-3 text-primary/70"></i> <?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?>
          </span>
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white text-primary border border-primary/15 rounded-md text-[10px] font-bold shadow-sm">
            <i data-lucide="user" class="w-3 h-3 text-primary/70"></i> Teacher: <?php echo htmlspecialchars($student['teacher_name'] ?? 'Unassigned'); ?>
          </span>
        </div>
      </div>
    </div>
    
    <!-- Performance Highlights -->
    <div class="relative z-10 w-full lg:w-auto bg-white/60 backdrop-blur-sm rounded-2xl p-4 border border-primary/10 shadow-sm flex gap-4 overflow-x-auto scrollbar-none">
      <div class="text-center min-w-[80px]">
        <div class="text-primary font-black text-xl"><?php echo htmlspecialchars($student['fee_currency'] ?? 'PKR'); ?> <?php echo htmlspecialchars($student['monthly_fee'] ?? '0'); ?></div>
        <div class="text-[9px] font-bold text-primary/60 uppercase tracking-wider mt-1">Monthly<br>Fee</div>
      </div>
      <div class="w-px bg-primary/10 shrink-0"></div>
      <div class="text-center min-w-[80px]">
        <div class="text-primary font-black text-xl">0%</div>
        <div class="text-[9px] font-bold text-primary/60 uppercase tracking-wider mt-1">Attendance<br>Rate</div>
      </div>
      <div class="w-px bg-primary/10 shrink-0"></div>
      <div class="text-center min-w-[80px]">
        <div class="text-primary font-black text-xl">A+</div>
        <div class="text-[9px] font-bold text-primary/60 uppercase tracking-wider mt-1">Academic<br>Grade</div>
      </div>
    </div>
  </div>
</div>
