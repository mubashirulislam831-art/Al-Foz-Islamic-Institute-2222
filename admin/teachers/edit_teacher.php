<?php
/**
 * Al Foz Islamic Institute - Super Admin Edit Teacher Form
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/teachers_data.php';

// Strictly require Admin/Super Admin role
require_role(['Admin', 'Super Admin']);

$teacher_id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($teacher_id > 0) {
        update_teacher($teacher_id, $_POST);
    }
    header("Location: edit_teacher.php?id=" . $teacher_id . "&updated=success");
    exit;
}

$teacher = get_teacher_by_id($teacher_id);
if (!$teacher) {
    $all_teach = get_all_teachers();
    foreach ($all_teach as $t) {
        if (intval($t['id']) === $teacher_id || $t['employee_id'] === ($_GET['id'] ?? '') || end(explode('-', $t['employee_id'] ?? '')) === ($_GET['id'] ?? '')) {
            $teacher = $t;
            break;
        }
    }
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
      <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Modify Scholar Credentials</h1>
      <p class="text-[10px] text-primary/70 uppercase tracking-wider font-bold mt-1">Review and commit updates to active staff profiles.</p>
    </div>

    <div class="bg-white rounded-3xl border border-primary/10 shadow-sm p-6 sm:p-8 max-w-4xl">
      <?php if (isset($_GET['updated']) && $_GET['updated'] === 'success'): ?>
      <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs font-bold flex items-center gap-2">
        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
        Modifications successfully saved to the registry.
      </div>
      <?php endif; ?>
      <form action="edit_teacher.php?id=<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
        
        <!-- SECTION 1: Personal Information -->
        <div>
          <h2 class="text-xs font-bold uppercase tracking-widest text-primary border-b border-primary/10 pb-2 mb-4 flex items-center gap-1.5">
            <i data-lucide="user" class="w-4 h-4"></i> Personal Information
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile pic -->
            <div class="md:col-span-3 flex flex-col sm:flex-row items-center gap-4">
              <div class="relative shrink-0">
                <img src="<?php echo htmlspecialchars($teacher['teacher_picture'] ?? 'https://ui-avatars.com/api/?name='.urlencode($teacher['name'] ?? 'Teacher').'&background=184D55&color=fff&size=200'); ?>" alt="Current Headshot" class="w-20 h-20 rounded-2xl border-2 border-white shadow-md object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($teacher['name'] ?? 'Teacher'); ?>&background=184D55&color=fff&size=200';">
                <input type="file" name="teacher_picture" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                <span class="absolute -bottom-1 -right-1 bg-primary text-white p-1 rounded-md border border-white shadow-sm flex items-center justify-center cursor-pointer">
                  <i data-lucide="camera" class="w-3 h-3"></i>
                </span>
              </div>
              <div>
                <h4 class="text-xs font-bold text-primary">Educator Profile Picture</h4>
                <p class="text-[10px] text-primary/60 mt-0.5">Please provide a clear white-background headshot of the teacher. Max size: 2MB.</p>
              </div>
            </div>

            <!-- Full name -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Full Name</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($teacher['name'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Father name -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Father's Name</label>
              <input type="text" name="father_name" value="<?php echo htmlspecialchars($teacher['father_name'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Gender -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Gender</label>
              <select name="gender" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
                <option <?php echo (isset($teacher['gender']) && $teacher['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                <option <?php echo (isset($teacher['gender']) && $teacher['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
              </select>
            </div>
            <!-- DOB -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Date of Birth</label>
              <input type="date" name="dob" value="<?php echo htmlspecialchars($teacher['dob'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Marital Status -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Marital Status</label>
              <select name="marital_status" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
                <option <?php echo (isset($teacher['marital_status']) && $teacher['marital_status'] === 'Married') ? 'selected' : ''; ?>>Married</option>
                <option <?php echo (isset($teacher['marital_status']) && $teacher['marital_status'] === 'Unmarried') ? 'selected' : ''; ?>>Unmarried</option>
              </select>
            </div>
            <!-- Nationality -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Nationality</label>
              <input type="text" name="nationality" value="<?php echo htmlspecialchars($teacher['nationality'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Country -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Country</label>
              <input type="text" name="country" value="<?php echo htmlspecialchars($teacher['country'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- City -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">City</label>
              <input type="text" name="city" value="<?php echo htmlspecialchars($teacher['city'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Time Zone -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Time Zone</label>
              <input type="text" name="timezone" value="<?php echo htmlspecialchars($teacher['timezone'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
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
              <input type="tel" name="phone" value="<?php echo htmlspecialchars($teacher['phone'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- WhatsApp -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">WhatsApp Number</label>
              <input type="tel" name="whatsapp" value="<?php echo htmlspecialchars($teacher['whatsapp'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Email -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Email Address</label>
              <input type="email" name="email" value="<?php echo htmlspecialchars($teacher['email'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Address -->
            <div class="md:col-span-2">
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Address</label>
              <input type="text" name="address" value="<?php echo htmlspecialchars($teacher['address'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Emergency Contact -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Emergency Contact Details</label>
              <input type="text" name="emergency_contact" value="<?php echo htmlspecialchars($teacher['emergency_contact'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
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
              <input type="text" name="qualification" value="<?php echo htmlspecialchars($teacher['qualification'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Experience -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Experience (Years)</label>
              <input type="number" name="experience" value="<?php echo htmlspecialchars($teacher['experience'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Specialization -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Specialization</label>
              <input type="text" name="specialization" value="<?php echo htmlspecialchars($teacher['specialization'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Joining date -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Joining Date</label>
              <input type="date" name="joining_date" value="<?php echo htmlspecialchars($teacher['joining_date'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <!-- Status Dropdown -->
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Teacher Status</label>
              <select name="status" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-black text-primary outline-none focus:border-primary bg-white transition-all">
                <option <?php echo (isset($teacher['status']) && $teacher['status'] === 'Under Training') ? 'selected' : ''; ?>>Under Training</option>
                <option <?php echo (isset($teacher['status']) && $teacher['status'] === 'Probation') ? 'selected' : ''; ?>>Probation</option>
                <option <?php echo (isset($teacher['status']) && $teacher['status'] === 'Permanent') ? 'selected' : ''; ?>>Permanent</option>
                <option <?php echo (isset($teacher['status']) && $teacher['status'] === 'Senior Teacher') ? 'selected' : ''; ?>>Senior Teacher</option>
                <option <?php echo (isset($teacher['status']) && $teacher['status'] === 'Head Teacher') ? 'selected' : ''; ?>>Head Teacher</option>
                <option <?php echo (isset($teacher['status']) && $teacher['status'] === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                <option <?php echo (isset($teacher['status']) && $teacher['status'] === 'Resigned') ? 'selected' : ''; ?>>Resigned</option>
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
              <input type="email" name="portal_email" value="<?php echo htmlspecialchars($teacher['portal_email'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
            </div>
            <div>
              <label class="block text-[10px] font-bold text-primary/70 uppercase tracking-wider mb-1.5">Portal Password</label>
              <div class="relative">
                <input type="text" name="portal_password" value="<?php echo htmlspecialchars($teacher['portal_password'] ?? ''); ?>" class="w-full px-3 py-2 border border-primary/20 rounded-lg text-xs font-semibold text-primary outline-none focus:border-primary bg-white transition-all">
                <button type="button" onclick="document.querySelector('input[name=\'portal_password\']').value = Math.random().toString(36).slice(-8);" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary/10 hover:bg-primary/20 text-primary px-2 py-1 rounded text-[9px] font-bold uppercase tracking-wider transition-colors">Generate</button>
              </div>
            </div>
          </div>
          <p class="text-[10px] text-primary/60 mt-2"><i data-lucide="info" class="w-3 h-3 inline"></i> These credentials will be used by the teacher to log into their portal.</p>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-primary/10">
          <a href="teachers.php" class="px-5 py-2.5 border border-primary/20 text-primary rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-primary/5 transition-all">Cancel</a>
          <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-primary/95 shadow-md transition-all">Save Changes</button>
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


