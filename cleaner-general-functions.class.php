<?php

class CleanerGeneralFunctions {

    private $options_general;

    public function __construct() {
        $this->options_general = get_option('cleaner_general_option');

        if(cleaner_is_login_page() && !empty($this->options_general['cleaner_general_login_logo'])) {
            add_action('login_head', array($this, 'cleaner_general_login_logo'));
        }

        if(isset($this->options_general['cleaner_general_disable_rss'])) {
            add_action('do_feed', array($this, 'cleaner_general_disable_rss'), 1);
            add_action('do_feed_rdf', array($this, 'cleaner_general_disable_rss'), 1);
            add_action('do_feed_rss', array($this, 'cleaner_general_disable_rss'), 1);
            add_action('do_feed_rss2', array($this, 'cleaner_general_disable_rss'), 1);
            add_action('do_feed_atom', array($this, 'cleaner_general_disable_rss'), 1);
        }

        if(isset($this->options_general['cleaner_general_disable_auto_update'])) {
            add_filter('automatic_updater_disabled', '__return_true');
        }
    }

    public function cleaner_general_login_logo() {
        $css = '';

        if(isset($this->options_general['cleaner_general_login_logo_height'], $this->options_general['cleaner_general_login_logo_width'])) {
            $height = $this->options_general['cleaner_general_login_logo_height'];
            $width = $this->options_general['cleaner_general_login_logo_width'];

            $css = '
                background-size: '.$width.'px '.$height.'px;
                height: '.$height.'px;
                width: '.$width.'px;
            ';
        }
                
        echo '<style type="text/css">
            .login h1 a { 
                background-image: none, url('.$this->options_general['cleaner_general_login_logo'].');

                '.$css.'

                padding-bottom: 30px;
            }
        </style>';
    }

    public function cleaner_general_disable_rss() {
        wp_die( __('Pas de flux disponible, vous pouvez visiter notre <a href="'. get_bloginfo('url') .'">Site</a> !'), CLEANER_TEXT_DOMAIN);
    }

}

$cleaner_general_functions = new CleanerGeneralFunctions();