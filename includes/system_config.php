<?php
/**
 * Al Foz Islamic Institute - System Configuration
 */
require_once __DIR__ . '/../auth/session.php';

// Default system date to June 2026 if not set
if (!isset($_SESSION['system_date'])) {
    $_SESSION['system_date'] = '2026-06-01';
}

/**
 * Get current system date
 */
function get_system_date() {
    return $_SESSION['system_date'];
}

/**
 * Set system date
 */
function set_system_date($date) {
    $_SESSION['system_date'] = $date;
}

/**
 * Get system month and year
 */
function get_system_month_year() {
    $date = get_system_date();
    return [
        'month' => date('F', strtotime($date)),
        'month_num' => date('m', strtotime($date)),
        'year' => date('Y', strtotime($date))
    ];
}
?>
