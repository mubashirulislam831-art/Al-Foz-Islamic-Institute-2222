<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Certificates
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

$cert_no = 'AF-CERT-2026-' . rand(1000, 9999);
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <div class="mb-8 mt-4 no-print">
      <a href="../student_profile.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Profile
      </a>
    </div>

    <!-- Professional Certificate Paper -->
    <div class="bg-white rounded-3xl border-8 border-double border-primary shadow-xl p-12 max-w-4xl mx-auto text-center relative overflow-hidden print-container bg-[radial-gradient(#F7FAFF_1px,transparent_1px)] bg-[size:16px_16px]">
      
      <!-- Golden Accent Ornaments -->
      <div class="absolute top-0 left-0 w-32 h-32 border-t-4 border-l-4 border-primary/40 m-6"></div>
      <div class="absolute top-0 right-0 w-32 h-32 border-t-4 border-r-4 border-primary/40 m-6"></div>
      <div class="absolute bottom-0 left-0 w-32 h-32 border-b-4 border-l-4 border-primary/40 m-6"></div>
      <div class="absolute bottom-0 right-0 w-32 h-32 border-b-4 border-r-4 border-primary/40 m-6"></div>

      <div class="space-y-8 relative z-10 py-6">
        <div>
          <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary block mb-3">CERTIFICATE OF RECITATION HONOUR</span>
          <h1 class="text-3xl font-extrabold text-primary tracking-tight uppercase">AL FOZ ISLAMIC INSTITUTE</h1>
          <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1.5">Lahore, Punjab, Pakistan</p>
        </div>

        <div class="py-4">
          <p class="text-xs italic text-slate-500 font-serif text-lg">This official administrative seal verifies that</p>
          <h2 class="text-3xl font-black text-primary tracking-tight mt-3 font-serif italic"><?php echo htmlspecialchars($student['name']); ?></h2>
          <div class="w-48 h-0.5 bg-primary text-white mx-auto my-3"></div>
          <p class="text-[11px] text-slate-400 uppercase font-black font-mono tracking-wider">Seeker Roll ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
        </div>

        <div class="max-w-2xl mx-auto">
          <p class="text-sm text-slate-600 leading-relaxed font-serif italic">
            has successfully completed the vetted scholastic criteria, verbal reviews, and formal written/oral testing procedures for the course track program:
          </p>
          <h3 class="text-xl font-extrabold text-primary tracking-tight mt-3 uppercase"><?php echo htmlspecialchars($student['course']); ?></h3>
          <p class="text-xs text-slate-500 mt-2">under the primary academic guidance of assigned teacher <span class="font-bold text-primary"><?php echo htmlspecialchars($student['teacher_name']); ?></span>.</p>
        </div>

        <div class="grid grid-cols-3 gap-6 pt-12 items-end">
          <div class="text-left text-xs text-slate-400">
            <p>Verification ID: <span class="font-mono font-bold text-slate-600"><?php echo htmlspecialchars($cert_no); ?></span></p>
            <p class="mt-1">Date: <?php echo date('Y-m-d'); ?></p>
          </div>
          <div class="flex justify-center">
            <!-- Mock Golden Seal -->
            <div class="w-20 h-20 rounded-full border-4 border-primary bg-yellow-50/10 flex items-center justify-center relative shadow-inner">
              <span class="text-[9px] font-black text-primary text-center tracking-tighter leading-none block uppercase m-1">AL FOZ<br>TREASURY<br>SEAL</span>
            </div>
          </div>
          <div class="text-right text-xs">
            <div class="w-32 border-b border-slate-300 ml-auto mb-1 h-8 flex items-end justify-center">
              <span class="text-[10px] italic font-serif text-slate-400 font-bold">Sumera Tabassum</span>
            </div>
            <p class="font-extrabold text-primary">Registrar General</p>
          </div>
        </div>
      </div>

    </div>

    <!-- Print Action -->
    <div class="flex justify-center mt-8 no-print">
      <button onclick="window.print()" class="bg-primary text-white text-xs font-bold px-8 py-3 rounded-xl uppercase tracking-wider shadow-md hover:bg-opacity-95 transition-all">
        Print Certificate PDF
      </button>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  .print-container, .print-container * {
    visibility: visible;
  }
  .print-container {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
  }
  .no-print {
    display: none !important;
  }
}
</style>
