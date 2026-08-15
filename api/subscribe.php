<?php
// api/subscribe.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/sanitize.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$email = clean_input($_POST['email'] ?? '');
if (empty($email) || !validate_email($email)) {
    echo json_encode(['success' => false, 'message' => 'সঠিক ইমেইল ঠিকানা প্রদান করুন।']);
    exit;
}

$db = Database::getConnection();
if (!$db) {
    echo json_encode(['success' => false, 'message' => 'ডাটাবেজ সংযোগ পাওয়া যায়নি।']);
    exit;
}

try {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    // Check if already subscribed
    $checkStmt = $db->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        echo json_encode(['success' => true, 'message' => 'আপনি ইতিমধ্যেই আমাদের নিউজলেটারে সাবস্ক্রাইব করেছেন!']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO newsletter_subscribers (email, ip_address) VALUES (?, ?)");
    $stmt->execute([$email, $ip]);

    echo json_encode(['success' => true, 'message' => 'ধন্যবাদ! সফলভাবে নিউজলেটারে যুক্ত হয়েছেন।']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'ত্রুটি হয়েছে, অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।']);
}
