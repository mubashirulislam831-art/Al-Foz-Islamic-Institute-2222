<?php
/**
 * Teacher Salary ERP - Reports
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Salary Reports";
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
      <h1 class="text-2xl font-bold text-primary mb-6">Salary Reports & Analytics</h1>
      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 text-center text-gray-500">
        <i data-lucide="bar-chart-2" class="w-12 h-12 mx-auto mb-4 text-primary opacity-50"></i>
        <p class="text-sm font-bold">Reporting Engine</p>
        <p class="text-xs mt-2">Generate Monthly, Teacher, Bonus, and Deduction reports.</p>
      </div>
    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
