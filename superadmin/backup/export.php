<?php
/**
 * Al Foz Islamic Institute - Super Admin Backup & Recovery
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'export';
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Breadcrumbs -->
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-primary">System Backups & Recovery</h1>
      <p class="text-xs text-primary/60 mt-1">Configure automated database snapshots, restore previous checkpoints, and export data tables.</p>
    </div>

    <!-- Backup Tabbed Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-primary/10 pb-4 mb-8">
      <a href="database_backup.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'backup' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Database Snapshots
      </a>
      <a href="restore.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'restore' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Restore Points
      </a>
      <a href="export.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'export' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Data Export
      </a>
    </div>

    <!-- Active Tab Panel: Data Export -->
    <div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8 shadow-sm">
      <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 pb-2 border-b border-primary/5">Bulk Exporter</h3>
      <form onsubmit="event.preventDefault(); alert('Exporting requested database tables...');" class="space-y-6 max-w-2xl text-xs">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-2">Select Dataset Table</label>
            <select class="w-full px-4 py-3 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none">
              <option>Students Registry (Full Profiles)</option>
              <option>Scholars & Educators Payroll Log</option>
              <option>Billing, Invoices & Fees Ledger</option>
              <option>System Login & Access Logs</option>
            </select>
          </div>
          <div>
            <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-2">Select Format</label>
            <select class="w-full px-4 py-3 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none">
              <option>Excel Worksheet (.xlsx)</option>
              <option>Comma-Separated Values (.csv)</option>
              <option>Structured JSON (.json)</option>
              <option>Standard SQL Dump (.sql)</option>
            </select>
          </div>
        </div>

        <div class="pt-4">
          <button type="submit" class="bg-primary hover:bg-opacity-95 text-secondary px-6 py-3 rounded-xl font-bold uppercase tracking-wider shadow-md transition-all active:scale-95">
            Trigger Secure Export
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
