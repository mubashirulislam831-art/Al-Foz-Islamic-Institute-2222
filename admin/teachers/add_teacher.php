<?php
/**
 * Al Foz Islamic Institute - Admin Add Teacher Form
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/teachers_data.php';

// Strictly require Admin or Super Admin role
require_role(['Admin', 'Super Admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_id = add_teacher($_POST);
    header('Location: teachers.php?msg=added');
    exit;
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8">
      <a href="teachers.php" class="text-[10px] font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to Registry
      </a>
      <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Onboard New Scholar</h1>
      <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Register detailed faculty credentials, specialization, and status attributes.</p>
    </div>

    <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-6 sm:p-8 max-w-4xl">
      <form action="add_teacher.php" method="POST" enctype="multipart/form-data" class="space-y-8">
        
        <!-- SECTION 1: Personal Information -->
        <div>
          <h2 class="text-xs font-bold uppercase tracking-widest text-primary border-b border-primary/10 pb-2 mb-4 flex items-center gap-1.5">
            <i data-lucide="user" class="w-4 h-4"></i> Personal Information
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile pic -->
            <div class="md:col-span-3 flex flex-col sm:flex-row items-center gap-4">
              <div class="w-20 h-20 rounded-2xl bg-primary/5 border-2 border-dashed border-primary/20 flex flex-col items-center justify-center text-primary/40 hover:bg-primary/10 transition-colors cursor-pointer shrink-0 relative">
                <input type="file" name="teacher_picture" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                <i data-lucide="camera" class="w-6 h-6"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider mt-1">Upload JPG</span>
              </div>
              <div>
                <h4 class="text-xs font-bold text-primary">Educator Profile Picture</h4>
                <p class="text-[10px] text-primary/60 mt-0.5">Please provide a clear white-background headshot of the teacher. Max size: 2MB.</p>
              </div>
            </div>

            <!-- Full name -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Full Name</label>
              <input type="text" name="name" placeholder="e.g. Fatima Al-Zahra" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Father name -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Father's Name</label>
              <input type="text" name="father_name" placeholder="e.g. Abdullah Khan" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Gender -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Gender</label>
              <select name="gender" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
                <option>Female</option>
                <option>Male</option>
              </select>
            </div>
            <!-- DOB -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Date of Birth</label>
              <input type="date" name="dob" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Marital Status -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Marital Status</label>
              <select name="marital_status" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
                <option>Married</option>
                <option>Unmarried</option>
              </select>
            </div>
            <!-- Nationality -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Nationality</label>
              <input type="text" name="nationality" placeholder="e.g. Pakistani" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Country -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Country</label>
              <input type="text" name="country" placeholder="e.g. Pakistan" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- City -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">City</label>
              <input type="text" name="city" placeholder="e.g. Lahore" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Time Zone -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Time Zone</label>
              <input type="text" name="timezone" placeholder="e.g. PKT (UTC+5)" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
          </div>
        </div>

        <!-- SECTION 2: Contact Information -->
        <div>
          <h2 class="text-xs font-bold uppercase tracking-widest text-primary border-b border-primary/10 pb-2 mb-4 flex items-center gap-1.5">
            <i data-lucide="phone" class="w-4 h-4"></i> Contact Information
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Phone -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Phone Number</label>
              <input type="tel" name="phone" placeholder="e.g. +92 300 1234567" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- WhatsApp -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">WhatsApp Number</label>
              <input type="tel" name="whatsapp" placeholder="e.g. +92 300 1234567" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Email -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Email Address</label>
              <input type="email" name="email" placeholder="e.g. fatima@alfoz.com" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Address -->
            <div class="md:col-span-2">
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Address</label>
              <input type="text" name="address" placeholder="e.g. House 45-B, Sector Z, DHA" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Emergency Contact -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Emergency Contact Details</label>
              <input type="text" name="emergency_contact" placeholder="Brother: +92 311 9876543" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
          </div>
        </div>

        <!-- SECTION 3: Professional Information & Status -->
        <div>
          <h2 class="text-xs font-bold uppercase tracking-widest text-primary border-b border-primary/10 pb-2 mb-4 flex items-center gap-1.5">
            <i data-lucide="briefcase" class="w-4 h-4"></i> Professional & Status Credentials
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Qualification -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Qualification</label>
              <input type="text" name="qualification" placeholder="e.g. Wafaq-ul-Madaris Al-Aaliyah Degree" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Experience -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Experience (Years)</label>
              <input type="number" name="experience" placeholder="e.g. 5" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Specialization -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Specialization</label>
              <input type="text" name="specialization" placeholder="e.g. Tajweed Rules & Hifdh Coaching" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Joining date -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Joining Date</label>
              <input type="date" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all" required>
            </div>
            <!-- Status Dropdown -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Teacher Status</label>
              <select name="status" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-black text-primary outline-none focus:border-primary bg-white transition-all">
                <option>Under Training</option>
                <option>Probation</option>
                <option selected>Permanent</option>
                <option>Senior Teacher</option>
                <option>Head Teacher</option>
                <option>Inactive</option>
                <option>Resigned</option>
              </select>
            </div>
          </div>
        </div>

        <!-- SECTION 4: Portal Access -->
        <div>
          <h2 class="text-xs font-bold uppercase tracking-widest text-primary border-b border-primary/10 pb-2 mb-4 flex items-center gap-1.5">
            <i data-lucide="lock" class="w-4 h-4"></i> Portal Access
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Portal Login Email</label>
              <input type="email" name="portal_email" placeholder="teacher@alfoz.com" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Portal Password</label>
              <div class="relative">
                <input type="text" name="portal_password" placeholder="Enter secure password" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
                <button type="button" onclick="document.querySelector('input[name=\'portal_password\']').value = Math.random().toString(36).slice(-8);" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary/10 hover:bg-primary/20 text-primary px-2 py-1 rounded text-[9px] font-bold uppercase tracking-wider transition-colors">Generate</button>
              </div>
            </div>
          </div>
          <p class="text-[10px] text-primary/60 mt-2"><i data-lucide="info" class="w-3 h-3 inline"></i> These credentials will be used by the teacher to log into their portal.</p>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-primary/10">
          <a href="teachers.php" class="px-5 py-2.5 border border-primary/20 text-primary rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-primary/5 transition-all">Cancel</a>
          <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-primary/95 shadow-md transition-all">Onboard Educator</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>


