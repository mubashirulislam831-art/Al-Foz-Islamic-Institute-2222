<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Monthly Salary
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
        <h1 class="text-2xl font-black text-primary tracking-tight">Monthly Payroll Management</h1>
        <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Review, compute, and authorize teacher salary disbursements.</p>
      </div>
      <div class="flex gap-2">
        <a href="payroll_engine.php" class="px-4 py-2 bg-emerald-600 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-emerald-700 transition-colors flex items-center gap-1">
          <i data-lucide="calculator" class="w-3.5 h-3.5"></i> Run Payroll Engine
        </a>
      </div>
    </div>

    <!-- Salary Summary Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm">
        <span class="text-[9px] text-primary/60 font-black uppercase tracking-wider">Total Faculty Payroll</span>
        <div class="text-xl font-black text-primary mt-1">2,450,000 PKR</div>
      </div>
      <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm">
        <span class="text-[9px] text-emerald-600 font-black uppercase tracking-wider">Paid Amount</span>
        <div class="text-xl font-black text-emerald-700 mt-1">1,800,000 PKR</div>
      </div>
      <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm">
        <span class="text-[9px] text-rose-600 font-black uppercase tracking-wider">Pending Amount</span>
        <div class="text-xl font-black text-rose-700 mt-1">650,000 PKR</div>
      </div>
      <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm">
        <span class="text-[9px] text-indigo-600 font-black uppercase tracking-wider">Teachers To Pay</span>
        <div class="text-xl font-black text-indigo-700 mt-1">12 / 44</div>
      </div>
    </div>

    <!-- Payroll Table -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Teacher</th>
              <th class="p-4">Base Salary</th>
              <th class="p-4">Commission</th>
              <th class="p-4">Bonus</th>
              <th class="p-4">Deductions</th>
              <th class="p-4">Net Payable</th>
              <th class="p-4">Status</th>
              <th class="p-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr class="hover:bg-primary/5 transition-colors">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[10px]">FA</div>
                  <div>
                    <div class="font-bold text-primary">Fatima Al-Zahra</div>
                    <div class="text-[9px] text-primary/60">June 2026</div>
                  </div>
                </div>
              </td>
              <td class="p-4 font-bold text-primary">45,000 PKR</td>
              <td class="p-4 font-bold text-emerald-600">+8,000</td>
              <td class="p-4 font-bold text-indigo-600">+2,000</td>
              <td class="p-4 font-bold text-rose-600">-1,500</td>
              <td class="p-4 font-black text-primary">53,500 PKR</td>
              <td class="p-4">
                <span class="px-2 py-0.5 bg-rose-50 text-rose-700 rounded text-[9px] font-bold uppercase tracking-wider border border-rose-100">Pending</span>
              </td>
              <td class="p-4 text-right flex justify-end gap-1">
                <button class="px-3 py-1 bg-emerald-600 text-white rounded text-[9px] font-bold uppercase hover:bg-emerald-700">Pay Now</button>
                <button class="px-3 py-1 bg-primary/5 text-primary rounded text-[9px] font-bold uppercase hover:bg-primary/10">Details</button>
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
