<?php
/**
 * Teacher Salary ERP - Currency Conversion
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Currency Conversion";
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
          <h1 class="text-2xl font-bold text-primary">Currency Conversion</h1>
          <p class="text-sm text-gray-500 mt-1">Convert international salaries to PKR automatically</p>
        </div>
      </div>

      <!-- Currency Matrix -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
          <div class="bg-white p-4 rounded-xl border border-primary/10 shadow-sm text-center">
              <p class="text-[10px] font-bold text-gray-400 uppercase">1 USD</p>
              <h3 class="text-lg font-black text-primary mt-1">PKR 278.50</h3>
          </div>
          <div class="bg-white p-4 rounded-xl border border-primary/10 shadow-sm text-center">
              <p class="text-[10px] font-bold text-gray-400 uppercase">1 GBP</p>
              <h3 class="text-lg font-black text-primary mt-1">PKR 352.10</h3>
          </div>
          <div class="bg-white p-4 rounded-xl border border-primary/10 shadow-sm text-center">
              <p class="text-[10px] font-bold text-gray-400 uppercase">1 AUD</p>
              <h3 class="text-lg font-black text-primary mt-1">PKR 184.20</h3>
          </div>
          <div class="bg-white p-4 rounded-xl border border-primary/10 shadow-sm text-center">
              <p class="text-[10px] font-bold text-gray-400 uppercase">1 CAD</p>
              <h3 class="text-lg font-black text-primary mt-1">PKR 204.80</h3>
          </div>
          <div class="bg-white p-4 rounded-xl border border-primary/10 shadow-sm text-center">
              <p class="text-[10px] font-bold text-gray-400 uppercase">1 EUR</p>
              <h3 class="text-lg font-black text-primary mt-1">PKR 301.40</h3>
          </div>
      </div>

      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <h3 class="text-sm font-bold text-primary uppercase tracking-widest mb-4">Teacher Currency Ledger</h3>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Teacher</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Country</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Local Currency (Base)</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right">Converted PKR</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="4" class="p-3 text-xs text-gray-500 text-center">No international transactions pending.</td></tr>
            </tbody>
        </table>
      </div>

    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
