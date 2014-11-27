<?php
/*
Plugin Name: The Cleaner
Version: 1.0
Description: The Cleaner is a plugin to clean the WordPress Back Office and do SEO and redundant functionality for our customers
Author: Romain DORR
Author URI: https://romaindorr.fr
Text Domain: the-cleaner
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
function cleaner_load_files($dir, $files, $prefix = '', $suffix = '') {
    foreach ($files as $file) {
        if ( is_file($dir . $prefix . $file . $suffix . ".php") ) {
            require_once($dir . $prefix . $file . $suffix . ".php");
        }
    }   
}

function cleaner_checked($option, $current = NULL, $value = 1) {
    $checked = (isset($option[$current]) ? checked($value, $option[$current], false) : '';
    return $checked;
}

function cleaner_is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

define('CLEANER_PATH', plugin_dir_path(__FILE__));
define('CLEANER_URL', plugin_dir_url(__FILE__));
define('CLEANER_TEXT_DOMAIN', 'the-cleaner');

if(is_admin()) {
    cleaner_load_files(CLEANER_PATH, array('cleaner', 'cleaner-general', 'cleaner-seo', 'cleaner-deactivation', 'cleaner-user'), '', '.class');
}

cleaner_load_files(CLEANER_PATH, array('cleaner-general-functions', 'cleaner-seo-functions', 'cleaner-deactivation-functions', 'cleaner-user-functions'), '', '.class');
