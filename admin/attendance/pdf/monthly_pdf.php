<?php
/**
 * Al Foz Islamic Institute - Monthly Attendance PDF Generator
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';

require_role('Admin');

echo "<h1>Monthly Attendance Matrix PDF</h1>";
echo "<p>Generating monthly audit trail...</p>";
echo "<script>window.print();</script>";
