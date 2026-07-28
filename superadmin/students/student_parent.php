<?php
/**
 * Al Foz Islamic Institute - Student Parent/Guardian Dossier
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
      <span class="text-primary">Guardian Dossier</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Guardian Detailed Dossier -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3">
                        <span class="w-6 h-[1px] bg-primary text-white/30"></span> Primary Guardian Profile
                    </h3>
                    <span class="px-4 py-1.5 rounded-full bg-primary text-white text-[10px] font-black uppercase tracking-widest">Verified Contact</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Father Info -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-xs font-black text-primary uppercase">Father Information</h4>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary/40 uppercase">Full Name</span>
                                <span class="font-black text-primary"><?php echo htmlspecialchars($student['parent_info']['father_name']); ?></span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary/40 uppercase">WhatsApp Node</span>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $student['parent_info']['father_whatsapp']); ?>" target="_blank" class="font-black text-emerald-600 hover:underline"><?php echo $student['parent_info']['father_whatsapp']; ?></a>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary/40 uppercase">Email Protocol</span>
                                <span class="font-black text-primary"><?php echo $student['parent_info']['father_email']; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Mother Info -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-xs font-black text-primary uppercase">Mother Information</h4>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary/40 uppercase">Full Name</span>
                                <span class="font-black text-primary"><?php echo htmlspecialchars($student['parent_info']['mother_name'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 pt-10 border-t border-primary/5">
                    <h4 class="text-[10px] font-black text-primary/30 uppercase tracking-[0.2em] mb-6">Emergency Contact Protocol</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[8px] font-black text-primary/30 uppercase mb-1">Primary Guardian</p>
                            <p class="text-xs font-black text-primary"><?php echo $student['parent_info']['guardian']; ?></p>
                        </div>
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-100">
                            <p class="text-[8px] font-black text-rose-600/60 uppercase mb-1">Emergency Name</p>
                            <p class="text-xs font-black text-rose-700"><?php echo htmlspecialchars($student['emergency_contact']['name'] ?? 'Father'); ?></p>
                        </div>
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-100">
                            <p class="text-[8px] font-black text-rose-600/60 uppercase mb-1">Emergency Number</p>
                            <p class="text-xs font-black text-rose-700"><?php echo htmlspecialchars($student['emergency_contact']['number'] ?? $student['parent_info']['father_whatsapp']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent-Teacher Communication Node -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Communication Ledger</h3>
                    <button class="bg-primary/5 text-primary hover:bg-primary hover:text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                        Initiate Broadcast
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="p-5 rounded-2xl border border-primary/5 bg-primary/5 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <i data-lucide="mail" class="w-4 h-4 text-primary"></i>
                            <div>
                                <p class="text-[11px] font-black text-primary">Monthly Performance Broadcast</p>
                                <p class="text-[8px] font-bold text-primary/40 uppercase">Automated System Node • June 01, 2026</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-black text-emerald-600 uppercase">Delivered</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Verification & Actions -->
        <div class="space-y-8">
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Verification Status</h3>
                <div class="text-center py-6 border-b border-white/10">
                    <div class="w-20 h-20 rounded-full bg-emerald-500/20 border-4 border-emerald-500 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="check" class="w-10 h-10 text-emerald-500"></i>
                    </div>
                    <p class="text-sm font-black uppercase">Verified Guardian</p>
                    <p class="text-[10px] text-white/40 font-bold uppercase mt-1">Registry Authenticated</p>
                </div>
                <div class="pt-8 space-y-4">
                    <button class="w-full py-4 bg-white/10 hover:bg-white text-white hover:text-primary rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                        View KYC Documents
                    </button>
                    <button class="w-full py-4 border border-white/20 hover:bg-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Update Contact Profile
                    </button>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-4">Registry Notes</h3>
                <p class="text-xs text-primary/60 italic leading-relaxed">
                    "Parents requested focus on Tajweed and pronunciation. Responsive communication via WhatsApp preferred."
                </p>
            </div>
        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
