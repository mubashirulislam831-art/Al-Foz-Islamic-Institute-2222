<?php
/**
 * Al Foz Islamic Institute - Permissions Manager
 * Verifies and enforces role-based access control.
 */

require_once __DIR__ . '/session.php';

/**
 * Enforces that the current logged in user has one of the allowed roles.
 *
 * @param array|string $allowed_roles Role or list of roles allowed to access this resource.
 */
function require_role($allowed_roles) {
    check_session();
    
    if (!isset($_SESSION['role'])) {
        header("Location: /auth/login.php?error=unauthorized");
        exit();
    }
    
    $user_role = strtolower(str_replace(' ', '_', $_SESSION['role']));
    
    if (is_array($allowed_roles)) {
        $normalized_allowed = array_map(function($r) {
            return strtolower(str_replace(' ', '_', $r));
        }, $allowed_roles);
        
        if (!in_array($user_role, $normalized_allowed)) {
            header("Location: /auth/login.php?error=unauthorized");
            exit();
        }
    } else {
        $target_role = strtolower(str_replace(' ', '_', $allowed_roles));
        if ($user_role !== $target_role) {
            header("Location: /auth/login.php?error=unauthorized");
            exit();
        }
    }
}
?>
