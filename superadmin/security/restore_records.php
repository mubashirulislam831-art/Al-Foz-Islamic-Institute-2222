<?php
/**
 * Al Foz Islamic Institute - Super Admin Restore Records Management
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db_bridge.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'restore_records';

$teachers = get_db_table('teachers');
$students = get_db_table('students');
$parents = get_db_table('parents');
$users = get_db_table('users');

$all_restorable = [];

// Admins
foreach ($users as $u) {
    if (in_array(strtolower($u['role']), ['admin', 'super_admin', 'super admin'])) {
        $status = $u['status'] ?? 'Active';
        if (strtolower($status) === 'deactivated' || strtolower($status) === 'deleted') {
            $all_restorable[] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => strtolower($u['role']) === 'admin' ? 'Admin' : 'Super Admin',
                'status' => $status,
                'raw_role' => strtolower($u['role']) === 'admin' ? 'admin' : 'super_admin'
            ];
        }
    }
}

// Teachers
foreach ($teachers as $t) {
    $status = $t['status'] ?? 'Active';
    if (strtolower($status) === 'deactivated' || strtolower($status) === 'deleted') {
        $all_restorable[] = [
            'id' => $t['employee_id'],
            'name' => $t['name'],
            'email' => $t['portal_email'] ?: 'N/A',
            'role' => 'Teacher',
            'status' => $status,
            'raw_role' => 'teacher'
        ];
    }
}

// Students
foreach ($students as $s) {
    $status = $s['status'] ?? 'Active';
    if (strtolower($status) === 'deactivated' || strtolower($status) === 'deleted') {
        $all_restorable[] = [
            'id' => $s['roll_no'],
            'name' => $s['name'],
            'email' => $s['portal_email'] ?: 'N/A',
            'role' => 'Student',
            'status' => $status,
            'raw_role' => 'student'
        ];
    }
}

// Parents
foreach ($parents as $p) {
    $status = $p['status'] ?? 'Active';
    if (strtolower($status) === 'deactivated' || strtolower($status) === 'deleted') {
        $all_restorable[] = [
            'id' => $p['id'],
            'name' => $p['name'],
            'email' => $p['portal_email'] ?: 'N/A',
            'role' => 'Parent',
            'status' => $status,
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
      <h1 class="text-2xl font-extrabold text-primary">Restore Records Center</h1>
      <p class="text-xs text-primary/60 mt-1">Reactivate deactivated users or restore soft-deleted records. Restored users return to their original position and permissions.</p>
    </div>

    <!-- Security Tabbed Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-primary/10 pb-4 mb-8">
      <a href="account_security.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        My Account & Profile
      </a>
      <a href="permissions.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Role Permissions
      </a>
      <a href="active_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Active Users
      </a>
      <a href="deactivated_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Deactivated Users
      </a>
      <a href="deleted_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Deleted Users
      </a>
      <a href="restore_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'restore_records' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Restore Records (<?php echo count($all_restorable); ?>)
      </a>
      <a href="permanent_delete_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Permanent Delete
      </a>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-primary/5">
        <h3 class="font-extrabold text-xs text-primary uppercase tracking-wider">Restorable User Accounts</h3>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4 sm:p-5">User Details</th>
              <th class="p-4 sm:p-5">Identifier</th>
              <th class="p-4 sm:p-5">Role</th>
              <th class="p-4 sm:p-5">Current Status</th>
              <th class="p-4 sm:p-5 text-right font-black">Quick Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($all_restorable)): ?>
              <tr>
                <td colspan="5" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No restorable accounts found in the system.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($all_restorable as $user): ?>
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
                    <?php if (strtolower($user['status']) === 'deleted'): ?>
                      <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-800 text-[10px] font-black uppercase">
                        Soft-Deleted
                      </span>
                    <?php else: ?>
                      <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-black uppercase">
                        Deactivated
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="p-4 sm:p-5 text-right">
                    <?php if (strtolower($user['status']) === 'deleted'): ?>
                      <a href="restore_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" class="text-[10px] bg-green-50 text-green-700 hover:bg-green-100 px-4 py-2 rounded-lg font-black uppercase transition-all active:scale-95 inline-block">
                        Restore Record
                      </a>
                    <?php else: ?>
                      <a href="activate_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" class="text-[10px] bg-green-50 text-green-700 hover:bg-green-100 px-4 py-2 rounded-lg font-black uppercase transition-all active:scale-95 inline-block">
                        Activate User
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
