<?php
/**
 * Al Foz Islamic Institute - Super Admin Account & Security Settings
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db_bridge.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'account_security';
$success_msg = '';
$error_msg = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'profile_updated') $success_msg = 'Your personal profile information has been securely updated.';
    if ($_GET['msg'] === 'password_updated') $success_msg = 'Your secure access credentials have been successfully updated.';
    if ($_GET['msg'] === '2fa_enabled') $success_msg = 'Two-Factor Authentication (2FA) is now ACTIVE on your system profile.';
    if ($_GET['msg'] === '2fa_disabled') $success_msg = 'Two-Factor Authentication has been deactivated. Your account security level is now reduced.';
    if ($_GET['msg'] === 'password_reset_sent') $success_msg = 'Password reset verification link dispatched.';
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid_password') $error_msg = 'The current password you provided is invalid.';
    if ($_GET['error'] === 'password_mismatch') $error_msg = 'Your new password and confirmation password do not match.';
    if ($_GET['error'] === 'password_length') $error_msg = 'For safety, your new password must be at least 6 characters in length.';
}

$admin_id = $_SESSION['user_id'] ?? 1;
global $pdo;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $admin_id]);
$admin_user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin_user) {
    die("User not found in database.");
}

// Ensure nested properties are initialized
if (!isset($admin_user['two_fa_enabled'])) $admin_user['two_fa_enabled'] = false;
if (!isset($admin_user['visibility_settings'])) {
    $admin_user['visibility_settings'] = [
        'show_whatsapp_teachers' => true,
        'show_email_directory' => true,
        'show_active_status' => true,
        'show_signature_certificates' => true
    ];
} elseif (is_string($admin_user['visibility_settings'])) {
    $admin_user['visibility_settings'] = json_decode($admin_user['visibility_settings'], true) ?: [];
}

$_SESSION['email'] = $admin_user['email'];
$_SESSION['name'] = $admin_user['name'];

// Process Post Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $timestamp_now = date('Y-m-d H:i:s');
    
    // Helper to log administrative actions
    $log_action = function($action_performed, $details) use ($admin_id, $pdo, $timestamp_now) {
        try {
            $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, timestamp) VALUES (:uid, :act, :det, :ts)");
            $stmt->execute([
                ':uid' => $admin_id,
                ':act' => $action_performed,
                ':det' => $details,
                ':ts' => $timestamp_now
            ]);
        } catch(PDOException $e) {}
    };

    if ($action === 'update_profile') {
        $new_name = trim($_POST['name'] ?? '');
        $new_email = trim($_POST['email'] ?? '');
        
        if (empty($new_name) || empty($new_email)) {
            $error_msg = 'Full name and email address cannot be left empty.';
        } else {
            $update_fields = [
                'name' => $new_name,
                'email' => strtolower($new_email)
            ];
            update_db_record('users', 'id', $admin_id, $update_fields);
            
            $log_action('Profile Settings Update', 'Super Admin updated their personal profile.');
            header("Location: account_security.php?msg=profile_updated");
            exit;
        }
    } elseif ($action === 'update_password') {
        $current_pass = $_POST['current_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm_pass = $_POST['confirm_password'] ?? '';

        if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
            $error_msg = 'Please complete all password configuration fields.';
        } elseif (!password_verify($current_pass, $admin_user['password']) && $current_pass !== $admin_user['password']) {
            header("Location: account_security.php?error=invalid_password");
            exit;
        } elseif ($new_pass !== $confirm_pass) {
            header("Location: account_security.php?error=password_mismatch");
            exit;
        } elseif (strlen($new_pass) < 6) {
            header("Location: account_security.php?error=password_length");
            exit;
        } else {
            $update_fields = [
                'password' => password_hash($new_pass, PASSWORD_DEFAULT)
            ];
            update_db_record('users', 'id', $admin_id, $update_fields);
            
            $log_action('Password Changed', 'Super Admin changed account credentials securely.');
            header("Location: account_security.php?msg=password_updated");
            exit;
        }
    }
}

$stmt_logs = $pdo->prepare("SELECT * FROM activity_logs WHERE user_id = :uid ORDER BY timestamp DESC LIMIT 20");
$stmt_logs->execute([':uid' => $admin_id]);
$admin_logs = $stmt_logs->fetchAll(PDO::FETCH_ASSOC);

?>
<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-primary mt-3 uppercase tracking-wider">Account & Security</h1>
      <p class="text-xs text-primary/60 mt-1">Manage your administrator profile, security credentials, and view login activity.</p>
    </div>

    <?php if ($success_msg): ?>
      <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
        <p class="text-xs font-bold text-emerald-800"><?php echo htmlspecialchars($success_msg); ?></p>
      </div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
      <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0"></i>
        <p class="text-xs font-bold text-red-800"><?php echo htmlspecialchars($error_msg); ?></p>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- Profile Settings -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Personal Info -->
        <div class="bg-white rounded-2xl border border-primary/10 p-6 shadow-sm">
          <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 flex items-center gap-2 border-b border-primary/5 pb-2">
            <i data-lucide="user" class="w-4 h-4 text-primary"></i>
            Personal Information
          </h3>
          <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="action" value="update_profile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-1.5">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($admin_user['name']); ?>" required class="w-full px-4 py-2.5 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none text-xs text-primary font-medium">
              </div>
              <div>
                <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-1.5">Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($admin_user['email']); ?>" required class="w-full px-4 py-2.5 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none text-xs text-primary font-medium">
              </div>
            </div>
            <div class="flex justify-end pt-2">
              <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold uppercase tracking-wider text-[10px] hover:bg-primary/90 transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                Save Profile Changes
              </button>
            </div>
          </form>
        </div>

        <!-- Security & Passwords -->
        <div class="bg-white rounded-2xl border border-primary/10 p-6 shadow-sm">
          <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 flex items-center gap-2 border-b border-primary/5 pb-2">
            <i data-lucide="lock" class="w-4 h-4 text-primary"></i>
            Account Password & Security
          </h3>
          <form action="" method="POST" class="space-y-6">
            <input type="hidden" name="action" value="update_password">
            <div class="space-y-4">
              <div>
                <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2.5 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none text-xs">
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-1.5">New Password</label>
                  <input type="password" name="new_password" required class="w-full px-4 py-2.5 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none text-xs">
                </div>
                <div>
                  <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-1.5">Confirm New Password</label>
                  <input type="password" name="confirm_password" required class="w-full px-4 py-2.5 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none text-xs">
                </div>
              </div>
            </div>
            <div class="flex justify-end pt-2">
              <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold uppercase tracking-wider text-[10px] hover:bg-primary/90 transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="key" class="w-3.5 h-3.5"></i>
                Update Password
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Activity Logs -->
      <div class="space-y-8">
        <div class="bg-white rounded-2xl border border-primary/10 p-6 shadow-sm">
          <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 flex items-center gap-2 border-b border-primary/5 pb-2">
            <i data-lucide="activity" class="w-4 h-4 text-primary"></i>
            Recent Activity
          </h3>
          <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
            <?php if (count($admin_logs) > 0): ?>
              <?php foreach ($admin_logs as $log): ?>
                <div class="p-3 bg-primary/5 border border-primary/10 rounded-xl relative">
                  <h5 class="font-bold text-primary text-[11px] uppercase tracking-wider mb-1"><?php echo htmlspecialchars($log['action'] ?? ''); ?></h5>
                  <p class="text-[10px] text-primary/70 leading-normal mb-2"><?php echo htmlspecialchars($log['details'] ?? ''); ?></p>
                  <span class="font-mono text-[9px] text-primary/50 block"><?php echo htmlspecialchars($log['timestamp'] ?? ''); ?></span>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="p-8 text-center bg-primary/5 border border-primary/10 rounded-xl text-primary/40 uppercase tracking-wider font-bold text-[9px]">
                No activity records found.
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
