<?php
/**
 * Al Foz Islamic Institute - Student Management System
 * Students Dashboard
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);

// Aggregate stats from session
$all_students = get_all_students();
$stats = [
    'total' => count($all_students),
    'trial' => 0,
    'active' => 0,
    'on_leave' => 0,
    'deactivated' => 0,
    'completed' => 0,
    'monthly_revenue' => 0,
    'attendance_avg' => 0
];

foreach ($all_students as $s) {
    switch ($s['status']) {
        case 'Trial': $stats['trial']++; break;
        case 'Active': $stats['active']++; break;
        case 'On Leave': $stats['on_leave']++; break;
        case 'Deactivated': $stats['deactivated']++; break;
        case 'Completed': $stats['completed']++; break;
    }
    
    // Revenue calculation (converting to PKR for total)
    if ($s['status'] === 'Active') {
        $stats['monthly_revenue'] += convert_to_pkr($s['monthly_fee'], $s['currency']);
    }
    
    $stats['attendance_avg'] += $s['attendance']['percentage'] ?? 0;
}

if ($stats['total'] > 0) {
    $stats['attendance_avg'] = round($stats['attendance_avg'] / $stats['total'], 1);
}

?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-primary/40 mb-6">
      <a href="/admin/dashboard.php" class="hover:text-primary transition-colors">Portal</a>
      <i data-lucide="chevron-right" class="w-3 h-3"></i>
      <span class="text-primary">Students Dashboard</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-primary">Student Analytics & Control</h1>
      <p class="text-xs text-primary/60 mt-1 uppercase tracking-wider font-bold">Real-time lifecycle monitoring of Al Foz scholars.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <!-- Total Students -->
      <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
          <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center mb-4">
            <i data-lucide="users" class="w-5 h-5"></i>
          </div>
          <h3 class="text-[10px] font-black text-primary/40 uppercase tracking-widest">Total Students</h3>
          <p class="text-3xl font-black text-primary mt-1"><?php echo $stats['total']; ?></p>
        </div>
      </div>

      <!-- Active Students -->
      <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
          <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/20">
            <i data-lucide="user-check" class="w-5 h-5"></i>
          </div>
          <h3 class="text-[10px] font-black text-primary/40 uppercase tracking-widest">Active Seekers</h3>
          <p class="text-3xl font-black text-emerald-600 mt-1"><?php echo $stats['active']; ?></p>
        </div>
      </div>

      <!-- Trial Students -->
      <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
          <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center mb-4 shadow-lg shadow-amber-500/20">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
          </div>
          <h3 class="text-[10px] font-black text-primary/40 uppercase tracking-widest">Trial Evaluation</h3>
          <p class="text-3xl font-black text-amber-600 mt-1"><?php echo $stats['trial']; ?></p>
        </div>
      </div>

      <!-- Monthly Revenue -->
      <div class="bg-primary rounded-[24px] p-6 border border-white/10 shadow-xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative text-white">
          <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center mb-4 border border-white/20">
            <i data-lucide="wallet" class="w-5 h-5"></i>
          </div>
          <h3 class="text-[10px] font-black text-white/40 uppercase tracking-widest">Monthly Revenue (PKR)</h3>
          <p class="text-2xl font-black mt-1"><?php echo number_format($stats['monthly_revenue']); ?></p>
          <div class="mt-2 flex items-center gap-1">
            <span class="text-[9px] font-bold text-white/60 uppercase">Est. Conversion</span>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- On Leave -->
        <div class="bg-white rounded-[24px] p-5 border border-primary/10 shadow-sm text-center">
            <h4 class="text-[9px] font-black text-primary/40 uppercase tracking-widest">On Leave</h4>
            <p class="text-xl font-black text-indigo-600 mt-1"><?php echo $stats['on_leave']; ?></p>
        </div>
        <!-- Deactivated -->
        <div class="bg-white rounded-[24px] p-5 border border-primary/10 shadow-sm text-center">
            <h4 class="text-[9px] font-black text-primary/40 uppercase tracking-widest">Deactivated</h4>
            <p class="text-xl font-black text-rose-600 mt-1"><?php echo $stats['deactivated']; ?></p>
        </div>
        <!-- Completed -->
        <div class="bg-white rounded-[24px] p-5 border border-primary/10 shadow-sm text-center">
            <h4 class="text-[9px] font-black text-primary/40 uppercase tracking-widest">Completed</h4>
            <p class="text-xl font-black text-emerald-600 mt-1"><?php echo $stats['completed']; ?></p>
        </div>
        <!-- Attendance % -->
        <div class="bg-white rounded-[24px] p-5 border border-primary/10 shadow-sm text-center">
            <h4 class="text-[9px] font-black text-primary/40 uppercase tracking-widest">Avg Attendance</h4>
            <p class="text-xl font-black text-primary mt-1"><?php echo $stats['attendance_avg']; ?>%</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6 border-b border-primary/5 pb-4">Lifecycle Shortcuts</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="/admin/students/add_student.php" class="flex flex-col items-center justify-center p-6 bg-primary/5 hover:bg-primary hover:text-white rounded-2xl transition-all group">
                    <i data-lucide="user-plus" class="w-6 h-6 mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Enroll Student</span>
                </a>
                <a href="/admin/students/students.php" class="flex flex-col items-center justify-center p-6 bg-primary/5 hover:bg-primary hover:text-white rounded-2xl transition-all group">
                    <i data-lucide="users-round" class="w-6 h-6 mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">View Registry</span>
                </a>
                <a href="/admin/attendance/leave_management.php" class="flex flex-col items-center justify-center p-6 bg-primary/5 hover:bg-primary hover:text-white rounded-2xl transition-all group">
                    <i data-lucide="calendar-clock" class="w-6 h-6 mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Leaves & Makeup</span>
                </a>
                <a href="/admin/students/student_reports.php" class="flex flex-col items-center justify-center p-6 bg-primary/5 hover:bg-primary hover:text-white rounded-2xl transition-all group">
                    <i data-lucide="file-bar-chart-2" class="w-6 h-6 mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">System Reports</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity Placeholder -->
        <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-6 border-b border-primary/5 pb-4">Node Activity Feed</h3>
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/20"></div>
                    <div class="flex-grow">
                        <p class="text-xs font-bold text-primary">New Admission: Yusuf Mansoor</p>
                        <p class="text-[9px] text-primary/40 uppercase font-bold">2 minutes ago • STU-101</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-amber-500 shadow-lg shadow-amber-500/20"></div>
                    <div class="flex-grow">
                        <p class="text-xs font-bold text-primary">Trial Completed: Zainab Al-Farsi</p>
                        <p class="text-[9px] text-primary/40 uppercase font-bold">1 hour ago • Evaluation Pending</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-primary/30 italic">
                    <div class="flex-grow text-center py-4">
                        <p class="text-[9px] font-bold uppercase tracking-widest">End of current buffer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
