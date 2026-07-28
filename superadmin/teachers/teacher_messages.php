<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Messages
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

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden min-h-[500px] flex flex-col lg:flex-row">
      <!-- Chat List Sidebar -->
      <div class="w-full lg:w-80 border-r border-primary/10 flex flex-col">
        <div class="p-4 border-b border-primary/10 bg-primary/5">
          <h3 class="font-bold text-primary text-xs uppercase tracking-wider">Inbox Conversations</h3>
        </div>
        <div class="flex-grow overflow-y-auto divide-y divide-primary/5">
          <!-- Active Chat -->
          <div class="p-4 bg-primary/5 flex items-center gap-3 cursor-pointer">
            <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs">FA</div>
            <div class="flex-grow overflow-hidden">
              <div class="flex justify-between items-center mb-0.5">
                <span class="font-bold text-primary text-xs">Fatima Al-Zahra</span>
                <span class="text-[8px] text-primary/60 font-semibold">10:15 AM</span>
              </div>
              <p class="text-[10px] text-primary/80 truncate">Ahmad was very prompt today in class...</p>
            </div>
          </div>
          <!-- Other Chat -->
          <div class="p-4 hover:bg-primary/5 flex items-center gap-3 cursor-pointer transition-colors">
            <div class="w-9 h-9 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-xs">A</div>
            <div class="flex-grow overflow-hidden">
              <div class="flex justify-between items-center mb-0.5">
                <span class="font-bold text-primary text-xs">Academic Office</span>
                <span class="text-[8px] text-primary/60 font-semibold">Yesterday</span>
              </div>
              <p class="text-[10px] text-primary/60 truncate">Please approve the syllabus revision proposal.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Active Conversation Area -->
      <div class="flex-grow flex flex-col justify-between bg-transparent/40">
        <!-- Chat Header -->
        <div class="p-4 border-b border-primary/10 bg-white flex justify-between items-center">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-black text-sm"><?php echo strtoupper(substr($teacher['name'], 0, 2)); ?></div>
            <div>
              <h4 class="font-bold text-primary text-xs"><?php echo htmlspecialchars($teacher['name']); ?></h4>
              <p class="text-[9px] text-primary/60 uppercase tracking-widest font-bold"><?php echo htmlspecialchars($teacher['status']); ?></p>
            </div>
          </div>
          <button class="p-2 text-primary/50 hover:text-primary hover:bg-primary/5 rounded-xl transition-colors">
            <i data-lucide="info" class="w-4.5 h-4.5"></i>
          </button>
        </div>

        <!-- Message History -->
        <div class="p-6 flex-grow overflow-y-auto space-y-4 flex flex-col items-center justify-center">
          <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <i data-lucide="message-square-dashed" class="w-8 h-8 text-primary/20"></i>
          </div>
          <p class="text-xs font-bold text-primary/40 uppercase tracking-widest">No secure message history for this session node.</p>
        </div>

        <!-- Chat Input Form -->
        <div class="p-4 bg-white border-t border-primary/10 flex gap-3 items-center">
          <input type="text" class="flex-grow px-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary outline-none" placeholder="Type your message...">
          <button class="bg-primary hover:bg-primary/90 text-white p-2 rounded-xl transition-all"><i data-lucide="send" class="w-4 h-4"></i></button>
        </div>
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
