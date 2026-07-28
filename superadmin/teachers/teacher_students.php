<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Assigned Students
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
    require_once __DIR__ . '/../../includes/students_data.php';
    $all_students = get_all_students();
    $assigned_students = [];
    foreach ($all_students as $s) {
        if (
            (isset($s['teacher_id']) && $s['teacher_id'] === $teacher['employee_id']) ||
            (isset($s['teacher_name']) && strcasecmp($s['teacher_name'], $teacher['name']) === 0)
        ) {
            $assigned_students[] = $s;
        }
    }

    $t_total = count($assigned_students);
    $t_active = 0;
    $t_trial = 0;
    $t_leave = 0;
    $t_inactive = 0;
    foreach ($assigned_students as $s) {
        $st = strtolower($s['status'] ?? '');
        if ($st === 'active') $t_active++;
        elseif ($st === 'trial') $t_trial++;
        elseif ($st === 'leave' || $st === 'on leave') $t_leave++;
        else $t_inactive++;
    }
    ?>

    <!-- Student Count Highlight Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
      <div class="bg-white p-4 rounded-xl border border-primary/10 text-center shadow-sm">
        <span class="text-[9px] text-primary/60 font-black uppercase tracking-wider">Total Students</span>
        <div class="text-xl font-black text-primary mt-1"><?php echo $t_total; ?></div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-primary/10 text-center shadow-sm">
        <span class="text-[9px] text-emerald-600 font-black uppercase tracking-wider">Active Students</span>
        <div class="text-xl font-black text-emerald-700 mt-1"><?php echo $t_active; ?></div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-primary/10 text-center shadow-sm">
        <span class="text-[9px] text-amber-600 font-black uppercase tracking-wider">Trial Students</span>
        <div class="text-xl font-black text-amber-700 mt-1"><?php echo $t_trial; ?></div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-primary/10 text-center shadow-sm">
        <span class="text-[9px] text-indigo-600 font-black uppercase tracking-wider">On Leave</span>
        <div class="text-xl font-black text-indigo-700 mt-1"><?php echo $t_leave; ?></div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-primary/10 text-center shadow-sm">
        <span class="text-[9px] text-rose-600 font-black uppercase tracking-wider">Inactive</span>
        <div class="text-xl font-black text-rose-700 mt-1"><?php echo $t_inactive; ?></div>
      </div>
    </div>

    <!-- Student List Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">Assigned Student Roster</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Detailed list of students authorized under this scholar.</p>
        </div>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-3">Student Picture & Name</th>
              <th class="p-3">Student ID</th>
              <th class="p-3">Country</th>
              <th class="p-3">Course</th>
              <th class="p-3">Schedule (Days)</th>
              <th class="p-3">Minutes/Class</th>
              <th class="p-3">Total Fee</th>
              <th class="p-3">Teacher Salary Share</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($assigned_students)): ?>
            <tr>
              <td colspan="9" class="p-20 text-center">
                <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <i data-lucide="users-2" class="w-8 h-8 text-primary/20"></i>
                </div>
                <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No assigned students found for this scholar.</p>
              </td>
            </tr>
            <?php else: ?>
              <?php foreach ($assigned_students as $student): ?>
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="p-3 flex items-center gap-3">
                  <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student['name']); ?>&background=184D55&color=fff&size=100" alt="" class="w-8 h-8 rounded-lg object-cover">
                  <div class="font-bold text-primary"><?php echo htmlspecialchars($student['name']); ?></div>
                </td>
                <td class="p-3 font-mono text-[10px]"><?php echo htmlspecialchars($student['student_id'] ?? $student['roll_no'] ?? 'N/A'); ?></td>
                <td class="p-3 font-semibold text-primary/70"><?php echo htmlspecialchars($student['country'] ?? 'N/A'); ?></td>
                <td class="p-3 font-semibold text-primary/70"><?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?></td>
                <td class="p-3 text-primary/70 font-semibold">
                  <?php
                    $days = [];
                    foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $d) {
                        if (!empty($student[$d . '_enabled']) && $student[$d . '_enabled'] != 'false' && $student[$d . '_enabled'] != '0') {
                            $days[] = ucfirst(substr($d, 0, 3));
                        }
                    }
                    echo empty($days) ? 'N/A' : implode(', ', $days);
                  ?>
                </td>
                <td class="p-3 font-semibold text-primary/70"><?php echo htmlspecialchars($student['class_duration'] ?? $student['monday_duration'] ?? '30'); ?> Mins</td>
                <td class="p-3 font-bold text-primary"><?php echo htmlspecialchars($student['monthly_fee'] ?? '0'); ?> <?php echo htmlspecialchars($student['currency'] ?? 'PKR'); ?></td>
                <td class="p-3 font-bold text-emerald-700">
                  <?php
                    // Display individual student rate computed based on teacher's rate matrix
                    $days_count = count($days);
                    $student_duration = intval($student['class_duration'] ?? $student['monday_duration'] ?? 30);
                    $rate_days = ($days_count <= 3) ? 3 : 5;
                    $rate_dur = 30;
                    if ($student_duration > 75) $rate_dur = 90;
                    elseif ($student_duration > 52) $rate_dur = 60;
                    elseif ($student_duration > 37) $rate_dur = 45;
                    $rate_key = 'rate_' . $rate_dur . '_' . $rate_days;
                    $student_rate = isset($teacher[$rate_key]) ? floatval($teacher[$rate_key]) : 0;
                    if ($student_rate <= 0) {
                        if ($rate_dur == 30) $student_rate = ($rate_days == 3) ? 1000 : 1500;
                        elseif ($rate_dur == 45) $student_rate = ($rate_days == 3) ? 1500 : 2000;
                        elseif ($rate_dur == 60) $student_rate = ($rate_days == 3) ? 2000 : 2500;
                        else $student_rate = ($rate_days == 3) ? 3000 : 4000;
                    }
                    echo htmlspecialchars($student_rate) . ' ' . htmlspecialchars($teacher['currency'] ?? 'PKR');
                  ?>
                </td>
                <td class="p-3 text-right">
                  <a href="../students/student_profile.php?id=<?php echo $student['id']; ?>" class="text-[10px] bg-primary/10 hover:bg-primary/20 text-primary px-2.5 py-1.5 rounded-lg font-bold uppercase transition-all">View Profile</a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
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
