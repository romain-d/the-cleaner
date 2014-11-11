<?php
/*
Plugin Name: RD Clean
Version: 1.0
Description: Plugin Clean for Back Office and other features
Author: Romain DORR
Author URI: https://romaindorr.fr
Text Domain: rd-clean
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
function rd_clean_load_files($dir, $files, $prefix = '', $suffix = '') {
    foreach ($files as $file) {
        if ( is_file($dir . $prefix . $file . $suffix . ".php") ) {
            require_once($dir . $prefix . $file . $suffix . ".php");
        }
    }   
}

function rd_clean_checked($option, $current = NULL, $value = 1) {
    if(isset($option[$current])) {
        $checked = checked($value, $option[$current], false);
    }
    else {
        $checked = '';
    }

    return $checked;
}

define('RD_CLEAN_PATH', dirname(__FILE__).'/');
define('RD_CLEAN_TEXT_DOMAIN', 'rd-clean');

rd_clean_load_files(RD_CLEAN_PATH, array('rd-clean', 'rd-clean-general', 'rd-clean-seo', 'rd-clean-deactivation'), '', '.class');

rd_clean_load_files(RD_CLEAN_PATH, array('rd-clean-general-functions', 'rd-clean-seo-functions'), '', '.class');