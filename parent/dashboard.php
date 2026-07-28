<?php
/**
 * Al Foz Islamic Institute - Parent Tracking Dashboard
 * Dynamic child progress tracking.
 */
require_once __DIR__ . '/includes/parent_context.php';
?>
<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 page-transition">
    
    <!-- Welcome Header -->
    <div class="mb-10 bg-transparent border border-primary/10 rounded-[32px] p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden shadow-sm">
      <div class="relative z-10">
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-primary">Assalamu Alaikum, <?php echo htmlspecialchars(explode(' ', $parent_name)[0]); ?>!</h2>
        <p class="text-xs sm:text-sm text-primary/75 mt-1">Audit daily progress and track official billing metrics for your seekers.</p>
      </div>
      <div class="bg-white border border-primary/15 rounded-xl px-4 py-2.5 text-xs font-semibold flex items-center gap-2 relative z-10 text-primary shadow-sm">
        <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
        <span>Parents Portal: Active</span>
      </div>
    </div>

    <!-- SUMMARY BOARD -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
      <div class="bg-white rounded-3xl p-6 border border-primary/10 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/40">Seekers Enrolled</span>
        <span class="text-3xl font-extrabold text-primary mt-3"><?php echo $child_count; ?></span>
        <span class="text-[10px] text-primary/60 font-bold mt-2"><?php echo $child_count === 1 ? 'Linked Seeker' : 'Linked Seekers'; ?></span>
      </div>
      <div class="bg-white rounded-3xl p-6 border border-primary/10 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/40">Attendance Score</span>
        <?php
        $avg_attendance = $child_count > 0 ? array_sum(array_map(function($c){return $c['performance']['attendance_score'] ?? 0;}, $children)) / $child_count : 0;
        ?>
        <span class="text-3xl font-extrabold text-emerald-600 mt-3"><?php echo round($avg_attendance); ?>%</span>
        <span class="text-[10px] text-emerald-600 font-bold mt-2">Optimal Engagement</span>
      </div>
      <div class="bg-white rounded-3xl p-6 border border-primary/10 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/40">Invoice Status</span>
        <?php
        $pending = array_filter($children, function($c){return ($c['fee_status'] ?? '') !== 'Paid';});
        $pending_count = count($pending);
        ?>
        <span class="text-3xl font-extrabold <?php echo $pending_count > 0 ? 'text-rose-500' : 'text-emerald-600'; ?> mt-3"><?php echo $pending_count > 0 ? $pending_count . ' Overdue' : 'Cleared'; ?></span>
        <span class="text-[10px] font-bold mt-2 text-primary/60">Billing Registry</span>
      </div>
      <div class="bg-white rounded-3xl p-6 border border-primary/10 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] uppercase font-bold tracking-widest text-primary/40">Portal Status</span>
        <span class="text-3xl font-extrabold text-primary mt-3">Live</span>
        <span class="text-[10px] text-primary/60 font-bold mt-2">Sync: Enabled</span>
      </div>
    </div>


    <!-- CHILD PROFILES LIST -->
    <div class="space-y-10">
      <?php if($child_count > 0): foreach($children as $child): 
          $initials = implode("", array_map(function($n) { return substr($n, 0, 1); }, explode(" ", $child['name'])));
          $avatar_bg = $child['gender'] === 'Male' ? 'bg-primary text-white' : 'bg-rose-500 text-white';
      ?>
      <div class="bg-white rounded-[40px] border border-primary/10 shadow-sm overflow-hidden">
        <!-- Child Header -->
        <div class="p-8 border-b border-primary/5 flex flex-col md:flex-row items-center gap-6">
            <div class="w-24 h-24 rounded-3xl <?php echo $avatar_bg; ?> flex items-center justify-center border-4 border-slate-50 shadow-xl overflow-hidden">
                <?php if(!empty($child['student_picture'])): ?>
                    <img src="<?php echo $child['student_picture']; ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-2xl font-black"><?php echo $initials; ?></span>
                <?php endif; ?>
            </div>
            <div class="flex-grow text-center md:text-left">
                <h3 class="text-2xl font-black tracking-tight text-primary"><?php echo htmlspecialchars($child['name']); ?></h3>
                <p class="text-xs font-black uppercase tracking-widest text-primary/40 mt-1"><?php echo $child['course']; ?> &bull; Instructor: <?php echo $child['teacher_name']; ?></p>
            </div>
            <div class="flex gap-4">
                <div class="text-center bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100">
                    <p class="text-[9px] font-black uppercase text-primary/40 tracking-widest">Attendance</p>
                    <p class="text-lg font-black text-primary"><?php echo $child['performance']['attendance_score'] ?? 0; ?>%</p>
                </div>
                <div class="text-center bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100">
                    <p class="text-[9px] font-black uppercase text-primary/40 tracking-widest">Fee Status</p>
                    <p class="text-lg font-black <?php echo ($child['fee_status'] ?? '') === 'Paid' ? 'text-emerald-600' : 'text-rose-500'; ?>"><?php echo $child['fee_status'] ?? 'Pending'; ?></p>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Progress Section -->
            <div class="md:col-span-2 space-y-6">
                <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 flex items-center gap-2">
                    <i data-lucide="activity" class="w-4 h-4"></i> Progress Data
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                        <p class="text-[9px] font-black uppercase tracking-widest text-primary/40 mb-3">Academic Performance</p>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold">Exam Average</span>
                            <span class="text-xs font-black"><?php echo $child['performance']['exam_score'] ?? 0; ?>%</span>
                        </div>
                        <div class="w-full h-1.5 bg-white rounded-full overflow-hidden border border-slate-100">
                            <div class="h-full bg-emerald-500" style="width: <?php echo $child['performance']['exam_score'] ?? 0; ?>%"></div>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                        <p class="text-[9px] font-black uppercase tracking-widest text-primary/40 mb-3">Homework Completion</p>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold">Integrity Score</span>
                            <span class="text-xs font-black"><?php echo $child['performance']['homework_score'] ?? 0; ?>%</span>
                        </div>
                        <div class="w-full h-1.5 bg-white rounded-full overflow-hidden border border-slate-100">
                            <div class="h-full bg-primary" style="width: <?php echo $child['performance']['homework_score'] ?? 0; ?>%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Feedback Box -->
                <div class="bg-primary/5 p-6 rounded-3xl border border-primary/10">
                    <h5 class="text-[9px] font-black uppercase tracking-widest text-primary/40 mb-2">Teacher Feedback & Messages</h5>
                    <p class="text-xs font-bold leading-relaxed text-primary/80">
                        <?php echo !empty($child['parent_notes']) ? nl2br(htmlspecialchars($child['parent_notes'])) : 'No messages from instructor yet.'; ?>
                    </p>
                </div>
            </div>

            <!-- Schedule & Quick Info -->
            <div class="space-y-6">
                <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i> Weekly Schedule
                </h4>
                <div class="space-y-2">
                    <?php 
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    foreach($days as $day): 
                        $lower = strtolower($day);
                        if(!empty($child[$lower.'_enabled'])):
                    ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-[10px] font-black uppercase text-primary"><?php echo $day; ?></span>
                        <span class="text-[10px] font-bold text-primary/60"><?php echo date('h:i A', strtotime($child[$lower.'_time'])); ?></span>
                    </div>
                    <?php endif; endforeach; ?>
                </div>
                
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $child['whatsapp']); ?>" class="w-full py-4 bg-emerald-500 text-white rounded-2xl flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Contact Coordinator
                </a>
            </div>
        </div>
      </div>
      <?php endforeach; else: ?>
        <div class="bg-white rounded-[40px] border border-primary/10 shadow-sm p-20 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="users-2" class="w-10 h-10 text-primary/20"></i>
            </div>
            <h3 class="text-xl font-black text-primary">No Linked Seekers Found</h3>
            <p class="text-sm text-primary/60 mt-2 max-w-md mx-auto">Please contact the Al Foz administration to link your child's enrollment with this parent account (<?php echo htmlspecialchars($parent_email); ?>).</p>
        </div>
      <?php endif; ?>
    </div>

  </main>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
