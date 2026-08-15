<?php
// includes/mailer.php
// Robust Self-Contained SMTP & Mail Dispatcher for CDS Portfolio

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/settings_helper.php';

/**
 * Sends an email using either configured SMTP server or native PHP mail fallback.
 * 
 * @param string $to Recipient email address
 * @param string $subject Email subject
 * @param string $html_content HTML body of the email
 * @param string $to_name Optional recipient name
 * @param array $attachments Optional array of file paths
 * @return array ['success' => bool, 'message' => string]
 */
function send_cds_email($to, $subject, $html_content, $to_name = '', $attachments = []) {
    $to = trim($to);
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'অকার্যকর ইমেইল ঠিকানা।'];
    }

    $smtp_enabled = (bool)get_setting('smtp_enabled', 0);
    $from_email   = get_setting('smtp_from_email', 'no-reply@cds.org.bd');
    $from_name    = get_setting('smtp_from_name', 'Citizen Development Society (CDS)');
    $host         = get_setting('smtp_host', '');
    $port         = (int)get_setting('smtp_port', 465);
    $secure       = strtolower(get_setting('smtp_secure', 'ssl'));
    $username     = get_setting('smtp_user', '');
    $password     = get_setting('smtp_pass', '');

    // If SMTP is enabled and host is provided, use SMTP socket
    if ($smtp_enabled && !empty($host) && !empty($username)) {
        return smtp_send_socket($host, $port, $secure, $username, $password, $from_email, $from_name, $to, $to_name, $subject, $html_content);
    }

    // Otherwise fallback to standard mail() with proper MIME headers
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: =?UTF-8?B?" . base64_encode($from_name) . "?= <" . $from_email . ">\r\n";
    $headers .= "Reply-To: <" . $from_email . ">\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    $encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

    $sent = @mail($to, $encoded_subject, $html_content, $headers);
    if ($sent) {
        return ['success' => true, 'message' => 'ইমেইল সফলভাবে পাঠানো হয়েছে (PHP Mail)।'];
    }

    return ['success' => false, 'message' => 'ইমেইল পাঠানো সম্ভব হয়নি। অনুগ্রহ করে SMTP সেটিংস পরীক্ষা করুন।'];
}

/**
 * Lightweight Native Socket SMTP Sender supporting SSL/TLS & AUTH LOGIN
 */
function smtp_send_socket($host, $port, $secure, $user, $pass, $from_email, $from_name, $to_email, $to_name, $subject, $body) {
    $timeout = 15;
    $socket_host = ($secure === 'ssl') ? 'ssl://' . $host : $host;
    
    $socket = @fsockopen($socket_host, $port, $errno, $errstr, $timeout);
    if (!$socket) {
        return ['success' => false, 'message' => "SMTP সংযোগ ব্যর্থ ($errno): $errstr"];
    }

    stream_set_timeout($socket, $timeout);

    $read = function() use ($socket) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') break;
        }
        return $response;
    };

    $write = function($cmd) use ($socket) {
        fputs($socket, $cmd . "\r\n");
    };

    $initial = $read();
    if (substr($initial, 0, 3) !== '220') {
        fclose($socket);
        return ['success' => false, 'message' => "সার্ভার রেসপন্স ত্রুটি: $initial"];
    }

    $write("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
    $ehlo = $read();

    // STARTTLS if configured
    if ($secure === 'tls') {
        $write("STARTTLS");
        $tls_resp = $read();
        if (substr($tls_resp, 0, 3) === '220') {
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($socket);
                return ['success' => false, 'message' => 'TLS ক্রিপ্টোগ্রাফি সক্রিয় করা যায়নি।'];
            }
            $write("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
            $read();
        }
    }

    // Authenticate
    $write("AUTH LOGIN");
    $auth_resp = $read();
    if (substr($auth_resp, 0, 3) !== '334') {
        fclose($socket);
        return ['success' => false, 'message' => "AUTH LOGIN প্রত্যাখ্যান করেছে: $auth_resp"];
    }

    $write(base64_encode($user));
    $user_resp = $read();
    if (substr($user_resp, 0, 3) !== '334') {
        fclose($socket);
        return ['success' => false, 'message' => "ইউজারনেম প্রত্যাখ্যান করেছে: $user_resp"];
    }

    $write(base64_encode($pass));
    $pass_resp = $read();
    if (substr($pass_resp, 0, 3) !== '235') {
        fclose($socket);
        return ['success' => false, 'message' => "পাসওয়ার্ড সঠিক নয় বা প্রমাণীকরণ ব্যর্থ: $pass_resp"];
    }

    // Sender
    $write("MAIL FROM: <$from_email>");
    $mail_from_resp = $read();
    if (substr($mail_from_resp, 0, 3) !== '250') {
        fclose($socket);
        return ['success' => false, 'message' => "Sender প্রত্যাখ্যান: $mail_from_resp"];
    }

    // Recipient
    $write("RCPT TO: <$to_email>");
    $rcpt_resp = $read();
    if (substr($rcpt_resp, 0, 3) !== '250') {
        fclose($socket);
        return ['success' => false, 'message' => "Recipient প্রত্যাখ্যান: $rcpt_resp"];
    }

    // Data
    $write("DATA");
    $data_resp = $read();
    if (substr($data_resp, 0, 3) !== '354') {
        fclose($socket);
        return ['success' => false, 'message' => "DATA কমান্ড ব্যর্থ: $data_resp"];
    }

    // Headers & Body
    $encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    $encoded_from = "=?UTF-8?B?" . base64_encode($from_name) . "?= <$from_email>";
    $encoded_to = !empty($to_name) ? "=?UTF-8?B?" . base64_encode($to_name) . "?= <$to_email>" : "<$to_email>";

    $message_headers = [
        "MIME-Version: 1.0",
        "Content-Type: text/html; charset=UTF-8",
        "Content-Transfer-Encoding: base64",
        "From: $encoded_from",
        "To: $encoded_to",
        "Reply-To: <$from_email>",
        "Subject: $encoded_subject",
        "Date: " . date('r'),
        "X-Mailer: CDS Civil Society Mailer 2.0"
    ];

    $email_payload = implode("\r\n", $message_headers) . "\r\n\r\n" . chunk_split(base64_encode($body)) . "\r\n.";
    $write($email_payload);
    $sent_resp = $read();

    $write("QUIT");
    $read();
    fclose($socket);

    if (substr($sent_resp, 0, 3) === '250') {
        return ['success' => true, 'message' => 'ইমেইল সফলভাবে ইনবক্সে পাঠানো হয়েছে!'];
    }

    return ['success' => false, 'message' => "ইমেইল পাঠাতে ব্যর্থ: $sent_resp"];
}

/**
 * Generate secure HMAC unsubscribe token for an email address
 */
function get_unsubscribe_token($email) {
    $secret = 'CDS_SECRET_UNSUB_SALT_2026_@!';
    return hash_hmac('sha256', strtolower(trim($email)), $secret);
}

/**
 * Helper to build Unsubscribe URL
 */
function get_unsubscribe_url($email) {
    $token = get_unsubscribe_token($email);
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'cds.fuminds.com');
    return $base_url . "/unsubscribe.php?email=" . urlencode($email) . "&token=" . $token;
}

/**
 * Sends standard Welcome Email to a newly registered subscriber
 */
function send_newsletter_welcome_email($subscriber_email) {
    $template_file = __DIR__ . '/../templates/email/welcome_newsletter.html';
    if (!file_exists($template_file)) {
        return false;
    }

    $template = file_get_contents($template_file);
    $unsub_url = get_unsubscribe_url($subscriber_email);
    $site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'cds.fuminds.com');

    $search = [
        '{{EMAIL}}',
        '{{UNSUBSCRIBE_URL}}',
        '{{SITE_URL}}',
        '{{YEAR}}'
    ];

    $replace = [
        htmlspecialchars($subscriber_email),
        $unsub_url,
        $site_url,
        date('Y')
    ];

    $body = str_replace($search, $replace, $template);
    $subject = 'সিডিএস নিউজলেটারে আপনাকে স্বাগতম! | Welcome to CDS';

    return send_cds_email($subscriber_email, $subject, $body);
}
