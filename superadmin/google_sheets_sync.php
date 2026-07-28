<?php
/**
 * Al Foz Islamic Institute - Super Admin Google Sheets Sync
 */

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/permissions.php';

// Strictly require Super Admin role
require_role('Super Admin');

// Load sidebar
require_once __DIR__ . '/../includes/sidebar.php';

// Load the shared Google Sheets synchronization view template
require_once __DIR__ . '/../includes/sheets_sync_template.php';

// Load footer
require_once __DIR__ . '/../includes/footer.php';
?>
