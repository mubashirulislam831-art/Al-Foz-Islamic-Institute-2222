<?php
/**
 * Al Foz Islamic Institute - Today's Attendance Desk (Teacher)
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../auth/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';
require_once __DIR__ . '/../../includes/teachers_data.php';
require_once __DIR__ . '/../../includes/teacher_attendance_functions.php';

// Strictly require Teacher, Admin, or Super Admin roles
require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_name = $_SESSION['name'] ?? 'Faculty Member';
$teacher_email = $_SESSION['email'] ?? 'teacher@alfoz.org';

$today_date = date('Y-m-d');
$today_day = strtolower(date('l'));

// Fetch students assigned to this teacher
$all_students = get_all_students() ?: [];
$my_students = array_filter($all_students, function($s) use ($teacher_name) {
    return (isset($s['teacher_name']) && $s['teacher_name'] === $teacher_name);
});

// Post action handling to mark attendance
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_attendance') {
    $student_id = $_POST['student_id'] ?? '';
    $status = $_POST['status'] ?? 'Present';
    $lesson = $_POST['lesson'] ?? '-';
    $homework = $_POST['homework'] ?? '-';
    $remarks = $_POST['remarks'] ?? '-';
    $waited = $_POST['waited'] ?? '0';
    $duration = $_POST['duration'] ?? '30';
    $notes_text = "Lesson: $lesson | Homework: $homework | Remarks: $remarks | Waited: {$waited} Min | Duration: {$duration} Min";

    // Validate student exists
    $student_found = null;
    foreach ($my_students as $s) {
        $sid = $s['roll_no'] ?? $s['student_id'] ?? $s['id'] ?? '';
        if ($sid == $student_id) {
            $student_found = $s;
            break;
        }
    }

    if ($student_found) {
        // Check if record already exists for today
        $all_attendance = get_db_table('attendance') ?: [];
        $existing_id = null;
        foreach ($all_attendance as $att) {
            if ($att['student_id'] == $student_id && $att['date'] === $today_date) {
                $existing_id = $att['id'];
                break;
            }
        }

        $record = [
            'student_id' => $student_id,
            'date' => $today_date,
            'year' => intval(date('Y', strtotime($today_date))),
            'month' => intval(date('n', strtotime($today_date))),
            'status' => $status,
            'notes' => $notes_text,
            'lesson' => $lesson,
            'homework' => $homework,
            'remarks' => $remarks,
            'waited' => $waited . ' Min',
            'duration' => $duration . ' Min'
        ];

        if ($existing_id) {
            update_db_record('attendance', 'id', $existing_id, $record);
            $msg = 'updated';
        } else {
            insert_db_record('attendance', $record);
            mark_teacher_present_auto($teacher_email);
            $msg = 'saved';
        }

        // Also update the student's current attendance status in the student table
        $update_fields = ['attendance_status' => $status];
        update_db_record('students', 'id', $student_found['id'], $update_fields);

        // Refresh the students list to reflect changes
        $all_students = get_all_students() ?: [];
        $my_students = array_filter($all_students, function($s) use ($teacher_name) {
            return (isset($s['teacher_name']) && $s['teacher_name'] === $teacher_name);
        });
    } else {
        $msg = 'error';
    }
}

// Fetch all attendance logs to see what's marked today
$all_attendance_logs = get_db_table('attendance') ?: [];
$today_logs = array_filter($all_attendance_logs, function($a) use ($today_date) {
    return isset($a['date']) && $a['date'] === $today_date;
});

// Helper to find today's logged status for a student
function get_student_today_status($student_id, $today_logs) {
    foreach ($today_logs as $log) {
        if ($log['student_id'] == $student_id) {
            return $log;
        }
    }
    return null;
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
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">ROLL CALL DESK</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Evaluate Tajweed Progress, Daily Sabaq, and Homework</p>
      </div>
      <div class="bg-white border border-primary/10 px-4 py-2 rounded-xl text-xs font-bold text-primary flex items-center gap-2 shadow-sm">
        <i data-lucide="calendar" class="w-4 h-4 text-primary/60"></i> <?php echo date('d M Y, l'); ?>
      </div>
    </div>

    <?php if ($msg === 'saved'): ?>
      <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2">
        <i data-lucide="check-circle-2" class="w-4.5 h-4.5 text-emerald-600"></i>
        <span>Attendance log saved successfully for the student!</span>
      </div>
    <?php elseif ($msg === 'updated'): ?>
      <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl text-xs font-bold flex items-center gap-2">
        <i data-lucide="info" class="w-4.5 h-4.5 text-blue-600"></i>
        <span>Attendance log updated successfully!</span>
      </div>
    <?php elseif ($msg === 'error'): ?>
      <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold flex items-center gap-2">
        <i data-lucide="alert-triangle" class="w-4.5 h-4.5 text-rose-600"></i>
        <span>An error occurred. Student record was not found or is invalid.</span>
      </div>
    <?php endif; ?>

    <!-- Seeker Cards Grid -->
    <div class="space-y-6">
      <?php 
      $has_classes_today = false;
      if (!empty($my_students)): 
        foreach ($my_students as $student):
          $is_enabled = isset($student[$today_day . '_enabled']) && $student[$today_day . '_enabled'];
          if (!$is_enabled) continue; // Only show students who have class scheduled today
          
          $has_classes_today = true;
          $student_id = $student['id'];
          $today_log = get_student_today_status($student_id, $today_logs);
          $status_val = $today_log ? $today_log['status'] : ($student['attendance_status'] ?? 'Pending');
          $initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $student['name'])));
          
          // Parse dynamic notes from today's log if marked
          $saved_lesson = $today_log['lesson'] ?? '';
          $saved_homework = $today_log['homework'] ?? '';
          $saved_remarks = $today_log['remarks'] ?? '';
          $saved_waited = str_replace(' Min', '', $today_log['waited'] ?? '0');
          $saved_duration = str_replace(' Min', '', $today_log['duration'] ?? '30');
      ?>
          <div class="bg-white rounded-3xl border border-primary/10 shadow-sm overflow-hidden">
            <!-- Card Header -->
            <div class="p-5 border-b border-primary/5 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-bold text-lg border-2 border-white shadow-sm">
                  <?php echo htmlspecialchars($initials); ?>
                </div>
                <div>
                  <h3 class="font-black text-primary text-base flex items-center gap-2">
                    <?php echo htmlspecialchars($student['name']); ?>
                    <span class="text-[10px] font-bold bg-primary/10 text-primary px-2.5 py-0.5 rounded-full uppercase tracking-wider"><?php echo htmlspecialchars($student_id); ?></span>
                  </h3>
                  <p class="text-xs font-medium text-primary/60 mt-0.5"><?php echo htmlspecialchars($student['course'] ?? 'Islamic Studies'); ?></p>
                </div>
              </div>
              <div class="flex flex-wrap items-center gap-4 text-xs font-bold">
                <div class="bg-white border border-primary/10 px-3 py-1.5 rounded-xl text-primary shadow-sm flex items-center gap-1.5">
                  <i data-lucide="clock" class="w-3.5 h-3.5 text-primary/50"></i> <?php echo htmlspecialchars($student[$today_day . '_time'] ?? '12:00 PM'); ?>
                </div>
                <div class="bg-white border border-primary/10 px-3 py-1.5 rounded-xl text-emerald-600 shadow-sm flex items-center gap-1.5">
                  <i data-lucide="timer" class="w-3.5 h-3.5 text-emerald-500"></i> <?php echo htmlspecialchars($student['duration'] ?? '30 Mins'); ?>
                </div>
                <?php if ($today_log): ?>
                  <span class="bg-emerald-500/10 border border-emerald-400/20 text-emerald-800 rounded-xl px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Marked Today
                  </span>
                <?php else: ?>
                  <span class="bg-amber-500/10 border border-amber-400/20 text-amber-800 rounded-xl px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                  </span>
                <?php endif; ?>
              </div>
            </div>

            <!-- Card Body / Form -->
            <form method="POST" action="" class="p-5 grid grid-cols-1 lg:grid-cols-12 gap-6">
              <input type="hidden" name="action" value="save_attendance">
              <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">

              <!-- Status & Parameters (Left Col) -->
              <div class="lg:col-span-4 space-y-4">
                <div>
                  <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Class Status</label>
                  <select name="status" class="w-full px-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all">
                    <option value="Present" <?php echo $status_val === 'Present' ? 'selected' : ''; ?>>Present</option>
                    <option value="Absent" <?php echo $status_val === 'Absent' ? 'selected' : ''; ?>>Absent</option>
                    <option value="Late" <?php echo $status_val === 'Late' ? 'selected' : ''; ?>>Late</option>
                    <option value="Leave" <?php echo ($status_val === 'Leave' || strpos($status_val, 'Leave') !== false) ? 'selected' : ''; ?>>Excused Leave</option>
                    <option value="Makeup" <?php echo $status_val === 'Makeup' ? 'selected' : ''; ?>>Makeup Class</option>
                  </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Wait (Min)</label>
                    <input type="number" name="waited" min="0" value="<?php echo htmlspecialchars($saved_waited ?: '0'); ?>" class="w-full px-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all">
                  </div>
                  <div>
                    <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Dur (Min)</label>
                    <input type="number" name="duration" min="0" value="<?php echo htmlspecialchars($saved_duration ?: '30'); ?>" class="w-full px-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all">
                  </div>
                </div>
              </div>

              <!-- Lesson Details & Remarks (Right Col) -->
              <div class="lg:col-span-8 flex flex-col justify-between">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Topic / Lesson Covered</label>
                    <input type="text" name="lesson" value="<?php echo htmlspecialchars($saved_lesson ?: 'Quran Reading & Pronunciation'); ?>" placeholder="e.g. Para 1, Surah Baqarah v1-10" class="w-full px-4 py-2.5 bg-transparent border border-primary/10 rounded-xl text-xs font-medium text-primary focus:outline-none focus:border-primary/30 transition-all">
                  </div>
                  <div>
                    <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Homework Assigned</label>
                    <input type="text" name="homework" value="<?php echo htmlspecialchars($saved_homework ?: 'Revise verses 1-10'); ?>" placeholder="e.g. Memorize lesson details" class="w-full px-4 py-2.5 bg-transparent border border-primary/10 rounded-xl text-xs font-medium text-primary focus:outline-none focus:border-primary/30 transition-all">
                  </div>
                </div>

                <div class="mb-4">
                  <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Remarks / Instructor Notes</label>
                  <textarea name="remarks" rows="2" placeholder="e.g. Excellent recitation. Paid great attention to Tajweed rules." class="w-full px-4 py-2.5 bg-transparent border border-primary/10 rounded-xl text-xs font-medium text-primary focus:outline-none focus:border-primary/30 transition-all resize-none"><?php echo htmlspecialchars($saved_remarks); ?></textarea>
                </div>

                <div class="flex justify-end pt-4 border-t border-primary/5">
                  <button type="submit" class="bg-primary hover:bg-opacity-95 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> <?php echo $today_log ? 'Update Attendance Log' : 'Submit & Save Class'; ?>
                  </button>
                </div>
              </div>
            </form>
          </div>
      <?php 
        endforeach; 
      endif;

      if (!$has_classes_today): 
      ?>
        <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-12 text-center">
          <i data-lucide="calendar-x" class="w-12 h-12 text-primary/30 mx-auto mb-4"></i>
          <p class="text-sm font-black text-primary">No Scheduled Classes Today</p>
          <p class="text-xs text-primary/60 mt-1">You do not have any students scheduled for classes today (<?php echo ucfirst($today_day); ?>).</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
