<?php
/**
 * Al Foz Islamic Institute - Attendance Global Timeline
 * Premium analytical and audit timeline connected dynamically to database.
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Admin');

$students = get_all_students() ?: [];
$attendance_records = get_db_table('attendance') ?: [];

// Fallbacks for demo mode if empty
if (empty($students)) {
    $students = [
        ['id' => 1, 'roll_no' => 'STU-1001', 'name' => 'Tur Al Kibria', 'teacher_name' => 'Sumera Tabassum'],
        ['id' => 2, 'roll_no' => 'STU-1002', 'name' => 'Ali Khan', 'teacher_name' => 'Hafiz Ahmed'],
        ['id' => 3, 'roll_no' => 'STU-1003', 'name' => 'Fatima Noor', 'teacher_name' => 'Sumera Tabassum']
    ];
}

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
        ]
    ];
}

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$timeline = [];
foreach ($attendance_records as $rec) {
    $rec_month = date('m', strtotime($rec['date']));
    $rec_year = date('Y', strtotime($rec['date']));
    
    if ($rec_month === $month && $rec_year === $year) {
        // Find matching student
        $matched_student = null;
        foreach ($students as $st) {
            if ($st['roll_no'] === $rec['student_id']) {
                $matched_student = $st;
                break;
            }
        }
        $stu_name = $matched_student ? $matched_student['name'] : 'Unknown Student';
        $t_name = $matched_student ? $matched_student['teacher_name'] : 'Unassigned';
        
        $timeline[] = [
            'date' => date('d F Y', strtotime($rec['date'])),
            'time' => '10:00 AM', // assumed default class time
            'student' => $stu_name,
            'teacher' => $t_name,
            'status' => $rec['status'],
            'wait' => $rec['waited'] ?? '0 Min',
            'duration' => $rec['duration'] ?? '30 Min',
            'lesson' => $rec['lesson'] ?? '-',
            'remarks' => $rec['remarks'] ?? '-'
        ];
    }
}

// Sort timeline by date descending
usort($timeline, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent font-['Poppins']">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-black text-primary tracking-tight">Global Attendance Timeline</h1>
        <p class="text-xs text-primary/60 mt-1 font-medium">Historical audit trail for <?php echo date('F Y', mktime(0, 0, 0, (int)$month, 10, (int)$year)); ?>.</p>
      </div>
      <form method="GET" class="flex gap-2">
        <select name="month" class="bg-white border border-primary/10 rounded-xl px-4 py-2 text-xs font-bold text-primary focus:ring-1 focus:ring-primary outline-none">
            <?php for($m=1; $m<=12; $m++): $m_val = str_pad($m, 2, '0', STR_PAD_LEFT); ?>
                <option value="<?php echo $m_val; ?>" <?php echo $month == $m_val ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $m, 10)); ?></option>
            <?php endfor; ?>
        </select>
        <select name="year" class="bg-white border border-primary/10 rounded-xl px-4 py-2 text-xs font-bold text-primary focus:ring-1 focus:ring-primary outline-none">
            <?php for($y=2024; $y<=2027; $y++): ?>
                <option value="<?php echo $y; ?>" <?php echo $year == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all hover:bg-primary/90">Filter</button>
      </form>
    </div>

    <div class="space-y-6 relative before:absolute before:left-4 sm:before:left-8 before:top-0 before:bottom-0 before:w-0.5 before:bg-primary/5">
        <?php if(empty($timeline)): ?>
            <div class="bg-white rounded-[24px] p-12 border border-primary/10 shadow-sm text-center">
                <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-3 text-primary/30"></i>
                <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No historical logs found for this period.</p>
            </div>
        <?php endif; ?>
        <?php foreach($timeline as $t): ?>
        <div class="relative pl-12 sm:pl-20">
            <div class="absolute left-2 sm:left-6 top-0 w-4 h-4 rounded-full bg-primary border-4 border-white shadow-sm"></div>
            <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm hover:border-primary/30 transition-all">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-primary/40 block mb-1"><?php echo $t['date']; ?> &bull; <?php echo $t['time']; ?></span>
                        <h4 class="font-bold text-primary"><?php echo $t['student']; ?> with <?php echo $t['teacher']; ?></h4>
                    </div>
                    <span class="px-3 py-1 rounded-full border border-primary/10 bg-primary/5 text-primary font-black text-[9px] uppercase tracking-widest"><?php echo $t['status']; ?></span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-y border-primary/5 mb-4">
                    <div>
                        <p class="text-[8px] font-bold text-primary/40 uppercase tracking-widest mb-0.5">Wait Time</p>
                        <p class="font-bold text-primary text-xs"><?php echo $t['wait']; ?></p>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-primary/40 uppercase tracking-widest mb-0.5">Duration</p>
                        <p class="font-bold text-primary text-xs"><?php echo $t['duration']; ?></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[8px] font-bold text-primary/40 uppercase tracking-widest mb-0.5">Lesson Focus</p>
                        <p class="font-medium text-primary text-xs italic">"<?php echo $t['lesson']; ?>"</p>
                    </div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-primary/60 font-medium">
                    <span>Remarks: <?php echo $t['remarks']; ?></span>
                    <a href="student_attendance.php" class="text-primary font-bold text-[9px] uppercase tracking-widest hover:underline flex items-center gap-1.5">
                        <i data-lucide="edit-3" class="w-3 h-3"></i> Manage Records
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
  </div>
  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
