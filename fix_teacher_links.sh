#!/bin/bash
sed -i 's/teacher_profile.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>#edit-section/edit_teacher.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>/g' ./superadmin/teachers/teachers.php
sed -i 's/teacher_profile.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>#students-section/teacher_students.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>/g' ./superadmin/teachers/teachers.php
sed -i 's/teacher_profile.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>#schedule-section/teacher_schedule.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>/g' ./superadmin/teachers/teachers.php
sed -i 's/teacher_profile.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>#attendance-section/teacher_attendance.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>/g' ./superadmin/teachers/teachers.php
sed -i 's/teacher_profile.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>#finance-section/teacher_salary.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>/g' ./superadmin/teachers/teachers.php
sed -i 's/teacher_profile.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>#reports-section/teacher_reports.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>/g' ./superadmin/teachers/teachers.php
sed -i 's/teacher_profile.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>#timeline-section/teacher_timeline.php?id=<?php echo $teacher\['"'"'id'"'"'\]; ?>/g' ./superadmin/teachers/teachers.php
