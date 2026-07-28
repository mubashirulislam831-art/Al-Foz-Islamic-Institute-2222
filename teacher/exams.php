<?php
/**
 * Al Foz Islamic Institute - Teacher ERP
 */
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../auth/permissions.php';

require_role(['Teacher', 'Admin', 'Super Admin']);
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="mb-8">
      <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">exams</h1>
      <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Faculty Management Area</p>
    </div>

    <div class="bg-white rounded-[22px] border border-primary/10 shadow-sm p-8 text-center text-primary/40 font-bold uppercase tracking-widest text-xs">
      Module content loading...
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
