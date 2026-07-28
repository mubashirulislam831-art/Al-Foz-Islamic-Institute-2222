<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Specific Attendance
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/teachers_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

// Active tab logic based on current file name
$teacher_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$all_teachers = get_all_teachers();
$teacher = null;
foreach ($all_teachers as $t) {
    if ($t['id'] == $teacher_id) {
        $teacher = $t;
        break;
    }
}
if (!$teacher && !empty($all_teachers)) {
    $teacher = $all_teachers[0];
    $teacher_id = $teacher['id'];
}

if (!$teacher) {
    echo "<div class='bg-red-50 p-6 rounded-2xl text-red-700 font-bold'>Error: Teacher not found.</div>";
    return;
}

// Handle Post Actions for CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $record = [
            'employee_id' => $teacher['employee_id'],
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'check_in' => $_POST['check_in'] ?? '09:00 AM',
            'check_out' => $_POST['check_out'] ?? '05:00 PM',
            'hours' => $_POST['hours'] ?? '8.0',
            'status' => $_POST['status'] ?? 'Present',
            'remarks' => $_POST['remarks'] ?? '-'
        ];
        insert_db_record('teacher_attendance', $record);
        header('Location: teacher_attendance.php?id=' . $teacher_id . '&msg=added');
        exit;
    }
    
    if ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $record = [
            'employee_id' => $teacher['employee_id'],
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'check_in' => $_POST['check_in'] ?? '09:00 AM',
            'check_out' => $_POST['check_out'] ?? '05:00 PM',
            'hours' => $_POST['hours'] ?? '8.0',
            'status' => $_POST['status'] ?? 'Present',
            'remarks' => $_POST['remarks'] ?? '-'
        ];
        update_db_record('teacher_attendance', 'id', $id, $record);
        header('Location: teacher_attendance.php?id=' . $teacher_id . '&msg=updated');
        exit;
    }
    
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        delete_db_record('teacher_attendance', 'id', $id);
        header('Location: teacher_attendance.php?id=' . $teacher_id . '&msg=deleted');
        exit;
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Teacher Header and Navigation -->
    <?php require_once __DIR__ . '/_teacher_header.php'; ?>

    <?php
    // Load all attendance records for this specific teacher
    $all_teacher_attendance = get_db_table('teacher_attendance') ?: [];
    $this_teacher_attendance = [];
    foreach ($all_teacher_attendance as $att) {
        if (isset($att['employee_id']) && $att['employee_id'] === $teacher['employee_id']) {
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

    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs font-bold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Attendance records successfully updated on live database!</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-800/60 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <!-- Attendance Stats -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm">
                <span class="text-[9px] text-primary/60 font-black uppercase tracking-wider">Attendance Percentage</span>
                <div class="text-3xl font-black text-emerald-600 mt-1"><?php echo $attendance_pct; ?>%</div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-emerald-500 h-full" style="width: <?php echo $attendance_pct; ?>%;"></div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm">
                <span class="text-[9px] text-primary/60 font-black uppercase tracking-wider">Leaves Taken (Year)</span>
                <div class="text-3xl font-black text-amber-600 mt-1"><?php echo $leaves_count; ?> Days</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm">
                <span class="text-[9px] text-primary/60 font-black uppercase tracking-wider">Late Arrivals</span>
                <div class="text-3xl font-black text-rose-600 mt-1"><?php echo $late_days; ?></div>
            </div>
        </div>

        <!-- Attendance Calendar/Log -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-primary/10 flex justify-between items-center bg-primary/5">
                    <h3 class="font-bold text-primary text-xs uppercase tracking-wider">Recent Attendance Logs</h3>
                    <button onclick="openAddModal()" class="bg-primary text-white text-[10px] font-black uppercase tracking-wider px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 hover:bg-primary/95 shadow-sm transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Mark Attendance
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                          <tr class="border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
                            <th class="p-4">Date</th>
                            <th class="p-4">Login</th>
                            <th class="p-4">Logout</th>
                            <th class="p-4">Work Duration</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Remarks</th>
                            <th class="p-4 text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/5 text-primary/80">
                            <?php if (empty($this_teacher_attendance)): ?>
                            <tr>
                                <td colspan="7" class="p-8 text-center text-primary/50 font-semibold">No attendance records logged for this teacher.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($this_teacher_attendance as $att): ?>
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="p-4 font-bold"><?php echo htmlspecialchars(date('d M Y', strtotime($att['date'] ?? ''))); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($att['check_in'] ?? 'N/A'); ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($att['check_out'] ?? 'N/A'); ?></td>
                                    <td class="p-4 font-semibold"><?php echo htmlspecialchars($att['hours'] ?? '0.0'); ?>h</td>
                                    <td class="p-4">
                                        <?php echo render_status_badge($att['status'] ?? 'Present'); ?>
                                    </td>
                                    <td class="p-4 font-medium text-primary/60">
                                        <?php echo htmlspecialchars($att['remarks'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($att)); ?>)" class="p-1.5 hover:bg-primary/5 text-primary rounded-lg transition-all" title="Edit Log">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <button onclick="openDeleteModal(<?php echo $att['id']; ?>)" class="p-1.5 hover:bg-red-50 text-red-600 rounded-lg transition-all" title="Delete Log">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
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
                <span class="font-black tracking-tight text-sm uppercase">Mark Faculty Attendance</span>
            </div>
            <button onclick="closeAddModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="teacher_attendance.php?id=<?php echo $teacher_id; ?>" class="p-6 space-y-4 text-xs font-semibold">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Date</label>
                    <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" class="w-full">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Duty Status</label>
                    <select name="status" required class="w-full">
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Leave">Leave</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Check-In Time</label>
                    <input type="text" name="check_in" value="09:00 AM" placeholder="09:00 AM" required class="w-full">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Check-Out Time</label>
                    <input type="text" name="check_out" value="05:00 PM" placeholder="05:00 PM" required class="w-full">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Hours Worked</label>
                <input type="number" step="0.1" name="hours" value="8.0" required class="w-full">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Remarks & Log Notes</label>
                <textarea name="remarks" placeholder="Notes..." class="w-full h-20 p-3 text-xs"></textarea>
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
                <span class="font-black tracking-tight text-sm uppercase">Edit Faculty Attendance</span>
            </div>
            <button onclick="closeEditModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="teacher_attendance.php?id=<?php echo $teacher_id; ?>" class="p-6 space-y-4 text-xs font-semibold">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Date</label>
                    <input type="date" name="date" id="edit_date" required class="w-full">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Duty Status</label>
                    <select name="status" id="edit_status" required class="w-full">
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Leave">Leave</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Check-In Time</label>
                    <input type="text" name="check_in" id="edit_check_in" required class="w-full">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Check-Out Time</label>
                    <input type="text" name="check_out" id="edit_check_out" required class="w-full">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Hours Worked</label>
                <input type="number" step="0.1" name="hours" id="edit_hours" required class="w-full">
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
        <form method="POST" action="teacher_attendance.php?id=<?php echo $teacher_id; ?>" class="p-6 space-y-4">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" id="delete_id">
            <p class="text-xs text-primary font-bold">Are you absolutely sure you want to permanently delete this attendance record from the live system? This cannot be undone.</p>
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
      document.getElementById('edit_check_in').value = record.check_in || '';
      document.getElementById('edit_check_out').value = record.check_out || '';
      document.getElementById('edit_status').value = record.status;
      document.getElementById('edit_hours').value = record.hours || '8.0';
      document.getElementById('edit_remarks').value = record.remarks || '';
      
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

