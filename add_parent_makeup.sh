#!/bin/bash
sed -i '/<!-- Stats Cards -->/i \
    <?php \n\
    global $pdo;\n\
    $makeup_history = [];\n\
    if ($pdo !== null) {\n\
        $stmt = $pdo->prepare("SELECT r.*, r.new_date as rescheduled_date, r.new_time as time, t.name as teacher_name FROM rescheduled_classes r JOIN classes c ON r.class_id = c.id JOIN teachers t ON c.teacher_id = t.id WHERE c.student_id = ? ORDER BY r.id DESC");\n\
        $stmt->execute([$ward["id"]]);\n\
        $makeup_history = $stmt->fetchAll(PDO::FETCH_ASSOC);\n\
    }\n\
    ?>' ./parent/attendance.php

sed -i '/<!-- Logs Table -->/i \
    <!-- Makeup Classes Section -->\n\
    <div class="bg-white rounded-[24px] border border-primary/10 shadow-sm p-6 mb-8 overflow-hidden">\n\
      <h2 class="text-sm font-black text-primary uppercase tracking-widest mb-6 flex items-center gap-2"><i data-lucide="refresh-cw" class="w-4 h-4 text-primary/70"></i> Makeup Classes</h2>\n\
      <div class="overflow-x-auto">\n\
        <table class="w-full text-left border-collapse whitespace-nowrap">\n\
          <thead>\n\
            <tr class="border-b border-primary/10">\n\
              <th class="py-3 px-4 text-[10px] font-extrabold text-primary/50 uppercase tracking-wider">Original Date</th>\n\
              <th class="py-3 px-4 text-[10px] font-extrabold text-primary/50 uppercase tracking-wider">Makeup Date</th>\n\
              <th class="py-3 px-4 text-[10px] font-extrabold text-primary/50 uppercase tracking-wider">Teacher</th>\n\
              <th class="py-3 px-4 text-[10px] font-extrabold text-primary/50 uppercase tracking-wider text-right">Status</th>\n\
            </tr>\n\
          </thead>\n\
          <tbody class="text-xs divide-y divide-primary/5">\n\
            <?php if (empty($makeup_history)): ?>\n\
            <tr>\n\
              <td colspan="4" class="py-6 px-4 text-center text-primary/50 font-bold uppercase tracking-wider">\n\
                No makeup classes scheduled.\n\
              </td>\n\
            </tr>\n\
            <?php else: ?>\n\
              <?php foreach ($makeup_history as $row): \n\
                $status = $row["status"] ?? "Pending Approval";\n\
                $statusClass = "";\n\
                switch ($status) {\n\
                  case "Approved": $statusClass = "bg-emerald-500/10 text-emerald-600"; break;\n\
                  case "Declined": $statusClass = "bg-rose-500/10 text-rose-600"; break;\n\
                  case "Completed": $statusClass = "bg-blue-500/10 text-blue-600"; break;\n\
                  default: $statusClass = "bg-amber-500/10 text-amber-600"; break;\n\
                }\n\
              ?>\n\
              <tr class="hover:bg-slate-50/50 transition-colors">\n\
                <td class="py-4 px-4 font-medium text-rose-600/80">\n\
                  <?php echo date("d M Y", strtotime($row["original_date"])); ?>\n\
                </td>\n\
                <td class="py-4 px-4 font-bold text-emerald-600">\n\
                  <?php echo date("d M Y", strtotime($row["new_date"])); ?> <span class="text-primary/50 text-[9px] font-medium ml-1"><?php echo date("h:i A", strtotime($row["time"])); ?></span>\n\
                </td>\n\
                <td class="py-4 px-4 font-bold text-primary">\n\
                  <?php echo htmlspecialchars($row["teacher_name"] ?? "Unknown"); ?>\n\
                </td>\n\
                <td class="py-4 px-4 text-right">\n\
                  <span class="inline-block px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest <?php echo $statusClass; ?>">\n\
                    <?php echo htmlspecialchars($status); ?>\n\
                  </span>\n\
                </td>\n\
              </tr>\n\
              <?php endforeach; ?>\n\
            <?php endif; ?>\n\
          </tbody>\n\
        </table>\n\
      </div>\n\
    </div>\n' ./parent/attendance.php

