<?php
/**
 * Al Foz Islamic Institute - Teacher ERP
 * Makeup Classes
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../auth/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_name = $_SESSION['name'] ?? 'Faculty Member';
global $pdo;

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_makeup') {
        $student_id = $_POST['student_id'] ?? 0;
        $original_date = $_POST['original_date'] ?? '';
        $new_date = $_POST['new_date'] ?? '';
        $new_time = $_POST['new_time'] ?? '';
        $reason = $_POST['reason'] ?? '';
        
        if ($pdo !== null && $student_id && $original_date && $new_date) {
            $year = intval(date('Y', strtotime($new_date)));
            $month = intval(date('n', strtotime($new_date)));
            
            // Dummy class creation logic just to store the request for now
            // since we don't have a separate makeup classes table specifically for this in schema without class_id
            $stmt = $pdo->prepare("INSERT INTO classes (student_id, teacher_id, class_name, schedule_time, status, date, year, month) VALUES (?, (SELECT id FROM teachers WHERE name = ? LIMIT 1), 'Makeup Class', ?, 'Scheduled', ?, ?, ?)");
            if ($stmt->execute([$student_id, $teacher_name, $new_time, $new_date, $year, $month])) {
                $class_id = $pdo->lastInsertId();
                $stmt = $pdo->prepare("INSERT INTO rescheduled_classes (class_id, original_date, new_date, new_time, reason, status, year, month) VALUES (?, ?, ?, ?, ?, 'Pending Approval', ?, ?)");
                $stmt->execute([$class_id, $original_date, $new_date, $new_time, $reason, $year, $month]);
                $msg = 'makeup_added';
            }
        }
    }
}

// Fetch students assigned to this teacher
$all_students = get_all_students() ?: [];
$my_students = array_filter($all_students, function($s) use ($teacher_name) {
    return (isset($s['teacher_name']) && $s['teacher_name'] === $teacher_name);
});

// Fetch makeup classes history
$makeup_history = [];
if ($pdo !== null) {
    $stmt = $pdo->prepare("
        SELECT r.*, c.student_id, s.name as student_name 
        FROM rescheduled_classes r 
        JOIN classes c ON r.class_id = c.id 
        JOIN teachers t ON c.teacher_id = t.id
        LEFT JOIN students s ON c.student_id = s.id
        WHERE t.name = ? 
        ORDER BY r.new_date DESC
    ");
    $stmt->execute([$teacher_name]);
    $makeup_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-[#F4F7F9] page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mt-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">MAKEUP CLASSES</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Schedule and track makeup sessions</p>
      </div>
    </div>
    
    <?php if ($msg === 'makeup_added'): ?>
    <div class="bg-emerald-50 text-emerald-600 border border-emerald-200 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2">
      <i data-lucide="check-circle" class="w-5 h-5"></i>
      Makeup class requested successfully. Awaiting approval.
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Schedule Form -->
      <div class="lg:col-span-1 bg-white rounded-[32px] p-8 shadow-sm border border-primary/10">
        <h2 class="text-lg font-black text-primary uppercase tracking-tight mb-6">Schedule Makeup</h2>
        <form method="POST" action="">
          <input type="hidden" name="action" value="add_makeup">
          
          <div class="space-y-4">
            <div>
              <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Select Student</label>
              <select name="student_id" required class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-2xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all">
                <option value="">Choose Student...</option>
                <?php foreach ($my_students as $student): ?>
                <option value="<?php echo htmlspecialchars($student['id']); ?>">
                  <?php echo htmlspecialchars($student['name']); ?> (<?php echo htmlspecialchars($student['roll_no'] ?? $student['student_id']); ?>)
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <div>
              <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Original Missed Date</label>
              <input type="date" name="original_date" required class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-2xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">New Date</label>
                <input type="date" name="new_date" required min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-2xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all">
              </div>
              <div>
                <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Time</label>
                <input type="time" name="new_time" required class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-2xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all">
              </div>
            </div>
            
            <div>
              <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Reason (Optional)</label>
              <textarea name="reason" rows="2" placeholder="Reason for rescheduling..." class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-2xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all resize-none"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-primary hover:bg-primary/95 text-white py-3 rounded-2xl text-[10px] font-extrabold uppercase tracking-wider shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 mt-4">
              <i data-lucide="calendar-plus" class="w-4 h-4"></i> Request Schedule
            </button>
          </div>
        </form>
      </div>

      <!-- History -->
      <div class="lg:col-span-2 bg-white rounded-[32px] p-8 shadow-sm border border-primary/10">
        <h2 class="text-lg font-black text-primary uppercase tracking-tight mb-6">Requested Makeup Classes</h2>
        
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
              <tr class="border-b border-primary/10 bg-slate-50/50">
                <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider rounded-tl-2xl">Student</th>
                <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">Missed Date</th>
                <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">Makeup Date</th>
                <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider text-right rounded-tr-2xl">Status</th>
              </tr>
            </thead>
            <tbody class="text-sm divide-y divide-primary/5">
              <?php if (empty($makeup_history)): ?>
              <tr>
                <td colspan="4" class="p-8 text-center text-primary/50 text-xs font-bold uppercase tracking-wider">
                  No makeup classes scheduled.
                </td>
              </tr>
              <?php else: ?>
                <?php foreach ($makeup_history as $row): 
                  $status = $row['status'] ?? 'Pending Approval';
                  $statusClass = '';
                  switch ($status) {
                    case 'Approved': $statusClass = 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20'; break;
                    case 'Declined': $statusClass = 'bg-rose-500/10 text-rose-600 border border-rose-500/20'; break;
                    default: $statusClass = 'bg-amber-500/10 text-amber-600 border border-amber-500/20'; break;
                  }
                ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="p-4">
                    <div class="font-bold text-primary">
                      <?php echo htmlspecialchars($row['student_name'] ?? 'Unknown'); ?>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="text-xs font-medium text-rose-600/80">
                      <?php echo date('d M Y', strtotime($row['original_date'])); ?>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="text-xs font-bold text-emerald-600">
                      <?php echo date('d M Y', strtotime($row['new_date'])); ?> <span class="text-primary/50 text-[10px] font-medium ml-1"><?php echo date('h:i A', strtotime($row['new_time'])); ?></span>
                    </div>
                  </td>
                  <td class="p-4 text-right">
                    <span class="inline-block px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-widest <?php echo $statusClass; ?>">
                      <?php echo htmlspecialchars($status); ?>
                    </span>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
