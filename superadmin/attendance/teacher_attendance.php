<?php
/**
 * Al Foz Islamic Institute - Teacher Attendance Logs Control
 * Premium Full-CRUD database-linked logs with filters, stats, and real-time operations.
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/teachers_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

// Handle Post Actions for CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $record = [
            'employee_id' => $_POST['employee_id'] ?? '',
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'check_in' => $_POST['check_in'] ?? '09:00 AM',
            'check_out' => $_POST['check_out'] ?? '05:00 PM',
            'hours' => $_POST['hours'] ?? '8.0',
            'status' => $_POST['status'] ?? 'Present',
            'remarks' => $_POST['remarks'] ?? '-'
        ];
        insert_db_record('teacher_attendance', $record);
        header('Location: teacher_attendance.php?msg=added');
        exit;
    }
    
    if ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $record = [
            'employee_id' => $_POST['employee_id'] ?? '',
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'check_in' => $_POST['check_in'] ?? '09:00 AM',
            'check_out' => $_POST['check_out'] ?? '05:00 PM',
            'hours' => $_POST['hours'] ?? '8.0',
            'status' => $_POST['status'] ?? 'Present',
            'remarks' => $_POST['remarks'] ?? '-'
        ];
        update_db_record('teacher_attendance', 'id', $id, $record);
        header('Location: teacher_attendance.php?msg=updated');
        exit;
    }
    
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        delete_db_record('teacher_attendance', 'id', $id);
        header('Location: teacher_attendance.php?msg=deleted');
        exit;
    }
}

// Fetch Teachers and Attendance
$teachers = get_all_teachers();
if (empty($teachers)) {
    $teachers = [
        [
            'id' => 1,
            'employee_id' => 'EMP-1001',
            'name' => 'Sumera Tabassum',
            'specialization' => 'Tajweed & Quran Hifz',
            'whatsapp' => '+923015551234',
            'email' => 'sumera@alfoz.org',
            'status' => 'Permanent'
        ],
        [
            'id' => 2,
            'employee_id' => 'EMP-1002',
            'name' => 'Hafiz Ahmed',
            'specialization' => 'Islamic Studies & Arabic',
            'whatsapp' => '+923027771234',
            'email' => 'ahmed@alfoz.org',
            'status' => 'Permanent'
        ],
        [
            'id' => 3,
            'employee_id' => 'EMP-1003',
            'name' => 'Ustadha Sara',
            'specialization' => 'Arabic Linguistics',
            'whatsapp' => '+923038881234',
            'email' => 'sara@alfoz.org',
            'status' => 'Visiting'
        ]
    ];
}

$teacher_attendance = get_db_table('teacher_attendance') ?: [];
if (empty($teacher_attendance)) {
    $teacher_attendance = [
        [
            'id' => 1,
            'employee_id' => 'EMP-1001',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'check_in' => '08:55 AM',
            'check_out' => '05:02 PM',
            'hours' => '8.1',
            'status' => 'Present',
            'remarks' => 'On time, active classes'
        ],
        [
            'id' => 2,
            'employee_id' => 'EMP-1002',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'check_in' => '09:12 AM',
            'check_out' => '05:00 PM',
            'hours' => '7.8',
            'status' => 'Late',
            'remarks' => 'Traffic delay'
        ],
        [
            'id' => 3,
            'employee_id' => 'EMP-1003',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'check_in' => '10:00 AM',
            'check_out' => '02:00 PM',
            'hours' => '4.0',
            'status' => 'Present',
            'remarks' => 'Part-time hours completed'
        ],
        [
            'id' => 4,
            'employee_id' => 'EMP-1001',
            'date' => date('Y-m-d', strtotime('-2 days')),
            'check_in' => '-',
            'check_out' => '-',
            'hours' => '0.0',
            'status' => 'Leave',
            'remarks' => 'Approved personal leave'
        ]
    ];
}

// Search and Filter Handling (PHP side)
$filter_teacher = $_GET['employee_id'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_date = $_GET['date'] ?? '';

$filtered_records = [];
foreach ($teacher_attendance as $rec) {
    if ($filter_teacher && $rec['employee_id'] !== $filter_teacher) continue;
    if ($filter_status && $rec['status'] !== $filter_status) continue;
    if ($filter_date && $rec['date'] !== $filter_date) continue;
    $filtered_records[] = $rec;
}

// Compute Statistics on filtered or total logs
$total_days = count($filtered_records);
$presents = 0;
$lates = 0;
$leaves = 0;
$total_hours = 0.0;
foreach ($filtered_records as $rec) {
    if ($rec['status'] === 'Present') $presents++;
    elseif ($rec['status'] === 'Late') $lates++;
    elseif ($rec['status'] === 'Leave') $leaves++;
    
    $total_hours += floatval($rec['hours'] ?? 0.0);
}
$presence_rate = $total_days > 0 ? round((($presents + $lates) / $total_days) * 100, 1) : 100;
$avg_hours = $total_days > 0 ? round($total_hours / $total_days, 1) : 0;

?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent font-['Poppins']">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Message Alerts -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-semibold animate-fade-in">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
            <span>
                <?php
                    if ($_GET['msg'] === 'added') echo 'Faculty attendance record created successfully.';
                    elseif ($_GET['msg'] === 'updated') echo 'Faculty attendance record updated successfully.';
                    elseif ($_GET['msg'] === 'deleted') echo 'Faculty attendance record deleted successfully.';
                ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Module Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8 bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-primary/5 rounded-xl flex items-center justify-center text-primary shrink-0 border border-primary/10">
            <i data-lucide="users" class="w-7 h-7"></i>
        </div>
        <div>
          <h1 class="text-2xl font-black text-primary tracking-tight">Faculty Attendance Logs</h1>
          <p class="text-xs text-primary/60 mt-1 font-medium">Browse, edit, delete, and log daily teacher check-ins</p>
        </div>
      </div>
      <div class="flex gap-3">
         <button onclick="openAddModal()" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(24,77,85,0.3)] flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Check-in
        </button>
      </div>
    </div>

    <!-- Filter Control Panel -->
    <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm mb-8">
        <form method="GET" action="teacher_attendance.php" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-2">Select Instructor</label>
                <select name="employee_id" class="w-full px-4 py-3 bg-white border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                    <option value="">All Teachers</option>
                    <?php foreach ($teachers as $t): ?>
                        <option value="<?php echo $t['employee_id']; ?>" <?php echo $filter_teacher === $t['employee_id'] ? 'selected' : ''; ?>>
                            <?php echo $t['name']; ?> (<?php echo $t['employee_id']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-2">Duty Status</label>
                <select name="status" class="w-full px-4 py-3 bg-white border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                    <option value="">All Statuses</option>
                    <option value="Present" <?php echo $filter_status === 'Present' ? 'selected' : ''; ?>>Present</option>
                    <option value="Late" <?php echo $filter_status === 'Late' ? 'selected' : ''; ?>>Late</option>
                    <option value="Leave" <?php echo $filter_status === 'Leave' ? 'selected' : ''; ?>>Leave</option>
                    <option value="Absent" <?php echo $filter_status === 'Absent' ? 'selected' : ''; ?>>Absent</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-2">Specific Date</label>
                <input type="date" name="date" class="w-full px-4 py-3 bg-white border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none" value="<?php echo $filter_date; ?>">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-grow bg-primary text-white py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all text-center">Filter</button>
                <a href="teacher_attendance.php" class="px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Metrics Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-primary/5 rounded-xl text-primary flex items-center justify-center shrink-0">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold tracking-widest text-primary/50 block">Logged Days</span>
                <span class="text-xl font-black text-primary block mt-0.5"><?php echo $total_days; ?></span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold tracking-widest text-primary/50 block">Attendance Rate</span>
                <span class="text-xl font-black text-emerald-600 block mt-0.5"><?php echo $presence_rate; ?>%</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl text-amber-600 flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold tracking-widest text-primary/50 block">Late Entries</span>
                <span class="text-xl font-black text-amber-600 block mt-0.5"><?php echo $lates; ?></span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 rounded-xl text-purple-600 flex items-center justify-center shrink-0">
                <i data-lucide="hourglass" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold tracking-widest text-primary/50 block">Avg Daily Hours</span>
                <span class="text-xl font-black text-purple-600 block mt-0.5"><?php echo $avg_hours; ?> Hrs</span>
            </div>
        </div>
    </div>

    <!-- Attendance Logs List -->
    <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="bg-primary/5 text-primary/50 uppercase font-black text-[10px] tracking-widest border-b border-primary/10">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Faculty Profile</th>
                        <th class="px-6 py-4">Specialization & Status</th>
                        <th class="px-6 py-4">Duty Status</th>
                        <th class="px-6 py-4">Check-In / Out Times</th>
                        <th class="px-6 py-4">Hours Logged</th>
                        <th class="px-6 py-4">Remarks</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    <?php if (empty($filtered_records)): ?>
                        <tr>
                            <td colspan="8" class="text-center p-12 text-primary/40 font-bold">
                                <i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                                No faculty attendance logs match current filters.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($filtered_records as $rec):
                        // Find matching teacher
                        $matched_teacher = null;
                        foreach ($teachers as $t) {
                            if ($t['employee_id'] === $rec['employee_id']) {
                                $matched_teacher = $t;
                                break;
                            }
                        }
                        $teacher_name = $matched_teacher ? $matched_teacher['name'] : 'Unknown Instructor';
                        $teacher_pic = 'https://ui-avatars.com/api/?name=' . urlencode($teacher_name) . '&background=184D55&color=F7FAFF&size=100';
                        
                        // Status styling
                        $status_class = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                        if ($rec['status'] === 'Present') $status_class = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                        elseif ($rec['status'] === 'Late') $status_class = 'bg-amber-50 text-amber-700 border-amber-200';
                        elseif ($rec['status'] === 'Leave') $status_class = 'bg-purple-50 text-purple-700 border-purple-200';
                        elseif ($rec['status'] === 'Absent') $status_class = 'bg-red-50 text-red-700 border-red-100';
                    ?>
                    <tr class="hover:bg-primary/[0.01] transition-colors">
                        <td class="px-6 py-4 font-extrabold text-primary"><?php echo date('d M Y', strtotime($rec['date'])); ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden border border-primary/10 shrink-0">
                                    <img src="<?php echo $teacher_pic; ?>" alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-primary text-sm"><?php echo $teacher_name; ?></p>
                                    <p class="text-[9px] text-primary/50 font-black uppercase tracking-wider mt-0.5"><?php echo $rec['employee_id']; ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-primary"><?php echo $matched_teacher ? $matched_teacher['specialization'] : 'N/A'; ?></p>
                            <p class="text-[10px] text-primary/60 font-medium mt-0.5">Classification: <?php echo $matched_teacher ? $matched_teacher['status'] : 'Visiting'; ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 border <?php echo $status_class; ?> rounded-full text-[9px] font-black uppercase tracking-wider">
                                <?php echo $rec['status']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-primary"><span class="text-[9px] uppercase tracking-wide text-primary/50 mr-1 font-black">IN:</span><?php echo $rec['check_in']; ?></p>
                            <p class="text-[10px] text-primary/60 font-medium mt-0.5"><span class="text-[9px] uppercase tracking-wide text-primary/50 mr-1 font-black">OUT:</span><?php echo $rec['check_out']; ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 bg-primary/5 border border-primary/10 rounded-lg font-black text-primary text-xs">
                                <?php echo $rec['hours']; ?> Hrs
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate font-medium text-primary/70" title="<?php echo $rec['remarks']; ?>">
                            <?php echo $rec['remarks']; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($rec)); ?>)" class="p-2 bg-primary/5 text-primary hover:bg-primary hover:text-white rounded-lg transition-colors" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <form method="POST" action="teacher_attendance.php" onsubmit="return confirm('Are you sure you want to delete this log?');" class="inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $rec['id']; ?>">
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors" title="Delete">
                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
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

<!-- Modal: Add Attendance Check-in -->
<div id="addModal" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl border border-primary/10 shadow-2xl w-full max-w-lg overflow-hidden animate-scale-up">
        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i data-lucide="plus-circle" class="w-6 h-6"></i>
                <span class="font-black tracking-tight text-lg">Add Faculty Check-in</span>
            </div>
            <button onclick="closeAddModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>
        <form method="POST" action="teacher_attendance.php" class="p-6 space-y-4">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Select Instructor</label>
                    <select name="employee_id" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <?php foreach ($teachers as $t): ?>
                            <option value="<?php echo $t['employee_id']; ?>"><?php echo $t['name']; ?> (<?php echo $t['employee_id']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Date</label>
                    <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Check-In Time</label>
                    <input type="text" name="check_in" value="09:00 AM" placeholder="e.g. 09:00 AM" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Check-Out Time</label>
                    <input type="text" name="check_out" value="05:00 PM" placeholder="e.g. 05:00 PM" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Duty Status</label>
                    <select name="status" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Leave">Leave</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Hours Worked</label>
                    <input type="number" step="0.1" name="hours" value="8.0" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Remarks & Log Notes</label>
                <textarea name="remarks" placeholder="Provide attendance exceptions or duties notes..." class="w-full p-4 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none h-24"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAddModal()" class="px-5 py-3 border border-primary/10 hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-bold uppercase transition-all">Cancel</button>
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all">Save Check-in</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Attendance Record -->
<div id="editModal" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl border border-primary/10 shadow-2xl w-full max-w-lg overflow-hidden animate-scale-up">
        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i data-lucide="edit-3" class="w-6 h-6"></i>
                <span class="font-black tracking-tight text-lg">Edit Faculty Check-in</span>
            </div>
            <button onclick="closeEditModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>
        <form method="POST" action="teacher_attendance.php" class="p-6 space-y-4">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Select Instructor</label>
                    <select name="employee_id" id="edit_employee_id" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <?php foreach ($teachers as $t): ?>
                            <option value="<?php echo $t['employee_id']; ?>"><?php echo $t['name']; ?> (<?php echo $t['employee_id']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Date</label>
                    <input type="date" name="date" id="edit_date" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Check-In Time</label>
                    <input type="text" name="check_in" id="edit_check_in" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Check-Out Time</label>
                    <input type="text" name="check_out" id="edit_check_out" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Duty Status</label>
                    <select name="status" id="edit_status" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Leave">Leave</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Hours Worked</label>
                    <input type="number" step="0.1" name="hours" id="edit_hours" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Remarks & Log Notes</label>
                <textarea name="remarks" id="edit_remarks" class="w-full p-4 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none h-24"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 border border-primary/10 hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-bold uppercase transition-all">Cancel</button>
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all">Update Check-in</button>
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
        document.getElementById('edit_employee_id').value = record.employee_id;
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
</script>
