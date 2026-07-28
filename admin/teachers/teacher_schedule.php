<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Schedule
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

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">Weekly Class Schedules</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Full active slot matching index for this scholar.</p>
        </div>
      </div>
      
      <?php
      $schedules = [];
      if (!empty($assigned_students)) {
          $days_list = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
          foreach ($assigned_students as $student) {
              foreach ($days_list as $d_key => $d_name) {
                  $enabled = $student[$d_key . '_enabled'] ?? '';
                  if (!empty($enabled) && $enabled !== 'false' && $enabled !== '0') {
                      $schedules[] = [
                          'student_name' => $student['name'] ?? 'N/A',
                          'course' => $student['course_name'] ?? $student['course'] ?? 'N/A',
                          'day' => $d_name,
                          'time' => $student[$d_key . '_time'] ?? $student['class_time'] ?? 'N/A',
                          'duration' => ($student[$d_key . '_duration'] ?? $student['duration'] ?? '30') . ' mins',
                          'country' => $student['country'] ?? 'N/A',
                          'timezone' => $student['timezone'] ?? 'N/A',
                          'status' => $student['status'] ?? 'Active',
                          'day_index' => array_search($d_key, array_keys($days_list))
                      ];
                  }
              }
          }
      }

      // Sort by day index, then time
      usort($schedules, function($a, $b) {
          if ($a['day_index'] !== $b['day_index']) {
              return $a['day_index'] - $b['day_index'];
          }
          return strcmp($a['time'], $b['time']);
      });
      ?>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-3">Student Name</th>
              <th class="p-3">Course</th>
              <th class="p-3">Day</th>
              <th class="p-3">Time</th>
              <th class="p-3">Duration</th>
              <th class="p-3">Country</th>
              <th class="p-3">Time Zone</th>
              <th class="p-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($schedules)): ?>
            <tr>
              <td colspan="8" class="p-20 text-center">
                <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <i data-lucide="calendar-off" class="w-8 h-8 text-primary/20"></i>
                </div>
                <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No active class schedules found for this scholar.</p>
              </td>
            </tr>
            <?php else: ?>
              <?php foreach ($schedules as $sched): ?>
              <tr class="hover:bg-primary/5 transition-colors">
                <td class="p-3 font-bold"><?php echo htmlspecialchars($sched['student_name']); ?></td>
                <td class="p-3 font-semibold text-primary/70"><?php echo htmlspecialchars($sched['course']); ?></td>
                <td class="p-3 font-bold text-primary"><?php echo htmlspecialchars($sched['day']); ?></td>
                <td class="p-3 font-mono text-primary font-bold"><?php echo htmlspecialchars($sched['time']); ?></td>
                <td class="p-3 font-semibold"><?php echo htmlspecialchars($sched['duration']); ?></td>
                <td class="p-3 font-semibold"><?php echo htmlspecialchars($sched['country']); ?></td>
                <td class="p-3 font-mono text-[10px]"><?php echo htmlspecialchars($sched['timezone']); ?></td>
                <td class="p-3">
                  <?php echo render_status_badge($sched['status']); ?>
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
