<?php
/**
 * Teacher Salary ERP - Payment History
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Payment History";
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
      <h1 class="text-2xl font-bold text-primary mb-6">Payment History</h1>
      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Month</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Teacher</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Amount</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="4" class="p-3 text-xs text-gray-500 text-center">No payment history available.</td></tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
