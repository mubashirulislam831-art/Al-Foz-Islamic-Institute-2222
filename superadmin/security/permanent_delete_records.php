<?php
/**
 * Al Foz Islamic Institute - Super Admin Permanent Delete Records Management
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db_bridge.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'permanent_delete_records';

$teachers = get_db_table('teachers');
$students = get_db_table('students');
$parents = get_db_table('parents');
$users = get_db_table('users');

$all_soft_deleted = [];

// Admins
foreach ($users as $u) {
    if (in_array(strtolower($u['role']), ['admin', 'super_admin', 'super admin'])) {
        $status = $u['status'] ?? 'Active';
        if (strtolower($status) === 'deleted') {
            $all_soft_deleted[] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => strtolower($u['role']) === 'admin' ? 'Admin' : 'Super Admin',
                'raw_role' => strtolower($u['role']) === 'admin' ? 'admin' : 'super_admin'
            ];
        }
    }
}

// Teachers
foreach ($teachers as $t) {
    $status = $t['status'] ?? 'Active';
    if (strtolower($status) === 'deleted') {
        $all_soft_deleted[] = [
            'id' => $t['employee_id'],
            'name' => $t['name'],
            'email' => $t['portal_email'] ?: 'N/A',
            'role' => 'Teacher',
            'raw_role' => 'teacher'
        ];
    }
}

// Students
foreach ($students as $s) {
    $status = $s['status'] ?? 'Active';
    if (strtolower($status) === 'deleted') {
        $all_soft_deleted[] = [
            'id' => $s['roll_no'],
            'name' => $s['name'],
            'email' => $s['portal_email'] ?: 'N/A',
            'role' => 'Student',
            'raw_role' => 'student'
        ];
    }
}

// Parents
foreach ($parents as $p) {
    $status = $p['status'] ?? 'Active';
    if (strtolower($status) === 'deleted') {
        $all_soft_deleted[] = [
            'id' => $p['id'],
            'name' => $p['name'],
            'email' => $p['portal_email'] ?: 'N/A',
            'role' => 'Parent',
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
      <h1 class="text-2xl font-extrabold text-primary">Permanent Purge Management</h1>
      <p class="text-xs text-primary/60 mt-1">Permanently erase soft-deleted accounts. Erasing accounts purges them completely from databases, which cannot be undone.</p>
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
      <a href="restore_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all bg-white text-primary border border-primary/10 hover:bg-primary/5">
        Restore Records
      </a>
      <a href="permanent_delete_records.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'permanent_delete_records' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Permanent Delete (<?php echo count($all_soft_deleted); ?>)
      </a>
    </div>

    <!-- Red Banner Warning Alert -->
    <div class="mb-8 bg-red-50 border-l-4 border-red-600 p-6 rounded-2xl flex items-start gap-4 shadow-sm">
      <div class="p-2 bg-red-100 text-red-800 rounded-xl">
        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
      </div>
      <div>
        <h3 class="text-sm font-black text-red-900 uppercase tracking-wide">CRITICAL SECURITY WARNING</h3>
        <p class="text-xs text-red-700 mt-1 leading-relaxed font-semibold">
          Executing a permanent delete will immediately and irreversibly purge the selected record, credentials, database mapping, and access privilege. It is highly recommended to keep accounts deactivated or soft-deleted unless absolutely necessary.
        </p>
      </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-primary/5">
        <h3 class="font-extrabold text-xs text-primary uppercase tracking-wider">Purgeable Accounts</h3>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4 sm:p-5">User Details</th>
              <th class="p-4 sm:p-5">Identifier</th>
              <th class="p-4 sm:p-5">Role</th>
              <th class="p-4 sm:p-5 text-right font-black">Authorized Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($all_soft_deleted)): ?>
              <tr>
                <td colspan="4" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No purgeable accounts found in the recycle bin.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($all_soft_deleted as $user): ?>
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
                  <td class="p-4 sm:p-5 text-right">
                    <a href="permanent_delete_user.php?id=<?php echo urlencode($user['id']); ?>&role=<?php echo urlencode($user['raw_role']); ?>" onclick="return confirm('⚠️ ULTIMATE WARNING: This is the LAST CONFIRMATION. Clicking OK will permanently delete this record from the disk. This cannot be undone! Are you absolutely sure?');" class="text-[10px] bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-lg font-black uppercase transition-all active:scale-95 inline-block shadow-sm">
                      Permanently Delete Record
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
