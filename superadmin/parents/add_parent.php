<?php
/**
 * Al Foz Islamic Institute - Super Admin Add Parent
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

    <div class="mb-8">
      <a href="parents.php" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Registry
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Link Parent / Guardian</h1>
    </div>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 max-w-2xl">
      <form action="#" method="POST" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="form-group">
            <label class="form-label">Parent Full Name</label>
            <input type="text" name="father_name" class="form-control" placeholder="Sulaiman Ali" required>
          </div>
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="parent@alfoz.com" required>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-primary/5">
          <a href="parents.php" class="btn-erp btn-erp-secondary">Cancel</a>
          <button type="submit" class="btn-erp btn-erp-primary">Provision Parent</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
