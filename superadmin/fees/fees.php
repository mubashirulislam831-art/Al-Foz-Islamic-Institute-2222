<?php
/**
 * Al Foz Islamic Institute - Super Admin Fee Ledger
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

$students = get_all_students();
$total_collection = 0;
$pending_receivables = 0;

foreach ($students as $s) {
    $fee_pkr = convert_to_pkr($s['monthly_fee'], $s['currency']);
    if (isset($s['fee_status']) && $s['fee_status'] === 'Paid') {
        $total_collection += $fee_pkr;
    } else {
        $pending_receivables += $fee_pkr;
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Fee Ledger</h1>
        <p class="text-xs text-primary/60 mt-1">Manage seeker billing accounts, invoices, and general currency conversions.</p>
      </div>
      <div class="flex gap-2">
        <a href="invoices.php" class="bg-primary hover:bg-opacity-95 text-secondary px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
          Generate Invoice
        </a>
        <a href="currency_system.php" class="border border-primary text-primary hover:bg-primary hover:text-secondary px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
          Currency Converter
        </a>
      </div>
    </div>

    <!-- Quick Revenue Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm">
        <h3 class="text-xs font-bold text-primary/50 uppercase tracking-wider">Total Collection (This Month)</h3>
        <p class="text-2xl font-extrabold mt-2"><?php echo format_currency($total_collection); ?></p>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm">
        <h3 class="text-xs font-bold text-primary/50 uppercase tracking-wider">Pending Receivables</h3>
        <p class="text-2xl font-extrabold mt-2 text-red-600"><?php echo format_currency($pending_receivables); ?></p>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-primary/10 shadow-sm">
        <h3 class="text-xs font-bold text-primary/50 uppercase tracking-wider">Conversion Node Status</h3>
        <p class="text-2xl font-extrabold mt-2">Active (Multi-Currency)</p>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
