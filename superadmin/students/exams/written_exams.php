<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Written Exams
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

$id = isset($_GET['id']) ? intval($_GET['id']) : 101;
$student = get_student_by_id($id);

if (!$student) {
    header("Location: ../students.php");
    exit;
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grammar = floatval($_POST['grammar']);
    $fiqh = floatval($_POST['fiqh']);
    $translation = floatval($_POST['translation']);
    $notes = sanitize_input($_POST['notes']);
    
    // Calculate written aggregate
    $written_total = round(($grammar + $fiqh + $translation) / 3, 1);
    
    // Update student written exam score in Session
    $_SESSION['students'][$id]['exams']['written'] = $written_total;
    
    // Calculate total score average if oral exists
    $oral = $_SESSION['students'][$id]['exams']['oral'];
    $_SESSION['students'][$id]['exams']['total'] = round(($oral + $written_total) / 2, 1);
    
    // Determine status
    if ($_SESSION['students'][$id]['exams']['total'] >= 60) {
        $_SESSION['students'][$id]['exams']['status'] = 'Passed';
    } else {
        $_SESSION['students'][$id]['exams']['status'] = 'Failed';
    }
    
    // Log timeline
    $_SESSION['students'][$id]['timeline'][] = [
        'date' => date('Y-m-d'),
        'type' => 'Exams Matrix',
        'title' => 'Written Examination Graded',
        'desc' => "Graded $written_total% average (Grammar: $grammar, Fiqh: $fiqh, Translation: $translation). Remarks: $notes"
    ];
    
    $success = true;
    $student = get_student_by_id($id); // refresh data
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
      <h1 class="text-2xl font-extrabold text-primary mt-3">Written Exams Center</h1>
      <p class="text-xs text-primary/60 mt-0.5">Grade Quranic translation, Arabic grammar rules, and Fiqh fundamentals: <span class="font-extrabold text-primary"><?php echo htmlspecialchars($student['name']); ?></span></p>
    </div>

    <?php if ($success): ?>
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-4 rounded-xl mb-6 max-w-xl">
        ✓ Written exam scores saved! Seeker's total index aggregate updated.
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Grading Form -->
      <div class="lg:col-span-7 bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-6 pb-2 border-b border-slate-50">Log Written Assessment</h3>
        
        <form action="written_exams.php?id=<?php echo $id; ?>" method="POST" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-[10px] font-bold uppercase text-primary mb-2">Arabic Grammar *</label>
              <input type="number" step="any" min="0" max="100" name="grammar" placeholder="Score (0-100)" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required>
              <span class="text-[9px] text-slate-400 mt-1 block">Sarf & Nahw rules.</span>
            </div>
            <div>
              <label class="block text-[10px] font-bold uppercase text-primary mb-2">Fiqh Basics *</label>
              <input type="number" step="any" min="0" max="100" name="fiqh" placeholder="Score (0-100)" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required>
              <span class="text-[9px] text-slate-400 mt-1 block">Islamic Jurisprudence.</span>
            </div>
            <div>
              <label class="block text-[10px] font-bold uppercase text-primary mb-2">Quran Translation *</label>
              <input type="number" step="any" min="0" max="100" name="translation" placeholder="Score (0-100)" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required>
              <span class="text-[9px] text-slate-400 mt-1 block">Word-to-word literal translation.</span>
            </div>
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">Examiner Remarks *</label>
            <textarea name="notes" placeholder="Write qualitative details concerning spelling, comprehensive translation retention, or syntax rules..." rows="3" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required></textarea>
          </div>
          
          <button type="submit" class="w-full bg-primary text-white text-xs font-bold py-2.5 rounded-xl uppercase tracking-wider shadow-md hover:bg-opacity-95 transition-all">Record Written Grades</button>
        </form>
      </div>

      <!-- Stats Summary -->
      <div class="lg:col-span-5 bg-white rounded-2xl border border-primary/10 shadow-sm p-6 flex flex-col justify-between">
        <div>
          <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-5 pb-2 border-b border-slate-50">Current Written Matrix</h3>
          <div class="space-y-4 text-xs">
            <div class="flex justify-between items-center bg-primary/5 p-4 rounded-xl">
              <span class="font-bold text-slate-600">Current Written Average Grade:</span>
              <span class="text-lg font-black text-primary"><?php echo $student['exams']['written']; ?>%</span>
            </div>
          </div>
        </div>
        <p class="text-[11px] text-slate-400 italic leading-relaxed bg-slate-50 p-4 rounded-xl mt-6 border border-slate-100">"Written evaluations check academic, logical, and theological comprehension. Combined with recitation fluency, they determine passing criteria."</p>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
