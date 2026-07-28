<?php
/**
 * Al Foz Islamic Institute - Super Admin Deleted Records Directory
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db_bridge.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'deleted_users';

$teachers = get_db_table('teachers');
$students = get_db_table('students');
$parents = get_db_table('parents');
$users = get_db_table('users');

$all_deleted = [];

// Admins
foreach ($users as $u) {
    if (in_array(strtolower($u['role']), ['admin', 'super_admin', 'super admin'])) {
        $status = $u['status'] ?? 'Active';
        if (strtolower($status) === 'deleted') {
            $all_deleted[] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => strtolower($u['role']) === 'admin' ? 'Admin' : 'Super Admin',
                'status' => 'Deleted',
                'raw_role' => strtolower($u['role']) === 'admin' ? 'admin' : 'super_admin',
                'deleted_at' => '2026-06-29',
                'deleted_by' => 'Super Admin'
            ];
        }
    }
}

// Teachers
foreach ($teachers as $t) {
    $status = $t['status'] ?? 'Active';
    if (strtolower($status) === 'deleted') {
        $all_deleted[] = [
            'id' => $t['employee_id'],
            'name' => $t['name'],
            'email' => $t['portal_email'] ?: 'N/A',
            'role' => 'Teacher',
            'status' => 'Deleted',
            'raw_role' => 'teacher',
            'deleted_at' => $t['deleted_at'] ?? '2026-06-29',
            'deleted_by' => $t['deleted_by'] ?? 'Super Admin'
        ];
    }
}

// Students
foreach ($students as $s) {
    $status = $s['status'] ?? 'Active';
    if (strtolower($status) === 'deleted') {
        $all_deleted[] = [
            'id' => $s['roll_no'],
            'name' => $s['name'],
            'email' => $s['portal_email'] ?: 'N/A',
            'role' => 'Student',
            'status' => 'Deleted',
            'raw_role' => 'student',
            'deleted_at' => $s['deleted_at'] ?? '2026-06-29',
            'deleted_by' => $s['deleted_by'] ?? 'Super Admin'
        ];
    }
}

// Parents
foreach ($parents as $p) {
    $status = $p['status'] ?? 'Active';
    if (strtolower($status) === 'deleted') {
        $all_deleted[] = [
            'id' => $p['id'],
            'name' => $p['name'],
            'email' => $p['portal_email'] ?: 'N/A',
            'role' => 'Parent',
            'status' => 'Deleted',
            'raw_role' => 'parent',
            'deleted_at' => $p['deleted_at'] ?? '2026-06-29',
            'deleted_by' => $p['deleted_by'] ?? 'Super Admin'
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
      <h1 class="text-2xl font-extrabold text-primary">Deleted Records (Recycle Bin)</h1>
      <p class="text-xs text-primary/60 mt-1">Soft-deleted user profiles. These can be restored instantly or permanently removed by confirming authorization.</p>
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
      <a href="deleted_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'deleted_users' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Deleted Users (<?php echo count($all_deleted); ?>)
      </a>
      <a href="restore_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Restore Records
      </a>
      <a href="permanent_delete_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Permanent Delete
      </a>
    </div>

    <!-- Alert Messaging -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'restored_success'): ?>
      <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl flex items-center gap-3">
        <span class="text-xs font-bold text-green-800">User successfully restored to their original group and permissions!</span>
      </div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'permanent_deleted_success'): ?>
      <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-center gap-3">
        <span class="text-xs font-bold text-red-800">User permanently purged from the system. This action cannot be undone.</span>
      </div>
    <?php endif; ?>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-primary/5 flex items-center justify-between">
        <h3 class="font-extrabold text-xs text-primary uppercase tracking-wider">Soft-Deleted Records</h3>
        <span class="text-[10px] bg-red-50 text-red-700 px-2.5 py-1 rounded-full font-bold uppercase">Soft-Deleted</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4 sm:p-5">User Details</th>
              <th class="p-4 sm:p-5">Identifier</th>
              <th class="p-4 sm:p-5">Role</th>
              <th class="p-4 sm:p-5">Delete Date</th>
              <th class="p-4 sm:p-5">Deleted By</th>
              <th class="p-4 sm:p-5 text-right font-black">Quick Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($all_deleted)): ?>
              <tr>
                <td colspan="6" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No soft-deleted accounts in the recycle bin.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($all_deleted as $user): ?>
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
                  <td class="p-4 sm:p-5 font-medium"><?php echo htmlspecialchars($user['deleted_at']); ?></td>
                  <td class="p-4 sm:p-5 font-bold text-primary/60"><?php echo htmlspecialchars($user['deleted_by']); ?></td>
                  <td class="p-4 sm:p-5 text-right space-x-2">
                    <a href="restore_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" class="text-[10px] bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1.5 rounded-lg font-extrabold uppercase transition-all active:scale-95">
                      Restore
                    </a>
                    <a href="permanent_delete_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" onclick="return confirm('⚠️ WARNING: Are you absolutely sure you want to PERMANENTLY delete this user? This action is irreversible and cannot be restored!');" class="text-[10px] bg-red-100 text-red-800 hover:bg-red-200 px-3 py-1.5 rounded-lg font-extrabold uppercase transition-all active:scale-95">
                      Perm Delete
                    </a>
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
