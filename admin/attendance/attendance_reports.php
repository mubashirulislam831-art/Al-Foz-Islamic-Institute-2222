<?php
/**
 * Al Foz Islamic Institute - Attendance Reports
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Admin');

?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent font-['Poppins']">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Module Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-primary/5 rounded-xl flex items-center justify-center text-primary shrink-0 border border-primary/10">
            <i data-lucide="file-text" class="w-7 h-7"></i>
        </div>
        <div>
          <h1 class="text-2xl font-black text-primary tracking-tight">Attendance Reports</h1>
          <p class="text-xs text-primary/60 mt-1 font-medium">Generate and export detailed attendance data</p>
        </div>
      </div>
    </div>

    <!-- Generator Interface -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Monthly Report -->
        <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-8">
            <div class="flex items-center gap-3 mb-6">
                <i data-lucide="calendar" class="w-5 h-5 text-primary/50"></i>
                <h3 class="font-black text-primary uppercase tracking-widest text-sm">Monthly Attendance Report</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-primary/60 mb-2 block">Select Month</label>
                    <input type="month" class="w-full px-4 py-3 bg-gray-50 border border-primary/20 rounded-xl text-sm font-bold text-primary outline-none focus:ring-2 focus:ring-primary/30" value="<?php echo date('Y-m'); ?>">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-primary/60 mb-2 block">Format</label>
                    <select class="w-full px-4 py-3 bg-gray-50 border border-primary/20 rounded-xl text-sm font-bold text-primary outline-none focus:ring-2 focus:ring-primary/30">
                        <option>PDF Document</option>
                        <option>Excel Spreadsheet</option>
                        <option>Print View</option>
                    </select>
                </div>
                <div class="pt-4">
                    <button class="w-full bg-primary text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-md flex justify-center items-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i> Generate Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Student Specific Report -->
        <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-8">
            <div class="flex items-center gap-3 mb-6">
                <i data-lucide="user" class="w-5 h-5 text-primary/50"></i>
                <h3 class="font-black text-primary uppercase tracking-widest text-sm">Student Attendance History</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-primary/60 mb-2 block">Student Name / ID</label>
                    <input type="text" class="w-full px-4 py-3 bg-gray-50 border border-primary/20 rounded-xl text-sm font-bold text-primary outline-none focus:ring-2 focus:ring-primary/30" placeholder="Search student...">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-primary/60 mb-2 block">From</label>
                        <input type="date" class="w-full px-4 py-3 bg-gray-50 border border-primary/20 rounded-xl text-sm font-bold text-primary outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-primary/60 mb-2 block">To</label>
                        <input type="date" class="w-full px-4 py-3 bg-gray-50 border border-primary/20 rounded-xl text-sm font-bold text-primary outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                </div>
                <div class="pt-4">
                    <button class="w-full bg-primary text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-md flex justify-center items-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i> Export History
                    </button>
                </div>
            </div>
        </div>
    </div>
  </div>
  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
