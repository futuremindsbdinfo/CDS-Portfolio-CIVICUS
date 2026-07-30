<?php
error_reporting(0);
require_once __DIR__ . '/../includes/auth.php';
init_secure_session();
require_admin_login();

// Allowed origins to prevent CSRF via CORS
$accepted_origins = array("http://localhost", "http://localhost:8000", "http://127.0.0.1:8000", "https://cds.fuminds.com");

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // same-origin requests won't set an origin. If the origin is set, it must be valid.
    if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    } else {
        header("HTTP/1.1 403 Origin Denied");
        return;
    }
}

// Don't attempt to process the upload on an OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    return;
}

reset ($_FILES);
$temp = current($_FILES);
if (is_uploaded_file($temp['tmp_name'])){
    // Sanitize file name
    $file_extension = strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION));
    
    // Verify extension
    if (!in_array($file_extension, array("gif", "jpg", "jpeg", "png", "webp"))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }
    
    // Generate unique name
    $file_name = uniqid('img_', true) . '.' . $file_extension;
    $filetowrite = __DIR__ . '/../uploads/blogs/' . $file_name;
    
    // Ensure directory exists
    $dir = dirname($filetowrite);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    if(move_uploaded_file($temp['tmp_name'], $filetowrite)){
        // Determine the base URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://";
        $uri_dir = dirname($_SERVER['REQUEST_URI']);
        $uri_dir = str_replace('\\', '/', $uri_dir);
        $basepath = preg_replace('#/admin$#', '', $uri_dir);
        $baseurl = $protocol . $_SERVER['HTTP_HOST'] . $basepath;
        
        // Respond to the successful upload with JSON.
        header('Content-Type: application/json');
        echo json_encode(array('location' => $baseurl . '/uploads/blogs/' . $file_name));
    } else {
        header("HTTP/1.1 500 Server Error");
    }
} else {
    header("HTTP/1.1 500 Server Error");
}
?>
