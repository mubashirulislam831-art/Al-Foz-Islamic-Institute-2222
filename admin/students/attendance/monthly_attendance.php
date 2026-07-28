<?php
/**
 * Al Foz Islamic Institute - Super Admin Seeker Monthly Attendance
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/students_data.php';

// Strictly require Super Admin role
require_role(['Admin', 'Super Admin']);

$id = isset($_GET['id']) ? intval($_GET['id']) : 101;
$student = get_student_by_id($id);

if (!$student) {
    header("Location: ../students.php");
    exit;
}

$month = isset($_GET['month']) ? sanitize_input($_GET['month']) : date('F Y');
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../../includes/navbar.php'; ?>

    <div class="mb-6 mt-4">
      <a href="../student_profile.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1 mb-2">
        ← Back to Profile
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3 uppercase tracking-tight">Monthly Attendance Report</h1>
      <p class="text-xs text-primary/60 mt-1 uppercase tracking-widest font-bold">Admin Management View</p>
    </div>

    <!-- Report Container -->
    <div id="report_container" class="max-w-4xl mx-auto bg-white rounded-3xl border border-primary/10 shadow-sm overflow-hidden mt-8">
        
        <!-- Header -->
        <div class="bg-primary p-8 text-center border-b border-primary/10 text-white relative">
            <button onclick="window.print()" class="absolute right-6 top-6 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-colors"><i data-lucide="printer" class="w-3.5 h-3.5 inline mr-1"></i> Print</button>
            <h2 class="text-lg font-black uppercase tracking-[0.2em] opacity-90 mb-2 mt-4">Al Foz Islamic Institute</h2>
            <h1 class="text-3xl font-black uppercase tracking-widest text-primary">Monthly Attendance Report</h1>
        </div>

        <div class="p-8 space-y-8">
            <!-- Student Profile Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-xs">
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Student Name</span>
                    <span class="font-black text-primary"><?php echo htmlspecialchars($student['name']); ?></span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Student ID</span>
                    <span class="font-black text-primary"><?php echo htmlspecialchars($student['student_id']); ?></span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Portal Code</span>
                    <span class="font-black text-primary">-</span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Course</span>
                    <span class="font-black text-primary"><?php echo htmlspecialchars($student['course']); ?></span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Teacher Name</span>
                    <span class="font-black text-primary"><?php echo htmlspecialchars($student['teacher_name']); ?></span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Country</span>
                    <span class="font-black text-primary"><?php echo htmlspecialchars($student['country'] ?? 'Pakistan'); ?></span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">City</span>
                    <span class="font-black text-primary">-</span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Currency</span>
                    <span class="font-black text-primary">USD</span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Month</span>
                    <span class="font-black text-primary"><?php echo htmlspecialchars($month); ?></span>
                </div>
                <div class="flex justify-between border-b border-primary/5 pb-2">
                    <span class="font-bold text-primary/50 uppercase tracking-widest">Class Duration</span>
                    <span class="font-black text-primary"><?php echo htmlspecialchars($student['monday_duration'] ?? '30'); ?> Min</span>
                </div>
            </div>

            <!-- Attendance Summary -->
            <div>
                <h3 class="text-sm font-black uppercase tracking-widest text-primary mb-4 pb-2 border-b border-primary/10">Attendance Summary</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    <div class="bg-primary/5 rounded-xl p-3">
                        <div class="text-[10px] font-bold text-primary/50 uppercase tracking-wider mb-1">Total Classes</div>
                        <div class="text-lg font-black text-primary">20</div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-3 border border-green-100">
                        <div class="text-[10px] font-bold text-green-700/60 uppercase tracking-wider mb-1">Present</div>
                        <div class="text-lg font-black text-green-700">18</div>
                    </div>
                    <div class="bg-red-50 rounded-xl p-3 border border-red-100">
                        <div class="text-[10px] font-bold text-red-700/60 uppercase tracking-wider mb-1">Absent</div>
                        <div class="text-lg font-black text-red-700">0</div>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-3 border border-amber-100">
                        <div class="text-[10px] font-bold text-amber-700/60 uppercase tracking-wider mb-1">Leaves</div>
                        <div class="text-lg font-black text-amber-700">2</div>
                    </div>
                </div>
            </div>

            <!-- Monthly Attendance Table -->
            <div>
                <h3 class="text-sm font-black uppercase tracking-widest text-primary mb-4 pb-2 border-b border-primary/10 flex justify-between items-center">
                    Monthly Attendance Table
                    <button class="bg-primary/5 hover:bg-primary/10 text-primary px-3 py-1.5 rounded-lg text-[9px] font-bold transition-colors">Edit Entries</button>
                </h3>
                <div class="overflow-x-auto rounded-xl border border-primary/10">
                    <table class="w-full text-left text-[10px]">
                        <thead class="bg-primary/5">
                            <tr class="uppercase font-bold text-primary/70 tracking-wider">
                                <th class="p-3">Date</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center">Teacher Waited</th>
                                <th class="p-3 text-center">Duration</th>
                                <th class="p-3">Lesson</th>
                                <th class="p-3">Homework</th>
                                <th class="p-3">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/5 font-semibold text-primary/90" id="report_tbody">
                            <?php 
                            for ($i = 1; $i <= 20; $i++) {
                                $st = "Present";
                                $cls = "text-green-600";
                                if ($i % 8 === 0) { $st = "Absent"; $cls = "text-red-600"; }
                                elseif ($i % 14 === 0) { $st = "Student Leave"; $cls = "text-amber-500"; }
                                
                                $waited = ($st === "Present" && $i%3===0) ? "2 Min" : "0 Min";
                                $dur = ($st === "Present") ? ($student['monday_duration'] ?? '30') . " Min" : "-";
                                $lesson = ($st === "Present") ? "Qaida Pg " . $i : "-";
                                $hw = ($st === "Present") ? "Read" : "-";
                                $rmk = ($st === "Present") ? "Good" : "-";
                                
                                echo "<tr class='border-b border-primary/5 last:border-0 hover:bg-primary/5'>";
                                echo "<td class='p-3 whitespace-nowrap'>" . str_pad($i, 2, '0', STR_PAD_LEFT) . " Jun 2026</td>";
                                echo "<td class='p-3 font-bold $cls'>$st</td>";
                                echo "<td class='p-3 text-center'>$waited</td>";
                                echo "<td class='p-3 text-center'>$dur</td>";
                                echo "<td class='p-3'>$lesson</td>";
                                echo "<td class='p-3'>$hw</td>";
                                echo "<td class='p-3 text-primary/60 italic'>$rmk</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lesson Tracker & Performance -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-4">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-3 pb-2 border-b border-primary/10">Lesson Tracker</h3>
                    <div class="space-y-3 text-[11px]">
                        <div class="flex justify-between"><span class="text-primary/60 font-bold uppercase tracking-wider">Completed Lessons</span><span class="font-black text-primary">Qaida Pg 1-20</span></div>
                        <div class="flex justify-between"><span class="text-primary/60 font-bold uppercase tracking-wider">Current Lesson</span><span class="font-black text-primary">Qaida Pg 21</span></div>
                        <div class="flex justify-between"><span class="text-primary/60 font-bold uppercase tracking-wider">Next Lesson</span><span class="font-black text-primary">Qaida Pg 22</span></div>
                        <div class="flex justify-between"><span class="text-primary/60 font-bold uppercase tracking-wider">Homework Completion</span><span class="font-black text-green-600">95%</span></div>
                    </div>
                </div>
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-3 pb-2 border-b border-primary/10 flex justify-between">Performance <button class="bg-primary/5 hover:bg-primary/10 text-primary px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-widest transition-colors">Update</button></h3>
                    <div class="space-y-3 text-[11px]">
                        <div class="flex justify-between"><span class="text-primary/60 font-bold uppercase tracking-wider">Teacher Rating</span><span class="font-black text-primary">★★★★★</span></div>
                        <div class="flex justify-between"><span class="text-primary/60 font-bold uppercase tracking-wider">Student Progress</span><span class="font-black text-primary">Excellent</span></div>
                        <div class="flex justify-between"><span class="text-primary/60 font-bold uppercase tracking-wider">Exam Average</span><span class="font-black text-primary">92%</span></div>
                        <div class="flex justify-between"><span class="text-primary/60 font-bold uppercase tracking-wider">Attendance Score</span><span class="font-black text-primary">90%</span></div>
                    </div>
                </div>
            </div>

            <!-- Signatures -->
            <div class="pt-12 pb-6 flex justify-between items-end px-4 sm:px-12 border-t border-primary/5 mt-8">
                <div class="text-center">
                    <div class="w-24 sm:w-32 h-px bg-primary/20 mb-2 mx-auto"></div>
                    <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-primary/60">Teacher Signature</p>
                </div>
                <div class="text-center space-y-1">
                    <p class="text-[8px] sm:text-[9px] font-bold text-primary/40 uppercase tracking-widest">Generated Date: <?php echo date('d M Y'); ?></p>
                    <p class="text-[8px] sm:text-[9px] font-bold text-primary/40 uppercase tracking-widest">Generated Time: <?php echo date('h:i A'); ?></p>
                </div>
                <div class="text-center">
                    <div class="w-24 sm:w-32 h-px bg-primary/20 mb-2 mx-auto"></div>
                    <p class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-primary/60">Admin Signature</p>
                </div>
            </div>

        </div>
    </div>

  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
</div>

<!-- Print Styles -->
<style>
@media print {
    body * { visibility: hidden; }
    #report_container, #report_container * { visibility: visible; }
    #report_container { position: absolute; left: 0; top: 0; width: 100%; border: none; box-shadow: none; margin: 0; padding: 0; }
    button { display: none !important; }
    .bg-primary { background-color: #184D55 !important; -webkit-print-color-adjust: exact; }
    .text-white { color: white !important; }
}
</style>
