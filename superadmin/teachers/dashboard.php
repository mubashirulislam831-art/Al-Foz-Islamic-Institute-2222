<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Management ERP Dashboard
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/teachers_data.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

$teachers = get_all_teachers();
$students = get_all_students();

// Calculate Teacher Stats
$total_faculty = count($teachers);
$active_teachers = 0;
$under_training = 0;
$probation = 0;
$senior_scholars = 0;
$inactive = 0;

foreach ($teachers as $t) {
    if ($t['status'] === 'Active') $active_teachers++;
    if ($t['status'] === 'Training') $under_training++;
    if ($t['status'] === 'Probation') $probation++;
    if ($t['status'] === 'Senior') $senior_scholars++;
    if ($t['status'] === 'Inactive') $inactive++;
}

// Calculate Student Stats
$total_students = count($students);
$trial_students = 0;
foreach ($students as $s) {
    if (isset($s['type']) && $s['type'] === 'Trial') $trial_students++;
}

// Monthly Salary (Dummy for now as we don't have full payroll data in session)
$total_salary = 0;
foreach ($teachers as $t) {
    $total_salary += (float)($t['salary'] ?? 0);
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Teachers ERP Dashboard</h1>
        <p class="text-xs text-primary/70 uppercase tracking-wider font-bold mt-1">Real-time faculty auditing, attendance analytics, and payroll metrics.</p>
      </div>
      <div>
        <a href="teachers.php" class="bg-primary hover:bg-primary/95 text-[#F7FAFF] px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all inline-block">
          View Teachers List
        </a>
      </div>
    </div>

    <!-- CORE ERP SUMMARY HIGHLIGHTS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      
      <!-- Total & Active Scholars -->
      <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm relative overflow-hidden group hover:border-primary/30 transition-all">
        <div class="text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-2">Total Faculty</div>
        <div class="text-3xl font-black text-primary"><?php echo $total_faculty; ?></div>
        <div class="text-[9px] font-bold text-emerald-600 mt-2 bg-emerald-50 inline-block px-2 py-0.5 rounded"><?php echo $active_teachers; ?> Active Teachers</div>
      </div>

      <!-- Students Metrics -->
      <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm relative overflow-hidden group hover:border-primary/30 transition-all">
        <div class="text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-2">Student Assignment</div>
        <div class="text-3xl font-black text-primary"><?php echo $total_students; ?></div>
        <div class="text-[9px] font-bold text-amber-600 mt-2 bg-amber-50 inline-block px-2 py-0.5 rounded"><?php echo $trial_students; ?> Trial Students</div>
      </div>

      <!-- Financial Metrics -->
      <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm relative overflow-hidden group hover:border-primary/30 transition-all">
        <div class="text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-2">Total Monthly Salary</div>
        <div class="text-3xl font-black text-primary"><?php echo format_currency($total_salary); ?></div>
        <div class="text-[9px] font-bold text-rose-600 mt-2 bg-rose-50 inline-block px-2 py-0.5 rounded">0 Pending Salary</div>
      </div>

      <!-- Attendance Overall -->
      <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm relative overflow-hidden group hover:border-primary/30 transition-all">
        <div class="text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-2">Attendance Rate</div>
        <div class="text-3xl font-black text-primary">--%</div>
        <div class="text-[9px] font-bold text-indigo-600 mt-2 bg-indigo-50 inline-block px-2 py-0.5 rounded">No sessions recorded</div>
      </div>

    </div>

    <!-- MAIN ERP PANELS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Status Distribution (Faculty Breakdown) -->
      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 lg:col-span-2">
        <h3 class="text-sm font-bold text-primary uppercase tracking-wider mb-5 flex items-center gap-1.5">
          <i data-lucide="pie-chart" class="w-4.5 h-4.5 text-primary"></i> Faculty Status Distribution
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
          <div class="p-3 border border-primary/10 rounded-xl bg-transparent text-center">
            <span class="text-[9px] text-primary/60 font-bold uppercase tracking-wider block mb-1">Active</span>
            <span class="text-xl font-black text-emerald-600"><?php echo $active_teachers; ?></span>
          </div>
          <div class="p-3 border border-primary/10 rounded-xl bg-transparent text-center">
            <span class="text-[9px] text-primary/60 font-bold uppercase tracking-wider block mb-1">Under Training</span>
            <span class="text-xl font-black text-primary"><?php echo $under_training; ?></span>
          </div>
          <div class="p-3 border border-primary/10 rounded-xl bg-transparent text-center">
            <span class="text-[9px] text-primary/60 font-bold uppercase tracking-wider block mb-1">Probation</span>
            <span class="text-xl font-black text-amber-600"><?php echo $probation; ?></span>
          </div>
          <div class="p-3 border border-primary/10 rounded-xl bg-transparent text-center">
            <span class="text-[9px] text-primary/60 font-bold uppercase tracking-wider block mb-1">Senior Scholar</span>
            <span class="text-xl font-black text-primary"><?php echo $senior_scholars; ?></span>
          </div>
          <div class="p-3 border border-rose-500/10 rounded-xl bg-rose-50/20 text-center">
            <span class="text-[9px] text-rose-600/60 font-bold uppercase tracking-wider block mb-1">Inactive</span>
            <span class="text-xl font-black text-rose-600"><?php echo $inactive; ?></span>
          </div>
        </div>

        <div class="mt-8 border-t border-primary/10 pt-6">
          <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-4 flex items-center gap-1.5">
            <i data-lucide="bar-chart-3" class="w-4 h-4 text-primary"></i> Performance Statistics & KPI Rankings
          </h4>
          <div class="space-y-3.5">
            <div>
              <div class="flex justify-between text-[10px] font-bold text-primary mb-1">
                <span>Tajweed & Quran Scholars (Hifdh Conversion)</span>
                <span>0%</span>
              </div>
              <div class="w-full bg-primary/10 h-2 rounded-full overflow-hidden">
                <div class="bg-primary h-full" style="width: 0%;"></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between text-[10px] font-bold text-primary mb-1">
                <span>Islamic Studies Web Seminar Quality Rating</span>
                <span>0%</span>
              </div>
              <div class="w-full bg-primary/10 h-2 rounded-full overflow-hidden">
                <div class="bg-emerald-600 h-full" style="width: 0%;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Notification & Alerts Panel -->
      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 flex flex-col justify-between">
        <div>
          <h3 class="text-sm font-bold text-primary uppercase tracking-wider mb-4 flex items-center gap-1.5">
            <i data-lucide="bell" class="w-4.5 h-4.5 text-primary"></i> Critical Notifications
          </h3>
          <div class="space-y-3">
            <p class="text-[10px] text-primary/40 font-bold uppercase tracking-widest text-center py-10">No critical alerts for today.</p>
          </div>
        </div>
        <a href="teachers.php" class="w-full text-center mt-6 py-2.5 bg-primary text-white rounded-xl text-[10px] font-bold uppercase tracking-wider hover:bg-primary/90 transition-colors">Manage Scholars Registry</a>
      </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
