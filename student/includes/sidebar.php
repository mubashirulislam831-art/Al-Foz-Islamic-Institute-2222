<?php
/**
 * Al Foz Islamic Institute - Student ERP Sidebar (Teacher Portal Design Clone)
 */
require_once __DIR__ . '/../../auth/session.php';

$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Student';
$student_name = $_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Student';
$current_page = $_SERVER['SCRIPT_NAME'] ?? ''; 

if (!function_exists('is_student_parent_active')) {
    function is_student_parent_active($folder, $current_page) {
        return strpos($current_page, $folder) !== false;
    }
}
?>
<aside class="erp-sidebar w-[260px] bg-white text-primary h-screen sticky top-0 flex flex-col border-r border-primary/10 shrink-0 shadow-[4px_0_24px_rgba(24,77,85,0.05)] transition-all duration-300">
  <!-- Logo/Branding Header -->
  <a href="/student/dashboard.php" class="p-6 border-b border-primary/10 flex items-center gap-3 bg-transparent group shrink-0">
    <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center shrink-0">
      <img src="/assets/logo.png" alt="Al Foz Logo" class="w-full h-full object-cover" referrerPolicy="no-referrer">
    </div>
    <div class="logo-text overflow-hidden transition-all duration-300 whitespace-nowrap">
      <h1 class="font-bold text-[14px] tracking-wide text-primary leading-tight">Al Foz</h1>
      <span class="text-[9px] font-bold uppercase tracking-widest block leading-tight mt-0.5" style="color: #184D55 !important; opacity: 0.8;">STUDENT PORTAL</span>
    </div>
  </a>

  <!-- User Profile Section -->
  <div class="p-6 border-b border-primary/10 flex items-center gap-4 bg-transparent shrink-0">
    <?php 
      if (function_exists('render_sidebar_profile_pic_html')) {
          echo render_sidebar_profile_pic_html();
      } else {
          $words = explode(' ', trim($student_name));
          $initials = strtoupper(substr($words[0] ?? 'S', 0, 1) . substr($words[1] ?? '', 0, 1));
          echo '<div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center font-bold text-sm shrink-0">' . htmlspecialchars($initials ?: 'ST') . '</div>';
      }
    ?>
    <div class="profile-text overflow-hidden transition-all duration-300 whitespace-nowrap">
      <h2 class="font-bold text-[10px] tracking-wide text-primary truncate"><?php echo htmlspecialchars($student_name); ?></h2>
      <span class="text-[9px] font-bold text-primary/60 uppercase tracking-widest block mt-0.5">STUDENT SCHOLAR</span>
    </div>
  </div>

  <!-- Dynamic Menu Items -->
  <nav class="py-4 flex flex-col gap-1 text-[10px] bg-transparent flex-1 overflow-y-auto">
    <span class="core-portal-label px-6 py-2 text-[10px] font-bold text-primary/45 uppercase tracking-widest block shrink-0">Student Portal</span>

    <a href="/student/dashboard.php" title="Dashboard" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/student/dashboard.php') ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Dashboard</span>
    </a>
    
    <a href="/student/profile.php" title="My Profile" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_student_parent_active('/student/profile', $current_page) ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="user-round" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Profile</span>
    </a>

    <a href="/student/attendance.php" title="My Attendance" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_student_parent_active('/student/attendance', $current_page) ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="calendar-check" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Attendance</span>
    </a>

    <a href="/student/schedule.php" title="My Schedule" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_student_parent_active('/student/schedule', $current_page) ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="clock" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Schedule</span>
    </a>

    <a href="/student/homework.php" title="My Homework" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_student_parent_active('/student/homework', $current_page) ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="book-open" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Homework</span>
    </a>

    <a href="/student/exams.php" title="Exams & Grades" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_student_parent_active('/student/exams', $current_page) ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="file-text" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Exams & Grades</span>
    </a>

    <a href="/student/reports.php" title="My Progress" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_student_parent_active('/student/reports', $current_page) ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="bar-chart-3" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Progress</span>
    </a>

    <a href="/student/fees.php" title="My Fees" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_student_parent_active('/student/fees', $current_page) ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="credit-card" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Fees</span>
    </a>

    <a href="/student/notifications.php" title="Announcements" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_student_parent_active('/student/notifications', $current_page) ? 'active-link' : 'font-medium'; ?>">
      <i data-lucide="bell" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Announcements</span>
    </a>

    

    <a href="/auth/logout.php" title="Logout" class="flex items-center gap-3 px-4 py-2.5 transition-all hover:bg-primary/5 font-medium">
      <i data-lucide="log-out" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Logout</span>
    </a>
  </nav>

  <!-- Sidebar Footer -->
  <div class="p-4 border-t border-primary/10 text-center bg-primary/5 shrink-0">
    <a href="/" class="text-[10px] font-bold text-primary/80 hover:text-primary uppercase tracking-wider block transition-all flex items-center justify-center gap-1.5">
      <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Website
    </a>
  </div>
</aside>
