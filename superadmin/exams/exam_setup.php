<?php
/**
 * Al Foz Islamic Institute - Super Admin Examination Configuration
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Examination Configuration</h1>
        <p class="text-xs text-primary/60 mt-1">Configure oral/written test standards, exam sessions, and certification rules.</p>
      </div>
    </div>

    <!-- Quick Options -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <a href="oral_exams.php" class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm hover:scale-[1.01] transition-transform block">
        <h3 class="font-bold text-primary">Oral Examination Desk</h3>
        <p class="text-xs text-primary/60 mt-2">Manage hifdh tajweed oral evaluations.</p>
      </a>
      <a href="written_exams.php" class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm hover:scale-[1.01] transition-transform block">
        <h3 class="font-bold text-primary">Written Examination Desk</h3>
        <p class="text-xs text-primary/60 mt-2">Manage islamic knowledge written evaluations.</p>
      </a>
      <a href="results.php" class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm hover:scale-[1.01] transition-transform block">
        <h3 class="font-bold text-primary">Results & grading</h3>
        <p class="text-xs text-primary/60 mt-2">Analyze final grades and report releases.</p>
      </a>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
