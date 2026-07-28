<?php
/**
 * Al Foz Islamic Institute - Super Admin Attendance Reports Hub
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-primary">Attendance Metrics Dashboard</h1>
    </div>

    <!-- Reports Menu Links -->
    <div class="flex flex-wrap gap-2 mb-8 text-xs font-bold">
      <a href="student_reports.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2.5 rounded-xl uppercase transition-all">Seekers</a>
      <a href="teacher_reports.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2.5 rounded-xl uppercase transition-all">Scholars</a>
      <a href="attendance_reports.php" class="bg-primary text-secondary px-4 py-2.5 rounded-xl uppercase transition-all">Attendance</a>
      <a href="fee_reports.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2.5 rounded-xl uppercase transition-all">Fees</a>
      <a href="salary_reports.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2.5 rounded-xl uppercase transition-all">Salaries</a>
      <a href="exam_reports.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2.5 rounded-xl uppercase transition-all">Exams</a>
      <a href="financial_reports.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2.5 rounded-xl uppercase transition-all">Financials</a>
    </div>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
      <h3 class="font-bold text-primary mb-4">Attendance Logs Aggregates</h3>
      <p class="text-xs text-primary/70">Trace daily roll calls and monthly summaries.</p>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
