<?php
/**
 * Al Foz Islamic Institute - Leave Management
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Admin');

// Fetch dynamic leaves from student and teacher attendance logs
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {

    $action = $_POST["action"];

    $id = $_POST["id"];

    $type = $_POST["type"];

    if (isset($pdo)) {

        if ($type === "Teacher" && in_array($action, ["Approved", "Rejected"])) {

            $stmt = $pdo->prepare("UPDATE teacher_attendance SET leave_status = ? WHERE id = ?");

            $stmt->execute([$action, $id]);

        }

    }

}
$leaves = [];
if (isset($pdo)) {
    try {
        // Query student leaves from attendance table
        $stmt_s = $pdo->query("
            SELECT a.id, 'Student' as type, s.name, a.date, a.notes as reason, 'Approved' as status 
            FROM attendance a
            JOIN students s ON a.student_id = s.id
            WHERE a.status = 'Leave'
            ORDER BY a.date DESC
        ");
        $student_leaves = $stmt_s->fetchAll(PDO::FETCH_ASSOC);
        
        // Query teacher leaves from teacher_attendance table
        $stmt_t = $pdo->query("
            SELECT id, 'Teacher' as type, teacher_id, date, leave_reason as reason, leave_status as status
            FROM teacher_attendance
            WHERE status = 'Leave'
            ORDER BY date DESC
        ");
        $teacher_leaves = $stmt_t->fetchAll(PDO::FETCH_ASSOC);
        
        // Resolve teacher employee_id to name
        foreach ($teacher_leaves as &$tl) {
            $stmt_tn = $pdo->prepare("SELECT name FROM teachers WHERE id = ? LIMIT 1");
            $stmt_tn->execute([$tl['teacher_id']]);
            $name_res = $stmt_tn->fetchColumn();
            if ($name_res) {
                $tl['name'] = $name_res;
            }
        }
        unset($tl);
        
        $leaves = array_merge($student_leaves, $teacher_leaves);
        
        // Sort by date DESC
        usort($leaves, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });
        
    } catch (PDOException $ex) {
        // Safe fallback
    }
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent font-['Poppins']">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Module Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 bg-white p-6 rounded-2xl border border-primary/10 shadow-sm">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-primary/5 rounded-xl flex items-center justify-center text-primary shrink-0 border border-primary/10">
            <i data-lucide="user-minus" class="w-7 h-7"></i>
        </div>
        <div>
          <h1 class="text-2xl font-black text-primary tracking-tight">Leave Management</h1>
          <p class="text-xs text-primary/60 mt-1 font-medium">Handle leave requests from teachers and students</p>
        </div>
      </div>
      <div class="flex gap-3">
         <button class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-md flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Leave Entry
        </button>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-primary/5 text-primary/50 uppercase font-black text-[10px] tracking-widest border-b border-primary/10">
                        <th class="px-6 py-4">Requester Type</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Leave Dates</th>
                        <th class="px-6 py-4">Reason</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    <?php if(empty($leaves)): ?>
                        <tr><td colspan="6" class="text-center p-8 text-gray-400 font-bold">No leave requests found.</td></tr>
                    <?php endif; ?>
                    <?php foreach($leaves as $l): ?>
                    <tr class="hover:bg-primary/[0.02] transition-colors">
                        <td class="px-6 py-4 font-black <?php echo $l['type'] === 'Teacher' ? 'text-purple-600' : 'text-orange-500'; ?>"><?php echo $l['type']; ?></td>
                        <td class="px-6 py-4 font-bold text-primary"><?php echo $l['name']; ?></td>
                        <td class="px-6 py-4 font-medium text-gray-500"><?php echo $l['date']; ?></td>
                        <td class="px-6 py-4 font-medium text-gray-500 truncate max-w-xs"><?php echo $l['reason']; ?></td>
                        <td class="px-6 py-4">
                            <?php 
                                $bg = 'bg-yellow-100 text-yellow-700';
                                if($l['status'] === 'Approved') $bg = 'bg-green-100 text-green-700';
                                if($l['status'] === 'Rejected') $bg = 'bg-red-100 text-red-700';
                            ?>
                            <span class="px-3 py-1 <?php echo $bg; ?> rounded-lg text-[10px] font-black uppercase tracking-widest">
                                <?php echo $l['status']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($l['type'] === 'Teacher' && $l['status'] === 'Pending'): ?>
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo $l['id']; ?>">
                                <input type="hidden" name="type" value="<?php echo $l['type']; ?>">
                                <input type="hidden" name="action" value="Approved">
                                <button type="submit" class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-emerald-100 mr-1">Approve</button>
                            </form>
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo $l['id']; ?>">
                                <input type="hidden" name="type" value="<?php echo $l['type']; ?>">
                                <input type="hidden" name="action" value="Rejected">
                                <button type="submit" class="bg-rose-50 text-rose-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-rose-100">Reject</button>
                            </form>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">-</span>
                            <?php endif; ?>
                            <button class="bg-gray-50 hover:bg-gray-100 text-gray-600 px-3 py-2 rounded-lg text-[10px] font-bold uppercase transition-colors">
                                Review
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
