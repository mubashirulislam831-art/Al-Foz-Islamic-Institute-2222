<?php
/**
 * Al Foz Islamic Institute - Student Academic Attendance Control
 * Premium Full-CRUD database-linked logs with filters, stats, and real-time operations.
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

// Handle Post Actions for CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $date_val = $_POST['date'] ?? date('Y-m-d');
        $lesson = $_POST['lesson'] ?? '-';
        $homework = $_POST['homework'] ?? '-';
        $remarks = $_POST['remarks'] ?? '-';
        $waited = $_POST['waited'] ?? '0';
        $duration = $_POST['duration'] ?? '30';
        $notes_text = "Lesson: $lesson | Homework: $homework | Remarks: $remarks | Waited: {$waited} Min | Duration: {$duration} Min";

        $record = [
            'student_id' => $_POST['student_id'] ?? '',
            'date' => $date_val,
            'year' => intval(date('Y', strtotime($date_val))),
            'month' => intval(date('n', strtotime($date_val))),
            'status' => $_POST['status'] ?? 'Present',
            'notes' => $notes_text,
            'lesson' => $lesson,
            'homework' => $homework,
            'remarks' => $remarks,
            'waited' => $waited . ' Min',
            'duration' => $duration . ' Min'
        ];
        insert_db_record('attendance', $record);
        
        // Update student's daily attendance status as well
        $students_db = get_all_students();
        foreach ($students_db as $s) {
            if ($s['roll_no'] === $record['student_id'] || $s['id'] == $record['student_id']) {
                $update_fields = ['attendance_status' => $record['status']];
                update_db_record('students', 'id', $s['id'], $update_fields);
                break;
            }
        }
        
        header('Location: student_attendance.php?msg=added');
        exit;
    }
    
    if ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $date_val = $_POST['date'] ?? date('Y-m-d');
        $lesson = $_POST['lesson'] ?? '-';
        $homework = $_POST['homework'] ?? '-';
        $remarks = $_POST['remarks'] ?? '-';
        $waited = $_POST['waited'] ?? '0';
        $duration = $_POST['duration'] ?? '30';
        $notes_text = "Lesson: $lesson | Homework: $homework | Remarks: $remarks | Waited: {$waited} Min | Duration: {$duration} Min";

        $record = [
            'student_id' => $_POST['student_id'] ?? '',
            'date' => $date_val,
            'year' => intval(date('Y', strtotime($date_val))),
            'month' => intval(date('n', strtotime($date_val))),
            'status' => $_POST['status'] ?? 'Present',
            'notes' => $notes_text,
            'lesson' => $lesson,
            'homework' => $homework,
            'remarks' => $remarks,
            'waited' => $waited . ' Min',
            'duration' => $duration . ' Min'
        ];
        update_db_record('attendance', 'id', $id, $record);
        header('Location: student_attendance.php?msg=updated');
        exit;
    }
    
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        delete_db_record('attendance', 'id', $id);
        header('Location: student_attendance.php?msg=deleted');
        exit;
    }
}

// Fetch Students and Attendance
$students = get_all_students();
if (empty($students)) {
    $students = [
        [
            'id' => 1,
            'roll_no' => 'STU-1001',
            'name' => 'Tur Al Kibria',
            'father_name' => 'Kibria Alam',
            'teacher_name' => 'Sumera Tabassum',
            'course' => 'Quran Hifz',
            'status' => 'Active',
            'country' => 'Pakistan',
            'city' => 'Lahore',
            'whatsapp' => '+923001234567',
        ],
        [
            'id' => 2,
            'roll_no' => 'STU-1002',
            'name' => 'Ali Khan',
            'father_name' => 'Akbar Khan',
            'teacher_name' => 'Hafiz Ahmed',
            'course' => 'Tajweed Essentials',
            'status' => 'Active',
            'country' => 'United Kingdom',
            'city' => 'London',
            'whatsapp' => '+447912345678',
        ],
        [
            'id' => 3,
            'roll_no' => 'STU-1003',
            'name' => 'Fatima Noor',
            'father_name' => 'Muhammad Noor',
            'teacher_name' => 'Sumera Tabassum',
            'course' => 'Arabic Language',
            'status' => 'Active',
            'country' => 'United States',
            'city' => 'Dallas',
            'whatsapp' => '+12145550199',
        ]
    ];
}

$attendance_records = get_db_table('attendance') ?: [];
if (empty($attendance_records)) {
    $attendance_records = [
        [
            'id' => 1,
            'student_id' => 'STU-1001',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'status' => 'Present',
            'lesson' => 'Surah Al-Baqarah Verses 1-10',
            'homework' => 'Memorize Verses 11-15',
            'remarks' => 'Excellent recitation and focus',
            'waited' => '2 Min',
            'duration' => '30 Min'
        ],
        [
            'id' => 2,
            'student_id' => 'STU-1002',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'status' => 'Present',
            'lesson' => 'Tajweed Rule: Noon Sakinah',
            'homework' => 'Identify 5 examples from Juzz Amma',
            'remarks' => 'Good comprehension of the rule',
            'waited' => '0 Min',
            'duration' => '45 Min'
        ],
        [
            'id' => 3,
            'student_id' => 'STU-1003',
            'date' => date('Y-m-d', strtotime('-2 days')),
            'status' => 'Absent',
            'lesson' => '-',
            'homework' => '-',
            'remarks' => 'Informed 2 hours prior',
            'waited' => '0 Min',
            'duration' => '0 Min'
        ],
        [
            'id' => 4,
            'student_id' => 'STU-1001',
            'date' => date('Y-m-d', strtotime('-3 days')),
            'status' => 'Present',
            'lesson' => 'Surah Al-Fatihah revision',
            'homework' => 'Revise first half of Juzz Amma',
            'remarks' => 'Fluent delivery',
            'waited' => '5 Min',
            'duration' => '30 Min'
        ]
    ];
}

// Search and Filter Handling (PHP side)
$filter_student = $_GET['student_id'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_date = $_GET['date'] ?? '';

$filtered_records = [];
foreach ($attendance_records as $rec) {
    if ($filter_student && $rec['student_id'] !== $filter_student) continue;
    if ($filter_status && $rec['status'] !== $filter_status) continue;
    if ($filter_date && $rec['date'] !== $filter_date) continue;
    $filtered_records[] = $rec;
}

// Compute Statistics on filtered or total logs
$total_sessions = count($filtered_records);
$presents = 0;
$absents = 0;
$leaves = 0;
foreach ($filtered_records as $rec) {
    if ($rec['status'] === 'Present') $presents++;
    elseif ($rec['status'] === 'Absent') $absents++;
    elseif (strpos($rec['status'], 'Leave') !== false || $rec['status'] === 'Leave') $leaves++;
}
$presence_rate = $total_sessions > 0 ? round(($presents / $total_sessions) * 100, 1) : 100;

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
                    if ($_GET['msg'] === 'added') echo 'Attendance record created successfully.';
                    elseif ($_GET['msg'] === 'updated') echo 'Attendance record updated successfully.';
                    elseif ($_GET['msg'] === 'deleted') echo 'Attendance record deleted successfully.';
                ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Module Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8 bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-primary/5 rounded-xl flex items-center justify-center text-primary shrink-0 border border-primary/10">
            <i data-lucide="graduation-cap" class="w-7 h-7"></i>
        </div>
        <div>
          <h1 class="text-2xl font-black text-primary tracking-tight">Student Academic Attendance</h1>
          <p class="text-xs text-primary/60 mt-1 font-medium">Browse, edit, delete, and add daily student attendance logs</p>
        </div>
      </div>
      <div class="flex gap-3">
         <button onclick="openAddModal()" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(24,77,85,0.3)] flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Record
        </button>
      </div>
    </div>

    <!-- Filter Control Panel -->
    <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm mb-8">
        <form method="GET" action="student_attendance.php" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-2">Select Student</label>
                <select name="student_id" class="w-full px-4 py-3 bg-white border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                    <option value="">All Students</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?php echo $s['roll_no']; ?>" <?php echo $filter_student === $s['roll_no'] ? 'selected' : ''; ?>>
                            <?php echo $s['name']; ?> (<?php echo $s['roll_no']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-2">Attendance Status</label>
                <select name="status" class="w-full px-4 py-3 bg-white border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                    <option value="">All Statuses</option>
                    <option value="Present" <?php echo $filter_status === 'Present' ? 'selected' : ''; ?>>Present</option>
                    <option value="Absent" <?php echo $filter_status === 'Absent' ? 'selected' : ''; ?>>Absent</option>
                    <option value="Student On Leave" <?php echo $filter_status === 'Student On Leave' ? 'selected' : ''; ?>>Student On Leave</option>
                    <option value="Teacher On Leave" <?php echo $filter_status === 'Teacher On Leave' ? 'selected' : ''; ?>>Teacher On Leave</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-2">Specific Date</label>
                <input type="date" name="date" class="w-full px-4 py-3 bg-white border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none" value="<?php echo $filter_date; ?>">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-grow bg-primary text-white py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all text-center">Filter</button>
                <a href="student_attendance.php" class="px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Metrics Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-primary/5 rounded-xl text-primary flex items-center justify-center shrink-0">
                <i data-lucide="book-open" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold tracking-widest text-primary/50 block">Total Sessions</span>
                <span class="text-xl font-black text-primary block mt-0.5"><?php echo $total_sessions; ?></span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="award" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold tracking-widest text-primary/50 block">Presence Rate</span>
                <span class="text-xl font-black text-emerald-600 block mt-0.5"><?php echo $presence_rate; ?>%</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-xl text-red-600 flex items-center justify-center shrink-0">
                <i data-lucide="x-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold tracking-widest text-primary/50 block">Total Absents</span>
                <span class="text-xl font-black text-red-600 block mt-0.5"><?php echo $absents; ?></span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-50 rounded-xl text-orange-500 flex items-center justify-center shrink-0">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold tracking-widest text-primary/50 block">Total Leaves</span>
                <span class="text-xl font-black text-orange-500 block mt-0.5"><?php echo $leaves; ?></span>
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
                        <th class="px-6 py-4">Student Profile</th>
                        <th class="px-6 py-4">Teacher & Course</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Lesson & Homework</th>
                        <th class="px-6 py-4">Time Specs</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    <?php if (empty($filtered_records)): ?>
                        <tr>
                            <td colspan="7" class="text-center p-12 text-primary/40 font-bold">
                                <i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                                No student attendance logs match current filters.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($filtered_records as $rec):
                        // Find matching student
                        $matched_student = null;
                        foreach ($students as $st) {
                            if ($st['roll_no'] === $rec['student_id']) {
                                $matched_student = $st;
                                break;
                            }
                        }
                        $stu_name = $matched_student ? $matched_student['name'] : 'Unknown Student';
                        $stu_pic = 'https://ui-avatars.com/api/?name=' . urlencode($stu_name) . '&background=184D55&color=F7FAFF&size=100';
                        $stu_info = $matched_student ? ($matched_student['course'] . ' &bull; ' . $matched_student['teacher_name']) : '-';
                        
                        // Status styling
                        $status_class = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                        if ($rec['status'] === 'Present') $status_class = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                        elseif ($rec['status'] === 'Absent') $status_class = 'bg-red-50 text-red-700 border-red-100';
                        elseif (strpos($rec['status'], 'Leave') !== false || $rec['status'] === 'Leave') $status_class = 'bg-orange-50 text-orange-700 border-orange-100';
                    ?>
                    <tr class="hover:bg-primary/[0.01] transition-colors">
                        <td class="px-6 py-4 font-extrabold text-primary"><?php echo date('d M Y', strtotime($rec['date'])); ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden border border-primary/10 shrink-0">
                                    <img src="<?php echo $stu_pic; ?>" alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-primary text-sm"><?php echo $stu_name; ?></p>
                                    <p class="text-[9px] text-primary/50 font-black uppercase tracking-wider mt-0.5"><?php echo $rec['student_id']; ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-primary"><?php echo $matched_student ? $matched_student['course'] : 'N/A'; ?></p>
                            <p class="text-[10px] text-primary/60 font-medium mt-0.5">Instructor: <?php echo $matched_student ? $matched_student['teacher_name'] : 'Unassigned'; ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 border <?php echo $status_class; ?> rounded-full text-[9px] font-black uppercase tracking-wider">
                                <?php echo $rec['status']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate">
                            <p class="font-bold text-primary" title="<?php echo $rec['lesson']; ?>">L: <?php echo $rec['lesson']; ?></p>
                            <p class="text-[10px] text-primary/60 font-medium mt-0.5" title="<?php echo $rec['homework']; ?>">HW: <?php echo $rec['homework']; ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-primary font-bold"><i data-lucide="clock" class="w-3.5 h-3.5 inline mr-1 text-primary/40 align-middle"></i><?php echo $rec['duration']; ?></span>
                                <span class="text-[10px] text-primary/60 font-medium">Wait Time: <?php echo $rec['waited']; ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($rec)); ?>)" class="p-2 bg-primary/5 text-primary hover:bg-primary hover:text-white rounded-lg transition-colors" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <form method="POST" action="student_attendance.php" onsubmit="return confirm('Are you sure you want to delete this record?');" class="inline">
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

<!-- Modal: Add Attendance Record -->
<div id="addModal" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl border border-primary/10 shadow-2xl w-full max-w-lg overflow-hidden animate-scale-up">
        <div class="bg-primary p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i data-lucide="plus-circle" class="w-6 h-6"></i>
                <span class="font-black tracking-tight text-lg">Add Attendance Record</span>
            </div>
            <button onclick="closeAddModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>
        <form method="POST" action="student_attendance.php" class="p-6 space-y-4">
            <input type="hidden" name="action" value="add">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Select Student</label>
                    <select name="student_id" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <?php foreach ($students as $s): ?>
                            <option value="<?php echo $s['roll_no']; ?>"><?php echo $s['name']; ?> (<?php echo $s['roll_no']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Date</label>
                    <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Status</label>
                    <select name="status" required class="w-full px-3 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Student On Leave">Student On Leave</option>
                        <option value="Teacher On Leave">Teacher On Leave</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Duration (Min)</label>
                    <select name="duration" class="w-full px-3 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="30">30 Min</option>
                        <option value="45">45 Min</option>
                        <option value="60">60 Min</option>
                        <option value="0">0 Min</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Wait Time (Min)</label>
                    <select name="waited" class="w-full px-3 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="0">0 Min</option>
                        <option value="2">2 Min</option>
                        <option value="5">5 Min</option>
                        <option value="10">10 Min</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Lesson Covered</label>
                <input type="text" name="lesson" placeholder="e.g. Surah Al-Baqarah Verses 15-20" class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Homework Assigned</label>
                <input type="text" name="homework" placeholder="e.g. Memorize lesson details" class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Performance Remarks</label>
                <textarea name="remarks" placeholder="Provide general feedback on student performance..." class="w-full p-4 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none h-20"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAddModal()" class="px-5 py-3 border border-primary/10 hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-bold uppercase transition-all">Cancel</button>
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all">Save Record</button>
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
                <span class="font-black tracking-tight text-lg">Edit Attendance Record</span>
            </div>
            <button onclick="closeEditModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>
        <form method="POST" action="student_attendance.php" class="p-6 space-y-4">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Select Student</label>
                    <select name="student_id" id="edit_student_id" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <?php foreach ($students as $s): ?>
                            <option value="<?php echo $s['roll_no']; ?>"><?php echo $s['name']; ?> (<?php echo $s['roll_no']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Date</label>
                    <input type="date" name="date" id="edit_date" required class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Status</label>
                    <select name="status" id="edit_status" required class="w-full px-3 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Student On Leave">Student On Leave</option>
                        <option value="Teacher On Leave">Teacher On Leave</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Duration (Min)</label>
                    <select name="duration" id="edit_duration" class="w-full px-3 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="30">30 Min</option>
                        <option value="45">45 Min</option>
                        <option value="60">60 Min</option>
                        <option value="0">0 Min</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Wait Time (Min)</label>
                    <select name="waited" id="edit_waited" class="w-full px-3 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="0">0 Min</option>
                        <option value="2">2 Min</option>
                        <option value="5">5 Min</option>
                        <option value="10">10 Min</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Lesson Covered</label>
                <input type="text" name="lesson" id="edit_lesson" class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Homework Assigned</label>
                <input type="text" name="homework" id="edit_homework" class="w-full px-4 py-3 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-primary/60 block mb-1">Performance Remarks</label>
                <textarea name="remarks" id="edit_remarks" class="w-full p-4 border border-primary/20 rounded-xl text-xs font-bold text-primary focus:ring-2 focus:ring-primary/30 outline-none h-20"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 border border-primary/10 hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-bold uppercase transition-all">Cancel</button>
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all">Update Record</button>
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
        document.getElementById('edit_student_id').value = record.student_id;
        document.getElementById('edit_date').value = record.date;
        document.getElementById('edit_status').value = record.status;
        
        // Extract raw number values for dropdowns
        const durMatch = (record.duration || '').match(/\d+/);
        document.getElementById('edit_duration').value = durMatch ? durMatch[0] : '30';
        
        const waitMatch = (record.waited || '').match(/\d+/);
        document.getElementById('edit_waited').value = waitMatch ? waitMatch[0] : '0';
        
        document.getElementById('edit_lesson').value = record.lesson || '';
        document.getElementById('edit_homework').value = record.homework || '';
        document.getElementById('edit_remarks').value = record.remarks || '';
        
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
