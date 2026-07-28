<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Attendance Reports
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
      <h1 class="text-2xl font-extrabold text-primary mt-3">Seeker Attendance Reports</h1>
      <p class="text-xs text-primary/60 mt-0.5">Generate, audit, and analyze detailed session log indices for: <span class="font-extrabold text-primary"><?php echo htmlspecialchars($student['name']); ?></span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Big Stat Overview -->
      <div class="lg:col-span-8 bg-white rounded-2xl border border-primary/10 shadow-sm p-6 space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-50">
          <h3 class="text-xs font-black uppercase tracking-widest text-primary">Attendance Log Analysis</h3>
          <span class="text-xs font-bold text-primary">Current Term</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
          <div class="p-5 rounded-xl border border-primary/10 bg-primary/5">
            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Index percentage</span>
            <span class="text-3xl font-black text-primary"><?php echo $student['attendance']['percentage']; ?>%</span>
          </div>
          <div class="p-5 rounded-xl border border-emerald-100 bg-emerald-50/20">
            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Present Days</span>
            <span class="text-3xl font-black text-emerald-700"><?php echo $student['attendance']['present']; ?> / <?php echo $student['attendance']['present'] + $student['attendance']['absent'] + $student['attendance']['leave']; ?></span>
          </div>
          <div class="p-5 rounded-xl border border-rose-100 bg-rose-50/20">
            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Absences Logged</span>
            <span class="text-3xl font-black text-rose-700"><?php echo $student['attendance']['absent']; ?></span>
          </div>
        </div>

        <div class="pt-4">
          <h4 class="font-bold text-xs text-primary uppercase tracking-wider mb-3">Academic Performance Assessment</h4>
          <p class="text-xs text-slate-500 leading-relaxed">
            Attendance rate is currently at <span class="font-bold text-primary"><?php echo $student['attendance']['percentage']; ?>%</span>. 
            According to Al Foz Islamic Institute guidelines, a minimum of 80% attendance is strictly required for promotion to advanced Juz/Tajweed levels. 
            The student is currently <span class="text-emerald-600 font-bold uppercase">Fully Compliant</span> with session regulations.
          </p>
        </div>
      </div>

      <!-- Action Panel -->
      <div class="lg:col-span-4 bg-white rounded-2xl border border-primary/10 shadow-sm p-6 space-y-6">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-2 pb-2 border-b border-slate-50">Report Actions</h3>
        <p class="text-xs text-slate-500">Generate printable worksheets or permanent PDF files for administrative filings or parental deliveries.</p>
        
        <div class="space-y-3">
          <a href="../pdf/attendance_pdf.php?id=<?php echo $id; ?>" target="_blank" class="block w-full text-center bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold py-3 rounded-xl uppercase tracking-wider transition-all border border-red-200">Export Attendance PDF</a>
          <a href="../print/attendance_print.php?id=<?php echo $id; ?>" target="_blank" class="block w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold py-3 rounded-xl uppercase tracking-wider transition-all border border-slate-200">Print Attendance Sheet</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
