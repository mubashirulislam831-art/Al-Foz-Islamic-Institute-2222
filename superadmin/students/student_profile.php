<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

require_role('Super Admin');

$student_id = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
$student = null;
$students = get_all_students();
foreach ($students as $s) {
    if ($s['id'] == $student_id || (isset($s['student_id']) && $s['student_id'] == $student_id)) {
        $student = $s;
        break;
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Header and Navigation -->
    <!-- Dynamic Header and Navigation -->
    <?php require_once __DIR__ . '/_student_header.php'; ?>

    <!-- Main Grid (Profile Section) -->
    <div id="profile-section" class="profile-section-card transition-all duration-500 border-2 border-transparent rounded-[32px] p-1">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN -->
        <div class="lg:col-span-1 space-y-6">
          
          <!-- Basic Information -->
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
              <i data-lucide="user" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Basic Information</h3>
            </div>
            <div class="space-y-3 text-xs">
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Student ID:</span>
                <span class="text-primary font-black tracking-wide"><?php echo htmlspecialchars($student['student_id'] ?? $student['id'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Father Name:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($student['father_name'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Gender:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($student['gender'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Date of Birth:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($student['dob'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Country:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($student['country'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">City:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($student['city'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-primary/60 font-semibold">Time Zone:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($student['timezone'] ?? 'N/A'); ?></span>
              </div>
            </div>
          </div>

          <!-- Account Information -->
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
              <i data-lucide="shield" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Account Details</h3>
            </div>
            <div class="space-y-3 text-xs">
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Student Login:</span>
                <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($student['portal_email'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Parent Login:</span>
                <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($student['parent_username'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Password:</span>
                <button class="text-xs font-bold text-primary/50 hover:text-primary uppercase tracking-wider transition-colors">Reset/View</button>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Role:</span>
                <span class="text-primary font-bold bg-primary/10 px-2 py-0.5 rounded text-[10px]">Student</span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Created Date:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($student['joining_date'] ?? 'N/A'); ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Academic Information -->
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center justify-between mb-5">
              <div class="flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-primary"></i>
                <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Academic Information</h3>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="p-4 rounded-xl border border-primary/10 bg-primary/5">
                <div class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Enrolled Course</div>
                <div class="font-bold text-sm text-primary mb-2"><?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?></div>
                
                <div class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Shift</div>
                <div class="font-bold text-sm text-primary"><?php echo htmlspecialchars($student['shift'] ?? 'Morning'); ?></div>
              </div>
              
              <div class="p-4 rounded-xl border border-primary/10 bg-primary/5">
                <div class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Current Level</div>
                <div class="font-bold text-sm text-primary mb-2"><?php echo htmlspecialchars($student['current_level'] ?? 'N/A'); ?></div>
                
                <div class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">School Grade</div>
                <div class="font-bold text-sm text-primary"><?php echo htmlspecialchars($student['school_grade'] ?? 'N/A'); ?></div>
              </div>
            </div>
            
            <div class="flex flex-wrap gap-2 text-xs">
               <span class="bg-primary/5 text-primary px-3 py-1.5 rounded-lg border border-primary/10 font-bold">
                 Memorized Paras: <?php echo htmlspecialchars($student['memorized_paras'] ?? '0'); ?>
               </span>
               <span class="bg-primary/5 text-primary px-3 py-1.5 rounded-lg border border-primary/10 font-bold">
                 Reading Level: <?php echo htmlspecialchars($student['reading_level'] ?? 'N/A'); ?>
               </span>
               <span class="bg-primary/5 text-primary px-3 py-1.5 rounded-lg border border-primary/10 font-bold">
                 Arabic Level: <?php echo htmlspecialchars($student['arabic_level'] ?? 'N/A'); ?>
               </span>
            </div>
          </div>

          <!-- Class Schedule Grid -->
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
              <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Weekly Schedule</h3>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
               <?php 
                 $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                 foreach($days as $day): 
                   $is_enabled = !empty($student[$day.'_enabled']);
                   $bg_class = $is_enabled ? 'bg-primary/10 border-primary/30' : 'bg-slate-50 border-slate-200';
                   $text_class = $is_enabled ? 'text-primary' : 'text-slate-400';
               ?>
               <div class="border rounded-xl p-3 text-center flex flex-col justify-center <?php echo $bg_class; ?>">
                  <span class="text-[10px] font-black uppercase tracking-widest mb-1 <?php echo $text_class; ?>"><?php echo substr(ucfirst($day), 0, 3); ?></span>
                  <?php if($is_enabled): ?>
                      <span class="text-xs font-bold text-primary"><?php echo htmlspecialchars($student[$day.'_time'] ?? '--:--'); ?></span>
                      <span class="text-[9px] font-semibold text-primary/60 mt-0.5"><?php echo htmlspecialchars($student[$day.'_duration'] ?? '30'); ?> mins</span>
                  <?php else: ?>
                      <span class="text-xs font-bold text-slate-400">Off</span>
                  <?php endif; ?>
               </div>
               <?php endforeach; ?>
            </div>
          </div>
          
          <!-- Documents Section -->
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center justify-between mb-5">
              <div class="flex items-center gap-2">
                <i data-lucide="file-stack" class="w-4 h-4 text-primary"></i>
                <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Official Documents</h3>
              </div>
              <button class="bg-primary/5 hover:bg-primary/10 text-primary px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors flex items-center gap-1">
                <i data-lucide="upload-cloud" class="w-3 h-3"></i> Upload
              </button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
              <!-- Doc Item -->
              <div class="flex flex-col items-center group cursor-pointer">
                <div class="w-full aspect-[3/4] bg-transparent rounded-xl border border-primary/10 flex flex-col items-center justify-center mb-2 group-hover:border-primary/30 group-hover:bg-primary/5 transition-all relative">
                  <i data-lucide="credit-card" class="w-6 h-6 text-primary/40 group-hover:text-primary transition-colors mb-2"></i>
                  <span class="text-[9px] font-bold text-primary/50 group-hover:text-primary uppercase">JPG</span>
                  
                  <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-2">
                    <button class="p-1.5 bg-white text-primary rounded-lg shadow-sm hover:scale-110 transition-transform"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                    <button class="p-1.5 bg-white text-primary rounded-lg shadow-sm hover:scale-110 transition-transform"><i data-lucide="download" class="w-3.5 h-3.5"></i></button>
                  </div>
                </div>
                <span class="text-[10px] font-bold text-primary text-center leading-tight">Student ID</span>
              </div>
              <!-- Doc Item -->
              <div class="flex flex-col items-center group cursor-pointer">
                <div class="w-full aspect-[3/4] bg-transparent rounded-xl border border-primary/10 flex flex-col items-center justify-center mb-2 group-hover:border-primary/30 group-hover:bg-primary/5 transition-all relative">
                  <i data-lucide="file-check-2" class="w-6 h-6 text-primary/40 group-hover:text-primary transition-colors mb-2"></i>
                  <span class="text-[9px] font-bold text-primary/50 group-hover:text-primary uppercase">PDF</span>
                  
                  <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-2">
                    <button class="p-1.5 bg-white text-primary rounded-lg shadow-sm hover:scale-110 transition-transform"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                    <button class="p-1.5 bg-white text-primary rounded-lg shadow-sm hover:scale-110 transition-transform"><i data-lucide="download" class="w-3.5 h-3.5"></i></button>
                  </div>
                </div>
                <span class="text-[10px] font-bold text-primary text-center leading-tight">Previous Results</span>
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

<!-- Lucide Icons initialization -->
<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
