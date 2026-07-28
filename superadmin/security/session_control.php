<?php
/**
 * Al Foz Islamic Institute - Super Admin Security Settings
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'session_control';
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

    <!-- Active Tab Panel: Session Control -->
    <div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8 shadow-sm">
      <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 pb-2 border-b border-primary/5">Active Browser Sessions</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="border-b border-primary/10 uppercase font-bold text-primary/50 text-[10px]">
              <th class="pb-3">User Name</th>
              <th class="pb-3">Device / Browser</th>
              <th class="pb-3">IP Address</th>
              <th class="pb-3">Status</th>
              <th class="pb-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr>
              <td class="py-4">
                <p class="font-black text-primary tracking-wide">Mubashir Ul Islam Awan</p>
                <p class="text-[10px] text-primary/50">admin@alfoz.com</p>
              </td>
              <td class="py-4">Chrome on macOS (10.15)</td>
              <td class="py-4 font-mono">182.180.201.42</td>
              <td class="py-4"><span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full font-bold text-[9px] uppercase">This Session</span></td>
              <td class="py-4 text-right">
                <button class="text-[10px] font-bold text-primary/40 uppercase tracking-wider cursor-not-allowed" disabled>Active</button>
              </td>
            </tr>
            <tr>
              <td class="py-4">
                <p class="font-bold">Sumera Tabassum</p>
                <p class="text-[10px] text-primary/50">admin@alfoz.com</p>
              </td>
              <td class="py-4">Chrome on Windows (10)</td>
              <td class="py-4 font-mono">102.13.44.201</td>
              <td class="py-4"><span class="px-2.5 py-1 bg-primary/10 text-primary rounded-full font-bold text-[9px] uppercase">Logged In</span></td>
              <td class="py-4 text-right">
                <button onclick="alert('Session terminated successfully!');" class="text-[10px] font-bold text-red-600 hover:text-red-700 uppercase tracking-wider">Terminate</button>
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
