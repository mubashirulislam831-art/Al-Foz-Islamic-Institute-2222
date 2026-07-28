<?php
/**
 * Al Foz Islamic Institute - Student Attendance Log Page
 */
require_once __DIR__ . '/includes/student_context.php';

$present_count = 0;
$absent_count = 0;
$leave_count = 0;
$total_logs = count($student_attendance);

foreach ($student_attendance as $att) {
    $st = strtolower(trim($att['status'] ?? ''));
    if ($st === 'present') $present_count++;
    elseif ($st === 'absent') $absent_count++;
    elseif ($st === 'leave') $leave_count++;
}

$attendance_percentage = ($total_logs > 0) ? round(($present_count / $total_logs) * 100) : 0;
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
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">my attendance</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Student Attendance & Session Log Register</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="px-4 py-2 bg-emerald-50 text-emerald-800 text-xs font-extrabold rounded-xl border border-emerald-200">
          Attendance Score: <?php echo $attendance_percentage; ?>%
        </span>
      </div>
    </div>

    <?php 

    global $pdo;

    $makeup_history = [];

    if ($pdo !== null) {

        $stmt = $pdo->prepare("SELECT r.*, r.new_date as rescheduled_date, r.new_time as time, t.name as teacher_name FROM rescheduled_classes r JOIN classes c ON r.class_id = c.id JOIN teachers t ON c.teacher_id = t.id WHERE c.student_id = ? ORDER BY r.id DESC");

        $stmt->execute([$student["id"]]);

        $makeup_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    ?>
    <!-- Stats Cards Row -->
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
        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
          <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Present</span>
          <span class="text-xl font-black text-emerald-700"><?php echo $present_count; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 flex items-center justify-center shrink-0">
          <i data-lucide="x-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Absent</span>
          <span class="text-xl font-black text-rose-700"><?php echo $absent_count; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center shrink-0">
          <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Excused Leaves</span>
          <span class="text-xl font-black text-amber-700"><?php echo $leave_count; ?></span>
        </div>
      </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-6 flex items-center gap-2">
        <i data-lucide="list-checks" class="w-4 h-4 text-primary"></i> Daily Attendance Register
      </h3>

      <?php if (!empty($student_attendance)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-primary/10 text-primary/50 uppercase tracking-wider text-[10px]">
                <th class="py-3 px-4 font-bold">Session Date</th>
                <th class="py-3 px-4 font-bold">Status</th>
                <th class="py-3 px-4 font-bold">Topic / Lesson Covered</th>
                <th class="py-3 px-4 font-bold">Duration</th>
                <th class="py-3 px-4 font-bold">Remarks / Instructor Notes</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 font-medium text-primary">
              <?php foreach ($student_attendance as $att): 
                $status = ucfirst($att['status'] ?? 'Present');
                $status_bg = 'bg-emerald-50 border-emerald-200 text-emerald-800';
                if ($status === 'Absent') $status_bg = 'bg-rose-50 border-rose-200 text-rose-800';
                elseif ($status === 'Leave') $status_bg = 'bg-amber-50 border-amber-200 text-amber-800';
              ?>
                <tr class="hover:bg-primary/5 transition-all">
                  <td class="py-3.5 px-4 font-mono font-bold">
                    <?php echo date('d M, Y', strtotime($att['date'] ?? $att['created_at'] ?? 'now')); ?>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 text-[9px] font-black rounded-full uppercase border <?php echo $status_bg; ?>">
                      <?php echo $status; ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-4">
                    <?php echo htmlspecialchars($att['topic'] ?? $att['notes'] ?? 'Quran Recitation & Tajweed Practice'); ?>
                  </td>
                  <td class="py-3.5 px-4 text-primary/70 font-mono">
                    <?php echo htmlspecialchars($att['duration'] ?? '30 Mins'); ?>
                  </td>
                  <td class="py-3.5 px-4 italic text-primary/80">
                    <?php echo htmlspecialchars($att['remarks'] ?? 'Good participation & recitation.'); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="p-12 text-center text-primary/60 font-medium">
          <i data-lucide="calendar-x" class="w-12 h-12 text-primary/30 mx-auto mb-3"></i>
          <p class="text-sm font-bold text-primary">No Attendance Records Found</p>
          <p class="text-xs text-primary/60 mt-1">There are no recorded attendance logs for this account yet.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
