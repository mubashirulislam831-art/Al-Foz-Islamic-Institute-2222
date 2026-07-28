<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Salary History
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);
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
        <h1 class="text-2xl font-black text-primary tracking-tight">Faculty Salary History</h1>
        <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Archived disbursement records and historical payroll data.</p>
      </div>
      <div class="flex gap-2">
        <a href="monthly_salary.php" class="px-4 py-2 border border-primary/20 text-primary text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/5 transition-colors">
          Back
        </a>
      </div>
    </div>

    <!-- History Log -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-primary/10 flex justify-between items-center bg-primary/5">
            <h3 class="font-bold text-primary text-xs uppercase tracking-wider">Historical Transactions</h3>
            <div class="flex gap-2">
                <input type="text" placeholder="Search teacher..." class="px-3 py-1.5 border border-primary/10 rounded-lg text-[10px] outline-none">
            </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
                <th class="p-4">Transaction ID</th>
                <th class="p-4">Teacher Name</th>
                <th class="p-4">Month/Year</th>
                <th class="p-4">Amount Paid</th>
                <th class="p-4">Paid On</th>
                <th class="p-4">Payment Method</th>
                <th class="p-4 text-right">Invoice</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 text-primary/80">
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="p-4 font-mono font-bold">#SAL-88210</td>
                <td class="p-4 font-bold text-primary">Fatima Al-Zahra</td>
                <td class="p-4 font-semibold">May 2026</td>
                <td class="p-4 font-black text-emerald-700">51,200 PKR</td>
                <td class="p-4">05 Jun 2026</td>
                <td class="p-4 text-[10px] uppercase font-bold">Bank Transfer</td>
                <td class="p-4 text-right">
                    <button class="text-primary hover:text-primary/70"><i data-lucide="file-text" class="w-4 h-4"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
