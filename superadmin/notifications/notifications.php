<?php
/**
 * Al Foz Islamic Institute - Super Admin Broadcast Dashboard
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

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Broadcasting & Alerts</h1>
        <p class="text-xs text-primary/60 mt-1">Send or trace system alerts, notice board bulletins, and sms notification histories.</p>
      </div>
      <div>
        <a href="add_notification.php" class="bg-primary hover:bg-opacity-95 text-secondary px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all active:scale-95 inline-block">
          + Send Broadcast Alert
        </a>
      </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4 sm:p-5">Message Title</th>
              <th class="p-4 sm:p-5">Recipient Group</th>
              <th class="p-4 sm:p-5">Channels</th>
              <th class="p-4 sm:p-5 text-right">Date Sent</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr>
              <td colspan="4" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No broadcasts or alerts dispatched yet.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
