<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Alerts & Notifications
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Teacher Header and Navigation -->
    <?php require_once __DIR__ . '/_teacher_header.php'; ?>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">Teacher Alerts & Action Items</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Real-time system, payroll, and scheduling triggers.</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-white border border-primary/20 text-primary text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/5 transition-colors flex items-center gap-1">Mark All Read</button>
        </div>
      </div>

      <div class="space-y-4">
        <!-- Empty State for Notifications -->
        <div class="py-20 text-center">
            <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="bell-off" class="w-8 h-8 text-primary/20"></i>
            </div>
            <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No active alerts or action triggers for this scholar node.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
