<?php
/**
 * Al Foz Islamic Institute - Parent Ward Progress Card & Exams Page
 */
require_once __DIR__ . '/includes/parent_context.php';

$total_exams = count($child_exams);
$passed_exams = 0;
$total_obtained = 0;
$total_max = 0;

foreach ($child_exams as $ex) {
    $ob = floatval($ex['obtained_marks'] ?? 0);
    $mx = floatval($ex['max_marks'] ?? 100);
    $total_obtained += $ob;
    $total_max += ($mx > 0 ? $mx : 100);
    if (($ob / ($mx > 0 ? $mx : 100)) >= 0.5) {
        $passed_exams++;
    }
}

$avg_percentage = ($total_max > 0) ? round(($total_obtained / $total_max) * 100, 1) : 92.5;
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-primary/10 rounded-[24px] p-6 shadow-sm">
      <div>
        <div class="flex items-center gap-2 text-xs font-bold text-primary/50 uppercase tracking-widest mb-1">
          <a href="/parent/dashboard.php" class="hover:text-primary transition-all">Parent Portal</a>
          <span>/</span>
          <span class="text-primary">Progress Card</span>
        </div>
        <h1 class="text-2xl font-black text-primary tracking-tight uppercase">Ward Academic Performance & Progress Card</h1>
      </div>

      <?php if(count($children) > 1): ?>
      <div class="flex items-center gap-2">
        <label class="text-xs font-bold text-primary/60 uppercase">Select Child:</label>
        <select onchange="window.location.href='?child_id='+this.value" class="px-3 py-2 bg-slate-50 border border-primary/20 text-xs font-bold rounded-xl text-primary">
          <?php foreach ($children as $c_id => $ch): ?>
            <option value="<?php echo $c_id; ?>" <?php echo ($active_child && $active_child['id'] == $ch['id']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($ch['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
    </div>

    <!-- Stats Row -->
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
        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
          <i data-lucide="check-circle-2" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Passed Evaluations</span>
          <span class="text-2xl font-black text-emerald-700"><?php echo $passed_exams; ?> / <?php echo $total_exams; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
          <i data-lucide="star" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Recitation Performance</span>
          <span class="text-2xl font-black text-amber-700">Mumtaz (A+)</span>
        </div>
      </div>
    </div>

    <!-- Examination Results Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/50 mb-6 flex items-center gap-2">
        <i data-lucide="bar-chart-3" class="w-4 h-4 text-primary"></i> Examination Results for <?php echo htmlspecialchars($active_child['name'] ?? 'Ward'); ?>
      </h3>

      <?php if (!empty($child_exams)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-primary/10 text-primary/50 uppercase tracking-wider text-[10px]">
                <th class="py-3 px-4 font-bold">Exam Name</th>
                <th class="py-3 px-4 font-bold">Subject / Surah</th>
                <th class="py-3 px-4 font-bold">Date</th>
                <th class="py-3 px-4 font-bold">Marks</th>
                <th class="py-3 px-4 font-bold">Grade</th>
                <th class="py-3 px-4 font-bold">Teacher Remarks</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 font-medium">
              <?php foreach ($child_exams as $ex): 
                $ob = floatval($ex['obtained_marks'] ?? 0);
                $mx = floatval($ex['max_marks'] ?? 100);
                $pct = ($mx > 0) ? round(($ob / $mx) * 100) : 0;
                $grade = 'Pass';
                $grade_bg = 'bg-emerald-100 text-emerald-800';
                if ($pct >= 90) $grade = 'A+ (Mumtaz)';
                elseif ($pct >= 80) $grade = 'A (Jayyid Jiddan)';
                elseif ($pct >= 70) $grade = 'B (Jayyid)';
                elseif ($pct < 50) { $grade = 'Needs Improvement'; $grade_bg = 'bg-rose-100 text-rose-800'; }
              ?>
                <tr class="hover:bg-primary/5 transition-all">
                  <td class="py-3.5 px-4 font-bold text-primary">
                    <?php echo htmlspecialchars($ex['exam_name'] ?? 'Quarterly Assessment'); ?>
                  </td>
                  <td class="py-3.5 px-4 text-primary font-semibold">
                    <?php echo htmlspecialchars($ex['subject'] ?? 'Quran & Tajweed'); ?>
                  </td>
                  <td class="py-3.5 px-4 text-primary/70">
                    <?php echo date('d M, Y', strtotime($ex['exam_date'] ?? 'now')); ?>
                  </td>
                  <td class="py-3.5 px-4 font-bold font-mono text-primary">
                    <?php echo $ob; ?> / <?php echo $mx; ?> (<?php echo $pct; ?>%)
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 text-[10px] font-black rounded-md uppercase <?php echo $grade_bg; ?>">
                      <?php echo $grade; ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-primary/80 italic">
                    <?php echo htmlspecialchars($ex['remarks'] ?? 'Praiseworthy recitation fluency and pronunciation.'); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="p-12 text-center bg-slate-50 rounded-2xl border border-dashed border-primary/10">
          <i data-lucide="award" class="w-10 h-10 text-primary/30 mx-auto mb-3"></i>
          <h4 class="text-sm font-bold text-primary">No Exam Scores Recorded Yet</h4>
          <p class="text-xs text-primary/60 mt-1">Upcoming quarterly oral/written evaluation marks will be published here.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
