<?php
/**
 * Al Foz Islamic Institute - Print Teacher Salary Slip
 */
require_once __DIR__ . '/../../../includes/functions.php';
require_role(['Admin', 'Super Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Salary Slip</title>
    <style>
        body { font-family: sans-serif; padding: 40px; color: #333; }
        .slip { max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; color: #184D55; }
        .details { display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid #184D55; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #eee; padding: 12px; text-align: left; }
        th { bg-color: #f9f9f9; font-weight: bold; }
        .total { font-size: 18px; font-weight: bold; text-align: right; }
    </style>
</head>
<body onload="window.print()">
    <div class="slip">
        <div class="header">
            <div class="title">AL FOZ ISLAMIC INSTITUTE</div>
            <div>Monthly Salary Statement</div>
        </div>
        <div class="details">
            <div>
                <strong>Teacher:</strong> Fatima Al-Zahra<br>
                <strong>ID:</strong> TCH-0001
            </div>
            <div>
                <strong>Month:</strong> June 2026<br>
                <strong>Date:</strong> 28 Jun 2026
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount (PKR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td>45,000</td>
                </tr>
                <tr>
                    <td>Commission (Student Retention)</td>
                    <td>8,000</td>
                </tr>
                <tr>
                    <td>Performance Bonus</td>
                    <td>2,000</td>
                </tr>
                <tr>
                    <td>Deductions (Late Arrivals)</td>
                    <td>-1,500</td>
                </tr>
            </tbody>
        </table>
        <div class="total">Net Payable: 53,500 PKR</div>
    </div>
</body>
</html>
