<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Availability (Free Slots)
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
          <h2 class="text-lg font-bold text-primary">Weekly Availability Slots</h2>
          <p class="text-[10px] text-primary/70 uppercase tracking-wider font-semibold mt-1">Configure open time intervals for student matching.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="document.getElementById('add_slot_modal').classList.remove('hidden')" class="px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/90 transition-colors flex items-center gap-1">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Free Slot
            </button>
        </div>
      </div>

                  <!-- Days Grid of Availability -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-7 gap-4">
        <?php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $slots = [
            'Monday' => ['09:00 - 10:00', '10:00 - 11:00', '13:00 - 14:00'],
            'Tuesday' => ['11:00 - 12:00', '15:30 - 16:30'],
            'Wednesday' => ['09:00 - 10:00', '14:00 - 15:00'],
            'Thursday' => ['10:00 - 11:00', '16:00 - 17:00'],
            'Friday' => ['09:00 - 10:00', '11:00 - 12:00', '15:00 - 16:00'],
            'Saturday' => ['10:00 - 12:00'],
            'Sunday' => []
        ];
        foreach ($days as $day):
        ?>
        <div class="bg-transparent rounded-xl border border-primary/10 flex flex-col justify-between min-h-[220px]">
          <div class="p-3 border-b border-primary/10 bg-primary/5 rounded-t-xl flex justify-between items-center">
            <span class="font-bold text-xs text-primary uppercase tracking-wider"><?php echo $day; ?></span>
            <span class="text-[9px] bg-primary text-white font-black px-1.5 py-0.5 rounded-full"><?php echo count($slots[$day]); ?></span>
          </div>
          <div class="p-3 flex-grow space-y-2">
            <?php if (empty($slots[$day])): ?>
              <div class="text-center text-[10px] text-primary/40 italic py-6">No Slots</div>
            <?php else: ?>
              <?php foreach ($slots[$day] as $slot): ?>
                <div class="bg-white p-2 rounded-lg border border-primary/15 flex items-center justify-between group">
                  <span class="text-[10px] font-bold text-primary"><?php echo $slot; ?></span>
                  <button class="text-rose-500 hover:text-rose-700 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                  </button>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <div class="p-2 border-t border-primary/5">
            <button onclick="document.getElementById('add_slot_modal').classList.remove('hidden')" class="w-full py-1.5 bg-white hover:bg-primary/5 text-center text-[9px] font-bold uppercase tracking-wider text-primary/70 rounded-lg border border-primary/10 transition-all flex items-center justify-center gap-1">
              <i data-lucide="plus" class="w-2.5 h-2.5"></i> Add
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
  </div>

  <!-- Add Slot Modal -->
  <div id="add_slot_modal" class="hidden fixed inset-0 z-50 bg-primary/20 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-xl max-w-md w-full relative">
      <h3 class="text-sm font-black text-primary uppercase tracking-wider mb-4 flex items-center gap-2">
         <i data-lucide="clock" class="w-4 h-4"></i> Add Availability Slot
      </h3>
      <form action="#" method="POST" onsubmit="event.preventDefault(); document.getElementById('add_slot_modal').classList.add('hidden');" class="space-y-4">
        <div>
          <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Day of the Week</label>
          <select class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none">
            <?php foreach ($days as $day): ?>
              <option><?php echo $day; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">Start Time</label>
            <input type="time" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none">
          </div>
          <div>
            <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1">End Time</label>
            <input type="time" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-bold text-primary outline-none">
          </div>
        </div>
        <div class="flex justify-end gap-2 pt-4 border-t border-primary/10">
          <button type="button" onclick="document.getElementById('add_slot_modal').classList.add('hidden')" class="px-4 py-2 border border-primary/20 text-primary text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/5 transition-colors">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-primary/90 transition-colors">Save Slot</button>
        </div>
      </form>
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
