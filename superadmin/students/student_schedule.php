<?php
/**
 * Al Foz Islamic Institute - Student Schedule Management
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';
require_once __DIR__ . '/../../includes/teachers_data.php';

require_role('Super Admin');

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$student = get_student_by_id($student_id);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    
    $student_data = [];
    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    foreach ($days as $day) {
        $student_data[$day . '_enabled'] = isset($_POST[$day . '_enabled']);
        $student_data[$day . '_time'] = sanitize_input($_POST[$day . '_time'] ?? '');
        $student_data[$day . '_duration'] = sanitize_input($_POST[$day . '_duration'] ?? '30');
        $student_data[$day . '_pkt'] = sanitize_input($_POST[$day . '_pkt'] ?? '');
    }
    
    update_student($id, $student_data);
    
    // Redirect to success state
    header("Location: student_schedule.php?id=$id&updated=success");
    exit;
}

$initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $student['name'])));

$teachers = get_all_teachers();
$teacherTimezones = [];
foreach ($teachers as $t) {
    $teacherTimezones[trim($t['name'])] = $t['timezone'] ?? 'PKT';
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Header and Navigation -->
    <?php require_once __DIR__ . '/_student_header.php'; ?>

    <!-- Student Dossier Portals Box (Structured Vertical-to-Grid Portals) -->
    

    <?php if (isset($_GET['updated']) && $_GET['updated'] === 'success'): ?>
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm">
      <i data-lucide="check-circle-2" class="w-4 h-4 text-green-600"></i>
      Schedule modifications successfully synchronized to the registry.
    </div>
    <?php endif; ?>

    <!-- Hidden localization inputs for JS timing engine -->
    <input type="hidden" id="timezone_input" value="<?php echo htmlspecialchars($student['timezone'] ?? 'UTC'); ?>">
    <input type="hidden" id="teacher_name_hidden" value="<?php echo htmlspecialchars($student['teacher_name'] ?? 'Unassigned'); ?>">

    <form action="student_schedule.php?id=<?php echo $student['id']; ?>" method="POST">
        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Schedule Grid -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                        <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="w-6 h-[1px] bg-primary"></span> Weekly Operational Grid
                        </h3>
                        <button type="button" id="toggle7DaysBtn" class="bg-primary hover:bg-[#123a40] text-white text-[9px] font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all active:scale-95 flex items-center gap-1.5 self-start">
                            📅 Tick All 7 Days
                        </button>
                    </div>
                    
                    <div class="row g-4">
                        
                        <?php 
                        $monday_enabled = !empty($student['monday_enabled']);
                        $monday_time_val = $student['monday_time'] ?? '';
                        $monday_duration_val = $student['monday_duration'] ?? '30';
                        $monday_pkt_val = $student['monday_pkt'] ?? '-';
                        ?>
                        <!-- CARD 1: Monday -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div id="row_monday" class="schedule-row card border border-primary/10 <?php echo $monday_enabled ? 'bg-emerald-50/40 border-emerald-500/20 shadow-sm' : 'bg-slate-50/50'; ?> hover:shadow rounded-4 overflow-hidden transition-all" style="transition: all 0.25s ease;">
                                <div class="card-header bg-primary text-white py-2.5 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-xs font-black uppercase tracking-widest text-white d-flex align-items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4"></i> Monday
                                    </h6>
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded-full status-badge <?php echo $monday_enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'; ?>" id="status_monday">
                                        <?php echo $monday_enabled ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                
                                <div class="card-body p-3.5 bg-white space-y-3">
                                    <div class="form-check p-0 mb-2">
                                        <label class="flex items-center gap-2.5 text-xs font-black text-primary uppercase cursor-pointer select-none">
                                            <input type="checkbox" name="monday_enabled" <?php echo $monday_enabled ? 'checked' : ''; ?> class="rounded text-primary border-primary/25 focus:ring-primary day-checkbox w-4.5 h-4.5">
                                            <span>Enable Monday</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Student Time</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="clock" class="w-3.5 h-3.5"></i></span>
                                            <input type="text" name="monday_time" value="<?php echo htmlspecialchars($monday_time_val); ?>" placeholder="e.g. 05:00 pm" <?php echo $monday_enabled ? '' : 'disabled'; ?> class="form-control text-xs py-1.5 time-input border-start-0" id="time_monday">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Duration</label>
                                        <select name="monday_duration" <?php echo $monday_enabled ? '' : 'disabled'; ?> class="form-select form-select-sm py-1.5 duration-select text-xs" id="dur_monday">
                                            <option value="15" <?php echo $monday_duration_val == '15' ? 'selected' : ''; ?>>15 Min</option>
                                            <option value="20" <?php echo $monday_duration_val == '20' ? 'selected' : ''; ?>>20 Min</option>
                                            <option value="25" <?php echo $monday_duration_val == '25' ? 'selected' : ''; ?>>25 Min</option>
                                            <option value="30" <?php echo $monday_duration_val == '30' ? 'selected' : ''; ?>>30 Min</option>
                                            <option value="35" <?php echo $monday_duration_val == '35' ? 'selected' : ''; ?>>35 Min</option>
                                            <option value="40" <?php echo $monday_duration_val == '40' ? 'selected' : ''; ?>>40 Min</option>
                                            <option value="45" <?php echo $monday_duration_val == '45' ? 'selected' : ''; ?>>45 Min</option>
                                            <option value="50" <?php echo $monday_duration_val == '50' ? 'selected' : ''; ?>>50 Min</option>
                                            <option value="60" <?php echo $monday_duration_val == '60' ? 'selected' : ''; ?>>60 Min</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-2.5 border-top">
                                        <div class="space-y-1.5">
                                            <div class="d-flex align-items-center justify-content-between bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <span class="text-[9px] font-bold uppercase text-slate-500">Student Local Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 student-country-time text-slate-800 w-24" readonly value="-" id="student_country_time_monday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-indigo-50/40 p-2 rounded-xl border border-indigo-100/30">
                                                <span class="text-[9px] font-bold uppercase text-indigo-500">Teacher Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 teacher-time text-indigo-700 w-24" readonly value="-" id="teach_time_monday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-teal-50/40 p-2 rounded-xl border border-teal-100/30">
                                                <span class="text-[9px] font-bold uppercase text-teal-600">Pakistan Time</span>
                                                <input type="text" name="monday_pkt" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 institute-time text-teal-700 w-24" readonly value="<?php echo htmlspecialchars($monday_pkt_val); ?>" id="pkt_time_monday">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $tuesday_enabled = !empty($student['tuesday_enabled']);
                        $tuesday_time_val = $student['tuesday_time'] ?? '';
                        $tuesday_duration_val = $student['tuesday_duration'] ?? '30';
                        $tuesday_pkt_val = $student['tuesday_pkt'] ?? '-';
                        ?>
                        <!-- CARD 2: Tuesday -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div id="row_tuesday" class="schedule-row card border border-primary/10 <?php echo $tuesday_enabled ? 'bg-emerald-50/40 border-emerald-500/20 shadow-sm' : 'bg-slate-50/50'; ?> hover:shadow rounded-4 overflow-hidden transition-all" style="transition: all 0.25s ease;">
                                <div class="card-header bg-primary text-white py-2.5 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-xs font-black uppercase tracking-widest text-white d-flex align-items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4"></i> Tuesday
                                    </h6>
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded-full status-badge <?php echo $tuesday_enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'; ?>" id="status_tuesday">
                                        <?php echo $tuesday_enabled ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                
                                <div class="card-body p-3.5 bg-white space-y-3">
                                    <div class="form-check p-0 mb-2">
                                        <label class="flex items-center gap-2.5 text-xs font-black text-primary uppercase cursor-pointer select-none">
                                            <input type="checkbox" name="tuesday_enabled" <?php echo $tuesday_enabled ? 'checked' : ''; ?> class="rounded text-primary border-primary/25 focus:ring-primary day-checkbox w-4.5 h-4.5">
                                            <span>Enable Tuesday</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Student Time</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="clock" class="w-3.5 h-3.5"></i></span>
                                            <input type="text" name="tuesday_time" value="<?php echo htmlspecialchars($tuesday_time_val); ?>" placeholder="e.g. 05:00 pm" <?php echo $tuesday_enabled ? '' : 'disabled'; ?> class="form-control text-xs py-1.5 time-input border-start-0" id="time_tuesday">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Duration</label>
                                        <select name="tuesday_duration" <?php echo $tuesday_enabled ? '' : 'disabled'; ?> class="form-select form-select-sm py-1.5 duration-select text-xs" id="dur_tuesday">
                                            <option value="15" <?php echo $tuesday_duration_val == '15' ? 'selected' : ''; ?>>15 Min</option>
                                            <option value="20" <?php echo $tuesday_duration_val == '20' ? 'selected' : ''; ?>>20 Min</option>
                                            <option value="25" <?php echo $tuesday_duration_val == '25' ? 'selected' : ''; ?>>25 Min</option>
                                            <option value="30" <?php echo $tuesday_duration_val == '30' ? 'selected' : ''; ?>>30 Min</option>
                                            <option value="35" <?php echo $tuesday_duration_val == '35' ? 'selected' : ''; ?>>35 Min</option>
                                            <option value="40" <?php echo $tuesday_duration_val == '40' ? 'selected' : ''; ?>>40 Min</option>
                                            <option value="45" <?php echo $tuesday_duration_val == '45' ? 'selected' : ''; ?>>45 Min</option>
                                            <option value="50" <?php echo $tuesday_duration_val == '50' ? 'selected' : ''; ?>>50 Min</option>
                                            <option value="60" <?php echo $tuesday_duration_val == '60' ? 'selected' : ''; ?>>60 Min</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-2.5 border-top">
                                        <div class="space-y-1.5">
                                            <div class="d-flex align-items-center justify-content-between bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <span class="text-[9px] font-bold uppercase text-slate-500">Student Local Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 student-country-time text-slate-800 w-24" readonly value="-" id="student_country_time_tuesday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-indigo-50/40 p-2 rounded-xl border border-indigo-100/30">
                                                <span class="text-[9px] font-bold uppercase text-indigo-500">Teacher Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 teacher-time text-indigo-700 w-24" readonly value="-" id="teach_time_tuesday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-teal-50/40 p-2 rounded-xl border border-teal-100/30">
                                                <span class="text-[9px] font-bold uppercase text-teal-600">Pakistan Time</span>
                                                <input type="text" name="tuesday_pkt" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 institute-time text-teal-700 w-24" readonly value="<?php echo htmlspecialchars($tuesday_pkt_val); ?>" id="pkt_time_tuesday">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $wednesday_enabled = !empty($student['wednesday_enabled']);
                        $wednesday_time_val = $student['wednesday_time'] ?? '';
                        $wednesday_duration_val = $student['wednesday_duration'] ?? '30';
                        $wednesday_pkt_val = $student['wednesday_pkt'] ?? '-';
                        ?>
                        <!-- CARD 3: Wednesday -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div id="row_wednesday" class="schedule-row card border border-primary/10 <?php echo $wednesday_enabled ? 'bg-emerald-50/40 border-emerald-500/20 shadow-sm' : 'bg-slate-50/50'; ?> hover:shadow rounded-4 overflow-hidden transition-all" style="transition: all 0.25s ease;">
                                <div class="card-header bg-primary text-white py-2.5 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-xs font-black uppercase tracking-widest text-white d-flex align-items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4"></i> Wednesday
                                    </h6>
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded-full status-badge <?php echo $wednesday_enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'; ?>" id="status_wednesday">
                                        <?php echo $wednesday_enabled ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                
                                <div class="card-body p-3.5 bg-white space-y-3">
                                    <div class="form-check p-0 mb-2">
                                        <label class="flex items-center gap-2.5 text-xs font-black text-primary uppercase cursor-pointer select-none">
                                            <input type="checkbox" name="wednesday_enabled" <?php echo $wednesday_enabled ? 'checked' : ''; ?> class="rounded text-primary border-primary/25 focus:ring-primary day-checkbox w-4.5 h-4.5">
                                            <span>Enable Wednesday</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Student Time</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="clock" class="w-3.5 h-3.5"></i></span>
                                            <input type="text" name="wednesday_time" value="<?php echo htmlspecialchars($wednesday_time_val); ?>" placeholder="e.g. 05:00 pm" <?php echo $wednesday_enabled ? '' : 'disabled'; ?> class="form-control text-xs py-1.5 time-input border-start-0" id="time_wednesday">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Duration</label>
                                        <select name="wednesday_duration" <?php echo $wednesday_enabled ? '' : 'disabled'; ?> class="form-select form-select-sm py-1.5 duration-select text-xs" id="dur_wednesday">
                                            <option value="15" <?php echo $wednesday_duration_val == '15' ? 'selected' : ''; ?>>15 Min</option>
                                            <option value="20" <?php echo $wednesday_duration_val == '20' ? 'selected' : ''; ?>>20 Min</option>
                                            <option value="25" <?php echo $wednesday_duration_val == '25' ? 'selected' : ''; ?>>25 Min</option>
                                            <option value="30" <?php echo $wednesday_duration_val == '30' ? 'selected' : ''; ?>>30 Min</option>
                                            <option value="35" <?php echo $wednesday_duration_val == '35' ? 'selected' : ''; ?>>35 Min</option>
                                            <option value="40" <?php echo $wednesday_duration_val == '40' ? 'selected' : ''; ?>>40 Min</option>
                                            <option value="45" <?php echo $wednesday_duration_val == '45' ? 'selected' : ''; ?>>45 Min</option>
                                            <option value="50" <?php echo $wednesday_duration_val == '50' ? 'selected' : ''; ?>>50 Min</option>
                                            <option value="60" <?php echo $wednesday_duration_val == '60' ? 'selected' : ''; ?>>60 Min</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-2.5 border-top">
                                        <div class="space-y-1.5">
                                            <div class="d-flex align-items-center justify-content-between bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <span class="text-[9px] font-bold uppercase text-slate-500">Student Local Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 student-country-time text-slate-800 w-24" readonly value="-" id="student_country_time_wednesday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-indigo-50/40 p-2 rounded-xl border border-indigo-100/30">
                                                <span class="text-[9px] font-bold uppercase text-indigo-500">Teacher Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 teacher-time text-indigo-700 w-24" readonly value="-" id="teach_time_wednesday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-teal-50/40 p-2 rounded-xl border border-teal-100/30">
                                                <span class="text-[9px] font-bold uppercase text-teal-600">Pakistan Time</span>
                                                <input type="text" name="wednesday_pkt" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 institute-time text-teal-700 w-24" readonly value="<?php echo htmlspecialchars($wednesday_pkt_val); ?>" id="pkt_time_wednesday">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $thursday_enabled = !empty($student['thursday_enabled']);
                        $thursday_time_val = $student['thursday_time'] ?? '';
                        $thursday_duration_val = $student['thursday_duration'] ?? '30';
                        $thursday_pkt_val = $student['thursday_pkt'] ?? '-';
                        ?>
                        <!-- CARD 4: Thursday -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div id="row_thursday" class="schedule-row card border border-primary/10 <?php echo $thursday_enabled ? 'bg-emerald-50/40 border-emerald-500/20 shadow-sm' : 'bg-slate-50/50'; ?> hover:shadow rounded-4 overflow-hidden transition-all" style="transition: all 0.25s ease;">
                                <div class="card-header bg-primary text-white py-2.5 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-xs font-black uppercase tracking-widest text-white d-flex align-items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4"></i> Thursday
                                    </h6>
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded-full status-badge <?php echo $thursday_enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'; ?>" id="status_thursday">
                                        <?php echo $thursday_enabled ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                
                                <div class="card-body p-3.5 bg-white space-y-3">
                                    <div class="form-check p-0 mb-2">
                                        <label class="flex items-center gap-2.5 text-xs font-black text-primary uppercase cursor-pointer select-none">
                                            <input type="checkbox" name="thursday_enabled" <?php echo $thursday_enabled ? 'checked' : ''; ?> class="rounded text-primary border-primary/25 focus:ring-primary day-checkbox w-4.5 h-4.5">
                                            <span>Enable Thursday</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Student Time</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="clock" class="w-3.5 h-3.5"></i></span>
                                            <input type="text" name="thursday_time" value="<?php echo htmlspecialchars($thursday_time_val); ?>" placeholder="e.g. 05:00 pm" <?php echo $thursday_enabled ? '' : 'disabled'; ?> class="form-control text-xs py-1.5 time-input border-start-0" id="time_thursday">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Duration</label>
                                        <select name="thursday_duration" <?php echo $thursday_enabled ? '' : 'disabled'; ?> class="form-select form-select-sm py-1.5 duration-select text-xs" id="dur_thursday">
                                            <option value="15" <?php echo $thursday_duration_val == '15' ? 'selected' : ''; ?>>15 Min</option>
                                            <option value="20" <?php echo $thursday_duration_val == '20' ? 'selected' : ''; ?>>20 Min</option>
                                            <option value="25" <?php echo $thursday_duration_val == '25' ? 'selected' : ''; ?>>25 Min</option>
                                            <option value="30" <?php echo $thursday_duration_val == '30' ? 'selected' : ''; ?>>30 Min</option>
                                            <option value="35" <?php echo $thursday_duration_val == '35' ? 'selected' : ''; ?>>35 Min</option>
                                            <option value="40" <?php echo $thursday_duration_val == '40' ? 'selected' : ''; ?>>40 Min</option>
                                            <option value="45" <?php echo $thursday_duration_val == '45' ? 'selected' : ''; ?>>45 Min</option>
                                            <option value="50" <?php echo $thursday_duration_val == '50' ? 'selected' : ''; ?>>50 Min</option>
                                            <option value="60" <?php echo $thursday_duration_val == '60' ? 'selected' : ''; ?>>60 Min</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-2.5 border-top">
                                        <div class="space-y-1.5">
                                            <div class="d-flex align-items-center justify-content-between bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <span class="text-[9px] font-bold uppercase text-slate-500">Student Local Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 student-country-time text-slate-800 w-24" readonly value="-" id="student_country_time_thursday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-indigo-50/40 p-2 rounded-xl border border-indigo-100/30">
                                                <span class="text-[9px] font-bold uppercase text-indigo-500">Teacher Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 teacher-time text-indigo-700 w-24" readonly value="-" id="teach_time_thursday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-teal-50/40 p-2 rounded-xl border border-teal-100/30">
                                                <span class="text-[9px] font-bold uppercase text-teal-600">Pakistan Time</span>
                                                <input type="text" name="thursday_pkt" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 institute-time text-teal-700 w-24" readonly value="<?php echo htmlspecialchars($thursday_pkt_val); ?>" id="pkt_time_thursday">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $friday_enabled = !empty($student['friday_enabled']);
                        $friday_time_val = $student['friday_time'] ?? '';
                        $friday_duration_val = $student['friday_duration'] ?? '30';
                        $friday_pkt_val = $student['friday_pkt'] ?? '-';
                        ?>
                        <!-- CARD 5: Friday -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div id="row_friday" class="schedule-row card border border-primary/10 <?php echo $friday_enabled ? 'bg-emerald-50/40 border-emerald-500/20 shadow-sm' : 'bg-slate-50/50'; ?> hover:shadow rounded-4 overflow-hidden transition-all" style="transition: all 0.25s ease;">
                                <div class="card-header bg-primary text-white py-2.5 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-xs font-black uppercase tracking-widest text-white d-flex align-items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4"></i> Friday
                                    </h6>
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded-full status-badge <?php echo $friday_enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'; ?>" id="status_friday">
                                        <?php echo $friday_enabled ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                
                                <div class="card-body p-3.5 bg-white space-y-3">
                                    <div class="form-check p-0 mb-2">
                                        <label class="flex items-center gap-2.5 text-xs font-black text-primary uppercase cursor-pointer select-none">
                                            <input type="checkbox" name="friday_enabled" <?php echo $friday_enabled ? 'checked' : ''; ?> class="rounded text-primary border-primary/25 focus:ring-primary day-checkbox w-4.5 h-4.5">
                                            <span>Enable Friday</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Student Time</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="clock" class="w-3.5 h-3.5"></i></span>
                                            <input type="text" name="friday_time" value="<?php echo htmlspecialchars($friday_time_val); ?>" placeholder="e.g. 05:00 pm" <?php echo $friday_enabled ? '' : 'disabled'; ?> class="form-control text-xs py-1.5 time-input border-start-0" id="time_friday">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Duration</label>
                                        <select name="friday_duration" <?php echo $friday_enabled ? '' : 'disabled'; ?> class="form-select form-select-sm py-1.5 duration-select text-xs" id="dur_friday">
                                            <option value="15" <?php echo $friday_duration_val == '15' ? 'selected' : ''; ?>>15 Min</option>
                                            <option value="20" <?php echo $friday_duration_val == '20' ? 'selected' : ''; ?>>20 Min</option>
                                            <option value="25" <?php echo $friday_duration_val == '25' ? 'selected' : ''; ?>>25 Min</option>
                                            <option value="30" <?php echo $friday_duration_val == '30' ? 'selected' : ''; ?>>30 Min</option>
                                            <option value="35" <?php echo $friday_duration_val == '35' ? 'selected' : ''; ?>>35 Min</option>
                                            <option value="40" <?php echo $friday_duration_val == '40' ? 'selected' : ''; ?>>40 Min</option>
                                            <option value="45" <?php echo $friday_duration_val == '45' ? 'selected' : ''; ?>>45 Min</option>
                                            <option value="50" <?php echo $friday_duration_val == '50' ? 'selected' : ''; ?>>50 Min</option>
                                            <option value="60" <?php echo $friday_duration_val == '60' ? 'selected' : ''; ?>>60 Min</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-2.5 border-top">
                                        <div class="space-y-1.5">
                                            <div class="d-flex align-items-center justify-content-between bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <span class="text-[9px] font-bold uppercase text-slate-500">Student Local Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 student-country-time text-slate-800 w-24" readonly value="-" id="student_country_time_friday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-indigo-50/40 p-2 rounded-xl border border-indigo-100/30">
                                                <span class="text-[9px] font-bold uppercase text-indigo-500">Teacher Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 teacher-time text-indigo-700 w-24" readonly value="-" id="teach_time_friday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-teal-50/40 p-2 rounded-xl border border-teal-100/30">
                                                <span class="text-[9px] font-bold uppercase text-teal-600">Pakistan Time</span>
                                                <input type="text" name="friday_pkt" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 institute-time text-teal-700 w-24" readonly value="<?php echo htmlspecialchars($friday_pkt_val); ?>" id="pkt_time_friday">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $saturday_enabled = !empty($student['saturday_enabled']);
                        $saturday_time_val = $student['saturday_time'] ?? '';
                        $saturday_duration_val = $student['saturday_duration'] ?? '30';
                        $saturday_pkt_val = $student['saturday_pkt'] ?? '-';
                        ?>
                        <!-- CARD 6: Saturday -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div id="row_saturday" class="schedule-row card border border-primary/10 <?php echo $saturday_enabled ? 'bg-emerald-50/40 border-emerald-500/20 shadow-sm' : 'bg-slate-50/50'; ?> hover:shadow rounded-4 overflow-hidden transition-all" style="transition: all 0.25s ease;">
                                <div class="card-header bg-primary text-white py-2.5 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-xs font-black uppercase tracking-widest text-white d-flex align-items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4"></i> Saturday
                                    </h6>
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded-full status-badge <?php echo $saturday_enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'; ?>" id="status_saturday">
                                        <?php echo $saturday_enabled ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                
                                <div class="card-body p-3.5 bg-white space-y-3">
                                    <div class="form-check p-0 mb-2">
                                        <label class="flex items-center gap-2.5 text-xs font-black text-primary uppercase cursor-pointer select-none">
                                            <input type="checkbox" name="saturday_enabled" <?php echo $saturday_enabled ? 'checked' : ''; ?> class="rounded text-primary border-primary/25 focus:ring-primary day-checkbox w-4.5 h-4.5">
                                            <span>Enable Saturday</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Student Time</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="clock" class="w-3.5 h-3.5"></i></span>
                                            <input type="text" name="saturday_time" value="<?php echo htmlspecialchars($saturday_time_val); ?>" placeholder="e.g. 05:00 pm" <?php echo $saturday_enabled ? '' : 'disabled'; ?> class="form-control text-xs py-1.5 time-input border-start-0" id="time_saturday">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Duration</label>
                                        <select name="saturday_duration" <?php echo $saturday_enabled ? '' : 'disabled'; ?> class="form-select form-select-sm py-1.5 duration-select text-xs" id="dur_saturday">
                                            <option value="15" <?php echo $saturday_duration_val == '15' ? 'selected' : ''; ?>>15 Min</option>
                                            <option value="20" <?php echo $saturday_duration_val == '20' ? 'selected' : ''; ?>>20 Min</option>
                                            <option value="25" <?php echo $saturday_duration_val == '25' ? 'selected' : ''; ?>>25 Min</option>
                                            <option value="30" <?php echo $saturday_duration_val == '30' ? 'selected' : ''; ?>>30 Min</option>
                                            <option value="35" <?php echo $saturday_duration_val == '35' ? 'selected' : ''; ?>>35 Min</option>
                                            <option value="40" <?php echo $saturday_duration_val == '40' ? 'selected' : ''; ?>>40 Min</option>
                                            <option value="45" <?php echo $saturday_duration_val == '45' ? 'selected' : ''; ?>>45 Min</option>
                                            <option value="50" <?php echo $saturday_duration_val == '50' ? 'selected' : ''; ?>>50 Min</option>
                                            <option value="60" <?php echo $saturday_duration_val == '60' ? 'selected' : ''; ?>>60 Min</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-2.5 border-top">
                                        <div class="space-y-1.5">
                                            <div class="d-flex align-items-center justify-content-between bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <span class="text-[9px] font-bold uppercase text-slate-500">Student Local Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 student-country-time text-slate-800 w-24" readonly value="-" id="student_country_time_saturday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-indigo-50/40 p-2 rounded-xl border border-indigo-100/30">
                                                <span class="text-[9px] font-bold uppercase text-indigo-500">Teacher Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 teacher-time text-indigo-700 w-24" readonly value="-" id="teach_time_saturday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-teal-50/40 p-2 rounded-xl border border-teal-100/30">
                                                <span class="text-[9px] font-bold uppercase text-teal-600">Pakistan Time</span>
                                                <input type="text" name="saturday_pkt" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 institute-time text-teal-700 w-24" readonly value="<?php echo htmlspecialchars($saturday_pkt_val); ?>" id="pkt_time_saturday">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $sunday_enabled = !empty($student['sunday_enabled']);
                        $sunday_time_val = $student['sunday_time'] ?? '';
                        $sunday_duration_val = $student['sunday_duration'] ?? '30';
                        $sunday_pkt_val = $student['sunday_pkt'] ?? '-';
                        ?>
                        <!-- CARD 7: Sunday -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div id="row_sunday" class="schedule-row card border border-primary/10 <?php echo $sunday_enabled ? 'bg-emerald-50/40 border-emerald-500/20 shadow-sm' : 'bg-slate-50/50'; ?> hover:shadow rounded-4 overflow-hidden transition-all" style="transition: all 0.25s ease;">
                                <div class="card-header bg-primary text-white py-2.5 px-4 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-xs font-black uppercase tracking-widest text-white d-flex align-items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4"></i> Sunday
                                    </h6>
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded-full status-badge <?php echo $sunday_enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'; ?>" id="status_sunday">
                                        <?php echo $sunday_enabled ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                
                                <div class="card-body p-3.5 bg-white space-y-3">
                                    <div class="form-check p-0 mb-2">
                                        <label class="flex items-center gap-2.5 text-xs font-black text-primary uppercase cursor-pointer select-none">
                                            <input type="checkbox" name="sunday_enabled" <?php echo $sunday_enabled ? 'checked' : ''; ?> class="rounded text-primary border-primary/25 focus:ring-primary day-checkbox w-4.5 h-4.5">
                                            <span>Enable Sunday</span>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Student Time</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i data-lucide="clock" class="w-3.5 h-3.5"></i></span>
                                            <input type="text" name="sunday_time" value="<?php echo htmlspecialchars($sunday_time_val); ?>" placeholder="e.g. 05:00 pm" <?php echo $sunday_enabled ? '' : 'disabled'; ?> class="form-control text-xs py-1.5 time-input border-start-0" id="time_sunday">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label text-[10px] font-black uppercase text-primary/60 mb-1 d-block">Duration</label>
                                        <select name="sunday_duration" <?php echo $sunday_enabled ? '' : 'disabled'; ?> class="form-select form-select-sm py-1.5 duration-select text-xs" id="dur_sunday">
                                            <option value="15" <?php echo $sunday_duration_val == '15' ? 'selected' : ''; ?>>15 Min</option>
                                            <option value="20" <?php echo $sunday_duration_val == '20' ? 'selected' : ''; ?>>20 Min</option>
                                            <option value="25" <?php echo $sunday_duration_val == '25' ? 'selected' : ''; ?>>25 Min</option>
                                            <option value="30" <?php echo $sunday_duration_val == '30' ? 'selected' : ''; ?>>30 Min</option>
                                            <option value="35" <?php echo $sunday_duration_val == '35' ? 'selected' : ''; ?>>35 Min</option>
                                            <option value="40" <?php echo $sunday_duration_val == '40' ? 'selected' : ''; ?>>40 Min</option>
                                            <option value="45" <?php echo $sunday_duration_val == '45' ? 'selected' : ''; ?>>45 Min</option>
                                            <option value="50" <?php echo $sunday_duration_val == '50' ? 'selected' : ''; ?>>50 Min</option>
                                            <option value="60" <?php echo $sunday_duration_val == '60' ? 'selected' : ''; ?>>60 Min</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-2.5 border-top">
                                        <div class="space-y-1.5">
                                            <div class="d-flex align-items-center justify-content-between bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                <span class="text-[9px] font-bold uppercase text-slate-500">Student Local Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 student-country-time text-slate-800 w-24" readonly value="-" id="student_country_time_sunday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-indigo-50/40 p-2 rounded-xl border border-indigo-100/30">
                                                <span class="text-[9px] font-bold uppercase text-indigo-500">Teacher Time</span>
                                                <input type="text" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 teacher-time text-indigo-700 w-24" readonly value="-" id="teach_time_sunday">
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between bg-teal-50/40 p-2 rounded-xl border border-teal-100/30">
                                                <span class="text-[9px] font-bold uppercase text-teal-600">Pakistan Time</span>
                                                <input type="text" name="sunday_pkt" class="form-control form-control-sm border-0 bg-transparent text-end text-xs font-black p-0 institute-time text-teal-700 w-24" readonly value="<?php echo htmlspecialchars($sunday_pkt_val); ?>" id="pkt_time_sunday">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="student_profile.php?id=<?php echo $student['id']; ?>" class="px-8 py-3 bg-slate-100 hover:bg-slate-200 text-primary rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Cancel Changes</a>
                    <button type="submit" class="px-8 py-3 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:bg-opacity-95 transition-all">
                        Synchronize Schedule
                    </button>
                </div>
            </div>

            <!-- Right Column: Context -->
            <div class="space-y-8">
                <div class="bg-primary rounded-[32px] p-8 border border-white/10 shadow-xl text-white relative overflow-hidden">
                    <div class="absolute right-0 top-0 p-8 opacity-5">
                        <i data-lucide="globe" class="w-32 h-32"></i>
                    </div>
                    <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-8">Localization Context</h3>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">Student Location</p>
                                <p class="text-xs font-black"><?php echo htmlspecialchars($student['country'] ?? 'Unknown'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">Time Zone Node</p>
                                <p class="text-xs font-black"><?php echo htmlspecialchars($student['timezone'] ?? 'UTC'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 p-4 rounded-2xl bg-white/5 border border-white/10 text-[9px] leading-relaxed text-white/60">
                        * All schedules are stored in the MySQL database. The system automatically performs timezone conversion for both faculty and seekers based on their detected nodes.
                    </div>
                </div>

                <div class="bg-white rounded-[32px] p-8 border border-primary/10 shadow-sm">
                    <h3 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-6">Upcoming Sessions</h3>
                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl border border-primary/5 bg-primary/5">
                            <p class="text-[9px] font-black text-primary/40 uppercase tracking-widest mb-1">In 2 Hours</p>
                            <p class="text-xs font-black text-primary"><?php echo htmlspecialchars($student['course'] ?? 'Quran Program'); ?></p>
                            <p class="text-[10px] font-bold text-primary/60">15:30 UTC • <?php echo htmlspecialchars($student['teacher_name'] ?? 'Unassigned'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
const teacherTimezones = <?php echo json_encode($teacherTimezones); ?>;

const timezoneMap = {
    "PKT": 5, "PST": -8, "EST": -5, "CST": -6, "MST": -7, "GMT": 0, "UTC": 0, 
    "BST": 1, "CET": 1, "EET": 2, "AST": 3, "GST": 4, "IST": 5.5, "AEST": 10, "NZST": 12, "SGT": 8, "TRT": 3
};

function parseTimezoneOffset(tzStr) {
    if (!tzStr) return 5; // default PKT
    tzStr = tzStr.toUpperCase().trim();
    if (timezoneMap[tzStr] !== undefined) return timezoneMap[tzStr];
    
    const regex = /(?:UTC|GMT)?([+-])(\d+)(?::(\d+))?/;
    const match = tzStr.match(regex);
    if (match) {
        const sign = match[1] === '-' ? -1 : 1;
        const hours = parseInt(match[2], 10);
        const minutes = match[3] ? parseInt(match[3], 10) : 0;
        return sign * (hours + minutes / 60);
    }
    return 5;
}

function parseTimeToHoursMinutes(timeStr) {
    if (!timeStr) return null;
    timeStr = timeStr.trim().toLowerCase();
    const regex = /^(\d{1,2})(?::(\d{2}))?\s*(am|pm)?$/;
    const match = timeStr.match(regex);
    if (!match) return null;
    
    let hours = parseInt(match[1], 10);
    let minutes = match[2] ? parseInt(match[2], 10) : 0;
    const ampm = match[3];
    
    if (ampm) {
        if (ampm === 'pm' && hours < 12) hours += 12;
        else if (ampm === 'am' && hours === 12) hours = 0;
    }
    
    if (hours < 0 || hours > 23 || minutes < 0 || minutes > 59) return null;
    return { hours, minutes };
}

function convertTime(studentTimeStr, studentTz, targetTz) {
    if (!studentTimeStr) return "-";
    const parsed = parseTimeToHoursMinutes(studentTimeStr);
    if (!parsed) return "-";
    
    const { hours, minutes } = parsed;
    const studentOffset = parseTimezoneOffset(studentTz);
    const targetOffset = parseTimezoneOffset(targetTz);
    
    let utcHours = hours - studentOffset;
    let targetHours = utcHours + targetOffset;
    targetHours = (targetHours % 24 + 24) % 24;
    
    let period = targetHours >= 12 ? 'PM' : 'AM';
    let displayHours = Math.floor(targetHours);
    let displayMinutes = minutes;
    
    let finalHours = displayHours % 12;
    finalHours = finalHours ? finalHours : 12;
    let finalMinutes = displayMinutes.toString().padStart(2, '0');
    
    return `${finalHours.toString().padStart(2, '0')}:${finalMinutes} ${period}`;
}

function updateTimes(row) {
    const timeInputEl = row.querySelector('.time-input');
    const enabled = row.querySelector('.day-checkbox').checked;
    
    if (!enabled) {
        if (row.querySelector('.student-country-time')) row.querySelector('.student-country-time').value = "-";
        row.querySelector('.institute-time').value = "-";
        row.querySelector('.teacher-time').value = "-";
        return;
    }
    
    const timeVal = timeInputEl.value;
    if (!timeVal) {
        if (row.querySelector('.student-country-time')) row.querySelector('.student-country-time').value = "-";
        row.querySelector('.institute-time').value = "-";
        row.querySelector('.teacher-time').value = "-";
        return;
    }
    
    const studentTz = document.getElementById('timezone_input').value || "UTC";
    const teacherName = document.getElementById('teacher_name_hidden').value;
    const teacherTz = teacherTimezones[teacherName] || "PKT";
    
    // Update badge in teacher section
    const badge = row.querySelector('.teacher-tz-badge');
    if (badge) badge.textContent = teacherTz;
    
    if (row.querySelector('.student-country-time')) {
        row.querySelector('.student-country-time').value = convertTime(timeVal, studentTz, studentTz);
    }
    row.querySelector('.institute-time').value = convertTime(timeVal, studentTz, "PKT");
    row.querySelector('.teacher-time').value = convertTime(timeVal, studentTz, teacherTz);
}

function updateAllSchedules() {
    document.querySelectorAll('.schedule-row').forEach(row => {
        updateTimes(row);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Row schedule interactivity
    const checkboxes = document.querySelectorAll('.day-checkbox');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', (e) => {
            const row = e.target.closest('.schedule-row');
            const inputs = row.querySelectorAll('input:not([type="checkbox"]), select');
            const badge = row.querySelector('.status-badge');
            
            if (e.target.checked) {
                row.classList.remove('bg-slate-50/50');
                row.classList.add('bg-emerald-50/40', 'border-emerald-500/20', 'shadow-sm');
                if (badge) {
                    badge.classList.remove('bg-slate-200', 'text-slate-600', 'text-slate-700');
                    badge.classList.add('bg-emerald-100', 'text-emerald-800');
                    badge.textContent = 'Active';
                }
            } else {
                row.classList.add('bg-slate-50/50');
                row.classList.remove('bg-emerald-50/40', 'border-emerald-500/20', 'shadow-sm');
                if (badge) {
                    badge.classList.remove('bg-emerald-100', 'text-emerald-800');
                    badge.classList.add('bg-slate-200', 'text-slate-700');
                    badge.textContent = 'Inactive';
                }
            }
            
            inputs.forEach(input => {
                input.disabled = !e.target.checked;
            });
            
            updateTimes(row);
        });
    });
    
    const timeInputs = document.querySelectorAll('.time-input');
    timeInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const row = e.target.closest('.schedule-row');
            updateTimes(row);
        });
    });
    
    // Quick select all 7 days for kids
    const toggle7DaysBtn = document.getElementById('toggle7DaysBtn');
    if (toggle7DaysBtn) {
        toggle7DaysBtn.addEventListener('click', () => {
            checkboxes.forEach(cb => {
                if (!cb.checked) {
                    cb.checked = true;
                    cb.dispatchEvent(new Event('change'));
                }
                const row = cb.closest('.schedule-row');
                const timeInput = row.querySelector('.time-input');
                if (timeInput && !timeInput.value) {
                    timeInput.value = "15:00"; // default 3:00 PM local
                    updateTimes(row);
                }
            });
        });
    }
    
    // Initial runs
    updateAllSchedules();
});
</script>
