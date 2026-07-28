<?php
/**
 * Al Foz Islamic Institute - Shared ERP Sidebar
 */
require_once __DIR__ . '/functions.php';

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
// Normalize role formats for safety
if (strtolower($role) === 'super_admin' || strtolower($role) === 'super admin') {
    $role = 'Super Admin';
} elseif (strtolower($role) === 'admin') {
    $role = 'Admin';
} elseif (strtolower($role) === 'teacher') {
    $role = 'Teacher';
} elseif (strtolower($role) === 'student') {
    $role = 'Student';
} elseif (strtolower($role) === 'parent') {
    $role = 'Parent';
}
?>
<script src="/assets/js/session-keepalive.js"></script>
<aside class="erp-sidebar w-[260px] bg-white text-primary h-screen sticky top-0 flex flex-col border-r border-primary/10 shrink-0 shadow-[4px_0_24px_rgba(24,77,85,0.05)] transition-all duration-300">
  <!-- Logo/Branding Header -->
  <a href="/" class="p-6 border-b border-primary/10 flex items-center gap-3 bg-transparent group shrink-0">
    <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center shrink-0">
      <img src="/assets/logo.png" alt="Al Foz Logo" class="w-full h-full object-cover" referrerPolicy="no-referrer">
    </div>
    <div class="logo-text overflow-hidden transition-all duration-300 whitespace-nowrap">
      <h1 class="font-bold text-[14px] tracking-wide text-primary leading-tight">Al Foz</h1>
      <span class="text-[9px] font-bold uppercase tracking-widest block leading-tight mt-0.5" style="color: #184D55 !important; opacity: 0.8;">MANAGEMENT SYSTEM</span>
    </div>
  </a>
  
  <!-- User Profile Section -->
  <div class="p-6 border-b border-primary/10 flex items-center gap-4 bg-transparent shrink-0">
    <?php echo render_sidebar_profile_pic_html(); ?>
    <div class="profile-text overflow-hidden transition-all duration-300 whitespace-nowrap">
      <h2 class="font-bold text-[10px] tracking-wide text-primary truncate"><?php echo htmlspecialchars($_SESSION['name'] ?? 'System User'); ?></h2>
      <span class="text-[9px] font-bold text-primary/60 uppercase tracking-widest block mt-0.5"><?php echo render_user_role_title_html(); ?></span>
    </div>
  </div>

  <!-- Dynamic Menu Items based on Role -->
  <nav class="py-4 flex flex-col gap-1 text-[10px] bg-transparent flex-1 overflow-y-auto">
    <span class="core-portal-label px-6 py-2 text-[10px] font-bold text-primary/45 uppercase tracking-widest block shrink-0">Core Portal</span>

      <?php 
      $current_page = $_SERVER['SCRIPT_NAME']; 
      
      // Helper function for dropdown parents
      function is_parent_active($folder, $current_page) {
          return strpos($current_page, $folder) !== false;
      }
      ?>

      <?php if ($role === 'Super Admin'): ?>
        <!-- Super Admin Menu Links -->
        <a href="/superadmin/dashboard.php" title="Overview" class="relative flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/superadmin/dashboard.php') ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Overview</span>
        </a>
        <a href="/superadmin/admins/admins.php" title="Admins" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/admins/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="shield" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Admins</span>
        </a>
        <a href="/superadmin/security/permissions.php" title="Permissions Matrix" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/superadmin/security/permissions.php') ? 'active-link' : ''; ?>">
          <i data-lucide="shield-check" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Permissions Matrix</span>
        </a>
        <a href="/superadmin/security/account_security.php" title="Account & Security" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo (is_parent_active('/superadmin/security/', $current_page) && $current_page !== '/superadmin/security/permissions.php') ? 'active-link' : ''; ?>">
          <i data-lucide="user-cog" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Account & Security</span>
        </a>
        <!-- Teachers Dropdown -->
        <div class="sidebar-dropdown">
          <button onclick="toggleSidebarDropdown('teachersDropdown', this)" title="Teachers" class="w-full flex items-center justify-between px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/teachers/', $current_page) ? 'active-link' : ''; ?>">
            <div class="flex items-center gap-3">
              <i data-lucide="users" class="w-4.5 h-4.5 shrink-0"></i> <span class="font-medium nav-text whitespace-nowrap">Teachers</span>
            </div>
            <i data-lucide="chevron-down" class="sidebar-dropdown-icon w-4 h-4 opacity-70 transition-transform duration-200 <?php echo is_parent_active('/superadmin/teachers/', $current_page) ? 'rotate-180' : ''; ?>"></i>
          </button>
          <div id="teachersDropdown" data-title="Teachers" class="sidebar-dropdown-content flex flex-col gap-1 pl-12 pr-2 py-1 overflow-hidden transition-all duration-300 <?php echo is_parent_active('/superadmin/teachers/', $current_page) ? 'open' : ''; ?>">
            <a href="/superadmin/teachers/teachers.php" title="View Registry" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/teachers/teachers.php') ? 'active-link' : ''; ?>">View Registry</a>
            <a href="/superadmin/teachers/add_teacher.php" title="Add Teacher" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/teachers/add_teacher.php') ? 'active-link' : ''; ?>">Add Teacher</a>
            <a href="/superadmin/teachers/teacher_schedule.php" title="Schedules" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/teachers/teacher_schedule.php') ? 'active-link' : ''; ?>">Schedules</a>
            <a href="/superadmin/teachers/teacher_reports.php" title="Reports" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/teachers/teacher_reports.php') ? 'active-link' : ''; ?>">Reports</a>
          </div>
        </div>

        <!-- Students Dropdown -->
        <div class="sidebar-dropdown">
          <button onclick="toggleSidebarDropdown('studentsDropdown', this)" title="Students" class="w-full flex items-center justify-between px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/students/', $current_page) ? 'active-link' : ''; ?>">
            <div class="flex items-center gap-3">
              <i data-lucide="graduation-cap" class="w-4.5 h-4.5 shrink-0"></i> <span class="font-medium nav-text whitespace-nowrap">Students</span>
            </div>
            <i data-lucide="chevron-down" class="sidebar-dropdown-icon w-4 h-4 opacity-70 transition-transform duration-200 <?php echo is_parent_active('/superadmin/students/', $current_page) ? 'rotate-180' : ''; ?>"></i>
          </button>
          <div id="studentsDropdown" data-title="Students" class="sidebar-dropdown-content flex flex-col gap-1 pl-12 pr-2 py-1 overflow-hidden transition-all duration-300 <?php echo is_parent_active('/superadmin/students/', $current_page) ? 'open' : ''; ?>">
            <a href="/superadmin/students/students.php" title="View Registry" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/students/students.php') ? 'active-link' : ''; ?>">View Registry</a>
            <a href="/superadmin/students/add_student.php" title="Enroll Student" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/students/add_student.php') ? 'active-link' : ''; ?>">Enroll Student</a>
            <a href="/superadmin/students/student_reports.php" title="Reports" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/students/student_reports.php') ? 'active-link' : ''; ?>">Reports</a>
            <a href="/superadmin/students/student_performance.php" title="Performance" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/students/student_performance.php') ? 'active-link' : ''; ?>">Performance</a>
          </div>
        </div>
        <a href="/superadmin/parents/parents.php" title="Parents" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/parents/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="user-round" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Parents</span>
        </a>
        
        <!-- Attendance Management Dropdown -->
        <div class="sidebar-dropdown">
          <button onclick="toggleSidebarDropdown('attendanceDropdown', this)" title="Attendance" class="w-full flex items-center justify-between px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/attendance/', $current_page) ? 'active-link' : ''; ?>">
            <div class="flex items-center gap-3">
              <i data-lucide="calendar-check" class="w-4.5 h-4.5 shrink-0"></i> <span class="font-medium nav-text whitespace-nowrap">Attendance</span>
            </div>
            <i data-lucide="chevron-down" class="sidebar-dropdown-icon w-4 h-4 opacity-70 transition-transform duration-200 <?php echo is_parent_active('/superadmin/attendance/', $current_page) ? 'rotate-180' : ''; ?>"></i>
          </button>
          <div id="attendanceDropdown" data-title="Attendance" class="sidebar-dropdown-content flex flex-col gap-1 pl-12 pr-2 py-1 overflow-hidden transition-all duration-300 <?php echo is_parent_active('/superadmin/attendance/', $current_page) ? 'open' : ''; ?>">
            <a href="/superadmin/attendance/dashboard.php" title="Dashboard" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/dashboard.php') ? 'active-link' : ''; ?>">Dashboard</a>
            <a href="/superadmin/attendance/today_attendance.php" title="Today's Attendance" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/today_attendance.php') ? 'active-link' : ''; ?>">Today's Attendance</a>
            <a href="/superadmin/attendance/monthly_attendance.php" title="Monthly Attendance" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/monthly_attendance.php') ? 'active-link' : ''; ?>">Monthly Attendance</a>
            <a href="/superadmin/attendance/teacher_attendance.php" title="Teacher Attendance" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/teacher_attendance.php') ? 'active-link' : ''; ?>">Teacher Attendance</a>
            <a href="/superadmin/attendance/student_attendance.php" title="Student Attendance" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/student_attendance.php') ? 'active-link' : ''; ?>">Student Attendance</a>
            <a href="/superadmin/attendance/makeup_classes.php" title="Makeup Classes" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/makeup_classes.php') ? 'active-link' : ''; ?>">Makeup Classes</a>
            <a href="/superadmin/attendance/leave_management.php" title="Leave Management" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/leave_management.php') ? 'active-link' : ''; ?>">Leave Management</a>
            <a href="/superadmin/attendance/attendance_analytics.php" title="Attendance Analytics" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/attendance_analytics.php') ? 'active-link' : ''; ?>">Attendance Analytics</a>
            <a href="/superadmin/attendance/attendance_reports.php" title="Attendance Reports" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/attendance_reports.php') ? 'active-link' : ''; ?>">Attendance Reports</a>
            <a href="/superadmin/attendance/attendance_timeline.php" title="Attendance Timeline" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/superadmin/attendance/attendance_timeline.php') ? 'active-link' : ''; ?>">Attendance Timeline</a>
            <a href="/superadmin/attendance/pdf/attendance_pdf.php" title="PDF" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo (strpos($current_page, '/pdf/') !== false) ? 'active-link' : ''; ?>">PDF</a>
            <a href="/superadmin/attendance/print/attendance_print.php" title="Print" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo (strpos($current_page, '/print/') !== false) ? 'active-link' : ''; ?>">Print</a>
          </div>
        </div>
        
        <a href="/superadmin/fees/fees.php" title="Financial ERP" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/fees/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="landmark" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Financial ERP</span>
        </a>
        <a href="/superadmin/salaries/salaries.php" title="Fee Manager" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/salaries/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="banknote" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Fee Manager</span>
        </a>

        <!-- Teacher Salary System -->
        <div class="sidebar-dropdown">
          <button onclick="toggleSidebarDropdown('teacherSalaryDropdown', this)" title="Teacher Salary" class="w-full flex items-center justify-between px-4 py-2.5 transition-all <?php echo is_parent_active('/salary/', $current_page) ? 'active-link' : ''; ?>">
            <div class="flex items-center gap-3">
              <i data-lucide="badge-dollar-sign" class="w-4.5 h-4.5 shrink-0"></i> <span class="font-medium nav-text whitespace-nowrap">Teacher Salary</span>
            </div>
            <i data-lucide="chevron-down" class="sidebar-dropdown-icon w-4 h-4 opacity-70 transition-transform duration-200 <?php echo is_parent_active('/salary/', $current_page) ? 'rotate-180' : ''; ?>"></i>
          </button>
          <div id="teacherSalaryDropdown" data-title="Teacher Salary" class="sidebar-dropdown-content flex flex-col gap-1 pl-12 pr-2 py-1 overflow-hidden transition-all duration-300 <?php echo is_parent_active('/salary/', $current_page) ? 'open' : ''; ?>">
            <a href="/salary/dashboard.php" title="Dashboard" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/dashboard.php') ? 'active-link' : ''; ?>">Dashboard</a>
            <a href="/salary/salary_setup.php" title="Salary Setup" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/salary_setup.php') ? 'active-link' : ''; ?>">Salary Setup</a>
            <a href="/salary/monthly_salary.php" title="Monthly Salary" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/monthly_salary.php') ? 'active-link' : ''; ?>">Monthly Salary</a>
            <a href="/salary/student_salary.php" title="Student Salary" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/student_salary.php') ? 'active-link' : ''; ?>">Student Salary</a>
            <a href="/salary/salary_bonus.php" title="Salary Bonus" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/salary_bonus.php') ? 'active-link' : ''; ?>">Salary Bonus</a>
            <a href="/salary/salary_deduction.php" title="Salary Deduction" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/salary_deduction.php') ? 'active-link' : ''; ?>">Salary Deduction</a>
            <a href="/salary/leave_deduction.php" title="Leave Deduction" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/leave_deduction.php') ? 'active-link' : ''; ?>">Leave Deduction</a>
            <a href="/salary/attendance_deduction.php" title="Attendance Deduction" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/attendance_deduction.php') ? 'active-link' : ''; ?>">Attendance Deduction</a>
            <a href="/salary/payment_history.php" title="Payment History" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/payment_history.php') ? 'active-link' : ''; ?>">Payment History</a>
            <a href="/salary/reports.php" title="Salary Reports" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/reports.php') ? 'active-link' : ''; ?>">Salary Reports</a>
            <a href="/salary/analytics.php" title="Salary Analytics" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/analytics.php') ? 'active-link' : ''; ?>">Salary Analytics</a>
            <a href="/salary/teacher_invoice.php" title="Invoice" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/salary/teacher_invoice.php') ? 'active-link' : ''; ?>">Invoice</a>
          </div>
        </div>
        
        <a href="/superadmin/homework/homework.php" title="Homework Tasks" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/homework/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="file-text" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Homework Tasks</span>
        </a>
        <a href="/superadmin/exams/exam_setup.php" title="Exams Control" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/exams/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="clipboard-list" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Exams Control</span>
        </a>
        <a href="/superadmin/reports/student_reports.php" title="Reports Hub" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/reports/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="bar-chart-3" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Reports Hub</span>
        </a>
        <a href="/superadmin/settings/academy_settings.php" title="Portal Settings" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/settings/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="settings" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Portal Settings</span>
        </a>
        <a href="/superadmin/google_sheets_sync.php" title="Google Sheets Sync" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/superadmin/google_sheets_sync.php') ? 'active-link' : ''; ?>">
          <i data-lucide="file-spreadsheet" class="w-4.5 h-4.5 shrink-0 text-emerald-600"></i> <span class="nav-text whitespace-nowrap">Sheets Sync</span>
        </a>
        <a href="/superadmin/system_map.php" title="System Map" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/superadmin/system_map.php', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="folder-tree" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">System Map</span>
        </a>

        

      <?php elseif ($role === 'Admin'): ?>
        <!-- Admin Menu Links -->
        <a href="/admin/dashboard.php" title="Overview" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/admin/dashboard.php') ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Overview</span>
        </a>
        <!-- Admin Students Dropdown -->
        <div class="sidebar-dropdown">
          <button onclick="toggleSidebarDropdown('adminStudentsDropdown', this)" title="Students" class="w-full flex items-center justify-between px-4 py-2.5 transition-all <?php echo is_parent_active('/admin/students/', $current_page) ? 'active-link' : ''; ?>">
            <div class="flex items-center gap-3">
              <i data-lucide="graduation-cap" class="w-4.5 h-4.5 shrink-0"></i> <span class="font-medium nav-text whitespace-nowrap">Students</span>
            </div>
            <i data-lucide="chevron-down" class="sidebar-dropdown-icon w-4 h-4 opacity-70 transition-transform duration-200 <?php echo is_parent_active('/admin/students/', $current_page) ? 'rotate-180' : ''; ?>"></i>
          </button>
          <div id="adminStudentsDropdown" data-title="Students" class="sidebar-dropdown-content flex flex-col gap-1 pl-12 pr-2 py-1 overflow-hidden transition-all duration-300 <?php echo is_parent_active('/admin/students/', $current_page) ? 'open' : ''; ?>">
            <a href="/admin/students/students.php" title="View Registry" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/admin/students/students.php') ? 'active-link' : ''; ?>">View Registry</a>
            <a href="/admin/students/add_student.php" title="Enroll Student" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/admin/students/add_student.php') ? 'active-link' : ''; ?>">Enroll Student</a>
            <a href="/admin/students/student_reports.php" title="Reports" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/admin/students/student_reports.php') ? 'active-link' : ''; ?>">Reports</a>
            <a href="/admin/students/student_performance.php" title="Performance" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/admin/students/student_performance.php') ? 'active-link' : ''; ?>">Performance</a>
          </div>
        </div>

        <!-- Admin Teachers Dropdown -->
        <div class="sidebar-dropdown">
          <button onclick="toggleSidebarDropdown('adminTeachersDropdown', this)" title="Teachers" class="w-full flex items-center justify-between px-4 py-2.5 transition-all <?php echo is_parent_active('/admin/teachers/', $current_page) ? 'active-link' : ''; ?>">
            <div class="flex items-center gap-3">
              <i data-lucide="users" class="w-4.5 h-4.5 shrink-0"></i> <span class="font-medium nav-text whitespace-nowrap">Teachers</span>
            </div>
            <i data-lucide="chevron-down" class="sidebar-dropdown-icon w-4 h-4 opacity-70 transition-transform duration-200 <?php echo is_parent_active('/admin/teachers/', $current_page) ? 'rotate-180' : ''; ?>"></i>
          </button>
          <div id="adminTeachersDropdown" data-title="Teachers" class="sidebar-dropdown-content flex flex-col gap-1 pl-12 pr-2 py-1 overflow-hidden transition-all duration-300 <?php echo is_parent_active('/admin/teachers/', $current_page) ? 'open' : ''; ?>">
            <a href="/admin/teachers/teachers.php" title="View Registry" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/admin/teachers/teachers.php') ? 'active-link' : ''; ?>">View Registry</a>
            <a href="/admin/teachers/add_teacher.php" title="Add Teacher" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/admin/teachers/add_teacher.php') ? 'active-link' : ''; ?>">Add Teacher</a>
            <a href="/admin/teachers/teacher_schedule.php" title="Schedules" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/admin/teachers/teacher_schedule.php') ? 'active-link' : ''; ?>">Schedules</a>
            <a href="/admin/teachers/teacher_reports.php" title="Reports" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/admin/teachers/teacher_reports.php') ? 'active-link' : ''; ?>">Reports</a>
          </div>
        </div>
        <a href="/admin/attendance/attendance.php" title="Attendance" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/admin/attendance/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="calendar-check" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Attendance</span>
        </a>
        <a href="/admin/fees/fees.php" title="Fees Management" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/admin/fees/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="wallet" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Fees Management</span>
        </a>
        <a href="/admin/exams/exam_setup.php" title="Exams Control" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/admin/exams/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="file-text" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Exams Control</span>
        </a>
        <a href="/admin/reports/student_reports.php" title="Reports Hub" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/admin/reports/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="bar-chart-3" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Reports Hub</span>
        </a>
        <a href="/admin/google_sheets_sync.php" title="Google Sheets Sync" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/admin/google_sheets_sync.php') ? 'active-link' : ''; ?>">
          <i data-lucide="file-spreadsheet" class="w-4.5 h-4.5 shrink-0 text-emerald-600"></i> <span class="nav-text whitespace-nowrap">Sheets Sync</span>
        </a>
        
        

      <?php elseif ($role === 'Teacher'): ?>
        <!-- Teacher Menu Links -->
        <a href="/teacher/dashboard.php" title="Overview" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/teacher/dashboard.php') ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Overview</span>
        </a>
        <a href="/teacher/profile.php" title="My Profile" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/teacher/profile.php' || is_parent_active('/teacher/profile.php', $current_page)) ? 'active-link' : ''; ?>">
          <i data-lucide="user-round" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Profile</span>
        </a>
        <a href="/teacher/students/students.php" title="My Students" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/teacher/students/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="graduation-cap" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Students</span>
        </a>
        <button onclick="toggleSidebarDropdown('teacherAttendanceDropdown', this)" title="Attendance" class="w-full flex items-center justify-between px-4 py-2.5 transition-all <?php echo is_parent_active('/teacher/attendance/', $current_page) ? 'active-link' : ''; ?>">
          <div class="flex items-center gap-3">
            <i data-lucide="calendar-check" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Attendance</span>
          </div>
          <i data-lucide="chevron-down" class="sidebar-dropdown-icon w-4 h-4 opacity-70 transition-transform duration-200 <?php echo is_parent_active('/teacher/attendance/', $current_page) ? 'rotate-180' : ''; ?>"></i>
        </button>
        <div id="teacherAttendanceDropdown" data-title="Attendance" class="sidebar-dropdown-content flex flex-col gap-1 pl-12 pr-2 py-1 overflow-hidden transition-all duration-300 <?php echo is_parent_active('/teacher/attendance/', $current_page) ? 'open' : ''; ?>">
          <a href="/teacher/attendance/today_attendance.php" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/teacher/attendance/today_attendance.php') ? 'active-link' : ''; ?>">Today's Classes</a>
          <a href="/teacher/attendance/monthly_attendance.php" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/teacher/attendance/monthly_attendance.php') ? 'active-link' : ''; ?>">Student Attendance</a>
          <a href="/teacher/attendance/leave_request.php" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/teacher/attendance/leave_request.php') ? 'active-link' : ''; ?>">Leave Request</a>
          <a href="/teacher/attendance/makeup_classes.php" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/teacher/attendance/makeup_classes.php') ? 'active-link' : ''; ?>">Makeup Classes</a>
          <a href="/teacher/attendance/my_attendance.php" class="py-2 px-3 text-[11px] transition-all duration-200 <?php echo ($current_page === '/teacher/attendance/my_attendance.php') ? 'active-link' : ''; ?>">My Attendance</a>
        </div>
          <i data-lucide="calendar-check" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Roll Call</span>
        </a>
        <a href="/teacher/homework/homework.php" title="Homework Tasks" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/teacher/homework/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="file-text" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Homework Tasks</span>
        </a>
        <a href="/teacher/exams/student_exams.php" title="Exams & Grading" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/teacher/exams/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="file-text" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Exams & Grading</span>
        </a>
        <a href="/teacher/salary/salary.php" title="Pay Statements" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/teacher/salary/', $current_page) ? 'active-link' : ''; ?>">
          <i data-lucide="credit-card" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Pay Statements</span>
        </a>
        
        

      <?php elseif ($role === 'Student'): ?>
        <!-- Student Professional Navigation Menu -->
        <a href="/student/dashboard.php" title="Dashboard" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo ($current_page === '/student/dashboard.php') ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Dashboard</span>
        </a>
        <a href="/student/profile.php" title="My Profile" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo is_parent_active('/student/profile', $current_page) ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="user-round" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Profile</span>
        </a>
        <a href="/student/profile.php#teacher" title="My Teacher" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 hover:bg-primary/5 text-primary/80 font-medium">
          <i data-lucide="graduation-cap" class="w-4.5 h-4.5 shrink-0 text-emerald-600"></i> <span class="nav-text whitespace-nowrap">My Teacher</span>
        </a>
        <a href="/student/schedule.php" title="My Schedule" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo is_parent_active('/student/schedule', $current_page) ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="calendar" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">My Schedule</span>
        </a>
        
        <a href="/student/attendance.php" title="Attendance" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo is_parent_active('/student/attendance', $current_page) ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="calendar-check-2" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Attendance</span>
        </a>
        <a href="/student/homework.php" title="Homework" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo is_parent_active('/student/homework', $current_page) ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="book-open-check" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Homework</span>
        </a>
        
        <a href="/student/reports.php" title="Progress" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo is_parent_active('/student/reports', $current_page) ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="line-chart" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Progress</span>
        </a>
        <a href="/student/exams.php" title="Exams" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo is_parent_active('/student/exams', $current_page) ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="award" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Exams</span>
        </a>
        <a href="/student/fees.php" title="Fees" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo is_parent_active('/student/fees', $current_page) ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="credit-card" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Fees</span>
        </a>
        <a href="/student/profile.php#documents" title="Documents" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 hover:bg-primary/5 text-primary/80 font-medium">
          <i data-lucide="folder" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Documents</span>
        </a>
        <a href="/student/exams.php#certificates" title="Certificates" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 hover:bg-primary/5 text-primary/80 font-medium">
          <i data-lucide="medal" class="w-4.5 h-4.5 shrink-0 text-amber-500"></i> <span class="nav-text whitespace-nowrap">Certificates</span>
        </a>
        <a href="/student/notifications.php" title="Notifications" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 <?php echo is_parent_active('/student/notifications', $current_page) ? 'bg-primary text-white font-bold shadow-sm' : 'hover:bg-primary/5 text-primary/80 font-medium'; ?>">
          <i data-lucide="bell" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Notifications</span>
        </a>
        <a href="/student/profile.php#settings" title="Settings" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-0.5 hover:bg-primary/5 text-primary/80 font-medium">
          <i data-lucide="settings" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Settings</span>
        </a>

        

        <a href="/auth/logout.php" title="Logout" class="flex items-center gap-3 px-4 py-2.5 transition-all rounded-xl mx-2 my-2 hover:bg-rose-50 text-rose-600 font-bold">
          <i data-lucide="log-out" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Logout</span>
        </a>

      <?php elseif ($role === 'Parent'): ?>
        <!-- Parent Menu Links -->
        <a href="/parent/dashboard.php" title="Overview" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo ($current_page === '/parent/dashboard.php') ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Overview</span>
        </a>
        <a href="/parent/profile.php" title="Ward Profile" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/parent/profile', $current_page) ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="user-round" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Ward Profile</span>
        </a>
        <a href="/parent/attendance.php" title="Ward Attendance" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/parent/attendance', $current_page) ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="calendar-check" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Ward Attendance</span>
        </a>
        <a href="/parent/homework.php" title="Assignments Tracker" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/parent/homework', $current_page) ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="book-open" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Assignments Tracker</span>
        </a>
        <a href="/parent/fees.php" title="Fee Invoices" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/parent/fees', $current_page) ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="wallet" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Fee Invoices</span>
        </a>
        <a href="/parent/exams.php" title="Progress Card" class="flex items-center gap-3 px-4 py-2.5 transition-all <?php echo is_parent_active('/parent/exams', $current_page) ? 'active-link' : 'font-medium'; ?>">
          <i data-lucide="award" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Progress Card</span>
        </a>

        
        <a href="/auth/logout.php" title="Logout" class="flex items-center gap-3 px-4 py-2.5 transition-all hover:bg-primary/5 font-medium text-rose-600">
          <i data-lucide="log-out" class="w-4.5 h-4.5 shrink-0"></i> <span class="nav-text whitespace-nowrap">Logout</span>
        </a>
      <?php endif; ?>
    </nav>

  <!-- Sidebar Footer -->
  <div class="p-4 border-t border-primary/10 text-center bg-primary/5 shrink-0">
    <a href="/" class="text-[10px] font-bold text-primary/80 hover:text-primary uppercase tracking-wider block transition-all flex items-center justify-center gap-1.5">
      <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Website
    </a>
  </div>
</aside>

<script>
function toggleSidebarDropdown(dropdownId, buttonEl) {
  var dropdown = document.getElementById(dropdownId);
  if (dropdown) {
    dropdown.classList.toggle('open');
  }
  if (buttonEl) {
    var icon = buttonEl.querySelector('.sidebar-dropdown-icon') || buttonEl.querySelector('[data-lucide="chevron-down"]');
    if (icon) {
      icon.classList.toggle('rotate-180');
    }
  }
}
</script>



