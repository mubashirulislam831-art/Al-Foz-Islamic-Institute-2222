<?php
/**
 * Al Foz Islamic Institute - Super Admin Messages Hub
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

    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Messages Hub</h1>
        <p class="text-xs text-primary/60 mt-1">Send secure internal memos and view staff conversations.</p>
      </div>
      <div>
        <button onclick="alert('Broadcasting wizard active.')" class="bg-primary hover:bg-opacity-95 text-secondary px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all active:scale-95 inline-block">
          + Direct Message
        </button>
      </div>
    </div>

    <!-- Active Messages -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Contact List -->
      <div class="lg:col-span-4 bg-white rounded-2xl border border-primary/10 p-6 space-y-4">
        <h3 class="font-bold text-sm text-primary uppercase tracking-wider">Active Conversations</h3>
        <p class="text-[10px] text-primary/40 italic">No active conversations started yet.</p>
      </div>

      <!-- Chat Board Placeholder -->
      <div class="lg:col-span-8 bg-white rounded-2xl border border-primary/10 p-6 flex flex-col justify-center items-center min-h-[400px]">
        <div class="text-center">
            <i data-lucide="message-square" class="w-10 h-10 text-primary/10 mx-auto mb-4"></i>
            <p class="text-xs text-primary/40 font-bold uppercase tracking-widest">Select a conversation to start chatting.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
