<?php
/**
 * Al Foz Islamic Institute - Parent Fee Invoices Page
 */
require_once __DIR__ . '/includes/parent_context.php';

$currency = $active_child['currency'] ?? 'PKR';
$monthly_fee = floatval($active_child['monthly_fee'] ?? 4500);

$paid_count = 0;
$unpaid_count = 0;

foreach ($child_fees as $f) {
    $st = strtolower(trim($f['status'] ?? ''));
    if ($st === 'paid') $paid_count++;
    else $unpaid_count++;
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
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-primary/10 rounded-[24px] p-6 shadow-sm">
      <div>
        <div class="flex items-center gap-2 text-xs font-bold text-primary/50 uppercase tracking-widest mb-1">
          <a href="/parent/dashboard.php" class="hover:text-primary transition-all">Parent Portal</a>
          <span>/</span>
          <span class="text-primary">Fee Invoices</span>
        </div>
        <h1 class="text-2xl font-black text-primary tracking-tight uppercase">Tuition Fee Ledger & Online Invoices</h1>
      </div>

      <?php if(count($children) > 1): ?>
      <div class="flex items-center gap-2">
        <label class="text-xs font-bold text-primary/60 uppercase">Select Child:</label>
        <select onchange="window.location.href='?child_id='+this.value" class="px-3 py-2 bg-slate-50 border border-primary/20 text-xs font-bold rounded-xl text-primary">
          <?php foreach ($children as $c_id => $ch): ?>
            <option value="<?php echo $c_id; ?>" <?php echo ($active_child && $active_child['id'] == $ch['id']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($ch['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
    </div>

    <!-- Stats Row -->
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
        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
          <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Paid Invoices</span>
          <span class="text-2xl font-black text-emerald-700"><?php echo $paid_count; ?> Cleared</span>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl <?php echo $unpaid_count > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700'; ?> flex items-center justify-center shrink-0">
          <i data-lucide="alert-circle" class="w-6 h-6"></i>
        </div>
        <div>
          <span class="text-[10px] font-bold text-primary/50 uppercase tracking-wider block">Pending Due</span>
          <span class="text-2xl font-black <?php echo $unpaid_count > 0 ? 'text-rose-700' : 'text-slate-700'; ?>"><?php echo $unpaid_count; ?> Due</span>
        </div>
      </div>
    </div>

    <!-- Fee Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/50 mb-6 flex items-center gap-2">
        <i data-lucide="receipt" class="w-4 h-4 text-primary"></i> Fee Invoice Ledger for <?php echo htmlspecialchars($active_child['name'] ?? 'Ward'); ?>
      </h3>

      <?php if (!empty($child_fees)): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-primary/10 text-primary/50 uppercase tracking-wider text-[10px]">
                <th class="py-3 px-4 font-bold">Invoice #</th>
                <th class="py-3 px-4 font-bold">Due Date</th>
                <th class="py-3 px-4 font-bold">Amount</th>
                <th class="py-3 px-4 font-bold">Status</th>
                <th class="py-3 px-4 font-bold">Paid Date</th>
                <th class="py-3 px-4 font-bold text-right">Receipt</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-primary/5 font-medium">
              <?php foreach ($child_fees as $f): 
                $status = ucfirst($f['status'] ?? 'Paid');
                $status_bg = ($status === 'Paid') ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800';
              ?>
                <tr class="hover:bg-primary/5 transition-all">
                  <td class="py-3.5 px-4 font-bold text-primary font-mono">
                    <?php echo htmlspecialchars($f['invoice_no'] ?? $f['id'] ?? 'INV-1001'); ?>
                  </td>
                  <td class="py-3.5 px-4 text-primary/80">
                    <?php echo date('d M, Y', strtotime($f['due_date'] ?? 'now')); ?>
                  </td>
                  <td class="py-3.5 px-4 font-bold font-mono text-primary">
                    <?php echo htmlspecialchars($currency); ?> <?php echo number_format(floatval($f['amount'] ?? $monthly_fee)); ?>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 text-[10px] font-black rounded-md uppercase <?php echo $status_bg; ?>">
                      <?php echo $status; ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-primary/70">
                    <?php echo !empty($f['paid_date']) ? date('d M, Y', strtotime($f['paid_date'])) : '—'; ?>
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <button onclick="alert('Receipt Downloaded.')" class="px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg text-[10px] font-bold transition-all">
                      Official Receipt
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="p-12 text-center bg-slate-50 rounded-2xl border border-dashed border-primary/10">
          <i data-lucide="check-circle" class="w-10 h-10 text-emerald-500 mx-auto mb-3"></i>
          <h4 class="text-sm font-bold text-primary">Fee Payments Up To Date</h4>
          <p class="text-xs text-primary/60 mt-1">All tuition invoices have been cleared.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
