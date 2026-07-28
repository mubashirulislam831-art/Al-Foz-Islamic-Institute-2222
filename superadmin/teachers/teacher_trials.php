<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Trial Classes Tracker
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
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h2 class="text-lg font-bold text-primary">Assigned Trial Lessons</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Trial conversion rate: <span class="text-emerald-600 font-bold">0%</span> (Avg: 0%)</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="p-4 border border-emerald-500/10 rounded-xl bg-emerald-50/20 text-center">
              <div class="text-primary/60 font-bold text-[9px] uppercase">Converted Trials</div>
              <div class="text-emerald-700 font-black text-2xl mt-1">0</div>
              <p class="text-[9px] text-primary/60 mt-1">Syllabus started successfully</p>
          </div>
          <div class="p-4 border border-amber-500/10 rounded-xl bg-amber-50/20 text-center">
              <div class="text-primary/60 font-bold text-[9px] uppercase">Pending Conversion</div>
              <div class="text-amber-700 font-black text-2xl mt-1">0</div>
              <p class="text-[9px] text-primary/60 mt-1">Feedback reports drafted</p>
          </div>
          <div class="p-4 border border-rose-500/10 rounded-xl bg-rose-50/20 text-center">
              <div class="text-primary/60 font-bold text-[9px] uppercase">Dropped Trials</div>
              <div class="text-rose-700 font-black text-2xl mt-1">0</div>
              <p class="text-[9px] text-primary/60 mt-1">Unsuitable timing</p>
          </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-3">Student Name</th>
              <th class="p-3">Date & Time</th>
              <th class="p-3">Trial Status</th>
              <th class="p-3">Conversion Status</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <tr>
              <td colspan="5" class="p-20 text-center">
                <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <i data-lucide="monitor-dot" class="w-8 h-8 text-primary/20"></i>
                </div>
                <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No active trial lessons assigned to this scholar node.</p>
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

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
