<?php
/**
 * Al Foz Islamic Institute - Attendance Management Dashboard
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

require_once __DIR__ . '/../../includes/students_data.php';
$students = get_all_students() ?: [];
$attendance_records = get_db_table('attendance') ?: [];

$total_classes_today = 0;
$present_classes = 0;
$absent_classes = 0;
$leave_classes = 0;
$makeup_classes = 0;

$today_day = strtolower(date('l')); // e.g. 'monday'

foreach ($students as $s) {
    if (isset($s[$today_day . '_enabled']) && $s[$today_day . '_enabled']) {
        $total_classes_today++;
        $status = $s['attendance_status'] ?? 'Pending';
        if ($status === 'Present') {
            $present_classes++;
        } elseif ($status === 'Absent') {
            $absent_classes++;
        } elseif (strpos($status, 'Leave') !== false || $status === 'Leave' || $status === 'Student On Leave' || $status === 'Teacher On Leave') {
            $leave_classes++;
        }
    }
}

$makeup_classes_db = get_db_table('rescheduled_classes') ?: [];
$makeup_classes = count($makeup_classes_db);

$attendance_pct = ($total_classes_today > 0) ? round(($present_classes / $total_classes_today) * 100, 1) : 0;

?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent font-['Poppins']">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Module Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-primary/5 rounded-xl flex items-center justify-center text-primary shrink-0 border border-primary/10">
            <i data-lucide="layout-dashboard" class="w-7 h-7"></i>
        </div>
        <div>
          <h1 class="text-2xl font-black text-primary tracking-tight">Attendance Dashboard</h1>
          <p class="text-xs text-primary/60 mt-1 font-medium">Real-time monitoring of educational engagement</p>
        </div>
      </div>
      <div class="flex gap-2">
        <a href="today_attendance.php" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 hover:-translate-y-0.5 active:scale-95 transition-all shadow-[0_8px_20px_-6px_rgba(24,77,85,0.4)] flex items-center gap-2">
          <i data-lucide="activity" class="w-4 h-4"></i> Live Desk
        </a>
      </div>
    </div>

    <!-- 6 STATS CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
      <!-- Total Classes -->
      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm text-center group hover:-translate-y-1 hover:shadow-md transition-all">
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-3 text-primary">
            <i data-lucide="calendar" class="w-5 h-5"></i>
        </div>
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Total Classes</span>
        <span class="text-3xl font-black text-primary mt-1 block"><?php echo $total_classes_today; ?></span>
      </div>
      <!-- Present -->
      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm text-center group hover:-translate-y-1 hover:shadow-md transition-all">
        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-3 text-green-600">
            <i data-lucide="user-check" class="w-5 h-5"></i>
        </div>
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Present</span>
        <span class="text-3xl font-black text-green-600 mt-1 block"><?php echo $present_classes; ?></span>
      </div>
      <!-- Absent -->
      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm text-center group hover:-translate-y-1 hover:shadow-md transition-all">
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3 text-red-600">
            <i data-lucide="user-x" class="w-5 h-5"></i>
        </div>
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Absent</span>
        <span class="text-3xl font-black text-red-600 mt-1 block"><?php echo $absent_classes; ?></span>
      </div>
      <!-- Leave -->
      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm text-center group hover:-translate-y-1 hover:shadow-md transition-all">
        <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center mx-auto mb-3 text-orange-500">
            <i data-lucide="user-minus" class="w-5 h-5"></i>
        </div>
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Leave</span>
        <span class="text-3xl font-black text-orange-500 mt-1 block"><?php echo $leave_classes; ?></span>
      </div>
      <!-- Makeup -->
      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm text-center group hover:-translate-y-1 hover:shadow-md transition-all">
        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3 text-blue-600">
            <i data-lucide="refresh-cw" class="w-5 h-5"></i>
        </div>
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/50 block">Makeup</span>
        <span class="text-3xl font-black text-blue-600 mt-1 block"><?php echo $makeup_classes; ?></span>
      </div>
      <!-- Percentage -->
      <div class="bg-gradient-to-br from-primary to-[#11383e] rounded-2xl p-6 shadow-[0_8px_20px_-6px_rgba(24,77,85,0.4)] text-center group hover:-translate-y-1 transition-all">
        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-3 text-white">
            <i data-lucide="percent" class="w-5 h-5"></i>
        </div>
        <span class="text-[10px] uppercase font-bold tracking-widest text-white/60 block">Attendance</span>
        <span class="text-3xl font-black text-white mt-1 block"><?php echo $attendance_pct; ?>%</span>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      <!-- Teacher Attendance Graph Placeholder -->
      <div class="bg-white rounded-[24px] p-8 border border-primary/10 shadow-sm relative overflow-hidden">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-sm font-black uppercase tracking-widest text-primary/80 flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-4 h-4 text-primary/50"></i> Faculty Engagement
            </h3>
            <span class="px-3 py-1 bg-primary/5 text-primary rounded-lg text-[10px] font-bold">This Month</span>
        </div>
        <div class="h-64 flex items-end justify-between gap-3 relative z-10">
          <?php 
          $teachers_db = get_db_table('teachers') ?: [];
          if (empty($teachers_db)): ?>
            <div class="w-full h-full flex items-center justify-center text-primary/40 text-[11px] font-bold uppercase tracking-wider">No faculty engagement logs yet</div>
          <?php else:
            $display_teachers = array_slice($teachers_db, 0, 10);
            foreach($display_teachers as $t): 
                $emp_id = $t['employee_id'] ?? $t['id'] ?? '';
                $total_days = 0;
                $present_days = 0;
                if (isset($pdo) && $emp_id) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN status='Present' THEN 1 ELSE 0 END) as present FROM teacher_attendance WHERE employee_id = ?");
                    $stmt->execute([$emp_id]);
                    $res = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total_days = $res['total'] ?? 0;
                    $present_days = $res['present'] ?? 0;
                }
                $pct = ($total_days > 0) ? round(($present_days / $total_days) * 100) : 0;
          ?>
            <div class="flex-grow flex flex-col items-center gap-3 group">
              <div class="w-full bg-primary/5 rounded-t-xl relative overflow-hidden group-hover:bg-primary/10 transition-all border border-primary/5 h-48">
                <div class="absolute bottom-0 w-full bg-gradient-to-t from-primary to-primary/80 transition-all duration-500 shadow-inner" style="height: <?php echo $pct; ?>%"></div>
              </div>
              <span class="text-[9px] font-bold text-primary/60 truncate w-12 text-center" title="<?php echo htmlspecialchars($t['name'] ?? ''); ?>"><?php echo htmlspecialchars($t['name'] ?? 'T'); ?></span>
            </div>
          <?php endforeach; 
          endif; ?>
        </div>
      </div>

      <!-- Student Attendance Graph Placeholder -->
      <div class="bg-white rounded-[24px] p-8 border border-primary/10 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-sm font-black uppercase tracking-widest text-primary/80 flex items-center gap-2">
                <i data-lucide="pie-chart" class="w-4 h-4 text-primary/50"></i> Student Participation
            </h3>
        </div>
        <div class="h-64 flex items-center justify-center">
          <div class="relative w-56 h-56 rounded-full border-[16px] border-primary/5 flex items-center justify-center shadow-inner">
            <div class="absolute inset-0 rounded-full border-[16px] border-primary border-t-transparent border-l-transparent rotate-45 opacity-90 transition-all"></div>
            <div class="text-center">
              <span class="text-4xl font-black text-primary block tracking-tighter"><?php echo $attendance_pct; ?>%</span>
              <span class="text-[9px] font-bold text-primary/50 uppercase tracking-widest mt-1 block">Global Rate</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Country Attendance & Monthly Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <div class="lg:col-span-4 bg-white rounded-[24px] p-8 border border-primary/10 shadow-sm">
        <h3 class="text-sm font-black uppercase tracking-widest text-primary/80 flex items-center gap-2 mb-8">
            <i data-lucide="globe" class="w-4 h-4 text-primary/50"></i> Regional Activity
        </h3>
        <div class="space-y-6">
          <?php 
          $regional_data = [];
          if (isset($pdo)) {
              try {
                  $stmt = $pdo->query("SELECT country, COUNT(*) as cnt FROM students WHERE country IS NOT NULL AND country != '' GROUP BY country ORDER BY cnt DESC LIMIT 4");
                  $regional_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  $colors = ['bg-green-500', 'bg-blue-500', 'bg-red-500', 'bg-orange-500'];
                  foreach ($regional_rows as $idx => $row) {
                      $country_name = $row['country'];
                      $stmt_att = $pdo->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN status='Present' THEN 1 ELSE 0 END) as present FROM attendance a JOIN students s ON a.student_id = s.id WHERE s.country = ?");
                      $stmt_att->execute([$country_name]);
                      $att_res = $stmt_att->fetch(PDO::FETCH_ASSOC);
                      $total_att = $att_res['total'] ?? 0;
                      $present_att = $att_res['present'] ?? 0;
                      $val = ($total_att > 0) ? round(($present_att / $total_att) * 100) : 100;
                      
                      $regional_data[] = [
                          'name' => $country_name,
                          'val' => $val,
                          'color' => $colors[$idx % count($colors)]
                      ];
                  }
              } catch (PDOException $ex) {
                  // Fallback if query fails
              }
          }
          if (empty($regional_data)): ?>
            <div class="text-center text-primary/40 font-bold uppercase tracking-widest text-[10px] py-12">No regional data available</div>
          <?php else: 
            foreach($regional_data as $c): ?>
              <div>
                <div class="flex justify-between text-[11px] font-bold mb-2 text-primary">
                  <span class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full <?php echo $c['color']; ?>"></div> <?php echo htmlspecialchars($c['name']); ?></span>
                  <span><?php echo $c['val']; ?>%</span>
                </div>
                <div class="w-full h-2 bg-primary/5 rounded-full overflow-hidden border border-primary/10">
                  <div class="<?php echo $c['color']; ?> h-full shadow-inner" style="width: <?php echo $c['val']; ?>%"></div>
                </div>
              </div>
            <?php endforeach; 
          endif; ?>
        </div>
      </div>

      <div class="lg:col-span-8 bg-white rounded-[24px] p-8 border border-primary/10 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-sm font-black uppercase tracking-widest text-primary/80 flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-4 h-4 text-primary/50"></i> Annual Matrix
            </h3>
        </div>
        <div class="h-52 flex items-end justify-between px-4">
          <?php 
          $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
          $current_year = date('Y');
          foreach($months as $idx => $m): 
              $month_num = $idx + 1;
              $val = 0;
              if (isset($pdo)) {
                  $stmt_m = $pdo->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN status='Present' THEN 1 ELSE 0 END) as present FROM attendance WHERE year = ? AND month = ?");
                  $stmt_m->execute([$current_year, $month_num]);
                  $res_m = $stmt_m->fetch(PDO::FETCH_ASSOC);
                  $total_m = $res_m['total'] ?? 0;
                  $present_m = $res_m['present'] ?? 0;
                  $val = ($total_m > 0) ? round(($present_m / $total_m) * 100) : 0;
              }
              $h = ($val > 0) ? ($val * 1.6) : 5;
          ?>
            <div class="flex flex-col items-center gap-3 group">
              <div class="w-10 bg-gradient-to-t from-primary to-primary/70 rounded-lg group-hover:opacity-80 transition-all shadow-sm" style="height: <?php echo $h; ?>px"></div>
              <span class="text-[10px] font-bold text-primary/60 uppercase"><?php echo $m; ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
