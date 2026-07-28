<?php
/**
 * Teacher Salary ERP - Salary Bonus
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Salary Bonus";
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
          <h1 class="text-2xl font-bold text-primary">Salary Bonus</h1>
          <p class="text-sm text-gray-500 mt-1">Manage Eid, Performance, Extra Class, and Manual bonuses</p>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-8">
          <form class="grid grid-cols-1 md:grid-cols-4 gap-6">
              <div>
                  <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Teacher</label>
                  <select class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                      <option>-- Select Teacher --</option>
                      <?php echo render_teacher_options_html(); ?>
                  </select>
              </div>
              <div>
                  <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Bonus Type</label>
                  <select class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                      <option>Eid Bonus</option>
                      <option>Performance Bonus</option>
                      <option>Extra Class Bonus</option>
                      <option>Manual Bonus</option>
                  </select>
              </div>
              <div>
                  <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Amount (PKR)</label>
                  <input type="number" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Amount">
              </div>
              <div class="flex items-end">
                  <button type="button" class="w-full py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-sm hover:bg-opacity-90 transition-all flex items-center justify-center gap-2">
                      <i data-lucide="plus" class="w-4 h-4"></i> Add Bonus
                  </button>
              </div>
          </form>
      </div>

      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <h3 class="text-sm font-bold text-primary uppercase tracking-widest mb-4">Recent Bonuses</h3>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Teacher</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Type</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Date</th>
                    <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right">Amount (PKR)</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="4" class="p-3 text-xs text-gray-500 text-center">No bonuses recorded this month.</td></tr>
            </tbody>
        </table>
      </div>

    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
