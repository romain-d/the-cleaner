<?php
/*
Plugin Name: WP Cleaner
Version: 1.0
Description: WP Cleaner is a plugin to clean the WordPress Back Office and do SEO and redundant functionality for our customers
Author: Romain DORR
Author URI: https://romaindorr.fr
Text Domain: wp-cleaner
Domain Path: /languages/
License: GPL v3
*/

function printr($data) {
    echo "<pre>";
        print_r($data);
    echo "</pre>";
}

// don't load directly
if ( !defined('ABSPATH') )
    die('-1');

// Function for easy load files
function wpcleaner_load_files($dir, $files, $prefix = '', $suffix = '') {
    foreach ($files as $file) {
        if ( is_file($dir . $prefix . $file . $suffix . ".php") ) {
            require_once($dir . $prefix . $file . $suffix . ".php");
        }
    }   
}

function wpcleaner_checked($option, $current = NULL, $value = 1) {
    $checked = (isset($option[$current])) ? checked($value, $option[$current], false) : '';
    return $checked;
}

function wpcleaner_is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

define('WPCLEANER_PATH', plugin_dir_path(__FILE__));
define('WPCLEANER_URL', plugin_dir_url(__FILE__));
define('WPCLEANER_TEXT_DOMAIN', 'wp-cleaner');

if(is_admin()) {
    wpcleaner_load_files(WPCLEANER_PATH, array('wpcleaner', 'wpcleaner-general', 'wpcleaner-seo', 'wpcleaner-deactivation', 'wpcleaner-role'), '', '.class');
}

wpcleaner_load_files(WPCLEANER_PATH, array('wpcleaner-general-functions', 'wpcleaner-seo-functions', 'wpcleaner-deactivation-functions', 'wpcleaner-role-functions'), '', '.class');
