<?php
/**
 * Al Foz Islamic Institute - Super Admin Students Students
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

// Handle Student Admission (Add / Edit) submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'admission') {
    // Standardize selected days
    $days_list = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    $post_days = $_POST['days'] ?? [];
    foreach ($days_list as $day) {
        $direct_enabled = isset($_POST[$day . '_enabled']) && ($_POST[$day . '_enabled'] === '1' || $_POST[$day . '_enabled'] === 'Yes' || $_POST[$day . '_enabled'] === 'on' || $_POST[$day . '_enabled'] === true || $_POST[$day . '_enabled'] === 1);
        $_POST[$day . '_enabled'] = $direct_enabled || in_array(ucfirst($day), $post_days) || in_array($day, $post_days);
        $_POST[$day . '_pkt'] = $_POST[$day . '_time'] ?? ''; // fallback
    }

    // Resolve teacher_name if teacher_id is provided
    $teacher_id = $_POST['teacher_id'] ?? '';
    $teacher_name = "Unassigned";
    if (!empty($teacher_id)) {
        require_once __DIR__ . '/../../includes/teachers_data.php';
        $matchedTeacher = get_teacher_by_id(intval($teacher_id));
        if ($matchedTeacher) {
            $teacher_name = $matchedTeacher['name'] ?? 'Unassigned';
        }
    }
    $_POST['teacher_name'] = $teacher_name;

    // Handle course
    $course_val = $_POST['course'] ?? '';
    $custom_course_val = $_POST['custom_course'] ?? '';
    if ($course_val === 'Other' && !empty($custom_course_val)) {
        $_POST['course'] = $custom_course_val;
    }

    $id = $_POST['id'] ?? '';
    if (!empty($id)) {
        update_student(intval($id), $_POST);
    } else {
        add_student($_POST);
    }

    header("Location: students.php");
    exit;
}

// Handle search and filtering
$students = array_filter(get_all_students(), function($s) {
    return ($s['status'] ?? '') !== 'Deleted';
});
$search_query = isset($_GET['search']) ? trim(sanitize_input($_GET['search'])) : '';
$course_filter = isset($_GET['course']) ? trim(sanitize_input($_GET['course'])) : '';

if ($search_query !== '') {
    $students = array_filter($students, function($student) use ($search_query) {
        return (stripos($student['name'], $search_query) !== false) || 
               (stripos($student['student_id'], $search_query) !== false) ||
               (stripos($student['parent_info']['father_name'], $search_query) !== false);
    });
}

if ($course_filter !== '' && $course_filter !== 'All Courses') {
    $students = array_filter($students, function($student) use ($course_filter) {
        return $student['course'] === $course_filter;
    });
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 mt-4">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">Student Management System</h1>
        <p class="text-xs text-primary/60 mt-1">Manage seeker lifecycles, global billing conversion, and real-time academics.</p>
      </div>
      <div>
        <a href="add_student.php" class="bg-primary hover:bg-opacity-95 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all active:scale-95 inline-block">
          + Enroll New Student
        </a>
      </div>
    </div>

    <!-- Filter Control Board -->
    <form method="GET" action="students.php" class="bg-white rounded-2xl p-4 border border-primary/10 shadow-sm mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
      <div class="w-full md:w-1/3">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by name, ID or father's name..." class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none">
      </div>
      <div class="flex flex-wrap gap-3 w-full md:w-auto justify-end">
        <select name="course" class="px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none bg-white">
                                <option value="">All Courses</option>
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
        <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-all hover:bg-opacity-95">
          Apply Filter
        </button>
        <?php if ($search_query !== '' || $course_filter !== ''): ?>
          <a href="students.php" class="bg-red-50 text-red-600 border border-red-200 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-red-100 transition-all flex items-center">
            Clear
          </a>
        <?php endif; ?>
      </div>
    </form>

    <!-- Students Grid & Table -->
    <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden mb-8">
      <div class="p-5 border-b border-primary/10 flex items-center justify-between bg-primary/5">
        <h2 class="text-sm font-extrabold text-primary uppercase tracking-wider">Seekers Directory</h2>
        <span class="bg-primary/10 text-primary font-bold text-xs px-3 py-1 rounded-full">Total: <?php echo count($students); ?> Seeker(s)</span>
      </div>
      <div class="overflow-x-auto min-h-[220px]">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-slate-50 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Seeker & ID</th>
              <th class="p-4">Course & Teacher</th>
              <th class="p-4">Geo & Joining</th>
              <th class="p-4">Active Days</th>
              <th class="p-4">Tuition Fee</th>
              <th class="p-4">Status</th>
              <th class="p-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80 bg-white">
            <?php if (empty($students)): ?>
              <tr>
                <td colspan="7" class="p-8 text-center text-primary/50 font-semibold">No students found matching filters.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($students as $student): 
                $pkr_converted = convert_to_pkr($student['monthly_fee'] ?? '0', $student['currency'] ?? $student['fee_currency'] ?? 'PKR');
                $initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $student['name'] ?? '')));
                $avatar_bg = ($student['gender'] ?? 'Male') === 'Male' ? 'bg-primary/10 text-primary' : 'bg-rose-100 text-rose-700';
              ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <!-- Seeker Info -->
                  <td class="p-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-xl <?php echo $avatar_bg; ?> flex items-center justify-center font-bold text-xs border border-primary/10 shadow-sm">
                        <?php echo htmlspecialchars($initials); ?>
                      </div>
                      <div>
                        <div class="font-bold text-xs text-primary"><?php echo htmlspecialchars($student['name'] ?? ''); ?></div>
                        <div class="text-[9px] text-slate-500 font-mono mt-0.5">ID: <?php echo htmlspecialchars($student['student_id'] ?? $student['id'] ?? ''); ?></div>
                      </div>
                    </div>
                  </td>

                  <!-- Academic Info -->
                  <td class="p-4">
                    <div class="font-bold text-[10px] uppercase tracking-wider"><?php echo htmlspecialchars($student['course'] ?? 'General'); ?></div>
                    <div class="text-[10px] text-slate-500 mt-0.5"><?php echo htmlspecialchars($student['teacher_name'] ?? 'Unassigned'); ?></div>
                  </td>

                  <!-- Location Info -->
                  <td class="p-4">
                    <div class="font-semibold text-[10px] uppercase"><?php echo htmlspecialchars($student['country'] ?? 'N/A'); ?></div>
                    <div class="text-[9px] text-slate-400 mt-1"><?php echo htmlspecialchars($student['joining_date'] ?? $student['enrollment_date'] ?? 'N/A'); ?></div>
                  </td>

                  <!-- Active Days -->
                  <td class="p-4">
                    <div class="flex gap-1 flex-wrap max-w-[120px]">
                      <?php 
                      $days_short = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                      $full_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                      foreach($days_short as $index => $ds):
                        $lower_day = strtolower($full_days[$index]);
                        $is_active = isset($student[$lower_day . '_enabled']) && $student[$lower_day . '_enabled'];
                      ?>
                      <span class="w-6 h-6 rounded-lg flex items-center justify-center text-[9px] font-black <?php echo $is_active ? 'bg-emerald-500 text-white shadow-sm' : 'bg-slate-100 text-slate-300'; ?> uppercase" title="<?php echo $full_days[$index]; ?>">
                        <?php echo $ds[0]; ?>
                      </span>
                      <?php endforeach; ?>
                    </div>
                  </td>

                  <!-- Tuition -->
                  <td class="p-4">
                    <?php if (strtoupper($student['status'] ?? '') === 'TRIAL'): ?>
                        <span class="text-[10px] font-black text-[#184D55] uppercase">Trial Node</span>
                    <?php elseif (strtoupper($student['status'] ?? '') === 'DEACTIVATED'): ?>
                        <span class="text-[10px] font-black text-rose-600 uppercase">Deactivated</span>
                    <?php else: ?>
                        <div class="font-extrabold text-[10px] text-primary">
                          <?php echo htmlspecialchars(($student['monthly_fee'] ?? '0') . ' ' . ($student['currency'] ?? $student['fee_currency'] ?? 'PKR')); ?>
                        </div>
                        <?php if (($student['currency'] ?? $student['fee_currency'] ?? 'PKR') !== 'PKR'): ?>
                          <div class="text-[9px] text-emerald-600 font-bold mt-1">
                            ≈ <?php echo number_format($pkr_converted, 0); ?> PKR
                          </div>
                        <?php endif; ?>
                    <?php endif; ?>
                  </td>

                  <!-- Status -->
                  <td class="p-4">
                    <?php 
                    $status_colors = [
                        'Active' => 'bg-emerald-50 text-emerald-700',
                        'Trial' => 'bg-[#184D55]/10 text-[#184D55]',
                        'On Leave' => 'bg-indigo-50 text-indigo-700',
                        'Deactivated' => 'bg-rose-50 text-rose-700',
                        'Completed' => 'bg-primary/10 text-primary'
                    ];
                    $status_color = $status_colors[$student['status'] ?? 'Active'] ?? 'bg-slate-50 text-slate-700';
                    ?>
                    <span class="px-2.5 py-1 rounded-lg <?php echo $status_color; ?> text-[9px] font-black uppercase tracking-widest border border-current/10">
                        <?php echo htmlspecialchars($student['status'] ?? 'Active'); ?>
                    </span>
                  </td>

                  <!-- Actions Control Board (Floating iOS Bar Menu) -->
                  <td class="p-4 text-right relative">
                    <div class="relative inline-block text-left">
                      <!-- iOS Style Trigger Button -->
                      <button onclick="toggleStudentMenu('<?php echo $student['id']; ?>', event)" class="w-10 h-10 inline-flex items-center justify-center bg-[#184D55] text-white rounded-2xl hover:bg-[#184D55]/90 transition-all cursor-pointer shadow-sm active:scale-95" title="Quick Profile Portals">
                        <i data-lucide="align-left" class="w-4 h-4"></i>
                      </button>

                      <!-- Floating iOS Bar Menu -->
                      <div id="student-menu-<?php echo $student['id']; ?>" class="student-portal-menu absolute right-full top-1/2 -translate-y-1/2 bg-[#184D55] text-white flex items-center gap-1.5 p-1.5 rounded-2xl shadow-2xl border border-white/10 opacity-0 scale-95 pointer-events-none transition-all duration-200 z-30 mr-2 whitespace-nowrap">
                        <!-- Profile -->
                        <a href="student_profile.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Profile">
                          <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Profile</span>
                        </a>
                        <!-- Edit -->
                        <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Edit">
                          <i data-lucide="edit-3" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Edit</span>
                        </a>
                        <!-- Teacher -->
                        <a href="student_teacher.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Teacher">
                          <i data-lucide="graduation-cap" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Teacher</span>
                        </a>
                        <!-- Schedule -->
                        <a href="student_schedule.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Schedule">
                          <i data-lucide="calendar" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Schedule</span>
                        </a>
                        <!-- Attendance -->
                        <a href="student_attendance.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Attendance">
                          <i data-lucide="calendar-check" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Attendance</span>
                        </a>
                        <!-- Fees -->
                        <a href="student_fees.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Fees">
                          <i data-lucide="wallet" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Fees</span>
                        </a>
                        <!-- Exams -->
                        <a href="student_exams.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Exams">
                          <i data-lucide="award" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Exams</span>
                        </a>
                        <!-- Reports -->
                        <a href="student_reports.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Reports">
                          <i data-lucide="line-chart" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Reports</span>
                        </a>
                        <!-- Documents -->
                        <a href="student_documents.php?id=<?php echo $student['id']; ?>" class="group relative w-8 h-8 flex items-center justify-center text-white bg-[#184D55] hover:bg-white/15 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Documents">
                          <i data-lucide="files" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-[#184D55] text-white text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Documents</span>
                        </a>
                        <!-- Delete -->
                        <a href="student_profile.php?id=<?php echo $student['id']; ?>#profile-section" onclick="window.location.href='student_profile.php?id=<?php echo $student['id']; ?>#delete-modal'; return false;" class="group relative w-8 h-8 flex items-center justify-center text-rose-300 bg-[#184D55] hover:bg-rose-600/20 border border-white/10 rounded-xl transition-all active:scale-95 shrink-0" title="Delete">
                          <i data-lucide="trash-2" class="w-4 h-4 shrink-0"></i>
                          <span class="absolute -top-9 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-all bg-rose-950 text-rose-200 text-[8px] font-bold px-1.5 py-0.5 rounded shadow-xl z-50 uppercase tracking-tighter whitespace-nowrap border border-white/10">Delete</span>
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }

  function toggleStudentMenu(studentId, event) {
    event.stopPropagation();
    const menuId = 'student-menu-' + studentId;
    const targetMenu = document.getElementById(menuId);
    if (!targetMenu) return;
    
    // Close all other menus first
    document.querySelectorAll('.student-portal-menu, .teacher-portal-menu').forEach(menu => {
      if (menu.id !== menuId) {
        menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        if (menu.parentElement) {
          menu.parentElement.classList.remove('z-50');
        }
      }
    });
    
    // Toggle the clicked menu
    const isClosed = targetMenu.classList.contains('opacity-0');
    if (isClosed) {
      targetMenu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
      if (targetMenu.parentElement) {
        targetMenu.parentElement.classList.add('z-50');
      }
    } else {
      targetMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
      if (targetMenu.parentElement) {
        targetMenu.parentElement.classList.remove('z-50');
      }
    }
  }

  // Close menus when clicking anywhere else on the document
  document.addEventListener('click', function(event) {
    if (event.target.closest('.student-portal-menu') || event.target.closest('.teacher-portal-menu')) return;
    document.querySelectorAll('.student-portal-menu, .teacher-portal-menu').forEach(menu => {
      menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
      if (menu.parentElement) {
        menu.parentElement.classList.remove('z-50');
      }
    });
  });
</script>
