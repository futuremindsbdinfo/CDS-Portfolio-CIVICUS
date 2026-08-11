<?php
require 'includes/db.php';
$db = Database::getConnection();
$rows = $db->query('SELECT * FROM gov_links')->fetchAll();
echo "Total rows: " . count($rows) . "\n";
foreach($rows as $r) {
    $title = isset($r['title']) ? addslashes($r['title']) : '';
    $url = isset($r['url']) ? addslashes($r['url']) : '';
    $category = isset($r['category']) ? addslashes($r['category']) : '';
    $logo_url = isset($r['logo_url']) ? addslashes($r['logo_url']) : '';
    echo "INSERT INTO gov_links (title, url, category, logo_url) VALUES ('$title', '$url', '$category', '$logo_url');\n";
}
