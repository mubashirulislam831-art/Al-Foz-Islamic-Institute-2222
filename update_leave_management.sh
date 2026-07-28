#!/bin/bash
sed -i "s/SELECT id, 'Teacher' as type, employee_id as name, date, remarks as reason, 'Approved' as status/SELECT id, 'Teacher' as type, teacher_id, date, leave_reason as reason, leave_status as status/g" ./superadmin/attendance/leave_management.php
sed -i "s/SELECT name FROM teachers WHERE employee_id = ? LIMIT 1/SELECT name FROM teachers WHERE id = ? LIMIT 1/g" ./superadmin/attendance/leave_management.php
sed -i "s/\$stmt_tn->execute(\[\$tl\['name'\]\]);/\$stmt_tn->execute([\$tl['teacher_id']]);/g" ./superadmin/attendance/leave_management.php
sed -i "s/FROM teacher_attendance/FROM teacher_attendance/" ./superadmin/attendance/leave_management.php

# Admin copy
sed -i "s/SELECT id, 'Teacher' as type, employee_id as name, date, remarks as reason, 'Approved' as status/SELECT id, 'Teacher' as type, teacher_id, date, leave_reason as reason, leave_status as status/g" ./admin/attendance/leave_management.php
sed -i "s/SELECT name FROM teachers WHERE employee_id = ? LIMIT 1/SELECT name FROM teachers WHERE id = ? LIMIT 1/g" ./admin/attendance/leave_management.php
sed -i "s/\$stmt_tn->execute(\[\$tl\['name'\]\]);/\$stmt_tn->execute([\$tl['teacher_id']]);/g" ./admin/attendance/leave_management.php

