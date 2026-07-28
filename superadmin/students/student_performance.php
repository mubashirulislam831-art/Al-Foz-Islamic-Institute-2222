<?php
/**
 * Al Foz Islamic Institute - Student Performance Dossier
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

require_role('Super Admin');

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

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-primary/40 mb-6 mt-4">
      <a href="students.php" class="hover:text-primary transition-colors">Registry</a>
      <i data-lucide="chevron-right" class="w-3 h-3"></i>
      <a href="student_profile.php?id=<?php echo $student['id']; ?>" class="hover:text-primary transition-colors">Student Profile</a>
      <i data-lucide="chevron-right" class="w-3 h-3"></i>
      <span class="text-primary">Performance Dossier</span>
    </div>

    <!-- Performance Header -->
    <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm mb-8 overflow-hidden relative">
        <div class="absolute right-0 top-0 w-64 h-full bg-emerald-500/5 -mr-20 skew-x-12"></div>
        <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-3xl bg-emerald-500 text-white flex items-center justify-center text-3xl font-black shadow-xl shadow-emerald-500/20">
                    <i data-lucide="zap" class="w-10 h-10"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-primary">Analytical Performance Hub</h1>
                    <p class="text-[10px] font-bold text-primary/40 uppercase tracking-widest">Holistic Academic Health Node • <?php echo htmlspecialchars($student['name']); ?></p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-center">
                    <p class="text-[9px] font-black text-primary/30 uppercase tracking-widest mb-1">Overall Node Index</p>
                    <p class="text-3xl font-black text-emerald-600"><?php echo $student['performance']['overall_rating']; ?><span class="text-sm opacity-40">/5.0</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Primary Performance Radar -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                    <span class="w-6 h-[1px] bg-primary text-white/30"></span> KPI Distribution Radar
                </h3>
                
                <div class="space-y-10">
                    <!-- Attendance -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <i data-lucide="calendar-check" class="w-4 h-4 text-primary"></i>
                                <span class="text-xs font-black text-primary uppercase">Attendance Integrity</span>
                            </div>
                            <span class="text-sm font-black text-primary"><?php echo $student['performance']['attendance_score']; ?>%</span>
                        </div>
                        <div class="w-full h-2.5 bg-primary/5 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full shadow-lg shadow-primary/20" style="width: <?php echo $student['performance']['attendance_score']; ?>%"></div>
                        </div>
                        <p class="text-[9px] text-primary/40 font-bold mt-2 uppercase tracking-widest">Consistency Node: Optimal</p>
                    </div>

                    <!-- Homework -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <i data-lucide="book-open" class="w-4 h-4 text-emerald-600"></i>
                                <span class="text-xs font-black text-primary uppercase">Homework Completion</span>
                            </div>
                            <span class="text-sm font-black text-emerald-600"><?php echo $student['performance']['homework_score']; ?>%</span>
                        </div>
                        <div class="w-full h-2.5 bg-emerald-500/5 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/20" style="width: <?php echo $student['performance']['homework_score']; ?>%"></div>
                        </div>
                        <p class="text-[9px] text-emerald-600/60 font-bold mt-2 uppercase tracking-widest">Self-Study Efficiency: High</p>
                    </div>

                    <!-- Evaluation -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <i data-lucide="award" class="w-4 h-4 text-amber-500"></i>
                                <span class="text-xs font-black text-primary uppercase">Evaluation Average</span>
                            </div>
                            <span class="text-sm font-black text-amber-600"><?php echo $student['performance']['exam_score']; ?>%</span>
                        </div>
                        <div class="w-full h-2.5 bg-amber-500/5 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full shadow-lg shadow-amber-500/20" style="width: <?php echo $student['performance']['exam_score']; ?>%"></div>
                        </div>
                        <p class="text-[9px] text-amber-600/60 font-bold mt-2 uppercase tracking-widest">Academic Vetting: Stable</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Performance Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Strengths Node</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                            <span class="text-[11px] font-bold text-emerald-700">Exceptional Makharij Pronunciation</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                            <span class="text-[11px] font-bold text-emerald-700">Highly Disciplined Punctuality</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Growth Vector</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-xl">
                            <i data-lucide="info" class="w-4 h-4 text-amber-600"></i>
                            <span class="text-[11px] font-bold text-amber-700">Tajweed Rule Memorization (Advanced)</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-xl">
                            <i data-lucide="info" class="w-4 h-4 text-amber-600"></i>
                            <span class="text-[11px] font-bold text-amber-700">Long Surah Retention</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Meta -->
        <div class="space-y-8">
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Faculty Assessment</h3>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center font-black text-xs border border-white/20">BS</div>
                    <div>
                        <p class="text-sm font-black"><?php echo $student['teacher_name']; ?></p>
                        <p class="text-[8px] text-white/40 font-bold uppercase tracking-widest">Primary Evaluator</p>
                    </div>
                </div>
                <p class="text-[11px] leading-relaxed text-white/60 italic">
                    "<?php echo $student['performance']['teacher_feedback']; ?>"
                </p>
                <div class="mt-8 pt-8 border-t border-white/10">
                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-4">Vetting Verification</p>
                    <div class="flex items-center gap-3">
                        <i data-lucide="shield-check" class="w-5 h-5 text-emerald-400"></i>
                        <span class="text-[10px] font-black uppercase">Analytics Authenticated</span>
                    </div>
                </div>
            </div>

            <!-- Performance Trend Chart Placeholder -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm h-64 flex flex-col items-center justify-center text-center">
                <i data-lucide="trending-up" class="w-12 h-12 text-primary/10 mb-4"></i>
                <p class="text-[10px] font-black text-primary/30 uppercase tracking-widest">Progressive Trend Visualization Node</p>
                <p class="text-[8px] font-bold text-primary/20 uppercase mt-1">Data Synthesis in Progress</p>
            </div>
        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
