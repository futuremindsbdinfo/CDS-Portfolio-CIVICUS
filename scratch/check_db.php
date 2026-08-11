<?php
require_once __DIR__ . '/../includes/db.php';
$db = Database::getConnection();

$tables = ['blogs', 'projects', 'gallery', 'publications', 'notices', 'gov_links', 'settings'];

foreach ($tables as $table) {
    echo "TABLE: $table\n";
    $stmt = $db->query("DESCRIBE $table");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo "  - " . $col['Field'] . "\n";
    }
    echo "\n";
}
