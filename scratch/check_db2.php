<?php
require_once __DIR__ . '/../includes/db.php';
$db = Database::getConnection();

$tables = ['blogs', 'projects', 'gallery', 'publications', 'notices', 'gov_links', 'settings'];
$output = "";

foreach ($tables as $table) {
    $output .= "TABLE: $table\n";
    $stmt = $db->query("DESCRIBE $table");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        $output .= "  - " . $col['Field'] . "\n";
    }
    $output .= "\n";
}
file_put_contents('db_out.txt', $output);
echo "Done";
