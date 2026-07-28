<?php
/**
 * Al Foz Islamic Institute - Super Admin Active Users Management
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db_bridge.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'active_users';

$teachers = get_db_table('teachers');
$students = get_db_table('students');
$parents = get_db_table('parents');
$users = get_db_table('users');

$all_active = [];

// Admins
foreach ($users as $u) {
    if (in_array(strtolower($u['role']), ['admin', 'super_admin', 'super admin'])) {
        $status = $u['status'] ?? 'Active';
        if (strtolower($status) === 'active') {
            $all_active[] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => strtolower($u['role']) === 'admin' ? 'Admin' : 'Super Admin',
                'status' => 'Active',
                'raw_role' => strtolower($u['role']) === 'admin' ? 'admin' : 'super_admin'
            ];
        }
    }
}

// Teachers
foreach ($teachers as $t) {
    $status = $t['status'] ?? 'Active';
    if (strtolower($status) === 'active') {
        $all_active[] = [
            'id' => $t['employee_id'],
            'name' => $t['name'],
            'email' => $t['portal_email'] ?: 'N/A',
            'role' => 'Teacher',
            'status' => 'Active',
            'raw_role' => 'teacher'
        ];
    }
}

// Students
foreach ($students as $s) {
    $status = $s['status'] ?? 'Active';
    if (strtolower($status) === 'active') {
        $all_active[] = [
            'id' => $s['roll_no'],
            'name' => $s['name'],
            'email' => $s['portal_email'] ?: 'N/A',
            'role' => 'Student',
            'status' => 'Active',
            'raw_role' => 'student'
        ];
    }
}

// Parents
foreach ($parents as $p) {
    $status = $p['status'] ?? 'Active';
    if (strtolower($status) === 'active') {
        $all_active[] = [
            'id' => $p['id'],
            'name' => $p['name'],
            'email' => $p['portal_email'] ?: 'N/A',
            'role' => 'Parent',
            'status' => 'Active',
            'raw_role' => 'parent'
        ];
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-primary">Active Users & Status Management</h1>
      <p class="text-xs text-primary/60 mt-1">Control active status, soft delete users, or restore deactivated accounts across the portal.</p>
    </div>

    <!-- Security Tabbed Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-primary/10 pb-4 mb-8">
      <a href="account_security.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        My Account & Profile
      </a>
      <a href="permissions.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Role Permissions
      </a>
      <a href="active_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'active_users' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Active Users (<?php echo count($all_active); ?>)
      </a>
      <a href="deactivated_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Deactivated Users
      </a>
      <a href="deleted_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Deleted Users
      </a>
      <a href="restore_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Restore Records
      </a>
      <a href="permanent_delete_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Permanent Delete
      </a>
    </div>

    <!-- Alert Messaging -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deactivated_success'): ?>
      <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl flex items-center gap-3">
        <span class="text-xs font-bold text-amber-800">User successfully deactivated. They can no longer log in.</span>
      </div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted_success'): ?>
      <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-center gap-3">
        <span class="text-xs font-bold text-red-800">User successfully soft-deleted and moved to Deleted Users.</span>
      </div>
    <?php endif; ?>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-primary/5 flex items-center justify-between">
        <h3 class="font-extrabold text-xs text-primary uppercase tracking-wider">Live Active Portal Users</h3>
        <span class="text-[10px] bg-green-50 text-green-700 px-2.5 py-1 rounded-full font-bold uppercase">Online System Sync</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4 sm:p-5">User Details</th>
              <th class="p-4 sm:p-5">Identifier</th>
              <th class="p-4 sm:p-5">Role</th>
              <th class="p-4 sm:p-5">Portal Status</th>
              <th class="p-4 sm:p-5 text-right font-black">Quick Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($all_active)): ?>
              <tr>
                <td colspan="5" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No active user accounts found.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($all_active as $user): ?>
                <tr class="hover:bg-primary/5 transition-colors">
                  <td class="p-4 sm:p-5">
                    <div class="font-bold text-primary"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="text-[10px] text-primary/60 font-medium"><?php echo htmlspecialchars($user['email']); ?></div>
                  </td>
                  <td class="p-4 sm:p-5 font-mono font-bold"><?php echo htmlspecialchars($user['id']); ?></td>
                  <td class="p-4 sm:p-5">
                    <span class="px-2.5 py-1 rounded-lg bg-primary/5 text-primary text-[10px] font-bold uppercase">
                      <?php echo htmlspecialchars($user['role']); ?>
                    </span>
                  </td>
                  <td class="p-4 sm:p-5">
                    <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-800 text-[10px] font-black uppercase">
                      Active
                    </span>
                  </td>
                  <td class="p-4 sm:p-5 text-right space-x-2">
                    <?php if ($user['role'] === 'Super Admin'): ?>
                      <a href="account_security.php?run_audit=true" class="text-[10px] bg-primary text-white hover:bg-opacity-95 px-3 py-1.5 rounded-lg font-extrabold uppercase transition-all active:scale-95 inline-flex items-center gap-1 shadow-sm">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-secondary"></i>
                        Audit Profile
                      </a>
                    <?php else: ?>
                      <a href="deactivate_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" class="text-[10px] bg-amber-50 text-amber-700 hover:bg-amber-100 px-3 py-1.5 rounded-lg font-extrabold uppercase transition-all active:scale-95">
                        Deactivate
                      </a>
                      <a href="delete_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" onclick="return confirm('Are you sure you want to move this user to Deleted Records?');" class="text-[10px] bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg font-extrabold uppercase transition-all active:scale-95">
                        Delete
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
