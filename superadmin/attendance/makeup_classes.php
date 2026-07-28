<?php
/**
 * Al Foz Islamic Institute - Makeup Classes Management
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');

// Fetch makeup classes from database
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {

    $action = $_POST["action"];

    $id = $_POST["id"];

    if (isset($pdo)) {

        if (in_array($action, ["Approved", "Declined", "Completed"])) {

            $stmt = $pdo->prepare("UPDATE rescheduled_classes SET status = ? WHERE id = ?");

            $stmt->execute([$action, $id]);

        }

    }

}
$makeup_classes = [];
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("
            SELECT r.*, r.new_date as rescheduled_date, r.new_time as time, s.name as student_name, t.name as teacher_name 
            FROM rescheduled_classes r
            JOIN classes c ON r.class_id = c.id
            JOIN students s ON c.student_id = s.id
            JOIN teachers t ON c.teacher_id = t.id
            ORDER BY r.id DESC
        ");
        $makeup_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <i data-lucide="refresh-cw" class="w-7 h-7"></i>
        </div>
        <div>
          <h1 class="text-2xl font-black text-primary tracking-tight">Makeup Classes</h1>
          <p class="text-xs text-primary/60 mt-1 font-medium">Manage rescheduled and missed sessions</p>
        </div>
      </div>
      <div class="flex gap-3">
         <button class="bg-white border border-primary/20 text-primary px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center gap-2">
            <i data-lucide="filter" class="w-4 h-4"></i> Filter
        </button>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-primary/5 text-primary/50 uppercase font-black text-[10px] tracking-widest border-b border-primary/10">
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Instructor</th>
                        <th class="px-6 py-4">Original Date</th>
                        <th class="px-6 py-4">Rescheduled For</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    <?php if(empty($makeup_classes)): ?>
                        <tr><td colspan="6" class="text-center p-8 text-gray-400 font-bold">No makeup classes found.</td></tr>
                    <?php endif; ?>
                    <?php foreach($makeup_classes as $m): ?>
                    <tr class="hover:bg-primary/[0.02] transition-colors">
                        <td class="px-6 py-4 font-bold text-primary"><?php echo $m['student_name']; ?></td>
                        <td class="px-6 py-4 font-bold text-primary/80"><?php echo $m['teacher_name']; ?></td>
                        <td class="px-6 py-4 font-medium text-gray-500"><?php echo $m['original_date']; ?></td>
                        <td class="px-6 py-4 font-black text-primary"><?php echo $m['rescheduled_date']; ?> <span class="text-xs text-gray-400 font-medium ml-1">@ <?php echo $m['time']; ?></span></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                <?php echo $m['status']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($c['status'] === 'Pending Approval'): ?>
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="action" value="Approved">
                                <button type="submit" class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-emerald-100 mr-1">Approve</button>
                            </form>
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="action" value="Declined">
                                <button type="submit" class="bg-rose-50 text-rose-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-rose-100 mr-1">Decline</button>
                            </form>
                            <?php elseif($c['status'] === 'Approved'): ?>
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="action" value="Completed">
                                <button type="submit" class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-blue-100 mr-1">Mark Completed</button>
                            </form>
                            <?php endif; ?>
                            <button class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors">
                                Edit / Start
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
