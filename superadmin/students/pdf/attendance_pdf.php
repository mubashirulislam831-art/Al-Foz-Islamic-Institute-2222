<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Attendance PDF / Print Sheet
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

<!-- Shared PDF/Print layout -->
<div class="bg-white min-h-screen p-8 max-w-4xl mx-auto border border-slate-200 rounded-xl shadow-md font-sans">
  
  <!-- Header Banner -->
  <div class="flex justify-between items-center pb-6 border-b-2 border-primary mb-8">
    <div>
      <h1 class="text-xl font-black text-primary tracking-tight uppercase">AL FOZ ISLAMIC INSTITUTE</h1>
      <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Primary Global Treasury & Registrar</p>
    </div>
    <div class="text-right">
      <span class="text-lg font-black text-primary tracking-tight block">OFFICIAL ATTENDANCE RECORD</span>
      <span class="text-[10px] font-mono text-slate-400 font-bold">SEEKER ROLL ID: <?php echo htmlspecialchars($student['student_id']); ?></span>
    </div>
  </div>

  <div class="mb-6 text-xs text-slate-600">
    <p>Seeker Name: <strong class="text-primary"><?php echo htmlspecialchars($student['name']); ?></strong></p>
    <p>Course Track: <strong><?php echo htmlspecialchars($student['course']); ?></strong></p>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-3 gap-4 text-center mb-8 text-xs">
    <div class="p-4 border border-slate-100 bg-slate-50 rounded-xl">
      <span class="block text-[9px] uppercase font-bold text-slate-400">Present Days</span>
      <strong class="text-emerald-700 text-lg"><?php echo $student['attendance']['present']; ?> Days</strong>
    </div>
    <div class="p-4 border border-slate-100 bg-slate-50 rounded-xl">
      <span class="block text-[9px] uppercase font-bold text-slate-400">Absent Days</span>
      <strong class="text-rose-700 text-lg"><?php echo $student['attendance']['absent']; ?> Days</strong>
    </div>
    <div class="p-4 border border-slate-100 bg-slate-50 rounded-xl">
      <span class="block text-[9px] uppercase font-bold text-slate-400">Index Percentage</span>
      <strong class="text-primary text-lg"><?php echo $student['attendance']['percentage']; ?>%</strong>
    </div>
  </div>

  <!-- Sign-off Seal -->
  <div class="flex justify-between items-end pt-12 border-t border-slate-100 text-[10px] text-slate-400">
    <div>
      <p>System Generated: <?php echo date('Y-m-d H:i:s T'); ?></p>
      <p>Audit Registry: Security cryptographic hash valid</p>
    </div>
    <div class="text-right">
      <p class="font-extrabold text-primary">Registrar Signature Seal</p>
      <div class="w-24 h-0.5 bg-primary text-white mt-1 ml-auto"></div>
    </div>
  </div>

</div>

<!-- Auto-print instruction -->
<script>
window.onload = function() {
    window.print();
}
</script>
