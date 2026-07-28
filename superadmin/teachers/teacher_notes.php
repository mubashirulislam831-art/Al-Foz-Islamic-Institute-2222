<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Notes & Warnings
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Notes & Warnings List -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h2 class="text-lg font-bold text-primary">Internal Notes & Warnings</h2>
            <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Official dossier remarks and administrative warnings.</p>
          </div>
        </div>

        <div class="space-y-4">
          <!-- Item 1: Warning -->
          <div class="p-4 border border-rose-500/10 rounded-xl bg-rose-50/30 flex gap-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
              <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="flex-grow">
              <div class="flex justify-between items-start">
                <span class="text-[9px] bg-rose-100 text-rose-800 font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">Official Warning</span>
                <span class="text-[9px] text-primary/50 font-bold">12 May 2026</span>
              </div>
              <h4 class="font-bold text-primary text-xs mt-1.5">Repeated Late Classes Alert</h4>
              <p class="text-xs text-primary/70 mt-1 leading-relaxed">The teacher was logged starting 3 classes more than 10 minutes late without prior notice to the operations team. Standard attendance compliance warning was dispatched.</p>
              <div class="text-[9px] text-rose-700 font-bold uppercase tracking-wider mt-2">Action: Resolved - Teacher replied with apology</div>
            </div>
          </div>

          <!-- Item 2: Note -->
          <div class="p-4 border border-primary/10 rounded-xl bg-transparent flex gap-4">
            <div class="w-10 h-10 rounded-full bg-primary/5 text-primary flex items-center justify-center shrink-0">
              <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <div class="flex-grow">
              <div class="flex justify-between items-start">
                <span class="text-[9px] bg-primary/10 text-primary font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">Internal Note</span>
                <span class="text-[9px] text-primary/50 font-bold">28 Apr 2026</span>
              </div>
              <h4 class="font-bold text-primary text-xs mt-1.5">Excellent Tajweed Lecture Series</h4>
              <p class="text-xs text-primary/70 mt-1 leading-relaxed">Conducted a marvelous supplementary Tajweed webinar for advanced students. Parents feedback was exceptionally high. Recognized during the quarterly administrative review.</p>
              <div class="text-[9px] text-primary/50 mt-2 font-bold uppercase">Logged by: Admin Sarah</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add Note Form -->
      <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 h-fit">
        <h3 class="text-sm font-bold text-primary uppercase tracking-wider mb-4 flex items-center gap-1.5">
          <i data-lucide="plus-circle" class="w-4 h-4 text-primary"></i> Add New Log Entry
        </h3>
        <form action="#" method="POST" onsubmit="event.preventDefault();" class="space-y-4">
          <div>
            <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Entry Type</label>
            <select class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none">
              <option>Internal Note (Regular)</option>
              <option>Performance Commendation</option>
              <option>Official Warning (Late/No-Show)</option>
              <option>Policy Violation Warning</option>
            </select>
          </div>
          <div>
            <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Title</label>
            <input type="text" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none" placeholder="e.g. Completed trial onboarding">
          </div>
          <div>
            <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Detailed Description</label>
            <textarea class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs text-primary outline-none h-24" placeholder="Enter comments, details or warnings here..."></textarea>
          </div>
          <button type="submit" class="w-full py-2 bg-primary text-white font-bold text-xs uppercase tracking-wider rounded-lg hover:bg-primary/90 transition-colors">Save Entry</button>
        </form>
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
