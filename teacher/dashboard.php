<?php
/**
 * Al Foz Islamic Institute - Teacher ERP Dashboard
 * Faculty Operations Control Room with Schedule, Homework & Performance Tracker
 */

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../auth/permissions.php';
require_once __DIR__ . '/../includes/students_data.php';
require_once __DIR__ . '/../includes/teachers_data.php';

// Strictly require Teacher, Admin, or Super Admin roles
require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_name = $_SESSION['name'] ?? 'Faculty Member';
$teacher_email = $_SESSION['email'] ?? 'teacher@alfoz.org';

// Fetch all teachers to get logged-in teacher details (salary, currency, etc.)
$all_teachers = get_all_teachers();
$current_teacher_record = null;
foreach ($all_teachers as $t) {
    if ((isset($t['name']) && $t['name'] === $teacher_name) || (isset($t['email']) && strtolower($t['email']) === strtolower($teacher_email))) {
        $current_teacher_record = $t;
        break;
    }
}

$salary_val = 35000;
$salary_currency = 'PKR';
if ($current_teacher_record) {
    $salary_val = $current_teacher_record['salary'] ?? 35000;
    $salary_currency = $current_teacher_record['currency'] ?? 'PKR';
}
$salary_pkr = convert_to_pkr($salary_val, $salary_currency);

// Fetch students assigned to this teacher
$all_students = get_all_students();
$my_students = array_filter($all_students, function($s) use ($teacher_name) {
    return (isset($s['teacher_name']) && $s['teacher_name'] === $teacher_name);
});
$my_students_count = count($my_students);

// Filter Teacher Stats
$present_today = 0;
$absent_today = 0;
$trial_students_count = 0;
foreach ($my_students as $s) {
    $att = $s['attendance_status'] ?? 'Present';
    if ($att === 'Present') {
        $present_today++;
    } elseif ($att === 'Absent') {
        $absent_today++;
    }
    
    $status = $s['status'] ?? 'Active';
    if ($status === 'Trial') {
        $trial_students_count++;
    }
}

// Today's classes count
$today_day = strtolower(date('l'));
$todays_classes_count = 0;
foreach ($my_students as $s) {
    if (isset($s[$today_day . '_enabled']) && $s[$today_day . '_enabled']) {
        $todays_classes_count++;
    }
}

$attendance_pct = $my_students_count > 0 ? round(($present_today / $my_students_count) * 100, 1) : 100;
$makeup_classes_count = 2; // Default realistic placeholder

?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <!-- Welcome Header -->
    <div class="mb-8 bg-white border border-primary/10 rounded-[24px] p-8 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden shadow-sm">
      <!-- Ambient background decoration glows -->
      <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
      
      <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10 w-full md:w-auto">
        <div class="relative">
          <?php echo render_dashboard_profile_pic_html(); ?>
          <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-4 border-white rounded-full shadow-md animate-pulse"></span>
        </div>
        <div class="text-center sm:text-left">
          <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-primary">Assalamu Alaikum, <?php echo htmlspecialchars($teacher_name); ?>!</h2>
          <p class="text-xs text-primary/70 mt-1.5 font-medium">Manage student schedules, assign daily Sabaq/homework, and evaluate tajweed progress.</p>
        </div>
      </div>
      <div class="bg-emerald-500/10 border border-emerald-400/20 text-emerald-800 rounded-2xl px-5 py-3 text-xs font-bold flex items-center gap-2 relative z-10 shadow-sm">
        <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <span>Faculty Session Active</span>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="mb-8">
      <h3 class="text-xs font-bold uppercase tracking-wider text-primary/60 mb-4">Quick Shortcuts</h3>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 micro-grid">
        
        <a href="/teacher/attendance/" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="calendar-check-2" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Attendance</span>
        </a>
        <a href="/teacher/homework.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="book-open" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Homework</span>
        </a>
        <a href="/teacher/student_profile.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="user-search" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Student Profile</span>
        </a>
        <a href="/teacher/salary.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="banknote" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">My Salary</span>
        </a>
        <a href="/teacher/reports.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Reports</span>
        </a>
      </div>
    </div>


    <!-- TEACHER TOP STATS CARDS -->
    <div class="mb-8">
      <h3 class="text-xs font-bold uppercase tracking-wider text-primary/60 mb-4">Faculty Performance Dashboard</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        
        <!-- Card 1: My Students -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="graduation-cap" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-primary/60 font-bold bg-primary/5 px-2 py-0.5 rounded-full">Assigned</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">My Students</span>
            <span class="text-2xl sm:text-3xl font-black text-primary mt-1 block"><?php echo $my_students_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Direct active learners</span>
          </div>
        </div>

        <!-- Card 2: Today's Classes -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="calendar" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Today</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Today's Classes</span>
            <span class="text-2xl sm:text-3xl font-black text-primary mt-1 block"><?php echo $todays_classes_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Quran & Tajweed classes</span>
          </div>
        </div>

        <!-- Card 3: Present Students -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-green-50 rounded-xl text-green-700 group-hover:bg-green-700 group-hover:text-white transition-all">
              <i data-lucide="user-check" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Present</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Present Students</span>
            <span class="text-2xl sm:text-3xl font-black text-green-700 mt-1 block"><?php echo $present_today; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Marked present today</span>
          </div>
        </div>

        <!-- Card 4: Absent Students -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-red-50 rounded-xl text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
              <i data-lucide="user-x" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded-full">Absent</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Absent Students</span>
            <span class="text-2xl sm:text-3xl font-black text-red-600 mt-1 block"><?php echo $absent_today; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Awaiting makeup slots</span>
          </div>
        </div>

        <!-- Card 5: Trial Students -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="sparkles" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-full">Trial</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Trial Students</span>
            <span class="text-2xl sm:text-3xl font-black text-amber-600 mt-1 block"><?php echo $trial_students_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Evaluation classes</span>
          </div>
        </div>

        <!-- Card 6: Attendance % -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="pie-chart" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full"><?php echo $attendance_pct; ?>%</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Attendance Rate</span>
            <span class="text-2xl sm:text-3xl font-black text-primary mt-1 block"><?php echo $attendance_pct; ?>%</span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Average monthly score</span>
          </div>
        </div>

        <!-- Card 7: This Month Salary -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="wallet" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Paid</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">This Month Salary</span>
            <span class="text-xl sm:text-2xl font-black text-primary mt-1 block">PKR <?php echo number_format($salary_pkr); ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Transferred directly</span>
          </div>
        </div>

        <!-- Card 8: Makeup Classes -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="rotate-ccw" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-full">Pending</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Makeup Classes</span>
            <span class="text-2xl sm:text-3xl font-black text-primary mt-1 block"><?php echo $makeup_classes_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Lessons to reschedule</span>
          </div>
        </div>

      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Left side modules (Schedule & Matrix) -->
      <div class="lg:col-span-8 space-y-8">
        
        <!-- TODAY'S SCHEDULE -->
        <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm overflow-hidden">
          <div class="bg-primary/5 px-6 py-4.5 border-b border-primary/10 flex justify-between items-center">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="clock" class="w-4.5 h-4.5 text-primary"></i>
              <span>Today's Live Classes Schedule</span>
            </h3>
            <span class="bg-primary/10 text-primary font-bold text-[10px] px-3 py-1 rounded-full uppercase tracking-wider">
              <?php echo date('l, d M'); ?>
            </span>
          </div>
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs text-primary">
                <thead>
                  <tr class="border-b border-primary/10 uppercase font-bold text-primary/60 text-[10px] tracking-wider">
                    <th class="pb-3">Student details</th>
                    <th class="pb-3">Course / syllabus</th>
                    <th class="pb-3">Timing (student/teacher/PKT)</th>
                    <th class="pb-3 text-right">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                  <?php if (empty($my_students)): ?>
                    <tr>
                      <td colspan="4" class="py-10 text-center text-primary/40 font-bold text-xs uppercase tracking-widest">No students assigned to you yet.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($my_students as $stud): 
                      $s_initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $stud['name'])));
                      $day_enabled = isset($stud[$today_day . '_enabled']) && $stud[$today_day . '_enabled'];
                      if (!$day_enabled) continue; // Only show active scheduled classes for today
                    ?>
                      <tr class="hover:bg-primary/5 transition-all">
                        <td class="py-4 font-bold flex items-center gap-3">
                          <div class="w-10 h-10 rounded-xl overflow-hidden border border-primary/15 shadow-sm bg-primary/10 flex items-center justify-center">
                            <?php if (!empty($stud['student_picture'])): ?>
                              <img src="<?php echo $stud['student_picture']; ?>" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline';" class="w-full h-full object-cover">
                              <span style="display:none;" class="text-xs font-black text-primary"><?php echo $s_initials; ?></span>
                            <?php else: ?>
                              <span class="text-xs font-black text-primary"><?php echo $s_initials; ?></span>
                            <?php endif; ?>
                          </div>
                          <div>
                            <p class="font-extrabold text-primary leading-tight"><?php echo htmlspecialchars($stud['name']); ?></p>
                            <p class="text-[9px] text-primary/60 uppercase tracking-wider mt-0.5"><?php echo htmlspecialchars($stud['country']); ?></p>
                          </div>
                        </td>
                        <td class="py-4">
                          <p class="font-semibold text-primary/85"><?php echo htmlspecialchars($stud['course']); ?></p>
                          <p class="text-[10px] text-green-700 font-bold">Target: <?php echo htmlspecialchars($stud['academic']['current_lesson'] ?? 'Initial stage'); ?></p>
                        </td>
                        <td class="py-4 font-mono text-[11px] text-primary/70">
                          <p class="font-bold">Student: <?php echo date('h:i A', strtotime($stud[$today_day . '_time'] ?? '12:00')); ?> (<?php echo htmlspecialchars($stud['timezone'] ?? 'PKT'); ?>)</p>
                          <p class="text-[10px]">PKT: <?php echo !empty($stud[$today_day . '_pkt']) ? date('h:i A', strtotime($stud[$today_day . '_pkt'])) : 'N/A'; ?></p>
                        </td>
                        <td class="py-4 text-right">
                          <button onclick="alert('Launching secure virtual Zoom classroom for student: <?php echo addslashes($stud['name']); ?>. Please prepare curriculum guidelines.')" class="bg-primary text-white hover:bg-[#10353a] px-4 py-2 rounded-xl font-bold text-[10px] uppercase tracking-wider shadow-sm transition-all active:scale-95">
                            Start Class
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Weekly Schedule Timetable matrix -->
        <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-6">
          <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary mb-5 flex items-center gap-2">
            <i data-lucide="grid" class="w-4.5 h-4.5"></i>
            <span>My Weekly Scheduled Matrix Timetable</span>
          </h3>
          <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 micro-grid">
            <?php 
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $d):
              $lower_d = strtolower($d);
              $class_list = [];
              foreach ($my_students as $s) {
                if (isset($s[$lower_d . '_enabled']) && $s[$lower_d . '_enabled']) {
                  $class_list[] = $s['name'] . ' (' . date('h:i A', strtotime($s[$lower_d . '_time'])) . ')';
                }
              }
              $is_active = !empty($class_list);
            ?>
            <div class="<?php echo $is_active ? 'bg-primary text-white border-transparent' : 'bg-slate-50 text-slate-300 opacity-60 border-slate-100'; ?> p-3 rounded-2xl text-center border">
              <p class="text-[9px] font-black uppercase tracking-widest mb-1"><?php echo substr($d, 0, 3); ?></p>
              <?php if ($is_active): ?>
                <p class="text-[11px] font-black"><?php echo count($class_list); ?> Class(es)</p>
                <div class="hidden lg:block text-[8px] opacity-80 mt-1 truncate" title="<?php echo htmlspecialchars(implode(', ', $class_list)); ?>">
                  <?php echo htmlspecialchars($class_list[0]); ?>
                </div>
              <?php else: ?>
                <p class="text-[11px] font-bold">OFF</p>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Pending Homework Widget -->
        <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm overflow-hidden">
          <div class="bg-primary/5 px-6 py-4 border-b border-primary/10">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="book-open" class="w-4.5 h-4.5"></i>
              <span>Pending Student Homework Evaluations</span>
            </h3>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <?php if (empty($my_students)): ?>
                <p class="text-center text-xs text-primary/40 font-bold uppercase tracking-wider py-6">No evaluations requested.</p>
              <?php else: ?>
                <?php foreach (array_slice($my_students, 0, 2) as $stud): ?>
                <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                  <div>
                    <span class="text-[9px] font-black bg-amber-100 text-amber-700 px-2 py-0.5 rounded uppercase tracking-wider">Awaiting review</span>
                    <h4 class="font-extrabold text-xs text-primary mt-1.5"><?php echo htmlspecialchars($stud['name']); ?> — <?php echo htmlspecialchars($stud['course']); ?></h4>
                    <p class="text-[11px] text-primary/70 mt-1">Homework assigned: "<?php echo htmlspecialchars($stud['academic']['homework'] ?? 'No homework details assigned'); ?>"</p>
                  </div>
                  <button onclick="alert('Opening submission grading interface...')" class="bg-primary hover:bg-opacity-95 text-white font-bold uppercase tracking-wider text-[9px] px-3.5 py-2 rounded-xl">Grade Homework</button>
                </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>

      <!-- Right side panels (Salary, Attendance summary & Alerts) -->
      <div class="lg:col-span-4 space-y-8">
        
        <!-- Attendance Summary Ring Widget -->
        <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm">
          <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary mb-5 flex items-center gap-2">
            <i data-lucide="donut" class="w-4.5 h-4.5"></i>
            <span>Attendance Summary</span>
          </h3>
          <div class="relative h-44 w-full flex items-center justify-center">
            <canvas id="teacherAttendanceDoughnut"></canvas>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-4 text-center text-xs">
            <div class="p-2.5 bg-green-50 rounded-xl">
              <span class="text-[10px] text-green-600 uppercase font-black tracking-widest block">Present</span>
              <span class="font-bold text-lg text-green-700"><?php echo $present_today; ?></span>
            </div>
            <div class="p-2.5 bg-red-50 rounded-xl">
              <span class="text-[10px] text-red-600 uppercase font-black tracking-widest block">Absent</span>
              <span class="font-bold text-lg text-red-700"><?php echo $absent_today; ?></span>
            </div>
          </div>
        </div>

        <!-- Salary Progress tracker bar -->
        <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm">
          <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary mb-4 flex items-center gap-2">
            <i data-lucide="badge-dollar-sign" class="w-4.5 h-4.5"></i>
            <span>Salary Processing Progress</span>
          </h3>
          <div class="space-y-4">
            <div class="flex justify-between text-xs font-bold text-primary">
              <span>Base Pay (<?php echo $salary_currency; ?>)</span>
              <span><?php echo number_format($salary_val) . ' ' . $salary_currency; ?></span>
            </div>
            <!-- Progress Bar -->
            <div>
              <div class="flex justify-between text-[10px] font-bold text-primary/60 mb-1">
                <span>Payroll Disbursed</span>
                <span>100% Cleared</span>
              </div>
              <div class="w-full bg-primary/10 h-3 rounded-full overflow-hidden">
                <div class="bg-primary h-full rounded-full" style="width: 100%;"></div>
              </div>
            </div>
            <div class="p-3 bg-green-50 border border-green-200 rounded-xl flex items-center gap-2 text-[10px] text-green-800 font-bold">
              <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
              <span>All payments synchronized to Bank</span>
            </div>
          </div>
        </div>

        <!-- Live Notifications Widget -->
        <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm">
          <div class="flex justify-between items-center mb-5 pb-3 border-b border-primary/10">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="bell" class="w-4.5 h-4.5"></i>
              <span>Faculty Alerts</span>
            </h3>
            <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
          </div>
          <div class="space-y-4">
            <div class="p-3 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl">
              <span class="text-[9px] font-mono text-amber-600 font-bold block">Syllabus Reminder</span>
              <p class="text-xs font-semibold text-primary mt-0.5">Please update lesson notes after student class concludes to sync parents logs.</p>
            </div>
            <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded-r-xl">
              <span class="text-[9px] font-mono text-blue-600 font-bold block">Evaluation Cycle</span>
              <p class="text-xs font-semibold text-primary mt-0.5">Next monthly tajweed evaluations scheduled starting July 5th.</p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

</div>

<!-- Chart Script Configurations -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  
  // Faculty Attendance Doughnut Chart
  new Chart(document.getElementById('teacherAttendanceDoughnut'), {
    type: 'doughnut',
    data: {
      labels: ['Present', 'Absent'],
      datasets: [{
        data: [<?php echo $present_today; ?>, <?php echo $absent_today; ?>],
        backgroundColor: ['#10B981', '#EF4444'],
        borderWidth: 1,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            font: { family: 'Poppins', size: 10 }
          }
        }
      },
      cutout: '70%'
    }
  });

});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
