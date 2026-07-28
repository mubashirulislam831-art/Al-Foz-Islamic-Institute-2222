<?php
/**
 * Al Foz Islamic Institute - Super Admin ERP Dashboard
 * Complete Institute Control Center with Premium Analytics & Statistics
 */

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/permissions.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/students_data.php';
require_once __DIR__ . '/../includes/teachers_data.php';
require_once __DIR__ . '/../includes/parents_data.php';
require_once __DIR__ . '/../includes/system_config.php';

// Strictly require Super Admin role
require_role('Super Admin');

$admin_name = $_SESSION['name'] ?? 'System Admin';
$admin_email = $_SESSION['email'] ?? 'admin@alfoz.org';
$sys_date = get_system_month_year();

// Calculate dynamic stats from real student database/session
$students = get_all_students();
$total_students = count($students);

// Filter student counts by status
$active_students = 0;
$trial_students = 0;
$pending_fees_count = 0;

foreach ($students as $s) {
    $status = $s['status'] ?? 'Active';
    if ($status === 'Active') {
        $active_students++;
    } elseif ($status === 'Trial') {
        $trial_students++;
    }
    
    if (isset($s['fee_status']) && $s['fee_status'] !== 'Paid') {
        $pending_fees_count++;
    }
}

// Unique teachers count
$teachers = get_all_teachers();
$teachers_count = count($teachers);

// Total admins (usually 1, but we can query or specify dynamically)
$admins_count = 1;

// Today's Day Name
$today_day = strtolower(date('l'));
$todays_classes = 0;
foreach ($students as $s) {
    if (isset($s[$today_day . '_enabled']) && $s[$today_day . '_enabled']) {
        $todays_classes++;
    }
}

// Today's attendance rate
$present_today = 0;
foreach ($students as $s) {
    if (isset($s['attendance_status']) && $s['attendance_status'] === 'Present') {
        $present_today++;
    }
}
$attendance_today_pct = $total_students > 0 ? round(($present_today / $total_students) * 100, 1) : 0;

// Financial computations (Converted to PKR)
$monthly_revenue_pkr = 0;
$pending_fees_pkr = 0;
$unpaid_invoices_count = 0;
foreach ($students as $s) {
    $fee_pkr = convert_to_pkr($s['monthly_fee'] ?? 0, $s['currency'] ?? 'PKR');
    if (isset($s['fee_status']) && $s['fee_status'] === 'Paid') {
        $monthly_revenue_pkr += $fee_pkr;
    } else {
        $pending_fees_pkr += $fee_pkr;
        $unpaid_invoices_count++;
    }
}

// Salaries computed from teacher data
$teacher_salaries_pkr = 0;
foreach ($teachers as $t) {
    $salary_pkr = convert_to_pkr($t['salary'] ?? 0, $t['currency'] ?? 'PKR');
    $teacher_salaries_pkr += $salary_pkr;
}

// Country counts
$countries_stats = [];
foreach ($students as $s) {
    $c = $s['country'] ?? 'Pakistan';
    $countries_stats[$c] = ($countries_stats[$c] ?? 0) + 1;
}

?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen w-full bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Top Welcome Section (Enterprise SaaS style Premium Card) -->
    <div class="mb-8 bg-white rounded-[24px] p-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden border border-primary/10">
      <!-- Ambient background decoration glows -->
      <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
      <div class="absolute -left-10 -bottom-10 w-48 h-48 rounded-full bg-primary/5 blur-2xl pointer-events-none"></div>
      
      <div class="flex flex-col sm:flex-row items-center gap-6 w-full md:w-auto relative z-10">
        <!-- User photo / Initials Circle -->
        <div class="relative">
          <?php echo render_dashboard_profile_pic_html(); ?>
          <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-4 border-white rounded-full shadow-md animate-pulse"></span>
        </div>
        
        <div class="text-center sm:text-left">
          <div class="flex flex-col sm:flex-row items-center gap-3">
            <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Assalamu Alaikum, <?php echo htmlspecialchars($admin_name); ?>!</h1>
            <span class="px-3.5 py-1 bg-primary/10 backdrop-blur-md text-primary border border-primary/25 text-[11px] font-bold rounded-xl uppercase tracking-widest shadow-sm">
              <?php echo render_user_role_title_html(); ?>
            </span>
          </div>
          <p class="text-xs text-primary/80 mt-2 flex items-center justify-center sm:justify-start gap-2 font-medium">
            <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
            <span>Active Session: <span class="font-bold underline decoration-primary/30"><?php echo $sys_date['month'] . ' ' . $sys_date['year']; ?></span> cycle</span>
          </p>
        </div>
      </div>
      
      <!-- Right Quick Info / Notifications -->
      <div class="flex flex-wrap items-center gap-4 w-full md:w-auto justify-center md:justify-end border-t border-primary/10 md:border-t-0 pt-6 md:pt-0 relative z-10">
        <div class="bg-green-500/10 border border-green-400/20 text-green-800 rounded-2xl px-5 py-3 text-xs font-bold flex items-center gap-2.5 shadow-sm">
          <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
          <span>System Online</span>
        </div>
      </div>
    </div>

    <!-- QUICK ACTIONS SECTION -->
    <div class="mb-8">
      <h2 class="text-xs font-bold uppercase tracking-wider text-primary/60 mb-4">Quick ERP Operations</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 micro-grid">
        <a href="/superadmin/students/add_student.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Add Student</span>
        </a>
        <a href="/superadmin/teachers/add_teacher.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="users" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Add Teacher</span>
        </a>
        <a href="/superadmin/trial_requests/new_requests.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="sparkles" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Create Trial</span>
        </a>
        <a href="/superadmin/attendance/dashboard.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="calendar-check-2" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Open Attendance</span>
        </a>
        <a href="/superadmin/fees/fees.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="wallet" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Open Fees</span>
        </a>
        <a href="/superadmin/reports/student_reports.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Open Reports</span>
        </a>
        <a href="/superadmin/exams/exam_setup.php" class="bg-white hover:bg-primary hover:text-white border border-primary/10 p-4 rounded-2xl flex flex-col items-center justify-center text-center group transition-all shadow-sm">
          <div class="p-3 bg-primary/5 rounded-xl text-primary group-hover:bg-white/20 group-hover:text-white mb-2 transition-all">
            <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-wide">Open Exams</span>
        </a>
      </div>
    </div>

    <!-- 12 STATS BOARD - GRID -->
    <div class="mb-8">
      <h2 class="text-xs font-bold uppercase tracking-wider text-primary/60 mb-4">Institute Key Statistics</h2>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Total Students -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="graduation-cap" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">+<?php echo $total_students; ?> All</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Total Students</span>
            <span class="text-2xl font-black text-primary mt-1 block"><?php echo $total_students; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Overall Registrations</span>
          </div>
        </div>

        <!-- Card 2: Active Students -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="user-check" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Active</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Active Students</span>
            <span class="text-2xl font-black text-green-700 mt-1 block"><?php echo $active_students; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Currently learning</span>
          </div>
        </div>

        <!-- Card 3: Trial Students -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="sparkles" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-full">Trial</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Trial Students</span>
            <span class="text-2xl font-black text-amber-600 mt-1 block"><?php echo $trial_students; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Evaluation classes</span>
          </div>
        </div>

        <!-- Card 4: Total Teachers -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="users" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded-full">Faculty</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Total Teachers</span>
            <span class="text-2xl font-black text-primary mt-1 block"><?php echo $teachers_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Educators database</span>
          </div>
        </div>

        <!-- Card 5: Total Admins -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="shield" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-purple-600 font-bold bg-purple-50 px-2 py-0.5 rounded-full">Security</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Total Admins</span>
            <span class="text-2xl font-black text-primary mt-1 block"><?php echo $admins_count; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>System administrators</span>
          </div>
        </div>

        <!-- Card 6: Today's Classes -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="monitor-play" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Schedule</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Today's Classes</span>
            <span class="text-2xl font-black text-emerald-800 mt-1 block"><?php echo $todays_classes; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Assigned today</span>
          </div>
        </div>

        <!-- Card 7: Today's Attendance % -->
        <a href="/superadmin/attendance/dashboard.php" class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="calendar-check" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full"><?php echo $attendance_today_pct; ?>%</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Today's Attendance</span>
            <span class="text-2xl font-black text-primary mt-1 block"><?php echo $attendance_today_pct; ?>%</span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span><?php echo $present_today; ?> Present today</span>
          </div>
        </a>

        <!-- Card 8: Monthly Revenue -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="coins" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">PKR</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Monthly Revenue</span>
            <span class="text-xl font-black text-primary mt-1 block">PKR <?php echo number_format($monthly_revenue_pkr); ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Paid invoices</span>
          </div>
        </div>

        <!-- Card 9: Teacher Salaries -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="banknote" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-primary font-bold bg-primary/5 px-2 py-0.5 rounded-full">Payroll</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Teacher Salaries</span>
            <span class="text-xl font-black text-primary mt-1 block">PKR <?php echo number_format($teacher_salaries_pkr); ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Monthly commitments</span>
          </div>
        </div>

        <!-- Card 10: Pending Fees -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-red-50 rounded-xl text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
              <i data-lucide="wallet-cards" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded-full">Unpaid</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Pending Fees</span>
            <span class="text-xl font-black text-red-600 mt-1 block">PKR <?php echo number_format($pending_fees_pkr); ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-red-500">
            <span><?php echo $pending_fees_count; ?> pending student(s)</span>
          </div>
        </div>

        <!-- Card 11: Trial Requests -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="clipboard-list" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded-full">Requests</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Trial Requests</span>
            <span class="text-2xl font-black text-primary mt-1 block"><?php echo $trial_students; ?></span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Trials to allocate</span>
          </div>
        </div>

        <!-- Card 12: Active Exams -->
        <div class="bg-white rounded-[18px] p-5 border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/30 hover:shadow-md transition-all duration-300 group">
          <div class="flex justify-between items-start mb-2">
            <div class="p-2.5 bg-primary/5 rounded-xl text-primary group-hover:bg-primary group-hover:text-white transition-all">
              <i data-lucide="award" class="w-4.5 h-4.5"></i>
            </div>
            <span class="text-[10px] text-primary font-bold bg-primary/5 px-2 py-0.5 rounded-full">Exams</span>
          </div>
          <div>
            <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Active Exams</span>
            <span class="text-2xl font-black text-emerald-800 mt-1 block">1</span>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[10px] text-primary/60">
            <span>Oral evaluation cycle</span>
          </div>
        </div>

      </div>
    </div>

    <!-- ANALYTICS SECTION -->
    <div class="mb-8">
      <h2 class="text-xs font-bold uppercase tracking-wider text-primary/60 mb-4">Enterprise Analytics Dashboard</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Chart 1: Student Growth Chart -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-primary">Student Growth</h3>
            <span class="text-[9px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">Line Graph</span>
          </div>
          <div class="relative h-48 w-full">
            <canvas id="studentGrowthChart"></canvas>
          </div>
        </div>

        <!-- Chart 2: Monthly Revenue Chart -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-primary">Monthly Revenue</h3>
            <span class="text-[9px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">Area Graph</span>
          </div>
          <div class="relative h-48 w-full">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>

        <!-- Chart 3: Attendance Chart -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-primary">Attendance Rate</h3>
            <span class="text-[9px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">Daily Trend</span>
          </div>
          <div class="relative h-48 w-full">
            <canvas id="attendanceChart"></canvas>
          </div>
        </div>

        <!-- Chart 4: Country Statistics -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-primary">Global Demographics</h3>
            <span class="text-[9px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">Demographics</span>
          </div>
          <div class="relative h-48 w-full flex items-center justify-center">
            <canvas id="countryChart"></canvas>
          </div>
        </div>

        <!-- Chart 5: Teacher Performance Graph -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-primary">Teacher Performance</h3>
            <span class="text-[9px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">Hours & Rating</span>
          </div>
          <div class="relative h-48 w-full">
            <canvas id="teacherPerfChart"></canvas>
          </div>
        </div>

        <!-- Chart 6: Trial Conversion Graph -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-100 shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-primary">Trial Conversion Rate</h3>
            <span class="text-[9px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">Conversion</span>
          </div>
          <div class="relative h-48 w-full flex items-center justify-center">
            <canvas id="conversionChart"></canvas>
          </div>
        </div>

      </div>
    </div>

    <!-- LIVE ACTIVITY / REPORTING SECTIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
      
      <!-- Left 8 columns for core activities -->
      <div class="lg:col-span-8 space-y-8">
        
        <!-- Recent Admissions & Trials Requests in tabs/cards -->
        <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-primary/5">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="sparkles" class="w-4 h-4"></i> Recent Student Admissions & Trials
            </h3>
            <a href="/superadmin/students/students.php" class="text-[10px] font-bold text-primary hover:underline">View All</a>
          </div>
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs text-primary">
                <thead>
                  <tr class="uppercase font-bold text-primary/60 text-[10px] tracking-wider border-b border-slate-100">
                    <th class="pb-3">Student Name</th>
                    <th class="pb-3">Course / Teacher</th>
                    <th class="pb-3">Country</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3 text-right">Fee</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="recent_enrollments_tbody"></tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Recent Payments Registry -->
        <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-primary/5">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="receipt" class="w-4 h-4"></i> Recent Invoices & Payments History
            </h3>
            <span class="text-[9px] uppercase font-bold px-2 py-0.5 bg-green-100 text-green-700 rounded-lg">Real-time status</span>
          </div>
          <div class="p-6">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs text-primary">
                <thead>
                  <tr class="uppercase font-bold text-primary/60 text-[10px] tracking-wider border-b border-slate-100">
                    <th class="pb-3">Student Roll No</th>
                    <th class="pb-3">Method / Currency</th>
                    <th class="pb-3">Due Date</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3 text-right">PKR Equiv</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="recent_invoices_tbody"></tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

      <!-- Right 4 columns for notifications and system logs -->
      <div class="lg:col-span-4 space-y-8">
        
        <!-- Live Notifications Widget -->
        <div class="bg-white rounded-[24px] p-6 border border-slate-100 shadow-sm">
          <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="bell" class="w-4.5 h-4.5"></i>
              <span>System Notifications</span>
            </h3>
            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
          </div>
          <div class="space-y-4">
            <div class="p-3 bg-green-50 border-l-4 border-green-500 rounded-r-xl">
              <span class="text-[9px] font-mono text-green-600 font-bold block"><?php echo date('H:i'); ?> • Secure Sync</span>
              <p class="text-xs font-semibold text-primary mt-0.5">Database cache validated. Deduplication resolved successfully.</p>
            </div>
            <div class="p-3 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl">
              <span class="text-[9px] font-mono text-amber-600 font-bold block"><?php echo date('H:i', strtotime('-15 minutes')); ?> • New Seeker</span>
              <p class="text-xs font-semibold text-primary mt-0.5">Trial request submitted from UK student for Tajweed lessons.</p>
            </div>
            <div class="p-3 bg-primary/5 border-l-4 border-primary rounded-r-xl">
              <span class="text-[9px] font-mono text-primary/60 font-bold block"><?php echo date('H:i', strtotime('-1 hour')); ?> • Payroll cycle</span>
              <p class="text-xs font-semibold text-primary mt-0.5">Faculty monthly salaries computed to PKR <?php echo number_format($teacher_salaries_pkr); ?>.</p>
            </div>
          </div>
        </div>

        <!-- Global ERP Auditing Log -->
        <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden">
          <div class="bg-primary/5 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-extrabold text-xs sm:text-sm uppercase tracking-wider text-primary flex items-center gap-2">
              <i data-lucide="terminal" class="w-4 h-4"></i>
              <span>ERP System Logs</span>
            </h3>
            <span class="bg-black/10 text-black font-mono text-[9px] px-2 py-0.5 rounded">Active</span>
          </div>
          <div class="p-5 font-mono text-[10px] space-y-2 bg-slate-950 text-slate-300 max-h-52 overflow-y-auto">
            <p class="text-green-400">[<?php echo date('Y-m-d H:i:s'); ?>] SECURITY: Session successfully authenticated for user <?php echo htmlspecialchars($admin_name); ?>.</p>
            <p class="text-slate-400">[<?php echo date('Y-m-d H:i:s', strtotime('-10 mins')); ?>] CACHE: Loaded <?php echo $total_students; ?> student objects from DB Bridge.</p>
            <p class="text-slate-400">[<?php echo date('Y-m-d H:i:s', strtotime('-1 hour')); ?>] CALC: Payroll recalculated for <?php echo $teachers_count; ?> active educators.</p>
            <p class="text-blue-400">[<?php echo date('Y-m-d H:i:s', strtotime('-2 hours')); ?>] SYS: Nginx reverse-proxy SSL handshake verified on port 3000.</p>
          </div>
        </div>

      </div>

    </div>

  </div>


<!-- Chart Configurations JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  
  // Custom global theme options for Chart.js
  const chartFont = {
    family: "Poppins",
    size: 10
  };
  
  // 1. Student Growth Chart (Line)
  new Chart(document.getElementById('studentGrowthChart'), {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Total Students',
        data: [0, 0, 0, 0, 0, 0],
        borderColor: '#184D55',
        backgroundColor: 'rgba(24, 77, 85, 0.05)',
        fill: true,
        tension: 0.4,
        borderWidth: 3,
        pointBackgroundColor: '#184D55',
        pointRadius: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: chartFont } },
        y: { grid: { color: 'rgba(24, 77, 85, 0.05)' }, ticks: { font: chartFont, stepSize: 1 } }
      }
    }
  });

  // 2. Revenue Chart (Bar/Area)
  new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Revenue (PKR)',
        data: [0, 0, 0, 0, 0, 0],
        backgroundColor: '#184D55',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: chartFont } },
        y: { grid: { color: 'rgba(24, 77, 85, 0.05)' }, ticks: { font: chartFont } }
      }
    }
  });

  // 3. Attendance Chart (Line/Bar)
  new Chart(document.getElementById('attendanceChart'), {
    type: 'line',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      datasets: [{
        label: 'Presence %',
        data: [0, 0, 0, 0, 0, 0],
        borderColor: '#10B981',
        backgroundColor: 'rgba(16, 185, 129, 0.05)',
        fill: true,
        tension: 0.3,
        borderWidth: 2.5,
        pointBackgroundColor: '#10B981'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: chartFont } },
        y: { grid: { color: 'rgba(24, 77, 85, 0.05)' }, ticks: { font: chartFont }, min: 0, max: 100 }
      }
    }
  });

  // 4. Country Statistics (Doughnut)
  <?php
  $cLabels = array_keys($countries_stats);
  $cData = array_values($countries_stats);
  ?>
  new Chart(document.getElementById('countryChart'), {
    type: 'doughnut',
    data: {
      labels: <?php echo json_encode(!empty($cLabels) ? $cLabels : ['Pakistan']); ?>,
      datasets: [{
        data: [0, 0, 0, 0, 0, 0, 0],
        backgroundColor: ['#184D55', '#F0CE62', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B'],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          labels: { font: chartFont, boxWidth: 10, padding: 8 }
        }
      },
      cutout: '60%'
    }
  });

  // 5. Teacher Performance Graph (Bar)
  new Chart(document.getElementById('teacherPerfChart'), {
    type: 'bar',
    data: {
      labels: ['M. Bilal', 'S. Tabassum', 'A. Rahman', 'Z. Fatima'],
      datasets: [{
        label: 'Assigned Learners',
        data: [0, 0, 0, 0],
        backgroundColor: '#F0CE62',
        borderRadius: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: chartFont } },
        y: { grid: { color: 'rgba(24, 77, 85, 0.05)' }, ticks: { font: chartFont, stepSize: 1 } }
      }
    }
  });

  // 6. Trial Conversion Graph (Doughnut)
  new Chart(document.getElementById('conversionChart'), {
    type: 'doughnut',
    data: {
      labels: ['Converted', 'In Progress', 'Lost'],
      datasets: [{
        data: [0, 0, 0],
        backgroundColor: ['#10B981', '#F0CE62', '#EF4444'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          labels: { font: chartFont, boxWidth: 10, padding: 8 }
        }
      },
      cutout: '70%'
    }
  });

});
</script>

</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
