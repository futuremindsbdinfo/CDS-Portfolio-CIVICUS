<?php
// includes/pdf_upload_handler.php

/**
 * Handle secure PDF upload
 * 
 * @param array $file $_FILES['input_name'] array
 * @param string $destination_dir Relative directory to move to (e.g. '../uploads/notices/')
 * @param int $max_size Maximum file size in bytes (default 5MB)
 * @return array ['success' => true/false, 'message' => '...', 'file_path' => '...']
 */
function handle_pdf_upload($file, $destination_dir = '../uploads/notices/', $max_size = 5242880) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Invalid parameters.'];
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return ['success' => false, 'message' => 'No file sent.'];
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['success' => false, 'message' => 'Exceeded filesize limit.'];
        default:
            return ['success' => false, 'message' => 'Unknown errors.'];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'Exceeded filesize limit (Max ' . ($max_size / 1024 / 1024) . 'MB).'];
    }

    // 1. Verify MIME type using finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);
    if ($mime_type !== 'application/pdf') {
        return ['success' => false, 'message' => 'Invalid file format. Only PDF files are allowed. (MIME: ' . htmlspecialchars($mime_type) . ')'];
    }

    // 2. Verify Magic Bytes (%PDF-)
    $handle = fopen($file['tmp_name'], 'rb');
    if ($handle) {
        $header = fread($handle, 5);
        fclose($handle);
        if ($header !== '%PDF-') {
            return ['success' => false, 'message' => 'File appears to be corrupt or fake (invalid magic bytes).'];
        }
    } else {
        return ['success' => false, 'message' => 'Failed to process file.'];
    }

    // 3. Extension Validation & No double extension
    // Extract everything after the last dot
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        return ['success' => false, 'message' => 'File must have a .pdf extension.'];
    }
    
    // Check for double extensions (e.g. file.php.pdf) to prevent Apache parsing bypasses
    $parts = explode('.', $file['name']);
    if (count($parts) > 2) {
        return ['success' => false, 'message' => 'Files with multiple extensions are not allowed.'];
    }

    // Generate safe random filename
    $random_bytes = random_bytes(16);
    $safe_filename = bin2hex($random_bytes) . '.pdf';
    
    // Ensure destination directory exists
    if (!is_dir($destination_dir)) {
        @mkdir($destination_dir, 0755, true);
    }

    $save_path = rtrim($destination_dir, '/') . '/' . $safe_filename;
    $db_path = str_replace('../', '', $save_path);

    if (move_uploaded_file($file['tmp_name'], $save_path)) {
        return [
            'success' => true,
            'file_path' => $db_path,
            'message' => 'File uploaded successfully.'
        ];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file.'];
    }
}
