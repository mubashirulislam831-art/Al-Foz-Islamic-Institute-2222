<?php
/**
 * Al Foz Islamic Institute - Super Admin Daily Attendance
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Admin');

$students = get_all_students();
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <a href="attendance.php" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1 mb-2">
          <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to Desk
        </a>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Today's Attendance Roll</h1>
      </div>
      <div class="flex items-center gap-3">
        <div class="bg-white border border-primary/10 px-4 py-2 rounded-xl text-xs font-bold text-primary flex items-center gap-2 shadow-sm">
          <i data-lucide="calendar" class="w-4 h-4 text-primary/60"></i> <?php echo date('d M Y, l'); ?>
        </div>
      </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white p-4 rounded-2xl border border-primary/10 shadow-sm mb-6 flex flex-col md:flex-row items-center gap-4">
      <div class="relative w-full md:w-64">
        <i data-lucide="search" class="w-4 h-4 text-primary/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" placeholder="Search student or teacher..." class="w-full pl-9 pr-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary focus:outline-none focus:border-primary/30 transition-colors placeholder:text-primary/40 font-medium">
      </div>
      <select class="w-full md:w-auto px-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30">
        <option value="">All Statuses</option>
        <option value="present">Present</option>
        <option value="not_joined">Student Not Joined</option>
        <option value="student_leave">Student On Leave</option>
        <option value="teacher_leave">Teacher On Leave</option>
        <option value="absent">Absent</option>
      </select>
    </div>

    <!-- Attendance Cards -->
    <div class="space-y-6">
      <?php if (empty($students)): ?>
        <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-10 text-center">
          <i data-lucide="users-2" class="w-12 h-12 text-primary/10 mx-auto mb-4"></i>
          <p class="text-xs text-primary/40 font-bold uppercase tracking-widest">No active seekers found for today's roll.</p>
        </div>
      <?php else: ?>
        <?php foreach ($students as $student): 
          $initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $student['name'])));
        ?>
          <!-- Student Card -->
          <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-primary/5 bg-transparent flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-lg border-2 border-white shadow-sm">
                  <?php echo $initials; ?>
                </div>
                <div>
                  <h3 class="font-black text-primary text-lg"><?php echo htmlspecialchars($student['name']); ?> <span class="text-xs font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-md ml-2 uppercase tracking-widest"><?php echo htmlspecialchars($student['student_id']); ?></span></h3>
                  <p class="text-xs font-bold text-primary/60 mt-0.5">Tr. <?php echo htmlspecialchars($student['teacher_name']); ?> • <?php echo htmlspecialchars($student['course']); ?></p>
                </div>
              </div>
              <div class="flex flex-wrap items-center gap-4 text-xs font-bold">
                <div class="bg-white border border-primary/10 px-3 py-1.5 rounded-lg text-primary shadow-sm flex items-center gap-1.5">
                  <i data-lucide="clock" class="w-3.5 h-3.5 text-primary/50"></i> <?php echo htmlspecialchars($student['class_time']); ?>
                </div>
                <div class="bg-white border border-primary/10 px-3 py-1.5 rounded-lg text-emerald-600 shadow-sm flex items-center gap-1.5">
                  <i data-lucide="timer" class="w-3.5 h-3.5 text-emerald-500"></i> 30 mins
                </div>
              </div>
            </div>

            <div class="p-5 grid grid-cols-1 lg:grid-cols-12 gap-6">
              
              <!-- Timers & Actions (Left Col) -->
              <div class="lg:col-span-4 space-y-5">
                <div>
                  <h4 class="text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <i data-lucide="activity" class="w-3.5 h-3.5"></i> Attendance Actions
                  </h4>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <button class="bg-primary hover:bg-primary/90 text-white px-3 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-sm transition-all flex items-center justify-center gap-1.5 w-full">
                      <i data-lucide="play-circle" class="w-3.5 h-3.5"></i> Start Class
                    </button>
                    <button class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 px-3 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 w-full">
                      <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Std Joined
                    </button>
                    <button class="bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 px-3 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 w-full">
                      <i data-lucide="user-x" class="w-3.5 h-3.5"></i> Not Joined
                    </button>
                    <button class="bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 px-3 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 w-full">
                      <i data-lucide="calendar-off" class="w-3.5 h-3.5"></i> Std Leave
                    </button>
                    <button class="bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 px-3 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 w-full sm:col-span-2">
                      <i data-lucide="user-minus" class="w-3.5 h-3.5"></i> Teacher On Leave
                    </button>
                  </div>
                </div>

                <div class="bg-primary/5 rounded-xl p-4 border border-primary/10">
                  <h4 class="text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-3">System Timers</h4>
                  <div class="space-y-2">
                    <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                      <span class="text-[10px] font-bold text-primary/60 uppercase tracking-wider">Teacher Started</span>
                      <span class="font-mono font-bold text-primary text-xs">--:--</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                      <span class="text-[10px] font-bold text-primary/60 uppercase tracking-wider">Student Joined</span>
                      <span class="font-mono font-bold text-primary text-xs">--:--</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                      <span class="text-[10px] font-bold text-primary/60 uppercase tracking-wider">Waiting Time</span>
                      <span class="font-mono font-bold text-amber-600 text-xs">--</span>
                    </div>
                    <div class="flex justify-between items-center">
                      <span class="text-[10px] font-bold text-primary/60 uppercase tracking-wider">Teacher Ended</span>
                      <span class="font-mono font-bold text-primary text-xs">--:--</span>
                    </div>
                  </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-transparent rounded-xl border border-primary/10">
                  <span class="text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">Current Status</span>
                  <span class="text-[10px] font-bold px-2 py-1 bg-primary/10 text-primary rounded border border-primary/20 uppercase tracking-wider"><?php echo htmlspecialchars($student['attendance_status'] ?? 'Pending'); ?></span>
                </div>
              </div>

              <!-- Lesson Entry & Submit (Right Col) -->
              <div class="lg:col-span-8">
                <h4 class="text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                  <i data-lucide="book-open" class="w-3.5 h-3.5"></i> Lesson Details
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                  <div>
                    <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-1.5">Current Lesson</label>
                    <input type="text" placeholder="e.g., Para 5" class="w-full px-3 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary focus:outline-none focus:border-primary/30 font-medium transition-colors">
                  </div>
                  <div>
                    <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-1.5">Completed Lesson</label>
                    <input type="text" placeholder="e.g., Ruku 2" class="w-full px-3 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary focus:outline-none focus:border-primary/30 font-medium transition-colors">
                  </div>
                  <div>
                    <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-1.5">Next Lesson</label>
                    <input type="text" placeholder="e.g., Para 5, Ruku 3" class="w-full px-3 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary focus:outline-none focus:border-primary/30 font-medium transition-colors">
                  </div>
                </div>

                <div class="mb-4">
                  <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-1.5">Homework</label>
                  <input type="text" placeholder="Assigned homework tasks..." class="w-full px-3 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary focus:outline-none focus:border-primary/30 font-medium transition-colors">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                  <div>
                    <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-1.5">Daily Remarks</label>
                    <textarea rows="2" placeholder="Session notes..." class="w-full px-3 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary focus:outline-none focus:border-primary/30 font-medium transition-colors resize-none"></textarea>
                  </div>
                  <div>
                    <label class="block text-[10px] font-bold text-primary/60 uppercase tracking-wider mb-1.5">Performance Notes</label>
                    <textarea rows="2" placeholder="Student performance..." class="w-full px-3 py-2 bg-transparent border border-primary/10 rounded-xl text-xs text-primary focus:outline-none focus:border-primary/30 font-medium transition-colors resize-none"></textarea>
                  </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-primary/5">
                  <button class="bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Submit & Save Class
                  </button>
                </div>
              </div>

            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
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

