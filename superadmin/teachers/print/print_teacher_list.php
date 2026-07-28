<?php
/**
 * Al Foz Islamic Institute - Print Teacher List
 */
require_once __DIR__ . '/../../../includes/functions.php';
require_role('Super Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Teacher Directory</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { color: #184D55; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background: #f4f4f4; }
    </style>
</head>
<body onload="window.print()">
    <h1>Faculty Directory - Al Foz Islamic Institute</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Course</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>TCH-0001</td>
                <td>Fatima Al-Zahra</td>
                <td>Abdullah Khan</td>
                <td>+92 300 1234567</td>
                <td>fatima@alfoz.com</td>
                <td>Tajweed & Hifz</td>
                <td>Active</td>
            </tr>
            <!-- More rows... -->
        </tbody>
    </table>
</body>
</html>
