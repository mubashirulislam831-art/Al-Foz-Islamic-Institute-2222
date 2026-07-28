<?php
/**
 * Al Foz Islamic Institute - Student Daily Homework Page
 */
require_once __DIR__ . '/includes/student_context.php';

$assigned_count = 0;
$completed_count = 0;

foreach ($student_homework as $hw) {
    $st = strtolower(trim($hw['status'] ?? ''));
    if ($st === 'completed' || $st === 'submitted') $completed_count++;
    else $assigned_count++;
}
if (empty($student_homework)) {
    $assigned_count = 0;
    $completed_count = 0;
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
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">my homework</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Sabaq, Sabaqi & Revision Target Log</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="px-3 py-1.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold rounded-xl">
          Pending Tasks: <?php echo $assigned_count; ?>
        </span>
        <span class="px-3 py-1.5 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold rounded-xl">
          Completed: <?php echo $completed_count; ?>
        </span>
      </div>
    </div>

    <!-- Target Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-primary text-white rounded-[24px] p-6 shadow-md border border-primary/20">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-300">Daily Sabaq (New Lesson)</span>
          <i data-lucide="book-open" class="w-5 h-5 text-amber-300"></i>
        </div>
        <h3 class="text-base font-black text-white"><?php echo htmlspecialchars($student['academic']['sabaq'] ?? 'N/A'); ?></h3>
        <p class="text-[11px] text-emerald-100/90 mt-2 font-medium">Focus on proper Makharij and Ghunnah rules.</p>
      </div>

      <div class="bg-primary/90 text-white rounded-[24px] p-6 shadow-md border border-primary/20">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-300">Sabaqi (Recent Revision)</span>
          <i data-lucide="repeat" class="w-5 h-5 text-emerald-300"></i>
        </div>
        <h3 class="text-base font-black text-white"><?php echo htmlspecialchars($student['academic']['sabaqi'] ?? 'N/A'); ?></h3>
        <p class="text-[11px] text-emerald-100/90 mt-2 font-medium">Recite to guardian prior to class.</p>
      </div>

      <div class="bg-primary/80 text-white rounded-[24px] p-6 shadow-md border border-primary/20">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-sky-300">Manzil (Old Revision)</span>
          <i data-lucide="layers" class="w-5 h-5 text-sky-300"></i>
        </div>
        <h3 class="text-base font-black text-white"><?php echo htmlspecialchars($student['academic']['manzil'] ?? 'N/A'); ?></h3>
        <p class="text-[11px] text-emerald-100/90 mt-2 font-medium">Daily revision target.</p>
      </div>
    </div>

    <!-- Homework Tasks List -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-6 flex items-center gap-2">
        <i data-lucide="file-text" class="w-4 h-4 text-primary"></i> Assigned Homework Tasks & Audio Submissions
      </h3>

      <?php if (!empty($student_homework)): ?>
        <div class="space-y-4">
          <?php foreach ($student_homework as $hw): 
            $status = ucfirst($hw['status'] ?? 'Assigned');
            $is_done = ($status === 'Completed' || $status === 'Submitted');
          ?>
          <div class="p-5 rounded-2xl border <?php echo $is_done ? 'bg-emerald-50/50 border-emerald-200' : 'bg-primary/5 border-primary/15'; ?> shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full border <?php echo $is_done ? 'bg-emerald-100 border-emerald-300 text-emerald-900' : 'bg-amber-100 border-amber-300 text-amber-800'; ?>">
                  <?php echo $status; ?>
                </span>
                <span class="text-[10px] text-primary/50 font-bold uppercase">Due Date: <?php echo date('d M, Y', strtotime($hw['due_date'] ?? 'tomorrow')); ?></span>
              </div>
              <h4 class="text-sm font-black text-primary"><?php echo htmlspecialchars($hw['title'] ?? 'Tajweed Recitation Assignment'); ?></h4>
              <p class="text-xs text-primary/70 font-medium mt-1"><?php echo htmlspecialchars($hw['description'] ?? ''); ?></p>
            </div>

            <button onclick="alert('Submission recorded successfully! Your teacher will review this assignment.')" class="px-4 py-2.5 bg-primary hover:bg-[#10353a] text-white text-xs font-bold rounded-xl shadow-sm transition-all shrink-0 flex items-center gap-2 active:scale-95">
              <i data-lucide="upload" class="w-4 h-4"></i> <?php echo $is_done ? 'Re-submit Audio' : 'Submit Audio Recitation'; ?>
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="p-12 text-center text-primary/60 font-medium">
          <i data-lucide="file-x" class="w-12 h-12 text-primary/30 mx-auto mb-3"></i>
          <p class="text-sm font-bold text-primary">No Homework Available</p>
          <p class="text-xs text-primary/60 mt-1">There are no pending homework assignments assigned to your account at this time.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
