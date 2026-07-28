#!/bin/bash
sed -i '/$leaves = \[\];/i \
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {\n\
    $action = $_POST["action"];\n\
    $id = $_POST["id"];\n\
    $type = $_POST["type"];\n\
    if (isset($pdo)) {\n\
        if ($type === "Teacher" && in_array($action, ["Approved", "Rejected"])) {\n\
            $stmt = $pdo->prepare("UPDATE teacher_attendance SET leave_status = ? WHERE id = ?");\n\
            $stmt->execute([$action, $id]);\n\
        }\n\
    }\n\
}' ./superadmin/attendance/leave_management.php

sed -i '/$leaves = \[\];/i \
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {\n\
    $action = $_POST["action"];\n\
    $id = $_POST["id"];\n\
    $type = $_POST["type"];\n\
    if (isset($pdo)) {\n\
        if ($type === "Teacher" && in_array($action, ["Approved", "Rejected"])) {\n\
            $stmt = $pdo->prepare("UPDATE teacher_attendance SET leave_status = ? WHERE id = ?");\n\
            $stmt->execute([$action, $id]);\n\
        }\n\
    }\n\
}' ./admin/attendance/leave_management.php

sed -i 's|<td class="px-6 py-4 text-center">|<td class="px-6 py-4 text-center">\n                            <?php if($l['\''type'\''] === '\''Teacher'\'' \&\& $l['\''status'\''] === '\''Pending'\''): ?>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $l['\''id'\'']; ?>">\n                                <input type="hidden" name="type" value="<?php echo $l['\''type'\'']; ?>">\n                                <input type="hidden" name="action" value="Approved">\n                                <button type="submit" class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-emerald-100 mr-1">Approve</button>\n                            </form>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $l['\''id'\'']; ?>">\n                                <input type="hidden" name="type" value="<?php echo $l['\''type'\'']; ?>">\n                                <input type="hidden" name="action" value="Rejected">\n                                <button type="submit" class="bg-rose-50 text-rose-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-rose-100">Reject</button>\n                            </form>\n                            <?php else: ?>\n                                <span class="text-xs text-gray-400">-</span>\n                            <?php endif; ?>|g' ./superadmin/attendance/leave_management.php

sed -i 's|<td class="px-6 py-4 text-center">|<td class="px-6 py-4 text-center">\n                            <?php if($l['\''type'\''] === '\''Teacher'\'' \&\& $l['\''status'\''] === '\''Pending'\''): ?>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $l['\''id'\'']; ?>">\n                                <input type="hidden" name="type" value="<?php echo $l['\''type'\'']; ?>">\n                                <input type="hidden" name="action" value="Approved">\n                                <button type="submit" class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-emerald-100 mr-1">Approve</button>\n                            </form>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $l['\''id'\'']; ?>">\n                                <input type="hidden" name="type" value="<?php echo $l['\''type'\'']; ?>">\n                                <input type="hidden" name="action" value="Rejected">\n                                <button type="submit" class="bg-rose-50 text-rose-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-rose-100">Reject</button>\n                            </form>\n                            <?php else: ?>\n                                <span class="text-xs text-gray-400">-</span>\n                            <?php endif; ?>|g' ./admin/attendance/leave_management.php

