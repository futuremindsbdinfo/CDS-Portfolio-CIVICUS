<?php
// scripts/seed_dummy_data.php

require_once __DIR__ . '/../includes/db.php';

try {
    $db = Database::getConnection();
    if (!$db) {
        die("Database connection failed.");
    }

    echo "Starting dummy data seed...\n";

    // 1. Seed Projects
    $projects = [
        [
            'title_bn' => 'বিনামূল্যে চক্ষু শিবির',
            'title_en' => 'Free Eye Camp',
            'description_bn' => 'দরিদ্র ও অসহায় মানুষদের জন্য বিনামূল্যে চক্ষু পরীক্ষা ও ছানি অপারেশন।',
            'description_en' => 'Free eye checkup and cataract surgery for poor and helpless people.',
            'status' => 'completed',
            'cover_image' => 'non_existent_placeholder1.jpg',
            'start_date' => '2025-01-10',
            'end_date' => '2025-01-15'
        ],
        [
            'title_bn' => 'শীতবস্ত্র বিতরণ ২০২৫',
            'title_en' => 'Winter Clothes Distribution 2025',
            'description_bn' => 'উত্তরাঞ্চলের শীতার্ত মানুষদের মাঝে শীতবস্ত্র ও কম্বল বিতরণ কর্মসূচি।',
            'description_en' => 'Winter clothes and blanket distribution program among the cold-stricken people of the northern region.',
            'status' => 'completed',
            'cover_image' => 'non_existent_placeholder2.jpg',
            'start_date' => '2025-12-01',
            'end_date' => '2025-12-31'
        ],
        [
            'title_bn' => 'বন্যার্তদের ত্রাণ সহায়তা',
            'title_en' => 'Flood Relief Support',
            'description_bn' => 'সিলেটের বন্যাকবলিত এলাকায় জরুরি ত্রাণ ও ঔষধ সামগ্রী বিতরণ।',
            'description_en' => 'Emergency relief and medicine distribution in the flood-affected areas of Sylhet.',
            'status' => 'ongoing',
            'cover_image' => 'non_existent_placeholder3.jpg',
            'start_date' => '2026-06-01',
            'end_date' => null
        ],
        [
            'title_bn' => 'বৃক্ষরোপণ কর্মসূচি',
            'title_en' => 'Tree Plantation Program',
            'description_bn' => 'পরিবেশ রক্ষায় দেশব্যাপী ৫০০০ বৃক্ষরোপণ কর্মসূচি।',
            'description_en' => '5000 tree plantation program nationwide to protect the environment.',
            'status' => 'ongoing',
            'cover_image' => 'non_existent_placeholder4.jpg',
            'start_date' => '2026-05-15',
            'end_date' => null
        ],
        [
            'title_bn' => 'সুবিধাবঞ্চিত শিশুদের শিক্ষা উপকরণ বিতরণ',
            'title_en' => 'Education Materials for Underprivileged Children',
            'description_bn' => 'বস্তির শিশুদের মাঝে বিনামূল্যে খাতা, কলম ও বই বিতরণ।',
            'description_en' => 'Free distribution of notebooks, pens, and books among slum children.',
            'status' => 'completed',
            'cover_image' => 'non_existent_placeholder5.jpg',
            'start_date' => '2025-08-10',
            'end_date' => '2025-08-20'
        ]
    ];

    $stmt = $db->prepare("INSERT INTO projects (title_bn, title_en, description_bn, description_en, status, cover_image, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($projects as $p) {
        $stmt->execute([$p['title_bn'], $p['title_en'], $p['description_bn'], $p['description_en'], $p['status'], $p['cover_image'], $p['start_date'], $p['end_date']]);
    }
    echo "Inserted projects.\n";

    // 2. Seed Gallery (Requires project IDs)
    $project_ids_stmt = $db->query("SELECT id FROM projects LIMIT 5");
    $project_ids = $project_ids_stmt->fetchAll(PDO::FETCH_COLUMN);

    $gallery = [
        ['project_id' => $project_ids[0] ?? null, 'image_path' => 'dummy_gal_1.jpg', 'caption_bn' => 'চক্ষু শিবিরের প্রথম দিন', 'caption_en' => 'First day of Eye Camp', 'event_date' => '2025-01-10'],
        ['project_id' => $project_ids[0] ?? null, 'image_path' => 'dummy_gal_2.jpg', 'caption_bn' => 'রোগীদের চিকিৎসা প্রদান', 'caption_en' => 'Treating patients', 'event_date' => '2025-01-11'],
        ['project_id' => $project_ids[1] ?? null, 'image_path' => 'dummy_gal_3.jpg', 'caption_bn' => 'কম্বল বিতরণ', 'caption_en' => 'Blanket distribution', 'event_date' => '2025-12-05'],
        ['project_id' => $project_ids[2] ?? null, 'image_path' => 'dummy_gal_4.jpg', 'caption_bn' => 'ত্রাণ সামগ্রী প্রস্তুত করা হচ্ছে', 'caption_en' => 'Preparing relief materials', 'event_date' => '2026-06-05'],
        ['project_id' => $project_ids[3] ?? null, 'image_path' => 'dummy_gal_5.jpg', 'caption_bn' => 'বৃক্ষরোপণ করছেন ভলান্টিয়াররা', 'caption_en' => 'Volunteers planting trees', 'event_date' => '2026-05-16'],
        ['project_id' => $project_ids[4] ?? null, 'image_path' => 'dummy_gal_6.jpg', 'caption_bn' => 'শিশুদের হাতে নতুন বই', 'caption_en' => 'New books in hands of children', 'event_date' => '2025-08-15'],
        ['project_id' => null, 'image_path' => 'dummy_gal_7.jpg', 'caption_bn' => 'আমাদের বার্ষিক সাধারণ সভা', 'caption_en' => 'Our Annual General Meeting', 'event_date' => '2025-11-20'],
        ['project_id' => null, 'image_path' => 'dummy_gal_8.jpg', 'caption_bn' => 'স্বেচ্ছাসেবক সম্মাননা প্রদান', 'caption_en' => 'Volunteer Award Ceremony', 'event_date' => '2025-11-20']
    ];

    $stmt = $db->prepare("INSERT INTO gallery (project_id, image_path, caption_bn, caption_en, event_date) VALUES (?, ?, ?, ?, ?)");
    foreach ($gallery as $g) {
        $stmt->execute([$g['project_id'], $g['image_path'], $g['caption_bn'], $g['caption_en'], $g['event_date']]);
    }
    echo "Inserted gallery items.\n";

    // 3. Seed Notices
    $notices = [
        ['title_bn' => 'আগামী মাসের ভলান্টিয়ার মিটিং', 'title_en' => 'Volunteer Meeting Next Month', 'content_bn' => 'সকল সদস্যকে আগামী ৫ই তারিখ বিকাল ৪টায় উপস্থিত থাকার জন্য অনুরোধ করা হলো।', 'content_en' => 'All members are requested to be present on the 5th at 4 PM.', 'file_path' => null],
        ['title_bn' => 'বন্যার্তদের জন্য ফান্ড রেইজিং', 'title_en' => 'Fundraising for Flood Victims', 'content_bn' => 'জরুরি ভিত্তিতে আমরা একটি ফান্ড রেইজিং ক্যাম্পেইন শুরু করেছি। বিস্তারিত জানতে পিডিএফ দেখুন।', 'content_en' => 'We have started an urgent fundraising campaign. See PDF for details.', 'file_path' => 'dummy_notice_flood.pdf'],
        ['title_bn' => 'নতুন কার্যনির্বাহী কমিটি গঠন', 'title_en' => 'Formation of New Executive Committee', 'content_bn' => '২০২৬-২৭ সেশনের জন্য নতুন কমিটি ঘোষণা করা হয়েছে।', 'content_en' => 'New committee has been announced for the 2026-27 session.', 'file_path' => 'dummy_committee.pdf'],
        ['title_bn' => 'অফিস স্থানান্তরের বিজ্ঞপ্তি', 'title_en' => 'Office Relocation Notice', 'content_bn' => 'আমাদের প্রধান কার্যালয় নতুন ঠিকানায় স্থানান্তরিত হয়েছে।', 'content_en' => 'Our head office has been relocated to a new address.', 'file_path' => null],
        ['title_bn' => 'রমজান মাসের খাদ্য বিতরণ', 'title_en' => 'Food Distribution in Ramadan', 'content_bn' => 'রমজান মাস উপলক্ষে বিশেষ খাদ্য বিতরণ কর্মসূচি গ্রহণ করা হয়েছে।', 'content_en' => 'Special food distribution program has been taken for Ramadan.', 'file_path' => 'dummy_ramadan.pdf']
    ];

    $stmt = $db->prepare("INSERT INTO notices (title_bn, title_en, content_bn, content_en, file_path) VALUES (?, ?, ?, ?, ?)");
    foreach ($notices as $n) {
        $stmt->execute([$n['title_bn'], $n['title_en'], $n['content_bn'], $n['content_en'], $n['file_path']]);
    }
    echo "Inserted notices.\n";

    // 4. Seed Contact Messages
    $messages = [
        ['name' => 'আব্দুর রহমান', 'email' => 'abdur@example.com', 'phone' => '01711000001', 'subject' => 'স্বেচ্ছাসেবক হিসেবে যোগ দিতে চাই', 'message' => 'আমি আপনাদের সংস্থায় একজন স্বেচ্ছাসেবক হিসেবে কাজ করতে আগ্রহী। কীভাবে যুক্ত হতে পারি?', 'is_read' => 0],
        ['name' => 'করিম মিয়া', 'email' => 'karim@example.com', 'phone' => '01811000002', 'subject' => 'ত্রাণ সহায়তার আবেদন', 'message' => 'আমাদের গ্রামে বন্যা পরিস্থিতির অবনতি হয়েছে। আমরা জরুরি ত্রাণ সহায়তা কামনা করছি।', 'is_read' => 1],
        ['name' => 'সাদিয়া ইসলাম', 'email' => 'sadia@example.com', 'phone' => '01911000003', 'subject' => 'ডোনেশন সংক্রান্ত তথ্য', 'message' => 'আমি আপনাদের ফান্ডে কিছু টাকা অনুদান দিতে চাই। ব্যাংক ডিটেইলস জানালে সুবিধা হতো।', 'is_read' => 0]
    ];

    $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, is_read) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($messages as $m) {
        $stmt->execute([$m['name'], $m['email'], $m['phone'], $m['subject'], $m['message'], $m['is_read']]);
    }
    echo "Inserted contact messages.\n";

    // 5. Seed Donation Interests
    $donations = [
        ['donor_name' => 'শফিকুল ইসলাম', 'donor_phone' => '01700112233', 'donor_email' => 'shafiq@example.com', 'donation_amount' => 5000.00, 'payment_method' => 'bKash', 'transaction_id' => 'TXN12345678', 'status' => 'pending'],
        ['donor_name' => 'মাহমুদুল হাসান', 'donor_phone' => '01800112244', 'donor_email' => 'mahmud@example.com', 'donation_amount' => 10000.00, 'payment_method' => 'Bank Transfer', 'transaction_id' => 'BNK98765432', 'status' => 'verified'],
        ['donor_name' => 'রহিমা খাতুন', 'donor_phone' => '01900112255', 'donor_email' => null, 'donation_amount' => 2000.00, 'payment_method' => 'Nagad', 'transaction_id' => 'NGD55667788', 'status' => 'rejected'],
        ['donor_name' => 'তানভীর আহমেদ', 'donor_phone' => '01600112266', 'donor_email' => 'tanvir@example.com', 'donation_amount' => 1500.00, 'payment_method' => 'Rocket', 'transaction_id' => 'RCK11223344', 'status' => 'verified']
    ];

    $stmt = $db->prepare("INSERT INTO donation_interests (donor_name, donor_phone, donor_email, donation_amount, payment_method, transaction_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($donations as $d) {
        $stmt->execute([$d['donor_name'], $d['donor_phone'], $d['donor_email'], $d['donation_amount'], $d['payment_method'], $d['transaction_id'], $d['status']]);
    }
    echo "Inserted donation interests.\n";

    echo "Dummy data seeding completed successfully.\n";

} catch (Exception $e) {
    die("Error seeding data: " . $e->getMessage());
}
