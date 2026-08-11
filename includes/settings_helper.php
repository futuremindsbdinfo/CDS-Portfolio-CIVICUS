<?php
// includes/settings_helper.php
require_once __DIR__ . '/db.php';

function get_all_settings() {
    static $settings = null;
    
    // Simple static cache to prevent multiple DB queries in a single request
    if ($settings === null) {
        $db = Database::getConnection();
        if ($db) {
            try {
                $rows = $db->query("SELECT setting_key, setting_value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
                $settings = $rows ? $rows : [];
            } catch (PDOException $e) {
                // Table might not exist yet
                $settings = [];
            }
        } else {
            $settings = [];
        }
    }
    
    return $settings;
}

function get_setting($key, $default = '') {
    $settings = get_all_settings();
    return (isset($settings[$key]) && trim($settings[$key]) !== '') ? $settings[$key] : $default;
}
