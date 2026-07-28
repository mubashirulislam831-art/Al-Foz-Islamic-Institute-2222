<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Today's Attendance
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);

$current_page = 'today_attendance.php';
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
        <h1 class="text-2xl font-black text-primary tracking-tight">Today's Teacher Attendance</h1>
        <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Live monitoring of daily faculty presence and class starts.</p>
      </div>
      <div class="flex gap-2">
        <a href="../teacher_attendance.php" class="px-4 py-2 border border-primary/20 text-primary text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/5 transition-colors">
          Back to Overview
        </a>
      </div>
    </div>

    <!-- Attendance Grid -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Teacher</th>
              <th class="p-4">Login Time</th>
              <th class="p-4">Scheduled Classes</th>
              <th class="p-4">Conducted</th>
              <th class="p-4">Makeup Classes</th>
              <th class="p-4">Status</th>
              <th class="p-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr class="hover:bg-primary/5 transition-colors">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[10px]">FA</div>
                  <div>
                    <div class="font-bold text-primary">Fatima Al-Zahra</div>
                    <div class="text-[9px] text-primary/60">TCH-0001</div>
                  </div>
                </div>
              </td>
              <td class="p-4 font-semibold">08:45 AM</td>
              <td class="p-4 font-bold text-primary">8</td>
              <td class="p-4 font-bold text-emerald-600">5</td>
              <td class="p-4 font-bold text-amber-600">1</td>
              <td class="p-4">
                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold uppercase tracking-wider">Present</span>
              </td>
              <td class="p-4 text-right">
                <button class="text-primary hover:underline font-bold">Details</button>
              </td>
            </tr>
            <tr class="hover:bg-primary/5 transition-colors">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[10px]">MA</div>
                  <div>
                    <div class="font-bold text-primary">Muhammad Ali</div>
                    <div class="text-[9px] text-primary/60">TCH-0002</div>
                  </div>
                </div>
              </td>
              <td class="p-4 font-semibold">-</td>
              <td class="p-4 font-bold text-primary">6</td>
              <td class="p-4 font-bold text-rose-600">0</td>
              <td class="p-4 font-bold text-primary">0</td>
              <td class="p-4">
                <span class="px-2 py-0.5 bg-rose-50 text-rose-700 rounded text-[9px] font-bold uppercase tracking-wider">Absent</span>
              </td>
              <td class="p-4 text-right">
                <button class="text-primary hover:underline font-bold">Details</button>
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
