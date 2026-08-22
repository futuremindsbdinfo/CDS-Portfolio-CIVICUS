<?php
require_once __DIR__ . '/../includes/db.php';
 = Database::getConnection();
if (!) { die("DB connection failed."); }
 = [
    'site_email'      => 'citizendevelopmentsociety@gmail.com',
    'site_phone'      => '+8801886004317',
    'site_address'    => 'কেন্দ্রীয় কার্যালয়: ২৮/১, কাকরাইল, ঢাকা-১০০০, বাংলাদেশ',
    'site_address_en' => 'Head Office: 28/1, Kakrail, Dhaka-1000, Bangladesh',
    'social_facebook' => 'https://www.facebook.com/citizendevelopmentsociety',
];
 = ->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
foreach ( as  => ) {
     = ->execute([, ]);
    echo ( ? "OK" : "FAIL") . ": \n";
}
echo "Done.\n";
?>
