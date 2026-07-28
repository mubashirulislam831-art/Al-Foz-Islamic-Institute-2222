<?php
/**
 * Al Foz Islamic Institute - Student Notification Hub
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
      <span class="text-primary">Communication Hub</span>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm mb-8 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-3xl bg-primary text-white flex items-center justify-center text-3xl font-black shadow-xl shadow-primary/20">
                <i data-lucide="bell-ring" class="w-10 h-10"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-primary">Notification Broadcast Node</h1>
                <p class="text-[10px] font-bold text-primary/40 uppercase tracking-widest">Multi-Channel Communication Synthesis • <?php echo htmlspecialchars($student['name']); ?></p>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="px-6 py-3 border border-primary/10 hover:border-primary text-primary rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                <i data-lucide="message-square" class="w-4 h-4"></i> WhatsApp Broadcast
            </button>
            <button class="px-6 py-3 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 flex items-center gap-2 hover:bg-opacity-95 transition-all">
                <i data-lucide="send" class="w-4 h-4"></i> Deploy Notification
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Transmission Logs -->
        <div class="lg:col-span-2 bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
            <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-6 h-[1px] bg-primary text-white/30"></span> Transmission Ledger
            </h3>
            
            <div class="space-y-6">
                <!-- Log Item 1 -->
                <div class="p-6 rounded-[28px] border border-primary/5 bg-primary/5 hover:border-primary/20 transition-all flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-primary shadow-sm">
                            <i data-lucide="mail-check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-primary">Monthly Fee Invoice - June</p>
                            <p class="text-[9px] font-bold text-primary/40 uppercase tracking-widest">Channel: Email • Sent June 01, 2026</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[8px] font-black uppercase tracking-widest">Delivered</span>
                        <button class="p-2 text-primary/20 hover:text-primary transition-all"><i data-lucide="more-vertical" class="w-4 h-4"></i></button>
                    </div>
                </div>

                <!-- Log Item 2 -->
                <div class="p-6 rounded-[28px] border border-primary/5 bg-primary/5 hover:border-primary/20 transition-all flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                            <i data-lucide="message-circle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-primary">Class Cancellation Alert (Maintenance)</p>
                            <p class="text-[9px] font-bold text-primary/40 uppercase tracking-widest">Channel: WhatsApp • Sent June 10, 2026</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[8px] font-black uppercase tracking-widest">Read By Recipient</span>
                        <button class="p-2 text-primary/20 hover:text-primary transition-all"><i data-lucide="more-vertical" class="w-4 h-4"></i></button>
                    </div>
                </div>

                <!-- Log Item 3 -->
                <div class="p-6 rounded-[28px] border border-primary/5 bg-primary/5 hover:border-primary/20 transition-all flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-amber-600 shadow-sm">
                            <i data-lucide="alert-circle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-primary">Unusual Absence Trigger</p>
                            <p class="text-[9px] font-bold text-primary/40 uppercase tracking-widest">Channel: System Alert • June 12, 2026</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[8px] font-black uppercase tracking-widest">Pending Node</span>
                        <button class="p-2 text-primary/20 hover:text-primary transition-all"><i data-lucide="more-vertical" class="w-4 h-4"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Channels & Meta -->
        <div class="space-y-8">
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Connectivity Protocol</h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-primary"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Email Node</span>
                        </div>
                        <span class="text-[9px] font-black text-emerald-400 uppercase">Operational</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="message-circle" class="w-4 h-4 text-primary"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">WhatsApp Node</span>
                        </div>
                        <span class="text-[9px] font-black text-emerald-400 uppercase">Operational</span>
                    </div>
                    <div class="flex items-center justify-between opacity-40">
                        <div class="flex items-center gap-3">
                            <i data-lucide="smartphone" class="w-4 h-4 text-white"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">SMS Gateway</span>
                            <span class="text-[8px] px-2 py-0.5 bg-white/10 rounded font-black">Inactive</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Engagement Synthesis</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-primary/40 uppercase">Open Rate</span>
                        <span class="text-sm font-black text-primary">94.2%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-primary/40 uppercase">Response Node</span>
                        <span class="text-sm font-black text-emerald-600">Active</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
