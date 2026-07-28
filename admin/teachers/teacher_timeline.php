<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher History Timeline
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
    $timeline_events = [];

    // 1. Joined Institute
    if (!empty($teacher['joining_date']) && $teacher['joining_date'] !== 'N/A') {
        $timeline_events[] = [
            'title' => 'Joined Institute',
            'date' => $teacher['joining_date'],
            'desc' => 'Officially onboarded as a scholar at Al Foz Islamic Institute.',
            'icon' => 'briefcase',
            'color' => 'bg-indigo-500'
        ];
    }

    // 2. Salary Disbursements
    $all_salaries = get_db_table('salary') ?: [];
    foreach ($all_salaries as $s) {
        if (isset($s['teacher_id']) && (int)$s['teacher_id'] === (int)$teacher['id']) {
            $p_date = $s['paid_date'] ?? '';
            if ($p_date) {
                $month_names = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'];
                $m_name = isset($month_names[$s['month']]) ? $month_names[$s['month']] : 'Month';
                $timeline_events[] = [
                    'title' => 'Salary Disbursed',
                    'date' => $p_date,
                    'desc' => 'Disbursed ' . number_format($s['amount']) . ' PKR for ' . $m_name . ' ' . $s['year'] . ' (Slip: ' . ($s['slip_number'] ?? 'N/A') . ').',
                    'icon' => 'check',
                    'color' => 'bg-emerald-500'
                ];
            }
        }
    }

    // 3. New Student Assigned
    if (!empty($assigned_students)) {
        foreach ($assigned_students as $student) {
            $reg_date = $student['created_at'] ?? $student['registration_date'] ?? '';
            if ($reg_date) {
                $timeline_events[] = [
                    'title' => 'New Student Assigned',
                    'date' => $reg_date,
                    'desc' => 'Student ' . htmlspecialchars($student['name']) . ' was assigned to ' . htmlspecialchars($teacher['name']) . ' for the ' . htmlspecialchars($student['course_name'] ?? $student['course'] ?? 'Quranic studies') . ' course.',
                    'icon' => 'user-plus',
                    'color' => 'bg-amber-500'
                ];
            }
        }
    }

    // Sort all events by date descending
    usort($timeline_events, function($a, $b) {
        return strcmp($b['date'], $a['date']);
    });

    // Take the top 8 events
    $timeline_events = array_slice($timeline_events, 0, 8);
    ?>

    <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-primary/10 shadow-sm p-8 mb-6">
        <h3 class="text-sm font-black text-primary uppercase tracking-wider mb-8 flex items-center gap-2">
            <i data-lucide="clock" class="w-4 h-4 text-primary"></i> Historical Event Timeline
        </h3>

        <?php if (empty($timeline_events)): ?>
        <div class="py-12 text-center">
            <div class="w-12 h-12 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-3">
                <i data-lucide="calendar-range" class="w-6 h-6 text-primary/30"></i>
            </div>
            <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No timeline logs found for this scholar.</p>
        </div>
        <?php else: ?>
        <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
            <?php foreach ($timeline_events as $ev): ?>
            <!-- Event Row -->
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white <?php echo $ev['color']; ?> text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                    <i data-lucide="<?php echo $ev['icon']; ?>" class="w-4 h-4"></i>
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[45%] bg-primary/5 p-4 rounded-xl border border-primary/10 shadow-sm">
                    <div class="flex items-center justify-between space-x-2 mb-1">
                        <div class="font-bold text-primary text-xs uppercase tracking-wider"><?php echo htmlspecialchars($ev['title']); ?></div>
                        <time class="font-mono text-[9px] font-bold text-primary/40"><?php echo htmlspecialchars(date('d M Y', strtotime($ev['date']))); ?></time>
                    </div>
                    <div class="text-[10px] text-primary/70 leading-relaxed"><?php echo htmlspecialchars($ev['desc']); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
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
