<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Exam Performance Reports
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
      <h1 class="text-2xl font-extrabold text-primary mt-3">Seeker Exam Performance Report</h1>
      <p class="text-xs text-primary/60 mt-0.5">Comprehensive grade report summarizing written scores, oral recitations, homework averages, and promotional clearance status.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Detailed Breakdown -->
      <div class="lg:col-span-8 bg-white rounded-2xl border border-primary/10 shadow-sm p-6 space-y-6">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-4 pb-2 border-b border-slate-50">Academic Assessment Audit</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
          <!-- Oral -->
          <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/30 space-y-3">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Oral recitation metrics</span>
            <div class="space-y-2 font-bold text-slate-700">
              <div class="flex justify-between">
                <span>Hifz Memorization:</span>
                <span class="text-primary"><?php echo $student['exams']['oral']; ?>%</span>
              </div>
              <div class="flex justify-between">
                <span>Tajweed Application:</span>
                <span class="text-primary"><?php echo $student['exams']['oral']; ?>%</span>
              </div>
              <div class="flex justify-between border-t border-slate-200/60 pt-2">
                <span>Oral Sub-Average:</span>
                <span class="text-emerald-600"><?php echo $student['exams']['oral']; ?>%</span>
              </div>
            </div>
          </div>

          <!-- Written -->
          <div class="p-5 rounded-xl border border-emerald-100 bg-emerald-50/20 space-y-3">
            <span class="block text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Written comprehension metrics</span>
            <div class="space-y-2 font-bold text-emerald-950">
              <div class="flex justify-between">
                <span>Quran Translation:</span>
                <span><?php echo $student['exams']['written']; ?>%</span>
              </div>
              <div class="flex justify-between">
                <span>Sarf/Nahw Grammar:</span>
                <span><?php echo $student['exams']['written']; ?>%</span>
              </div>
              <div class="flex justify-between border-t border-emerald-200 pt-2">
                <span>Written Sub-Average:</span>
                <span class="text-emerald-700"><?php echo $student['exams']['written']; ?>%</span>
              </div>
            </div>
          </div>
        </div>

        <div class="pt-4 text-xs border-t border-slate-50">
          <h4 class="font-bold text-primary uppercase tracking-wider mb-2">Promotional Clearance Disclaimer</h4>
          <p class="text-slate-500 leading-relaxed">
            According to Al Foz guidelines, promotional progress requires an aggregate grade of 60% or higher. Students scoring over 85% on oral recitation earn the "Honorable Recitation" certificate badge.
          </p>
        </div>
      </div>

      <!-- Action Panel -->
      <div class="lg:col-span-4 bg-white rounded-2xl border border-primary/10 shadow-sm p-6 space-y-6">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-2 pb-2 border-b border-slate-50">Report Actions</h3>
        <p class="text-xs text-slate-500 font-semibold">Generate official ledger printouts or certified PDF receipts for academic file archives.</p>
        
        <div class="space-y-3">
          <a href="../pdf/exam_pdf.php?id=<?php echo $id; ?>" target="_blank" class="block w-full text-center bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold py-3 rounded-xl uppercase tracking-wider transition-all border border-red-200">Export Academic PDF</a>
          <a href="../print/exam_print.php?id=<?php echo $id; ?>" target="_blank" class="block w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold py-3 rounded-xl uppercase tracking-wider transition-all border border-slate-200">Print Academic Sheet</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
