<?php
/**
 * Al Foz Islamic Institute - Student Document Repository
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
        
        <!-- Document Grid -->
        <div class="lg:col-span-2 bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
            <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-6 h-[1px] bg-primary text-white/30"></span> Artifact Registry
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach($student['documents'] as $doc): ?>
                <div class="p-6 rounded-3xl border border-primary/5 hover:border-primary/20 transition-all group bg-white shadow-sm flex flex-col justify-between h-48">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl <?php echo $doc['type'] === 'Image' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'; ?> flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="<?php echo $doc['type'] === 'Image' ? 'image' : 'file-text'; ?>" class="w-6 h-6"></i>
                        </div>
                        <div class="flex gap-2">
                            <button class="p-2 hover:bg-primary/5 rounded-lg text-primary/40 hover:text-primary transition-all"><i data-lucide="eye" class="w-4 h-4"></i></button>
                            <button class="p-2 hover:bg-primary/5 rounded-lg text-primary/40 hover:text-primary transition-all"><i data-lucide="download" class="w-4 h-4"></i></button>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-black text-primary truncate"><?php echo $doc['name']; ?></p>
                        <p class="text-[9px] font-bold text-primary/40 uppercase tracking-widest mt-1">Registry Entry: <?php echo date('d M, Y', strtotime($doc['date'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- Upload Placeholder Card -->
                <div class="p-6 rounded-3xl border-2 border-dashed border-primary/10 hover:border-primary/30 transition-all flex flex-col items-center justify-center gap-4 group cursor-pointer h-48">
                    <div class="w-12 h-12 rounded-full bg-primary/5 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <i data-lucide="plus" class="w-6 h-6"></i>
                    </div>
                    <p class="text-[10px] font-black text-primary/40 uppercase tracking-widest">Add Artifact</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Security & Quota -->
        <div class="space-y-8">
            <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white">
                <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Registry Security</h3>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black">End-to-End Encryption</p>
                            <p class="text-[8px] text-white/40 font-bold uppercase tracking-widest mt-0.5">Protocol: AES-256 Verified</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black">Private Node Storage</p>
                            <p class="text-[8px] text-white/40 font-bold uppercase tracking-widest mt-0.5">Registry Access Only</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Storage Quota</h3>
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-primary uppercase">Utilized</span>
                        <span class="text-[10px] font-black text-primary">12%</span>
                    </div>
                    <div class="w-full h-2 bg-primary/5 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: 12%"></div>
                    </div>
                </div>
                <p class="text-[9px] text-primary/40 font-bold uppercase tracking-widest text-center">Freeing Node Space: 44MB of 50MB</p>
            </div>
        </div>

    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
