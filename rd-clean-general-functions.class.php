<?php

class RDCleanGeneralFunctions {

    private $options_general;

    public function __construct() {
        $this->options_general = get_option( 'rd_clean_general_option' );

        if(isset($this->options_general['rd_clean_general_login_logo'])) {
            add_action('login_head', array($this, 'rd_clean_general_login_logo'));
        }

        if(isset($this->options_general['rd_clean_general_desactive_rss'])) {
            add_action('do_feed', array($this, 'rd_clean_general_desactive_rss'), 1);
            add_action('do_feed_rdf', array($this, 'rd_clean_general_desactive_rss'), 1);
            add_action('do_feed_rss', array($this, 'rd_clean_general_desactive_rss'), 1);
            add_action('do_feed_rss2', array($this, 'rd_clean_general_desactive_rss'), 1);
            add_action('do_feed_atom', array($this, 'rd_clean_general_desactive_rss'), 1);
        }
    }

    public function rd_clean_general_login_logo() {
        echo '<style type="text/css">
            .login h1 a { 
                background-image: none, url('.$this->options_general['rd_clean_general_login_logo'].');
                background-size: 200px 100px;
                height: 100px;
                width: 200px;
            }
        </style>';
    }

    public function rd_clean_general_desactive_rss() {
        wp_die( __('Pas de flux disponible, vous pouvez visiter notre <a href="'. get_bloginfo('url') .'">Site</a> !'), RD_CLEAN_TEXT_DOMAIN);
    }

}

$rd_clean_general_functions = new RDCleanGeneralFunctions();