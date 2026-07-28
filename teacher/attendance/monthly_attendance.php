<?php
/**
 * Al Foz Islamic Institute - Teacher ERP
 * Student Monthly Attendance Report Generator
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../auth/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_name = $_SESSION['name'] ?? 'Faculty Member';
$today_day = strtolower(date('l'));

// Fetch students assigned to this teacher
$all_students = get_all_students() ?: [];
$my_students = array_filter($all_students, function($s) use ($teacher_name) {
    return (isset($s['teacher_name']) && $s['teacher_name'] === $teacher_name);
});

// Load selected student
$selected_student_id = $_GET['student_id'] ?? '';
$selected_student = null;
$student_attendance_logs = [];

if ($selected_student_id) {
    foreach ($my_students as $s) {
        $sid = $s['roll_no'] ?? $s['student_id'] ?? $s['id'] ?? '';
        if ($sid == $selected_student_id) {
            $selected_student = $s;
            break;
        }
    }
    
    if ($selected_student) {
        $all_attendance = get_db_table('attendance') ?: [];
        $student_attendance_logs = array_filter($all_attendance, function($a) use ($selected_student_id) {
            return isset($a['student_id']) && $a['student_id'] == $selected_student_id;
        });
        // Sort chronology by date descending
        usort($student_attendance_logs, function($a, $b) {
            return strcmp($b['date'] ?? '', $a['date'] ?? '');
        });
    }
}

// Compute metrics
$total_classes = count($student_attendance_logs);
$present_count = 0;
$absent_count = 0;
$leave_count = 0;

foreach ($student_attendance_logs as $log) {
    $st = strtolower(trim($log['status'] ?? ''));
    if ($st === 'present') {
        $present_count++;
    } elseif ($st === 'absent') {
        $absent_count++;
    } elseif ($st === 'leave' || strpos($st, 'leave') !== false) {
        $leave_count++;
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    
    <div class="mb-6 flex justify-between items-end mt-4">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Monthly Attendance Report</h1>
        <p class="text-xs text-primary/60 mt-1">Generate Student Monthly Report</p>
      </div>
    </div>
    
    <!-- Student Selector -->
    <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-6 mb-8">
        <form method="GET" class="flex flex-col sm:flex-row items-end gap-4 max-w-xl">
            <div class="flex-grow w-full">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-primary/70 mb-2">Select Student</label>
                <select name="student_id" id="student_select" class="w-full px-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 outline-none">
                    <option value="">Choose a student...</option>
                    <?php foreach ($my_students as $student): 
                        $student_id = $student['id'];
                    ?>
                        <option value="<?php echo htmlspecialchars($student_id); ?>" <?php echo $selected_student_id == $student_id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($student['name']); ?> (<?php echo htmlspecialchars($student_id); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-opacity-95 text-white px-6 py-2 rounded-xl font-bold uppercase text-[10px] tracking-wider shadow-md transition-all h-[38px] flex items-center justify-center gap-1.5 whitespace-nowrap">
               <i data-lucide="eye" class="w-4 h-4"></i> View Report
            </button>
        </form>
    </div>

    <!-- Report Container (Only visible if selected) -->
    <?php if ($selected_student): ?>
    <div id="report_container" class="max-w-5xl mx-auto bg-white rounded-3xl border border-primary/10 shadow-sm overflow-hidden mb-8">
        
        <!-- Header -->
        <div class="bg-primary p-8 text-center border-b border-primary/10 text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
            <div class="absolute right-6 top-6 flex gap-2 z-10">
                <button onclick="window.print()" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-colors shadow-sm border border-white/5"><i data-lucide="printer" class="w-3.5 h-3.5 inline mr-1"></i> Print</button>
            </div>
            <div class="relative z-10">
              <h2 class="text-lg font-black uppercase tracking-[0.2em] opacity-90 mb-2 mt-4 sm:mt-0">Al Foz Islamic Institute</h2>
              <div class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">Official Seeker Performance & Progress Ledger</div>
              <div class="mt-4 inline-block bg-white/10 px-4 py-1.5 rounded-full text-xs font-mono font-bold tracking-wider" id="rep_month"><?php echo date('F Y'); ?></div>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-[10px] font-black uppercase text-primary/40 mb-4 tracking-widest border-b border-primary/5 pb-2">Seeker Profile</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-end border-b border-dashed border-primary/10 pb-1">
                            <span class="text-xs font-bold text-primary/60 uppercase">Name:</span>
                            <span class="text-sm font-black text-primary" id="rep_name"><?php echo htmlspecialchars($selected_student['name']); ?></span>
                        </div>
                        <div class="flex justify-between items-end border-b border-dashed border-primary/10 pb-1">
                            <span class="text-xs font-bold text-primary/60 uppercase">ID:</span>
                            <span class="text-xs font-bold text-slate-500 font-mono" id="rep_id"><?php echo htmlspecialchars($selected_student_id); ?></span>
                        </div>
                        <div class="flex justify-between items-end border-b border-dashed border-primary/10 pb-1">
                            <span class="text-xs font-bold text-primary/60 uppercase">Course:</span>
                            <span class="text-xs font-bold text-primary" id="rep_course"><?php echo htmlspecialchars($selected_student['course'] ?? 'Quranic Studies'); ?></span>
                        </div>
                        <div class="flex justify-between items-end border-b border-dashed border-primary/10 pb-1">
                            <span class="text-xs font-bold text-primary/60 uppercase">Teacher:</span>
                            <span class="text-xs font-bold text-primary" id="rep_teacher"><?php echo htmlspecialchars($selected_student['teacher_name'] ?? $teacher_name); ?></span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-[10px] font-black uppercase text-primary/40 mb-4 tracking-widest border-b border-primary/5 pb-2">Session Parameters</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-end border-b border-dashed border-primary/10 pb-1">
                            <span class="text-xs font-bold text-primary/60 uppercase">Base Currency:</span>
                            <span class="text-xs font-black text-emerald-600 uppercase" id="rep_curr"><?php echo htmlspecialchars($selected_student['currency'] ?? 'PKR'); ?></span>
                        </div>
                        <div class="flex justify-between items-end border-b border-dashed border-primary/10 pb-1">
                            <span class="text-xs font-bold text-primary/60 uppercase">Session Duration:</span>
                            <span class="text-xs font-bold text-slate-600" id="rep_dur"><?php echo htmlspecialchars($selected_student['duration'] ?? '30 Mins'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div class="bg-primary/5 rounded-2xl p-4 text-center border border-primary/10">
                    <div class="text-[10px] font-black text-primary/60 uppercase tracking-widest mb-1">Total Classes</div>
                    <div class="text-2xl font-black text-primary" id="sum_total"><?php echo $total_classes; ?></div>
                </div>
                <div class="bg-emerald-50 rounded-2xl p-4 text-center border border-emerald-100">
                    <div class="text-[10px] font-black text-emerald-600/60 uppercase tracking-widest mb-1">Present</div>
                    <div class="text-2xl font-black text-emerald-600" id="sum_present"><?php echo $present_count; ?></div>
                </div>
                <div class="bg-red-50 rounded-2xl p-4 text-center border border-red-100">
                    <div class="text-[10px] font-black text-red-600/60 uppercase tracking-widest mb-1">Absent</div>
                    <div class="text-2xl font-black text-red-600" id="sum_absent"><?php echo $absent_count; ?></div>
                </div>
                <div class="bg-amber-50 rounded-2xl p-4 text-center border border-amber-100">
                    <div class="text-[10px] font-black text-amber-600/60 uppercase tracking-widest mb-1">Leaves</div>
                    <div class="text-2xl font-black text-amber-600" id="sum_leaves"><?php echo $leave_count; ?></div>
                </div>
            </div>

            <!-- Detail Table -->
            <h3 class="text-[10px] font-black uppercase text-primary/40 mb-4 tracking-widest border-b border-primary/5 pb-2">Session Chronology</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
                            <th class="p-3">Date</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">Wait</th>
                            <th class="p-3 text-center">Dur.</th>
                            <th class="p-3">Lesson</th>
                            <th class="p-3">Homework</th>
                            <th class="p-3">Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="report_tbody" class="divide-y divide-primary/5 text-primary font-medium">
                        <?php if (empty($student_attendance_logs)): ?>
                            <tr>
                                <td colspan="7" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No attendance logs registered for this student yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($student_attendance_logs as $log): 
                                $st = ucfirst($log['status'] ?? 'Present');
                                $st_bg = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                if ($st === 'Absent') $st_bg = 'bg-rose-50 text-rose-800 border-rose-200';
                                elseif ($st === 'Late') $st_bg = 'bg-blue-50 text-blue-800 border-blue-200';
                                elseif ($st === 'Leave' || strpos(strtolower($st), 'leave') !== false) $st_bg = 'bg-amber-50 text-amber-800 border-amber-200';
                            ?>
                                <tr class="hover:bg-primary/5 transition-all">
                                    <td class="p-3 font-mono font-bold"><?php echo date('d M Y', strtotime($log['date'])); ?></td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 text-[9px] font-black rounded-full uppercase border <?php echo $st_bg; ?>">
                                            <?php echo htmlspecialchars($st); ?>
                                        </span>
                                    </td>
                                    <td class="p-3 text-center font-mono text-primary/70"><?php echo htmlspecialchars($log['waited'] ?? '0 Min'); ?></td>
                                    <td class="p-3 text-center font-mono text-primary/70"><?php echo htmlspecialchars($log['duration'] ?? '30 Min'); ?></td>
                                    <td class="p-3"><?php echo htmlspecialchars($log['lesson'] ?? '-'); ?></td>
                                    <td class="p-3"><?php echo htmlspecialchars($log['homework'] ?? '-'); ?></td>
                                    <td class="p-3 italic text-primary/80"><?php echo htmlspecialchars($log['remarks'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer Note -->
            <div class="mt-8 text-center border-t border-primary/10 pt-6">
                <p class="text-[10px] font-bold uppercase tracking-widest text-primary/40">System Generated Report. No Signature Required.</p>
                <div class="text-[9px] text-slate-400 font-mono mt-1" id="rep_timestamp">GENERATED: <?php echo date('Y-m-d H:i:s'); ?> UTC</div>
            </div>
        </div>
    </div>
    <?php elseif ($selected_student_id): ?>
        <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-12 text-center">
          <i data-lucide="user-x" class="w-12 h-12 text-primary/30 mx-auto mb-4"></i>
          <p class="text-sm font-black text-primary">Student Not Found</p>
          <p class="text-xs text-primary/60 mt-1">This student is not assigned to you or doesn't exist.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-12 text-center">
          <i data-lucide="eye" class="w-12 h-12 text-primary/30 mx-auto mb-4"></i>
          <p class="text-sm font-black text-primary">Select a Seeker to Load Logs</p>
          <p class="text-xs text-primary/60 mt-1">Select one of your assigned students from the dropdown above to load their attendance ledger.</p>
        </div>
    <?php endif; ?>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
