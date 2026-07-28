<?php
/**
 * Al Foz Islamic Institute - Super Admin Backup & Recovery
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'backup';
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

    <!-- Active Tab Panel: Database Backup -->
    <div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8 shadow-sm">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-primary/5">
        <div>
          <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider">Automated Database Backups</h3>
          <p class="text-[10px] text-primary/60 mt-0.5">Restore points are maintained on secure remote server clusters automatically.</p>
        </div>
        <button onclick="alert('Creating database snapshot...');" class="bg-primary hover:bg-opacity-95 text-secondary px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all active:scale-95">
          Create Snapshot Now
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="border-b border-primary/10 uppercase font-bold text-primary/50 text-[10px]">
              <th class="pb-3">Backup File</th>
              <th class="pb-3">Size</th>
              <th class="pb-3">Snapshot Node</th>
              <th class="pb-3">Triggered By</th>
              <th class="pb-3 text-right font-mono">Timestamp</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr>
              <td class="py-4 font-bold text-primary">alfoz_db_snap_20260628.sql</td>
              <td class="py-4">12.3 MB</td>
              <td class="py-4">Cloud Cluster C3</td>
              <td class="py-4">Scheduled System Daemon</td>
              <td class="py-4 text-right font-mono text-primary/60">2026-06-28 06:01</td>
            </tr>
            <tr>
              <td class="py-4 font-bold text-primary">alfoz_db_snap_20260627.sql</td>
              <td class="py-4">12.2 MB</td>
              <td class="py-4">Cloud Cluster C3</td>
              <td class="py-4">Scheduled System Daemon</td>
              <td class="py-4 text-right font-mono text-primary/60">2026-06-27 06:00</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
