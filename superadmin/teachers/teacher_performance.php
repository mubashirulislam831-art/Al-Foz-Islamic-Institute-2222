<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Performance Analytics
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');
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
    // Recalculate attendance stats for punctuality index
    $all_teacher_attendance = get_db_table('teacher_attendance') ?: [];
    $this_teacher_attendance = [];
    foreach ($all_teacher_attendance as $att) {
        if (isset($att['employee_id']) && $att['employee_id'] === $teacher['employee_id']) {
            $this_teacher_attendance[] = $att;
        }
    }
    $total_days = count($this_teacher_attendance);
    $late_days = 0;
    foreach ($this_teacher_attendance as $att) {
        $st = strtolower($att['status'] ?? '');
        if ($st === 'late' || $st === 'late present') {
            $late_days++;
        }
    }
    $punctuality_index = ($total_days > 0) ? round((1 - ($late_days / $total_days)) * 100, 0) : 100;

    // Gather all progress reports for assigned students
    $student_ids = [];
    $active_student_count = 0;
    if (!empty($assigned_students)) {
        foreach ($assigned_students as $student) {
            if (isset($student['id'])) {
                $student_ids[] = (int)$student['id'];
                if (($student['status'] ?? '') === 'Active') {
                    $active_student_count++;
                }
            }
        }
    }

    $all_progress_reports = get_db_table('progress_reports') ?: [];
    $this_teacher_reports = [];
    $total_grades_score = 0;
    $grades_count = 0;

    $grade_values = [
        'A+' => 98, 'A' => 92, 'A-' => 88,
        'B+' => 83, 'B' => 78, 'B-' => 74,
        'C+' => 68, 'C' => 62, 'D' => 50, 'F' => 30
    ];

    foreach ($all_progress_reports as $rep) {
        if (isset($rep['student_id']) && in_array((int)$rep['student_id'], $student_ids)) {
            $this_teacher_reports[] = $rep;
            $g = strtoupper(trim($rep['grade'] ?? ''));
            if (isset($grade_values[$g])) {
                $total_grades_score += $grade_values[$g];
                $grades_count++;
            }
        }
    }

    $avg_exam_score = ($grades_count > 0) ? round($total_grades_score / $grades_count, 1) : 0;
    $retention_rate = ($active_student_count > 0) ? 100 : 0;
    ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- KPI Metrics -->
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
            <h3 class="text-sm font-black text-primary uppercase tracking-wider mb-6 flex items-center gap-2">
                <i data-lucide="activity" class="w-4 h-4 text-primary"></i> Key Performance Indicators
            </h3>
            <div class="space-y-6">
                <div>
                    <div class="flex justify-between text-[10px] font-bold text-primary mb-2 uppercase tracking-widest">
                        <span>Student Retention Rate</span>
                        <span><?php echo $retention_rate > 0 ? $retention_rate . '%' : 'N/A'; ?></span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-primary h-full" style="width: <?php echo $retention_rate; ?>%;"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-[10px] font-bold text-primary mb-2 uppercase tracking-widest">
                        <span>Average Feedback Score</span>
                        <span>4.9 / 5.0</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full" style="width: 98%;"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-[10px] font-bold text-primary mb-2 uppercase tracking-widest">
                        <span>Punctuality Index</span>
                        <span><?php echo $punctuality_index; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-indigo-500 h-full" style="width: <?php echo $punctuality_index; ?>%;"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-[10px] font-bold text-primary mb-2 uppercase tracking-widest">
                        <span>Trial Conversion Rate</span>
                        <span>85%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-amber-500 h-full" style="width: 85%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Progress Summary -->
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
            <h3 class="text-sm font-black text-primary uppercase tracking-wider mb-6 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-primary"></i> Assigned Students Success Ratio
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 border border-primary/10 rounded-xl bg-primary/5 text-center">
                    <div class="text-2xl font-black text-primary"><?php echo count($assigned_students); ?></div>
                    <div class="text-[9px] font-bold text-primary/50 uppercase tracking-widest mt-1">Assigned Students</div>
                </div>
                <div class="p-4 border border-primary/10 rounded-xl bg-primary/5 text-center">
                    <div class="text-2xl font-black text-primary"><?php echo count($this_teacher_reports); ?></div>
                    <div class="text-[9px] font-bold text-primary/50 uppercase tracking-widest mt-1">Progress Reports Issued</div>
                </div>
                <div class="p-4 border border-primary/10 rounded-xl bg-primary/5 text-center">
                    <div class="text-2xl font-black text-primary"><?php echo $avg_exam_score > 0 ? $avg_exam_score . '%' : 'N/A'; ?></div>
                    <div class="text-[9px] font-bold text-primary/50 uppercase tracking-widest mt-1">Avg. Exam Score</div>
                </div>
                <div class="p-4 border border-primary/10 rounded-xl bg-primary/5 text-center">
                    <div class="text-2xl font-black text-primary">4.9</div>
                    <div class="text-[9px] font-bold text-primary/50 uppercase tracking-widest mt-1">Parent Rating</div>
                </div>
            </div>
            <div class="mt-8 border-t border-primary/5 pt-6 text-center">
                <button class="px-6 py-2 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary/90 transition-all">Detailed Performance Report</button>
            </div>
        </div>
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
