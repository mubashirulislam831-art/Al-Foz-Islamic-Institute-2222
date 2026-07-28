<?php
/**
 * Al Foz Islamic Institute - Attendance Record Editor
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

$id = isset($_GET['id']) ? $_GET['id'] : 0;

?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-black text-primary tracking-tight">Record Modification Console</h1>
        <p class="text-xs text-primary/60 mt-1 font-medium">Authoritative override for attendance status and educational remarks.</p>
      </div>
      <a href="dashboard.php" class="text-primary font-bold text-xs flex items-center gap-2">
          <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Desk
      </a>
    </div>

    <div class="max-w-4xl bg-white rounded-[24px] p-8 border border-primary/10 shadow-sm">
        <form class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-primary/40 block mb-3">Attendance Status</label>
                    <select class="status-select w-full bg-primary/5 border border-primary/10 rounded-xl px-4 py-3.5 text-xs font-bold text-primary focus:ring-2 focus:ring-primary outline-none" onchange="updateSelectColor(this)">
                        <option value="present" class="text-green-600">Present (Hazir)</option>
                        <option value="absent" class="text-red-600">Absent (Ghair Hazir)</option>
                        <option value="student-leave" class="text-orange-500">Student On Leave</option>
                        <option value="teacher-leave" class="text-purple-600">Teacher On Leave</option>
                        <option value="not-joined" class="text-yellow-600">Student Not Joined</option>
                        <option value="makeup" class="text-blue-600">Makeup</option>
                    </select>
                </div>

                <style>
                    .status-select option[value="present"] { color: #16a34a; font-weight: bold; }
                    .status-select option[value="absent"] { color: #dc2626; font-weight: bold; }
                    .status-select option[value="student-leave"] { color: #f59e0b; font-weight: bold; }
                    .status-select option[value="teacher-leave"] { color: #7c3aed; font-weight: bold; }
                    .status-select option[value="not-joined"] { color: #ca8a04; font-weight: bold; }
                    .status-select option[value="makeup"] { color: #2563eb; font-weight: bold; }
                </style>
                
                <script>
                    function updateSelectColor(select) {
                        const colors = {
                            'present': '#16a34a',
                            'absent': '#dc2626',
                            'student-leave': '#f59e0b',
                            'teacher-leave': '#7c3aed',
                            'not-joined': '#ca8a04',
                            'makeup': '#2563eb'
                        };
                        select.style.color = colors[select.value] || '#184D55';
                    }
                </script>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-primary/40 block mb-3">Locked Metrics (Read-only)</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-primary/5 rounded-xl px-4 py-3 border border-primary/10">
                            <span class="text-[8px] font-bold text-primary/30 uppercase block">Wait Time</span>
                            <span class="text-xs font-bold text-primary">05:12 min</span>
                        </div>
                        <div class="bg-primary/5 rounded-xl px-4 py-3 border border-primary/10">
                            <span class="text-[8px] font-bold text-primary/30 uppercase block">Duration</span>
                            <span class="text-xs font-bold text-primary">31:45 min</span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="text-[10px] font-bold uppercase tracking-widest text-primary/40 block mb-3">Lesson Covered Override</label>
                <textarea class="w-full h-32 p-4 rounded-2xl border border-primary/10 bg-primary/5 text-xs font-medium focus:ring-2 focus:ring-primary outline-none" placeholder="Enter lesson details..."></textarea>
            </div>

            <div>
                <label class="text-[10px] font-bold uppercase tracking-widest text-primary/40 block mb-3">Administrative Remarks</label>
                <textarea class="w-full h-32 p-4 rounded-2xl border border-primary/10 bg-primary/5 text-xs font-medium focus:ring-2 focus:ring-primary outline-none" placeholder="Enter remarks..."></textarea>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-primary text-secondary px-8 py-3.5 rounded-xl font-black uppercase text-xs tracking-widest shadow-md active:scale-95 transition-all">Update Secure Record</button>
            </div>
        </form>
    </div>
  </div>
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
