<?php
// download.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sanitize.php';

$form_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if ($form_id <= 0) {
    header("Location: forms.php");
    exit;
}

$db = Database::getConnection();
if ($db) {
    try {
        $stmt = $db->prepare("SELECT * FROM downloadable_forms WHERE id = ? AND is_active = 1");
        $stmt->execute([$form_id]);
        $form = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($form && !empty($form['file_path'])) {
            $filepath = __DIR__ . '/' . ltrim($form['file_path'], '/');
            if (file_exists($filepath)) {
                // Increment downloads count
                $incStmt = $db->prepare("UPDATE downloadable_forms SET downloads_count = downloads_count + 1 WHERE id = ?");
                $incStmt->execute([$form_id]);

                // Serve file
                $filename = basename($filepath);
                $mime_type = mime_content_type($filepath) ?: 'application/octet-stream';

                header('Content-Description: File Transfer');
                header('Content-Type: ' . $mime_type);
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                readfile($filepath);
                exit;
            }
        }
    } catch (PDOException $e) {
        // Fall through to redirect
    }
}

header("Location: forms.php");
exit;
