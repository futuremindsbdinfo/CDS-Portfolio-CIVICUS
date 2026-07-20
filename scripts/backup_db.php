<?php
// scripts/backup_db.php
require_once __DIR__ . '/../config/database.php';

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

$config = require __DIR__ . '/../config/database.php';

$host = $config['host'];
$dbname = $config['dbname'];
$username = $config['username'];
$password = $config['password'];

$backup_dir = __DIR__ . '/../logs/backups';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

$timestamp = date('Y-m-d_H-i-s');
$backup_file = $backup_dir . "/{$dbname}_backup_{$timestamp}.sql";

// Create mysqldump command
// Note: Depending on the OS, the path to mysqldump might need to be absolute if not in PATH.
$mysqldump_cmd = "mysqldump --host={$host} --user={$username}";
if (!empty($password)) {
    $mysqldump_cmd .= " --password=\"{$password}\"";
}
$mysqldump_cmd .= " {$dbname} > \"{$backup_file}\"";

echo "Starting database backup...\n";
exec($mysqldump_cmd, $output, $return_var);

if ($return_var === 0) {
    echo "Backup created successfully at:\n{$backup_file}\n";
} else {
    echo "Error creating backup. Exit code: {$return_var}\n";
}
