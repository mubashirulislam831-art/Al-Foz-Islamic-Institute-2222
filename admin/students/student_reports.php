<?php
/**
 * Al Foz Islamic Institute - Student Report Center
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
    

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Report Templates -->
        <div class="space-y-6">
            <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3">
                <span class="w-6 h-[1px] bg-primary text-white/30"></span> Available Report Modules
            </h3>
            
            <!-- Module 1 -->
            <div class="bg-white p-6 rounded-[32px] border border-primary/10 shadow-sm flex items-center justify-between hover:border-primary transition-all cursor-pointer group">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all">
                        <i data-lucide="check-square" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-primary">Attendance Comprehensive</p>
                        <p class="text-[9px] font-bold text-primary/40 uppercase tracking-widest">Monthly Breakdown • Detailed Absences</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-primary/20"></i>
            </div>

            <!-- Module 2 -->
            <div class="bg-white p-6 rounded-[32px] border border-primary/10 shadow-sm flex items-center justify-between hover:border-primary transition-all cursor-pointer group">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-all">
                        <i data-lucide="pie-chart" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-primary">Academic Evaluation Summary</p>
                        <p class="text-[9px] font-bold text-primary/40 uppercase tracking-widest">Grade Trends • Teacher Observations</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-primary/20"></i>
            </div>

            <!-- Module 3 -->
            <div class="bg-white p-6 rounded-[32px] border border-primary/10 shadow-sm flex items-center justify-between hover:border-primary transition-all cursor-pointer group">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-all">
                        <i data-lucide="credit-card" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-primary">Financial Ledger Export</p>
                        <p class="text-[9px] font-bold text-primary/40 uppercase tracking-widest">Fee History • Pending Dues Node</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-primary/20"></i>
            </div>
        </div>

        <!-- Configuration & Settings -->
        <div class="space-y-8">
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Export Configuration</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[9px] font-black text-white/40 uppercase tracking-widest mb-3">Time Horizon</label>
                        <select class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-primary appearance-none">
                            <option>Current Month (June 2026)</option>
                            <option>Last 3 Months</option>
                            <option>Academic Year 2025-26</option>
                            <option>Lifetime (Registry Start)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-white/40 uppercase tracking-widest mb-3">Output Protocol</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="p-4 bg-white/10 rounded-2xl border border-primary text-white flex flex-col items-center gap-2">
                                <i data-lucide="file-type-2" class="w-6 h-6 text-primary"></i>
                                <span class="text-[9px] font-black uppercase">PDF Format</span>
                            </button>
                            <button class="p-4 bg-white/5 rounded-2xl border border-white/10 text-white/40 flex flex-col items-center gap-2">
                                <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
                                <span class="text-[9px] font-black uppercase">Excel CSV</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Recent Reports Generated</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-primary/5 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <i data-lucide="file-check" class="w-4 h-4 text-primary"></i>
                            <span class="text-[11px] font-bold text-primary">Annual_Progress_2025.pdf</span>
                        </div>
                        <span class="text-[8px] font-black text-primary/30 uppercase tracking-widest">2 DAYS AGO</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
