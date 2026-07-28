<?php
/**
 * Al Foz Islamic Institute - Student Specific Attendance Control (Live DB Linked)
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

require_role('Super Admin');

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$student = get_student_by_id($student_id);

if (!$student) {
    echo "<div class='bg-red-50 p-6 rounded-2xl text-red-700 font-bold'>Error: Student not found.</div>";
    return;
}

$initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $student['name'])));

// Handle Post Actions for CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $date_val = $_POST['date'] ?? date('Y-m-d');
        $status_val = $_POST['status'] ?? 'Present';
        
        // Map rich info to notes column
        $lesson = $_POST['lesson'] ?? '-';
        $homework = $_POST['homework'] ?? '-';
        $remarks = $_POST['remarks'] ?? '-';
        $notes_text = "Lesson: $lesson | Homework: $homework | Remarks: $remarks";
        
        $record = [
            'student_id' => $student['id'], // use actual ID for live-linking to profile
            'date' => $date_val,
            'year' => intval(date('Y', strtotime($date_val))),
            'month' => intval(date('n', strtotime($date_val))),
            'status' => $status_val,
            'notes' => $notes_text,
            'created_by' => $_SESSION['user_name'] ?? 'Super Admin'
        ];
        
        insert_db_record('attendance', $record);
        
        // Also sync the visual status to the student table
        $update_fields = ['attendance_status' => $status_val];
        update_db_record('students', 'id', $student['id'], $update_fields);
        
        header('Location: student_attendance.php?id=' . $student_id . '&msg=added');
        exit;
    }
    
    if ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $date_val = $_POST['date'] ?? date('Y-m-d');
        $status_val = $_POST['status'] ?? 'Present';
        
        $lesson = $_POST['lesson'] ?? '-';
        $homework = $_POST['homework'] ?? '-';
        $remarks = $_POST['remarks'] ?? '-';
        $notes_text = "Lesson: $lesson | Homework: $homework | Remarks: $remarks";
        
        $record = [
            'student_id' => $student['id'],
            'date' => $date_val,
            'year' => intval(date('Y', strtotime($date_val))),
            'month' => intval(date('n', strtotime($date_val))),
            'status' => $status_val,
            'notes' => $notes_text,
            'created_by' => $_SESSION['user_name'] ?? 'Super Admin'
        ];
        
        update_db_record('attendance', 'id', $id, $record);
        
        // Also sync the visual status to the student table
        $update_fields = ['attendance_status' => $status_val];
        update_db_record('students', 'id', $student['id'], $update_fields);
        
        header('Location: student_attendance.php?id=' . $student_id . '&msg=updated');
        exit;
    }
    
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        delete_db_record('attendance', 'id', $id);
        header('Location: student_attendance.php?id=' . $student_id . '&msg=deleted');
        exit;
    }
}

// Fetch all attendance logs for this student from DB
$all_attendance = get_db_table('attendance') ?: [];
$this_student_attendance = [];
foreach ($all_attendance as $att) {
    if (isset($att['student_id']) && ($att['student_id'] == $student['id'] || $att['student_id'] == $student['roll_no'])) {
        $this_student_attendance[] = $att;
    }
}

// Sort descending
usort($this_student_attendance, function($a, $b) {
    return strcmp($b['date'] ?? '', $a['date'] ?? '');
});

// Calculate metrics
$total_days = count($this_student_attendance);
$presents = 0;
$absents = 0;
$leaves = 0;
$lates = 0;

foreach ($this_student_attendance as $att) {
    $st = strtolower($att['status'] ?? '');
    if ($st === 'present') $presents++;
    elseif ($st === 'absent') $absents++;
    elseif ($st === 'leave') $leaves++;
    elseif ($st === 'late') $lates++;
}

$attendance_pct = ($total_days > 0) ? round((($presents + $lates) / $total_days) * 100, 1) : 100.0;
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition font-['Poppins']">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Header and Navigation -->
    <?php require_once __DIR__ . '/_student_header.php'; ?>

    <!-- Student Dossier Portals Box -->
    

    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs font-bold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Student attendance records successfully updated on live database!</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-800/60 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Attendance Log -->
        <div class="lg:col-span-2 bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Academic Attendance Logs</h3>
                <button onclick="openAddModal()" class="bg-primary text-white text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-xl flex items-center gap-1.5 hover:bg-primary/95 shadow-sm transition-all">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Log Attendance
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-primary/30 uppercase tracking-[0.2em] border-b border-primary/5">
                            <th class="pb-4">Date</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Notes & Lesson Details</th>
                            <th class="pb-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary/5 text-xs text-primary font-medium">
                        <?php if (empty($this_student_attendance)): ?>
                            <tr>
                                <td colspan="4" class="py-8 text-center text-primary/50 font-semibold">No attendance records logged for this student.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($this_student_attendance as $att): 
                                $status = ucfirst($att['status'] ?? 'Present');
                                $status_class = 'bg-emerald-50 text-emerald-700';
                                if ($status === 'Absent') $status_class = 'bg-rose-50 text-rose-700';
                                elseif ($status === 'Leave') $status_class = 'bg-amber-50 text-amber-700';
                                elseif ($status === 'Late') $status_class = 'bg-orange-50 text-orange-700';
                            ?>
                            <tr class="hover:bg-primary/5 transition-all">
                                <td class="py-4 font-black">
                                    <?php echo date('d M, Y', strtotime($att['date'])); ?>
                                </td>
                                <td class="py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase border <?php echo $status_class; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td class="py-4 text-primary/80">
                                    <?php echo htmlspecialchars($att['notes'] ?? '-'); ?>
                                </td>
                                <td class="py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($att)); ?>)" class="p-1.5 hover:bg-primary/5 text-primary rounded-lg transition-all" title="Edit Log">
                                            <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                                        </button>
                                        <button onclick="openDeleteModal(<?php echo $att['id']; ?>)" class="p-1.5 hover:bg-red-50 text-red-600 rounded-lg transition-all" title="Delete Log">
                                            <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Stats & Breakdown -->
        <div class="space-y-8">
            
            <!-- Summary Stats Card -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-8">Lifetime Metrics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-primary/5 rounded-2xl border border-primary/5 text-center">
                        <p class="text-[8px] font-black text-primary/40 uppercase mb-1">Total Classes</p>
                        <p class="text-xl font-black text-primary"><?php echo $total_days; ?></p>
                    </div>
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                        <p class="text-[8px] font-black text-emerald-600/40 uppercase mb-1">Attended</p>
                        <p class="text-xl font-black text-emerald-700"><?php echo $presents + $lates; ?></p>
                    </div>
                    <div class="p-4 bg-rose-50 rounded-2xl border border-rose-100 text-center">
                        <p class="text-[8px] font-black text-rose-600/40 uppercase mb-1">Missed</p>
                        <p class="text-xl font-black text-rose-700"><?php echo $absents; ?></p>
                    </div>
                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 text-center">
                        <p class="text-[8px] font-black text-amber-600/40 uppercase mb-1">Leaves</p>
                        <p class="text-xl font-black text-amber-700"><?php echo $leaves; ?></p>
                    </div>
                </div>
            </div>

            <!-- Action Card -->
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-6">Management Node</h3>
                <div class="space-y-3">
                    <button onclick="window.print()" class="w-full py-4 bg-white/10 hover:bg-white text-white hover:text-primary rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-3">
                        <i data-lucide="file-text" class="w-4 h-4"></i> Print Attendance Report
                    </button>
                </div>
            </div>

        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<!-- Modal: Add Attendance Record -->
<div id="addModal" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl border border-primary/10 shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-primary p-5 text-white flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5 text-secondary"></i>
                <span class="font-black tracking-tight text-sm uppercase">Log Student Attendance</span>
            </div>
            <button onclick="closeAddModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="student_attendance.php?id=<?php echo $student_id; ?>" class="p-6 space-y-4 text-xs font-semibold">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Date</label>
                    <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" class="w-full">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Status</label>
                    <select name="status" required class="w-full">
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Leave">Leave</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Lesson Topic</label>
                <input type="text" name="lesson" placeholder="e.g. Surah Al-Baqarah Ayah 1-10" required class="w-full">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Homework Assigned</label>
                <input type="text" name="homework" placeholder="e.g. Memorize lines 1-5" required class="w-full">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Remarks & Log Notes</label>
                <textarea name="remarks" placeholder="Provide details..." class="w-full h-20 p-3 text-xs"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 border border-primary/10 text-gray-700 rounded-xl hover:bg-gray-50 uppercase tracking-wider font-bold">Cancel</button>
                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl font-black uppercase tracking-widest hover:bg-primary/95">Save Log</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Attendance Record -->
<div id="editModal" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl border border-primary/10 shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-primary p-5 text-white flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i data-lucide="edit-3" class="w-5 h-5 text-secondary"></i>
                <span class="font-black tracking-tight text-sm uppercase">Edit Attendance Record</span>
            </div>
            <button onclick="closeEditModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="student_attendance.php?id=<?php echo $student_id; ?>" class="p-6 space-y-4 text-xs font-semibold">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Date</label>
                    <input type="date" name="date" id="edit_date" required class="w-full">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Status</label>
                    <select name="status" id="edit_status" required class="w-full">
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Leave">Leave</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Lesson Topic</label>
                <input type="text" name="lesson" id="edit_lesson" required class="w-full">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Homework Assigned</label>
                <input type="text" name="homework" id="edit_homework" required class="w-full">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Remarks & Log Notes</label>
                <textarea name="remarks" id="edit_remarks" class="w-full h-20 p-3 text-xs"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 border border-primary/10 text-gray-700 rounded-xl hover:bg-gray-50 uppercase tracking-wider font-bold">Cancel</button>
                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl font-black uppercase tracking-widest hover:bg-primary/95">Update Log</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Delete Confirmation -->
<div id="deleteModal" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl border border-primary/10 shadow-2xl w-full max-w-sm overflow-hidden">
        <div class="bg-red-600 p-5 text-white flex justify-between items-center">
            <span class="font-black tracking-tight text-sm uppercase">Delete Attendance Record</span>
            <button onclick="closeDeleteModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="student_attendance.php?id=<?php echo $student_id; ?>" class="p-6 space-y-4">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" id="delete_id">
            <p class="text-xs text-primary font-bold">Are you absolutely sure you want to permanently delete this student's attendance record from the live system? This cannot be undone.</p>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-primary/10 text-gray-700 rounded-xl hover:bg-gray-50 uppercase text-[10px] font-black">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-5 py-2.5 rounded-xl uppercase text-[10px] font-black tracking-widest hover:bg-red-700">Delete Permanently</button>
            </div>
        </form>
    </div>
</div>

<script>
  function openAddModal() {
      document.getElementById('addModal').classList.remove('hidden');
  }
  function closeAddModal() {
      document.getElementById('addModal').classList.add('hidden');
  }
  
  function openEditModal(record) {
      document.getElementById('edit_id').value = record.id;
      document.getElementById('edit_date').value = record.date;
      document.getElementById('edit_status').value = record.status;
      
      // Parse combined notes text: "Lesson: X | Homework: Y | Remarks: Z"
      var notes = record.notes || '';
      var lesson = '';
      var homework = '';
      var remarks = '';
      
      var lessonMatch = notes.match(/Lesson:\s*(.*?)\s*(?:\||$)/);
      var homeworkMatch = notes.match(/Homework:\s*(.*?)\s*(?:\||$)/);
      var remarksMatch = notes.match(/Remarks:\s*(.*?)\s*$/);
      
      if (lessonMatch) lesson = lessonMatch[1];
      if (homeworkMatch) homework = homeworkMatch[1];
      if (remarksMatch) remarks = remarksMatch[1];
      
      document.getElementById('edit_lesson').value = lesson || '-';
      document.getElementById('edit_homework').value = homework || '-';
      document.getElementById('edit_remarks').value = remarks || '-';
      
      document.getElementById('editModal').classList.remove('hidden');
  }
  function closeEditModal() {
      document.getElementById('editModal').classList.add('hidden');
  }

  function openDeleteModal(id) {
      document.getElementById('delete_id').value = id;
      document.getElementById('deleteModal').classList.remove('hidden');
  }
  function closeDeleteModal() {
      document.getElementById('deleteModal').classList.add('hidden');
  }

  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
