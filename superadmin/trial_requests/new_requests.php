<?php
/**
 * Al Foz Islamic Institute - Super Admin New Trial Requests
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
        <h1 class="text-2xl font-extrabold text-primary">Trial Request Desk</h1>
        <p class="text-xs text-primary/60 mt-1">Review new, active, and completed trial class requests from global candidates.</p>
      </div>
    </div>

    <!-- Main Navigation Links -->
    <div class="flex flex-wrap gap-2 mb-8">
      <a href="new_requests.php" class="bg-primary text-secondary px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all">New Trials</a>
      <a href="assigned_trials.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all">Assigned</a>
      <a href="trial_schedule.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all">Schedule Matrix</a>
      <a href="converted_students.php" class="bg-white border border-primary/15 text-primary hover:bg-primary hover:text-secondary px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all">Converted</a>
    </div>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
      <p class="text-xs text-primary/70">Observe incoming admission requests before assigning to scholars.</p>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
