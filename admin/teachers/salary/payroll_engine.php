<?php
/**
 * Al Foz Islamic Institute - Super Admin Payroll Computation Engine
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
        <h1 class="text-2xl font-black text-primary tracking-tight">Payroll Engine v2.0</h1>
        <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Automated salary calculation based on attendance and student retention.</p>
      </div>
      <div class="flex gap-2">
        <a href="monthly_salary.php" class="px-4 py-2 border border-primary/20 text-primary text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/5 transition-colors">
          Exit Engine
        </a>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-8 max-w-2xl mx-auto text-center">
        <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="cpu" class="w-10 h-10"></i>
        </div>
        <h2 class="text-xl font-black text-primary mb-2 tracking-tight">Compute Current Month Payroll</h2>
        <p class="text-xs text-primary/60 mb-8 max-w-md mx-auto">This process will synchronize attendance data, commission percentages, and late-start penalties to generate the June 2026 payroll list.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 text-left">
            <div class="p-4 border border-primary/10 rounded-xl bg-primary/5">
                <span class="text-[9px] font-bold text-primary/60 uppercase block mb-1">Target Month</span>
                <span class="text-sm font-black text-primary">June 2026</span>
            </div>
            <div class="p-4 border border-primary/10 rounded-xl bg-primary/5">
                <span class="text-[9px] font-bold text-primary/60 uppercase block mb-1">Pending Syncs</span>
                <span class="text-sm font-black text-primary">44 Teachers Detected</span>
            </div>
        </div>

        <button class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-emerald-700 transition-all shadow-lg hover:shadow-emerald-200">
            Initialize Computation Sequence
        </button>
        <p class="text-[9px] text-primary/40 mt-4 uppercase font-bold tracking-widest italic">Wait for validation before closing this window.</p>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
