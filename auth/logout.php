<?php
/**
 * Al Foz Islamic Institute - Logout Handler
 * Safely clears PHP session data and cookie states.
 */

require_once __DIR__ . '/session.php';

// Unset all session variables
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Teacher' && isset($_SESSION['email'])) {
    require_once __DIR__ . '/../includes/teacher_attendance_functions.php';
    update_teacher_logout($_SESSION['email']);
}
$_SESSION = array();

// If session was initiated with cookie tracking, destroy the cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session on server
session_destroy();

// Redirect back to login page
header("Location: /auth/login.php?msg=logged_out");
exit();
?>
