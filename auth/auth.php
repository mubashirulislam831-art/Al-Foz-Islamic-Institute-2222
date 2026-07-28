<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../database/connection.php';

function authenticate_user($email, $password, $remember = false) {
    global $pdo;
    $email = strtolower(trim($email));
    
    if ($pdo === null) {
        error_log("Database Connection Failed.");
        return false;
    }
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if ($user['status'] !== 'Active') {
                error_log("Unauthorized Access: Account is not active.");
                return false; 
            }
            $password_valid = false;
            $needs_rehash = false;
            if (password_verify($password, $user['password'])) {
                $password_valid = true;
            } 
            else if ($user['password'] === $password) {
                $password_valid = true;
                $needs_rehash = true;
            }
            if ($password_valid) {
                if ($needs_rehash) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmtUpdate = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                    $stmtUpdate->execute([':password' => $newHash, ':id' => $user['id']]);
                }
                
                if ($remember) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), session_id(), time() + 30 * 24 * 60 * 60, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                }
                session_regenerate_id(true);
                
                if ($remember) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), session_id(), time() + 30 * 24 * 60 * 60, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                }
                
                $role = strtolower(str_replace(' ', '_', $user['role']));
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'] ?: $user['name'];
                $_SESSION['name'] = $user['name']; 
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $role;
                $_SESSION['login_time'] = time();
                
                if ($role === 'super_admin') return '/superadmin/dashboard.php';
                if ($role === 'admin') return '/admin/dashboard.php';
                if ($role === 'teacher') return '/teacher/dashboard.php';
                if ($role === 'student') return '/student/dashboard.php';
                if ($role === 'parent') return '/parent/dashboard.php';
            }
        }
    } catch (PDOException $e) {
        error_log("Database error during authentication: " . $e->getMessage());
    }
    return false;
}
?>
