<?php
/**
 * Al Foz Islamic Institute - Super Admin Deactivated Users Management
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db_bridge.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'deactivated_users';

$teachers = get_db_table('teachers');
$students = get_db_table('students');
$parents = get_db_table('parents');
$users = get_db_table('users');

$all_deactivated = [];

// Admins
foreach ($users as $u) {
    if (in_array(strtolower($u['role']), ['admin', 'super_admin', 'super admin'])) {
        $status = $u['status'] ?? 'Active';
        if (strtolower($status) === 'deactivated') {
            $all_deactivated[] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => strtolower($u['role']) === 'admin' ? 'Admin' : 'Super Admin',
                'status' => 'Deactivated',
                'raw_role' => strtolower($u['role']) === 'admin' ? 'admin' : 'super_admin'
            ];
        }
    }
}

// Teachers
foreach ($teachers as $t) {
    $status = $t['status'] ?? 'Active';
    if (strtolower($status) === 'deactivated') {
        $all_deactivated[] = [
            'id' => $t['employee_id'],
            'name' => $t['name'],
            'email' => $t['portal_email'] ?: 'N/A',
            'role' => 'Teacher',
            'status' => 'Deactivated',
            'raw_role' => 'teacher'
        ];
    }
}

// Students
foreach ($students as $s) {
    $status = $s['status'] ?? 'Active';
    if (strtolower($status) === 'deactivated') {
        $all_deactivated[] = [
            'id' => $s['roll_no'],
            'name' => $s['name'],
            'email' => $s['portal_email'] ?: 'N/A',
            'role' => 'Student',
            'status' => 'Deactivated',
            'raw_role' => 'student'
        ];
    }
}

// Parents
foreach ($parents as $p) {
    $status = $p['status'] ?? 'Active';
    if (strtolower($status) === 'deactivated') {
        $all_deactivated[] = [
            'id' => $p['id'],
            'name' => $p['name'],
            'email' => $p['portal_email'] ?: 'N/A',
            'role' => 'Parent',
            'status' => 'Deactivated',
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
      <h1 class="text-2xl font-extrabold text-primary">Deactivated Users Directory</h1>
      <p class="text-xs text-primary/60 mt-1">Review accounts that are currently suspended from portal login and active dashboards.</p>
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
      <a href="deactivated_users.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'deactivated_users' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Deactivated Users (<?php echo count($all_deactivated); ?>)
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
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'activated_success'): ?>
      <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl flex items-center gap-3">
        <span class="text-xs font-bold text-green-800">User successfully reactivated! They can now log in and appear in dashboards.</span>
      </div>
    <?php endif; ?>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-primary/5 flex items-center justify-between">
        <h3 class="font-extrabold text-xs text-primary uppercase tracking-wider">Deactivated Accounts</h3>
        <span class="text-[10px] bg-red-50 text-red-700 px-2.5 py-1 rounded-full font-bold uppercase">Locked Accounts</span>
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
            <?php if (empty($all_deactivated)): ?>
              <tr>
                <td colspan="5" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No deactivated accounts currently.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($all_deactivated as $user): ?>
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
                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-black uppercase">
                      Deactivated
                    </span>
                  </td>
                  <td class="p-4 sm:p-5 text-right space-x-2">
                    <a href="activate_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" class="text-[10px] bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1.5 rounded-lg font-extrabold uppercase transition-all active:scale-95">
                      Reactivate
                    </a>
                    <a href="delete_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" onclick="return confirm('Are you sure you want to move this deactivated user to Deleted Records?');" class="text-[10px] bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg font-extrabold uppercase transition-all active:scale-95">
                      Delete
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
