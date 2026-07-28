<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Payment History Log
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
      <a href="student_fee.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Tuition Ledger
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Ledger Deposit Registry</h1>
      <p class="text-xs text-primary/60 mt-0.5">Chronological list of tuition payments, scholarships, and active balances logged for: <span class="font-extrabold text-primary"><?php echo htmlspecialchars($student['name']); ?></span></p>
    </div>

    <!-- Payment history board -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
      <div class="flex items-center justify-between mb-6 pb-2 border-b border-slate-50">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary">Deposit Logs</h3>
        <span class="bg-emerald-50 text-emerald-800 text-xs px-3 py-1 rounded-full font-bold uppercase">Multi-Currency Vetted</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Invoice Number</th>
              <th class="p-4">Deposit Date</th>
              <th class="p-4">Amount Deposited</th>
              <th class="p-4">PKR Equivalent</th>
              <th class="p-4">Status</th>
              <th class="p-4 text-right">Invoice Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/85">
            <?php if (empty($student['fees']['history'])): ?>
              <tr>
                <td colspan="6" class="p-8 text-center text-slate-400">No deposits recorded yet.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($student['fees']['history'] as $invoice): 
                $pkr_equivalent = convert_to_pkr($invoice['amount'], $student['currency']);
              ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="p-4 font-mono font-bold text-sm text-primary"><?php echo htmlspecialchars($invoice['invoice_no']); ?></td>
                  <td class="p-4 font-semibold"><?php echo htmlspecialchars($invoice['date']); ?></td>
                  <td class="p-4 font-black"><?php echo htmlspecialchars($invoice['amount'] . ' ' . $student['currency']); ?></td>
                  <td class="p-4 font-extrabold text-emerald-700">PKR <?php echo number_format($pkr_equivalent, 0); ?></td>
                  <td class="p-4">
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[9px] font-bold uppercase"><?php echo htmlspecialchars($invoice['status']); ?></span>
                  </td>
                  <td class="p-4 text-right">
                    <a href="invoices.php?id=<?php echo $id; ?>&inv=<?php echo $invoice['invoice_no']; ?>" class="text-[10px] bg-primary text-white px-4 py-1.5 rounded-lg font-bold uppercase hover:bg-opacity-95 transition-all">View Receipt</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
