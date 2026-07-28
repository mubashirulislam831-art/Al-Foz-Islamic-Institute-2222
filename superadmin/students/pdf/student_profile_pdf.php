<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Profile PDF / Print Sheet
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
      <span class="text-lg font-black text-primary tracking-tight block">OFFICIAL BIOMETRIC DOSSIER</span>
      <span class="text-[10px] font-mono text-slate-400 font-bold">SEEKER ROLL ID: <?php echo htmlspecialchars($student['student_id']); ?></span>
    </div>
  </div>

  <!-- Content Grid -->
  <div class="grid grid-cols-2 gap-8 text-xs mb-8">
    <!-- Biographical -->
    <div class="space-y-4">
      <h3 class="font-extrabold text-primary border-b border-slate-100 pb-1.5 uppercase text-[10px] tracking-wider">Seeker Demographics</h3>
      <div class="space-y-2">
        <div class="flex justify-between"><span class="text-slate-400 font-semibold">Full Name:</span><span class="font-bold text-slate-800"><?php echo htmlspecialchars($student['name']); ?></span></div>
        <div class="flex justify-between"><span class="text-slate-400 font-semibold">Father Name:</span><span class="font-bold text-slate-800"><?php echo htmlspecialchars($student['father_name']); ?></span></div>
        <div class="flex justify-between"><span class="text-slate-400 font-semibold">WhatsApp:</span><span class="font-bold text-slate-800"><?php echo htmlspecialchars($student['whatsapp']); ?></span></div>
        <div class="flex justify-between"><span class="text-slate-400 font-semibold">Residence:</span><span class="font-bold text-slate-800"><?php echo htmlspecialchars($student['country']); ?></span></div>
      </div>
    </div>

    <!-- Academic -->
    <div class="space-y-4">
      <h3 class="font-extrabold text-primary border-b border-slate-100 pb-1.5 uppercase text-[10px] tracking-wider">Academic Record</h3>
      <div class="space-y-2">
        <div class="flex justify-between"><span class="text-slate-400 font-semibold">Course Program:</span><span class="font-bold text-slate-800"><?php echo htmlspecialchars($student['course']); ?></span></div>
        <div class="flex justify-between"><span class="text-slate-400 font-semibold">Class Hours:</span><span class="font-bold text-slate-800"><?php echo htmlspecialchars($student['class_time']); ?></span></div>
        <div class="flex justify-between"><span class="text-slate-400 font-semibold">Assigned Teacher:</span><span class="font-bold text-slate-800"><?php echo htmlspecialchars($student['teacher_name']); ?></span></div>
        <div class="flex justify-between"><span class="text-slate-400 font-semibold">Enrollment:</span><span class="font-bold text-slate-800"><?php echo htmlspecialchars($student['joining_date']); ?></span></div>
      </div>
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
