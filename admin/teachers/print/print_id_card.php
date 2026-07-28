<?php
/**
 * Al Foz Islamic Institute - Print Teacher ID Card
 */
require_once __DIR__ . '/../../../includes/functions.php';
require_role(['Admin', 'Super Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Teacher ID Card</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 50px; }
        .id-card { width: 350px; height: 200px; border: 2px solid #184D55; border-radius: 15px; padding: 20px; position: relative; }
        .header { text-align: center; font-weight: bold; color: #184D55; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .content { display: flex; gap: 20px; }
        .photo { width: 80px; height: 80px; background: #eee; border-radius: 10px; }
        .info div { font-size: 12px; margin-bottom: 5px; }
        .info .label { font-weight: bold; color: #666; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="id-card">
        <div class="header">AL FOZ ISLAMIC INSTITUTE</div>
        <div class="content">
            <div class="photo"></div>
            <div class="info">
                <div><span class="label">Name:</span> Fatima Al-Zahra</div>
                <div><span class="label">ID:</span> TCH-0001</div>
                <div><span class="label">Role:</span> Senior Teacher</div>
                <div><span class="label">Phone:</span> +92 300 1234567</div>
            </div>
        </div>
    </div>
</body>
</html>
