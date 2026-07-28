<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Attendance Analytics
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl font-black text-primary tracking-tight">Attendance Analytics</h1>
        <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Deep insights into faculty consistency and punctuality trends.</p>
      </div>
      <div class="flex gap-2">
        <a href="../teacher_attendance.php" class="px-4 py-2 border border-primary/20 text-primary text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/5 transition-colors">
          Back
        </a>
      </div>
    </div>

    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
        <h3 class="text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-4">Punctuality Score</h3>
        <div class="text-4xl font-black text-emerald-600">98.2%</div>
        <p class="text-[10px] text-primary/70 mt-2 font-semibold">Average on-time class start rate institute-wide.</p>
      </div>
      <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
        <h3 class="text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-4">Total Leave Ratio</h3>
        <div class="text-4xl font-black text-amber-600">3.5%</div>
        <p class="text-[10px] text-primary/70 mt-2 font-semibold">Percentage of scheduled classes missed due to leave.</p>
      </div>
      <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
        <h3 class="text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-4">Makeup Fulfillment</h3>
        <div class="text-4xl font-black text-indigo-600">89%</div>
        <p class="text-[10px] text-primary/70 mt-2 font-semibold">Rate at which missed classes are successfully recovered.</p>
      </div>
    </div>

    <!-- Analytics Visualization Placeholder -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm h-80 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-primary/5 rounded-full flex items-center justify-center mb-4 text-primary">
                <i data-lucide="line-chart" class="w-8 h-8"></i>
            </div>
            <h4 class="font-bold text-primary text-sm uppercase tracking-wider">Attendance Trends (Last 6 Months)</h4>
            <p class="text-[10px] text-primary/60 mt-1 max-w-xs">Data visualization graph will render here integrating with live attendance metrics.</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm h-80 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-primary/5 rounded-full flex items-center justify-center mb-4 text-primary">
                <i data-lucide="pie-chart" class="w-8 h-8"></i>
            </div>
            <h4 class="font-bold text-primary text-sm uppercase tracking-wider">Leave Categorization</h4>
            <p class="text-[10px] text-primary/60 mt-1 max-w-xs">Breakdown of planned vs unplanned leaves across the faculty.</p>
        </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
