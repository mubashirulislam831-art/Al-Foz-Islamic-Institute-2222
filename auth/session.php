<?php
/**
 * Al Foz Islamic Institute - Session Manager
 * Handles user authentication state and sessions securely.
 */

if (session_status() == PHP_SESSION_NONE) {
    // If a session token is provided in GET, POST or cookies, restore that session ID
    $token = $_GET['alfoz_session_token'] ?? $_POST['alfoz_session_token'] ?? $_COOKIE[session_name()] ?? '';
    // Strip any invalid characters to protect session file paths
    $token = preg_replace('/[^a-zA-Z0-9,-]/', '', $token);
    if (!empty($token)) {
        session_id($token);
    }
    
    // Configure session cookie params for modern browsers (SameSite=None; Secure)
    // inside iframes, although they might still be blocked, it's good practice.
    $cookie_params = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $cookie_params['lifetime'] ?: 0,
        'path' => $cookie_params['path'] ?: '/',
        'domain' => $cookie_params['domain'] ?: '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None'
    ]);
    
    session_start();
}

/**
 * Check if the user is logged in. If not, redirect to the login page.
 * Every protected page must check session exists, user ID exists, user role exists.
 */
function check_session() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !isset($_SESSION['name'])) {
        header("Location: /auth/login.php?error=unauthorized");
        exit();
    }

    $role = strtolower(trim($_SESSION['role']));
    $script = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['REQUEST_URI'] ?? '';

    // Ignore public auth files
    if (strpos($script, '/auth/login.php') !== false || strpos($script, '/auth/logout.php') !== false || strpos($script, '/register.php') !== false || strpos($script, '/test_db_hash.php') !== false) {
        return;
    }

    // Role-based Access Control (RBAC) enforcement
    if ($role === 'teacher') {
        if (strpos($script, '/teacher/') === false) {
            header("Location: /teacher/dashboard.php?error=unauthorized");
            exit();
        }
    } elseif ($role === 'student') {
        if (strpos($script, '/student/') === false) {
            header("Location: /student/dashboard.php?error=unauthorized");
            exit();
        }
    } elseif ($role === 'parent') {
        if (strpos($script, '/parent/') === false) {
            header("Location: /parent/dashboard.php?error=unauthorized");
            exit();
        }
    } elseif ($role === 'admin') {
        if (strpos($script, '/admin/') === false) {
            header("Location: /admin/dashboard.php?error=unauthorized");
            exit();
        }
    } elseif ($role === 'super_admin' || $role === 'super admin') {
        if (strpos($script, '/superadmin/') === false && strpos($script, '/salary/') === false) {
            header("Location: /superadmin/dashboard.php?error=unauthorized");
            exit();
        }
    } else {
        header("Location: /auth/login.php?error=unauthorized");
        exit();
    }
}

/**
 * Check if the user is already logged in and redirect to their respective dashboard.
 */
function redirect_if_logged_in() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
        $token = session_id();
        $token_param = !empty($token) ? "?alfoz_session_token=" . urlencode($token) : "";
        if ($role === 'Super Admin' || $role === 'super_admin') {
            header("Location: /superadmin/dashboard.php" . $token_param);
            exit();
        } elseif ($role === 'Admin' || $role === 'admin') {
            header("Location: /admin/dashboard.php" . $token_param);
            exit();
        } elseif ($role === 'Teacher' || $role === 'teacher') {
            header("Location: /teacher/dashboard.php" . $token_param);
            exit();
        } elseif ($role === 'Student' || $role === 'student') {
            header("Location: /student/dashboard.php" . $token_param);
            exit();
        } elseif ($role === 'Parent' || $role === 'parent') {
            header("Location: /parent/dashboard.php" . $token_param);
            exit();
        }
    }
}
?>
