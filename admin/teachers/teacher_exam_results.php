<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Exam Results Registry
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Teacher Header and Navigation -->
    <?php require_once __DIR__ . '/_teacher_header.php'; ?>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">Evaluated Student Exams & Results</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Syllabus progression grading and official certificates.</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-3">Student Name</th>
              <th class="p-3">Exam Description</th>
              <th class="p-3">Date Evaluated</th>
              <th class="p-3">Grade / Score</th>
              <th class="p-3">Certificate</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr>
              <td colspan="6" class="p-20 text-center">
                <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <i data-lucide="file-check-2" class="w-8 h-8 text-primary/20"></i>
                </div>
                <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No evaluated exam records found for this scholar node.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<!-- Lucide Icons initialization -->
<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
