<?php
/**
 * Al Foz Islamic Institute - Teacher Self Attendance View
 */
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../auth/permissions.php';
require_once __DIR__ . '/../includes/teachers_data.php';

require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_name = $_SESSION['name'] ?? 'Faculty Member';
$teacher_email = $_SESSION['email'] ?? 'teacher@alfoz.org';

$all_teachers = get_all_teachers();
$teacher = null;
foreach ($all_teachers as $t) {
    if ((isset($t['name']) && $t['name'] === $teacher_name) || (isset($t['email']) && strtolower($t['email']) === strtolower($teacher_email))) {
        $teacher = $t;
        break;
    }
}
if (!$teacher && !empty($all_teachers)) {
    $teacher = $all_teachers[0];
}

$employee_id = $teacher['employee_id'] ?? 'EMP-000';

// Fetch all attendance records for this specific teacher
$all_teacher_attendance = get_db_table('teacher_attendance') ?: [];
$this_teacher_attendance = [];
foreach ($all_teacher_attendance as $att) {
    if (isset($att['employee_id']) && $att['employee_id'] === $employee_id) {
        $this_teacher_attendance[] = $att;
    }
}

// Sort by date descending
usort($this_teacher_attendance, function($a, $b) {
    return strcmp($b['date'] ?? '', $a['date'] ?? '');
});

// Calculate statistics
$total_days = count($this_teacher_attendance);
$present_days = 0;
$late_days = 0;
$leaves_count = 0;

foreach ($this_teacher_attendance as $att) {
    $st = strtolower($att['status'] ?? '');
    if ($st === 'present') {
        $present_days++;
    } elseif ($st === 'late' || $st === 'late present') {
        $present_days++;
        $late_days++;
    } elseif ($st === 'leave' || $st === 'on leave' || $st === 'teacher_leave') {
        $leaves_count++;
    }
}

$attendance_pct = ($total_days > 0) ? round(($present_days / $total_days) * 100, 1) : 100.0;
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition font-['Poppins']">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">My Attendance</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Faculty Log Register & Performance Metrics</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="px-4 py-2 bg-emerald-50 text-emerald-800 text-xs font-extrabold rounded-xl border border-emerald-200 shadow-sm">
          Attendance Rate: <?php echo $attendance_pct; ?>%
        </span>
      </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/5">
          <i data-lucide="calendar" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Total Logged Days</span>
          <span class="text-xl font-black text-primary"><?php echo $total_days; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
          <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Presents</span>
          <span class="text-xl font-black text-emerald-700"><?php echo $present_days; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 flex items-center justify-center shrink-0">
          <i data-lucide="alert-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Late Arrivals</span>
          <span class="text-xl font-black text-rose-700"><?php echo $late_days; ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 flex items-center justify-center shrink-0">
          <i data-lucide="minus-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Leaves Taken</span>
          <span class="text-xl font-black text-amber-700"><?php echo $leaves_count; ?></span>
        </div>
      </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-6 flex items-center gap-2">
        <i data-lucide="list-checks" class="w-4 h-4 text-primary"></i> Faculty Daily Attendance Register
      </h3>

      <?php if (!empty($this_teacher_attendance)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-primary/10 text-primary/50 uppercase tracking-wider text-[10px] font-bold">
                <th class="py-3 px-4">Date</th>
                <th class="py-3 px-4">Check In</th>
                <th class="py-3 px-4">Check Out</th>
                <th class="py-3 px-4">Work Duration</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4 text-right">Remarks</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 font-medium text-primary">
              <?php foreach ($this_teacher_attendance as $att): 
                $status = ucfirst($att['status'] ?? 'Present');
                $status_bg = 'bg-emerald-50 border-emerald-200 text-emerald-800';
                if ($status === 'Absent') $status_bg = 'bg-rose-50 border-rose-200 text-rose-800';
                elseif ($status === 'Late' || $status === 'Late present') $status_bg = 'bg-orange-50 border-orange-200 text-orange-800';
                elseif ($status === 'Leave' || $status === 'On leave' || $status === 'Approved leave') $status_bg = 'bg-amber-50 border-amber-200 text-amber-800';
              ?>
                <tr class="hover:bg-primary/5 transition-all">
                  <td class="py-3.5 px-4 font-mono font-bold">
                    <?php echo date('d M, Y', strtotime($att['date'] ?? 'now')); ?>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-primary/70">
                    <?php echo htmlspecialchars($att['check_in'] ?? 'N/A'); ?>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-primary/70">
                    <?php echo htmlspecialchars($att['check_out'] ?? 'N/A'); ?>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-primary/80 font-bold">
                    <?php echo htmlspecialchars($att['hours'] ?? '0.0'); ?>h
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 text-[9px] font-black rounded-full uppercase border <?php echo $status_bg; ?>">
                      <?php echo $status; ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-4 italic text-primary/80 text-right">
                    <?php echo htmlspecialchars($att['remarks'] ?? 'N/A'); ?>
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
          <p class="text-xs text-primary/60 mt-1">There are no recorded attendance logs for your account yet.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
