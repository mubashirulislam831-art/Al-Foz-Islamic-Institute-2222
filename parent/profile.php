<?php
/**
 * Al Foz Islamic Institute - Parent Ward & Guardian Profile Page
 */
require_once __DIR__ . '/includes/parent_context.php';
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-primary/10 rounded-[24px] p-6 shadow-sm">
      <div>
        <div class="flex items-center gap-2 text-xs font-bold text-primary/50 uppercase tracking-widest mb-1">
          <a href="/parent/dashboard.php" class="hover:text-primary transition-all">Parent Portal</a>
          <span>/</span>
          <span class="text-primary">Ward Profile</span>
        </div>
        <h1 class="text-2xl font-black text-primary tracking-tight uppercase">Guardian Record & Student Profile</h1>
      </div>
    </div>

    <?php if ($active_child): ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      <!-- Left Card: Ward Details -->
      <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
        <h3 class="text-xs font-black uppercase tracking-wider text-primary/50 mb-6 flex items-center gap-2">
          <i data-lucide="user" class="w-4 h-4 text-primary"></i> Ward Information (<?php echo htmlspecialchars($active_child['name']); ?>)
        </h3>
        
        <div class="space-y-3 text-xs">
          <div class="flex justify-between items-center border-b border-primary/5 pb-2.5">
            <span class="text-primary/60 font-semibold">Student Name:</span>
            <span class="text-primary font-bold"><?php echo htmlspecialchars($active_child['name']); ?></span>
          </div>

          <div class="flex justify-between items-center border-b border-primary/5 pb-2.5">
            <span class="text-primary/60 font-semibold">Roll Number / Student ID:</span>
            <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($active_child['roll_no'] ?? $active_child['student_id'] ?? 'STU-101'); ?></span>
          </div>

          <div class="flex justify-between items-center border-b border-primary/5 pb-2.5">
            <span class="text-primary/60 font-semibold">Assigned Program:</span>
            <span class="text-primary font-bold"><?php echo htmlspecialchars($active_child['course'] ?? 'Quran & Tajweed Course'); ?></span>
          </div>

          <div class="flex justify-between items-center border-b border-primary/5 pb-2.5">
            <span class="text-primary/60 font-semibold">Faculty Mentor:</span>
            <span class="text-primary font-bold"><?php echo htmlspecialchars($active_child['teacher_name'] ?? 'Assigned Instructor'); ?></span>
          </div>

          <div class="flex justify-between items-center border-b border-primary/5 pb-2.5">
            <span class="text-primary/60 font-semibold">Country & Timezone:</span>
            <span class="text-primary font-bold"><?php echo htmlspecialchars($active_child['country'] ?? 'Pakistan'); ?> (<?php echo htmlspecialchars($active_child['timezone'] ?? 'PKT'); ?>)</span>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-primary/60 font-semibold">Status:</span>
            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-md uppercase">
              <?php echo htmlspecialchars($active_child['status'] ?? 'Active'); ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Right Card: Parent / Guardian Account Info -->
      <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
        <h3 class="text-xs font-black uppercase tracking-wider text-primary/50 mb-6 flex items-center gap-2">
          <i data-lucide="shield" class="w-4 h-4 text-primary"></i> Parent Portal Guardian Record
        </h3>

        <div class="space-y-3 text-xs">
          <div class="flex justify-between items-center border-b border-primary/5 pb-2.5">
            <span class="text-primary/60 font-semibold">Parent / Guardian Name:</span>
            <span class="text-primary font-bold"><?php echo htmlspecialchars($parent_name); ?></span>
          </div>

          <div class="flex justify-between items-center border-b border-primary/5 pb-2.5">
            <span class="text-primary/60 font-semibold">Registered Contact Email:</span>
            <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($parent_email); ?></span>
          </div>

          <div class="flex justify-between items-center border-b border-primary/5 pb-2.5">
            <span class="text-primary/60 font-semibold">Total Enrolled Wards:</span>
            <span class="text-primary font-bold"><?php echo count($children); ?> Child Record(s)</span>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-primary/60 font-semibold">Academy Portal Access:</span>
            <span class="text-emerald-700 font-extrabold uppercase">Verified Parent Portal</span>
          </div>
        </div>
      </div>

    </div>
    <?php endif; ?>

  </div>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
