<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Profile
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Admin or Super Admin role
require_role(['Admin', 'Super Admin']);
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Teacher Header and Navigation -->
    <?php require_once __DIR__ . '/_teacher_header.php'; ?>
<?php
$salary_type = $teacher['salary_type'] ?? 'Per Student';
$minute_rate = isset($teacher['minute_rate']) ? floatval($teacher['minute_rate']) : 8.50;

if ($salary_type === 'Fixed Monthly') {
    $base_salary = isset($teacher['salary']) ? floatval($teacher['salary']) : (isset($teacher['basic_salary']) ? floatval($teacher['basic_salary']) : 0);
} else {
    // Dynamic Per Student salary calculation based on assigned students and their schedule/durations
    $base_salary = 0;
    if (isset($assigned_students) && is_array($assigned_students)) {
        foreach ($assigned_students as $student) {
            $days_count = 0;
            $student_duration = 30; // default minutes
            $days_list = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days_list as $d) {
                if (!empty($student[$d . '_enabled']) && $student[$d . '_enabled'] != 'false' && $student[$d . '_enabled'] != '0') {
                    $days_count++;
                    if (isset($student[$d . '_duration'])) {
                        $student_duration = intval($student[$d . '_duration']);
                    }
                }
            }
            
            // Map days to standard 3 or 5
            $rate_days = ($days_count <= 3) ? 3 : 5;
            // Map duration to standard intervals (30, 45, 60, 90)
            $rate_dur = 30;
            if ($student_duration > 75) $rate_dur = 90;
            elseif ($student_duration > 52) $rate_dur = 60;
            elseif ($student_duration > 37) $rate_dur = 45;
            
            $rate_key = 'rate_' . $rate_dur . '_' . $rate_days;
            $student_rate = isset($teacher[$rate_key]) ? floatval($teacher[$rate_key]) : 0;
            
            // If the rate is 0/unset, fallback to standard default rates
            if ($student_rate <= 0) {
                if ($rate_dur == 30) $student_rate = ($rate_days == 3) ? 1000 : 1500;
                elseif ($rate_dur == 45) $student_rate = ($rate_days == 3) ? 1500 : 2000;
                elseif ($rate_dur == 60) $student_rate = ($rate_days == 3) ? 2000 : 2500;
                else $student_rate = ($rate_days == 3) ? 3000 : 4000;
            }
            $base_salary += $student_rate;
        }
    }
}

$allowances = isset($teacher['allowances']) ? floatval($teacher['allowances']) : (isset($teacher['allowance']) ? floatval($teacher['allowance']) : 0);
$deductions = isset($teacher['deductions']) ? floatval($teacher['deductions']) : 0;
$extra_classes = isset($teacher['extra_classes']) ? floatval($teacher['extra_classes']) : 0;

$est_final_salary = $base_salary + $allowances - $deductions + $extra_classes;

$bank_name = isset($teacher['bank_name']) && $teacher['bank_name'] ? $teacher['bank_name'] : 'N/A';
$account_number = isset($teacher['account_number']) && $teacher['account_number'] ? $teacher['account_number'] : 'N/A';
$account_title = isset($teacher['account_title']) && $teacher['account_title'] ? $teacher['account_title'] : $teacher['name'];
$payment_method = isset($teacher['payment_method']) && $teacher['payment_method'] ? $teacher['payment_method'] : 'N/A';
?>

    <!-- Main Grid -->
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
              <span class="text-primary/60 font-semibold">Teacher ID:</span>
              <span class="text-primary font-black tracking-wide"><?php echo htmlspecialchars($teacher['id']); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Employee ID:</span>
              <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($teacher['employee_id'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Full Name:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($account_title); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Father Name:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['father_name'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Gender:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['gender'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Marital Status:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['marital_status'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">CNIC:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['cnic'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Blood Group:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['blood_group'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Date of Birth:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['dob'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Age:</span>
              <span class="text-primary font-bold"><?php 
                if (!empty($teacher['dob'])) {
                    $dob = new DateTime($teacher['dob']);
                    $now = new DateTime();
                    echo $now->diff($dob)->y . ' Years';
                } else {
                    echo 'N/A';
                }
              ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Nationality:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['nationality'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Country:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['country'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">City:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['city'] ?? $teacher['location'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Time Zone:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['timezone'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-primary/60 font-semibold">Marital Status:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['marital_status'] ?? 'N/A'); ?></span>
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
              <span class="text-primary/60 font-semibold">Username:</span>
              <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($teacher['username'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Password:</span>
              <button class="text-xs font-bold text-primary/50 hover:text-primary uppercase tracking-wider transition-colors">Reset/View</button>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Role:</span>
              <span class="text-primary font-bold bg-primary/10 px-2 py-0.5 rounded text-[10px]">Teacher</span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Created By:</span>
              <span class="text-primary font-bold">Admin Panel</span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Created Date:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['joining_date'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-primary/60 font-semibold">Last Login:</span>
              <span class="text-primary font-bold">N/A</span>
            </div>
          </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
          <div class="flex items-center gap-2 mb-5">
            <i data-lucide="phone-call" class="w-4 h-4 text-primary"></i>
            <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Contact Info</h3>
          </div>
          <div class="space-y-3 text-xs">
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Phone:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['phone'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">WhatsApp:</span>
              <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $teacher['whatsapp'] ?? ''); ?>" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-bold flex items-center gap-1"><i data-lucide="message-circle" class="w-3 h-3"></i> <?php echo htmlspecialchars($teacher['whatsapp'] ?? 'N/A'); ?></a>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Email:</span>
              <span class="text-primary font-bold font-mono"><?php echo htmlspecialchars($teacher['email'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center border-b border-primary/5 pb-2">
              <span class="text-primary/60 font-semibold">Emergency:</span>
              <span class="text-rose-600 font-bold"><?php echo htmlspecialchars($teacher['emergency_contact'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex flex-col gap-1 pb-2">
              <span class="text-primary/60 font-semibold">Address:</span>
              <span class="text-primary font-bold leading-relaxed"><?php echo nl2br(htmlspecialchars($teacher['address'] ?? 'N/A')); ?></span>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN -->
      <div class="lg:col-span-2 space-y-6">
        
        <!-- Professional & Courses Section Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <!-- Professional Information -->
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
              <i data-lucide="briefcase" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Professional Details</h3>
            </div>
            <div class="space-y-3 text-xs">
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Qualification:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['qualification'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Specialization:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['specialization'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Teaching Exp:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['experience'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Joining Date:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['joining_date'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Teacher Status:</span>
                <span class="text-emerald-700 font-bold uppercase tracking-wider bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded text-[9px]"><?php echo htmlspecialchars($teacher['status'] ?? 'Active'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Languages:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['languages'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Teaching Level:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['teaching_level'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Department:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['department'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Designation:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['designation'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Joining Type:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['joining_type'] ?? 'N/A'); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Employment Type:</span>
                <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['employment_type'] ?? 'N/A'); ?></span>
              </div>
            </div>
          </div>

          <!-- Courses Authorized -->
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-5">
              <div class="flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-primary"></i>
                <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Approved Courses</h3>
              </div>
              <button class="text-[9px] font-bold text-emerald-600 hover:text-emerald-700 uppercase tracking-wider flex items-center gap-1">
                <i data-lucide="plus" class="w-3 h-3"></i> Add More
              </button>
            </div>
            
            <div class="grid grid-cols-2 gap-3 flex-grow">
              <?php
                $courses = !empty($teacher['specialization']) ? explode(',', $teacher['specialization']) : [];
                foreach ($courses as $course):
                    $course = trim($course);
                    if ($course):
              ?>
              <label class="flex items-center gap-2 cursor-pointer group">
                <div class="w-4 h-4 rounded border border-primary/30 bg-primary flex items-center justify-center">
                  <i data-lucide="check" class="w-3 h-3 text-white"></i>
                </div>
                <span class="text-xs font-bold text-primary"><?php echo htmlspecialchars($course); ?></span>
              </label>
              <?php 
                    endif;
                endforeach; 
                if (empty($courses)):
              ?>
              <span class="text-xs font-semibold text-primary/70 col-span-2">No courses selected</span>
              <?php endif; ?>
            </div>
            
            <div class="mt-4 pt-4 border-t border-primary/5">
              <button class="w-full py-2 border border-dashed border-primary/30 rounded-xl text-xs font-bold text-primary/60 hover:text-primary hover:border-primary/60 hover:bg-primary/5 transition-colors">
                + Custom Course
              </button>
            </div>
          </div>
        </div>



        <!-- Time Slots & Schedule -->
        <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
              <i data-lucide="calendar-clock" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Availability & Time Slots</h3>
            </div>
            <button class="bg-primary/5 hover:bg-primary/10 text-primary px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors flex items-center gap-1">
              <i data-lucide="plus" class="w-3 h-3"></i> Add Slot
            </button>
          </div>
          
          <div class="space-y-3">
            <!-- Slot Card -->
                                    <?php 
            $days = ['monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed', 'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun'];
            $has_slots = false;
            foreach ($days as $day => $short_day):
                $slot_key = 'slots_' . $day;
                if (!empty($teacher[$slot_key])):
                    $has_slots = true;
            ?>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-transparent rounded-xl border border-primary/5">
              <div class="flex items-center gap-4 mb-3 sm:mb-0">
                <div class="w-10 h-10 rounded-xl bg-white border border-primary/10 flex flex-col items-center justify-center shrink-0">
                  <span class="text-[9px] font-bold text-primary/60 uppercase"><?php echo $short_day; ?></span>
                </div>
                <div>
                  <h4 class="font-bold text-primary text-sm"><?php echo ucfirst($day); ?> Shift</h4>
                  <p class="text-[10px] font-semibold text-primary/60 uppercase tracking-wider"><?php echo htmlspecialchars($teacher[$slot_key]); ?></p>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <span class="px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded text-[10px] font-bold">Available</span>
              </div>
            </div>
            <?php 
                endif;
            endforeach; 
            if (!$has_slots):
            ?>
            <div class="text-center p-4 text-xs font-semibold text-primary/50">No time slots specified.</div>
            <?php endif; ?>
          </div>
        </div>

        
        <!-- Salary & Bank Details -->

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
              <i data-lucide="wallet" class="w-4 h-4 text-primary"></i>
              <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Salary Structure</h3>
            </div>
            <div class="space-y-3 text-xs">
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Minute Rate:</span>
                <span class="text-primary font-black tracking-wide">Rs. <?php echo $minute_rate; ?>/min</span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Salary Type:</span>
                <span class="text-primary font-bold uppercase tracking-wider text-[10px] bg-primary/5 px-2 py-0.5 rounded"><?php echo htmlspecialchars($salary_type); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Base Monthly:</span>
                <span class="text-primary font-bold">Rs. <?php echo number_format($base_salary); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Allowances/Bonus:</span>
                <span class="text-emerald-600 font-bold">+ Rs. <?php echo number_format($allowances); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Deductions/Penalties:</span>
                <span class="text-rose-600 font-bold">- Rs. <?php echo number_format($deductions); ?></span>
              </div>
              <div class="flex justify-between items-center border-b border-primary/5 pb-2">
                <span class="text-primary/60 font-semibold">Extra Classes:</span>
                <span class="text-emerald-600 font-bold">+ Rs. <?php echo number_format($extra_classes); ?></span>
              </div>
              <div class="flex justify-between items-center pt-1 pb-2 <?php echo ($salary_type === 'Per Student') ? 'border-b border-primary/5' : ''; ?>">
                <span class="text-primary font-extrabold uppercase tracking-wider">Est. Final Salary:</span>
                <span class="text-primary font-black text-sm">Rs. <?php echo number_format($est_final_salary); ?></span>
              </div>
              
              <?php if ($salary_type === 'Per Student'): ?>
              <div class="mt-4 pt-1">
                <span class="text-primary/60 font-bold block mb-2 uppercase tracking-wider text-[9px]">Per-Student Rates:</span>
                <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-[10px]">
                  <div class="flex justify-between border-b border-dashed border-primary/5 pb-1">
                    <span class="text-primary/50">30m (3d):</span>
                    <span class="text-primary font-bold">Rs. <?php echo number_format($teacher['rate_30_3'] ?? 0); ?></span>
                  </div>
                  <div class="flex justify-between border-b border-dashed border-primary/5 pb-1">
                    <span class="text-primary/50">30m (5d):</span>
                    <span class="text-primary font-bold">Rs. <?php echo number_format($teacher['rate_30_5'] ?? 0); ?></span>
                  </div>
                  <div class="flex justify-between border-b border-dashed border-primary/5 pb-1">
                    <span class="text-primary/50">45m (3d):</span>
                    <span class="text-primary font-bold">Rs. <?php echo number_format($teacher['rate_45_3'] ?? 0); ?></span>
                  </div>
                  <div class="flex justify-between border-b border-dashed border-primary/5 pb-1">
                    <span class="text-primary/50">45m (5d):</span>
                    <span class="text-primary font-bold">Rs. <?php echo number_format($teacher['rate_45_5'] ?? 0); ?></span>
                  </div>
                  <div class="flex justify-between border-b border-dashed border-primary/5 pb-1">
                    <span class="text-primary/50">60m (3d):</span>
                    <span class="text-primary font-bold">Rs. <?php echo number_format($teacher['rate_60_3'] ?? 0); ?></span>
                  </div>
                  <div class="flex justify-between border-b border-dashed border-primary/5 pb-1">
                    <span class="text-primary/50">60m (5d):</span>
                    <span class="text-primary font-bold">Rs. <?php echo number_format($teacher['rate_60_5'] ?? 0); ?></span>
                  </div>
                  <div class="flex justify-between border-b border-dashed border-primary/5 pb-1">
                    <span class="text-primary/50">90m (3d):</span>
                    <span class="text-primary font-bold">Rs. <?php echo number_format($teacher['rate_90_3'] ?? 0); ?></span>
                  </div>
                  <div class="flex justify-between border-b border-dashed border-primary/5 pb-1">
                    <span class="text-primary/50">90m (5d):</span>
                    <span class="text-primary font-bold">Rs. <?php echo number_format($teacher['rate_90_5'] ?? 0); ?></span>
                  </div>
                </div>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center justify-between mb-5">
              <div class="flex items-center gap-2">
                <i data-lucide="landmark" class="w-4 h-4 text-primary"></i>
                <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Bank Details</h3>
              </div>
              <button class="text-primary/50 hover:text-primary transition-colors">
                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
              </button>
            </div>
            
            <div class="p-4 bg-gradient-to-br from-primary to-[#123940] rounded-xl text-white shadow-md relative overflow-hidden mb-4">
              <div class="absolute -right-4 -bottom-4 w-24 h-24 rounded-full border-4 border-white/5 pointer-events-none"></div>
              <div class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Bank Name</div>
              <div class="font-black text-sm tracking-wide mb-4"><?php echo htmlspecialchars($bank_name); ?></div>
              
              <div class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Account Number</div>
              <div class="font-mono font-bold tracking-wider mb-2"><?php echo htmlspecialchars($account_number); ?></div>
              
              <div class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Account Title</div>
              <div class="font-bold text-sm truncate"><?php echo htmlspecialchars($account_title); ?></div>
            </div>
            
            <div class="flex justify-between items-center text-xs">
              <span class="text-primary/60 font-semibold">Payment Method:</span>
              <span class="text-primary font-bold bg-primary/10 px-2 py-0.5 rounded text-[10px]"><?php echo htmlspecialchars($payment_method); ?></span>
            </div>
            <div class="flex justify-between items-center text-xs mt-2">
              <span class="text-primary/60 font-semibold">Wise Email:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['wise_email'] ?? 'N/A'); ?></span>
            </div>
            <div class="flex justify-between items-center text-xs mt-2">
              <span class="text-primary/60 font-semibold">Mobile Wallet:</span>
              <span class="text-primary font-bold"><?php echo htmlspecialchars($teacher['mobile_wallet'] ?? 'N/A'); ?></span>
            </div>
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
              <span class="text-[10px] font-bold text-primary text-center leading-tight">CNIC Front</span>
            </div>
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
              <span class="text-[10px] font-bold text-primary text-center leading-tight">CNIC Back</span>
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
              <span class="text-[10px] font-bold text-primary text-center leading-tight">Certificates</span>
            </div>
            <!-- Doc Item -->
            <div class="flex flex-col items-center group cursor-pointer">
              <div class="w-full aspect-[3/4] bg-transparent rounded-xl border border-primary/10 flex flex-col items-center justify-center mb-2 group-hover:border-primary/30 group-hover:bg-primary/5 transition-all relative">
                <i data-lucide="scroll-text" class="w-6 h-6 text-primary/40 group-hover:text-primary transition-colors mb-2"></i>
                <span class="text-[9px] font-bold text-primary/50 group-hover:text-primary uppercase">PDF</span>
                
                <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-2">
                  <button class="p-1.5 bg-white text-primary rounded-lg shadow-sm hover:scale-110 transition-transform"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                  <button class="p-1.5 bg-white text-primary rounded-lg shadow-sm hover:scale-110 transition-transform"><i data-lucide="download" class="w-3.5 h-3.5"></i></button>
                </div>
              </div>
              <span class="text-[10px] font-bold text-primary text-center leading-tight">Experience Letters</span>
            </div>
            <!-- Doc Item -->
            <div class="flex flex-col items-center group cursor-pointer">
              <div class="w-full aspect-[3/4] bg-transparent rounded-xl border border-primary/10 flex flex-col items-center justify-center mb-2 group-hover:border-primary/30 group-hover:bg-primary/5 transition-all relative">
                <i data-lucide="file-signature" class="w-6 h-6 text-primary/40 group-hover:text-primary transition-colors mb-2"></i>
                <span class="text-[9px] font-bold text-primary/50 group-hover:text-primary uppercase">PDF</span>
                
                <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-2">
                  <button class="p-1.5 bg-white text-primary rounded-lg shadow-sm hover:scale-110 transition-transform"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                  <button class="p-1.5 bg-white text-primary rounded-lg shadow-sm hover:scale-110 transition-transform"><i data-lucide="download" class="w-3.5 h-3.5"></i></button>
                </div>
              </div>
              <span class="text-[10px] font-bold text-primary text-center leading-tight">Appointment Letter</span>
            </div>
          </div>
        </div>
        
        <div class="lg:col-span-3">
          <div class="bg-white rounded-2xl p-6 border border-primary/10 shadow-sm">
            <div class="flex items-center justify-between mb-5">
              <div class="flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-4 h-4 text-primary"></i>
                <h3 class="font-extrabold text-[11px] uppercase tracking-wider text-primary">Admin Notes</h3>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
              <div class="border border-primary/5 rounded-xl p-4 bg-slate-50/50">
                <span class="text-primary/60 font-semibold block mb-2 uppercase tracking-wider text-[10px]">Internal Notes</span>
                <span class="text-primary font-bold"><?php echo nl2br(htmlspecialchars($teacher['internal_notes'] ?? 'N/A')); ?></span>
              </div>
              <div class="border border-primary/5 rounded-xl p-4 bg-slate-50/50">
                <span class="text-primary/60 font-semibold block mb-2 uppercase tracking-wider text-[10px]">Performance Notes</span>
                <span class="text-primary font-bold"><?php echo nl2br(htmlspecialchars($teacher['performance_notes'] ?? 'N/A')); ?></span>
              </div>
              <div class="border border-primary/5 rounded-xl p-4 bg-slate-50/50">
                <span class="text-primary/60 font-semibold block mb-2 uppercase tracking-wider text-[10px]">Warnings</span>
                <span class="text-primary font-bold text-rose-600"><?php echo nl2br(htmlspecialchars($teacher['warnings'] ?? 'N/A')); ?></span>
              </div>
              <div class="border border-primary/5 rounded-xl p-4 bg-slate-50/50">
                <span class="text-primary/60 font-semibold block mb-2 uppercase tracking-wider text-[10px]">Achievements</span>
                <span class="text-primary font-bold text-emerald-600"><?php echo nl2br(htmlspecialchars($teacher['achievements'] ?? 'N/A')); ?></span>
              </div>
              <div class="md:col-span-2 border border-primary/5 rounded-xl p-4 bg-slate-50/50">
                <span class="text-primary/60 font-semibold block mb-2 uppercase tracking-wider text-[10px]">Private Notes</span>
                <span class="text-primary font-bold text-amber-600"><?php echo nl2br(htmlspecialchars($teacher['private_notes'] ?? 'N/A')); ?></span>
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
