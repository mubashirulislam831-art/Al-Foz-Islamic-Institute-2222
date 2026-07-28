<?php
/**
 * Al Foz Islamic Institute - Student Evaluation Dossier
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

require_role(['Admin', 'Super Admin']);

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$student = get_student_by_id($student_id);



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
        
        <!-- Exam History -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Academic Evaluation History</h3>
                    <button class="bg-primary text-white px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 flex items-center gap-2">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Result
                    </button>
                </div>
                
                <div class="space-y-4">
                    <?php foreach($student['exams'] as $exam): ?>
                    <div class="p-6 rounded-2xl border border-primary/5 hover:border-primary/20 transition-all group flex items-center justify-between bg-white shadow-sm">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center font-black text-primary group-hover:bg-primary group-hover:text-white transition-all text-sm">
                                <?php echo $exam['grade']; ?>
                            </div>
                            <div>
                                <p class="text-sm font-black text-primary"><?php echo $exam['name']; ?></p>
                                <p class="text-[9px] font-bold text-primary/40 uppercase tracking-widest">Evaluation Node • <?php echo $exam['marks']; ?>/100 Marks</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="text-center">
                                <p class="text-[8px] font-black text-primary/30 uppercase mb-1">Percentage</p>
                                <p class="text-sm font-black text-primary"><?php echo $exam['percentage']; ?>%</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[8px] font-black text-primary/30 uppercase mb-1">Status</p>
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase <?php echo $exam['result'] === 'Pass' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'; ?>">
                                    <?php echo $exam['result']; ?>
                                </span>
                            </div>
                            <button class="p-2 hover:bg-primary/5 rounded-lg text-primary/20 hover:text-primary transition-all">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Detailed Remarks -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                    <span class="w-6 h-[1px] bg-primary text-white/30"></span> Faculty Observations
                </h3>
                <div class="p-6 rounded-2xl bg-primary/5 border border-primary/5 italic text-sm text-primary/60 leading-relaxed">
                    "<?php echo $student['performance']['teacher_feedback']; ?>"
                    <div class="mt-4 flex items-center gap-3 not-italic">
                        <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center text-[10px] font-black">BS</div>
                        <div>
                            <p class="text-[10px] font-black text-primary"><?php echo $student['teacher_name']; ?></p>
                            <p class="text-[8px] font-bold text-primary/40 uppercase">Assigned Faculty</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Trends -->
        <div class="space-y-8">
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Performance Radar</h3>
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Tajweed Level</span>
                            <span class="text-xs font-black">92%</span>
                        </div>
                        <div class="w-full h-1 bg-white/10 rounded-full">
                            <div class="h-full bg-primary text-white rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Memorization</span>
                            <span class="text-xs font-black">78%</span>
                        </div>
                        <div class="w-full h-1 bg-white/10 rounded-full">
                            <div class="h-full bg-primary text-white rounded-full" style="width: 78%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Daily Progress</span>
                            <span class="text-xs font-black">85%</span>
                        </div>
                        <div class="w-full h-1 bg-white/10 rounded-full">
                            <div class="h-full bg-primary text-white rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Assessment Calendar -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Upcoming Evaluations</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex flex-col items-center justify-center">
                            <span class="text-[8px] font-black uppercase">JUN</span>
                            <span class="text-xs font-black">25</span>
                        </div>
                        <div>
                            <p class="text-xs font-black text-primary">Monthly Quiz</p>
                            <p class="text-[9px] text-primary/40 font-bold uppercase">Topic: Makharij</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 opacity-40">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex flex-col items-center justify-center">
                            <span class="text-[8px] font-black uppercase">JUL</span>
                            <span class="text-xs font-black">05</span>
                        </div>
                        <div>
                            <p class="text-xs font-black text-primary">Tajweed Oral</p>
                            <p class="text-[9px] text-primary/40 font-bold uppercase">Quarterly Evaluation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
