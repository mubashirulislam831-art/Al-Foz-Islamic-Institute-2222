<?php
/**
 * Al Foz Islamic Institute - Parent Management System Data Engine
 * Integrated with Dual-Mode DB Bridge (MySQL Strict Mode)
 */
require_once __DIR__ . '/db_bridge.php';

// Sync session parents with persistent database
$db_parents = get_db_table('parents');
$_SESSION['parents'] = [];
foreach ($db_parents as $p) {
    $id = intval($p['id']);
    $_SESSION['parents'][$id] = $p;
}

/**
 * Get all parent records
 */
function get_all_parents() {
    return $_SESSION['parents'];
}

/**
 * Get a single parent record
 */
function get_parent_by_id($id) {
    return $_SESSION['parents'][$id] ?? null;
}

/**
 * Add a new parent record
 */
function add_parent($data) {
    $next_id = 1;
    $parents = get_db_table('parents');
    if (!empty($parents)) {
        $ids = array_map(function($p) { return intval($p['id']); }, $parents);
        $next_id = max($ids) + 1;
    }
    
    $record = [
        'id' => $next_id,
        'user_id' => $data['user_id'] ?? $next_id + 4,
        'name' => $data['name'],
        'relation' => $data['relation'] ?? 'Father',
        'whatsapp' => $data['whatsapp'],
        'country' => $data['country'] ?? 'Pakistan',
        'timezone' => $data['timezone'] ?? 'PKT',
        'status' => $data['status'] ?? 'Active',
        'student_roll_no' => $data['student_roll_no'] ?? '',
        'portal_email' => $data['portal_email'] ?? '',
        'portal_password' => !empty($data['portal_password']) ? password_hash($data['portal_password'], PASSWORD_DEFAULT) : ''
    ];
    
    insert_db_record('parents', $record);
    
    // Auto-create User for Portal Access
    $portal_email = $data['portal_email'] ?? '';
    $raw_password = $data['portal_password'] ?? '';
    if (!empty($portal_email) && !empty($raw_password)) {
        $clean_email = strtolower(trim($portal_email));
        global $pdo;
        $user_id = null;
        if ($pdo !== null) {
            try {
                $stmt_u = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
                $stmt_u->execute([$clean_email]);
                $ex_u = $stmt_u->fetch(PDO::FETCH_ASSOC);
                if ($ex_u && !empty($ex_u['id'])) {
                    $user_id = intval($ex_u['id']);
                    update_db_record('users', 'id', $user_id, [
                        'name' => $data['name'] ?? 'Parent',
                        'password' => password_hash($raw_password, PASSWORD_DEFAULT),
                        'role' => 'Parent',
                        'status' => 'Active'
                    ]);
                }
            } catch (PDOException $ex) {}
        }
        if (!$user_id) {
            insert_db_record('users', [
                'username' => 'parent_' . $next_id,
                'name' => $data['name'] ?? 'Parent',
                'email' => $clean_email,
                'password' => password_hash($raw_password, PASSWORD_DEFAULT),
                'role' => 'Parent',
                'status' => 'Active'
            ]);
        }
    }
    
    $_SESSION['parents'][$next_id] = $record;
    return $next_id;
}
?>
