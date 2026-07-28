<?php
/**
 * Al Foz Islamic Institute - Teacher Student Profile
 */
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/permissions.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/students_data.php';

require_role('Teacher');
$teacher_name = $_SESSION['name'];
$student_id = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';

$student = null;
$students = get_all_students();
foreach ($students as $s) {
    if (($s['id'] == $student_id || $s['student_id'] == $student_id) && $s['teacher_name'] == $teacher_name) {
        $student = $s;
        break;
    }
}
if (!$student) {
    header("Location: students.php");
    exit;
}
$initials = strtoupper(substr($student['name'], 0, 2));
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 mt-4">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Student Dossier</h1>
        <p class="text-xs text-primary/60 mt-1">Comprehensive view of seeker profile and history.</p>
      </div>
      <div>
        <a href="students.php" class="bg-white border border-primary/10 text-primary hover:bg-primary hover:text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all active:scale-95 inline-flex items-center gap-2">
          Back to Registry
        </a>
      </div>
    </div>

    <div class="bg-white rounded-3xl border border-primary/10 shadow-sm overflow-hidden mb-8">
        <div class="bg-primary p-8 text-center text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-24 h-24 bg-white text-primary rounded-3xl flex items-center justify-center font-black text-3xl shadow-lg mb-4">
                    <?php echo htmlspecialchars($initials); ?>
                </div>
                <h2 class="text-2xl font-black mb-1"><?php echo htmlspecialchars($student['name']); ?></h2>
                <div class="text-xs font-bold tracking-widest text-emerald-300 uppercase mb-3">ID: <?php echo htmlspecialchars($student['student_id'] ?? 'N/A'); ?></div>
                <div class="inline-flex gap-2 text-[10px] font-black uppercase tracking-widest">
                    <span class="bg-white/10 px-3 py-1 rounded-full border border-white/20"><?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?></span>
                    <span class="bg-emerald-500/20 text-emerald-100 px-3 py-1 rounded-full border border-emerald-400/30"><?php echo htmlspecialchars($student['status'] ?? 'Active'); ?></span>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Details Column 1 -->
            <div>
                <h3 class="text-[10px] font-black uppercase text-primary/40 mb-4 tracking-widest border-b border-primary/5 pb-2">Personal Information</h3>
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-primary/60 uppercase mb-1">Gender</span>
                        <span class="text-sm font-semibold text-primary"><?php echo htmlspecialchars($student['gender'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-primary/60 uppercase mb-1">Date of Birth</span>
                        <span class="text-sm font-semibold text-primary"><?php echo htmlspecialchars($student['dob'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-primary/60 uppercase mb-1">Country</span>
                        <span class="text-sm font-semibold text-primary"><?php echo htmlspecialchars($student['country'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-primary/60 uppercase mb-1">WhatsApp</span>
                        <span class="text-sm font-semibold text-primary"><?php echo htmlspecialchars($student['whatsapp'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Details Column 2 -->
            <div>
                <h3 class="text-[10px] font-black uppercase text-primary/40 mb-4 tracking-widest border-b border-primary/5 pb-2">Academic & Financial</h3>
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-primary/60 uppercase mb-1">Joining Date</span>
                        <span class="text-sm font-semibold text-primary"><?php echo htmlspecialchars($student['joining_date'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-primary/60 uppercase mb-1">Monthly Fee</span>
                        <span class="text-sm font-semibold text-primary"><?php echo htmlspecialchars(($student['monthly_fee'] ?? '0') . ' ' . ($student['currency'] ?? 'PKR')); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-primary/60 uppercase mb-1">Father / Guardian</span>
                        <span class="text-sm font-semibold text-primary"><?php echo htmlspecialchars($student['parent_info']['father_name'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Schedule View -->
        <div class="p-8 pt-0">
            <h3 class="text-[10px] font-black uppercase text-primary/40 mb-4 tracking-widest border-b border-primary/5 pb-2">Weekly Class Schedule</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                <?php 
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                foreach($days as $day): 
                    $lower_day = strtolower($day);
                    $is_enabled = isset($student[$lower_day . '_enabled']) && $student[$lower_day . '_enabled'];
                    $time = $student[$lower_day . '_time'] ?? '';
                    $dur = $student[$lower_day . '_duration'] ?? '30';
                ?>
                <div class="bg-primary/5 rounded-xl p-3 border border-primary/10 text-center flex flex-col items-center <?php echo $is_enabled ? '' : 'opacity-40'; ?>">
                    <span class="text-[10px] font-bold text-primary uppercase mb-1"><?php echo substr($day, 0, 3); ?></span>
                    <?php if ($is_enabled): ?>
                        <span class="text-xs font-bold text-primary"><?php echo htmlspecialchars($time); ?></span>
                        <span class="text-[10px] text-primary/60"><?php echo htmlspecialchars($dur); ?> Min</span>
                    <?php else: ?>
                        <span class="text-[10px] text-primary/60 italic mt-1">Off</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

  </div>
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
