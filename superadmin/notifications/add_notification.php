<?php
/**
 * Al Foz Islamic Institute - Super Admin Add/Send Broadcast Notification
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

    <div class="mb-8">
      <a href="notifications.php" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Broadcasting
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Send Broadcast Alert</h1>
      <p class="text-xs text-primary/60">Dispatch a new alert, portal notice, or SMS bulletin to seekers, parents, or scholars.</p>
    </div>

    <!-- Alert / Broadcast Form -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 max-w-2xl">
      <form action="notifications.php?action=created" method="POST" class="space-y-6">
        <div class="space-y-4">
          <div class="form-group">
            <label class="form-label">Message Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Eid-ul-Adha Holidays Notice" required>
          </div>

          <div class="form-group">
            <label class="form-label">Recipient Group</label>
            <select name="recipient_group" class="form-control" required>
              <option value="">Select Target Audience</option>
              <option value="All Scholars, Seekers & Parents">All Scholars, Seekers & Parents</option>
              <option value="Scholars (Teachers) Only">Scholars (Teachers) Only</option>
              <option value="Seekers (Students) Only">Seekers (Students) Only</option>
              <option value="Parents Only">Parents Only</option>
              <option value="Admin Staff Only">Admin Staff Only</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Broadcasting Channels</label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
              <label class="flex items-center gap-2 p-3 border border-primary/10 rounded-xl cursor-pointer hover:bg-primary/5 transition-colors">
                <input type="checkbox" name="channels[]" value="Portal Banner" class="accent-primary" checked>
                <span class="text-xs font-medium text-primary">Portal Banner</span>
              </label>
              <label class="flex items-center gap-2 p-3 border border-primary/10 rounded-xl cursor-pointer hover:bg-primary/5 transition-colors">
                <input type="checkbox" name="channels[]" value="SMS" class="accent-primary">
                <span class="text-xs font-medium text-primary">SMS Bulletin</span>
              </label>
              <label class="flex items-center gap-2 p-3 border border-primary/10 rounded-xl cursor-pointer hover:bg-primary/5 transition-colors">
                <input type="checkbox" name="channels[]" value="Email" class="accent-primary">
                <span class="text-xs font-medium text-primary">Email Dispatch</span>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Notification Body</label>
            <textarea name="message" rows="5" class="form-control" placeholder="Write the complete announcement text here..." required></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-primary/5">
          <a href="notifications.php" class="btn-erp btn-erp-secondary">Cancel</a>
          <button type="submit" class="btn-erp btn-erp-primary">Dispatch Broadcast</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
