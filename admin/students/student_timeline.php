<?php
/**
 * Al Foz Islamic Institute - Student Activity Timeline
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

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-primary/40 mb-6 mt-4">
      <a href="students.php" class="hover:text-primary transition-colors">Registry</a>
      <i data-lucide="chevron-right" class="w-3 h-3"></i>
      <a href="student_profile.php?id=<?php echo $student['id']; ?>" class="hover:text-primary transition-colors">Student Profile</a>
      <i data-lucide="chevron-right" class="w-3 h-3"></i>
      <span class="text-primary">Activity Timeline</span>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm mb-8 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-3xl bg-primary text-white flex items-center justify-center text-3xl font-black shadow-xl shadow-primary/20">
                <i data-lucide="history" class="w-10 h-10"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-primary">Student Node History</h1>
                <p class="text-[10px] font-bold text-primary/40 uppercase tracking-widest">Complete Lifecycle Timeline • <?php echo htmlspecialchars($student['name']); ?></p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="relative space-y-12 before:absolute before:left-[19px] before:top-4 before:bottom-4 before:w-[2px] before:bg-primary/10">
            <?php foreach($student['timeline'] as $event): ?>
            <div class="relative pl-12 group">
                <div class="absolute left-0 top-1 w-10 h-10 rounded-2xl bg-white border-2 border-primary/20 flex items-center justify-center group-hover:bg-primary group-hover:text-white group-hover:scale-110 transition-all z-10 shadow-sm">
                    <i data-lucide="<?php 
                        switch($event['type']) {
                            case 'Admission': echo 'user-plus'; break;
                            case 'Payment': echo 'credit-card'; break;
                            case 'Evaluation': echo 'award'; break;
                            case 'Attendance': echo 'check-square'; break;
                            default: echo 'circle';
                        }
                    ?>" class="w-5 h-5"></i>
                </div>
                
                <div class="bg-white p-8 rounded-[32px] border border-primary/5 shadow-sm group-hover:border-primary/20 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-primary/5 text-primary rounded-lg text-[9px] font-black uppercase tracking-widest"><?php echo $event['type']; ?></span>
                        <span class="text-[10px] font-bold text-primary/30 uppercase tracking-widest"><?php echo date('d M, Y', strtotime($event['date'])); ?></span>
                    </div>
                    <h3 class="text-lg font-black text-primary mb-2"><?php echo $event['title']; ?></h3>
                    <p class="text-sm text-primary/60 leading-relaxed"><?php echo $event['desc']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!-- More events placeholder -->
            <div class="relative pl-12 opacity-40">
                <div class="absolute left-0 top-1 w-10 h-10 rounded-2xl bg-slate-100 border-2 border-slate-200 flex items-center justify-center z-10">
                    <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-400"></i>
                </div>
                <p class="text-[10px] font-black text-primary/30 uppercase tracking-[0.2em] pt-4">End of Timeline Nodes</p>
            </div>
        </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
