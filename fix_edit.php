<?php
$files = ['superadmin/students/edit_student.php', 'admin/students/edit_student.php'];
foreach($files as $file) {
    $content = file_get_contents($file);
    $search = "    if(ob_get_length()) ob_end_clean(); header(\"Location: students.php?updated=success\");\n    exit;";
    $replace = "    if (!empty(\$_SERVER['HTTP_ACCEPT']) && strpos(\$_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {\n        header('Content-Type: application/json');\n        echo json_encode(['status' => 'success', 'redirect' => (\$id > 0 ? 'student_profile.php?id=' . \$id . '&updated=success' : 'students.php?updated=success')]);\n        exit;\n    }\n    if(ob_get_length()) ob_end_clean(); header(\"Location: \" . (\$id > 0 ? 'student_profile.php?id=' . \$id . '&updated=success' : 'students.php?updated=success'));\n    exit;";
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
}
?>
