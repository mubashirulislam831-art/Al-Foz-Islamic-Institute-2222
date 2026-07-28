<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Documents
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

    <!-- Dynamic Teacher Header and Navigation -->
    <?php require_once __DIR__ . '/_teacher_header.php'; ?>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 mb-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
          <h2 class="text-lg font-bold text-primary">Verification & Academic Documents</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Official credentials and identity proofs for this scholar.</p>
        </div>
        <button class="px-4 py-2 bg-primary text-white rounded-xl text-[10px] font-bold uppercase tracking-wider hover:bg-primary/90 transition-all flex items-center gap-1.5 shadow-sm">
          <i data-lucide="plus" class="w-3.5 h-3.5"></i> Upload New Document
        </button>
      </div>
      
      <?php
      $documents = [];
      
      if (!empty($teacher['cnic']) && $teacher['cnic'] !== 'N/A') {
          $documents[] = [
              'title' => 'National Identity Card (CNIC)',
              'type' => 'Identity Verification',
              'ref' => $teacher['cnic'],
              'status' => 'Verified',
              'icon' => 'credit-card',
              'color_class' => 'text-emerald-600',
              'bg_class' => 'bg-emerald-50'
          ];
      }
      
      if (!empty($teacher['qualification']) && $teacher['qualification'] !== 'N/A') {
          $documents[] = [
              'title' => 'Academic Degree & Sanad',
              'type' => 'Educational Qualification',
              'ref' => $teacher['qualification'],
              'status' => 'Verified',
              'icon' => 'graduation-cap',
              'color_class' => 'text-blue-600',
              'bg_class' => 'bg-blue-50'
          ];
      }

      if (!empty($teacher['employee_id'])) {
          $documents[] = [
              'title' => 'Teacher Employment Agreement',
              'type' => 'Employment Contract',
              'ref' => 'AGREE-' . $teacher['employee_id'],
              'status' => 'Active',
              'icon' => 'file-text',
              'color_class' => 'text-purple-600',
              'bg_class' => 'bg-purple-50'
          ];
      }
      ?>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($documents)): ?>
        <!-- Empty State for Documents -->
        <div class="col-span-full py-20 text-center">
            <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="file-warning" class="w-8 h-8 text-primary/20"></i>
            </div>
            <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No verified documents available for this scholar.</p>
        </div>
        <?php else: ?>
            <?php foreach ($documents as $doc): ?>
            <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm flex flex-col justify-between hover:border-primary/20 transition-all">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl <?php echo $doc['bg_class']; ?> flex items-center justify-center <?php echo $doc['color_class']; ?>">
                            <i data-lucide="<?php echo $doc['icon']; ?>" class="w-5 h-5"></i>
                        </div>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold uppercase tracking-wider border border-emerald-100"><?php echo htmlspecialchars($doc['status']); ?></span>
                    </div>
                    <h3 class="font-bold text-primary text-xs mb-1"><?php echo htmlspecialchars($doc['title']); ?></h3>
                    <p class="text-[9px] text-primary/60 font-semibold uppercase tracking-wider mb-2"><?php echo htmlspecialchars($doc['type']); ?></p>
                    <p class="text-[10px] font-mono text-primary/70 bg-primary/5 p-2 rounded-lg break-all"><?php echo htmlspecialchars($doc['ref']); ?></p>
                </div>
                <div class="mt-4 pt-4 border-t border-primary/5 flex justify-between items-center">
                    <span class="text-[9px] text-primary/40 font-mono">Uploaded: N/A</span>
                    <button class="text-primary hover:text-primary/70 flex items-center gap-1 text-[10px] font-bold uppercase">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i> Download
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
