<?php
/**
 * Al Foz Islamic Institute - Teacher Students Registry
 */
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../auth/permissions.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/students_data.php';

// Strictly require Teacher, Admin, or Super Admin role
require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_name = $_SESSION['name'];

// Handle search and filtering
$students = array_filter(get_all_students(), function($s) use ($teacher_name) {
    return ($s['status'] ?? '') !== 'Deleted' && ($s['teacher_name'] ?? '') === $teacher_name;
});
$search_query = isset($_GET['search']) ? trim(sanitize_input($_GET['search'])) : '';
$course_filter = isset($_GET['course']) ? trim(sanitize_input($_GET['course'])) : '';

if ($search_query !== '') {
    $students = array_filter($students, function($student) use ($search_query) {
        return (stripos($student['name'], $search_query) !== false) || 
               (stripos($student['student_id'], $search_query) !== false);
    });
}

if ($course_filter !== '' && $course_filter !== 'All Courses') {
    $students = array_filter($students, function($student) use ($course_filter) {
        return $student['course'] === $course_filter;
    });
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 mt-4">
      <div>
        <h1 class="text-2xl font-extrabold text-primary">My Students Directory</h1>
        <p class="text-xs text-primary/60 mt-1">Manage seeker lifecycle and real-time academics.</p>
      </div>
      <div>
        <!-- Teacher actions can go here -->
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
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="bg-slate-50 border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
              <th class="p-4">Seeker & ID</th>
              <th class="p-4">Course & Teacher</th>
              <th class="p-4">Geo & Joining</th>
              <th class="p-4">Active Days</th>
              <th class="p-4">Tuition Fee</th>
              <th class="p-4">Status</th>
              <th class="p-4 text-center">Quick Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-primary/5 text-primary/80 bg-white">
            <?php if (empty($students)): ?>
              <tr>
                <td colspan="6" class="p-8 text-center text-primary/50 font-semibold">No students found matching filters.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($students as $student): 
                $pkr_converted = convert_to_pkr($student['monthly_fee'], $student['currency']);
                $initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $student['name'])));
                $avatar_bg = $student['gender'] === 'Male' ? 'bg-primary/10 text-primary' : 'bg-rose-100 text-rose-700';
              ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <!-- Seeker Info -->
                  <td class="p-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-xl <?php echo $avatar_bg; ?> flex items-center justify-center font-bold text-xs border border-primary/10 shadow-sm">
                        <?php echo htmlspecialchars($initials); ?>
                      </div>
                      <div>
                        <div class="font-bold text-xs text-primary"><?php echo htmlspecialchars($student['name']); ?></div>
                        <div class="text-[9px] text-slate-500 font-mono mt-0.5">ID: <?php echo htmlspecialchars($student['student_id']); ?></div>
                      </div>
                    </div>
                  </td>

                  <!-- Academic Info -->
                  <td class="p-4">
                    <div class="font-bold text-[10px] uppercase tracking-wider"><?php echo htmlspecialchars($student['course']); ?></div>
                    <div class="text-[10px] text-slate-500 mt-0.5"><?php echo htmlspecialchars($student['teacher_name']); ?></div>
                  </td>

                  <!-- Location Info -->
                  <td class="p-4">
                    <div class="font-semibold text-[10px] uppercase"><?php echo htmlspecialchars($student['country']); ?></div>
                    <div class="text-[9px] text-slate-400 mt-1"><?php echo htmlspecialchars($student['joining_date']); ?></div>
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
                    <?php if (strtoupper($student['status']) === 'TRIAL'): ?>
                        <span class="text-[10px] font-black text-amber-600 uppercase">Trial Node</span>
                    <?php elseif (strtoupper($student['status']) === 'DEACTIVATED'): ?>
                        <span class="text-[10px] font-black text-rose-600 uppercase">Deactivated</span>
                    <?php else: ?>
                        <div class="font-extrabold text-[10px] text-primary">
                          <?php echo htmlspecialchars($student['monthly_fee'] . ' ' . $student['currency']); ?>
                        </div>
                        <?php if ($student['currency'] !== 'PKR'): ?>
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
                        'Trial' => 'bg-amber-50 text-amber-700',
                        'On Leave' => 'bg-indigo-50 text-indigo-700',
                        'Deactivated' => 'bg-rose-50 text-rose-700',
                        'Completed' => 'bg-primary/10 text-primary'
                    ];
                    $status_color = $status_colors[$student['status']] ?? 'bg-slate-50 text-slate-700';
                    ?>
                    <span class="px-2.5 py-1 rounded-lg <?php echo $status_color; ?> text-[9px] font-black uppercase tracking-widest border border-current/10">
                        <?php echo htmlspecialchars($student['status']); ?>
                    </span>
                  </td>

                  <!-- Quick Actions -->
                  <td class="p-4">
                    <div class="flex flex-wrap items-center justify-center gap-2 max-w-[280px] mx-auto">
                      <a href="student_profile.php?id=<?php echo $student['id']; ?>" class="bg-primary/10 hover:bg-primary hover:text-white px-4 py-2 rounded-xl text-primary text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                        View Profile
                      </a>
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
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>
