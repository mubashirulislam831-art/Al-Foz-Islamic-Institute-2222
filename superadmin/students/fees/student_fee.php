<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Fees & Ledgers
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
    $amount_paid = floatval($_POST['amount_paid']);
    $payment_date = sanitize_input($_POST['payment_date']);
    $invoice_id = 'INV-2026-' . rand(200, 999);
    
    // Adjust financial parameters in Session
    $_SESSION['students'][$id]['fees']['paid_fee'] += $amount_paid;
    $_SESSION['students'][$id]['fees']['remaining_fee'] = max(0, $_SESSION['students'][$id]['fees']['remaining_fee'] - $amount_paid);
    
    if ($_SESSION['students'][$id]['fees']['remaining_fee'] === 0) {
        $_SESSION['students'][$id]['fee_status'] = 'Paid';
    }
    
    // Add to invoice history array
    $_SESSION['students'][$id]['fees']['history'][] = [
        'invoice_no' => $invoice_id,
        'amount' => $amount_paid,
        'date' => $payment_date,
        'status' => 'Paid'
    ];
    
    // Add timeline log
    $_SESSION['students'][$id]['timeline'][] = [
        'date' => date('Y-m-d'),
        'type' => 'Fee History',
        'title' => "Fee Payment Logged: $invoice_id",
        'desc' => "Tuition fee payment of " . $amount_paid . " " . $student['currency'] . " registered successfully."
    ];
    
    $success = true;
    $student = get_student_by_id($id); // refresh data
}

$pkr_converted = convert_to_pkr($student['monthly_fee'], $student['currency']);
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
      <h1 class="text-2xl font-extrabold text-primary mt-3">Tuition Ledger Control Board</h1>
      <p class="text-xs text-primary/60 mt-0.5">Manage tuition balances, track deposits, apply scholarship discounts, and convert currencies: <span class="font-extrabold text-primary"><?php echo htmlspecialchars($student['name']); ?></span></p>
    </div>

    <?php if ($success): ?>
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-4 rounded-xl mb-6 max-w-4xl">
        ✓ Fee deposit logged successfully! Student ledger updated in session.
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Left Column: Stats & Config -->
      <div class="lg:col-span-4 space-y-6">
        
        <!-- Billing Parameters -->
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
          <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-5 pb-2 border-b border-slate-50">Local Billing Parameters</h3>
          <div class="space-y-4 text-xs">
            <div class="flex justify-between">
              <span class="text-slate-500 font-semibold">Monthly Tuition Fee:</span>
              <span class="font-extrabold text-primary"><?php echo htmlspecialchars($student['monthly_fee'] . ' ' . $student['currency']); ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500 font-semibold">Cumulative Paid:</span>
              <span class="font-extrabold text-emerald-600"><?php echo htmlspecialchars($student['fees']['paid_fee'] . ' ' . $student['currency']); ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500 font-semibold">Pending Balance:</span>
              <span class="font-extrabold text-rose-600"><?php echo htmlspecialchars($student['fees']['remaining_fee'] . ' ' . $student['currency']); ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500 font-semibold">Discount Tier:</span>
              <span class="font-extrabold text-indigo-600"><?php echo htmlspecialchars($student['fees']['discount']); ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500 font-semibold">Scholarship Grant:</span>
              <span class="font-extrabold text-slate-600"><?php echo htmlspecialchars($student['fees']['scholarship']); ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500 font-semibold">Invoice Due Cycle:</span>
              <span class="font-extrabold text-rose-600"><?php echo htmlspecialchars($student['fees']['due_date']); ?></span>
            </div>
          </div>
        </div>

        <!-- PKR Multi Currency Engine -->
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 bg-emerald-50/20">
          <h3 class="text-xs font-black uppercase tracking-widest text-emerald-800 mb-4 pb-2 border-b border-emerald-100">PKR Currency conversion</h3>
          <div class="flex justify-between items-center text-xs">
            <div>
              <span class="block text-[9px] text-slate-400 font-bold uppercase">Original</span>
              <span class="text-base font-black text-primary"><?php echo htmlspecialchars($student['monthly_fee'] . ' ' . $student['currency']); ?></span>
            </div>
            <span class="text-lg font-bold text-slate-400">≈</span>
            <div class="text-right">
              <span class="block text-[9px] text-slate-400 font-bold uppercase">Converted PKR</span>
              <span class="text-base font-black text-emerald-700">PKR <?php echo number_format($pkr_converted, 0); ?></span>
            </div>
          </div>
          <span class="block text-[9px] text-slate-400 mt-3 italic leading-relaxed">System handles all currency detections and applies PKR conversion rates instantly for Al Foz Treasury.</span>
        </div>
      </div>

      <!-- Right Column: Ledger Entry Form & Payments -->
      <div class="lg:col-span-8 space-y-6">
        
        <!-- Entry Form -->
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
          <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-6 pb-2 border-b border-slate-50">Log Tuition Deposit</h3>
          
          <form action="student_fee.php?id=<?php echo $id; ?>" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-[10px] font-bold uppercase text-primary mb-2">Deposit Amount (In <?php echo htmlspecialchars($student['currency']); ?>) *</label>
              <input type="number" step="any" name="amount_paid" placeholder="Enter amount" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required>
            </div>
            <div>
              <label class="block text-[10px] font-bold uppercase text-primary mb-2">Settlement Date *</label>
              <input type="date" name="payment_date" value="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required>
            </div>
            <div class="sm:col-span-2">
              <button type="submit" class="w-full bg-primary text-white text-xs font-bold py-2.5 rounded-xl uppercase tracking-wider shadow-md hover:bg-opacity-95 transition-all">Submit Tuition Deposit</button>
            </div>
          </form>
        </div>

        <!-- Ledger History -->
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
          <div class="flex justify-between items-center mb-6 pb-2 border-b border-slate-50">
            <h3 class="text-xs font-black uppercase tracking-widest text-primary">Ledger Deposit Registry</h3>
            <a href="payment_history.php?id=<?php echo $id; ?>" class="text-[10px] text-indigo-600 hover:underline font-extrabold uppercase">Full History Ledger</a>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead>
                <tr class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider text-[10px]">
                  <th class="p-3">Invoice ID</th>
                  <th class="p-3">Amount Deposited</th>
                  <th class="p-3">Settlement Date</th>
                  <th class="p-3">Status</th>
                  <th class="p-3 text-right">Receipt Details</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-primary/5 text-primary/85">
                <?php if (empty($student['fees']['history'])): ?>
                  <tr>
                    <td colspan="5" class="p-4 text-center text-slate-400">No transactions recorded yet in database session.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($student['fees']['history'] as $invoice): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="p-3 font-mono font-bold"><?php echo htmlspecialchars($invoice['invoice_no']); ?></td>
                      <td class="p-3 font-extrabold"><?php echo htmlspecialchars($invoice['amount'] . ' ' . $student['currency']); ?></td>
                      <td class="p-3"><?php echo htmlspecialchars($invoice['date']); ?></td>
                      <td class="p-3">
                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[9px] font-bold uppercase"><?php echo htmlspecialchars($invoice['status']); ?></span>
                      </td>
                      <td class="p-3 text-right">
                        <a href="invoices.php?id=<?php echo $id; ?>&inv=<?php echo $invoice['invoice_no']; ?>" class="text-[10px] bg-primary/5 hover:bg-primary/10 text-primary px-3 py-1 rounded font-bold">Invoicing Receipt</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
