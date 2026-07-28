<?php
/**
 * Al Foz Islamic Institute - Student Progress Reports Page
 */
require_once __DIR__ . '/includes/student_context.php';

$all_reports = get_db_table('progress_reports');
$student_reports = array_filter($all_reports, function($r) use ($student_id_val, $student_roll) {
    return (isset($r['student_id']) && ($r['student_id'] == $student_id_val || $r['student_id'] == $student_roll));
});
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">progress reports</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Academic Progress & Evaluation Reports</p>
      </div>
      <button onclick="window.print()" class="px-5 py-2.5 bg-primary hover:bg-[#10353a] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition-all flex items-center gap-2 active:scale-95">
        <i data-lucide="printer" class="w-4 h-4"></i> Print Progress Sheet
      </button>
    </div>

    <!-- Progress Breakdown Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
            <i data-lucide="activity" class="w-5 h-5"></i>
          </div>
          <div>
            <h3 class="text-xs font-black uppercase text-primary">Makharij Accuracy</h3>
            <span class="text-lg font-black text-emerald-700">95 / 100</span>
          </div>
        </div>
        <p class="text-xs text-primary/70 font-medium">Pronunciation of throat letters (Haroof-e-Halqi) is excellent.</p>
      </div>

      <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
            <i data-lucide="volume-2" class="w-5 h-5"></i>
          </div>
          <div>
            <h3 class="text-xs font-black uppercase text-primary">Tajweed Rules Application</h3>
            <span class="text-lg font-black text-primary">92 / 100</span>
          </div>
        </div>
        <p class="text-xs text-primary/70 font-medium">Ghunnah, Ikhfa, and Mad-e-Muttasil rules applied correctly.</p>
      </div>

      <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center shrink-0">
            <i data-lucide="award" class="w-5 h-5"></i>
          </div>
          <div>
            <h3 class="text-xs font-black uppercase text-primary">Memorization Retention</h3>
            <span class="text-lg font-black text-amber-700">Grade A+ (Mumtaz)</span>
          </div>
        </div>
        <p class="text-xs text-primary/70 font-medium">Sabaqi and Manzil revision is steady and well-maintained.</p>
      </div>
    </div>

    <!-- Official Progress Reports List -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-6 flex items-center gap-2">
        <i data-lucide="bar-chart-3" class="w-4 h-4 text-primary"></i> Faculty Evaluation Reports Log
      </h3>

      <?php if (!empty($student_reports)): ?>
        <div class="space-y-4">
          <?php foreach ($student_reports as $rep): ?>
          <div class="p-5 rounded-2xl border border-primary/10 bg-primary/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 bg-primary text-white text-[9px] font-black uppercase rounded-full">
                  <?php echo htmlspecialchars($rep['report_period'] ?? 'Monthly Report'); ?>
                </span>
                <span class="text-[10px] text-primary/60 font-bold uppercase">Grade: <?php echo htmlspecialchars($rep['grade'] ?? 'A+'); ?></span>
              </div>
              <h4 class="text-sm font-black text-primary">Progress Report Summary</h4>
              <p class="text-xs text-primary/80 font-medium mt-1 leading-relaxed"><?php echo htmlspecialchars($rep['remarks'] ?? 'Demonstrated outstanding progress in Quranic recitation and Tajweed fluency throughout the month.'); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="p-12 text-center text-primary/60 font-medium">
          <i data-lucide="bar-chart-2" class="w-12 h-12 text-primary/30 mx-auto mb-3"></i>
          <p class="text-sm font-bold text-primary">No Reports Available</p>
          <p class="text-xs text-primary/60 mt-1">Faculty evaluation reports have not been published for this student account yet.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
