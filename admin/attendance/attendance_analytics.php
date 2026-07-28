<?php
/**
 * Al Foz Islamic Institute - Attendance Analytics Hub
 * Premium analytical center with dynamic, real-time calculations.
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
        ['id' => 1, 'roll_no' => 'STU-1001', 'name' => 'Tur Al Kibria', 'status' => 'Active', 'country' => 'Pakistan', 'teacher_name' => 'Sumera Tabassum'],
        ['id' => 2, 'roll_no' => 'STU-1002', 'name' => 'Ali Khan', 'status' => 'Active', 'country' => 'United Kingdom', 'teacher_name' => 'Hafiz Ahmed'],
        ['id' => 3, 'roll_no' => 'STU-1003', 'name' => 'Fatima Noor', 'status' => 'Active', 'country' => 'United States', 'teacher_name' => 'Sumera Tabassum'],
        ['id' => 4, 'roll_no' => 'STU-1004', 'name' => 'Zainab Bibi', 'status' => 'Trial', 'country' => 'Canada', 'teacher_name' => 'Ustadha Sara']
    ];
}

if (empty($attendance_records)) {
    $attendance_records = [
        ['student_id' => 'STU-1001', 'status' => 'Present'],
        ['student_id' => 'STU-1002', 'status' => 'Present'],
        ['student_id' => 'STU-1003', 'status' => 'Absent'],
        ['student_id' => 'STU-1001', 'status' => 'Present'],
        ['student_id' => 'STU-1002', 'status' => 'Present'],
        ['student_id' => 'STU-1004', 'status' => 'Present'],
    ];
}

// 1. Faculty Consistency
$teacher_stats = [];
foreach ($students as $s) {
    $teacher = $s['teacher_name'] ?? 'Unassigned';
    if (!isset($teacher_stats[$teacher])) {
        $teacher_stats[$teacher] = [
            'students_count' => 0,
            'present' => 0,
            'total' => 0
        ];
    }
    $teacher_stats[$teacher]['students_count']++;
}

foreach ($attendance_records as $rec) {
    $stu_id = $rec['student_id'];
    $teacher = 'Unassigned';
    foreach ($students as $s) {
        if ($s['roll_no'] === $stu_id) {
            $teacher = $s['teacher_name'] ?? 'Unassigned';
            break;
        }
    }
    if (!isset($teacher_stats[$teacher])) {
        $teacher_stats[$teacher] = [
            'students_count' => 0,
            'present' => 0,
            'total' => 0
        ];
    }
    $teacher_stats[$teacher]['total']++;
    if ($rec['status'] === 'Present') {
        $teacher_stats[$teacher]['present']++;
    }
}

// 2. Student Retention Rate
$total_students = count($students);
$active_students = 0;
foreach ($students as $s) {
    if (($s['status'] ?? 'Active') === 'Active') {
        $active_students++;
    }
}
$retention_rate = $total_students > 0 ? round(($active_students / $total_students) * 100) : 85;
$stroke_dashoffset = 283 - (283 * $retention_rate / 100);

// 3. Regional Participation
$country_stats = [];
foreach ($students as $s) {
    $c = $s['country'] ?? 'Other';
    $country_stats[$c] = ($country_stats[$c] ?? 0) + 1;
}
arsort($country_stats);

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
        <h1 class="text-2xl font-black text-primary tracking-tight">Attendance Analytics Hub</h1>
        <p class="text-xs text-primary/60 mt-1 font-medium">Data-driven insights into educational consistency and student retention.</p>
      </div>
    </div>

    <!-- Analytics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        <!-- Teacher Performance -->
        <div class="bg-white rounded-[24px] p-8 border border-primary/10 shadow-sm">
            <h3 class="text-xs font-black uppercase tracking-widest text-primary/50 mb-6">Faculty Consistency</h3>
            <div class="space-y-6">
                <?php foreach ($teacher_stats as $name => $stats): 
                    $pct = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100) : 100;
                    $bar_color = $pct >= 90 ? 'bg-emerald-500' : ($pct >= 75 ? 'bg-amber-500' : 'bg-red-500');
                ?>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <span class="text-sm font-black text-primary"><?php echo $name; ?></span>
                                <span class="text-[9px] text-primary/50 block font-bold uppercase tracking-wider mt-0.5"><?php echo $stats['students_count']; ?> Assigned Students</span>
                            </div>
                            <span class="text-xs font-black text-primary"><?php echo $pct; ?>% Rate</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                            <div class="<?php echo $bar_color; ?> h-full" style="width: <?php echo $pct; ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Retention Rate -->
        <div class="bg-white rounded-[24px] p-8 border border-primary/10 shadow-sm flex flex-col items-center justify-center">
            <h3 class="text-xs font-black uppercase tracking-widest text-primary/50 mb-6 text-center w-full">Student Retention Rate</h3>
            <div class="relative w-40 h-40">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="#184D55" stroke-width="8" stroke-opacity="0.05"></circle>
                    <circle cx="50" cy="50" r="45" fill="none" stroke="#184D55" stroke-width="8" stroke-dasharray="283" stroke-dashoffset="<?php echo $stroke_dashoffset; ?>" stroke-linecap="round" class="transition-all duration-500"></circle>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="text-3xl font-black text-primary"><?php echo $retention_rate; ?>%</span>
                    <span class="text-[8px] font-black text-primary/40 uppercase tracking-widest">Active Status</span>
                </div>
            </div>
            <p class="text-center text-[10px] text-primary/60 font-medium mt-6 max-w-xs leading-relaxed">
                Matches the ratio of active student accounts against total registered trial and temporary students.
            </p>
        </div>

        <!-- Attendance Trends -->
        <div class="bg-white rounded-[24px] p-8 border border-primary/10 shadow-sm">
            <h3 class="text-xs font-black uppercase tracking-widest text-primary/50 mb-6">Regional Participation</h3>
            <div class="space-y-4">
                <?php foreach ($country_stats as $country => $count): 
                    $pct = round(($count / $total_students) * 100);
                ?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 bg-primary rounded-full"></div>
                            <span class="text-xs font-bold text-primary"><?php echo $country; ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-primary/70"><?php echo $count; ?></span>
                            <span class="text-[9px] text-primary/40 font-black bg-primary/5 px-1.5 py-0.5 rounded"><?php echo $pct; ?>%</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
  </div>
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
