<?php
// includes/sanitize.php

function e($string) {
    if ($string === null) return '';
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

function clean_input($string) {
    if ($string === null) return '';
    $string = trim($string);
    $string = str_replace(chr(0), '', $string); // Strip null bytes
    return $string;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_phone($phone) {
    // Validates BD phone numbers like 01712345678, +8801712345678, 8801712345678
    return preg_match('/^(?:\+88|88)?01[3-9]\d{8}$/', $phone) === 1;
}

function get_bilingual_title($item) {
    $bn = $item['title_bn'] ?? $item['title'] ?? '';
    $en = $item['title_en'] ?? '';
    if (empty($en) || trim($en) === '') {
        $map = [
            'সিডিএস এর উদ্যোগে শিক্ষার্থীদের মাঝে গাছের চারা বিতরণ কর্মসূচি ২০২৬' => 'Tree Plantation Program 2026 for Students Organized by CDS',
            'বৃত্তি পরীক্ষা ২০২৬ ফলাফল প্রকাশ' => 'Scholarship Examination 2026 Results Published',
            'কার্যনির্বাহী পরিষদের বার্ষিক সাধারণ সভা' => 'Annual General Meeting of the Executive Committee',
            'দরিদ্র ও মেধাবী শিক্ষার্থীদের মাঝে শিক্ষা উপকরণ বিতরণ' => 'Distribution of Educational Materials to Underprivileged Students',
            'বিনামূল্যে রক্তদান ও স্বাস্থ্য পরীক্ষা ক্যাম্প' => 'Free Blood Donation and Health Checkup Camp',
            'সুশিক্ষা বিস্তার প্রকল্প' => 'Quality Education Expansion Project',
            'সুশাসন ও সামাজিক ন্যায়বিচার' => 'Good Governance and Social Justice Initiative',
            'কমিউনিটি স্বাস্থ্য সেবা কর্মসূচি' => 'Community Healthcare Service Program',
            'সচেতন নাগরিক নেতৃত্ব প্রশিক্ষণ' => 'Conscious Citizen Leadership Training'
        ];
        $en = $map[$bn] ?? 'CDS Announcement & Initiative';
    }
    return [
        'bn' => $bn,
        'en' => $en
    ];
}

function get_bilingual_content($item) {
    $bn = $item['content_bn'] ?? $item['description_bn'] ?? $item['content'] ?? $item['description'] ?? '';
    $en = $item['content_en'] ?? $item['description_en'] ?? '';
    if (empty($en) || trim($en) === '') {
        $en = 'Please check the official notice or contact CDS administration for complete details.';
    }
    return [
        'bn' => $bn,
        'en' => $en
    ];
}

