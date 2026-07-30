<?php
// includes/upload_handler.php

/**
 * Handle secure image upload with multiple validation layers.
 * 
 * @param array $file $_FILES entry (e.g. $_FILES['cover_image'])
 * @param string $target_dir Target directory inside 'uploads/' without trailing slash (e.g. 'projects')
 * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
 */
function handle_image_upload($file, $target_dir) {
    // 1. Error check
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload failed or no file uploaded.'];
    }

    // 2. Size limit (5MB)
    $max_size = 5 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'File size exceeds the 5MB limit.'];
    }

    // 3. Extension Whitelist & Double-Extension prevention
    // Get the *last* extension and force lowercase
    $parts = explode('.', $file['name']);
    $ext = strtolower(end($parts));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
    
    if (!in_array($ext, $allowed_exts)) {
        return ['success' => false, 'error' => 'Invalid file extension. Allowed: JPG, JPEG, PNG, WEBP.'];
    }

    // 4. True MIME Type Verification
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if (!$finfo) {
        return ['success' => false, 'error' => 'Server configuration error (finfo missing).'];
    }
    
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowed_mimes)) {
        return ['success' => false, 'error' => 'Invalid file content (MIME type mismatch).'];
    }

    // Map extensions securely from verified MIME (prevent jpg ext on png file)
    if ($mime === 'image/jpeg') {
        $final_ext = 'jpg';
    } elseif ($mime === 'image/png') {
        $final_ext = 'png';
    } elseif ($mime === 'image/webp') {
        $final_ext = 'webp';
    }

    // 5. Dimension Check (Min 100x100, Max 4000x4000)
    $image_info = getimagesize($file['tmp_name']);
    if ($image_info === false) {
        return ['success' => false, 'error' => 'File is not a valid image.'];
    }
    
    $width = $image_info[0];
    $height = $image_info[1];
    
    if ($width < 100 || $height < 100 || $width > 4000 || $height > 4000) {
        return ['success' => false, 'error' => 'Image dimensions must be between 100x100 and 4000x4000 pixels.'];
    }

    // 6. GD Re-encoding (Strips EXIF and malicious payloads)
    if (!extension_loaded('gd')) {
        // Graceful fallback if GD is missing (as per strict rules)
        return ['success' => false, 'error' => 'Image processing library (GD) is missing on the server.'];
    }

    $image = null;
    switch ($mime) {
        case 'image/jpeg':
            $image = @imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $image = @imagecreatefrompng($file['tmp_name']);
            break;
        case 'image/webp':
            $image = @imagecreatefromwebp($file['tmp_name']);
            break;
    }

    if (!$image) {
        return ['success' => false, 'error' => 'Failed to process image data. File might be corrupted.'];
    }

    // --- Automatic Resize & Compress Logic ---
    // Max dimension 1920x1080 (maintaining aspect ratio)
    $max_width = 1920;
    $max_height = 1080;
    
    $new_width = $width;
    $new_height = $height;
    
    if ($width > $max_width || $height > $max_height) {
        $ratio = min($max_width / $width, $max_height / $height);
        $new_width = (int) round($width * $ratio);
        $new_height = (int) round($height * $ratio);
    }
    
    // Create new true color image for resizing and re-encoding
    $new_image = imagecreatetruecolor($new_width, $new_height);
    
    // Preserve transparency for PNG/WEBP
    if ($mime === 'image/png' || $mime === 'image/webp') {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
        imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
    }
    
    // Resample the original image into the new canvas (this safely re-encodes pixel data)
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    imagedestroy($image); // Free original loaded memory
    $image = $new_image; // Swap to the resized canvas for saving

    // 7. Generate Random Secure Filename
    $random_name = bin2hex(random_bytes(16)) . '.' . $final_ext;
    
    // Ensure target directory exists
    $full_target_dir = __DIR__ . '/../uploads/' . $target_dir;
    if (!is_dir($full_target_dir)) {
        mkdir($full_target_dir, 0755, true);
    }
    
    $target_file_path = $full_target_dir . '/' . $random_name;

    // 8. Save the re-encoded (and resized) image
    $save_success = false;
    switch ($mime) {
        case 'image/jpeg':
            $save_success = imagejpeg($image, $target_file_path, 85); // 85% quality balance
            break;
        case 'image/png':
            $save_success = imagepng($image, $target_file_path, 8); // Compression level 8
            break;
        case 'image/webp':
            $save_success = imagewebp($image, $target_file_path, 85); // 85% quality balance
            break;
    }

    imagedestroy($image); // Free up memory

    if ($save_success) {
        return ['success' => true, 'filename' => $random_name];
    } else {
        return ['success' => false, 'error' => 'Failed to save the processed image.'];
    }
}

/**
 * Handle secure file upload (e.g., PDF)
 * 
 * @param array $file $_FILES entry
 * @param string $target_dir Target directory inside 'uploads/'
 * @param array $allowed_exts Allowed file extensions
 * @param int $max_size Maximum file size in bytes
 * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
 */
function handle_file_upload($file, $target_dir, $allowed_exts = ['pdf'], $max_size = 10 * 1024 * 1024) {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload failed or no file uploaded.'];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'File size exceeds the limit.'];
    }

    $parts = explode('.', $file['name']);
    $ext = strtolower(end($parts));
    
    if (!in_array($ext, $allowed_exts)) {
        return ['success' => false, 'error' => 'Invalid file extension. Allowed: ' . implode(', ', $allowed_exts)];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if (!$finfo) {
        return ['success' => false, 'error' => 'Server configuration error (finfo missing).'];
    }
    
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    // Simple mime check for PDF
    if ($ext === 'pdf' && $mime !== 'application/pdf') {
        return ['success' => false, 'error' => 'Invalid file content (MIME type mismatch for PDF).'];
    }

    $random_name = bin2hex(random_bytes(16)) . '.' . $ext;
    
    $full_target_dir = __DIR__ . '/../uploads/' . $target_dir;
    if (!is_dir($full_target_dir)) {
        mkdir($full_target_dir, 0755, true);
    }
    
    $target_file_path = $full_target_dir . '/' . $random_name;

    if (move_uploaded_file($file['tmp_name'], $target_file_path)) {
        return ['success' => true, 'filename' => $random_name];
    } else {
        return ['success' => false, 'error' => 'Failed to save the file.'];
    }
}
