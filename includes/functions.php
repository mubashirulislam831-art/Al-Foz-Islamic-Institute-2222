<?php
/**
 * Al Foz Islamic Institute - Shared Core ERP Functions
 */

/**
 * Sanitize user input to protect against XSS
 */
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Format currency nicely
 */
function format_currency($amount, $currency = 'PKR') {
    return $currency . ' ' . number_format($amount, 2);
}

/**
 * Generate a styled status badge
 */
function render_status_badge($status) {
    $status = strtolower(trim($status));
    switch ($status) {
        case 'active':
        case 'paid':
        case 'present':
        case 'completed':
            return '<span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-[10px] font-bold uppercase">Active</span>';
        case 'inactive':
        case 'unpaid':
        case 'absent':
        case 'pending':
            return '<span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-[10px] font-bold uppercase">Pending</span>';
        case 'excused':
        case 'leave':
            return '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold uppercase">Leave</span>';
        default:
            return '<span class="px-2 py-1 rounded-full bg-slate-100 text-slate-800 text-[10px] font-bold uppercase">' . htmlspecialchars($status) . '</span>';
    }
}

/**
 * Calculate PKT time from a local time and timezone
 */
function calculate_pkt_time($time_str, $timezone) {
    if (!$time_str || $time_str === '--:--' || $time_str === '') return '--:--';
    
    $offsets = [
        'PKT' => 5, 'GMT' => 0, 'BST' => 1, 'EST' => -5, 'EDT' => -4,
        'CST' => -6, 'CDT' => -5, 'PST' => -8, 'PDT' => -7, 'GST' => 4,
        'AST' => 3, 'AEDT' => 11, 'AEST' => 10
    ];
    
    $offset = $offsets[$timezone] ?? 5;
    
    // Use a fixed date to avoid issues with DST transitions in strtotime if not needed
    $time = strtotime("2026-01-01 " . $time_str);
    if (!$time) return '--:--';
    
    $pkt_timestamp = $time - ($offset * 3600) + (5 * 3600);
    return date('h:i A', $pkt_timestamp);
}

if (!function_exists('render_dashboard_profile_pic_html')) {
    function render_dashboard_profile_pic_html() {
        global $student, $teacher, $admin, $initials;
        $picture = $student['student_picture'] ?? $teacher['teacher_picture'] ?? '';
        $init = $initials ?? 'ST';
        if (!empty($picture)) {
            return '<img src="' . htmlspecialchars($picture) . '" alt="Profile Picture" class="w-20 h-20 rounded-2xl object-cover border border-[#184D55]/20 shadow-inner">';
        }
        return '<div class="w-20 h-20 rounded-2xl bg-[#184D55] text-white flex items-center justify-center font-black text-2xl border border-[#184D55]/20 shadow-inner">' . $init . '</div>';
    }
}

if (!function_exists('render_sidebar_profile_pic_html')) {
    function render_sidebar_profile_pic_html() {
        global $student, $teacher, $admin, $initials;
        $picture = $student['student_picture'] ?? $teacher['teacher_picture'] ?? '';
        $init = $initials ?? 'ST';
        if (!empty($picture)) {
            return '<img src="' . htmlspecialchars($picture) . '" class="w-10 h-10 rounded-full object-cover shrink-0 border border-primary/20">';
        }
        return '<div class="w-10 h-10 rounded-full bg-[#184D55] text-white flex items-center justify-center font-black text-sm shrink-0 border border-primary/20">' . $init . '</div>';
    }
}

if (!function_exists('render_user_role_title_html')) {
    function render_user_role_title_html() {
        $role = $_SESSION['role'] ?? 'Guest';
        $role_lower = strtolower(trim($role));
        switch ($role_lower) {
            case 'super_admin':
            case 'superadmin':
            case 'super admin':
                return 'Super Admin';
            case 'admin':
                return 'Admin';
            case 'teacher':
                return 'Teacher';
            case 'student':
                return 'Student';
            case 'parent':
                return 'Parent';
            default:
                return htmlspecialchars(ucwords(str_replace('_', ' ', $role)));
        }
    }
}
?>
