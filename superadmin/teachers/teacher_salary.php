<?php
/**
 * Al Foz Islamic Institute - Super Admin Teacher Specific Salary
 */
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/permissions.php';
require_once __DIR__ . '/../../includes/functions.php';

// Strictly require Super Admin role
require_role('Super Admin');
?>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen bg-transparent">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

    <!-- Dynamic Teacher Header and Navigation -->
    <?php require_once __DIR__ . '/_teacher_header.php'; ?>

    <?php
    // Load all salary disbursement records for this specific teacher
    $all_salaries = get_db_table('salary') ?: [];
    $this_teacher_salaries = [];
    foreach ($all_salaries as $s) {
        if (isset($s['teacher_id']) && (int)$s['teacher_id'] === (int)$teacher['id']) {
            $this_teacher_salaries[] = $s;
        }
    }

    // Sort by paid_date/year/month descending
    usort($this_teacher_salaries, function($a, $b) {
        if (isset($a['year'], $b['year']) && $a['year'] != $b['year']) {
            return $b['year'] - $a['year'];
        }
        if (isset($a['month'], $b['month']) && $a['month'] != $b['month']) {
            return $b['month'] - $a['month'];
        }
        return strcmp($b['paid_date'] ?? '', $a['paid_date'] ?? '');
    });

    $month_names = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
    ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Earnings Breakdown -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white p-6 rounded-2xl border border-primary/10 shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-500/5 rounded-full"></div>
                <span class="text-[9px] text-primary/60 font-black uppercase tracking-wider">Current Month Net</span>
                <div class="text-3xl font-black text-primary mt-1"><?php echo number_format(($teacher['salary'] ?? 0) + ($teacher['allowances'] ?? 0) - ($teacher['deductions'] ?? 0) + ($teacher['extra_classes'] ?? 0)); ?> <span class="text-xs">PKR</span></div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-[10px] font-bold text-primary/70">
                        <span>Base Salary</span>
                        <span class="text-primary"><?php echo number_format($teacher['salary'] ?? 0); ?></span>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold text-emerald-600">
                        <span>Commission</span>
                        <span>+<?php echo number_format($teacher['allowances'] ?? 0); ?></span>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold text-rose-600 border-t border-primary/5 pt-2">
                        <span>Deductions</span>
                        <span>-<?php echo number_format($teacher['deductions'] ?? 0); ?></span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-primary/10 shadow-sm">
                <h4 class="text-[10px] font-black text-primary uppercase tracking-widest mb-4">Salary Distribution Method</h4>
                <div class="flex items-center gap-3 p-3 bg-primary/5 rounded-xl border border-primary/10">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-primary"><?php echo htmlspecialchars($teacher['payment_method'] ?? 'N/A'); ?></div>
                        <div class="text-[9px] text-primary/60 font-mono"><?php echo htmlspecialchars($teacher['account_number'] ?? 'N/A'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historical Earnings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-primary/10 shadow-sm overflow-hidden h-full">
                <div class="p-4 border-b border-primary/10 flex justify-between items-center bg-primary/5">
                    <h3 class="font-bold text-primary text-xs uppercase tracking-wider">Historical Disbursements</h3>
                    <button class="text-primary text-[10px] font-bold uppercase hover:underline">Full Financial Statement</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-primary/10 text-primary uppercase font-bold tracking-wider text-[10px]">
                                <th class="p-4">Month</th>
                                <th class="p-4">Amount</th>
                                <th class="p-4">Payment Date</th>
                                <th class="p-4">Reference</th>
                                <th class="p-4 text-right">Slip</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/5 text-primary/80">
                            <?php if (empty($this_teacher_salaries)): ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-primary/50 font-semibold">No historical disbursements found for this teacher.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($this_teacher_salaries as $sal): ?>
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="p-4 font-bold"><?php echo isset($month_names[$sal['month']]) ? $month_names[$sal['month']] : ''; ?> <?php echo htmlspecialchars($sal['year'] ?? ''); ?></td>
                                    <td class="p-4 font-black text-emerald-700"><?php echo number_format($sal['amount'] ?? 0); ?> PKR</td>
                                    <td class="p-4"><?php echo htmlspecialchars($sal['paid_date'] ?? 'N/A'); ?></td>
                                    <td class="p-4 font-mono text-[10px]"><?php echo htmlspecialchars($sal['slip_number'] ?? 'N/A'); ?></td>
                                    <td class="p-4 text-right">
                                        <button class="text-primary hover:text-primary/70"><i data-lucide="download" class="w-4 h-4"></i></button>
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

  <!-- Shared Footer -->
  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
