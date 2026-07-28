<?php
/**
 * Al Foz Islamic Institute - Faculty Live Session Control Hub
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../auth/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_name = $_SESSION['name'] ?? 'Faculty Member';
$today_day = strtolower(date('l'));

// Fetch students assigned to this teacher
$all_students = get_all_students() ?: [];
$my_students = array_filter($all_students, function($s) use ($teacher_name) {
    return (isset($s['teacher_name']) && $s['teacher_name'] === $teacher_name);
});

// Currently active session (optional param)
$active_student_id = $_GET['student_id'] ?? '';
$active_student = null;
if ($active_student_id) {
    foreach ($my_students as $s) {
        $sid = $s['roll_no'] ?? $s['student_id'] ?? $s['id'] ?? '';
        if ($sid == $active_student_id) {
            $active_student = $s;
            break;
        }
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-[#F4F7F9] page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mt-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">LIVE CLASSROOM</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Active online portal & real-time tajweed evaluation desk</p>
      </div>
      <div class="bg-white border border-primary/10 px-4 py-2 rounded-xl text-xs font-bold text-primary flex items-center gap-2 shadow-sm">
        <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
        <span>Faculty Session Live</span>
      </div>
    </div>

    <!-- Active Class Selector -->
    <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-6 mb-8">
      <form method="GET" class="flex flex-col sm:flex-row items-end gap-4 max-w-xl">
        <div class="flex-grow w-full">
          <label class="block text-[10px] font-bold uppercase tracking-wider text-primary/70 mb-2">Launch Session for Student</label>
          <select name="student_id" onchange="this.form.submit()" class="w-full px-4 py-2 bg-transparent border border-primary/10 rounded-xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 outline-none">
            <option value="">-- Choose a student --</option>
            <?php foreach ($my_students as $student): 
              $student_id = $student['id'];
              $is_today = isset($student[$today_day . '_enabled']) && $student[$today_day . '_enabled'];
            ?>
              <option value="<?php echo htmlspecialchars($student_id); ?>" <?php echo $active_student_id == $student_id ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($student['name']); ?> (<?php echo htmlspecialchars($student_id); ?>) <?php echo $is_today ? '• Scheduled Today' : ''; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </form>
    </div>

    <?php if ($active_student): ?>
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Live Class Screen Simulator & Tools (Left Col) -->
        <div class="lg:col-span-8 space-y-6">
          
          <!-- Stream Board Simulator -->
          <div class="bg-slate-900 rounded-[28px] overflow-hidden border border-slate-800 shadow-xl relative aspect-video flex flex-col justify-between p-6">
            <!-- Background video mock decoration -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(15,23,42,0.8),rgba(2,6,23,1))] z-0"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:30px_30px] z-0"></div>
            
            <!-- Top Controls -->
            <div class="flex justify-between items-center relative z-10">
              <div class="bg-red-600 text-white font-extrabold text-[9px] uppercase tracking-widest px-3 py-1 rounded-full flex items-center gap-1.5 shadow-md">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-white animate-ping"></span> Live Broadcast
              </div>
              <div class="bg-slate-800/80 backdrop-blur-md text-slate-200 border border-slate-700/50 px-3 py-1 rounded-xl text-[10px] font-mono tracking-wider">
                ID: <?php echo htmlspecialchars($active_student_id); ?>
              </div>
            </div>

            <!-- Central Seeker Initials / Visual Mock -->
            <div class="flex flex-col items-center justify-center relative z-10 grow my-8">
              <div class="w-24 h-24 rounded-full bg-primary text-white border-4 border-slate-800 shadow-2xl flex items-center justify-center font-black text-3xl mb-4 transform hover:scale-105 transition-all">
                <?php echo strtoupper(substr($active_student['name'], 0, 2)); ?>
              </div>
              <h3 class="text-white font-black text-lg"><?php echo htmlspecialchars($active_student['name']); ?></h3>
              <p class="text-slate-400 text-xs mt-1">Conferencing Portal Ready • Reciting Quranic verses</p>
            </div>

            <!-- Bottom Controls / Status Bar -->
            <div class="flex justify-between items-center relative z-10 border-t border-slate-800/60 pt-4 bg-transparent">
              <div class="flex items-center gap-2">
                <button onclick="toggleMic(this)" class="p-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl transition-all shadow-md border border-slate-700/50" title="Mute Microphone">
                  <i data-lucide="mic" class="w-4 h-4" id="mic-icon"></i>
                </button>
                <button onclick="toggleVideo(this)" class="p-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl transition-all shadow-md border border-slate-700/50" title="Stop Video">
                  <i data-lucide="video" class="w-4 h-4" id="video-icon"></i>
                </button>
                <button onclick="toggleScreenShare(this)" class="p-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl transition-all shadow-md border border-slate-700/50" title="Share Screen">
                  <i data-lucide="monitor" class="w-4 h-4"></i>
                </button>
              </div>
              
              <!-- External Google Meet Quick Launch -->
              <a href="https://meet.google.com/new" target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg border border-emerald-500/30 transition-all flex items-center gap-2">
                <i data-lucide="external-link" class="w-4 h-4"></i> Launch on Google Meet
              </a>
            </div>
          </div>

          <!-- Digital Whiteboard & Lesson Notes -->
          <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-6">
            <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-4 flex items-center gap-2">
              <i data-lucide="file-text" class="w-4 h-4 text-primary"></i> Live Session Scratchpad
            </h3>
            <textarea id="scratchpad" rows="6" oninput="saveScratchpad()" placeholder="Type verses covered, specific spelling or pronunciation reminders, or homework assignments for this session..." class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-2xl text-xs font-medium text-primary focus:outline-none focus:bg-white focus:border-primary/30 transition-all resize-none"></textarea>
            <div class="flex justify-between items-center mt-3">
              <p class="text-[9px] text-primary/40 font-bold uppercase tracking-wider flex items-center gap-1">
                <i data-lucide="database" class="w-3.5 h-3.5"></i> Autocached locally in real-time
              </p>
              <button onclick="clearScratchpad()" class="text-[10px] font-extrabold uppercase text-rose-600 hover:text-rose-500 transition-colors">Clear Scratchpad</button>
            </div>
          </div>
        </div>

        <!-- Right Col: Timers & Real-Time Evaluation Checklist -->
        <div class="lg:col-span-4 space-y-6">
          
          <!-- Smart Stopwatch Timer -->
          <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-6 text-center">
            <h3 class="text-[10px] font-extrabold uppercase tracking-widest text-primary/40 mb-3">Live Class Duration</h3>
            <div class="text-4xl font-black font-mono text-primary my-4" id="timer-display">00:00:00</div>
            <div class="flex justify-center gap-3">
              <button onclick="startTimer()" id="btn-start" class="bg-primary hover:bg-opacity-95 text-white px-4 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-wider shadow-md transition-all flex items-center gap-1.5">
                <i data-lucide="play" class="w-3.5 h-3.5"></i> Start
              </button>
              <button onclick="pauseTimer()" id="btn-pause" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-wider transition-all flex items-center gap-1.5 hidden">
                <i data-lucide="pause" class="w-3.5 h-3.5"></i> Pause
              </button>
              <button onclick="resetTimer()" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-100 px-4 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-wider transition-all flex items-center gap-1.5">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset
              </button>
            </div>
          </div>

          <!-- Seeker Tajweed Evaluation Tracker -->
          <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-6">
            <h3 class="text-xs font-black uppercase tracking-wider text-primary/60 mb-5 flex items-center gap-2">
              <i data-lucide="award" class="w-4.5 h-4.5 text-primary"></i> Real-time Tajweed Evaluation
            </h3>
            
            <div class="space-y-5">
              <!-- Parameter 1: Makharij -->
              <div>
                <div class="flex justify-between items-center mb-1.5">
                  <span class="text-xs font-bold text-primary">Makharij (Pronunciation)</span>
                  <span class="text-[10px] font-mono font-bold text-primary/60" id="val-makharij">8/10</span>
                </div>
                <input type="range" min="1" max="10" value="8" oninput="updateEval('makharij', this.value)" class="w-full accent-primary h-1 bg-slate-100 rounded-lg appearance-none cursor-pointer">
              </div>

              <!-- Parameter 2: Sifaat -->
              <div>
                <div class="flex justify-between items-center mb-1.5">
                  <span class="text-xs font-bold text-primary">Sifaat (Characteristics)</span>
                  <span class="text-[10px] font-mono font-bold text-primary/60" id="val-sifaat">7/10</span>
                </div>
                <input type="range" min="1" max="10" value="7" oninput="updateEval('sifaat', this.value)" class="w-full accent-primary h-1 bg-slate-100 rounded-lg appearance-none cursor-pointer">
              </div>

              <!-- Parameter 3: Ghunnah -->
              <div>
                <div class="flex justify-between items-center mb-1.5">
                  <span class="text-xs font-bold text-primary">Ghunnah (Nasalization)</span>
                  <span class="text-[10px] font-mono font-bold text-primary/60" id="val-ghunnah">9/10</span>
                </div>
                <input type="range" min="1" max="10" value="9" oninput="updateEval('ghunnah', this.value)" class="w-full accent-primary h-1 bg-slate-100 rounded-lg appearance-none cursor-pointer">
              </div>

              <!-- Parameter 4: Madd -->
              <div>
                <div class="flex justify-between items-center mb-1.5">
                  <span class="text-xs font-bold text-primary">Madd (Prolongation)</span>
                  <span class="text-[10px] font-mono font-bold text-primary/60" id="val-madd">8/10</span>
                </div>
                <input type="range" min="1" max="10" value="8" oninput="updateEval('madd', this.value)" class="w-full accent-primary h-1 bg-slate-100 rounded-lg appearance-none cursor-pointer">
              </div>
            </div>

            <!-- Submit evaluation directly to homework page or remarks -->
            <button onclick="exportEvaluation()" class="w-full mt-6 bg-slate-50 hover:bg-slate-100 border border-primary/10 text-primary py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5">
              <i data-lucide="share-2" class="w-4.5 h-4.5 text-primary/60"></i> Export Scores to Remarks
            </button>
          </div>

          <!-- Class Roll Quick Link -->
          <div class="bg-slate-50 border border-primary/5 rounded-3xl p-5 text-center">
            <p class="text-[10px] font-bold text-primary/60 uppercase tracking-wider leading-relaxed">Ready to record today's final evaluation details?</p>
            <a href="today_attendance.php" class="inline-block mt-3 bg-primary hover:bg-opacity-95 text-white px-6 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-sm transition-all">Go to Roll Call Desk</a>
          </div>

        </div>

      </div>
    <?php else: ?>
      <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-12 text-center max-w-2xl mx-auto">
        <i data-lucide="monitor-play" class="w-12 h-12 text-primary/30 mx-auto mb-4 animate-bounce"></i>
        <p class="text-base font-black text-primary">Ready to begin class?</p>
        <p class="text-xs text-primary/60 mt-1 max-w-md mx-auto">Select an assigned seeker from the dropdown selector above to launch the live broadcast dashboard, virtual scratchpad, and tajweed evaluator.</p>
      </div>
    <?php endif; ?>

  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<script>
  // Stopwatch logic
  let timerInterval = null;
  let seconds = 0;

  function startTimer() {
    document.getElementById('btn-start').classList.add('hidden');
    document.getElementById('btn-pause').classList.remove('hidden');
    
    if (!timerInterval) {
      timerInterval = setInterval(() => {
        seconds++;
        let hrs = Math.floor(seconds / 3600);
        let mins = Math.floor((seconds % 3600) / 60);
        let secs = seconds % 60;
        
        let displayStr = 
          (hrs < 10 ? '0' + hrs : hrs) + ':' + 
          (mins < 10 ? '0' + mins : mins) + ':' + 
          (secs < 10 ? '0' + secs : secs);
          
        document.getElementById('timer-display').textContent = displayStr;
      }, 1000);
    }
  }

  function pauseTimer() {
    document.getElementById('btn-pause').classList.add('hidden');
    document.getElementById('btn-start').classList.remove('hidden');
    
    clearInterval(timerInterval);
    timerInterval = null;
  }

  function resetTimer() {
    pauseTimer();
    seconds = 0;
    document.getElementById('timer-display').textContent = '00:00:00';
  }

  // Scratchpad storage logic
  const studentId = "<?php echo htmlspecialchars($active_student_id); ?>";
  const scratchKey = 'scratch_' + studentId;

  document.addEventListener("DOMContentLoaded", () => {
    if (studentId) {
      const cached = localStorage.getItem(scratchKey);
      if (cached) {
        document.getElementById('scratchpad').value = cached;
      }
    }
  });

  function saveScratchpad() {
    const txt = document.getElementById('scratchpad').value;
    localStorage.setItem(scratchKey, txt);
  }

  function clearScratchpad() {
    if (confirm("Are you sure you want to clear the scratchpad? This cannot be undone.")) {
      document.getElementById('scratchpad').value = '';
      localStorage.removeItem(scratchKey);
    }
  }

  // Evaluation Checklist Updates
  function updateEval(field, val) {
    document.getElementById('val-' + field).textContent = val + '/10';
  }

  function exportEvaluation() {
    const mak = document.getElementById('val-makharij').textContent;
    const sif = document.getElementById('val-sifaat').textContent;
    const ghu = document.getElementById('val-ghunnah').textContent;
    const mad = document.getElementById('val-madd').textContent;
    
    const scoresStr = `[Tajweed Evaluation Scores: Makharij: ${mak}, Sifaat: ${sif}, Ghunnah: ${ghu}, Madd: ${mad}]\n`;
    const scratchpad = document.getElementById('scratchpad');
    scratchpad.value = scoresStr + scratchpad.value;
    saveScratchpad();
    alert("Tajweed scores successfully appended to the top of your Scratchpad notes!");
  }

  // Mic/Video Controls
  let micActive = true;
  function toggleMic(btn) {
    micActive = !micActive;
    const icon = document.getElementById('mic-icon');
    if (micActive) {
      btn.className = "p-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl transition-all shadow-md border border-slate-700/50";
      icon.setAttribute('data-lucide', 'mic');
    } else {
      btn.className = "p-3 bg-rose-600 hover:bg-rose-500 text-white rounded-2xl transition-all shadow-md border border-rose-500/50";
      icon.setAttribute('data-lucide', 'mic-off');
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
  }

  let videoActive = true;
  function toggleVideo(btn) {
    videoActive = !videoActive;
    const icon = document.getElementById('video-icon');
    if (videoActive) {
      btn.className = "p-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl transition-all shadow-md border border-slate-700/50";
      icon.setAttribute('data-lucide', 'video');
    } else {
      btn.className = "p-3 bg-rose-600 hover:bg-rose-500 text-white rounded-2xl transition-all shadow-md border border-rose-500/50";
      icon.setAttribute('data-lucide', 'video-off');
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
  }
</script>
