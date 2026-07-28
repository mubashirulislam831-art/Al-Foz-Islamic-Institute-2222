<?php
/**
 * Al Foz Islamic Institute - Super Admin Student Issue Ticket Manager
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/students_data.php';

// Strictly require Super Admin role
require_role('Super Admin');

$id = isset($_GET['id']) ? intval($_GET['id']) : 101;
$student = get_student_by_id($id);



$success = false;

// Handle ticket status change
if (isset($_GET['action']) && $_GET['action'] === 'update_status') {
    $issue_id = intval($_GET['issue_id']);
    $new_status = sanitize_input($_GET['status']);
    
    foreach ($_SESSION['students'][$id]['issues'] as $index => $issue) {
        if (intval($issue['id']) === $issue_id) {
            $_SESSION['students'][$id]['issues'][$index]['status'] = $new_status;
            
            // Add timeline log
            $_SESSION['students'][$id]['timeline'][] = [
                'date' => date('Y-m-d'),
                'type' => 'Complaints History',
                'title' => 'Issue #' . $issue_id . ' Status Changed',
                'desc' => 'Complaint of type ' . $issue['type'] . ' set to status: ' . $new_status
            ];
            break;
        }
    }
    header("Location: student_issue.php?id=$id&updated=1");
    exit;
}

// Handle adding new ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = sanitize_input($_POST['type']);
    $desc = sanitize_input($_POST['desc']);
    $new_id = count($_SESSION['students'][$id]['issues']) + 1;
    
    $_SESSION['students'][$id]['issues'][] = [
        'id' => $new_id,
        'type' => $type,
        'desc' => $desc,
        'date' => date('Y-m-d'),
        'status' => 'Pending'
    ];
    
    // Add timeline log
    $_SESSION['students'][$id]['timeline'][] = [
        'date' => date('Y-m-d'),
        'type' => 'Complaints History',
        'title' => 'New Ticket #' . $new_id . ' Logged',
        'desc' => 'Filed ' . $type . ': "' . substr($desc, 0, 40) . '..."'
    ];
    
    header("Location: student_issue.php?id=$id&created=1");
    exit;
}
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-4 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="mb-8 mt-4">
      <a href="student_profile.php?id=<?php echo $id; ?>" class="text-xs font-bold text-primary/60 hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
        ← Back to Profile
      </a>
      <h1 class="text-2xl font-extrabold text-primary mt-3">Complaints & Issues Tickets</h1>
      <p class="text-xs text-primary/60 mt-0.5">Manage and resolve tickets filed by Students, Parents, or Instructors.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Active Tickets -->
      <div class="lg:col-span-7 bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-6 pb-2 border-b border-slate-50">Active Ticket Registry</h3>
        
        <div class="space-y-4">
          <?php if (empty($student['issues'])): ?>
            <p class="text-xs text-slate-400">No tickets logged yet for this student.</p>
          <?php else: ?>
            <?php foreach ($student['issues'] as $issue): 
              $badge_class = $issue['status'] === 'Solved' ? 'bg-emerald-50 text-emerald-700' : ($issue['status'] === 'Closed' ? 'bg-slate-100 text-slate-500' : ($issue['status'] === 'Accepted' ? 'bg-indigo-50 text-indigo-700' : 'bg-rose-50 text-rose-700'));
            ?>
              <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/20 text-xs space-y-3">
                <div class="flex justify-between items-center">
                  <div class="flex items-center gap-2">
                    <span class="font-extrabold text-primary text-sm"><?php echo htmlspecialchars($issue['type']); ?></span>
                    <span class="text-[9px] text-slate-400">Ticket #<?php echo htmlspecialchars($issue['id']); ?> | Filed: <?php echo htmlspecialchars($issue['date']); ?></span>
                  </div>
                  <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase <?php echo $badge_class; ?>"><?php echo htmlspecialchars($issue['status']); ?></span>
                </div>
                <p class="text-slate-600 italic">"<?php echo htmlspecialchars($issue['desc']); ?>"</p>
                
                <!-- Quick Status Change Action Menu -->
                <div class="flex flex-wrap gap-1 pt-2 border-t border-slate-100 items-center">
                  <span class="text-[9px] text-slate-400 uppercase font-bold mr-2">Change Status:</span>
                  <a href="student_issue.php?id=<?php echo $id; ?>&action=update_status&issue_id=<?php echo $issue['id']; ?>&status=Pending" class="px-2 py-1 bg-rose-50 text-rose-700 text-[9px] font-bold rounded hover:bg-rose-100 transition-all">Pending</a>
                  <a href="student_issue.php?id=<?php echo $id; ?>&action=update_status&issue_id=<?php echo $issue['id']; ?>&status=Accepted" class="px-2 py-1 bg-indigo-50 text-indigo-700 text-[9px] font-bold rounded hover:bg-indigo-100 transition-all">Accept</a>
                  <a href="student_issue.php?id=<?php echo $id; ?>&action=update_status&issue_id=<?php echo $issue['id']; ?>&status=Solved" class="px-2 py-1 bg-emerald-50 text-emerald-700 text-[9px] font-bold rounded hover:bg-emerald-100 transition-all">Solve</a>
                  <a href="student_issue.php?id=<?php echo $id; ?>&action=update_status&issue_id=<?php echo $issue['id']; ?>&status=Closed" class="px-2 py-1 bg-slate-100 text-slate-600 text-[9px] font-bold rounded hover:bg-slate-200 transition-all">Close</a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Log New Issue -->
      <div class="lg:col-span-5 bg-white rounded-2xl border border-primary/10 shadow-sm p-6">
        <h3 class="text-xs font-black uppercase tracking-widest text-primary mb-5 pb-2 border-b border-slate-50">Log New Concern Ticket</h3>
        
        <form action="student_issue.php?id=<?php echo $id; ?>" method="POST" class="space-y-4">
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">Complainant / Ticket Origin *</label>
            <select name="type" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none bg-white" required>
              <option value="Student Complaint">Student Complaint</option>
              <option value="Parent Complaint">Parent Complaint</option>
              <option value="Teacher Complaint">Teacher Complaint</option>
            </select>
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase text-primary mb-2">Detailed Issue Description *</label>
            <textarea name="desc" placeholder="Describe the technical, operational, or academic issue..." rows="4" class="w-full px-4 py-2.5 border border-primary/10 rounded-xl text-xs focus:ring-1 focus:ring-primary focus:outline-none" required></textarea>
          </div>
          <button type="submit" class="w-full bg-primary text-white text-xs font-bold py-2.5 rounded-xl uppercase tracking-wider shadow-sm hover:bg-opacity-95 transition-all">Log Complaint Ticket</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>
