<?php
/**
 * Al Foz Islamic Institute - Monthly Attendance Matrix
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Admin');

// Get selected month/year
$selected_month_str = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$parts = explode('-', $selected_month_str);
$year = isset($parts[0]) ? intval($parts[0]) : intval(date('Y'));
$month = isset($parts[1]) ? intval($parts[1]) : intval(date('m'));

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$students_db = get_db_table('students') ?: [];
$students_matrix = [];

if (isset($pdo)) {
    try {
        foreach ($students_db as $s) {
            $student_id = $s['id'];
            
            // Query attendance summary for this month
            $stmt_sum = $pdo->prepare("
                SELECT 
                    COUNT(CASE WHEN status='Present' THEN 1 END) as present,
                    COUNT(CASE WHEN status='Absent' THEN 1 END) as absent,
                    COUNT(CASE WHEN status='Leave' THEN 1 END) as leave_cnt,
                    COUNT(CASE WHEN status='Makeup' THEN 1 END) as makeup
                FROM attendance 
                WHERE student_id = ? AND year = ? AND month = ?
            ");
            $stmt_sum->execute([$student_id, $year, $month]);
            $sum_res = $stmt_sum->fetch(PDO::FETCH_ASSOC);
            
            $present = $sum_res['present'] ?? 0;
            $absent = $sum_res['absent'] ?? 0;
            $leave_cnt = $sum_res['leave_cnt'] ?? 0;
            $makeup = $sum_res['makeup'] ?? 0;
            
            $total_attended_days = $present + $absent;
            $pct = ($total_attended_days > 0) ? round(($present / $total_attended_days) * 100) : 100;
            
            // Fetch individual day records
            $stmt_days = $pdo->prepare("SELECT day, status FROM attendance WHERE student_id = ? AND year = ? AND month = ?");
            $stmt_days->execute([$student_id, $year, $month]);
            $day_records = $stmt_days->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
            
            $students_matrix[] = [
                'id' => $student_id,
                'name' => $s['name'],
                'pic' => 'https://ui-avatars.com/api/?name=' . urlencode($s['name']) . '&background=184D55&color=F7FAFF',
                'teacher' => $s['teacher_name'] ?: 'Unassigned',
                'country' => $s['country'] ?: 'Pakistan',
                'present' => $present,
                'absent' => $absent,
                'leave' => $leave_cnt,
                'makeup' => $makeup,
                'pct' => $pct,
                'days' => $day_records
            ];
        }
    } catch (PDOException $ex) {
        // Safe fallback
    }
}
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
            <i data-lucide="calendar-days" class="w-7 h-7"></i>
        </div>
        <div>
          <h1 class="text-2xl font-black text-primary tracking-tight">Monthly Matrix</h1>
          <p class="text-xs text-primary/60 mt-1 font-medium">Detailed monthly overview of student attendance</p>
        </div>
      </div>
      <form method="GET" class="flex gap-3">
        <input type="month" name="month" class="px-4 py-3 bg-white border border-primary/20 rounded-xl text-sm font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none" value="<?php echo htmlspecialchars($selected_month_str); ?>">
        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(24,77,85,0.3)]">Filter</button>
      </form>
    </div>

    <!-- Attendance Matrix -->
    <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-primary/5 text-primary/50 uppercase font-black text-[10px] tracking-widest border-b border-primary/10">
                        <th class="px-6 py-4 sticky left-0 bg-white/95 backdrop-blur z-10 border-r border-primary/10">Student Profile</th>
                        <th class="px-4 py-4 text-center">Summary</th>
                        <?php for($i=1; $i<=$days_in_month; $i++): ?>
                            <th class="px-2 py-4 text-center min-w-[32px]"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    <?php if(empty($students_matrix)): ?>
                        <tr><td colspan="<?php echo $days_in_month + 2; ?>" class="text-center p-8 text-gray-400 font-bold">No students found.</td></tr>
                    <?php endif; ?>
                    <?php foreach($students_matrix as $s): ?>
                    <tr class="hover:bg-primary/[0.02] transition-colors">
                        <td class="px-6 py-4 sticky left-0 bg-white border-r border-primary/10 shadow-[4px_0_12px_rgba(0,0,0,0.02)]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden border border-primary/10 shrink-0">
                                    <img src="<?php echo $s['pic']; ?>" alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-primary text-sm"><?php echo htmlspecialchars($s['name']); ?></p>
                                    <p class="text-[9px] text-primary/60 font-black uppercase tracking-widest mt-0.5">
                                        <?php echo htmlspecialchars($s['teacher']); ?> &bull; <?php echo htmlspecialchars($s['country']); ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-[9px] font-bold" title="Present"><?php echo $s['present']; ?> P</span>
                                <span class="px-2 py-1 bg-red-50 text-red-700 rounded text-[9px] font-bold" title="Absent"><?php echo $s['absent']; ?> A</span>
                                <span class="px-2 py-1 bg-primary text-white rounded text-[9px] font-black"><?php echo $s['pct']; ?>%</span>
                            </div>
                        </td>
                        <?php for($i=1; $i<=$days_in_month; $i++): 
                            $raw_status = $s['days'][$i] ?? '-';
                            $status = '-';
                            if ($raw_status === 'Present') $status = 'P';
                            elseif ($raw_status === 'Absent') $status = 'A';
                            elseif ($raw_status === 'Leave') $status = 'L';
                            elseif ($raw_status === 'Makeup') $status = 'M';
                            
                            $color = 'text-gray-300';
                            $bg = 'hover:bg-gray-50';
                            if($status === 'P') { $color = 'text-green-600 font-black'; $bg = 'hover:bg-green-50'; }
                            if($status === 'A') { $color = 'text-red-600 font-black'; $bg = 'hover:bg-red-50'; }
                            if($status === 'L') { $color = 'text-orange-500 font-black'; $bg = 'hover:bg-orange-50'; }
                            if($status === 'M') { $color = 'text-blue-600 font-black'; $bg = 'hover:bg-blue-50'; }
                        ?>
                            <td class="px-1 py-4 text-center cursor-pointer <?php echo $bg; ?> transition-colors" onclick="openEditModal(<?php echo $s['id']; ?>, <?php echo $i; ?>)">
                                <span class="<?php echo $color; ?>"><?php echo $status; ?></span>
                            </td>
                        <?php endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
    function openEditModal(studentId, day) {
        alert('Edit functionality for student ' + studentId + ' on day ' + day);
    }
</script>
