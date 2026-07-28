<?php
/**
 * Al Foz Islamic Institute - Super Admin Parent Profile
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/parents_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

$parent_id = isset($_GET['id']) ? $_GET['id'] : null;
$parent = get_parent_by_id($parent_id);
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8">
      <a href="parents.php" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Registry
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Parent Profile Dossier</h1>
    </div>

    <?php if ($parent): ?>
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
      <h3 class="font-bold text-primary"><?php echo htmlspecialchars($parent['father_name']); ?></h3>
      <p class="text-xs text-primary/60 mt-1">Associated Wards: 
        <?php if (!empty($parent['students'])): ?>
          <?php echo implode(', ', array_map('htmlspecialchars', $parent['students'])); ?>
        <?php else: ?>
          None linked.
        <?php endif; ?>
      </p>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-10 text-center">
        <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i data-lucide="user-x" class="w-8 h-8 text-primary/20"></i>
        </div>
        <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No parent profile found matching this identifier.</p>
    </div>
    <?php endif; ?>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
