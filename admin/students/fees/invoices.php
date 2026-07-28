<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Invoices
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

$inv_no = isset($_GET['inv']) ? sanitize_input($_GET['inv']) : ($student['fees']['history'][0]['invoice_no'] ?? 'INV-2026-000');
$inv_amount = $student['monthly_fee'];
$inv_date = date('Y-m-d');
$inv_status = 'Paid';

foreach ($student['fees']['history'] as $history) {
    if ($history['invoice_no'] === $inv_no) {
        $inv_amount = $history['amount'];
        $inv_date = $history['date'];
        $inv_status = $history['status'];
        break;
    }
}

$pkr_converted = convert_to_pkr($inv_amount, $student['currency']);
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <div class="mb-8 mt-4 no-print">
      <a href="student_fee.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Tuition Ledger
      </a>
    </div>

    <!-- Professional Invoice Paper -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-lg p-8 max-w-3xl mx-auto print-container">
      
      <!-- Top header branding -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-slate-100 gap-4">
        <div>
          <h1 class="text-xl font-black text-primary tracking-tight uppercase">AL FOZ ISLAMIC INSTITUTE</h1>
          <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Primary Global Treasury & Registrar</p>
        </div>
        <div class="text-left sm:text-right">
          <span class="text-2xl font-black text-primary tracking-tight block">OFFICIAL INVOICE</span>
          <span class="text-xs font-mono text-slate-500 font-bold"><?php echo htmlspecialchars($inv_no); ?></span>
        </div>
      </div>

      <!-- Sender & Receiver details -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-8 text-xs">
        <div>
          <h4 class="font-bold text-primary uppercase tracking-wider text-[10px] mb-2.5">Billing Issuer</h4>
          <p class="font-extrabold text-primary">Al Foz Islamic Institute</p>
          <p class="text-slate-500 mt-1">Registrar Office, Treasury Division</p>
          <p class="text-slate-500">Lahore Center, Punjab, Pakistan</p>
          <p class="text-slate-500 mt-1">Email: billing@alfoz.edu.pk</p>
        </div>
        <div>
          <h4 class="font-bold text-primary uppercase tracking-wider text-[10px] mb-2.5">Billed To (Seeker)</h4>
          <p class="font-extrabold text-primary"><?php echo htmlspecialchars($student['name']); ?></p>
          <p class="text-slate-500 mt-1">Roll ID: <?php echo htmlspecialchars($student['student_id']); ?></p>
          <p class="text-slate-500">Course Track: <?php echo htmlspecialchars($student['course']); ?></p>
          <p class="text-slate-500">Residence Country: <?php echo htmlspecialchars($student['country']); ?></p>
          <p class="text-slate-500 mt-1">WhatsApp: <?php echo htmlspecialchars($student['whatsapp']); ?></p>
        </div>
      </div>

      <!-- Financial particulars table -->
      <div class="border border-primary/10 rounded-xl overflow-hidden mb-8">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider text-[10px] border-b border-slate-100">
              <th class="p-4">Item Description</th>
              <th class="p-4 text-center">Currency Unit</th>
              <th class="p-4 text-right">Settled Total</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-700">
            <tr>
              <td class="p-4">
                <div class="font-bold text-primary">Academic Tuition Fee</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Monthly billing cycle - Course: <?php echo htmlspecialchars($student['course']); ?></div>
              </td>
              <td class="p-4 text-center font-mono font-bold"><?php echo htmlspecialchars($student['currency']); ?></td>
              <td class="p-4 text-right font-extrabold text-primary"><?php echo htmlspecialchars($inv_amount . ' ' . $student['currency']); ?></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Conversion breakdowns & sums -->
      <div class="flex justify-end text-xs mb-8">
        <div class="w-full sm:w-1/2 space-y-3">
          <div class="flex justify-between font-bold">
            <span class="text-slate-500">Invoice Amount:</span>
            <span class="text-primary"><?php echo htmlspecialchars($inv_amount . ' ' . $student['currency']); ?></span>
          </div>
          <?php if ($student['currency'] !== 'PKR'): ?>
            <div class="flex justify-between font-bold text-emerald-600 border-t border-slate-100 pt-3">
              <span>PKR Equivalency:</span>
              <span>PKR <?php echo number_format($pkr_converted, 2); ?></span>
            </div>
          <?php endif; ?>
          <div class="flex justify-between items-center bg-primary/5 p-3 rounded-xl border border-primary/10 mt-3">
            <span class="font-extrabold text-primary uppercase text-[10px]">Settlement Status:</span>
            <span class="px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-800 text-[9px] font-extrabold uppercase"><?php echo htmlspecialchars($inv_status); ?></span>
          </div>
        </div>
      </div>

      <!-- Signature Area -->
      <div class="flex justify-between items-end pt-12 border-t border-slate-100 text-xs">
        <div>
          <p class="text-slate-400">Date issued: <?php echo htmlspecialchars($inv_date); ?></p>
          <p class="text-slate-400 mt-1">Payment Method: Online treasury channel</p>
        </div>
        <div class="text-right">
          <div class="w-32 border-b border-slate-300 mx-auto mb-1.5 h-10 flex items-end justify-center">
            <span class="text-[10px] font-mono italic text-slate-400 font-bold">Al Foz Treasury Seal</span>
          </div>
          <p class="font-extrabold text-primary">Authorized Officer</p>
        </div>
      </div>

    </div>

    <!-- Print Action -->
    <div class="flex justify-center mt-6 no-print">
      <button onclick="window.print()" class="bg-primary text-white text-xs font-bold px-6 py-2.5 rounded-xl uppercase tracking-wider shadow-md hover:bg-opacity-95 transition-all">
        Print Invoice Receipt
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
  }
  .no-print {
    display: none !important;
  }
}
</style>
