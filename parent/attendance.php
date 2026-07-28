<?php
/**
 * Al Foz Islamic Institute - Parent Ward Attendance Page
 */
require_once __DIR__ . '/includes/parent_context.php';

$present_count = 0;
$absent_count = 0;
$leave_count = 0;
$total_logs = count($child_attendance);

foreach ($child_attendance as $att) {
    $st = strtolower(trim($att['status'] ?? ''));
    if ($st === 'present') $present_count++;
    elseif ($st === 'absent') $absent_count++;
    elseif ($st === 'leave') $leave_count++;
}

$attendance_pct = ($total_logs > 0) ? round(($present_count / $total_logs) * 100) : 100;
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
          <span class="text-primary">Ward Attendance</span>
        </div>
        <h1 class="text-2xl font-black text-primary tracking-tight uppercase">Ward Attendance Log & Class Presence</h1>
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

    <!-- Ward Summary Card -->
    <?php if ($active_child): ?>
    <div class="mb-8 p-6 bg-gradient-to-r from-primary to-[#0f3d44] text-white rounded-[24px] shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">Selected Ward</span>
        <h2 class="text-xl font-black text-white"><?php echo htmlspecialchars($active_child['name']); ?></h2>
        <p class="text-xs text-white/80 font-mono mt-0.5">Roll No: <?php echo htmlspecialchars($active_child['roll_no'] ?? $active_child['student_id'] ?? 'STU-101'); ?> | Instructor: <?php echo htmlspecialchars($active_child['teacher_name'] ?? 'Faculty Mentor'); ?></p>
      </div>
      <div class="px-4 py-2 bg-white/10 rounded-xl text-center border border-white/20">
        <span class="text-[9px] font-bold uppercase tracking-wider text-white/70 block">Overall Attendance Score</span>
        <span class="text-xl font-black text-emerald-300"><?php echo $attendance_pct; ?>%</span>
      </div>
    </div>
    <?php endif; ?>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
          <i data-lucide="calendar" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Total Sessions</span>
          <span class="text-xl font-black text-primary"><?php echo $total_logs; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
          <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Present</span>
          <span class="text-xl font-black text-emerald-700"><?php echo $present_count; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
          <i data-lucide="x-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Absent</span>
          <span class="text-xl font-black text-rose-700"><?php echo $absent_count; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
          <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Leaves</span>
          <span class="text-xl font-black text-amber-700"><?php echo $leave_count; ?></span>
        </div>
      </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/50 mb-6 flex items-center gap-2">
        <i data-lucide="list-checks" class="w-4 h-4 text-primary"></i> Daily Session Log
      </h3>

      <?php if (!empty($child_attendance)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-primary/10 text-primary/50 uppercase tracking-wider text-[10px]">
                <th class="py-3 px-4 font-bold">Date</th>
                <th class="py-3 px-4 font-bold">Status</th>
                <th class="py-3 px-4 font-bold">Topic Covered</th>
                <th class="py-3 px-4 font-bold">Duration</th>
                <th class="py-3 px-4 font-bold">Teacher Feedback</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 font-medium">
              <?php foreach ($child_attendance as $att): 
                $status = ucfirst($att['status'] ?? 'Present');
                $status_bg = 'bg-emerald-100 text-emerald-800';
                if ($status === 'Absent') $status_bg = 'bg-rose-100 text-rose-800';
                elseif ($status === 'Leave') $status_bg = 'bg-amber-100 text-amber-800';
              ?>
                <tr class="hover:bg-primary/5 transition-all">
                  <td class="py-3.5 px-4 font-bold text-primary">
                    <?php echo date('d M, Y', strtotime($att['date'] ?? $att['created_at'] ?? 'now')); ?>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 text-[10px] font-black rounded-md uppercase <?php echo $status_bg; ?>">
                      <?php echo $status; ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-primary">
                    <?php echo htmlspecialchars($att['topic'] ?? $att['notes'] ?? 'Quran Recitation & Tajweed'); ?>
                  </td>
                  <td class="py-3.5 px-4 text-primary/70">
                    <?php echo htmlspecialchars($att['duration'] ?? '30 Mins'); ?>
                  </td>
                  <td class="py-3.5 px-4 text-primary/80 italic">
                    <?php echo htmlspecialchars($att['remarks'] ?? 'Punctual and focused in class.'); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="p-12 text-center bg-slate-50 rounded-2xl border border-dashed border-primary/10">
          <i data-lucide="calendar-check" class="w-10 h-10 text-emerald-500 mx-auto mb-3"></i>
          <h4 class="text-sm font-bold text-primary">All Sessions Up To Date</h4>
          <p class="text-xs text-primary/60 mt-1">Class attendance recorded by the instructor will appear here in real time.</p>
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
