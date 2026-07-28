#!/bin/bash
sed -i "/require_once __DIR__ . '\/..\/..\/includes\/teachers_data.php';/a require_once __DIR__ . '/../../includes/teacher_attendance_functions.php';" ./teacher/attendance/today_attendance.php
sed -i "/insert_db_record('attendance', \$record);/a \            mark_teacher_present_auto(\$teacher_email);" ./teacher/attendance/today_attendance.php
