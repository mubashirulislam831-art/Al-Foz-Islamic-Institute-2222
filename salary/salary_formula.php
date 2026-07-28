<?php
/**
 * Teacher Salary ERP - Formula Engine
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Formula Engine";

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
          <h1 class="text-2xl font-bold text-primary">Salary Formula Engine</h1>
          <p class="text-sm text-gray-500 mt-1">Define Per-Student payout rules based on schedule</p>
        </div>
      </div>

      <?php if ($success_msg === 'rule_added'): ?>
      <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> Formula rule added successfully.
      </div>
      <?php elseif ($success_msg === 'rule_deleted'): ?>
      <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> Formula rule deleted.
      </div>
      <?php endif; ?>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Add New Rule -->
          <div class="md:col-span-1">
              <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
                  <h3 class="text-sm font-bold text-primary uppercase tracking-widest mb-4 border-b border-gray-100 pb-2">Add Formula Rule</h3>
                  <form action="/api/salary/formula.php" method="POST" class="space-y-4">
                      <input type="hidden" name="action" value="add_rule">
                      <div>
                          <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Days Per Week</label>
                          <select name="days" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                              <option value="1">1 Day</option>
                              <option value="2">2 Days</option>
                              <option value="3">3 Days</option>
                              <option value="4">4 Days</option>
                              <option value="5" selected>5 Days</option>
                              <option value="6">6 Days</option>
                              <option value="7">7 Days</option>
                          </select>
                      </div>
                      <div>
                          <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Minutes Per Day</label>
                          <select name="minutes" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                              <option value="15">15 Minutes</option>
                              <option value="30" selected>30 Minutes</option>
                              <option value="45">45 Minutes</option>
                              <option value="60">60 Minutes</option>
                              <option value="90">90 Minutes</option>
                          </select>
                      </div>
                      <div>
                          <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Monthly Payout (PKR)</label>
                          <input type="number" name="payout" class="w-full p-2.5 bg-white border border-gray-200 rounded-xl text-sm" placeholder="e.g. 2000" required>
                      </div>
                      <div class="pt-2">
                          <button type="submit" class="w-full py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-sm hover:bg-opacity-90 transition-all flex items-center justify-center gap-2">
                              <i data-lucide="plus" class="w-4 h-4"></i> Add Rule
                          </button>
                      </div>
                  </form>
              </div>
          </div>

          <!-- Existing Rules -->
          <div class="md:col-span-2">
              <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
                <h3 class="text-sm font-bold text-primary uppercase tracking-widest mb-4">Configured Rules</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/50">
                                <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Days / Week</th>
                                <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Minutes / Day</th>
                                <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Teacher Gets (PKR)</th>
                                <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo render_formula_rules_html(); ?>
                        </tbody>
                    </table>
                </div>
              </div>
          </div>
      </div>

    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
