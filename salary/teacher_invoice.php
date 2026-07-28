<?php
/**
 * Teacher Salary ERP - Teacher Invoice
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Teacher Invoice";
require_once __DIR__ . '/../includes/header.php';
?>
<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
<!-- Main Content -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

</div>

  <div class="flex-grow flex flex-col min-h-screen">
    <div class="no-print">
        <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    </div>
    
    <div class="p-6 md:p-8">
      <div class="flex justify-between items-center mb-8 no-print">
        <div>
          <h1 class="text-2xl font-bold text-primary">Generate Invoice</h1>
          <p class="text-sm text-gray-500 mt-1">Generate and print salary invoices for teachers</p>
        </div>
        <div class="flex gap-3">
          <button onclick="window.print()" class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg shadow-sm flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i> Print Invoice
          </button>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-8 max-w-4xl mx-auto print-area">
          <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
              <div>
                  <h2 class="text-3xl font-black text-primary tracking-tight">AL FOZ ISLAMIC INSTITUTE</h2>
                  <p class="text-sm text-gray-500 mt-1">Professional Online Quran Academy</p>
                  <p class="text-xs text-gray-400 mt-2">admin@alfozinstitute.com<br>+92 300 0000000</p>
              </div>
              <div class="text-right">
                  <h1 class="text-3xl font-bold text-gray-300 uppercase tracking-widest">INVOICE</h1>
                  <p class="text-sm font-bold text-primary mt-2">INV-<?php echo date('Ym') . rand(100,999); ?></p>
                  <p class="text-xs text-gray-500">Date: <?php echo date('d M, Y'); ?></p>
              </div>
          </div>

          <div class="mb-8">
              <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Billed To (Educator)</h3>
              <p class="text-sm font-bold text-gray-800">Demo Teacher Name</p>
              <p class="text-xs text-gray-500">Salary Type: Per Student Salary</p>
              <p class="text-xs text-gray-500">Period: <?php echo date('F Y'); ?></p>
          </div>

          <table class="w-full text-left border-collapse mb-8">
              <thead>
                  <tr class="border-b-2 border-primary/20">
                      <th class="py-3 text-[10px] font-bold text-primary uppercase">Description</th>
                      <th class="py-3 text-[10px] font-bold text-primary uppercase text-right">Amount (PKR)</th>
                  </tr>
              </thead>
              <tbody>
                  <tr class="border-b border-gray-100">
                      <td class="py-4 text-sm text-gray-700">Base Salary (Dynamic Student Formula)</td>
                      <td class="py-4 text-sm font-bold text-gray-800 text-right">7,500</td>
                  </tr>
                  <tr class="border-b border-gray-100">
                      <td class="py-4 text-sm text-gray-700">Performance Bonus</td>
                      <td class="py-4 text-sm font-bold text-green-600 text-right">1,000</td>
                  </tr>
                  <tr class="border-b border-gray-100">
                      <td class="py-4 text-sm text-gray-700">Leave Deduction (2 Days)</td>
                      <td class="py-4 text-sm font-bold text-red-600 text-right">-500</td>
                  </tr>
              </tbody>
              <tfoot>
                  <tr>
                      <td class="py-4 text-sm font-bold text-primary text-right uppercase tracking-widest">Net Total Payable</td>
                      <td class="py-4 text-xl font-black text-primary text-right">PKR 8,000</td>
                  </tr>
              </tfoot>
          </table>

          <div class="mt-16 pt-8 border-t border-gray-200 text-center">
              <p class="text-[10px] text-gray-400">This is a system generated invoice and does not require a physical signature.</p>
          </div>
      </div>

    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
