<?php
/**
 * Al Foz Islamic Institute - Super Admin Admins Manager
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
        <h1 class="text-2xl font-extrabold text-primary">System Admins</h1>
        <p class="text-xs text-primary/60 mt-1">Manage system administration accounts, credentials, and access levels.</p>
      </div>
      <div>
        <a href="add_admin.php" class="bg-primary hover:bg-opacity-95 text-secondary px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all active:scale-95 inline-block">
          + Onboard Admin
        </a>
      </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4 sm:p-5">Name</th>
              <th class="p-4 sm:p-5">Email</th>
              <th class="p-4 sm:p-5">Privileges</th>
              <th class="p-4 sm:p-5 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr class="hover:bg-primary/5 transition-colors">
              <td class="p-4 sm:p-5 font-bold text-primary"><?php echo $_SESSION['name']; ?></td>
              <td class="p-4 sm:p-5 font-mono"><?php echo $_SESSION['email']; ?></td>
              <td class="p-4 sm:p-5"><span class="px-2 py-0.5 bg-primary/10 text-primary font-bold rounded">Super Admin</span></td>
              <td class="p-4 sm:p-5 text-right space-x-2">
                <a href="admin_profile.php?id=<?php echo $_SESSION['user_id']; ?>" class="text-xs bg-primary/5 hover:bg-primary/10 px-3 py-1.5 rounded-lg font-bold">Profile</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
