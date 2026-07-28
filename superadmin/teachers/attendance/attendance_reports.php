<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Attendance Reports
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
        <h1 class="text-2xl font-black text-primary tracking-tight">Attendance Reports</h1>
        <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Generate and export faculty presence dossiers.</p>
      </div>
      <div class="flex gap-2">
        <button class="px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/90 transition-colors flex items-center gap-1">
          <i data-lucide="download" class="w-3.5 h-3.5"></i> Export All
        </button>
      </div>
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-6">
      <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Select Teacher</label>
          <select class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none">
            <option>All Teachers</option>
            <option>Fatima Al-Zahra</option>
            <option>Muhammad Ali</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Date Range</label>
          <input type="text" placeholder="Select dates..." class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none">
        </div>
        <div>
          <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Status</label>
          <select class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none">
            <option>All Statuses</option>
            <option>Present</option>
            <option>Absent</option>
            <option>On Leave</option>
          </select>
        </div>
        <div class="flex items-end">
          <button type="submit" class="w-full py-2 bg-primary text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">Filter Reports</button>
        </div>
      </form>
    </div>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-primary/10 flex justify-between items-center bg-primary/5">
            <h3 class="font-bold text-primary text-xs uppercase tracking-wider">Attendance Data Log</h3>
            <span class="text-[9px] text-primary/60 font-bold uppercase">124 Records Found</span>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
                <th class="p-4">Date</th>
                <th class="p-4">Teacher Name</th>
                <th class="p-4">Login Time</th>
                <th class="p-4">Logout Time</th>
                <th class="p-4">Status</th>
                <th class="p-4">Remarks</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 text-primary/80">
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="p-4 font-bold">25 Jun 2026</td>
                <td class="p-4">Fatima Al-Zahra</td>
                <td class="p-4">08:55 AM</td>
                <td class="p-4">02:15 PM</td>
                <td class="p-4"><span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold uppercase tracking-wider">Present</span></td>
                <td class="p-4 text-[10px]">Regular class hours</td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
