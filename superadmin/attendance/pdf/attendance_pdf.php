<?php
/**
 * Al Foz Islamic Institute - Attendance PDF Generator
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';

require_role('Super Admin');

echo "<h1>Attendance PDF Report</h1>";
echo "<p>Generating secure PDF document for " . date('Y-m-d') . "...</p>";
echo "<script>window.print();</script>";
