<?php
/**
 * Al Foz Islamic Institute - Student Internal Notes
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

    <!-- Notes Ledger -->
        <div class="space-y-6">
            <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3">
                <span class="w-6 h-[1px] bg-primary text-white/30"></span> Faculty Observations
            </h3>
            
            <!-- Faculty Note -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm relative group overflow-hidden">
                <div class="absolute right-0 top-0 p-6 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="p-2 hover:bg-primary/5 rounded-lg text-primary/40 hover:text-primary transition-all"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                </div>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center font-black text-xs">BS</div>
                    <div>
                        <p class="text-xs font-black text-primary"><?php echo $student['teacher_name']; ?></p>
                        <p class="text-[9px] font-bold text-primary/30 uppercase tracking-widest">Faculty Lead • June 15, 2026</p>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 italic text-sm text-primary/60 leading-relaxed">
                    "<?php echo $student['notes']['teacher']; ?>"
                </div>
                <div class="mt-6 flex gap-2">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[8px] font-black uppercase tracking-widest">Positive Observation</span>
                    <span class="px-3 py-1 bg-primary/5 text-primary rounded-lg text-[8px] font-black uppercase tracking-widest">Academic Progress</span>
                </div>
            </div>

            <!-- Admin Note -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm relative group overflow-hidden">
                <div class="absolute right-0 top-0 p-6 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="p-2 hover:bg-primary/5 rounded-lg text-primary/40 hover:text-primary transition-all"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                </div>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center font-black text-xs">AD</div>
                    <div>
                        <p class="text-xs font-black text-primary">Admin Node</p>
                        <p class="text-[9px] font-bold text-primary/30 uppercase tracking-widest">Registry Ops • June 01, 2026</p>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 italic text-sm text-primary/60 leading-relaxed">
                    "<?php echo $student['notes']['admin']; ?>"
                </div>
            </div>
        </div>

        <!-- Right Column: Parent Feedback & Meta -->
        <div class="space-y-8">
            <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3">
                <span class="w-6 h-[1px] bg-primary text-white/30"></span> Guardian Communication
            </h3>
            
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-black text-xs">GR</div>
                    <div>
                        <p class="text-xs font-black text-primary">Guardian (Father)</p>
                        <p class="text-[9px] font-bold text-primary/30 uppercase tracking-widest">Direct Sync • June 10, 2026</p>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-amber-50/30 border border-amber-100/50 italic text-sm text-primary/60 leading-relaxed">
                    "<?php echo $student['notes']['parent']; ?>"
                </div>
            </div>

            <!-- Registry Rules -->
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-6">Confidentiality Protocol</h3>
                <p class="text-[11px] leading-relaxed text-white/60">
                    Registry notes are for internal operational use only. Sharing node observations outside the Al Foz network is strictly prohibited under the Faculty Security Framework.
                </p>
                <div class="mt-8 p-4 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                    <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Privacy Level</span>
                    <span class="px-3 py-1 bg-primary text-white text-primary rounded-lg text-[8px] font-black uppercase">Level 4 Verified</span>
                </div>
            </div>
        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
