<?php
/**
 * Al Foz Islamic Institute - Super Admin Student Transfer
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';
require_once __DIR__ . '/../../includes/teachers_data.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);

$id = isset($_GET['id']) ? intval($_GET['id']) : 101;
$student = get_student_by_id($id);



$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_course = sanitize_input($_POST['course']);
    $new_teacher = sanitize_input($_POST['teacher_name']);
    $reason = sanitize_input($_POST['reason']);
    
    // Update student details in Session
    $_SESSION['students'][$id]['course'] = $new_course;
    $_SESSION['students'][$id]['academic']['course'] = $new_course;
    $_SESSION['students'][$id]['teacher_name'] = $new_teacher;
    
    // Add timeline event
    $_SESSION['students'][$id]['timeline'][] = [
        'date' => date('Y-m-d'),
        'type' => 'Promotion History',
        'title' => 'Transferred to ' . $new_course,
        'desc' => 'Instructor assigned: ' . $new_teacher . '. Reason: ' . $reason
    ];
    
    $success = true;
    $student = get_student_by_id($id); // refresh data
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8 mt-4">
      <a href="student_profile.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Profile
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Transfer Course / Teacher</h1>
      <p class="text-xs text-primary/60 mt-0.5">Relocate academic course tracks or modify primary teacher assignment.</p>
    </div>

    <?php if ($success): ?>
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-4 rounded-xl mb-6 max-w-2xl">
        ✓ Student transfer completed successfully! Records updated and timeline event registered.
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm p-6 max-w-2xl">
      <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-extrabold text-sm">
          TS
        </div>
        <div>
          <h3 class="font-bold text-primary"><?php echo htmlspecialchars($student['name']); ?></h3>
          <p class="text-[10px] text-slate-500 font-mono">Current Course: <?php echo htmlspecialchars($student['course']); ?> | Teacher: <?php echo htmlspecialchars($student['teacher_name']); ?></p>
        </div>
      </div>

      <form action="student_transfer.php?id=<?php echo $id; ?>" method="POST" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">New Course Track *</label>
            <select name="course" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none bg-white" required>
                                <option value="">Select Target Course</option>
                                <option value="Qaida (For Beginners)">Qaida (For Beginners)</option>
                                <option value="Noorani Qaida">Noorani Qaida</option>
                                <option value="Nazra Quran Reading">Nazra Quran Reading</option>
                                <option value="Hifz-ul-Quran (Memorization)">Hifz-ul-Quran (Memorization)</option>
                                <option value="Revision (Muraja)">Revision (Muraja)</option>
                                <option value="Tajweed Rules">Tajweed Rules</option>
                                <option value="Quran Translation">Quran Translation</option>
                                <option value="Tafseer-ul-Quran">Tafseer-ul-Quran</option>
                                <option value="Arabic Language">Arabic Language</option>
                                <option value="Islamic Studies">Islamic Studies</option>
                                <option value="Duas & Sunnah">Duas & Sunnah</option>
                                <option value="Namaz Course">Namaz Course</option>
                                <option value="Basic Islam for Kids">Basic Islam for Kids</option>
                                <option value="Basic Islam for Adults">Basic Islam for Adults</option>
                                <option value="Hadith Studies">Hadith Studies</option>
                                <option value="Seerah Course">Seerah Course</option>
                                <option value="Fiqh Basics">Fiqh Basics</option>
                                <option value="Arabic Grammar (Nahw & Sarf)">Arabic Grammar (Nahw & Sarf)</option>
                                <option value="Ijazah Preparation">Ijazah Preparation</option>
                                <option value="Online School Tuition">Online School Tuition</option>
                                <option value="Spoken Arabic">Spoken Arabic</option>
                                <option value="Urdu Language">Urdu Language</option>
                                <option value="English Language">English Language</option>
                                <option value="Computer Basics">Computer Basics</option>
                                <option value="Other">Other</option>
                            </select>
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">New Assigned Teacher *</label>
            <select name="teacher_name" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none bg-white" required>
              <?php foreach(get_all_teachers() as $t): ?>
              <option value="<?php echo htmlspecialchars($t['name']); ?>" <?php echo $student['teacher_name'] === $t['name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($t['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="sm:col-span-2">
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">Reason for Transfer *</label>
            <textarea name="reason" placeholder="State reason (e.g. Completed basics, recommended for Hifz level by instructor)" rows="3" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
          <a href="student_profile.php?id=<?php echo $id; ?>" class="bg-slate-100 hover:bg-slate-200 text-primary px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Cancel</a>
          <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md hover:bg-opacity-95 transition-all">Authorize Transfer</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
