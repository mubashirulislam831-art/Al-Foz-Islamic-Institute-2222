<?php
/**
 * Al Foz Islamic Institute - Super Admin Attendance Desk
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
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Attendance Desk</h1>
        <p class="text-xs text-primary/60 mt-1">Manage global roll-call reports, logs, and analytics.</p>
      </div>
      <div class="flex gap-2">
        <a href="attendance_today.php" class="bg-primary hover:bg-opacity-95 text-secondary px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
          Today Attendance
        </a>
        <a href="monthly_attendance.php" class="border border-primary text-primary hover:bg-primary hover:text-secondary px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
          Monthly Attendance
        </a>
      </div>
    </div>

    <!-- Quick Analytics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm">
        <h3 class="text-xs font-bold text-primary/50 uppercase tracking-wider">Today's Attendance</h3>
        <p class="text-2xl font-extrabold mt-2">0%</p>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm">
        <h3 class="text-xs font-bold text-primary/50 uppercase tracking-wider">Excused Absences</h3>
        <p class="text-2xl font-extrabold mt-2">0</p>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm">
        <h3 class="text-xs font-bold text-primary/50 uppercase tracking-wider">Total Makeup Sessions</h3>
        <p class="text-2xl font-extrabold mt-2">0</p>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
