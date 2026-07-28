<?php
/**
 * Al Foz Islamic Institute - Super Admin Security Settings
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'permissions';
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
      <h1 class="text-2xl font-extrabold text-primary">Security & Access Management</h1>
      <p class="text-xs text-primary/60 mt-1">Configure role permissions, review activity logs, and terminate active portal sessions.</p>
    </div>

    <!-- Security Tabbed Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-primary/10 pb-4 mb-8">
      <a href="account_security.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'account_security' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        My Account & Profile
      </a>
      <a href="permissions.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'permissions' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Role Permissions
      </a>
      <a href="login_logs.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'login_logs' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Login Logs
      </a>
      <a href="activity_logs.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'activity_logs' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Activity Logs
      </a>
      <a href="audit_trail.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'audit_trail' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Audit Trail
      </a>
      <a href="session_control.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'session_control' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Session Control
      </a>
      <a href="active_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'active_users' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Active Users
      </a>
      <a href="deactivated_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'deactivated_users' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Deactivated Users
      </a>
      <a href="deleted_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'deleted_users' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Deleted Users
      </a>
      <a href="restore_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'restore_records' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Restore Records
      </a>
      <a href="permanent_delete_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'permanent_delete_records' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Permanent Delete
      </a>
    </div>

    <!-- Active Tab Panel: Permissions -->
    <div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8 shadow-sm">
      <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 pb-2 border-b border-primary/5">Role-Based Access Controls (RBAC)</h3>
      <form onsubmit="event.preventDefault(); alert('Role permissions saved successfully!');" class="space-y-6 text-xs text-primary/80">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-primary/10 text-[10px] font-bold uppercase text-primary/50">
              <th class="pb-3">Module Action</th>
              <th class="pb-3">Super Admin</th>
              <th class="pb-3">Admin</th>
              <th class="pb-3">Teacher</th>
              <th class="pb-3">Student</th>
              <th class="pb-3">Parent</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5">
            <tr>
              <td class="py-4 font-bold">Access System Configurations</td>
              <td class="py-4"><input type="checkbox" checked disabled></td>
              <td class="py-4"><input type="checkbox" disabled></td>
              <td class="py-4"><input type="checkbox" disabled></td>
              <td class="py-4"><input type="checkbox" disabled></td>
              <td class="py-4"><input type="checkbox" disabled></td>
            </tr>
            <tr>
              <td class="py-4 font-bold">Enroll & Edit Students</td>
              <td class="py-4"><input type="checkbox" checked></td>
              <td class="py-4"><input type="checkbox" checked></td>
              <td class="py-4"><input type="checkbox"></td>
              <td class="py-4"><input type="checkbox"></td>
              <td class="py-4"><input type="checkbox"></td>
            </tr>
            <tr>
              <td class="py-4 font-bold">Configure Fee Invoices</td>
              <td class="py-4"><input type="checkbox" checked></td>
              <td class="py-4"><input type="checkbox" checked></td>
              <td class="py-4"><input type="checkbox"></td>
              <td class="py-4"><input type="checkbox"></td>
              <td class="py-4"><input type="checkbox"></td>
            </tr>
            <tr>
              <td class="py-4 font-bold">Grade Examinations</td>
              <td class="py-4"><input type="checkbox" checked></td>
              <td class="py-4"><input type="checkbox" checked></td>
              <td class="py-4"><input type="checkbox" checked></td>
              <td class="py-4"><input type="checkbox"></td>
              <td class="py-4"><input type="checkbox"></td>
            </tr>
          </tbody>
        </table>

        <div class="pt-4">
          <button type="submit" class="bg-primary hover:bg-opacity-95 text-secondary px-6 py-3 rounded-xl font-bold uppercase tracking-wider shadow-md transition-all active:scale-95">
            Save Permissions Tree
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
