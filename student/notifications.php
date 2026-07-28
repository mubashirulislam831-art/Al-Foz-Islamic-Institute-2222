<?php
/**
 * Al Foz Islamic Institute - Student Notifications & Announcements Page
 */
require_once __DIR__ . '/includes/student_context.php';

$all_notifications = get_db_table('notifications');
$student_notifications = array_filter($all_notifications, function($n) {
    $grp = strtolower(trim($n['recipient_group'] ?? 'all'));
    return ($grp === 'all' || $grp === 'students' || $grp === 'student');
});
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">notifications</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Academy Broadcasts & Notice Board</p>
      </div>
      <span class="px-4 py-2 bg-primary/10 text-primary text-xs font-bold rounded-xl border border-primary/20 flex items-center gap-2">
        <i data-lucide="bell" class="w-4 h-4 text-primary"></i> <?php echo count($student_notifications); ?> Active Notices
      </span>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-[24px] border border-primary/10 p-6 shadow-sm">
      <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-6 flex items-center gap-2">
        <i data-lucide="megaphone" class="w-4 h-4 text-primary"></i> Latest Official Broadcasts
      </h3>

      <?php if (!empty($student_notifications)): ?>
        <div class="space-y-4">
          <?php foreach ($student_notifications as $notif): ?>
          <div class="p-5 rounded-2xl border border-primary/10 bg-primary/5 hover:bg-white transition-all shadow-sm">
            <div class="flex items-center justify-between mb-2">
              <span class="px-2.5 py-0.5 bg-primary text-white text-[9px] font-black uppercase rounded-full">
                Notice
              </span>
              <span class="text-[10px] text-primary/50 font-mono font-bold">
                <?php echo date('d M, Y', strtotime($notif['date_sent'] ?? $notif['created_at'] ?? 'now')); ?>
              </span>
            </div>
            <h4 class="text-sm font-black text-primary"><?php echo htmlspecialchars($notif['title'] ?? 'Institute Announcement'); ?></h4>
            <p class="text-xs text-primary/80 font-medium mt-1 leading-relaxed"><?php echo htmlspecialchars($notif['content'] ?? 'Please ensure your session attendance is maintained.'); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="p-12 text-center text-primary/60 font-medium">
          <i data-lucide="bell-off" class="w-12 h-12 text-primary/30 mx-auto mb-3"></i>
          <p class="text-sm font-bold text-primary">No Notifications Available</p>
          <p class="text-xs text-primary/60 mt-1">There are no official broadcasts or notices published at this time.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
