<?php
/**
 * API: Set System Date Context
 */
require_once __DIR__ . '/../includes/system_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = $_POST['month'] ?? 'June';
    $year = $_POST['year'] ?? '2026';
    $redirect = $_POST['redirect'] ?? '/';
    
    // Construct the date string (first of the month)
    $date_str = $year . '-' . date('m', strtotime($month)) . '-01';
    
    set_system_date($date_str);
    
    // Clear dynamic session data if needed (user requested refresh for new month)
    // Note: We don't delete student/teacher records, but we might want to reset 
    // monthly specific data like attendance if it was stored in a way that doesn't 
    // respect the system date. For now, we just update the context.
    
    header('Location: ' . $redirect);
    exit;
}
?>
