<?php
/**
 * Teacher Salary ERP - Monthly Salary Processing
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Monthly Salary";

$success_msg = $_GET['msg'] ?? '';
$error_msg = $_GET['error'] ?? '';
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
          <h1 class="text-2xl font-bold text-primary">Process Monthly Salary</h1>
          <p class="text-sm text-gray-500 mt-1">Review calculated payroll based on formula engine and deductions</p>
        </div>
        <button class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg shadow-sm flex items-center gap-2">
            <i data-lucide="calculator" class="w-4 h-4"></i> Run Payroll (<?php echo date('M Y'); ?>)
        </button>
      </div>

      <?php if ($success_msg === 'paid'): ?>
      <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> Salary paid successfully.
      </div>
      <?php endif; ?>

      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-sm font-bold text-primary uppercase tracking-widest">Pending Disbursals</h3>
            <select class="p-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 bg-gray-50">
                <option>All Types</option>
                <option>Under Training</option>
                <option>Fixed</option>
                <option>Per-Student</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Teacher</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Type</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right">Base Salary</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right text-green-600">Bonuses</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right text-red-600">Deductions</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right">Net Payable</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo render_monthly_salary_rows_html(); ?>
                </tbody>
            </table>
        </div>
      </div>

    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
