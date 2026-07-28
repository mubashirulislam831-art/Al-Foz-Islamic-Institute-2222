#!/bin/bash
sed -i "/\$_SESSION = array();/i \
if (isset(\$_SESSION['role']) && \$_SESSION['role'] === 'Teacher' && isset(\$_SESSION['email'])) {\n\
    require_once __DIR__ . '/../includes/teacher_attendance_functions.php';\n\
    update_teacher_logout(\$_SESSION['email']);\n\
}" ./auth/logout.php
