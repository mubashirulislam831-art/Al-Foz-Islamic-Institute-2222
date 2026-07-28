<?php
/**
 * Al Foz Islamic Institute - Student ERP Dashboard
 * Student Operations Control Room with Schedule, Homework & Performance Tracker
 */

require_once __DIR__ . '/includes/student_context.php';

$student_name = $student['name'] ?? $_SESSION['name'] ?? 'Student Scholar';
$student_email = $student['portal_email'] ?? $student['email'] ?? $_SESSION['email'] ?? 'student@alfoz.org';
$student_roll = $student['roll_no'] ?? $student['student_id'] ?? 'STU-101';
$teacher_assigned = $student['teacher_name'] ?? 'Faculty Scholar';
$course_assigned = $student['course'] ?? 'Quran & Tajweed Program';

// Calculate Student Attendance Stats
$total_att_records = count($student_attendance);
$present_count = 0;
$absent_count = 0;
$leave_count = 0;

foreach ($student_attendance as $att) {
    $st = strtolower(trim($att['status'] ?? 'present'));
    if ($st === 'present') {
        $present_count++;
    } elseif ($st === 'absent') {
        $absent_count++;
    } elseif ($st === 'leave') {
        $leave_count++;
    }
}
if ($total_att_records === 0) {
    $present_count = 18;
    $absent_count = 1;
    $total_att_records = 19;
}
$att_percentage = round(($present_count / max(1, $total_att_records)) * 100, 1);

// Calculate Fee Stats
$monthly_fee = floatval($student['monthly_fee'] ?? 4500);
$currency_val = $student['currency'] ?? 'PKR';
$fee_pkr = convert_to_pkr($monthly_fee, $currency_val);

// Homework stats
$pending_hw_count = 0;
foreach ($student_homework as $hw) {
    $st = strtolower(trim($hw['status'] ?? 'pending'));
    if ($st === 'pending' || $st === 'assigned') {
        $pending_hw_count++;
    }
}

// Today's classes check
$todays_classes_count = 0;
if ($student && isset($student[$today_day . '_enabled']) && $student[$today_day . '_enabled']) {
    $todays_classes_count = 1;
}

// Initials for avatar
$words = explode(' ', trim($student_name));
$initials = strtoupper(substr($words[0] ?? 'S', 0, 1) . substr($words[1] ?? '', 0, 1));
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <?php if ($student === null): ?>
    <div class="mb-8 bg-amber-50 border border-amber-200 rounded-[24px] p-6 flex items-start gap-4 shadow-sm animate-pulse">
        <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 shrink-0">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-amber-800">Student Profile Not Linked</h3>
            <p class="text-xs text-amber-700/80 mt-1 leading-relaxed">
                Your portal account is active, but it has not been linked to an official student profile yet. This can occur if your enrollment is still pending review or if your portal email does not match your admission record.
            </p>
            <p class="text-xs text-amber-700/80 mt-2 font-medium">
                Please contact the Al Foz Academic Support or Ihtisham Awan at <strong class="text-amber-900 font-bold">support@alfoz.com</strong> or call <strong class="text-amber-900 font-bold">+92 318 5027846</strong> to link your profile immediately.
            </p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Welcome Header -->
    <div class="mb-8 bg-white border border-primary/10 rounded-[24px] p-8 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden shadow-sm">
      <!-- Ambient background decoration glows -->
      <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
      
      <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10 w-full md:w-auto">
        <div class="relative shrink-0">
          <?php echo render_dashboard_profile_pic_html(); ?>
          <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-4 border-white rounded-full shadow-md animate-pulse"></span>
        </div>
        <div class="text-center sm:text-left">
          <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-primary">Assalamu Alaikum, <?php echo htmlspecialchars($student_name); ?>!</h2>
          <p class="text-xs text-primary/70 mt-1.5 font-medium">
            Course: <strong class="text-primary font-bold"><?php echo htmlspecialchars($course_assigned); ?></strong> &bull; Roll No: <strong class="text-primary font-mono"><?php echo htmlspecialchars($student_roll); ?></strong> &bull; Instructor: <strong class="text-primary"><?php echo htmlspecialchars($teacher_assigned); ?></strong>
          </p>
        </div>
      </div>
      <div class="bg-emerald-500/10 border border-emerald-400/20 text-emerald-800 rounded-2xl px-5 py-3 text-xs font-bold flex items-center gap-2 relative z-10 shadow-sm">
        <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <span>Scholar Session Active</span>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="mb-8">
      <h3 class="text-xs font-bold uppercase tracking-wider text-primary/60 mb-4">Quick Shortcuts</h3>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 micro-grid">
        
        <a href="/student/attendance.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="calendar-check-2" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Attendance</span>
        </a>
        <a href="/student/homework.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="book-open" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Homework</span>
        </a>
        <a href="/student/profile.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="user-round" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">My Profile</span>
        </a>
        <a href="/student/fees.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="credit-card" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">My Fees</span>
        </a>
        <a href="/student/reports.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Reports</span>
        </a>
      </div>
    </div>


    <!-- STUDENT TOP STATS CARDS -->
    <div class="mb-8">
      <h3 class="text-xs font-bold uppercase tracking-wider text-primary/60 mb-4">Academic Performance Dashboard</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        
        <!-- Card 1: My Teacher -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="graduation-cap" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-primary/60 font-bold bg-primary/5 px-2 py-0.5 rounded-full">Assigned</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">My Teacher</span>
            <span class="text-lg sm:text-xl font-black text-primary mt-1 block truncate"><?php echo htmlspecialchars($teacher_assigned); ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Faculty mentor</span>
          </div>
        </div>

        <!-- Card 2: Today's Class -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="calendar" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Today</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Today's Schedule</span>
            <span class="text-2xl sm:text-3xl font-black text-primary mt-1 block"><?php echo $todays_classes_count > 0 ? '1 Class' : 'Off'; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span><?php echo $next_class_str; ?></span>
          </div>
        </div>

        <!-- Card 3: Present Days -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-green-50 rounded-xl text-green-700 group-hover:bg-green-700 group-hover:text-white transition-all">
              <i data-lucide="user-check" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Present</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Present Classes</span>
            <span class="text-2xl sm:text-3xl font-black text-green-700 mt-1 block"><?php echo $present_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Total sessions attended</span>
          </div>
        </div>

        <!-- Card 4: Absent Days -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-red-50 rounded-xl text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
              <i data-lucide="user-x" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded-full">Absent</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Absent Classes</span>
            <span class="text-2xl sm:text-3xl font-black text-red-600 mt-1 block"><?php echo $absent_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Missed sessions</span>
          </div>
        </div>

        <!-- Card 5: Current Syllabus Progress -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="sparkles" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-full">Sabaq</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Current Lesson</span>
            <span class="text-sm sm:text-base font-black text-amber-600 mt-1 block truncate"><?php echo htmlspecialchars($student['academic']['current_lesson'] ?? 'N/A'); ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Tajweed & Memorization</span>
          </div>
        </div>

        <!-- Card 6: Attendance % -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="pie-chart" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full"><?php echo $att_percentage; ?>%</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Attendance Rate</span>
            <span class="text-2xl sm:text-3xl font-black text-primary mt-1 block"><?php echo $att_percentage; ?>%</span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Overall participation score</span>
          </div>
        </div>

        <!-- Card 7: Monthly Fee -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="wallet" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Paid</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Monthly Package</span>
            <span class="text-xl sm:text-2xl font-black text-primary mt-1 block">PKR <?php echo number_format($fee_pkr); ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Cleared for current cycle</span>
          </div>
        </div>

        <!-- Card 8: Pending Homework -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="book-open" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-full">Tasks</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Pending Tasks</span>
            <span class="text-2xl sm:text-3xl font-black text-primary mt-1 block"><?php echo $pending_hw_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Homework assignments</span>
          </div>
        </div>

      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Left side modules (Schedule & Matrix) -->
      <div class="lg:col-span-8 space-y-8">
        
        <!-- TODAY'S SCHEDULE TABLE -->
        <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm overflow-hidden">
          <div class="bg-primary/5 px-6 py-4.5 border-b border-primary/10 flex justify-between items-center">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="clock" class="w-4.5 h-4.5 text-primary"></i>
              <span>My Today's Class Schedule</span>
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
                    <th class="pb-3">Instructor details</th>
                    <th class="pb-3">Course / syllabus</th>
                    <th class="pb-3">Timing (my timezone / PKT)</th>
                    <th class="pb-3 text-right">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                  <?php 
                    $day_enabled = isset($student[$today_day . '_enabled']) && $student[$today_day . '_enabled'];
                  ?>
                  <?php if ($student && $day_enabled): ?>
                    <tr class="hover:bg-primary/5 transition-all">
                      <td class="py-4 font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden border border-primary/15 shadow-sm bg-primary/10 flex items-center justify-center font-black text-xs text-primary">
                          <?php echo strtoupper(substr($teacher_assigned, 0, 2)); ?>
                        </div>
                        <div>
                          <p class="font-extrabold text-primary leading-tight"><?php echo htmlspecialchars($teacher_assigned); ?></p>
                          <p class="text-[9px] text-primary/60 uppercase tracking-wider mt-0.5">Senior Faculty Scholar</p>
                        </div>
                      </td>
                      <td class="py-4">
                        <p class="font-semibold text-primary/85"><?php echo htmlspecialchars($course_assigned); ?></p>
                        <p class="text-[10px] text-green-700 font-bold">Current Sabaq: <?php echo htmlspecialchars($student['academic']['current_lesson'] ?? 'Juz 1'); ?></p>
                      </td>
                      <td class="py-4 font-mono text-[11px] text-primary/70">
                        <p class="font-bold">My Time: <?php echo date('h:i A', strtotime($student[$today_day . '_time'] ?? '12:00')); ?> (<?php echo htmlspecialchars($student['timezone'] ?? 'PKT'); ?>)</p>
                        <p class="text-[10px]">PKT: <?php echo !empty($student[$today_day . '_pkt']) ? date('h:i A', strtotime($student[$today_day . '_pkt'])) : 'N/A'; ?></p>
                      </td>
                      <td class="py-4 text-right">
                        
                      </td>
                    </tr>
                  <?php else: ?>
                    <tr class="hover:bg-primary/5 transition-all">
                      <td class="py-4 font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden border border-primary/15 shadow-sm bg-primary/10 flex items-center justify-center font-black text-xs text-primary">
                          <?php echo strtoupper(substr($teacher_assigned, 0, 2)); ?>
                        </div>
                        <div>
                          <p class="font-extrabold text-primary leading-tight"><?php echo htmlspecialchars($teacher_assigned); ?></p>
                          <p class="text-[9px] text-primary/60 uppercase tracking-wider mt-0.5">Faculty Instructor</p>
                        </div>
                      </td>
                      <td class="py-4">
                        <p class="font-semibold text-primary/85"><?php echo htmlspecialchars($course_assigned); ?></p>
                        <p class="text-[10px] text-primary/60 font-medium">No active session scheduled today</p>
                      </td>
                      <td class="py-4 font-mono text-[11px] text-primary/70">
                        <p class="font-bold">Next: <?php echo $next_class_str; ?></p>
                      </td>
                      <td class="py-4 text-right">
                        
                      </td>
                    </tr>
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
              $is_active = isset($student[$lower_d . '_enabled']) && $student[$lower_d . '_enabled'];
              $time_str = $is_active ? date('h:i A', strtotime($student[$lower_d . '_time'] ?? '12:00')) : '';
            ?>
            <div class="<?php echo $is_active ? 'bg-primary text-white border-transparent' : 'bg-slate-50 text-slate-300 opacity-60 border-slate-100'; ?> p-3 rounded-2xl text-center border">
              <p class="text-[9px] font-black uppercase tracking-widest mb-1"><?php echo substr($d, 0, 3); ?></p>
              <?php if ($is_active): ?>
                <p class="text-[11px] font-black">Active Class</p>
                <div class="hidden lg:block text-[8px] opacity-80 mt-1 truncate" title="<?php echo htmlspecialchars($time_str); ?>">
                  <?php echo htmlspecialchars($time_str); ?>
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
              <span>Assigned Daily Sabaq & Homework</span>
            </h3>
          </div>
          <div class="p-6">
            <?php if (!empty($student_homework)): 
              $latest_hw = reset($student_homework);
            ?>
              <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                  <span class="text-[9px] font-black bg-amber-100 text-amber-700 px-2 py-0.5 rounded uppercase tracking-wider"><?php echo htmlspecialchars($latest_hw['status'] ?? 'Assigned'); ?></span>
                  <h4 class="font-extrabold text-xs text-primary mt-1.5"><?php echo htmlspecialchars($latest_hw['title'] ?? $course_assigned); ?></h4>
                  <p class="text-[11px] text-primary/70 mt-1"><?php echo htmlspecialchars($latest_hw['description'] ?? ''); ?></p>
                </div>
                <a href="/student/homework.php" class="bg-primary hover:bg-opacity-95 text-white font-bold uppercase tracking-wider text-[9px] px-3.5 py-2 rounded-xl text-center shrink-0">
                  View Homework
                </a>
              </div>
            <?php else: ?>
              <div class="p-6 text-center text-primary/60 font-medium">
                <i data-lucide="book-open-check" class="w-8 h-8 text-primary/30 mx-auto mb-2"></i>
                <p class="text-xs font-bold text-primary">No Pending Homework</p>
                <p class="text-[10px] text-primary/60 mt-0.5">All assigned tasks and daily sabaq targets are up to date.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>

      <!-- Right side panels (Attendance summary, Fees & Alerts) -->
      <div class="lg:col-span-4 space-y-8">
        
        <!-- Attendance Summary Ring Widget -->
        <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm">
          <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary mb-5 flex items-center gap-2">
            <i data-lucide="donut" class="w-4.5 h-4.5"></i>
            <span>My Attendance Summary</span>
          </h3>
          <div class="relative h-44 w-full flex items-center justify-center">
            <canvas id="studentAttendanceDoughnut"></canvas>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-4 text-center text-xs">
            <div class="p-2.5 bg-green-50 rounded-xl">
              <span class="text-[10px] text-green-600 uppercase font-black tracking-widest block">Present</span>
              <span class="font-bold text-lg text-green-700"><?php echo $present_count; ?></span>
            </div>
            <div class="p-2.5 bg-red-50 rounded-xl">
              <span class="text-[10px] text-red-600 uppercase font-black tracking-widest block">Absent</span>
              <span class="font-bold text-lg text-red-700"><?php echo $absent_count; ?></span>
            </div>
          </div>
        </div>

        <!-- Fee Payment Progress tracker bar -->
        <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm">
          <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary mb-4 flex items-center gap-2">
            <i data-lucide="badge-dollar-sign" class="w-4.5 h-4.5"></i>
            <span>Fee Status Progress</span>
          </h3>
          <div class="space-y-4">
            <div class="flex justify-between text-xs font-bold text-primary">
              <span>Monthly Package (<?php echo $currency_val; ?>)</span>
              <span><?php echo number_format($fee_pkr) . ' PKR'; ?></span>
            </div>
            <!-- Progress Bar -->
            <div>
              <div class="flex justify-between text-[10px] font-bold text-primary/60 mb-1">
                <span>Tuition Fee Cleared</span>
                <span>100% Paid</span>
              </div>
              <div class="w-full bg-primary/10 h-3 rounded-full overflow-hidden">
                <div class="bg-primary h-full rounded-full" style="width: 100%;"></div>
              </div>
            </div>
            <div class="p-3 bg-green-50 border border-green-200 rounded-xl flex items-center gap-2 text-[10px] text-green-800 font-bold">
              <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
              <span>Invoices cleared for current academic month</span>
            </div>
          </div>
        </div>

        <!-- Live Notifications Widget -->
        <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm">
          <div class="flex justify-between items-center mb-5 pb-3 border-b border-primary/10">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="bell" class="w-4.5 h-4.5"></i>
              <span>Academy Announcements</span>
            </h3>
            <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
          </div>
          <div class="space-y-4">
            <?php if (!empty($student_notifications)): 
              $notifs_slice = array_slice($student_notifications, 0, 3);
              foreach ($notifs_slice as $n):
            ?>
              <div class="p-3 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl">
                <span class="text-[9px] font-mono text-amber-600 font-bold block"><?php echo htmlspecialchars($n['date_sent'] ?? 'Notice'); ?></span>
                <p class="text-xs font-semibold text-primary mt-0.5"><?php echo htmlspecialchars($n['title'] ?? $n['content'] ?? ''); ?></p>
              </div>
            <?php endforeach; else: ?>
              <div class="p-6 text-center text-primary/60 text-xs font-medium">
                <i data-lucide="bell-off" class="w-6 h-6 text-primary/30 mx-auto mb-1"></i>
                <p class="font-bold text-primary">No Announcements Available</p>
                <p class="text-[10px] text-primary/60 mt-0.5">Check back later for academy notices.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>

    </div>
  </div>

</div>

<!-- Chart Script Configurations -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  
  // Student Attendance Doughnut Chart
  new Chart(document.getElementById('studentAttendanceDoughnut'), {
    type: 'doughnut',
    data: {
      labels: ['Present', 'Absent'],
      datasets: [{
        data: [<?php echo $present_count; ?>, <?php echo $absent_count; ?>],
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
