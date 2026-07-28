<?php
/**
 * Al Foz Islamic Institute - Student Faculty Assignment
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';
require_once __DIR__ . '/../../includes/teachers_data.php';

require_role(['Admin', 'Super Admin']);

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$student = get_student_by_id($student_id);



$teacher = null;
foreach(get_all_teachers() as $t) {
    if ($t['name'] === $student['teacher_name']) {
        $teacher = $t;
        break;
    }
}

$initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $student['name'])));
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Header and Navigation -->
    <?php require_once __DIR__ . '/_student_header.php'; ?>

    <!-- Student Dossier Portals Box (Structured Vertical-to-Grid Portals) -->
    

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Assigned Faculty Dossier -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 p-8 opacity-[0.02]">
                    <i data-lucide="graduation-cap" class="w-48 h-48"></i>
                </div>
                
                <div class="flex items-center gap-8 mb-12">
                    <div class="w-24 h-24 rounded-3xl bg-primary text-white flex items-center justify-center text-2xl font-black shadow-xl shadow-primary/20">
                        <?php echo $teacher ? substr($teacher['name'], 0, 2) : 'BS'; ?>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-primary"><?php echo htmlspecialchars($student['teacher_name']); ?></h1>
                        <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-2">Senior Faculty Member • <?php echo $teacher['subject'] ?? 'Islamic Studies'; ?></p>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase">Active Duty</span>
                            <span class="px-3 py-1 bg-primary/5 text-primary rounded-lg text-[9px] font-black uppercase">Verified Faculty</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-primary/30 uppercase tracking-[0.2em]">Contact Node</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary/40 uppercase">Email Protocol</span>
                                <span class="font-black text-primary"><?php echo $teacher['email'] ?? 'faculty@alfoz.edu'; ?></span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary/40 uppercase">WhatsApp Node</span>
                                <span class="font-black text-emerald-600"><?php echo $teacher['phone'] ?? '+92 300 0000000'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-primary/30 uppercase tracking-[0.2em]">Faculty Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary/40 uppercase">Students Under Command</span>
                                <span class="font-black text-primary">24 Active Nodes</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary/40 uppercase">Faculty Rating</span>
                                <div class="flex items-center gap-1">
                                    <i data-lucide="star" class="w-3 h-3 text-primary fill-primary"></i>
                                    <span class="font-black text-primary">4.9 / 5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teaching Observations -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Faculty Observations & Feedback</h3>
                    <button class="bg-primary/5 text-primary hover:bg-primary hover:text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                        Request Peer Review
                    </button>
                </div>
                <div class="space-y-6">
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-xs font-bold text-primary/60 italic leading-relaxed mb-4">
                            "Student shows excellent focus during tajweed drills. Recommend proceeding to advanced makharij modules next month if performance trend sustains."
                        </p>
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-black text-primary/30 uppercase">Logged June 12, 2026</p>
                            <span class="text-[9px] font-black text-emerald-600 uppercase">Status: Actionable</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Re-assignment -->
        <div class="space-y-8">
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Re-assignment Protocol</h3>
                <p class="text-[11px] leading-relaxed text-white/60 mb-8">
                    Strict node management. Re-assigning faculty requires vetting of academic schedules and course compatibility.
                </p>
                <form action="edit_student.php?id=<?php echo $student['id']; ?>" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-[9px] font-black text-white/40 uppercase tracking-widest mb-3">Target Faculty Node</label>
                        <select name="teacher_name" class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-primary appearance-none">
                            <?php foreach(get_all_teachers() as $t): ?>
                            <option value="<?php echo htmlspecialchars($t['name']); ?>" <?php echo $student['teacher_name'] === $t['name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($t['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-primary text-white text-primary rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                        Commit Re-assignment
                    </button>
                </form>
            </div>
            
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm text-center">
                <i data-lucide="shield-check" class="w-12 h-12 text-emerald-500 mx-auto mb-4"></i>
                <p class="text-xs font-black text-primary">Vetted Assignment</p>
                <p class="text-[10px] text-primary/40 font-bold uppercase tracking-widest mt-1">Verified by Registry</p>
            </div>
        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
