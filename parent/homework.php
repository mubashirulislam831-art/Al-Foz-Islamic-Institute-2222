<?php
/**
 * Al Foz Islamic Institute - Parent Ward Homework Tracker Page
 */
require_once __DIR__ . '/includes/parent_context.php';

$assigned_count = 0;
$completed_count = 0;

foreach ($child_homework as $hw) {
    $st = strtolower(trim($hw['status'] ?? ''));
    if ($st === 'completed' || $st === 'submitted') $completed_count++;
    else $assigned_count++;
}
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
          <span class="text-primary">Assignments Tracker</span>
        </div>
        <h1 class="text-2xl font-black text-primary tracking-tight uppercase">Ward Sabaq & Revision Tracker</h1>
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

    <!-- Ward Targets Overview -->
    <?php if ($active_child): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-gradient-to-br from-emerald-950 to-primary text-white rounded-[24px] p-6 shadow-md border border-emerald-800/30">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-300">Daily Sabaq Target</span>
          <i data-lucide="book-open" class="w-5 h-5 text-emerald-300"></i>
        </div>
        <h3 class="text-base font-black text-white"><?php echo htmlspecialchars($active_child['academic']['sabaq'] ?? 'Surah Al-Baqarah (Verses 1 - 10)'); ?></h3>
        <p class="text-[11px] text-white/70 mt-2 font-medium">Please supervise 15 minutes daily recitation.</p>
      </div>

      <div class="bg-gradient-to-br from-teal-950 to-primary text-white rounded-[24px] p-6 shadow-md border border-teal-800/30">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-300">Sabaqi Revision</span>
          <i data-lucide="repeat" class="w-5 h-5 text-teal-300"></i>
        </div>
        <h3 class="text-base font-black text-white"><?php echo htmlspecialchars($active_child['academic']['sabaqi'] ?? 'Surah Al-Fatiha & Last 5 Surahs'); ?></h3>
        <p class="text-[11px] text-white/70 mt-2 font-medium">Listen to recitation before evening class.</p>
      </div>

      <div class="bg-gradient-to-br from-cyan-950 to-primary text-white rounded-[24px] p-6 shadow-md border border-cyan-800/30">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-cyan-300">Manzil Target</span>
          <i data-lucide="layers" class="w-5 h-5 text-cyan-300"></i>
        </div>
        <h3 class="text-base font-black text-white"><?php echo htmlspecialchars($active_child['academic']['manzil'] ?? 'Juz 30 Complete'); ?></h3>
        <p class="text-[11px] text-white/70 mt-2 font-medium">Daily 1 Para review target.</p>
      </div>
    </div>
    <?php endif; ?>

    <!-- Assigned Homework Tasks -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/50 mb-6 flex items-center gap-2">
        <i data-lucide="file-text" class="w-4 h-4 text-primary"></i> Assigned Tasks & Supervised Progress
      </h3>

      <?php if (!empty($child_homework)): ?>
        <div class="space-y-4">
          <?php foreach ($child_homework as $hw): 
            $status = ucfirst($hw['status'] ?? 'Assigned');
            $is_done = ($status === 'Completed' || $status === 'Submitted');
          ?>
          <div class="p-5 rounded-2xl border <?php echo $is_done ? 'bg-emerald-50/50 border-emerald-200' : 'bg-white border-primary/15'; ?> shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-md <?php echo $is_done ? 'bg-emerald-200 text-emerald-900' : 'bg-amber-100 text-amber-800'; ?>">
                  <?php echo $status; ?>
                </span>
                <span class="text-[10px] text-primary/50 font-bold uppercase">Due Date: <?php echo date('d M, Y', strtotime($hw['due_date'] ?? 'tomorrow')); ?></span>
              </div>
              <h4 class="text-sm font-black text-primary"><?php echo htmlspecialchars($hw['title'] ?? 'Tajweed Recitation Assignment'); ?></h4>
              <p class="text-xs text-primary/70 font-medium mt-1"><?php echo htmlspecialchars($hw['description'] ?? 'Prepare audio recording of Surah Al-Kahf verses 1-10.'); ?></p>
            </div>

            <span class="px-3 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-xl border border-primary/20">
              <?php echo $is_done ? 'Verified Completed' : 'Pending Supervision'; ?>
            </span>
          </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="p-12 text-center bg-slate-50 rounded-2xl border border-dashed border-primary/10">
          <i data-lucide="check-circle-2" class="w-10 h-10 text-emerald-500 mx-auto mb-3"></i>
          <h4 class="text-sm font-bold text-primary">All Homework Supervised</h4>
          <p class="text-xs text-primary/60 mt-1">No pending homework assignments require attention.</p>
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
