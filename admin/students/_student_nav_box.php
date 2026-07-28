<?php
// Reusable Student Dossier Portals Box (Horizontal Scrollable Ribbon)
$current_page = basename($_SERVER['PHP_SELF']);
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id > 0) {
    $nav_items = [
        [
            'file' => 'student_profile.php',
            'label' => 'Profile',
            'icon' => 'user',
            'bg' => 'bg-primary/5 text-primary',
            'active_bg' => 'bg-primary text-white shadow-md'
        ],
        [
            'file' => 'student_attendance.php',
            'label' => 'Attendance',
            'icon' => 'calendar-check',
            'bg' => 'bg-emerald-50 text-emerald-600',
            'active_bg' => 'bg-emerald-600 text-white shadow-md'
        ],
        [
            'file' => 'student_fees.php',
            'label' => 'Fees & Finance',
            'icon' => 'wallet',
            'bg' => 'bg-amber-50 text-amber-600',
            'active_bg' => 'bg-amber-600 text-white shadow-md'
        ],
        [
            'file' => 'student_exams.php',
            'label' => 'Exams',
            'icon' => 'award',
            'bg' => 'bg-purple-50 text-purple-600',
            'active_bg' => 'bg-purple-600 text-white shadow-md'
        ],
        [
            'file' => 'student_reports.php',
            'label' => 'Reports',
            'icon' => 'file-bar-chart-2',
            'bg' => 'bg-rose-50 text-rose-600',
            'active_bg' => 'bg-rose-600 text-white shadow-md'
        ],
        [
            'file' => 'student_schedule.php',
            'label' => 'Schedule',
            'icon' => 'calendar-days',
            'bg' => 'bg-indigo-50 text-indigo-600',
            'active_bg' => 'bg-indigo-600 text-white shadow-md'
        ],
        [
            'file' => 'student_teacher.php',
            'label' => 'Teacher',
            'icon' => 'graduation-cap',
            'bg' => 'bg-sky-50 text-sky-600',
            'active_bg' => 'bg-sky-600 text-white shadow-md'
        ],
        [
            'file' => 'student_documents.php',
            'label' => 'Documents',
            'icon' => 'files',
            'bg' => 'bg-slate-100 text-slate-700',
            'active_bg' => 'bg-slate-700 text-white shadow-md'
        ],
    ];
?>
<div class="bg-white border border-primary/10 rounded-2xl p-2 shadow-sm mb-6">
    <div class="flex items-center gap-2 custom-horizontal-scrollbar pb-2 pt-1 px-1">
        <?php foreach ($nav_items as $item): 
            $is_active = ($current_page === $item['file']);
            $btn_class = $is_active ? $item['active_bg'] : "{$item['bg']} hover:bg-opacity-80";
        ?>
            <a href="<?php echo $item['file']; ?>?id=<?php echo $student_id; ?>" 
               class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all font-bold text-[10px] uppercase tracking-wider shrink-0 active:scale-95 <?php echo $btn_class; ?>">
                <i data-lucide="<?php echo $item['icon']; ?>" class="w-3.5 h-3.5 shrink-0"></i>
                <span><?php echo htmlspecialchars($item['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php } ?>
