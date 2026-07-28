<?php
/**
 * Al Foz Islamic Institute - Teacher ERP
 * My Attendance
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../auth/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/teacher_attendance_functions.php';

require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_email = $_SESSION['email'] ?? 'teacher@alfoz.org';
$teacher_id = get_teacher_id_by_email($teacher_email);

global $pdo;
$attendance_logs = [];
$total_present = 0;
$total_absent = 0;
$total_leave = 0;
$total_hours = 0;

if ($pdo !== null && $teacher_id) {
    $stmt = $pdo->prepare("SELECT * FROM teacher_attendance WHERE teacher_id = ? ORDER BY date DESC");
    $stmt->execute([$teacher_id]);
    $attendance_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($attendance_logs as $log) {
        if ($log['status'] === 'Present') $total_present++;
        if ($log['status'] === 'Absent') $total_absent++;
        if ($log['status'] === 'Leave') $total_leave++;
        $total_hours += floatval($log['total_teaching_hours']);
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-[#F4F7F9] page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mt-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">MY ATTENDANCE</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Automatic login/logout logs and history</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <!-- Present Days -->
      <div class="bg-white rounded-[24px] p-6 shadow-sm border border-primary/10 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-emerald-500/10 group-hover:bg-emerald-500/20 transition-all blur-2xl"></div>
        <div class="relative z-10 flex flex-col h-full">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
              <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-xs font-black uppercase tracking-widest text-primary/60">Present</h3>
          </div>
          <div class="text-3xl font-black text-primary mt-auto">
            <?php echo $total_present; ?>
          </div>
        </div>
      </div>
      
      <!-- Absent Days -->
      <div class="bg-white rounded-[24px] p-6 shadow-sm border border-primary/10 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-rose-500/10 group-hover:bg-rose-500/20 transition-all blur-2xl"></div>
        <div class="relative z-10 flex flex-col h-full">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-600">
              <i data-lucide="x-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-xs font-black uppercase tracking-widest text-primary/60">Absent</h3>
          </div>
          <div class="text-3xl font-black text-primary mt-auto">
            <?php echo $total_absent; ?>
          </div>
        </div>
      </div>
      
      <!-- Leave Days -->
      <div class="bg-white rounded-[24px] p-6 shadow-sm border border-primary/10 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-amber-500/10 group-hover:bg-amber-500/20 transition-all blur-2xl"></div>
        <div class="relative z-10 flex flex-col h-full">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600">
              <i data-lucide="calendar-off" class="w-5 h-5"></i>
            </div>
            <h3 class="text-xs font-black uppercase tracking-widest text-primary/60">Leave</h3>
          </div>
          <div class="text-3xl font-black text-primary mt-auto">
            <?php echo $total_leave; ?>
          </div>
        </div>
      </div>
      
      <!-- Teaching Hours -->
      <div class="bg-white rounded-[24px] p-6 shadow-sm border border-primary/10 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-indigo-500/10 group-hover:bg-indigo-500/20 transition-all blur-2xl"></div>
        <div class="relative z-10 flex flex-col h-full">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600">
              <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <h3 class="text-xs font-black uppercase tracking-widest text-primary/60">Total Hours</h3>
          </div>
          <div class="text-3xl font-black text-primary mt-auto">
            <?php echo number_format($total_hours, 2); ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Attendance Logs -->
    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-primary/10">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-lg font-black text-primary uppercase tracking-tight">Attendance Logs</h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
          <thead>
            <tr class="border-b border-primary/10 bg-slate-50/50">
              <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider rounded-tl-2xl">Date</th>
              <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">Status</th>
              <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">Login Time</th>
              <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">First Class</th>
              <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">Logout Time</th>
              <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">Teaching Hours</th>
              <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider rounded-tr-2xl">Details</th>
            </tr>
          </thead>
          <tbody class="text-sm divide-y divide-primary/5">
            <?php if (empty($attendance_logs)): ?>
            <tr>
              <td colspan="7" class="p-8 text-center text-primary/50 text-xs font-bold uppercase tracking-wider">
                No attendance logs found.
              </td>
            </tr>
            <?php else: ?>
              <?php foreach ($attendance_logs as $log): 
                $statusClass = '';
                switch ($log['status']) {
                  case 'Present': $statusClass = 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20'; break;
                  case 'Absent': $statusClass = 'bg-rose-500/10 text-rose-600 border border-rose-500/20'; break;
                  case 'Leave': $statusClass = 'bg-amber-500/10 text-amber-600 border border-amber-500/20'; break;
                }
              ?>
              <tr class="hover:bg-slate-50/50 transition-colors group">
                <td class="p-4">
                  <div class="font-bold text-primary">
                    <?php echo date('d M Y', strtotime($log['date'])); ?>
                  </div>
                </td>
                <td class="p-4">
                  <span class="inline-block px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-widest <?php echo $statusClass; ?>">
                    <?php echo htmlspecialchars($log['status']); ?>
                  </span>
                </td>
                <td class="p-4 font-mono text-xs font-bold text-primary/80">
                  <?php echo $log['login_time'] ? date('h:i A', strtotime($log['login_time'])) : '-'; ?>
                </td>
                <td class="p-4 font-mono text-xs font-bold text-primary/80">
                  <?php echo $log['first_class_time'] ? date('h:i A', strtotime($log['first_class_time'])) : '-'; ?>
                </td>
                <td class="p-4 font-mono text-xs font-bold text-primary/80">
                  <?php echo $log['logout_time'] ? date('h:i A', strtotime($log['logout_time'])) : '-'; ?>
                </td>
                <td class="p-4 font-bold text-primary">
                  <?php echo $log['total_teaching_hours'] > 0 ? floatval($log['total_teaching_hours']) . ' hrs' : '-'; ?>
                </td>
                <td class="p-4 text-xs font-medium text-primary/60">
                  <?php 
                    if ($log['status'] === 'Leave') {
                      echo "Leave Status: " . htmlspecialchars($log['leave_status'] ?? 'Pending') . "<br>";
                      echo "Reason: " . htmlspecialchars($log['leave_reason'] ?? '-');
                    } else {
                      echo '-';
                    }
                  ?>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
