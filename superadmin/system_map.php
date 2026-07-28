<?php
/**
 * Al Foz Islamic Institute - Super Admin ERP System File Explorer
 * A state-of-the-art directory map of all project folders and PHP files.
 */
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/permissions.php';
require_once __DIR__ . '/../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

// All PHP files structured logically into system modules
$modules = [
    [
        'id' => 'auth',
        'name' => 'Authentication & Access',
        'icon' => 'key',
        'description' => 'Security gateways, login portals, user verification, and active session managers.',
        'files' => [
            [
                'name' => 'login.php',
                'path' => '/auth/login.php',
                'desc' => 'Primary root redirector, routes incoming requests to authenticated dashboards.',
                'css' => ['Tailwind CSS', 'global.css', 'login.css'],
                'js' => ['app.js', 'validation.js'],
                'scope' => 'Public/Guest'
            ],
            [
                'name' => 'auth/auth/login.php',
                'path' => '/auth/login.php',
                'desc' => 'Premium multi-role responsive login portal with custom card design and input verification.',
                'css' => ['Tailwind CSS', 'global.css', 'login.css'],
                'js' => ['app.js', 'validation.js'],
                'scope' => 'Public/Guest'
            ],
            [
                'name' => 'auth/auth/logout.php',
                'path' => '/auth/logout.php',
                'desc' => 'Gracefully terminates active sessions and redirects user to secure login interface.',
                'css' => ['Standard Redirect'],
                'js' => ['Session Clearer'],
                'scope' => 'Authenticated'
            ],
            [
                'name' => 'auth/session.php',
                'path' => '/auth/session.php',
                'desc' => 'Initializes secure PHP sessions and manages login lifetime cookies.',
                'css' => ['System Engine'],
                'js' => ['Session Guard'],
                'scope' => 'Core'
            ],
            [
                'name' => 'auth/permissions.php',
                'path' => '/auth/permissions.php',
                'desc' => 'Restricts specific views and dashboard menus strictly to authorized user roles.',
                'css' => ['System Engine'],
                'js' => ['Role Guard'],
                'scope' => 'Core'
            ]
        ]
    ],
    [
        'id' => 'dashboards',
        'name' => 'Role Dashboards',
        'icon' => 'layout',
        'description' => 'Multi-portal homepages customized for Super Admins, Admins, Scholars, Students, and Parents.',
        'files' => [
            [
                'name' => 'superadmin/dashboard.php',
                'path' => '/superadmin/dashboard.php',
                'desc' => 'Super Admin console featuring 12 statistics metrics, active trials registry, and visual cards.',
                'css' => ['Tailwind CSS', 'global.css', 'dashboard.css', 'sidebar.css'],
                'js' => ['app.js', 'sidebar.js', 'navigation.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'admin/dashboard.php',
                'path' => '/admin/dashboard.php',
                'desc' => 'Admin dashboard showing key operation summaries, schedule coordinators, and advisor channels.',
                'css' => ['Tailwind CSS', 'global.css', 'dashboard.css', 'sidebar.css'],
                'js' => ['app.js', 'sidebar.js', 'navigation.js'],
                'scope' => 'Admin'
            ],
            [
                'name' => 'teacher/dashboard.php',
                'path' => '/teacher/dashboard.php',
                'desc' => 'Scholar terminal for lesson planning, tracking syllabus progression, and managing classes.',
                'css' => ['Tailwind CSS', 'global.css', 'dashboard.css', 'sidebar.css'],
                'js' => ['app.js', 'sidebar.js', 'navigation.js'],
                'scope' => 'Teacher'
            ],
            [
                'name' => 'student/dashboard.php',
                'path' => '/student/dashboard.php',
                'desc' => 'Seeker interface with daily lessons progression log, schedule boards, and class launching links.',
                'css' => ['Tailwind CSS', 'global.css', 'dashboard.css', 'sidebar.css'],
                'js' => ['app.js', 'sidebar.js', 'navigation.js'],
                'scope' => 'Student'
            ],
            [
                'name' => 'parent/dashboard.php',
                'path' => '/parent/dashboard.php',
                'desc' => 'Parent auditing console to view student daily mistakes, fee metrics, and connect with faculty.',
                'css' => ['Tailwind CSS', 'global.css', 'dashboard.css', 'sidebar.css'],
                'js' => ['app.js', 'sidebar.js', 'navigation.js'],
                'scope' => 'Parent'
            ]
        ]
    ],
    [
        'id' => 'students_module',
        'name' => 'Students Module',
        'icon' => 'graduation-cap',
        'description' => 'Enrollment forms, dossier editors, seeker registries, history timelines, and incident trackers.',
        'files' => [
            [
                'name' => 'superadmin/students/students.php',
                'path' => '/superadmin/students/students.php',
                'desc' => 'Searchable student registration index with course and billing filters.',
                'css' => ['Tailwind CSS', 'global.css', 'tables.css', 'students.css'],
                'js' => ['app.js', 'sidebar.js', 'students.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/students/add_student.php',
                'path' => '/superadmin/students/add_student.php',
                'desc' => 'Interactive student enrollment form, configures academic courses and guardian links.',
                'css' => ['Tailwind CSS', 'global.css', 'forms.css', 'add_student.css'],
                'js' => ['app.js', 'validation.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/students/edit_student.php',
                'path' => '/superadmin/students/edit_student.php',
                'desc' => 'Student detail updater, adjusts status, class levels, and billing currencies.',
                'css' => ['Tailwind CSS', 'global.css', 'forms.css', 'edit_student.css'],
                'js' => ['app.js', 'validation.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/students/student_profile.php',
                'path' => '/superadmin/students/student_profile.php',
                'desc' => 'Comprehensive dynamic student profile dashboard including schedule and guardian modules.',
                'css' => ['Tailwind CSS', 'global.css', 'student_profile.css'],
                'js' => ['app.js', 'navigation.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/students/student_timeline.php',
                'path' => '/superadmin/students/student_timeline.php',
                'desc' => 'Chronological syllabus progression logs and lesson history.',
                'css' => ['Tailwind CSS', 'global.css'],
                'js' => ['app.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/students/student_notes.php',
                'path' => '/superadmin/students/student_notes.php',
                'desc' => 'Special instruction ledger for scholars to document behavioral or lesson details.',
                'css' => ['Tailwind CSS', 'global.css', 'forms.css'],
                'js' => ['app.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/students/student_issue.php',
                'path' => '/superadmin/students/student_issue.php',
                'desc' => 'Trouble ticketing interface to document internet connection errors or class quality logs.',
                'css' => ['Tailwind CSS', 'global.css', 'forms.css'],
                'js' => ['app.js'],
                'scope' => 'Super Admin'
            ]
        ]
    ],
    [
        'id' => 'teachers_module',
        'name' => 'Scholars & Faculty',
        'icon' => 'users',
        'description' => 'Vetted educators registry, credentials dossiers, salary structures, and teacher-seeker links.',
        'files' => [
            [
                'name' => 'superadmin/teachers/teachers.php',
                'path' => '/superadmin/teachers/teachers.php',
                'desc' => 'Educators directory tracking designatory ranks, contact emails, and active classes.',
                'css' => ['Tailwind CSS', 'global.css', 'tables.css'],
                'js' => ['app.js', 'sidebar.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/teachers/add_teacher.php',
                'path' => '/superadmin/teachers/add_teacher.php',
                'desc' => 'New scholar registration form detailing specialized teaching domains (Tajweed, Tafseer, Hifz).',
                'css' => ['Tailwind CSS', 'global.css', 'forms.css'],
                'js' => ['app.js', 'validation.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/teachers/teacher_profile.php',
                'path' => '/superadmin/teachers/teacher_profile.php',
                'desc' => 'Educator record card including personal, professional, and operational assignments.',
                'css' => ['Tailwind CSS', 'global.css'],
                'js' => ['app.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/teachers/teacher_reports.php',
                'path' => '/superadmin/teachers/teacher_reports.php',
                'desc' => 'Evaluates tutor instruction quality, feedback metrics, and monthly session attendance graphs.',
                'css' => ['Tailwind CSS', 'global.css', 'dashboard.css'],
                'js' => ['app.js', 'navigation.js'],
                'scope' => 'Super Admin'
            ]
        ]
    ],
    [
        'id' => 'billing_module',
        'name' => 'Fees & Salaries Ledger',
        'icon' => 'wallet',
        'description' => 'Multi-currency invoicing, international payment reconciliations, ledger tracking, and faculty payrolls.',
        'files' => [
            [
                'name' => 'superadmin/fees/fees.php',
                'path' => '/superadmin/fees/fees.php',
                'desc' => 'Fee dashboard detailing real-time collection metrics, unpaid indices, and invoice states.',
                'css' => ['Tailwind CSS', 'global.css', 'tables.css'],
                'js' => ['app.js', 'sidebar.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/fees/currency_system.php',
                'path' => '/superadmin/fees/currency_system.php',
                'desc' => 'Global conversion engines mapping USD/GBP/EUR invoices straight to standard PKR ledger values.',
                'css' => ['Tailwind CSS', 'global.css', 'forms.css'],
                'js' => ['app.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/salaries/salaries.php',
                'path' => '/superadmin/salaries/salaries.php',
                'desc' => 'Payroll control interface generating scholar paychecks based on active instructional hours.',
                'css' => ['Tailwind CSS', 'global.css', 'tables.css'],
                'js' => ['app.js', 'sidebar.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/salaries/add_salary.php',
                'path' => '/superadmin/salaries/add_salary.php',
                'desc' => 'Salary disbursement form logging payment channels (Bank, EasyPaisa, JazzCash) and receipt files.',
                'css' => ['Tailwind CSS', 'global.css', 'forms.css'],
                'js' => ['app.js', 'validation.js'],
                'scope' => 'Super Admin'
            ]
        ]
    ],
    [
        'id' => 'academics_module',
        'name' => 'Exams & Attendance Control',
        'icon' => 'calendar-check',
        'description' => 'Daily roll call, makeup lecture planners, oral/written testing setup, results, and certificates.',
        'files' => [
            [
                'name' => 'superadmin/attendance/attendance.php',
                'path' => '/superadmin/attendance/attendance.php',
                'desc' => 'Interactive attendance hub showing today\'s ratios, excused logs, and makeup classes.',
                'css' => ['Tailwind CSS', 'global.css', 'tables.css'],
                'js' => ['app.js', 'sidebar.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/exams/exam_setup.php',
                'path' => '/superadmin/exams/exam_setup.php',
                'desc' => 'Test planner configures examination dates, marking criteria, and grading scales.',
                'css' => ['Tailwind CSS', 'global.css', 'forms.css'],
                'js' => ['app.js', 'validation.js'],
                'scope' => 'Super Admin'
            ],
            [
                'name' => 'superadmin/exams/oral_exams.php',
                'path' => '/superadmin/exams/oral_exams.php',
                'desc' => 'Evaluation grid for oral recitation testing, tracking pronunciation (Makhraj) and application of rules (Tajweed).',
                'css' => ['Tailwind CSS', 'global.css', 'tables.css'],
                'js' => ['app.js'],
                'scope' => 'Super Admin'
            ]
        ]
    ]
];

// Flat list of all files for stats and quick search
$all_files_count = 0;
foreach ($modules as $mod) {
    $all_files_count += count($mod['files']);
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Header Section (Premium Card) -->
    <div class="mb-8 bg-transparent islamic-texture rounded-[24px] p-6 sm:p-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden border border-primary/10">
      <div class="absolute -right-10 -top-10 w-60 h-60 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
      <div class="absolute -left-10 -bottom-10 w-48 h-48 rounded-full bg-primary/5 blur-2xl pointer-events-none"></div>
      
      <div class="flex items-center gap-5 relative z-10 w-full md:w-auto">
        <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center border border-primary/20 shadow-inner shrink-0">
          <i data-lucide="folder-tree" class="w-8 h-8 text-primary"></i>
        </div>
        <div>
          <h1 class="text-xl sm:text-2xl font-black text-primary tracking-tight">System Files & Modules Explorer</h1>
          <p class="text-xs text-primary/75 mt-1">Real-time directory sitemap tracking PHP file architectures, connected styles, and portal permissions.</p>
        </div>
      </div>
      
      <div class="relative z-10 flex items-center gap-3 w-full md:w-auto justify-end">
        <div class="bg-white border border-primary/15 rounded-xl px-4 py-2 text-xs font-bold text-primary shadow-sm flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
          <span><?php echo $all_files_count; ?> PHP Files Cataloged</span>
        </div>
      </div>
    </div>

    <!-- Search and Categorization Controls -->
    <div class="bg-white rounded-2xl p-4 border border-primary/10 shadow-sm mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
      <div class="w-full md:w-1/3 relative">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary/45">
          <i data-lucide="search" class="w-4 h-4"></i>
        </span>
        <input type="text" id="search-input" placeholder="Search by file name, keyword or role..." class="w-full pl-9 pr-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none placeholder-primary/40 font-medium">
      </div>
      <div class="flex gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 scrollbar-none">
        <button onclick="filterCategory('all')" class="category-btn active px-4 py-2 bg-primary text-secondary rounded-xl text-xs font-bold shadow-sm transition-all whitespace-nowrap">
          All Modules
        </button>
        <?php foreach ($modules as $mod): ?>
          <button onclick="filterCategory('<?php echo $mod['id']; ?>')" class="category-btn px-4 py-2 bg-primary/5 hover:bg-primary/10 text-primary rounded-xl text-xs font-bold transition-all whitespace-nowrap">
            <?php echo $mod['name']; ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Modules Container -->
    <div class="space-y-12">
      <?php foreach ($modules as $mod): ?>
        <div id="mod-<?php echo $mod['id']; ?>" class="module-group transition-all duration-300">
          <div class="flex items-center gap-3 mb-4">
            <span class="p-2 bg-primary/5 text-primary rounded-lg border border-primary/10">
              <i data-lucide="<?php echo $mod['icon']; ?>" class="w-5 h-5"></i>
            </span>
            <div>
              <h2 class="text-base font-bold text-primary tracking-tight"><?php echo $mod['name']; ?></h2>
              <p class="text-[11px] text-primary/60"><?php echo $mod['description']; ?></p>
            </div>
          </div>
          
          <!-- Files Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($mod['files'] as $file): ?>
              <div class="file-card bg-white rounded-2xl p-5 border border-primary/10 hover:border-primary/30 hover:shadow-md transition-all duration-300 flex flex-col justify-between" data-search-name="<?php echo strtolower($file['name'] . ' ' . $file['desc'] . ' ' . $file['scope']); ?>">
                <div>
                  <div class="flex justify-between items-start gap-2 mb-3">
                    <div class="flex items-center gap-1.5 min-w-0">
                      <i data-lucide="file-code-2" class="w-4 h-4 text-primary shrink-0"></i>
                      <h3 class="font-bold text-xs font-mono text-primary truncate" title="<?php echo $file['name']; ?>"><?php echo $file['name']; ?></h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider whitespace-nowrap bg-primary/10 text-primary">
                      <?php echo $file['scope']; ?>
                    </span>
                  </div>
                  
                  <p class="text-[11px] text-primary/70 leading-relaxed mb-4 min-h-[44px]">
                    <?php echo $file['desc']; ?>
                  </p>
                  
                  <!-- CSS & JS Connections Verification Block -->
                  <div class="space-y-2 border-t border-primary/5 pt-3 mb-4">
                    <span class="text-[10px] font-bold text-primary/45 uppercase tracking-wider block">Styles & Scripts Integrations:</span>
                    
                    <div class="flex flex-wrap gap-1.5">
                      <?php foreach ($file['css'] as $css_item): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-transparent text-primary border border-primary/10 rounded-md text-[9px] font-semibold">
                          <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                          <?php echo $css_item; ?>
                        </span>
                      <?php endforeach; ?>
                      
                      <?php foreach ($file['js'] as $js_item): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-transparent text-primary border border-primary/10 rounded-md text-[9px] font-semibold">
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                          <?php echo $js_item; ?>
                        </span>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>

                <!-- Open / Launch button -->
                <div class="flex items-center justify-between border-t border-primary/5 pt-3 mt-1">
                  <span class="text-[9px] font-semibold text-emerald-600 flex items-center gap-1">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    Connected & Styled
                  </span>
                  <a href="<?php echo $file['path']; ?>" class="bg-primary hover:bg-opacity-95 text-secondary px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1 select-none active:scale-95">
                    Launch Page
                    <i data-lucide="external-link" class="w-3 h-3"></i>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

<!-- Interactive JS filters for System Sitemap Explorer -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.getElementById("search-input");
  const fileCards = document.querySelectorAll(".file-card");
  const moduleGroups = document.querySelectorAll(".module-group");
  const categoryBtns = document.querySelectorAll(".category-btn");

  // Search filter
  if (searchInput) {
    searchInput.addEventListener("input", function() {
      const query = searchInput.value.toLowerCase().trim();
      
      fileCards.forEach(card => {
        const text = card.getAttribute("data-search-name");
        if (text && text.includes(query)) {
          card.style.display = "";
        } else {
          card.style.display = "none";
        }
      });

      // Hide module header if all cards inside it are filtered out
      moduleGroups.forEach(group => {
        const visibleCards = group.querySelectorAll(".file-card:not([style*='display: none'])");
        if (visibleCards.length === 0) {
          group.style.display = "none";
        } else {
          group.style.display = "";
        }
      });
    });
  }

  // Category filter
  window.filterCategory = function(catId) {
    categoryBtns.forEach(btn => {
      btn.classList.remove("bg-primary", "text-secondary", "active", "shadow-sm");
      btn.classList.add("bg-primary/5", "text-primary", "hover:bg-primary/10");
    });

    const activeBtn = Array.from(categoryBtns).find(btn => 
      btn.getAttribute("onclick").includes("'" + catId + "'")
    );
    if (activeBtn) {
      activeBtn.classList.remove("bg-primary/5", "text-primary", "hover:bg-primary/10");
      activeBtn.classList.add("bg-primary", "text-secondary", "active", "shadow-sm");
    }

    if (catId === 'all') {
      moduleGroups.forEach(group => {
        group.style.display = "";
      });
    } else {
      moduleGroups.forEach(group => {
        if (group.id === "mod-" + catId) {
          group.style.display = "";
        } else {
          group.style.display = "none";
        }
      });
    }
  };
});
</script>
