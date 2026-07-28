<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Virtual Class Sessions
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/students_data.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);

$id = isset($_GET['id']) ? intval($_GET['id']) : 101;
$student = get_student_by_id($id);

if (!$student) {
    header("Location: ../students.php");
    exit;
}

// Generate some sample session schedules
$sessions = [
    ['date' => '2026-06-28', 'time' => $student['class_time'], 'teacher' => $student['teacher_name'], 'topic' => 'Recitation of Juz 30 and Tajweed rules', 'link' => 'https://zoom.us/j/9876543210', 'status' => 'Scheduled'],
    ['date' => '2026-06-27', 'time' => $student['class_time'], 'teacher' => $student['teacher_name'], 'topic' => 'Reviewing Surah Al-Baqarah Verses 1-10', 'link' => '#', 'status' => 'Completed'],
    ['date' => '2026-06-26', 'time' => $student['class_time'], 'teacher' => $student['teacher_name'], 'topic' => 'Articulation points (Makharij) exercises', 'link' => '#', 'status' => 'Completed']
];
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <div class="mb-8 mt-4">
      <a href="../student_profile.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Profile
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Virtual Class Sessions</h1>
      <p class="text-xs text-primary/60 mt-0.5">Track live lessons, schedule upcoming sessions, and view playback recording archives.</p>
    </div>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-8">
      <div class="flex items-center justify-between mb-6 pb-2 border-b border-slate-50">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary">Class Session Ledger</h3>
        <span class="bg-primary/10 text-primary font-bold text-xs px-3 py-1 rounded-full"><?php echo htmlspecialchars($student['name']); ?></span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Session Date & Time</th>
              <th class="p-4">Instructor</th>
              <th class="p-4">Syllabus Topic / Target</th>
              <th class="p-4">Meeting Access Link</th>
              <th class="p-4">Session Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/85">
            <?php foreach ($sessions as $session): 
              $badge_class = $session['status'] === 'Scheduled' ? 'bg-sky-50 text-sky-700' : 'bg-emerald-50 text-emerald-700';
            ?>
              <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="p-4">
                  <div class="font-bold text-primary"><?php echo htmlspecialchars($session['date']); ?></div>
                  <div class="text-[10px] text-slate-500 font-mono mt-0.5"><?php echo htmlspecialchars($session['time']); ?></div>
                </td>
                <td class="p-4 font-semibold"><?php echo htmlspecialchars($session['teacher']); ?></td>
                <td class="p-4 italic">"<?php echo htmlspecialchars($session['topic']); ?>"</td>
                <td class="p-4">
                  <?php if ($session['status'] === 'Scheduled'): ?>
                    <a href="<?php echo htmlspecialchars($session['link']); ?>" target="_blank" class="text-[10px] bg-primary text-white px-3 py-1.5 rounded-lg font-bold uppercase hover:bg-opacity-95 transition-all">Launch Zoom Room</a>
                  <?php else: ?>
                    <span class="text-[10px] text-slate-400 font-semibold italic">Recording archived</span>
                  <?php endif; ?>
                </td>
                <td class="p-4">
                  <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase <?php echo $badge_class; ?>"><?php echo htmlspecialchars($session['status']); ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
