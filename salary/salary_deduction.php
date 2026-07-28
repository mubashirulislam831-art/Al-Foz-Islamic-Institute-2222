<?php
/**
 * Teacher Salary ERP - Deduction Management
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Salary Deductions";
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
      <h1 class="text-2xl font-bold text-primary mb-6">Salary Deductions</h1>
      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 text-center text-gray-500">
        <i data-lucide="minus-circle" class="w-12 h-12 mx-auto mb-4 text-red-400"></i>
        <p class="text-sm font-bold">Deduction Rules Engine Active</p>
        <p class="text-xs mt-2">Deductions based on 1-3 days (No deduction) and 4+ days are calculated automatically in Monthly Salary.</p>
      </div>
    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
