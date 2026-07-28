<?php
/**
 * Al Foz Islamic Institute - Student Exams & Evaluations Page
 */
require_once __DIR__ . '/includes/student_context.php';

$total_exams = count($student_exams);
$passed_exams = 0;
$total_obtained = 0;
$total_max = 0;

foreach ($student_exams as $ex) {
    $ob = floatval($ex['obtained_marks'] ?? 0);
    $mx = floatval($ex['max_marks'] ?? 100);
    $total_obtained += $ob;
    $total_max += ($mx > 0 ? $mx : 100);
    if (($ob / ($mx > 0 ? $mx : 100)) >= 0.5) {
        $passed_exams++;
    }
}

$avg_percentage = ($total_max > 0) ? round(($total_obtained / $total_max) * 100, 1) : 0;
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
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">exams & grades</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Academic Evaluation & Assessment Results</p>
      </div>
      <button onclick="window.print()" class="px-5 py-2.5 bg-primary hover:bg-[#10353a] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition-all flex items-center gap-2 active:scale-95">
        <i data-lucide="printer" class="w-4 h-4"></i> Print Evaluation Card
      </button>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
          <i data-lucide="award" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Average Grade Score</span>
          <span class="text-2xl font-black text-primary"><?php echo $avg_percentage; ?>%</span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
          <i data-lucide="check-circle-2" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Passed Evaluations</span>
          <span class="text-2xl font-black text-emerald-700"><?php echo $passed_exams; ?> / <?php echo $total_exams; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center shrink-0">
          <i data-lucide="star" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Tajweed Fluency Rating</span>
          <span class="text-xl font-black text-amber-700">Mumtaz (A+)</span>
        </div>
      </div>
    </div>

    <!-- Examination Results Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm mb-8">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-6 flex items-center gap-2">
        <i data-lucide="file-check-2" class="w-4 h-4 text-primary"></i> Exam Results & Assessment History
      </h3>

      <?php if (!empty($student_exams)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-primary/10 text-primary/50 uppercase tracking-wider text-[10px]">
                <th class="py-3 px-4 font-bold">Exam Name</th>
                <th class="py-3 px-4 font-bold">Subject / Surah</th>
                <th class="py-3 px-4 font-bold">Exam Date</th>
                <th class="py-3 px-4 font-bold">Marks Obtained</th>
                <th class="py-3 px-4 font-bold">Grade Result</th>
                <th class="py-3 px-4 font-bold">Instructor Remarks</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 font-medium text-primary">
              <?php foreach ($student_exams as $ex): 
                $ob = floatval($ex['obtained_marks'] ?? 0);
                $mx = floatval($ex['max_marks'] ?? 100);
                $pct = ($mx > 0) ? round(($ob / $mx) * 100) : 0;
                $grade = 'Pass';
                $grade_bg = 'bg-emerald-50 border-emerald-200 text-emerald-800';
                if ($pct >= 90) $grade = 'A+ (Mumtaz)';
                elseif ($pct >= 80) $grade = 'A (Jayyid Jiddan)';
                elseif ($pct >= 70) $grade = 'B (Jayyid)';
                elseif ($pct < 50) { $grade = 'Needs Improvement'; $grade_bg = 'bg-rose-50 border-rose-200 text-rose-800'; }
              ?>
                <tr class="hover:bg-primary/5 transition-all">
                  <td class="py-3.5 px-4 font-bold text-primary">
                    <?php echo htmlspecialchars($ex['exam_name'] ?? 'Quarterly Evaluation'); ?>
                  </td>
                  <td class="py-3.5 px-4 font-semibold">
                    <?php echo htmlspecialchars($ex['subject'] ?? 'Quran Recitation & Tajweed'); ?>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-primary/70">
                    <?php echo date('d M, Y', strtotime($ex['exam_date'] ?? 'now')); ?>
                  </td>
                  <td class="py-3.5 px-4 font-bold font-mono">
                    <?php echo $ob; ?> / <?php echo $mx; ?> (<?php echo $pct; ?>%)
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 text-[9px] font-black rounded-full border uppercase <?php echo $grade_bg; ?>">
                      <?php echo $grade; ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-4 italic text-primary/80">
                    <?php echo htmlspecialchars($ex['remarks'] ?? 'Excellent Makharij accuracy and voice control.'); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="p-12 text-center text-primary/60 font-medium">
          <i data-lucide="award" class="w-12 h-12 text-primary/30 mx-auto mb-3"></i>
          <p class="text-sm font-bold text-primary">No Evaluation Records Found</p>
          <p class="text-xs text-primary/60 mt-1">There are no examination or evaluation assessment results recorded for this student yet.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
