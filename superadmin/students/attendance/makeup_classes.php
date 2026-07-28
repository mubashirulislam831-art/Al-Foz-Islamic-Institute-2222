<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Makeup Class Setup
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

$id = isset($_GET['id']) ? intval($_GET['id']) : 101;
$student = get_student_by_id($id);

if (!$student) {
    header("Location: ../students.php");
    exit;
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $makeup_date = sanitize_input($_POST['makeup_date']);
    $makeup_time = sanitize_input($_POST['makeup_time']);
    $instructor = sanitize_input($_POST['instructor']);
    $topic = sanitize_input($_POST['topic']);
    
    // Add makeup class credit
    $_SESSION['students'][$id]['attendance']['makeup_classes'] += 1;
    
    // Log to timeline
    $_SESSION['students'][$id]['timeline'][] = [
        'date' => date('Y-m-d'),
        'type' => 'Attendance History',
        'title' => 'Makeup Class Scheduled',
        'desc' => "Makeup lesson scheduled on $makeup_date at $makeup_time with $instructor. Subject: $topic"
    ];
    
    $success = true;
    $student = get_student_by_id($id); // refresh data
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <div class="mb-8 mt-4">
      <a href="../student_profile.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Profile
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Makeup Classes Setup</h1>
      <p class="text-xs text-primary/60 mt-0.5">Allocate and schedule makeup lesson credits to resolve absences and maintain academic trajectories.</p>
    </div>

    <?php if ($success): ?>
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-4 rounded-xl mb-6 max-w-xl">
        ✓ Makeup class scheduled successfully! Student's completed makeup count incremented.
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Scheduling Form -->
      <div class="lg:col-span-6 bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-6 pb-2 border-b border-slate-50">Schedule Makeup Class</h3>
        
        <form action="makeup_classes.php?id=<?php echo $id; ?>" method="POST" class="space-y-4">
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">Makeup Date *</label>
            <input type="date" name="makeup_date" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required>
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">Makeup Time *</label>
            <input type="text" name="makeup_time" placeholder="14:00 UTC" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required>
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">Instructor Assigned *</label>
            <select name="instructor" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none bg-white" required>
              <?php if (!empty($student['teacher_name'])): ?>
                <option value="<?php echo htmlspecialchars($student['teacher_name']); ?>"><?php echo htmlspecialchars($student['teacher_name']); ?> (Primary)</option>
              <?php endif; ?>
              <?php 
              require_once __DIR__ . '/../../../includes/teachers_data.php';
              $all_teachers = get_all_teachers();
              foreach ($all_teachers as $t):
                if ($t['name'] !== ($student['teacher_name'] ?? '')):
              ?>
                <option value="<?php echo htmlspecialchars($t['name']); ?>"><?php echo htmlspecialchars($t['name']); ?></option>
              <?php 
                endif;
              endforeach; 
              ?>
            </select>
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">Class Topic / Notes *</label>
            <textarea name="topic" placeholder="e.g., Revision of Tajweed rules on Qalqalah and Sukoon letters..." rows="3" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required></textarea>
          </div>
          
          <button type="submit" class="w-full bg-primary text-white text-xs font-bold py-2.5 rounded-xl uppercase tracking-wider shadow-md hover:bg-opacity-95 transition-all">Authorize Makeup Slot</button>
        </form>
      </div>

      <!-- Stats Summary -->
      <div class="lg:col-span-6 bg-white rounded-2xl border border-primary/10 shadow-sm p-6 flex flex-col justify-between">
        <div>
          <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-5 pb-2 border-b border-slate-50">Makeup Log Stats</h3>
          <div class="space-y-4 text-xs">
            <div class="flex justify-between items-center bg-primary/5 p-4 rounded-xl">
              <span class="font-bold text-slate-600">Total Makeup Sessions Attended:</span>
              <span class="text-lg font-black text-primary"><?php echo $student['attendance']['makeup_classes']; ?> Slots</span>
            </div>
          </div>
        </div>
        <p class="text-[11px] text-slate-400 italic leading-relaxed bg-slate-50 p-4 rounded-xl mt-6 border border-slate-100">"Al Foz Student Policy encourages scheduling makeup slots within 14 days of the logged absence. Student timelines register makeup histories automatically."</p>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>
