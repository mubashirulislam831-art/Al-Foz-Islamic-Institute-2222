<?php
/**
 * Al Foz Islamic Institute - Teacher ERP
 * Leave Request
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../auth/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/teacher_attendance_functions.php';

require_role(['Teacher', 'Admin', 'Super Admin']);

$teacher_email = $_SESSION['email'] ?? 'teacher@alfoz.org';
$teacher_id = get_teacher_id_by_email($teacher_email);

global $pdo;

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'request_leave') {
    $date = $_POST['date'] ?? '';
    $reason = $_POST['reason'] ?? '';
    
    if ($date && $reason) {
        if (request_teacher_leave($teacher_email, $reason, $date)) {
            $msg = 'leave_requested';
        } else {
            $msg = 'error';
        }
    }
}

$leave_requests = [];
if ($pdo !== null && $teacher_id) {
    $stmt = $pdo->prepare("SELECT * FROM teacher_attendance WHERE teacher_id = ? AND status = 'Leave' ORDER BY date DESC");
    $stmt->execute([$teacher_id]);
    $leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">LEAVE REQUEST</h1>
        <p class="text-xs text-primary/70 font-bold mt-1 uppercase tracking-widest">Apply for leave and view status</p>
      </div>
    </div>
    
    <?php if ($msg === 'leave_requested'): ?>
    <div class="bg-emerald-50 text-emerald-600 border border-emerald-200 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-2">
      <i data-lucide="check-circle" class="w-5 h-5"></i>
      Leave request submitted successfully.
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Leave Form -->
      <div class="lg:col-span-1 bg-white rounded-[32px] p-8 shadow-sm border border-primary/10">
        <h2 class="text-lg font-black text-primary uppercase tracking-tight mb-6">Apply for Leave</h2>
        <form method="POST" action="">
          <input type="hidden" name="action" value="request_leave">
          
          <div class="space-y-4">
            <div>
              <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Leave Date</label>
              <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-2xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all">
            </div>
            
            <div>
              <label class="block text-[10px] font-extrabold text-primary/60 uppercase tracking-wider mb-2">Reason for Leave</label>
              <textarea name="reason" required rows="4" placeholder="Please specify the reason..." class="w-full px-4 py-3 bg-slate-50 border border-primary/10 rounded-2xl text-xs font-bold text-primary focus:outline-none focus:border-primary/30 transition-all resize-none"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-primary hover:bg-primary/95 text-white py-3 rounded-2xl text-[10px] font-extrabold uppercase tracking-wider shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
              <i data-lucide="send" class="w-4 h-4"></i> Submit Request
            </button>
          </div>
        </form>
      </div>

      <!-- Leave History -->
      <div class="lg:col-span-2 bg-white rounded-[32px] p-8 shadow-sm border border-primary/10">
        <h2 class="text-lg font-black text-primary uppercase tracking-tight mb-6">Leave History</h2>
        
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
              <tr class="border-b border-primary/10 bg-slate-50/50">
                <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider rounded-tl-2xl">Date</th>
                <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider">Reason</th>
                <th class="p-4 text-[10px] font-extrabold text-primary/60 uppercase tracking-wider rounded-tr-2xl text-right">Status</th>
              </tr>
            </thead>
            <tbody class="text-sm divide-y divide-primary/5">
              <?php if (empty($leave_requests)): ?>
              <tr>
                <td colspan="3" class="p-8 text-center text-primary/50 text-xs font-bold uppercase tracking-wider">
                  No leave requests found.
                </td>
              </tr>
              <?php else: ?>
                <?php foreach ($leave_requests as $leave): 
                  $status = $leave['leave_status'] ?? 'Pending';
                  $statusClass = '';
                  switch ($status) {
                    case 'Approved': $statusClass = 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20'; break;
                    case 'Rejected': $statusClass = 'bg-rose-500/10 text-rose-600 border border-rose-500/20'; break;
                    default: $statusClass = 'bg-amber-500/10 text-amber-600 border border-amber-500/20'; break;
                  }
                ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="p-4">
                    <div class="font-bold text-primary">
                      <?php echo date('d M Y', strtotime($leave['date'])); ?>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="text-xs font-medium text-primary/70 whitespace-normal max-w-sm">
                      <?php echo htmlspecialchars($leave['leave_reason'] ?? '-'); ?>
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
