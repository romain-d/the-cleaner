<?php

class RDCleanSEOFunctions {

    private $options_seo;

    public function __construct() {
        $this->options_seo = get_option( 'rd_clean_seo_option' );

        if(isset($this->options_seo['rd_clean_seo_internal_ping'])) {
            add_action('pre_ping', array($this, 'rd_clean_seo_internal_ping'));
        }

        if(isset($this->options_seo['rd_clean_seo_author_description'])) {
            add_action('show_user_profile', array($this, 'rd_clean_seo_author_description_field'));
            add_action('edit_user_profile', array($this, 'rd_clean_seo_author_description_field'));
            add_action('personal_options_update', array($this, 'rd_clean_seo_author_description_save'));
            add_action('edit_user_profile_update', array($this, 'rd_clean_seo_author_description_save'));
        }

        if(isset($this->options_seo['rd_clean_seo_show_terms'])) {
            add_filter('get_the_terms', array($this, 'rd_clean_seo_show_terms_tag_limit'), 10, 1);
            add_filter('get_terms', array($this, 'rd_clean_seo_show_terms_get_terms'));
            add_action('template_redirect', array($this, 'rd_clean_seo_show_terms_redirect'));
        }

        if(isset($this->options_seo['rd_clean_seo_comment_nofollow'])) {
            add_filter('comment_text', array($this, 'rd_clean_seo_comment_nofollow_text'));
            remove_filter('pre_comment_content', 'wp_rel_nofollow', 15);
            add_filter('get_comment_author_link', array($this, 'rd_clean_seo_comment_nofollow_link'));
        }

        if(isset($this->options_seo['rd_clean_seo_editor_styles'])) {
            add_filter('tiny_mce_before_init', array($this, 'rd_clean_seo_editor_styles'));
        }

        if(isset($this->options_seo['rd_clean_seo_clean_head'])) {
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
            remove_action('wp_head', 'wp_dlmp_l10n_style' );
            remove_action('wp_head', 'rsd_link');
            remove_action('wp_head', 'wlwmanifest_link');
        }

        if(isset($this->options_seo['rd_clean_seo_excerpt_length'])) {
            add_filter('excerpt_length', 'rd_clean_seo_excerpt_length', 100);
        }
    }

    /*
        FUNCTIONS INTERNAL PING
     */
    public function rd_clean_seo_internal_ping (&$links) {
        $home = get_option( 'home' );

        foreach ($links as $l => $link) {
            if (0 === strpos($link, $home))
                unset($links[$l]);
        }
    }

    /*
        FUNCTIONS AUTHOR DESCRIPTION
     */
    public function rd_clean_seo_author_description_field ($user) { ?>
        <h3><?php _e('Informations complémentaires sur le profil', RD_CLEAN_TEXT_DOMAIN); ?></h3>

        <table class="form-table">
            <tr>
                <th>
                    <label for="shortdesc"><?php _e('Description détaillée de l\'auteur', RD_CLEAN_TEXT_DOMAIN); ?></label>
                </th>
                <td>
                    <textarea name="shortdesc" id="shortdesc" cols="8" rows="3"><?php echo esc_textarea(get_the_author_meta('shortdesc', $user->ID) ); ?></textarea>
                    <br />
                </td>
            </tr>
        </table>
        <?php 
    } 

    public function rd_clean_seo_author_description_save ($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        update_usermeta($user_id, 'shortdesc', $_POST['shortdesc']);
    }

    /*
        FUNCTIONS TERMS
     */
    public function rd_clean_seo_show_terms_tag_limit ($terms) {
        foreach ($terms as $k => $tag) {
            if ($tag->taxonomy == 'post_tag') {
                if ($tag->count < 3)
                    unset($terms[$k]);
            }
        }
        return $terms;
    }

    public function rd_clean_seo_show_terms_get_terms ($terms) {
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

    public function rd_clean_seo_show_terms_redirect () {
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
    public function rd_clean_seo_comment_nofollow_text ($text) {
        return str_replace('" rel="nofollow">', '">', $text);
    }

    public function rd_clean_seo_comment_nofollow_link ($link) {
        return str_ireplace(' nofollow', '', $link);
    }

    /*
        FUNCTIONS EDITOR STYLES
     */
    function rd_clean_seo_editor_styles ($initArray) {
        $initArray['block_formats'] = 'Paragraphe=p;Adresse=address;Titre 2=h2;Titre 3=h3;Titre 4=h4;Code=code';
        return $initArray;
    }

    /*
        FUNCTIONS EXCERPT LENGTH
     */
    function rd_clean_seo_excerpt_length($length) {
        if (isset($this->options_seo['rd_clean_seo_excerpt_length_rss']) && is_feed()) {
            return $this->options_seo['rd_clean_seo_excerpt_length_rss']; 
        }
        elseif (isset($this->options_seo['rd_clean_seo_excerpt_length_tag']) && is_tag()) {
            return $this->options_seo['rd_clean_seo_excerpt_length_tag'];
        }
        elseif (isset($this->options_seo['rd_clean_seo_excerpt_length_default'])) {
            return $this->options_seo['rd_clean_seo_excerpt_length_default'];
        }
        else
            return $length;
    }
}

$rd_clean_seo_functions = new RDCleanSEOFunctions();