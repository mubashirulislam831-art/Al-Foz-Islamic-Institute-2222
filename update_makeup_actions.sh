#!/bin/bash
sed -i '/$makeup_classes = \[\];/i \
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {\n\
    $action = $_POST["action"];\n\
    $id = $_POST["id"];\n\
    if (isset($pdo)) {\n\
        if (in_array($action, ["Approved", "Declined", "Completed"])) {\n\
            $stmt = $pdo->prepare("UPDATE rescheduled_classes SET status = ? WHERE id = ?");\n\
            $stmt->execute([$action, $id]);\n\
        }\n\
    }\n\
}' ./superadmin/attendance/makeup_classes.php

sed -i '/$makeup_classes = \[\];/i \
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {\n\
    $action = $_POST["action"];\n\
    $id = $_POST["id"];\n\
    if (isset($pdo)) {\n\
        if (in_array($action, ["Approved", "Declined", "Completed"])) {\n\
            $stmt = $pdo->prepare("UPDATE rescheduled_classes SET status = ? WHERE id = ?");\n\
            $stmt->execute([$action, $id]);\n\
        }\n\
    }\n\
}' ./admin/attendance/makeup_classes.php

sed -i 's|<td class="px-6 py-4 text-center">|<td class="px-6 py-4 text-center">\n                            <?php if($c['\''status'\''] === '\''Pending Approval'\''): ?>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $c['\''id'\'']; ?>">\n                                <input type="hidden" name="action" value="Approved">\n                                <button type="submit" class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-emerald-100 mr-1">Approve</button>\n                            </form>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $c['\''id'\'']; ?>">\n                                <input type="hidden" name="action" value="Declined">\n                                <button type="submit" class="bg-rose-50 text-rose-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-rose-100 mr-1">Decline</button>\n                            </form>\n                            <?php elseif($c['\''status'\''] === '\''Approved'\''): ?>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $c['\''id'\'']; ?>">\n                                <input type="hidden" name="action" value="Completed">\n                                <button type="submit" class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-blue-100 mr-1">Mark Completed</button>\n                            </form>\n                            <?php endif; ?>|g' ./superadmin/attendance/makeup_classes.php

sed -i 's|<td class="px-6 py-4 text-center">|<td class="px-6 py-4 text-center">\n                            <?php if($c['\''status'\''] === '\''Pending Approval'\''): ?>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $c['\''id'\'']; ?>">\n                                <input type="hidden" name="action" value="Approved">\n                                <button type="submit" class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-emerald-100 mr-1">Approve</button>\n                            </form>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $c['\''id'\'']; ?>">\n                                <input type="hidden" name="action" value="Declined">\n                                <button type="submit" class="bg-rose-50 text-rose-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-rose-100 mr-1">Decline</button>\n                            </form>\n                            <?php elseif($c['\''status'\''] === '\''Approved'\''): ?>\n                            <form method="POST" class="inline-block">\n                                <input type="hidden" name="id" value="<?php echo $c['\''id'\'']; ?>">\n                                <input type="hidden" name="action" value="Completed">\n                                <button type="submit" class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-[10px] font-bold hover:bg-blue-100 mr-1">Mark Completed</button>\n                            </form>\n                            <?php endif; ?>|g' ./admin/attendance/makeup_classes.php

