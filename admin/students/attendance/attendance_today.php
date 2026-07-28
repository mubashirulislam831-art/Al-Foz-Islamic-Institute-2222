<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Today's Attendance
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/students_data.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);

$id = isset($_GET['id']) ? intval($_GET['id']) : 101;
$student = get_student_by_id($id);

if (!$student) {
    header("Location: ../students.php");
    exit;
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance_status = sanitize_input($_POST['attendance_status']);
    
    // Update student attendance status in Session
    $_SESSION['students'][$id]['attendance_status'] = $attendance_status;
    
    // Adjust stats
    if ($attendance_status === 'Present') {
        $_SESSION['students'][$id]['attendance']['present'] += 1;
    } elseif ($attendance_status === 'Absent') {
        $_SESSION['students'][$id]['attendance']['absent'] += 1;
    } elseif ($attendance_status === 'Leave') {
        $_SESSION['students'][$id]['attendance']['leave'] += 1;
    }
    
    // Recalculate percentage
    $total = $_SESSION['students'][$id]['attendance']['present'] + $_SESSION['students'][$id]['attendance']['absent'] + $_SESSION['students'][$id]['attendance']['leave'];
    if ($total > 0) {
        $_SESSION['students'][$id]['attendance']['percentage'] = round(($_SESSION['students'][$id]['attendance']['present'] / $total) * 100);
    }
    
    // Log timeline
    $_SESSION['students'][$id]['timeline'][] = [
        'date' => date('Y-m-d'),
        'type' => 'Attendance History',
        'title' => "Marked Today's Attendance: " . $attendance_status,
        'desc' => "Marked " . $attendance_status . " for class today."
    ];
    
    $success = true;
    $student = get_student_by_id($id); // refresh data
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <div class="mb-8 mt-4">
      <a href="../student_profile.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Profile
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Mark Today's Attendance</h1>
      <p class="text-xs text-primary/60 mt-0.5">Mark daily attendance status and record virtual session check-ins for this seeker.</p>
    </div>

    <?php if ($success): ?>
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-4 rounded-xl mb-6 max-w-xl">
        ✓ Today's attendance has been successfully logged!
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 max-w-xl">
      <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-extrabold text-sm">
          AT
        </div>
        <div>
          <h3 class="font-bold text-primary"><?php echo htmlspecialchars($student['name']); ?></h3>
          <p class="text-[10px] text-slate-500 font-mono">Assigned Teacher: <?php echo htmlspecialchars($student['teacher_name']); ?> | Class: <?php echo htmlspecialchars($student['class_time']); ?></p>
        </div>
      </div>

      <form action="attendance_today.php?id=<?php echo $id; ?>" method="POST" class="space-y-6">
        <div>
          <label class="block text-[10px] font-bold uppercase tracking-wider text-primary mb-3">Class Day Attendance Status *</label>
          <div class="grid grid-cols-3 gap-3">
            <label class="border-2 border-slate-100 hover:border-primary/30 rounded-xl p-4 flex flex-col items-center gap-2 cursor-pointer transition-all bg-white text-center">
              <input type="radio" name="attendance_status" value="Present" <?php echo $student['attendance_status'] === 'Present' ? 'checked' : ''; ?> class="accent-primary" required>
              <span class="text-xs font-extrabold text-emerald-700">Present</span>
            </label>
            <label class="border-2 border-slate-100 hover:border-primary/30 rounded-xl p-4 flex flex-col items-center gap-2 cursor-pointer transition-all bg-white text-center">
              <input type="radio" name="attendance_status" value="Absent" <?php echo $student['attendance_status'] === 'Absent' ? 'checked' : ''; ?> class="accent-primary">
              <span class="text-xs font-extrabold text-rose-700">Absent</span>
            </label>
            <label class="border-2 border-slate-100 hover:border-primary/30 rounded-xl p-4 flex flex-col items-center gap-2 cursor-pointer transition-all bg-white text-center">
              <input type="radio" name="attendance_status" value="Leave" <?php echo $student['attendance_status'] === 'Leave' ? 'checked' : ''; ?> class="accent-primary">
              <span class="text-xs font-extrabold text-amber-700">Leave</span>
            </label>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
          <a href="../student_profile.php?id=<?php echo $id; ?>" class="bg-slate-100 hover:bg-slate-200 text-primary px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Cancel</a>
          <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:bg-opacity-95 transition-all">Log Attendance</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
