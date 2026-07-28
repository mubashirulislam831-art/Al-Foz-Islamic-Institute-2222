<?php
/**
 * Teacher Salary ERP - Dashboard
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Teacher Salary Management";
require_once __DIR__ . '/../includes/header.php';
?>
<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
<!-- Main Content -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="p-6 md:p-8">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-2xl font-bold text-primary">Teacher Salary System</h1>
          <p class="text-sm text-gray-500 mt-1">Overview of payroll, deductions, and analytics</p>
        </div>
        <div class="flex gap-3">
          <a href="monthly_salary.php" class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg shadow-sm">Process Salaries</a>
          <a href="salary_setup.php" class="px-4 py-2 bg-white text-primary border border-primary/20 text-sm font-bold rounded-lg shadow-sm">Setup Formulas</a>
        </div>
      </div>

      <!-- Dashboard Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
          <p class="text-xs font-bold text-gray-500 uppercase">Total Paid (This Month)</p>
          <h3 class="text-2xl font-black text-green-600 mt-2">PKR <?php echo number_format($total_paid_salary ?? 0); ?></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
          <p class="text-xs font-bold text-gray-500 uppercase">Pending Salaries</p>
          <h3 class="text-2xl font-black text-red-600 mt-2">PKR <?php echo number_format($pending_salary ?? 0); ?></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
          <p class="text-xs font-bold text-gray-500 uppercase">Total Bonuses</p>
          <h3 class="text-2xl font-black text-primary mt-2">PKR <?php echo number_format($total_bonuses ?? 0); ?></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
          <p class="text-xs font-bold text-gray-500 uppercase">Total Deductions</p>
          <h3 class="text-2xl font-black text-amber-600 mt-2">PKR <?php echo number_format($total_deductions ?? 0); ?></h3>
        </div>
      </div>
      
      <!-- Charts placeholder -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm h-80 flex items-center justify-center">
            <span class="text-gray-400 font-bold">Monthly Salary Chart (D3.js integration)</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm h-80 flex items-center justify-center">
            <span class="text-gray-400 font-bold">Teacher Salary Breakdown Chart</span>
        </div>
      </div>
    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
