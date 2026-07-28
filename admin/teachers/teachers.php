<?php
/**
 * Al Foz Islamic Institute - Super Admin Teachers Teachers
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/teachers_data.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);

$teachers = get_all_teachers();

// Calculate stats
$active_faculty = 0;
$probation = 0;
$training = 0;
$total_registered = count($teachers);

foreach ($teachers as $t) {
    if ($t['status'] === 'Active') $active_faculty++;
    if ($t['status'] === 'Probation') $probation++;
    if ($t['status'] === 'Training') $training++;
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Our Teachers (Scholars Teachers)</h1>
        <p class="text-xs text-primary/70 uppercase tracking-wider font-bold mt-1">Manage and audit all institute scholars, assignment structures, and lifecycle metrics.</p>
      </div>
      <div>
        <a href="add_teacher.php" class="bg-primary hover:bg-primary/95 text-[#F7FAFF] px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all active:scale-95 inline-block">
          + Onboard New Scholar
        </a>
      </div>
    </div>

    <!-- Quick Stats Headers for Teachers Teachers -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white p-4 rounded-xl border border-primary/10 flex justify-between items-center">
        <div>
          <span class="text-[9px] text-primary/60 font-bold uppercase">Active Faculty</span>
          <div class="text-xl font-black text-primary"><?php echo $active_faculty; ?></div>
        </div>
        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
          <i data-lucide="shield-check" class="w-4 h-4"></i>
        </div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-primary/10 flex justify-between items-center">
        <div>
          <span class="text-[9px] text-primary/60 font-bold uppercase">Probation Scholars</span>
          <div class="text-xl font-black text-amber-600"><?php echo $probation; ?></div>
        </div>
        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
          <i data-lucide="activity" class="w-4 h-4"></i>
        </div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-primary/10 flex justify-between items-center">
        <div>
          <span class="text-[9px] text-primary/60 font-bold uppercase">Under Training</span>
          <div class="text-xl font-black text-indigo-600"><?php echo $training; ?></div>
        </div>
        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
          <i data-lucide="book-open" class="w-4 h-4"></i>
        </div>
      </div>
      <div class="bg-white p-4 rounded-xl border border-primary/10 flex justify-between items-center">
        <div>
          <span class="text-[9px] text-primary/60 font-bold uppercase">Total Registered</span>
          <div class="text-xl font-black text-primary"><?php echo $total_registered; ?></div>
        </div>
        <div class="p-2 bg-primary/5 text-primary rounded-lg">
          <i data-lucide="users" class="w-4 h-4"></i>
        </div>
      </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
      <div class="overflow-x-auto min-h-[220px]">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-primary/5 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Scholars ID & Info</th>
              <th class="p-4">Employee ID</th>
              <th class="p-4">Geographics & Currency</th>
              <th class="p-4">Teacher Status</th>
              <th class="p-4">Joining Date</th>
              <th class="p-4">Assigned Students</th>
              <th class="p-4">Est. Salary</th>
              <th class="p-4 text-right">Quick Profile Portals</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80">
            <?php if (empty($teachers)): ?>
              <tr>
                <td colspan="8" class="p-10 text-center text-primary/40 font-bold uppercase tracking-widest text-[10px]">No scholars registered in global node yet.</td>
              </tr>
            <?php else: ?>
              <?php foreach($teachers as $teacher): ?>
                <tr class="hover:bg-primary/5 transition-colors">
                  <td class="p-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-xs border border-primary/15 shadow-sm">
                        <?php echo htmlspecialchars(implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $teacher['name'])))); ?>
                      </div>
                      <div>
                        <div class="font-extrabold text-primary text-xs"><?php echo htmlspecialchars($teacher['name']); ?></div>
                        <div class="text-[9px] font-bold text-primary/60">ID: <?php echo htmlspecialchars($teacher['id']); ?> • <?php echo htmlspecialchars($teacher['specialization'] ?? 'General'); ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="p-4 font-mono font-bold text-primary"><?php echo htmlspecialchars($teacher['employee_id'] ?? 'N/A'); ?></td>
                  <td class="p-4">
                    <div class="font-semibold text-xs text-primary/90"><?php echo htmlspecialchars($teacher['location'] ?? 'Remote'); ?></div>
                    <div class="text-[9px] font-bold text-primary/60 uppercase"><?php echo htmlspecialchars($teacher['timezone'] ?? 'UTC'); ?> • <?php echo htmlspecialchars($teacher['currency'] ?? 'USD'); ?></div>
                  </td>
                  <td class="p-4">
                    <span class="px-2 py-0.5 bg-primary/10 text-primary rounded text-[9px] font-bold uppercase tracking-wider"><?php echo htmlspecialchars($teacher['status']); ?></span>
                  </td>
                  <td class="p-4 font-semibold text-primary/80"><?php echo htmlspecialchars($teacher['joining_date'] ?? 'N/A'); ?></td>
                  <td class="p-4">
                    <div class="text-xs font-bold text-primary"><?php echo count($teacher['students'] ?? []); ?> Students</div>
                  </td>
                  <td class="p-4 font-black text-primary"><?php echo htmlspecialchars($teacher['salary'] ?? '0'); ?> <?php echo htmlspecialchars($teacher['currency'] ?? ''); ?></td>
                  <td class="p-4 text-right relative">
                    <div class="relative inline-block text-left">
                      <!-- iOS Style Trigger Button -->
                      <button onclick="toggleTeacherMenu('<?php echo $teacher['id']; ?>', event)" class="w-10 h-10 inline-flex items-center justify-center bg-[#184D55] text-white rounded-2xl hover:bg-[#184D55]/90 transition-all cursor-pointer shadow-sm active:scale-95" title="Quick Profile Portals">
                        <i data-lucide="align-left" class="w-4 h-4"></i>
                      </button>

                      <!-- Floating iOS Bar Menu -->
                      <div id="menu-<?php echo $teacher['id']; ?>" class="teacher-portal-menu absolute right-full top-1/2 -translate-y-1/2 bg-[#184D55] text-white flex items-center gap-1.5 p-1.5 rounded-2xl shadow-2xl border border-white/10 opacity-0 scale-95 pointer-events-none transition-all duration-200 z-30 mr-2 whitespace-nowrap">
                        
                        <!-- Profile -->
                        <a href="teacher_profile.php?id=<?php echo $teacher['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Profile">
                          <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Profile</span>
                        </a>

                        <!-- Edit -->
                        <a href="edit_teacher.php?id=<?php echo $teacher['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Edit">
                          <i data-lucide="edit-3" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Edit</span>
                        </a>

                        <!-- Students -->
                        <a href="teacher_students.php?id=<?php echo $teacher['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Students">
                          <i data-lucide="users" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Students</span>
                        </a>

                        <!-- Schedule -->
                        <a href="teacher_schedule.php?id=<?php echo $teacher['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Schedule">
                          <i data-lucide="calendar" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Schedule</span>
                        </a>

                        <!-- Attendance -->
                        <a href="teacher_attendance.php?id=<?php echo $teacher['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Attendance">
                          <i data-lucide="clipboard-check" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Attendance</span>
                        </a>

                        <!-- Salary -->
                        <a href="teacher_salary.php?id=<?php echo $teacher['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Salary">
                          <i data-lucide="wallet" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Salary</span>
                        </a>

                        <!-- Reports -->
                        <a href="teacher_reports.php?id=<?php echo $teacher['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Reports">
                          <i data-lucide="line-chart" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Reports</span>
                        </a>

                        <!-- Timeline -->
                        <a href="teacher_timeline.php?id=<?php echo $teacher['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Timeline">
                          <i data-lucide="history" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Timeline</span>
                        </a>

                        <!-- Delete Action -->
                        <a href="teacher_profile.php?id=<?php echo $teacher['id']; ?>#profile-section" onclick="window.location.href='teacher_profile.php?id=<?php echo $teacher['id']; ?>#delete-modal'; return false;" class="group relative w-8 h-8 flex items-center justify-center text-rose-300 bg-[#184D55] hover:bg-rose-600/20 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Delete">
                          <i data-lucide="trash-2" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-rose-950 text-rose-200 text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Delete</span>
                        </a>

                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
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

  function toggleTeacherMenu(teacherId, event) {
    event.stopPropagation();
    const menuId = 'menu-' + teacherId;
    const targetMenu = document.getElementById(menuId);
    if (!targetMenu) return;
    
    // Close all other menus first
    document.querySelectorAll('.teacher-portal-menu').forEach(menu => {
      if (menu.id !== menuId) {
        menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        if (menu.parentElement) {
          menu.parentElement.classList.remove('z-50');
        }
      }
    });
    
    // Toggle the clicked menu
    const isClosed = targetMenu.classList.contains('opacity-0');
    if (isClosed) {
      targetMenu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
      if (targetMenu.parentElement) {
        targetMenu.parentElement.classList.add('z-50');
      }
    } else {
      targetMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
      if (targetMenu.parentElement) {
        targetMenu.parentElement.classList.remove('z-50');
      }
    }
  }

  // Close menus when clicking anywhere else on the document
  document.addEventListener('click', function(event) {
    if (event.target.closest('.teacher-portal-menu')) return;
    document.querySelectorAll('.teacher-portal-menu').forEach(menu => {
      menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
      if (menu.parentElement) {
        menu.parentElement.classList.remove('z-50');
      }
    });
  });
</script>
