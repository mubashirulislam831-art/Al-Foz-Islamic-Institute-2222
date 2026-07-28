<?php
/**
 * Al Foz Islamic Institute - Super Admin Settings
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'email';
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
      <h1 class="text-2xl font-extrabold text-primary">Portal Configuration</h1>
      <p class="text-xs text-primary/60 mt-1">Configure global system parameters, academy schedules, and regional settings.</p>
    </div>

    <!-- Settings Tabbed Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-primary/10 pb-4 mb-8">
      <a href="academy_settings.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'academy' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Academy
      </a>
      <a href="logo_settings.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'logo' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Logo & Assets
      </a>
      <a href="fee_settings.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'fee' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Fee Config
      </a>
      <a href="attendance_settings.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'attendance' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Attendance Check
      </a>
      <a href="email_settings.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'email' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        SMTP / Email
      </a>
      <a href="whatsapp_settings.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'whatsapp' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        WhatsApp API
      </a>
      <a href="currency_settings.php" class="px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all <?php echo $active_tab === 'currency' ? 'bg-primary text-secondary' : 'bg-white text-primary border border-primary/10 hover:bg-primary/5'; ?>">
        Currencies
      </a>
    </div>

    <!-- Active Tab Panel: Email Settings -->
    <div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8 shadow-sm">
      <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 pb-2 border-b border-primary/5">SMTP Mail Server Settings</h3>
      <form onsubmit="event.preventDefault(); alert('SMTP configurations saved successfully!');" class="space-y-6 max-w-2xl text-xs">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-2">SMTP Host</label>
            <input type="text" value="smtp.alfoz.com" class="w-full px-4 py-3 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none">
          </div>
          <div>
            <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-2">SMTP Port</label>
            <input type="number" value="587" class="w-full px-4 py-3 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none">
          </div>
          <div>
            <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-2">SMTP User Email</label>
            <input type="email" value="noreply@alfoz.com" class="w-full px-4 py-3 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none">
          </div>
          <div>
            <label class="block text-[10px] uppercase font-bold tracking-wider text-primary/70 mb-2">SMTP Password</label>
            <input type="password" value="••••••••••••••" class="w-full px-4 py-3 border border-primary/10 rounded-xl focus:ring-1 focus:ring-primary focus:outline-none">
          </div>
        </div>

        <div class="pt-4">
          <button type="submit" class="bg-primary hover:bg-opacity-95 text-secondary px-6 py-3 rounded-xl font-bold uppercase tracking-wider shadow-md transition-all active:scale-95">
            Save SMTP Settings
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
