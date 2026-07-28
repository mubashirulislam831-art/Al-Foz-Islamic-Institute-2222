<?php
/**
 * Teacher Salary ERP - Salary Setup
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Salary Setup";

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
          <h1 class="text-2xl font-bold text-primary">Salary Setup</h1>
          <p class="text-sm text-gray-500 mt-1">Configure teacher payroll type and base formulas</p>
        </div>
      </div>

      <?php if ($success_msg === 'updated'): ?>
      <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> Setup saved successfully.
      </div>
      <?php endif; ?>

      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-8">
          <form action="/api/salary/setup.php" method="POST" class="max-w-3xl">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                  <div>
                      <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Select Teacher</label>
                      <select name="teacher_id" id="teacher_select" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                          <option value="">-- Choose Teacher --</option>
                          <?php echo render_teacher_options_html(); ?>
                      </select>
                  </div>
                  <div>
                      <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Salary Type</label>
                      <select name="salary_type" id="salary_type" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20" required onchange="toggleSalaryFields()">
                          <option value="Under Training">Under Training</option>
                          <option value="Monthly Fixed Salary">Monthly Fixed Salary</option>
                          <option value="Per Student Salary">Per Student Salary</option>
                      </select>
                  </div>
              </div>

              <!-- Under Training Fields -->
              <div id="training_fields" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                  <div>
                      <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Training Period (Months)</label>
                      <input type="number" name="training_period" class="w-full p-2.5 bg-white border border-gray-200 rounded-lg text-sm" placeholder="e.g. 3">
                  </div>
                  <div>
                      <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Training Salary (PKR)</label>
                      <input type="number" name="training_salary" class="w-full p-2.5 bg-white border border-gray-200 rounded-lg text-sm" placeholder="e.g. 15000">
                  </div>
                  <div>
                      <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Training End Date</label>
                      <input type="date" name="training_end_date" class="w-full p-2.5 bg-white border border-gray-200 rounded-lg text-sm">
                  </div>
              </div>

              <!-- Fixed Salary Fields -->
              <div id="fixed_fields" class="hidden mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                  <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Monthly Fixed Salary (PKR)</label>
                  <input type="number" name="fixed_salary" class="w-full md:w-1/3 p-2.5 bg-white border border-gray-200 rounded-lg text-sm" placeholder="e.g. 80000">
              </div>

              <!-- Per Student Salary (Formula Engine) -->
              <div id="student_fields" class="hidden mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                  <p class="text-xs text-gray-500 mb-4 font-medium"><i data-lucide="info" class="w-4 h-4 inline-block mr-1"></i> Per Student Salary is calculated automatically based on the Salary Formula Engine. You don't need to specify a base salary here.</p>
                  <a href="salary_formula.php" class="text-sm font-bold text-primary hover:underline flex items-center gap-1">
                      Configure Formula Engine <i data-lucide="arrow-right" class="w-4 h-4"></i>
                  </a>
              </div>

              <div class="border-t border-gray-100 pt-6">
                  <button type="submit" class="px-6 py-3 bg-primary text-white text-sm font-bold rounded-xl shadow-sm hover:bg-opacity-90 transition-all flex items-center gap-2">
                      <i data-lucide="save" class="w-4 h-4"></i> Save Setup
                  </button>
              </div>
          </form>
      </div>

      <!-- Current Setup Overview -->
      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <h3 class="text-sm font-bold text-primary uppercase tracking-widest mb-4">Current Configurations</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Teacher</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Type</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo render_teacher_salary_setups_html(); ?>
                </tbody>
            </table>
        </div>
      </div>

    </div>
  </div>
  
  <script>
    lucide.createIcons();
    
    function toggleSalaryFields() {
        const salaryTypeEl = document.getElementById('salary_type');
        if (!salaryTypeEl) return;
        const type = salaryTypeEl.value;
        const trainingFields = document.getElementById('training_fields');
        const fixedFields = document.getElementById('fixed_fields');
        const studentFields = document.getElementById('student_fields');
        if (trainingFields) trainingFields.classList.toggle('hidden', type !== 'Under Training');
        if (fixedFields) fixedFields.classList.toggle('hidden', type !== 'Monthly Fixed Salary');
        if (studentFields) studentFields.classList.toggle('hidden', type !== 'Per Student Salary');
    }
    
    document.addEventListener('DOMContentLoaded', toggleSalaryFields);
  </script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
