<?php
/**
 * Al Foz Islamic Institute - Student Weekly Schedule & Timetable Page
 */
require_once __DIR__ . '/includes/student_context.php';
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
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">my schedule</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Class Timetable & Session Matrix</p>
      </div>
      <div class="px-4 py-2 bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold rounded-xl flex items-center gap-2">
        <i data-lucide="clock" class="w-4 h-4 text-emerald-600"></i> Next Class: <span class="font-black text-emerald-900"><?php echo htmlspecialchars($next_class_str); ?></span>
      </div>
    </div>

    <!-- Timetable Grid -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-primary/10">
        <div>
          <h3 class="text-xs font-black text-primary uppercase tracking-wider">Weekly Session Timetable Matrix</h3>
          <p class="text-[11px] text-primary/60 font-medium mt-0.5">Timezone: <span class="font-bold text-primary"><?php echo htmlspecialchars($student['timezone'] ?? 'PKT'); ?></span></p>
        </div>
        
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3.5">
        <?php 
        $day_labels = [
          'monday' => 'Monday',
          'tuesday' => 'Tuesday',
          'wednesday' => 'Wednesday',
          'thursday' => 'Thursday',
          'friday' => 'Friday',
          'saturday' => 'Saturday',
          'sunday' => 'Sunday'
        ];

        foreach ($days_of_week as $index => $day_key): 
          $is_today = ($day_key === $today_day);
          $is_active = isset($student[$day_key . '_enabled']) && $student[$day_key . '_enabled'];
          $time_str = $student[$day_key . '_time'] ?? '';
          $formatted = $time_str ? date('h:i A', strtotime($time_str)) : '';
        ?>
        <div class="rounded-2xl border p-4 transition-all <?php echo $is_today ? 'bg-primary text-white border-transparent shadow-md font-bold' : ($is_active ? 'bg-primary/90 text-white border-transparent' : 'bg-slate-50 border-slate-100 opacity-60 text-slate-400'); ?>">
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-black uppercase tracking-wider">
              <?php echo $day_labels[$day_key]; ?>
            </span>
            <?php if($is_today): ?>
              <span class="px-2 py-0.5 bg-white text-primary text-[8px] font-black rounded-full uppercase">Today</span>
            <?php endif; ?>
          </div>

          <?php if($is_active && $formatted): ?>
            <div class="mt-2 pt-2 border-t border-current/20">
              <span class="text-[9px] font-extrabold uppercase tracking-widest block opacity-70">Session Time</span>
              <span class="text-xs font-black block mt-0.5"><?php echo $formatted; ?></span>
              <span class="inline-block mt-2 px-2 py-0.5 bg-white/20 text-current text-[8px] font-extrabold rounded-md uppercase">Live Session</span>
            </div>
          <?php else: ?>
            <div class="mt-2 pt-2 border-t border-slate-200">
              <span class="text-[10px] font-semibold text-slate-400 block italic">Rest Day</span>
            </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Instructor & Class Access Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
        <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-4 flex items-center gap-2">
          <i data-lucide="book-open" class="w-4 h-4 text-primary"></i> Course Information & Department
        </h3>
        <div class="space-y-3 text-xs">
          <div class="flex justify-between items-center border-b border-primary/10 pb-2.5">
            <span class="text-primary/60 font-semibold">Course Title:</span>
            <span class="text-primary font-bold"><?php echo htmlspecialchars($student['course'] ?? 'Quran & Tajweed Masterclass'); ?></span>
          </div>
          <div class="flex justify-between items-center border-b border-primary/10 pb-2.5">
            <span class="text-primary/60 font-semibold">Faculty Mentor:</span>
            <span class="text-primary font-bold"><?php echo htmlspecialchars($student['teacher_name'] ?? 'Faculty Instructor'); ?></span>
          </div>
          <div class="flex justify-between items-center border-b border-primary/10 pb-2.5">
            <span class="text-primary/60 font-semibold">Class Duration:</span>
            <span class="text-primary font-bold">30 Minutes / Session</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-primary/60 font-semibold">Class Platform:</span>
            <span class="text-primary font-bold">Zoom / Google Meet (Direct Portal Link)</span>
          </div>
        </div>
      </div>

      <div class="bg-primary text-white rounded-[24px] p-6 flex flex-col justify-between shadow-sm">
        <div>
          <h3 class="text-xs font-black uppercase tracking-wider text-white mb-2">Session Guidelines & Etiquette</h3>
          <ul class="text-xs text-emerald-100/90 space-y-2 font-medium list-disc list-inside">
            <li>Please join the session 5 minutes prior to the scheduled start time.</li>
            <li>Ensure a quiet environment and keep your Quran & note copy ready.</li>
            <li>If you are unable to attend a session, notify your instructor via WhatsApp at least 2 hours in advance.</li>
          </ul>
        </div>
        <div class="mt-4 pt-4 border-t border-white/10 flex justify-end">
          
        </div>
      </div>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
