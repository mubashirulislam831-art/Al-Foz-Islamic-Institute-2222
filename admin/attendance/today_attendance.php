<?php
/**
 * Al Foz Islamic Institute - Today's Attendance Desk
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Admin');

// Fetch today's classes from database
$today_classes = [];
$today_day = strtolower(date('l')); // e.g. 'monday'

if (isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE status IN ('Active', 'Trial') AND `" . $today_day . "_enabled` = 1");
        $stmt->execute();
        $today_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($today_students as $s) {
            $student_id = $s['id'];
            $student_name = $s['name'];
            $student_country = $s['country'] ?: 'Pakistan';
            $student_timezone = $s['timezone'] ?: 'PKT';
            $class_time_local = $s[$today_day . '_time'] ?: '00:00';
            $duration = $s[$today_day . '_duration'] ?: '30';
            
            $teacher_name = $s['teacher_name'] ?: 'Unassigned';
            $teacher_country = 'Pakistan';
            $teacher_timezone = 'PKT';
            
            if ($teacher_name !== 'Unassigned') {
                $stmt_t = $pdo->prepare("SELECT country, timezone FROM teachers WHERE name = ? LIMIT 1");
                $stmt_t->execute([$teacher_name]);
                $t_info = $stmt_t->fetch(PDO::FETCH_ASSOC);
                if ($t_info) {
                    $teacher_country = $t_info['country'] ?: 'Pakistan';
                    $teacher_timezone = $t_info['timezone'] ?: 'PKT';
                }
            }
            
            $student_local_time = $class_time_local . ' (' . $student_timezone . ')';
            $teacher_local_time = calculate_pkt_time($class_time_local, $student_timezone) . ' (' . $teacher_timezone . ')';
            $student_picture = 'https://ui-avatars.com/api/?name=' . urlencode($student_name) . '&background=184D55&color=F7FAFF';
            
            // Format class end time nicely
            $end_time = date('H:i', strtotime($class_time_local) + ($duration * 60));
            
            $today_classes[] = [
                'id' => $student_id,
                'student_picture' => $student_picture,
                'student_name' => $student_name,
                'student_country' => $student_country,
                'teacher_name' => $teacher_name,
                'teacher_country' => $teacher_country,
                'student_local_time' => $student_local_time,
                'teacher_local_time' => $teacher_local_time,
                'class_time' => $class_time_local . ' - ' . $end_time,
                'duration' => $duration . ' Min'
            ];
        }
    } catch (PDOException $ex) {
        // Fallback to empty array
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent font-['Poppins']">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Module Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-primary/5 rounded-xl flex items-center justify-center text-primary shrink-0 border border-primary/10">
            <i data-lucide="clock-4" class="w-7 h-7"></i>
        </div>
        <div>
          <h1 class="text-2xl font-black text-primary tracking-tight">Today's Live Classes</h1>
          <p class="text-xs text-primary/60 mt-1 font-medium">Monitor and control live session attendance</p>
        </div>
      </div>
      <div class="text-right bg-primary/5 px-6 py-3 rounded-xl border border-primary/10">
        <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Current Node Time</p>
        <p class="text-xl font-black text-primary timer-font" id="global-timer">00:00:00</p>
      </div>
    </div>

    <!-- Attendance Cards -->
    <div class="space-y-6">
      <?php if (empty($today_classes)): ?>
      <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-20 text-center">
        <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
          <i data-lucide="monitor-off" class="w-10 h-10 text-primary/20"></i>
        </div>
        <p class="text-sm font-bold text-primary/40 uppercase tracking-widest">No live sessions scheduled for today</p>
      </div>
      <?php endif; ?>

      <?php foreach($today_classes as $class): ?>
      <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm overflow-hidden class-card transition-all" id="class-card-<?php echo $class['id']; ?>">
        
        <!-- Top Info Bar -->
        <div class="bg-primary/5 border-b border-primary/10 p-5 flex flex-wrap items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-white shadow-sm shrink-0">
                    <img src="<?php echo $class['student_picture']; ?>" alt="Student" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-black text-primary text-lg"><?php echo $class['student_name']; ?></h3>
                    <div class="flex items-center gap-3 text-xs text-primary/60 mt-1">
                        <span class="flex items-center gap-1 font-medium"><i data-lucide="globe-2" class="w-3.5 h-3.5"></i> <?php echo $class['student_country']; ?></span>
                        <span class="flex items-center gap-1 font-medium"><i data-lucide="clock" class="w-3.5 h-3.5"></i> <?php echo $class['student_local_time']; ?></span>
                    </div>
                </div>
            </div>

            <div class="hidden md:block w-px h-12 bg-primary/10"></div>

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary shrink-0 border border-white shadow-sm">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-bold text-primary text-base"><?php echo $class['teacher_name']; ?></h3>
                    <div class="flex items-center gap-3 text-xs text-primary/60 mt-1">
                        <span class="flex items-center gap-1 font-medium"><i data-lucide="globe-2" class="w-3.5 h-3.5"></i> <?php echo $class['teacher_country']; ?></span>
                        <span class="flex items-center gap-1 font-medium"><i data-lucide="clock" class="w-3.5 h-3.5"></i> <?php echo $class['teacher_local_time']; ?></span>
                    </div>
                </div>
            </div>

            <div class="hidden md:block w-px h-12 bg-primary/10"></div>

            <div class="text-right">
                <p class="text-[10px] font-bold text-primary/50 uppercase tracking-widest mb-1">Scheduled Time</p>
                <div class="inline-block bg-white px-4 py-1.5 rounded-lg border border-primary/10 shadow-sm font-black text-primary text-sm">
                    <?php echo $class['class_time']; ?> <span class="text-primary/40 text-xs ml-1">(<?php echo $class['duration']; ?>)</span>
                </div>
            </div>
        </div>

        <!-- Timers & Controls Area -->
        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Status & Timers -->
                <div class="lg:col-span-5 flex flex-wrap gap-6" id="timers-container-<?php echo $class['id']; ?>">
                    <div class="status-indicator">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary/10 text-primary/60 font-black text-xs uppercase tracking-widest border border-primary/20">
                            <i data-lucide="circle-dashed" class="w-4 h-4"></i> Awaiting Start
                        </span>
                    </div>
                </div>

                <!-- Action Controls -->
                <div class="lg:col-span-7 flex flex-wrap items-center justify-end gap-3" id="controls-<?php echo $class['id']; ?>">
                    <button onclick="startClass(<?php echo $class['id']; ?>)" class="bg-primary text-white px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 hover:-translate-y-0.5 active:scale-95 transition-all shadow-[0_8px_20px_-6px_rgba(24,77,85,0.4)] flex items-center gap-2">
                        <i data-lucide="play-circle" class="w-5 h-5"></i> Start Class
                    </button>
                    <!-- Alternate Actions -->
                    <div class="relative group">
                        <button class="bg-gray-100 text-gray-600 px-4 py-3.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-gray-200 transition-all border border-gray-200 flex items-center gap-2">
                            Options <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>
                        <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10 flex flex-col overflow-hidden">
                            <button onclick="markStatus(<?php echo $class['id']; ?>, 'student-leave')" class="text-left px-4 py-3 text-xs font-bold text-orange-600 hover:bg-orange-50 border-b border-gray-50">Student On Leave</button>
                            <button onclick="markStatus(<?php echo $class['id']; ?>, 'teacher-leave')" class="text-left px-4 py-3 text-xs font-bold text-purple-600 hover:bg-purple-50">Teacher On Leave</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Form Area (Lesson, Remarks, Reschedule) -->
            <div id="form-area-<?php echo $class['id']; ?>" class="mt-8 hidden border-t border-primary/10 pt-8">
                <!-- Injected via JS -->
            </div>
        </div>

      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<link rel="stylesheet" href="css/attendance.css">
<!-- Need Lucide Icons again because dynamic HTML injection might drop them if not rendered -->
<script src="https://unpkg.com/lucide@latest"></script>
<script src="js/attendance.js"></script>

<script>
    // Live Clock
    setInterval(() => {
        const now = new Date();
        document.getElementById('global-timer').textContent = now.toLocaleTimeString('en-US', { hour12: false });
    }, 1000);

    // Globals
    window.sessionData = {};

    function startClass(id) {
        const controls = document.getElementById(`controls-${id}`);
        const timers = document.getElementById(`timers-container-${id}`);
        
        window.sessionData[id] = {
            startTime: new Date().getTime(),
            waitTimerInterval: null,
            durationInterval: null,
            status: 'Teacher Joined'
        };

        // UI Update for Teacher Joined
        if(timers) timers.innerHTML = `
            <div class="flex flex-col gap-1.5 min-w-[120px]">
                <span class="text-[9px] font-bold text-primary/50 uppercase tracking-widest">Teacher Waited</span>
                <div class="timer-badge bg-primary/10 text-primary border border-primary/20 font-black text-xl py-2 px-4 rounded-xl text-center" id="teacher-timer-${id}">00:00:00</div>
            </div>
        `;
        
        if(controls) controls.innerHTML = `
            <button onclick="studentJoined(${id})" class="bg-[#10b981] text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#10b981]/90 hover:-translate-y-0.5 transition-all shadow-[0_8px_20px_-6px_rgba(16,185,129,0.3)] flex items-center gap-2">
                <i data-lucide="user-check" class="w-4 h-4"></i> Student Joined
            </button>
            <button onclick="markStatus(${id}, 'not-joined')" class="bg-[#eab308] text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#eab308]/90 hover:-translate-y-0.5 transition-all shadow-[0_8px_20px_-6px_rgba(234,179,8,0.3)] flex items-center gap-2">
                <i data-lucide="user-x" class="w-4 h-4"></i> Not Joined
            </button>
        `;
        
        lucide.createIcons();

        // Start Wait Timer
        let elapsed = 0;
        window.sessionData[id].waitTimerInterval = setInterval(() => {
            elapsed++;
            const tt = document.getElementById(`teacher-timer-${id}`); if(tt) tt.innerText = formatTime(elapsed);
        }, 1000);
    }

    function studentJoined(id) {
        // Stop Wait Timer
        clearInterval(window.sessionData[id].waitTimerInterval);
        
        const controls = document.getElementById(`controls-${id}`);
        const timers = document.getElementById(`timers-container-${id}`);
        const formArea = document.getElementById(`form-area-${id}`);
        
        if(timers) timers.innerHTML += `
            <div class="flex flex-col gap-1.5 min-w-[120px]">
                <span class="text-[9px] font-bold text-green-600/70 uppercase tracking-widest">Class Duration</span>
                <div class="timer-badge bg-green-500 text-white font-black text-xl py-2 px-4 rounded-xl text-center shadow-[0_4px_12px_rgba(16,185,129,0.2)]" id="class-timer-${id}">00:00:00</div>
            </div>
        `;

        if(controls) controls.innerHTML = `
            <span class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-green-50 text-green-600 font-black text-xs uppercase tracking-widest border border-green-200 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Class In Progress
            </span>
            <button onclick="endClass(${id}, 'present')" class="bg-red-500 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-600 transition-all shadow-sm flex items-center gap-2 ml-4">
                <i data-lucide="square" class="w-4 h-4"></i> End Class
            </button>
        `;

        lucide.createIcons();

        // Start Duration Timer
        let elapsed = 0;
        window.sessionData[id].durationInterval = setInterval(() => {
            elapsed++;
            const ct = document.getElementById(`class-timer-${id}`); if(ct) ct.innerText = formatTime(elapsed);
        }, 1000);

        // Show Lesson & Remarks form
        showLessonForm(id);
    }

    function markStatus(id, status) {
        // Stop any running timers
        if(window.sessionData[id]) {
            clearInterval(window.sessionData[id].waitTimerInterval);
            clearInterval(window.sessionData[id].durationInterval);
        }

        const timers = document.getElementById(`timers-container-${id}`);
        const formArea = document.getElementById(`form-area-${id}`);
        const controls = document.getElementById(`controls-${id}`);
        
        formArea.classList.remove('hidden');

        let badgeHtml = '';
        if(status === 'not-joined') {
            badgeHtml = `<span class="inline-flex px-4 py-2 rounded-xl bg-yellow-100 text-yellow-700 font-black text-xs uppercase tracking-widest border border-yellow-200">Student Not Joined</span>`;
            if(timers) timers.innerHTML += badgeHtml;
            if(controls) controls.innerHTML = `<button onclick="showRescheduleForm(${id})" class="bg-blue-600 text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-sm">Reschedule Class</button>`;
        } else if(status === 'student-leave') {
            if(timers) timers.innerHTML = `<span class="inline-flex px-4 py-2 rounded-xl bg-orange-100 text-orange-700 font-black text-xs uppercase tracking-widest border border-orange-200">Student On Leave</span>`;
            if(controls) controls.innerHTML = `<button onclick="showRescheduleForm(${id})" class="bg-blue-600 text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-sm">Reschedule Class</button>`;
        } else if(status === 'teacher-leave') {
            if(timers) timers.innerHTML = `<span class="inline-flex px-4 py-2 rounded-xl bg-purple-100 text-purple-700 font-black text-xs uppercase tracking-widest border border-purple-200">Teacher On Leave</span>`;
            if(controls) controls.innerHTML = `<button onclick="showRescheduleForm(${id})" class="bg-blue-600 text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-sm">Reschedule Class</button>`;
        }
    }

    function endClass(id, finalStatus) {
        if(window.sessionData[id]) {
            clearInterval(window.sessionData[id].durationInterval);
        }
        const controls = document.getElementById(`controls-${id}`);
        if(controls) controls.innerHTML = `
            <span class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gray-100 text-gray-600 font-black text-xs uppercase tracking-widest border border-gray-200 shadow-sm">
                Session Ended
            </span>
        `;
    }

    function showLessonForm(id) {
        const formArea = document.getElementById(`form-area-${id}`);
        formArea.classList.remove('hidden');
        if(formArea) formArea.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-primary/[0.02] p-6 rounded-2xl border border-primary/10">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-primary/50 mb-2 block flex items-center gap-2">
                        <i data-lucide="book-open" class="w-3.5 h-3.5"></i> Lesson Covered
                    </label>
                    <textarea id="lesson-${id}" class="w-full h-28 p-4 rounded-xl border border-primary/20 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all resize-none shadow-inner" placeholder="e.g. Surah Yaseen, Page 12, Tajweed Rules..."></textarea>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-primary/50 mb-2 block flex items-center gap-2">
                        <i data-lucide="message-square" class="w-3.5 h-3.5"></i> Remarks & Feedback
                    </label>
                    <textarea id="remarks-${id}" class="w-full h-28 p-4 rounded-xl border border-primary/20 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all resize-none shadow-inner" placeholder="e.g. Excellent recitation, needs focus on Makharij..."></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6" id="submit-action-${id}">
                <button onclick="submitAttendance(${id})" class="bg-primary text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-[0_4px_12px_rgba(24,77,85,0.3)] flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Submit & Lock
                </button>
            </div>
        `;
        lucide.createIcons();
    }

    function showRescheduleForm(id) {
        const formArea = document.getElementById(`form-area-${id}`);
        if(formArea) formArea.innerHTML = `
            <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                <h4 class="text-sm font-bold text-blue-900 mb-4 flex items-center gap-2"><i data-lucide="calendar-clock" class="w-5 h-5 text-blue-600"></i> Reschedule Class</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-blue-800/60 mb-2 block">New Date</label>
                        <input type="date" class="w-full p-3 rounded-xl border border-blue-200 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-blue-800/60 mb-2 block">New Time</label>
                        <input type="time" class="w-full p-3 rounded-xl border border-blue-200 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-widest text-blue-800/60 mb-2 block">Duration</label>
                        <select class="w-full p-3 rounded-xl border border-blue-200 text-sm focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none bg-white">
                            <option>30 Minutes</option>
                            <option>45 Minutes</option>
                            <option>60 Minutes</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="submitMakeup(${id})" class="bg-blue-600 text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-md">
                        Save Makeup Class
                    </button>
                </div>
            </div>
        `;
        lucide.createIcons();
    }

    function submitAttendance(id) {
        const formArea = document.getElementById(`form-area-${id}`);
        const inputs = formArea.querySelectorAll('textarea, input');
        inputs.forEach(input => {
            input.disabled = true;
            input.classList.add('bg-gray-50', 'opacity-70');
        });

        const sub = document.getElementById(`submit-action-${id}`);
        if(sub) sub.innerHTML = `
            <button onclick="editAttendance(${id})" class="bg-white border border-primary/20 text-primary px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
            </button>
            <span class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-green-50 text-green-700 font-black text-xs uppercase tracking-widest border border-green-200">
                <i data-lucide="lock" class="w-4 h-4"></i> Locked
            </span>
        `;
        lucide.createIcons();
    }

    function editAttendance(id) {
        const formArea = document.getElementById(`form-area-${id}`);
        const inputs = formArea.querySelectorAll('textarea, input');
        inputs.forEach(input => {
            input.disabled = false;
            input.classList.remove('bg-gray-50', 'opacity-70');
        });
        
        const sub = document.getElementById(`submit-action-${id}`);
        if(sub) sub.innerHTML = `
            <button onclick="submitAttendance(${id})" class="bg-primary text-white px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-md flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Update & Lock
            </button>
        `;
        lucide.createIcons();
    }

    function submitMakeup(id) {
        const fa = document.getElementById(`form-area-${id}`);
        if(fa) fa.innerHTML = `
            <div class="bg-blue-50 text-blue-800 p-4 rounded-xl text-center font-bold text-sm border border-blue-200">
                Makeup class scheduled successfully.
            </div>
        `;
    }

    function formatTime(totalSeconds) {
        const h = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
        const m = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
        const s = (totalSeconds % 60).toString().padStart(2, '0');
        return `${h}:${m}:${s}`;
    }
</script>

