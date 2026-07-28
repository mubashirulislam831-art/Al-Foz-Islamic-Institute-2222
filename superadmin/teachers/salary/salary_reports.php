<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Salary Reports
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
        <h1 class="text-2xl font-black text-primary tracking-tight">Financial Payroll Reports</h1>
        <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Audit-ready documentation for faculty financial expenditures.</p>
      </div>
      <div class="flex gap-2">
        <button class="px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/90 transition-colors">
          Download Annual Audit
        </button>
      </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Monthly Summary Report -->
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm hover:border-primary/30 transition-all group">
            <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary mb-4 group-hover:bg-primary group-hover:text-white transition-colors">
                <i data-lucide="file-bar-chart" class="w-5 h-5"></i>
            </div>
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">Monthly Payroll Summary</h3>
            <p class="text-[10px] text-primary/60 mt-1 mb-4">Complete breakdown of all salaries, bonuses, and deductions for the current month.</p>
            <button class="w-full py-2 border border-primary/20 text-primary text-[9px] font-bold uppercase rounded-lg hover:bg-primary/5 transition-colors">Generate PDF</button>
        </div>

        <!-- Teacher Wise Summary -->
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm hover:border-primary/30 transition-all group">
            <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary mb-4 group-hover:bg-primary group-hover:text-white transition-colors">
                <i data-lucide="user-check" class="w-5 h-5"></i>
            </div>
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">Teacher Earnings Report</h3>
            <p class="text-[10px] text-primary/60 mt-1 mb-4">Historical earnings analysis per teacher including performance commissions.</p>
            <button class="w-full py-2 border border-primary/20 text-primary text-[9px] font-bold uppercase rounded-lg hover:bg-primary/5 transition-colors">Generate PDF</button>
        </div>

        <!-- Tax & Deductions Report -->
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm hover:border-primary/30 transition-all group">
            <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary mb-4 group-hover:bg-primary group-hover:text-white transition-colors">
                <i data-lucide="receipt" class="w-5 h-5"></i>
            </div>
            <h3 class="font-bold text-primary text-sm uppercase tracking-wider">Deductions Audit</h3>
            <p class="text-[10px] text-primary/60 mt-1 mb-4">Detailed log of all financial penalties and tax withholdings applied.</p>
            <button class="w-full py-2 border border-primary/20 text-primary text-[9px] font-bold uppercase rounded-lg hover:bg-primary/5 transition-colors">Generate PDF</button>
        </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
