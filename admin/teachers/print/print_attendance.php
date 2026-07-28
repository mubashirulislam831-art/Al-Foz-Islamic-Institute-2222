<?php
/**
 * Al Foz Islamic Institute - Print Teacher Attendance
 */
require_once __DIR__ . '/../../../includes/functions.php';
require_role(['Admin', 'Super Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Attendance Report</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { color: #184D55; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; }
        th { background: #f4f4f4; }
    </style>
</head>
<body onload="window.print()">
    <h1>Teacher Attendance Report - June 2026</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Teacher Name</th>
                <th>Login</th>
                <th>Logout</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>25 Jun 2026</td>
                <td>Fatima Al-Zahra</td>
                <td>08:55 AM</td>
                <td>02:15 PM</td>
                <td>Present</td>
            </tr>
            <!-- More rows... -->
        </tbody>
    </table>
</body>
</html>
