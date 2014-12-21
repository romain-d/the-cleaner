<?php

class WPCleanerSEOFunctions {

    private $options_seo;

    public function __construct() {
        $this->options_seo = get_option('wpcleaner_seo_option');

        if(isset($this->options_seo['wpcleaner_seo_internal_ping'])) {
            add_action('pre_ping', array($this, 'wpcleaner_seo_internal_ping'));
        }

        if(isset($this->options_seo['wpcleaner_seo_show_terms'])) {
            add_filter('get_the_terms', array($this, 'wpcleaner_seo_show_terms_tag_limit'), 10, 1);
            add_filter('get_terms', array($this, 'wpcleaner_seo_show_terms_get_terms'));
            add_action('template_redirect', array($this, 'wpcleaner_seo_show_terms_redirect'));
        }

        if(isset($this->options_seo['wpcleaner_seo_comment_nofollow'])) {
            add_filter('comment_text', array($this, 'wpcleaner_seo_comment_nofollow_text'));
            remove_filter('pre_comment_content', 'wp_rel_nofollow', 15);
            add_filter('get_comment_author_link', array($this, 'wpcleaner_seo_comment_nofollow_link'));
        }

        if(is_admin() && isset($this->options_seo['wpcleaner_seo_editor_styles'])) {
            add_filter('tiny_mce_before_init', array($this, 'wpcleaner_seo_editor_styles'));
        }

        if(isset($this->options_seo['wpcleaner_seo_clean_head'])) {
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
            remove_action('wp_head', 'wp_dlmp_l10n_style' );
            remove_action('wp_head', 'rsd_link');
            remove_action('wp_head', 'wlwmanifest_link');
        }

        if(isset($this->options_seo['wpcleaner_seo_remove_short_link'])) {
            add_filter('pre_get_shortlink', '__return_empty_string');
        }

        if(isset($this->options_seo['wpcleaner_seo_excerpt_length'])) {
            add_filter('excerpt_length', array($this, 'wpcleaner_seo_excerpt_length'), 100);
        }
    }

    /*
        FUNCTIONS INTERNAL PING
     */
    public function wpcleaner_seo_internal_ping (&$links) {
        $home = get_option( 'home' );

        foreach ($links as $l => $link) {
            if (0 === strpos($link, $home))
                unset($links[$l]);
        }
    } 

    /*
        FUNCTIONS TERMS
     */
    public function wpcleaner_seo_show_terms_tag_limit ($terms) {
        foreach ($terms as $k => $tag) {
            if ($tag->taxonomy == 'post_tag') {
                if ($tag->count < 3)
                    unset($terms[$k]);
            }
        }
        return $terms;
    }

    public function wpcleaner_seo_show_terms_get_terms ($terms) {
        if (!is_admin()) {
            foreach ($terms as $k => $tag){
                if ($tag->taxonomy == 'post_tag') {
                    if ($tag->count < 3)
                        unset($terms[$k]);
                }
            }
        }
        return $terms;
    }

    public function wpcleaner_seo_show_terms_redirect () {
        if (is_tag()) {
            $term_id = get_query_var('tag_id');
            $term = get_term_by('id', $term_id, 'post_tag');
            $term_count = $term->count;

            $home_url = home_url();

            if ($term_count < 3)
                wp_redirect($home_url , '301');
        }
    }

    /*
        FUNCTIONS COMMENTS NOFOLLOW
     */   
    public function wpcleaner_seo_comment_nofollow_text ($text) {
        return str_replace('" rel="nofollow">', '">', $text);
    }

    public function wpcleaner_seo_comment_nofollow_link ($link) {
        return str_ireplace(' nofollow', '', $link);
    }

    /*
        FUNCTIONS EDITOR STYLES
     */
    function wpcleaner_seo_editor_styles ($initArray) {
        $initArray['block_formats'] = 'Paragraphe=p;Adresse=address;Titre 2=h2;Titre 3=h3;Titre 4=h4;Code=code';
        return $initArray;
    }

    /*
        FUNCTIONS EXCERPT LENGTH
     */
    function wpcleaner_seo_excerpt_length ($length) {
        if (isset($this->options_seo['wpcleaner_seo_excerpt_length_rss']) && is_feed()) {
            return $this->options_seo['wpcleaner_seo_excerpt_length_rss']; 
        }
        elseif (isset($this->options_seo['wpcleaner_seo_excerpt_length_tag']) && is_tag()) {
            return $this->options_seo['wpcleaner_seo_excerpt_length_tag'];
        }
        elseif (isset($this->options_seo['wpcleaner_seo_excerpt_length_default'])) {
            return $this->options_seo['wpcleaner_seo_excerpt_length_default'];
        }
        else
            return $length;
    }
}

$wpcleaner_seo_functions = new WPCleanerSEOFunctions();