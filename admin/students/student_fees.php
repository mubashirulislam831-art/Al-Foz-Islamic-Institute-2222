<?php
/**
 * Al Foz Islamic Institute - Student Fee Dossier
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
        
        <!-- Transaction Ledger -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Financial Ledger History</h3>
                    <button class="bg-primary text-white px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 flex items-center gap-2">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Register Payment
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[9px] font-black text-primary/30 uppercase tracking-[0.2em] border-b border-primary/5">
                                <th class="pb-4">Transaction ID</th>
                                <th class="pb-4">Cycle / Month</th>
                                <th class="pb-4">Amount</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-right">Method</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/5">
                            <tr class="text-xs">
                                <td class="py-5 font-bold text-primary/40">TXN-9921</td>
                                <td class="py-5 font-black text-primary">June 2026</td>
                                <td class="py-5 font-black text-primary"><?php echo $student['monthly_fee'] . ' ' . $student['currency']; ?></td>
                                <td class="py-5">
                                    <span class="px-3 py-1 rounded-full <?php echo strtolower($student['fee_status']) === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'; ?> text-[9px] font-black uppercase">
                                        <?php echo $student['fee_status']; ?>
                                    </span>
                                </td>
                                <td class="py-5 text-right font-bold text-primary/60">Bank Transfer</td>
                            </tr>
                            <tr class="text-xs">
                                <td class="py-5 font-bold text-primary/40">TXN-8812</td>
                                <td class="py-5 font-black text-primary">May 2026</td>
                                <td class="py-5 font-black text-primary"><?php echo $student['monthly_fee'] . ' ' . $student['currency']; ?></td>
                                <td class="py-5">
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[9px] font-black uppercase">Paid</span>
                                </td>
                                <td class="py-5 text-right font-bold text-primary/60">WhatsApp Pay</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoices Container -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                    <span class="w-6 h-[1px] bg-primary text-white/30"></span> Document Node (Invoices)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl border border-primary/10 hover:border-primary/30 transition-all group flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-primary">Invoice_JUN26.pdf</p>
                                <p class="text-[8px] font-bold text-primary/30 uppercase">System Generated • 1.2MB</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-primary/5 rounded-lg text-primary/40 hover:text-primary transition-all">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Billing Tools -->
        <div class="space-y-8">
            
            <!-- Payment Configuration -->
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Node Financial Settings</h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest mb-3">Currency Engine</p>
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/10 flex items-center justify-between">
                            <span class="text-xs font-black"><?php echo $student['currency']; ?> Protocol</span>
                            <span class="text-[10px] px-2 py-0.5 bg-primary text-white text-primary rounded font-black">Active</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest mb-3">Auto-Billing</p>
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/10 flex items-center justify-between">
                            <span class="text-xs font-black">Monthly Generation</span>
                            <div class="w-10 h-5 bg-emerald-500 rounded-full relative">
                                <div class="absolute right-1 top-1 w-3 h-3 bg-white rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discount / Scholarship -->
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-[0.03]">
                    <i data-lucide="award" class="w-32 h-32 text-primary"></i>
                </div>
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Financial Concessions</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-bold text-primary/40 uppercase">Discount Rate</span>
                        <span class="font-black text-primary">0% / Standard</span>
                    </div>
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-bold text-primary/40 uppercase">Scholarship</span>
                        <span class="font-black text-rose-500 uppercase">None Detected</span>
                    </div>
                    <button class="w-full mt-4 flex items-center justify-center gap-2 py-3 border border-primary/10 hover:border-primary hover:bg-primary hover:text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Apply Concession
                    </button>
                </div>
            </div>

        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
