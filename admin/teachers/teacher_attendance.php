<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Specific Attendance
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);
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
                    <button class="text-primary text-[10px] font-bold uppercase hover:underline">View All Records</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
                                <th class="p-4">Date</th>
                                <th class="p-4">Login</th>
                                <th class="p-4">Logout</th>
                                <th class="p-4">Work Duration</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/5 text-primary/80">
                            <?php if (empty($this_teacher_attendance)): ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-primary/50 font-semibold">No attendance records logged for this teacher.</td>
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
                                    <td class="p-4 text-right font-medium text-primary/60">
                                        <?php echo htmlspecialchars($att['remarks'] ?? 'N/A'); ?>
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

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
