<?php
/**
 * Al Foz Islamic Institute - Unified Database Access Bridge
 * Strictly uses MySQL. No JSON fallback for ERP modules.
 */
require_once __DIR__ . '/../auth/session.php';

require_once __DIR__ . '/../database/connection.php';

function get_db_table($table_name) {
    global $pdo;
    if ($pdo !== null) {
        try {
            $stmt = $pdo->query("SELECT * FROM `" . $table_name . "`");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch (PDOException $e) {
            error_log("MySQL Table Query failed: " . $e->getMessage());
        }
    }
    return [];
}

function get_db_table_columns($table_name) {
    global $pdo;
    static $columns_cache = [];
    if (isset($columns_cache[$table_name])) {
        return $columns_cache[$table_name];
    }
    if ($pdo !== null) {
        try {
            $stmt = $pdo->query("DESCRIBE `" . $table_name . "`");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($cols)) {
                $columns_cache[$table_name] = $cols;
                return $cols;
            }
        } catch (PDOException $e) {
            error_log("Failed to describe table columns for " . $table_name . ": " . $e->getMessage());
        }
    }
    return [];
}

function insert_db_record($table_name, $record) {
    global $pdo;
    if ($pdo !== null) {
        try {
            if (isset($record['id'])) {
                unset($record['id']);
            }
            $valid_columns = get_db_table_columns($table_name);
            if (!empty($valid_columns)) {
                $filtered = [];
                foreach ($record as $key => $val) {
                    if (in_array($key, $valid_columns)) {
                        if (is_bool($val)) {
                            $val = $val ? 1 : 0;
                        }
                        $filtered[$key] = $val;
                    }
                }
                $record = $filtered;
            } else {
                foreach ($record as $key => &$val) {
                    if (is_bool($val)) {
                        $val = $val ? 1 : 0;
                    }
                }
            }
            if (empty($record)) {
                return $record;
            }
            $fields = array_keys($record);
            $placeholders = array_map(function($f) { return ":" . $f; }, $fields);
            
            $sql = "INSERT INTO `" . $table_name . "` (" . implode(",", array_map(function($f) { return "`" . $f . "`"; }, $fields)) . ") VALUES (" . implode(",", $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($record);
            $record['id'] = $pdo->lastInsertId();
            return $record;
        } catch (PDOException $e) {
            error_log("MySQL Insert failed for " . $table_name . ": " . $e->getMessage());
        }
    }
    return $record;
}

function update_db_record($table_name, $key_field, $key_value, $update_fields) {
    global $pdo;
    if ($pdo !== null) {
        try {
            $valid_columns = get_db_table_columns($table_name);
            if (!empty($valid_columns)) {
                $filtered = [];
                foreach ($update_fields as $key => $val) {
                    if (in_array($key, $valid_columns)) {
                        if (is_bool($val)) {
                            $val = $val ? 1 : 0;
                        }
                        $filtered[$key] = $val;
                    }
                }
                $update_fields = $filtered;
            } else {
                foreach ($update_fields as $key => &$val) {
                    if (is_bool($val)) {
                        $val = $val ? 1 : 0;
                    }
                }
            }
            if (empty($update_fields)) {
                return;
            }
            $set_clause = [];
            $params = [':key_val' => $key_value];
            foreach ($update_fields as $field => $val) {
                if ($val === null) $val = '';
                if (is_bool($val)) $val = $val ? 1 : 0;
                $set_clause[] = "`" . $field . "` = :" . $field;
                $params[':' . $field] = $val;
            }
            
            $sql = "UPDATE `" . $table_name . "` SET " . implode(", ", $set_clause) . " WHERE `" . $key_field . "` = :key_val";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return;
        } catch (PDOException $e) {
            error_log("MySQL Update failed for " . $table_name . ": " . $e->getMessage());
        }
    }
}

function delete_db_record($table_name, $key_field, $key_value) {
    global $pdo;
    if ($pdo !== null) {
        try {
            $sql = "DELETE FROM `" . $table_name . "` WHERE `" . $key_field . "` = :key_val";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':key_val' => $key_value]);
            return;
        } catch (PDOException $e) {
            error_log("MySQL Delete failed: " . $e->getMessage());
        }
    }
}
?>
