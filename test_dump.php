<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/students_data.php';
echo "<pre>";
print_r($_SESSION['students']);
echo "</pre>";
