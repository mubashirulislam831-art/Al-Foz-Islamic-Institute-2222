<?php
/**
 * Al Foz Islamic Institute - Super Admin Settings
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

$active_tab = 'logo';
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

    <!-- Active Tab Panel: Logo Settings -->
    <div class="bg-white rounded-2xl border border-primary/10 p-6 md:p-8 shadow-sm">
      <h3 class="font-extrabold text-sm text-primary uppercase tracking-wider mb-6 pb-2 border-b border-primary/5">Logo & Asset Management</h3>
      <form onsubmit="event.preventDefault(); alert('Logo assets updated!');" class="space-y-6 max-w-2xl text-xs">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pb-4">
          <div class="w-24 h-24 rounded-2xl border border-primary/10 bg-primary/5 flex items-center justify-center overflow-hidden">
            <img src="/assets/logo.png" alt="Logo" class="w-16 h-16 object-contain">
          </div>
          <div>
            <h4 class="font-bold text-sm text-primary">Main Institute Logo</h4>
            <p class="text-[10px] text-primary/60 mt-0.5">Recommended format: transparent PNG or SVG, min size 250x250px.</p>
            <input type="file" class="mt-3 block text-[11px] text-primary/60">
          </div>
        </div>

        <div class="pt-4">
          <button type="submit" class="bg-primary hover:bg-opacity-95 text-secondary px-6 py-3 rounded-xl font-bold uppercase tracking-wider shadow-md transition-all active:scale-95">
            Upload & Apply Logo
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
