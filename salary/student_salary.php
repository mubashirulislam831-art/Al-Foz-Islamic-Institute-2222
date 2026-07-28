<?php
/**
 * Teacher Salary ERP - Per Student Salary Management
 */
require_once __DIR__ . '/../includes/system_config.php';
$page_title = "Student Salary";

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
          <h1 class="text-2xl font-bold text-primary">Student Salary Mappings</h1>
          <p class="text-sm text-gray-500 mt-1">Review student mapping payouts for teachers on dynamic formula</p>
        </div>
      </div>

      <!-- Overview -->
      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-8">
        <h3 class="text-sm font-bold text-primary uppercase tracking-widest mb-4">Student Roster & Payout Rules</h3>
        <p class="text-xs text-gray-500 mb-4">
            Teachers on the <strong>Per Student Salary</strong> type are paid automatically based on the students assigned to them, cross-referenced with the <a href="salary_formula.php" class="text-primary font-bold hover:underline">Salary Formula Engine</a>.
        </p>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50 text-left">
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Student Name</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Assigned Teacher</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Schedule</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Joining Date</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase">Status</th>
                        <th class="p-3 text-[10px] font-bold text-gray-500 uppercase text-right">Calculated Payout</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo render_student_salary_mappings_html(); ?>
                </tbody>
            </table>
        </div>
      </div>

    </div>
  </div>
  <script>lucide.createIcons();</script>

  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
