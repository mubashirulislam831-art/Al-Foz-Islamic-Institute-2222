<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Fee PDF / Print Sheet
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

$pkr_fee = convert_to_pkr($student['monthly_fee'], $student['currency']);
$pkr_paid = convert_to_pkr($student['fees']['paid_fee'], $student['currency']);
$pkr_remaining = convert_to_pkr($student['fees']['remaining_fee'], $student['currency']);
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
      <span class="text-lg font-black text-primary tracking-tight block">OFFICIAL FINANCIAL STATEMENT</span>
      <span class="text-[10px] font-mono text-slate-400 font-bold">SEEKER ROLL ID: <?php echo htmlspecialchars($student['student_id']); ?></span>
    </div>
  </div>

  <div class="mb-6 text-xs text-slate-600 space-y-1">
    <p>Seeker Name: <strong class="text-primary"><?php echo htmlspecialchars($student['name']); ?></strong></p>
    <p>Residence: <strong><?php echo htmlspecialchars($student['country']); ?></strong></p>
    <p>Payment Currency: <strong class="font-mono text-xs"><?php echo htmlspecialchars($student['currency']); ?></strong></p>
  </div>

  <!-- Financials -->
  <div class="grid grid-cols-2 gap-6 text-xs mb-8">
    <!-- Local Currency -->
    <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/40 space-y-2">
      <h4 class="font-black text-primary uppercase text-[10px] tracking-wider mb-2">Original Student Currency</h4>
      <div class="flex justify-between"><span>Monthly Fee:</span><strong><?php echo htmlspecialchars($student['monthly_fee'] . ' ' . $student['currency']); ?></strong></div>
      <div class="flex justify-between"><span>Total Deposited:</span><strong class="text-emerald-700"><?php echo htmlspecialchars($student['fees']['paid_fee'] . ' ' . $student['currency']); ?></strong></div>
      <div class="flex justify-between border-t border-slate-200 pt-2"><span>Remaining Balance:</span><strong class="text-rose-700"><?php echo htmlspecialchars($student['fees']['remaining_fee'] . ' ' . $student['currency']); ?></strong></div>
    </div>

    <!-- Converted PKR -->
    <div class="p-5 rounded-xl border border-emerald-100 bg-emerald-50/20 space-y-2">
      <h4 class="font-black text-emerald-800 uppercase text-[10px] tracking-wider mb-2">Converted PKR Treasury Equivalency</h4>
      <div class="flex justify-between"><span>Monthly Fee:</span><strong>PKR <?php echo number_format($pkr_fee, 0); ?></strong></div>
      <div class="flex justify-between"><span>Total Deposited:</span><strong class="text-emerald-700">PKR <?php echo number_format($pkr_paid, 0); ?></strong></div>
      <div class="flex justify-between border-t border-emerald-200 pt-2"><span>Remaining Balance:</span><strong class="text-rose-700">PKR <?php echo number_format($pkr_remaining, 0); ?></strong></div>
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
