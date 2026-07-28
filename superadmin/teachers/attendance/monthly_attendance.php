<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Monthly Attendance
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');
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
        <h1 class="text-2xl font-black text-primary tracking-tight">Monthly Attendance Record</h1>
        <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Full view of teacher presence throughout the current month.</p>
      </div>
      <div class="flex gap-2">
        <select class="px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none">
          <option>June 2026</option>
          <option>May 2026</option>
        </select>
        <a href="../teacher_attendance.php" class="px-4 py-2 border border-primary/20 text-primary text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/5 transition-colors">
          Back
        </a>
      </div>
    </div>

    <!-- Monthly Summary Table -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Teacher</th>
              <th class="p-4 text-center">Working Days</th>
              <th class="p-4 text-center">Present</th>
              <th class="p-4 text-center">Absent</th>
              <th class="p-4 text-center">Leave</th>
              <th class="p-4 text-center">Makeup</th>
              <th class="p-4 text-center">Percentage</th>
              <th class="p-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr class="hover:bg-primary/5 transition-colors">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[10px]">FA</div>
                  <div class="font-bold text-primary">Fatima Al-Zahra</div>
                </div>
              </td>
              <td class="p-4 text-center font-bold">26</td>
              <td class="p-4 text-center font-bold text-emerald-600">25</td>
              <td class="p-4 text-center font-bold text-rose-600">0</td>
              <td class="p-4 text-center font-bold text-amber-600">1</td>
              <td class="p-4 text-center font-bold text-indigo-600">4</td>
              <td class="p-4 text-center">
                <div class="flex items-center justify-center gap-2">
                  <div class="w-16 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full" style="width: 96.1%;"></div>
                  </div>
                  <span class="font-black text-primary">96.1%</span>
                </div>
              </td>
              <td class="p-4 text-right">
                <button class="text-primary hover:underline font-bold">View Calendar</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
