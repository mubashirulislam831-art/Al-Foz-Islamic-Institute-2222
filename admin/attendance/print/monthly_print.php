<?php
/**
 * Al Foz Islamic Institute - Monthly Attendance Print View
 */
require_once __DIR__ . '/../../../includes/header.php';
require_once __DIR__ . '/../../../includes/permissions.php';

require_role('Admin');

?>
<div class="p-8">
    <h1 class="text-2xl font-bold">Monthly Attendance Matrix</h1>
    <p>Audit trail for <?php echo date('F Y'); ?></p>
    <hr class="my-4">
    <p>This is a print-ready document.</p>
    <script>window.print();</script>
</div>
