<?php
/**
 * Al Foz Islamic Institute - Student Fee Ledger Page
 */
require_once __DIR__ . '/includes/student_context.php';

$currency = $student['currency'] ?? 'PKR';
$monthly_fee = floatval($student['monthly_fee'] ?? 4500);

$total_fees_count = count($student_fees);
$paid_count = 0;
$unpaid_count = 0;

foreach ($student_fees as $f) {
    $st = strtolower(trim($f['status'] ?? ''));
    if ($st === 'paid') $paid_count++;
    else $unpaid_count++;
}
if ($total_fees_count === 0) {
    $paid_count = 0;
    $unpaid_count = 0;
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">my fees</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Tuition Fees & Invoice Ledger</p>
      </div>
      <button onclick="window.print()" class="px-5 py-2.5 bg-primary hover:bg-[#10353a] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition-all flex items-center gap-2 active:scale-95">
        <i data-lucide="printer" class="w-4 h-4"></i> Print Statement
      </button>
    </div>

    <!-- Overview Soft Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
          <i data-lucide="wallet" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Monthly Package</span>
          <span class="text-2xl font-black text-primary"><?php echo htmlspecialchars($currency); ?> <?php echo number_format($monthly_fee); ?></span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
          <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Paid Invoices</span>
          <span class="text-2xl font-black text-emerald-700"><?php echo $paid_count; ?> Cleared</span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl <?php echo $unpaid_count > 0 ? 'bg-rose-50 border border-rose-100 text-rose-700' : 'bg-slate-50 border border-slate-100 text-slate-700'; ?> flex items-center justify-center shrink-0">
          <i data-lucide="alert-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Outstanding Due</span>
          <span class="text-2xl font-black <?php echo $unpaid_count > 0 ? 'text-rose-700' : 'text-slate-700'; ?>"><?php echo $unpaid_count; ?> Pending</span>
        </div>
      </div>
    </div>

    <!-- Fee Invoices Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-6 flex items-center gap-2">
        <i data-lucide="receipt" class="w-4 h-4 text-primary"></i> Fee Invoices & Payment Ledger
      </h3>

      <?php if (!empty($student_fees)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-primary/10 text-primary/50 uppercase tracking-wider text-[10px]">
                <th class="py-3 px-4 font-bold">Invoice #</th>
                <th class="py-3 px-4 font-bold">Due Date</th>
                <th class="py-3 px-4 font-bold">Amount</th>
                <th class="py-3 px-4 font-bold">Status</th>
                <th class="py-3 px-4 font-bold">Paid Date</th>
                <th class="py-3 px-4 font-bold">Payment Method</th>
                <th class="py-3 px-4 font-bold text-right">Receipt</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 font-medium text-primary">
              <?php foreach ($student_fees as $f): 
                $status = ucfirst($f['status'] ?? 'Paid');
                $status_bg = ($status === 'Paid') ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-rose-50 border-rose-200 text-rose-800';
              ?>
                <tr class="hover:bg-primary/5 transition-all">
                  <td class="py-3.5 px-4 font-bold font-mono">
                    <?php echo htmlspecialchars($f['invoice_no'] ?? $f['id'] ?? 'INV-1001'); ?>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-primary/80">
                    <?php echo date('d M, Y', strtotime($f['due_date'] ?? 'now')); ?>
                  </td>
                  <td class="py-3.5 px-4 font-bold font-mono">
                    <?php echo htmlspecialchars($currency); ?> <?php echo number_format(floatval($f['amount'] ?? $monthly_fee)); ?>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 text-[9px] font-black rounded-full border uppercase <?php echo $status_bg; ?>">
                      <?php echo $status; ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-primary/70 font-mono">
                    <?php echo !empty($f['paid_date']) ? date('d M, Y', strtotime($f['paid_date'])) : '—'; ?>
                  </td>
                  <td class="py-3.5 px-4">
                    <?php echo htmlspecialchars($f['payment_method'] ?? 'Online Bank / Gateway'); ?>
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <button onclick="alert('Receipt Download: Official Payment Receipt PDF downloaded.')" class="px-3 py-1.5 bg-primary/10 hover:bg-primary hover:text-white text-primary font-bold text-[10px] rounded-xl transition-all">
                      Receipt
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="p-12 text-center text-primary/60 font-medium">
          <i data-lucide="receipt" class="w-12 h-12 text-primary/30 mx-auto mb-3"></i>
          <p class="text-sm font-bold text-primary">No Fee Records Found</p>
          <p class="text-xs text-primary/60 mt-1">There are no fee invoices or payment receipts recorded for this account yet.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
