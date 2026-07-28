<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Assignments
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

$assignments = [
    ['title' => 'Sarf & Nahw Exercise 4', 'due' => '2026-06-30', 'grade' => 'Pending', 'status' => 'Submitted'],
    ['title' => 'Recitation Audio Recording of Surah Yaseen', 'due' => '2026-06-25', 'grade' => '94%', 'status' => 'Graded'],
    ['title' => 'Written translation of Al-Baqarah verses 10-20', 'due' => '2026-06-20', 'grade' => '88%', 'status' => 'Graded']
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
      <h1 class="text-2xl font-extrabold text-primary mt-3">Seeker Homework & Assignments</h1>
      <p class="text-xs text-primary/60 mt-0.5">Track submitted assignments, homework tasks, due dates, and individual grades.</p>
    </div>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-8">
      <div class="flex items-center justify-between mb-6 pb-2 border-b border-slate-50">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary">Academic Assignment Ledger</h3>
        <span class="bg-primary/10 text-primary font-bold text-xs px-3 py-1 rounded-full"><?php echo htmlspecialchars($student['name']); ?></span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Assignment Title</th>
              <th class="p-4">Due Date</th>
              <th class="p-4">Submission Status</th>
              <th class="p-4">Grade Received</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/85">
            <?php foreach ($assignments as $assign): 
              $badge = $assign['status'] === 'Submitted' ? 'bg-indigo-50 text-indigo-700' : 'bg-emerald-50 text-emerald-700';
            ?>
              <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="p-4 font-bold text-primary"><?php echo htmlspecialchars($assign['title']); ?></td>
                <td class="p-4 font-semibold text-slate-500"><?php echo htmlspecialchars($assign['due']); ?></td>
                <td class="p-4">
                  <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase <?php echo $badge; ?>"><?php echo htmlspecialchars($assign['status']); ?></span>
                </td>
                <td class="p-4 font-extrabold text-slate-700"><?php echo htmlspecialchars($assign['grade']); ?></td>
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
