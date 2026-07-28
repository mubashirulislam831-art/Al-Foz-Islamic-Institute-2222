<?php
/**
 * Al Foz Islamic Institute - Attendance Print View
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';

require_role('Admin');

?>
<div class="p-8">
    <h1 class="text-2xl font-bold">Attendance Print Report</h1>
    <p>Session data for <?php echo date('Y-m-d'); ?></p>
    <hr class="my-4">
    <p>This is a print-ready document.</p>
    <script>window.print();</script>
</div>
