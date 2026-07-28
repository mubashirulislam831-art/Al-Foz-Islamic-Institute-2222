<?php
/**
 * Al Foz Islamic Institute - Database Connection Manager
 */

if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'alfoz_erp_user');
if (!defined('DB_PASS')) define('DB_PASS', 'AlFozSecurePass2026!');
if (!defined('DB_NAME')) define('DB_NAME', 'alfoz_erp_db');

$pdo = null;

try {
    // Attempt standard PDO connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Ensure user_id column allows NULL to prevent strict mode insert failures
    try {
        $pdo->exec("ALTER TABLE `teachers` MODIFY `user_id` INT NULL DEFAULT NULL");
    } catch (PDOException $ex) {}
    try {
        $pdo->exec("ALTER TABLE `teachers` ADD COLUMN `form_data` LONGTEXT NULL DEFAULT NULL");
    } catch (PDOException $ex) {}
    try {
        $pdo->exec("ALTER TABLE `students` MODIFY `user_id` INT NULL DEFAULT NULL");
    } catch (PDOException $ex) {}
    try {
        $pdo->exec("ALTER TABLE `students` ADD COLUMN `form_data` LONGTEXT NULL DEFAULT NULL");
    } catch (PDOException $ex) {}
    try {
        $pdo->exec("ALTER TABLE `parents` MODIFY `user_id` INT NULL DEFAULT NULL");
    } catch (PDOException $ex) {}
    try {
        $pdo->exec("ALTER TABLE `admins` MODIFY `user_id` INT NULL DEFAULT NULL");
    } catch (PDOException $ex) {}
} catch (PDOException $e) {
    // High stability fallback logic
    $pdo = null;
    error_log("Database connection failure: " . $e->getMessage());
}
?>
