<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Exam Results Overview
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

$status_badge = $student['exams']['status'] === 'Passed' ? 'bg-emerald-50 text-emerald-800 border-emerald-100' : 'bg-rose-50 text-rose-800 border-rose-100';
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
      <h1 class="text-2xl font-extrabold text-primary mt-3">Seeker Examination Results</h1>
      <p class="text-xs text-primary/60 mt-0.5">Comprehensive grade card summarizing written scores, oral recitations, and overall promotional approvals.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Big Score Banner -->
      <div class="lg:col-span-4 bg-white rounded-2xl border border-primary/10 shadow-sm p-6 space-y-4">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-4 pb-2 border-b border-slate-50">Aggregate index</h3>
        <div class="p-6 rounded-xl border border-primary/10 bg-primary/5 text-center">
          <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Average score</span>
          <span class="text-4xl font-black text-primary"><?php echo $student['exams']['total']; ?>%</span>
        </div>
        
        <div class="flex justify-between items-center bg-white p-3 border border-slate-100 rounded-xl">
          <span class="text-xs font-bold text-slate-500">Term Clearance:</span>
          <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase border <?php echo $status_badge; ?>"><?php echo htmlspecialchars($student['exams']['status']); ?></span>
        </div>
      </div>

      <!-- Specific Sub-Grades -->
      <div class="lg:col-span-8 bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-6 pb-2 border-b border-slate-50">Sub-Grade Breakdowns</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <!-- Oral -->
          <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/20 space-y-3">
            <h4 class="font-bold text-primary text-xs uppercase tracking-wider">Oral Examination average</h4>
            <div class="flex justify-between items-baseline">
              <span class="text-slate-400 text-xs">Graded Percentage:</span>
              <span class="text-xl font-black text-primary"><?php echo $student['exams']['oral']; ?>%</span>
            </div>
            <p class="text-[10px] text-slate-400 italic">Focuses on Quranic pronunciation rules, Tajweed rules, and general memorization metrics.</p>
          </div>

          <!-- Written -->
          <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/20 space-y-3">
            <h4 class="font-bold text-primary text-xs uppercase tracking-wider">Written Examination average</h4>
            <div class="flex justify-between items-baseline">
              <span class="text-slate-400 text-xs">Graded Percentage:</span>
              <span class="text-xl font-black text-primary"><?php echo $student['exams']['written']; ?>%</span>
            </div>
            <p class="text-[10px] text-slate-400 italic">Assesses vocabulary, word-to-word literal translation, grammar rules (Sarf/Nahw), and fiqh fundamentals.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
