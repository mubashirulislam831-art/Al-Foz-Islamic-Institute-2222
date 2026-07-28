<?php
/**
 * Al Foz Islamic Institute - Shared ERP Top Navbar
 */
require_once __DIR__ . '/system_config.php';
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'System User';
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest';
$sys_date = get_system_month_year();
?>
<header class="flex items-center justify-between bg-white border border-primary/10 px-6 py-4.5 shadow-sm mb-6 rounded-[18px]">
  <!-- Left Side: Page Title or Toggle -->
  <div class="flex items-center gap-3">
    <button id="sidebar-toggle" title="Menu Toggle" class="p-2 hover:bg-primary/5 rounded-xl text-primary transition-all">
      <i data-lucide="menu" class="w-5 h-5"></i>
    </button>
    
    <!-- System Date Context Selector -->
    <div class="flex items-center gap-2 px-3 py-1.5 bg-primary/5 border border-primary/10 rounded-xl" title="Select System Date Context">
      <i data-lucide="calendar" class="w-4 h-4 text-primary opacity-60"></i>
      <form action="/api/set_system_date.php" method="POST" id="dateSelectorForm" class="flex items-center gap-1">
        <select name="month" onchange="document.getElementById('dateSelectorForm').submit()" class="!bg-transparent text-[11px] font-bold text-primary focus:outline-none !border-0 !outline-none !ring-0 !shadow-none appearance-none cursor-pointer !pr-1 !p-0 w-auto">
          <?php 
            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            foreach($months as $m):
          ?>
            <option value="<?php echo $m; ?>" <?php echo ($sys_date['month'] === $m) ? 'selected' : ''; ?>><?php echo $m; ?></option>
          <?php endforeach; ?>
        </select>
        <select name="year" onchange="document.getElementById('dateSelectorForm').submit()" class="!bg-transparent text-[11px] font-bold text-primary focus:outline-none !border-0 !outline-none !ring-0 !shadow-none appearance-none cursor-pointer !p-0 w-auto">
          <?php for($y = 2024; $y <= 2030; $y++): ?>
            <option value="<?php echo $y; ?>" <?php echo ($sys_date['year'] == $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
          <?php endfor; ?>
        </select>
        <input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
      </form>
    </div>

    <!-- Real-time Ticking Digital Clock -->
    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-primary/5 border border-primary/10 rounded-xl font-mono text-[11px] font-bold text-primary" id="realtime-clock" title="Your Local Real-Time Clock">
      <i data-lucide="clock" class="w-4 h-4 text-primary shrink-0"></i>
      <span id="clock-display">--:--:-- --</span>
      <span class="text-[9px] text-primary/60" id="clock-tz"></span>
    </div>
  </div>

  <!-- Right Side: User Menu & Notification -->
  <div class="flex items-center gap-4">
    <!-- User Quick Info -->
    <div class="text-right">
      <h3 class="text-xs font-bold text-primary leading-tight"><?php echo htmlspecialchars($user_name); ?></h3>
      <span class="text-[9px] font-bold uppercase tracking-wider text-primary leading-none bg-primary/10 px-2.5 py-1 rounded-full inline-block mt-0.5"><?php echo htmlspecialchars($user_role); ?></span>
    </div>

    <!-- Quick Logout -->
    <a href="/auth/logout.php" class="p-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all active:scale-95 shadow-sm flex items-center gap-1.5 text-xs font-bold">
      <i data-lucide="log-out" class="w-4 h-4"></i>
      <span class="hidden md:inline">Sign Out</span>
    </a>
  </div>
</header>

<script>
  function updateRealTimeClock() {
    const clockDisplay = document.getElementById('clock-display');
    const clockTz = document.getElementById('clock-tz');
    if (!clockDisplay) return;
    
    const now = new Date();
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    const formattedTime = String(hours).padStart(2, '0') + ':' + minutes + ':' + seconds + ' ' + ampm;
    
    clockDisplay.textContent = formattedTime;
    
    try {
      const tzName = Intl.DateTimeFormat().resolvedOptions().timeZone;
      // Get short abbreviation like GMT+5 / PKT
      const shortTz = now.toLocaleDateString('en-US', { day: 'numeric', timeZoneName: 'short' }).split(', ')[1] || '';
      clockTz.textContent = shortTz + ' (' + tzName + ')';
    } catch(e) {
      clockTz.textContent = 'Local';
    }
  }
  
  setInterval(updateRealTimeClock, 1000);
  document.addEventListener('DOMContentLoaded', () => {
    updateRealTimeClock();
  });
</script>
