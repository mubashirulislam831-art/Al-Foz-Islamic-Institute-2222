<?php
/**
 * Al Foz Islamic Institute - Super Admin Backup & Recovery
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'restore';
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

    <!-- Active Tab Panel: Restore Points -->
    <div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8 shadow-sm">
      <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 pb-2 border-b border-primary/5">Upload Recovery Point (.sql)</h3>
      <form onsubmit="event.preventDefault(); alert('Verifying backup file integrity and structure...');" class="space-y-6 max-w-2xl text-xs">
        <div class="border-2 border-dashed border-primary/20 rounded-2xl p-8 text-center bg-primary/5 flex flex-col items-center justify-center cursor-pointer hover:bg-primary/10 transition-all">
          <span class="text-3xl mb-2">📁</span>
          <p class="font-bold text-primary">Drag & drop your backup file here</p>
          <p class="text-[10px] text-primary/50 mt-1">Accepts .sql, .sql.gz, or .tar.gz (Max 256MB)</p>
          <input type="file" class="hidden" id="file-uploader">
        </div>

        <div class="pt-4">
          <button type="submit" class="bg-primary hover:bg-opacity-95 text-secondary px-6 py-3 rounded-xl font-bold uppercase tracking-wider shadow-md transition-all active:scale-95">
            Verify & Restore Point
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
