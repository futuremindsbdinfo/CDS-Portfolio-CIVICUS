<?php
require_once __DIR__ . '/../includes/db.php';

$db = Database::getConnection();

if ($db) {
    try {
        $db->exec("ALTER TABLE publications ADD COLUMN title_en VARCHAR(255) NULL AFTER title");
        $db->exec("ALTER TABLE publications ADD COLUMN description_en TEXT NULL AFTER description");
        echo "Table altered successfully.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "Columns already exist.\n";
        } else {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
