<?php
/**
 * Al Foz Islamic Institute - Student ERP Academic Profile
 */
require_once __DIR__ . '/includes/student_context.php';

$initials = 'ST';
if ($student) {
    $name_parts = explode(" ", trim($student['name']));
    $initials = strtoupper(substr($name_parts[0], 0, 1) . (isset($name_parts[1]) ? substr($name_parts[1], 0, 1) : ''));
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight uppercase">my profile</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Student Portal Management Area</p>
      </div>
      <button onclick="window.print()" class="px-5 py-2.5 bg-primary hover:bg-opacity-90 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition-all flex items-center gap-2 active:scale-95">
        <i data-lucide="printer" class="w-4 h-4"></i> Print ID Card
      </button>
    </div>

    <?php if ($student): ?>
    
    <!-- HERO PROFILE HEADER CARD -->
    <div class="mb-8 bg-white border border-primary/10 rounded-[24px] p-6 sm:p-8 shadow-sm relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>

      <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10">
        <!-- Profile Picture -->
        <div class="relative shrink-0">
          <?php echo render_dashboard_profile_pic_html(); ?>
        </div>

        <div class="text-center sm:text-left space-y-1.5 flex-grow">
          <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
            <span class="px-3 py-1 bg-primary text-white font-extrabold text-[10px] rounded-full uppercase tracking-wider">
              Roll No: <?php echo htmlspecialchars($student['roll_no'] ?? $student['student_id'] ?? 'STU-101'); ?>
            </span>
            <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-[10px] rounded-full uppercase tracking-wider">
              <?php echo htmlspecialchars($student['status'] ?? 'Active'); ?> Scholar
            </span>
          </div>

          <h2 class="text-2xl sm:text-3xl font-black text-primary tracking-tight"><?php echo htmlspecialchars($student['name']); ?></h2>
          <p class="text-xs sm:text-sm text-primary/80 font-medium">
            Program: <strong class="text-primary"><?php echo htmlspecialchars($student['course'] ?? 'Tajweed & Quran Program'); ?></strong>
          </p>
          <p class="text-[11px] text-primary/60 font-mono">
            Portal Email: <?php echo htmlspecialchars($student['portal_email'] ?? $student['email'] ?? 'student@alfoz.com'); ?>
          </p>
        </div>
      </div>
    </div>

    <!-- CARDS GRID SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      <!-- CARD 1: STUDENT INFORMATION CARD -->
      <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-6 hover:shadow-md transition-all">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary/60 mb-5 flex items-center gap-2">
          <i data-lucide="user" class="w-4 h-4 text-primary"></i> Student Information
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Full Name</span>
            <span class="font-black text-primary text-sm mt-0.5 block"><?php echo htmlspecialchars($student['name']); ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Roll Number</span>
            <span class="font-mono font-black text-primary text-sm mt-0.5 block"><?php echo htmlspecialchars($student['roll_no'] ?? $student['student_id'] ?? 'STU-101'); ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Gender</span>
            <span class="font-bold text-primary mt-0.5 block"><?php echo htmlspecialchars(ucfirst($student['gender'] ?? 'Male')); ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Age / Date of Birth</span>
            <span class="font-bold text-primary mt-0.5 block"><?php echo htmlspecialchars($student['dob'] ?? 'N/A'); ?> (Age <?php echo $student['age'] ?? 16; ?>)</span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Country & Location</span>
            <span class="font-bold text-primary mt-0.5 block"><?php echo htmlspecialchars($student['country'] ?? 'Pakistan'); ?> <?php echo !empty($student['city']) ? '('.htmlspecialchars($student['city']).')' : ''; ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Admission Date</span>
            <span class="font-mono font-bold text-primary mt-0.5 block"><?php echo !empty($student['joining_date']) ? date('d M, Y', strtotime($student['joining_date'])) : '15 Jan, 2026'; ?></span>
          </div>
        </div>
      </div>

      <!-- CARD 2: PARENT INFORMATION CARD -->
      <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-6 hover:shadow-md transition-all">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary/60 mb-5 flex items-center gap-2">
          <i data-lucide="users" class="w-4 h-4 text-primary"></i> Parent & Guardian Information
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Guardian Name</span>
            <span class="font-black text-primary text-sm mt-0.5 block"><?php echo htmlspecialchars($student['parent_name'] ?? $student['parent_info']['name'] ?? 'Guardian Name Recorded'); ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Relationship</span>
            <span class="font-bold text-primary mt-0.5 block"><?php echo htmlspecialchars($student['parent_relation'] ?? $student['parent_info']['relation'] ?? 'Father / Guardian'); ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Guardian Phone Number</span>
            <span class="font-mono font-bold text-primary mt-0.5 block"><?php echo htmlspecialchars($student['parent_phone'] ?? $student['phone'] ?? '+92-300-0000000'); ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Emergency Email</span>
            <span class="font-mono font-bold text-primary mt-0.5 block truncate"><?php echo htmlspecialchars($student['parent_email'] ?? $student['email'] ?? 'parent@alfoz.com'); ?></span>
          </div>
        </div>
      </div>

      <!-- CARD 3: TEACHER INFORMATION CARD -->
      <div id="teacher" class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-6 hover:shadow-md transition-all">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary/60 mb-5 flex items-center gap-2">
          <i data-lucide="graduation-cap" class="w-4 h-4 text-primary"></i> Assigned Faculty Teacher
        </h3>

        <div class="p-4 bg-primary/5 rounded-2xl border border-primary/10 flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary text-white flex items-center justify-center font-black text-base shadow shrink-0">
              <?php echo strtoupper(substr($student['teacher_name'] ?? 'Faculty Instructor', 0, 2)); ?>
            </div>
            <div>
              <h4 class="text-sm font-black text-primary"><?php echo htmlspecialchars($student['teacher_name'] ?? 'Faculty Instructor'); ?></h4>
              <p class="text-[10px] font-bold text-primary/60 uppercase tracking-wider mt-0.5">Quran & Tajweed Scholar</p>
            </div>
          </div>

          <?php if(!empty($student['whatsapp'])): ?>
          <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $student['whatsapp']); ?>" target="_blank" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5 shrink-0">
            <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp Instructor
          </a>
          <?php endif; ?>
        </div>
      </div>

      <!-- CARD 4: COURSE & SCHEDULE INFORMATION -->
      <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-6 hover:shadow-md transition-all">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary/60 mb-5 flex items-center gap-2">
          <i data-lucide="book-open" class="w-4 h-4 text-primary"></i> Course & Shift Schedule Matrix
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs mb-4">
          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Enrolled Program</span>
            <span class="font-black text-primary text-sm mt-0.5 block"><?php echo htmlspecialchars($student['course'] ?? 'Tajweed & Quran Program'); ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Shift / Timezone</span>
            <span class="font-bold text-primary mt-0.5 block"><?php echo htmlspecialchars($student['shift'] ?? 'Regular'); ?> (<?php echo htmlspecialchars($student['timezone'] ?? 'PKT'); ?>)</span>
          </div>
        </div>

        <!-- Weekly matrix -->
        <div class="grid grid-cols-7 gap-1.5 text-center text-[10px]">
          <?php 
          foreach ($days_of_week as $day):
              $is_enabled = isset($student[$day . '_enabled']) && $student[$day . '_enabled'];
          ?>
          <div class="<?php echo $is_enabled ? 'bg-primary text-white font-bold' : 'bg-slate-100 text-slate-400'; ?> p-2 rounded-xl">
            <p class="font-black uppercase text-[8px]"><?php echo substr(ucfirst($day), 0, 3); ?></p>
            <p class="text-[9px] mt-0.5"><?php echo $is_enabled ? 'Active' : 'Off'; ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- CARD 5: FEE STATUS CARD -->
      <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-6 hover:shadow-md transition-all">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary/60 mb-5 flex items-center gap-2">
          <i data-lucide="wallet" class="w-4 h-4 text-primary"></i> Tuition Ledger & Fee Status
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Monthly Tuition Fee</span>
            <span class="font-black text-primary text-sm mt-0.5 block"><?php echo htmlspecialchars($student['currency'] ?? 'PKR'); ?> <?php echo number_format($student['monthly_fee'] ?? 4500); ?></span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Payment Status</span>
            <span class="font-bold px-2.5 py-0.5 rounded-full border inline-block mt-1 text-xs <?php echo ($student['fee_status'] ?? 'Pending') === 'Paid' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700'; ?>">
              <?php echo htmlspecialchars($student['fee_status'] ?? 'Pending'); ?>
            </span>
          </div>
        </div>
      </div>

      <!-- CARD 6: ATTENDANCE & ACADEMIC PROGRESS -->
      <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-6 hover:shadow-md transition-all">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary/60 mb-5 flex items-center gap-2">
          <i data-lucide="trending-up" class="w-4 h-4 text-primary"></i> Attendance & Evaluation Metrics
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Attendance Rate</span>
            <span class="font-black text-emerald-600 text-base mt-0.5 block"><?php echo $student['performance']['attendance_score'] ?? 96; ?>%</span>
          </div>

          <div class="p-3.5 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-[10px] font-bold text-primary/50 block uppercase">Exam Grade Average</span>
            <span class="font-black text-primary text-base mt-0.5 block"><?php echo $student['performance']['exam_score'] ?? 94; ?>% (A+)</span>
          </div>
        </div>
      </div>

      <!-- CARD 7: DOCUMENTS & CERTIFICATES CARD -->
      <div id="documents" class="lg:col-span-2 bg-white rounded-[24px] border border-primary/10 shadow-sm p-6 hover:shadow-md transition-all">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary/60 mb-5 flex items-center gap-2">
          <i data-lucide="folder-open" class="w-4 h-4 text-primary"></i> Official Documents & Certificates
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
          <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl flex items-center justify-between">
            <div>
              <p class="font-bold text-primary">Student ID Card</p>
              <p class="text-[10px] text-primary/50">Digital Verification</p>
            </div>
            <button onclick="window.print()" class="px-3 py-1.5 bg-primary text-white font-bold text-[10px] rounded-lg shadow-sm">Print</button>
          </div>

          <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl flex items-center justify-between">
            <div>
              <p class="font-bold text-primary">Enrollment Slip</p>
              <p class="text-[10px] text-primary/50">Official Certificate</p>
            </div>
            <button onclick="alert('Downloading Enrollment Slip PDF...')" class="px-3 py-1.5 bg-primary text-white font-bold text-[10px] rounded-lg shadow-sm">Download</button>
          </div>

          <div class="p-4 bg-primary/5 border border-primary/10 rounded-2xl flex items-center justify-between">
            <div>
              <p class="font-bold text-primary">Academic Report</p>
              <p class="text-[10px] text-primary/50">Progress Record</p>
            </div>
            <a href="/student/reports.php" class="px-3 py-1.5 bg-primary text-white font-bold text-[10px] rounded-lg shadow-sm">View</a>
          </div>
        </div>
      </div>

    </div>
    <?php endif; ?>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
