<?php
/**
 * Al Foz Islamic Institute - Super Admin Parent Directory
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/parents_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

$parents = get_all_parents();
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Parent Directory</h1>
        <p class="text-xs text-primary/60 mt-1">Manage parent profiles, credentials, and associated ward/student mappings.</p>
      </div>
      <div>
        <a href="add_parent.php" class="bg-primary hover:bg-opacity-95 text-secondary px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all active:scale-95 inline-block">
          + Link Parent
        </a>
      </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4 sm:p-5">Name</th>
              <th class="p-4 sm:p-5">Email</th>
              <th class="p-4 sm:p-5">Wards</th>
              <th class="p-4 sm:p-5 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($parents)): ?>
              <tr>
                <td colspan="4" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No parent profiles linked to active seekers.</td>
              </tr>
            <?php else: ?>
              <?php foreach($parents as $parent): ?>
                <tr class="hover:bg-primary/5 transition-colors">
                  <td class="p-4 sm:p-5 font-bold text-primary"><?php echo htmlspecialchars($parent['father_name']); ?></td>
                  <td class="p-4 sm:p-5 font-mono"><?php echo htmlspecialchars($parent['email'] ?? 'N/A'); ?></td>
                  <td class="p-4 sm:p-5">
                    <?php if (!empty($parent['students'])): ?>
                      <?php foreach ($parent['students'] as $student_id): ?>
                        <span class="px-2 py-0.5 bg-accent/10 text-primary font-bold rounded text-[10px]"><?php echo htmlspecialchars($student_id); ?></span>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <span class="text-primary/40 italic">No linked wards</span>
                    <?php endif; ?>
                  </td>
                  <td class="p-4 sm:p-5 text-right">
                    <div class="flex justify-end gap-2">
                      <a href="parent_profile.php?id=<?php echo $parent['id']; ?>" title="Profile" class="group relative p-2 bg-primary/5 hover:bg-primary/10 text-primary rounded transition-all">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-primary text-secondary text-[9px] font-bold px-2 py-1 rounded shadow-xl z-20 pointer-events-none uppercase tracking-widest whitespace-nowrap">Profile</span>
                      </a>
                      <a href="edit_parent.php?id=<?php echo $parent['id']; ?>" title="Edit" class="group relative p-2 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded transition-all">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-amber-600 text-white text-[9px] font-bold px-2 py-1 rounded shadow-xl z-20 pointer-events-none uppercase tracking-widest whitespace-nowrap">Edit</span>
                      </a>
                    </div>
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
