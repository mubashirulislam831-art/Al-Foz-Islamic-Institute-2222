<?php
/**
 * Al Foz Islamic Institute - Admin ERP Dashboard
 * Restricts access and renders coordinator configurations.
 */
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../auth/permissions.php';
require_once __DIR__ . '/../includes/students_data.php';
require_once __DIR__ . '/../includes/teachers_data.php';

// Strictly require Admin or higher roles
require_role(['Admin', 'Super Admin']);

$admin_name = $_SESSION['name'];
$admin_email = $_SESSION['email'];
$students = get_all_students();
$total_students = count($students);
$teachers = get_all_teachers();
$total_teachers = count($teachers);

// Calculate attendance rate
$present_today = 0;
foreach ($students as $s) {
    if (isset($s['attendance_status']) && $s['attendance_status'] === 'Present') {
        $present_today++;
    }
}
$attendance_today_pct = $total_students > 0 ? round(($present_today / $total_students) * 100, 1) : 0;
?>
<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 page-transition">
    
    <!-- Welcome Header -->
    <div class="mb-10 bg-transparent islamic-texture border border-primary/10 rounded-[22px] p-6 sm:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden shadow-sm">
      <div class="relative z-10">
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-primary">Assalamu Alaikum, <?php echo htmlspecialchars(explode(' ', $admin_name)[0]); ?>!</h2>
        <p class="text-xs sm:text-sm text-primary/75 mt-1">Manage student sessions, verify class schedules, and coordinates with global tutors.</p>
      </div>
      <div class="bg-white border border-primary/15 rounded-xl px-4 py-2.5 text-xs font-semibold flex items-center gap-2 relative z-10 text-primary shadow-sm">
        <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
        <span>Standard Sync: active</span>
      </div>
    </div>

    <!-- ADMIN STATS GRID -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
      
      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/60">Enrolled Students</span>
        <span class="text-3xl sm:text-4xl font-extrabold text-primary mt-3"><?php echo $total_students; ?></span>
        <span class="text-[10px] text-green-600 font-bold mt-2">Active Seekers</span>
      </div>

      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/60">Scheduled Sessions</span>
        <span class="text-3xl sm:text-4xl font-extrabold text-primary mt-3"><?php echo $total_students; ?></span>
        <span class="text-[10px] text-primary/70 font-bold mt-2">Active Rooms</span>
      </div>

      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/60">Class Completion</span>
        <span class="text-3xl sm:text-4xl font-extrabold text-green-600 mt-3"><?php echo $attendance_today_pct; ?>%</span>
        <span class="text-[10px] text-green-600 font-bold mt-2">Daily Attendance Rate</span>
      </div>

      <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/60">Vetted Teachers</span>
        <span class="text-3xl sm:text-4xl font-extrabold text-primary mt-3"><?php echo $total_teachers; ?></span>
        <span class="text-[10px] text-primary/70 font-bold mt-2">Active Staff</span>
      </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Left side modules -->
      <div class="lg:col-span-8 space-y-8">
        
        <!-- Scheduled Class Grid -->
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
          <div class="bg-primary/5 px-6 py-4 border-b border-primary/10 flex justify-between items-center">
            <h3 class="font-extrabold text-sm uppercase tracking-wider text-primary">Class Coordination Console</h3>
            <span class="bg-green-500/15 text-green-800 font-bold text-[10px] px-2.5 py-1 rounded-full uppercase">Class Active Live</span>
          </div>
          <div class="p-6 overflow-x-auto">
            <table class="w-full text-left text-xs text-primary/95">
              <thead>
                <tr class="border-b border-primary/10 uppercase font-bold text-primary/70 text-[10px] tracking-wider">
                  <th class="pb-3">Student Name</th>
                  <th class="pb-3">Teacher</th>
                  <th class="pb-3">Scheduled Timing</th>
                  <th class="pb-3">Session Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-primary/5" id="admin_active_sessions_tbody"></tbody>
            </table>
          </div>
        </div>

        <!-- Student Attendance Logs -->
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
          <div class="bg-primary/5 px-6 py-4 border-b border-primary/10">
            <h3 class="font-extrabold text-sm uppercase tracking-wider text-primary">Student Registration Sheets</h3>
          </div>
          <div class="p-6 overflow-x-auto">
            <table class="w-full text-left text-xs text-primary/95">
              <thead>
                <tr class="border-b border-primary/10 uppercase font-bold text-primary/70 text-[10px] tracking-wider">
                  <th class="pb-3">Candidate</th>
                  <th class="pb-3">Registration Date</th>
                  <th class="pb-3">Contact Email</th>
                  <th class="pb-3">Evaluation Link</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-primary/5" id="admin_registration_sheets_tbody"></tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- Right side panels -->
      <div class="lg:col-span-4 space-y-8">
        
        <!-- Coordination controls -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
          <h3 class="font-extrabold text-sm uppercase tracking-wider text-primary mb-4">Operations Center</h3>
          <div class="space-y-4">
            <button onclick="alert('Coordination Email: Transmitting calendar schedules to all faculty members.')" class="w-full text-center bg-primary text-secondary py-3.5 rounded-xl font-bold uppercase text-[10px] sm:text-xs tracking-wider shadow-md hover:bg-opacity-95 active:scale-95 transition-all">
              Broadcast Schedules to Tutors
            </button>
            <button onclick="alert('Parent notification center: Sending student evaluation remarks directly to parent emails.')" class="w-full text-center border-2 border-primary text-primary py-3 rounded-xl font-bold uppercase text-[10px] sm:text-xs tracking-wider hover:bg-primary hover:text-secondary transition-all">
              Email Monthly Remarks
            </button>
          </div>
        </div>

        <!-- Academic Advisor Quick Contacts -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm space-y-4">
          <h3 class="font-extrabold text-sm uppercase tracking-wider text-primary">Administration Channels</h3>
          <div class="text-xs space-y-3">
            <p class="flex items-center justify-between border-b border-primary/5 pb-2"><strong class="text-primary/75">CEO:</strong> <span class="text-primary font-black tracking-wide">Mubashir Ul Islam Awan</span></p>
            <p class="flex items-center justify-between border-b border-primary/5 pb-2"><strong class="text-primary/75">Sisters Section Lead:</strong> <span class="text-primary font-semibold">Nosheen Tabassum</span></p>
            <p class="flex items-center justify-between"><strong class="text-primary/75">Operational Supervisor:</strong> <span class="text-primary font-semibold">Ihtisham Awan</span></p>
          </div>
          <div class="h-px bg-primary/10"></div>
          <p class="text-[10px] text-primary/60 leading-relaxed font-semibold">For critical technical support or billing portal bugs, directly invoke Ihtisham Awan on WhatsApp.</p>
        </div>

      </div>

    </div>

  </main>

  <!-- DASHBOARD FOOTER -->
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
