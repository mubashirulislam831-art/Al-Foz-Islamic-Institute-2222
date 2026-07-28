<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Specific Reports
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Teacher Header and Navigation -->
    <?php require_once __DIR__ . '/_teacher_header.php'; ?>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-6">
        <h3 class="text-sm font-black text-primary uppercase tracking-wider mb-6 flex items-center gap-2">
            <i data-lucide="file-text" class="w-4 h-4 text-primary"></i> Specialized Documentation Requests
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Attendance Summary -->
            <div class="p-5 border border-primary/10 rounded-2xl bg-primary/5 hover:border-primary transition-all group">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary mb-4 shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-primary text-[11px] uppercase tracking-wider">Attendance Dossier</h4>
                <p class="text-[9px] text-primary/60 mt-1 mb-4 leading-relaxed">Complete presence and class-start statistics for the current academic year.</p>
                <a href="print/print_attendance.php?id=<?php echo $teacher['id']; ?>" target="_blank" class="block w-full text-center py-2 bg-white border border-primary/20 text-primary text-[9px] font-bold uppercase rounded-lg hover:bg-primary hover:text-white transition-all">Generate PDF</a>
            </div>

            <!-- Student Progress -->
            <div class="p-5 border border-primary/10 rounded-2xl bg-primary/5 hover:border-primary transition-all group">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary mb-4 shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-primary text-[11px] uppercase tracking-wider">Batch Progress Report</h4>
                <p class="text-[9px] text-primary/60 mt-1 mb-4 leading-relaxed">Cumulative success ratio and course completion metrics for assigned students.</p>
                <a href="print/print_teacher_list.php?id=<?php echo $teacher['id']; ?>" target="_blank" class="block w-full text-center py-2 bg-white border border-primary/20 text-primary text-[9px] font-bold uppercase rounded-lg hover:bg-primary hover:text-white transition-all">Generate PDF</a>
            </div>

            <!-- Financial Ledger -->
            <div class="p-5 border border-primary/10 rounded-2xl bg-primary/5 hover:border-primary transition-all group">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary mb-4 shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-primary text-[11px] uppercase tracking-wider">Financial Statement</h4>
                <p class="text-[9px] text-primary/60 mt-1 mb-4 leading-relaxed">Detailed earnings log including base, commission, and historical payments.</p>
                <a href="print/print_salary_slip.php?id=<?php echo $teacher['id']; ?>" target="_blank" class="block w-full text-center py-2 bg-white border border-primary/20 text-primary text-[9px] font-bold uppercase rounded-lg hover:bg-primary hover:text-white transition-all">Generate PDF</a>
            </div>

            <!-- Certificate Generator -->
            <div class="p-5 border border-primary/10 rounded-2xl bg-primary/5 hover:border-primary transition-all group">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary mb-4 shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-primary text-[11px] uppercase tracking-wider">Experience Certificate</h4>
                <p class="text-[9px] text-primary/60 mt-1 mb-4 leading-relaxed">Generate official verification of employment and seniority status.</p>
                <a href="print/print_id_card.php?id=<?php echo $teacher['id']; ?>" target="_blank" class="block w-full text-center py-2 bg-white border border-primary/20 text-primary text-[9px] font-bold uppercase rounded-lg hover:bg-primary hover:text-white transition-all">Generate PDF</a>
            </div>
        </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
