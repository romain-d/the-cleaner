<?php

class WPCleanerDeactivationFunctions {

    private $options_deactivation;

    public function __construct() {
        $this->options_deactivation = get_option( 'wpcleaner_deactivation_option' );

        // printr($this->options_deactivation);

        // $post_types = get_post_types(array('public' => true), 'objects');

        // foreach ($post_types as $post_type) {
        //     global $wp_taxonomies; 
        //     $label = $post_type->label;
        //     $name = $post_type->name;

        //     $tag = 0;
        //     $category = 0;

        //     foreach ($wp_taxonomies as $taxonomy) {
        //         if($taxonomy->object_type[0] == $name) {
        //             if(true == $taxonomy->hierarchical) {
        //                 $category++;
        //             }
        //             else {
        //                 $tag ++;
        //             }
        //         }
        //     }
        // }
        
        /*
            HOOKS POSTS
         */
        
        if(isset($this->options_deactivation['wpcleaner_deactivation_post_disable'])) {
            add_action('admin_menu', array($this, 'wpcleaner_deactivation_post_disable_remove_menu'));
            add_action('admin_init', array($this, 'wpcleaner_deactivation_post_disable_redirect'));
            add_action('admin_bar_menu', array($this, 'wpcleaner_deactivation_post_disable_admin_bar'), 999);
            add_action('wp_dashboard_setup', array($this, 'wpcleaner_deactivation_post_disable_dashboard'));
        }
         
        if(isset($this->options_deactivation['wpcleaner_deactivation_post_comment'])) {
            add_filter('comments_open', array($this, 'wpcleaner_deactivation_post_comment_close_front'), 20, 2);
            add_filter('pings_open', array($this, 'wpcleaner_deactivation_post_comment_close_front'), 20, 2);
            add_action('admin_init', array($this, 'wpcleaner_deactivation_post_comment_remove_support'));
            add_filter('comments_array', array($this, 'wpcleaner_deactivation_post_comment_hide_existing'), 10, 2);
            add_filter('get_comments_number', array($this, 'wpcleaner_deactivation_post_comment_comments_number'), 10, 2);
        }

        /*
            HOOKS PAGES
         */
         
        if(isset($this->options_deactivation['wpcleaner_deactivation_page_comment'])) {
            add_filter('comments_open', array($this, 'wpcleaner_deactivation_page_comment_close_front'), 20, 2);
            add_filter('pings_open', array($this, 'wpcleaner_deactivation_page_comment_close_front'), 20, 2);
            add_action('admin_init', array($this, 'wpcleaner_deactivation_page_comment_remove_support'));
            add_filter('comments_array', array($this, 'wpcleaner_deactivation_page_comment_hide_existing'), 10, 2);
        }

        /*
            HOOKS COMMENTS
         */

        if(isset($this->options_deactivation['wpcleaner_deactivation_comment_disable'])) {
            add_action('admin_menu', array($this, 'wpcleaner_deactivation_comment_remove_menu'));
            add_action('admin_init', array($this, 'wpcleaner_deactivation_comment_remove_support'));
            add_filter('comments_open', array($this, 'wpcleaner_deactivation_comment_close_front'), 20, 2);
            add_filter('pings_open', array($this, 'wpcleaner_deactivation_comment_close_front'), 20, 2);
            add_filter('comments_array', array($this, 'wpcleaner_deactivation_comment_hide_existing'), 10, 2);
            add_filter('get_comments_number', array($this, 'wpcleaner_deactivation_comment_comments_number'), 10);
            add_action('admin_init', array($this, 'wpcleaner_deactivation_comment_redirect'));
            add_action('admin_init', array($this, 'wpcleaner_deactivation_comment_dashboard'));
            add_action('admin_bar_menu', array($this, 'wpcleaner_deactivation_comment_admin_bar'), 999);
            add_action('widgets_init', array($this, 'wpcleaner_deactivation_comment_widget_recent_comments'));
        }
    }

    /*
        FUNCTIONS POSTS
     */
    
    /* DISABLE POSTS */
    public function wpcleaner_deactivation_post_disable_remove_menu() {
        remove_menu_page('edit.php');
    }

    public function wpcleaner_deactivation_post_disable_redirect() {
        global $pagenow;
        
        $posts_page = array(
            'post-new.php', 
            'edit.php', 
            'edit-tags.php?taxonomy=category', 
            'edit-tags.php?taxonomy=post_tag'
        );

        if(in_array($pagenow, $posts_page)) {
            wp_redirect(admin_url()); exit;
        }
    }

    public function wpcleaner_deactivation_post_disable_admin_bar($wp_admin_bar) {
        if (is_admin_bar_showing()) {
            $wp_admin_bar->remove_node('new-post');
        }
    }

    public function wpcleaner_deactivation_post_disable_dashboard() {
        global $wp_meta_boxes;
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    }
    
    /* COMMENTS */
    public function wpcleaner_deactivation_post_comment_close_front($open, $post_id) {
        $post = get_post($post_id);
        if ('post' == $post->post_type)
            $open = false;
        return $open;
    }

    public function wpcleaner_deactivation_post_comment_remove_support() {
        remove_post_type_support('post', 'comments');
        remove_post_type_support('post', 'trackbacks');
    }

    public function wpcleaner_deactivation_post_comment_hide_existing($comments, $post_id) {
        $post = get_post($post_id);
        if ('post' == $post->post_type)
            $comments = array();
        return $comments;
    }

    public function wpcleaner_deactivation_post_comment_comments_number($count, $post_id) {
        $post = get_post($post_id);
        if ('post' == $post->post_type)
            $count = 0;
        return $count;
    }

    // TODO : Widget Recent Comments
    
    /*
        FUNCTIONS PAGES
     */
    
    public function wpcleaner_deactivation_page_comment_close_front($open, $post_id) {
        $post = get_post($post_id);
        if ('page' == $post->post_type)
            $open = false;
        return $open;
    }

    public function wpcleaner_deactivation_page_comment_remove_support() {
        remove_post_type_support('page', 'comments');
        remove_post_type_support('page', 'trackbacks');
    }

    public function wpcleaner_deactivation_page_comment_hide_existing($comments, $post_id) {
        $post = get_post($post_id);
        if ('page' == $post->post_type)
            $comments = array();
        return $comments;
    }

    // TODO : Widget Recent Comments

    /*
        FUNCTIONS COMMENTS
     */
    
    // Désactivation du menu
    public function wpcleaner_deactivation_comment_remove_menu() {
        remove_menu_page('edit-comments.php');
        remove_submenu_page('options-general.php', 'options-discussion.php');
    }

    // Disable support for comments and trackbacks in post types
    public function wpcleaner_deactivation_comment_remove_support() {
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            if(post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    // Close comments on the front-end
    public function wpcleaner_deactivation_comment_close_front() {
        return false;
    }

    // Hide existing comments
    public function wpcleaner_deactivation_comment_hide_existing($comments) {
        $comments = array();
        return $comments;
    }

    // Disable comments links on posts
    public function wpcleaner_deactivation_comment_comments_number($count) {
        return 0;
    }

    // Redirect any user trying to access comments page
    public function wpcleaner_deactivation_comment_redirect() {
        global $pagenow;
        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url()); exit;
        }
    }

    // Remove comments metabox from dashboard
    public function wpcleaner_deactivation_comment_dashboard() {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    }

    // Remove comments links from admin bar
    public function wpcleaner_deactivation_comment_admin_bar($wp_admin_bar) {
        if (is_admin_bar_showing()) {
            $wp_admin_bar->remove_node('comments');
        }
    }

    // Remove widget recent comments
    public function wpcleaner_deactivation_comment_widget_recent_comments() {
        unregister_widget('WP_Widget_Recent_Comments');
    }
}

$wpcleaner_deactivation_functions = new WPCleanerDeactivationFunctions();




    // disable post & CPT : https://github.com/tonykwon/wp-disable-posts/blob/master/wp-disable-posts.php
    // http://wordpress.stackexchange.com/questions/3820/deregister-custom-post-types
    // https://gist.github.com/johnkolbert/769160
    // commentaires :https://www.dfactory.eu/wordpress-how-to/turn-off-disable-comments/
    //  http://wpchannel.com/desactiver-commentaires-wordpress/
    // http://codex.wordpress.org/Function_Reference/remove_post_type_support
    // categories : https://wordpress.org/support/topic/how-do-i-disable-categories
    // tags : http://www.diije.fr/wordpress-supprimer-tags/
    // remove menu : http://wordpress.stackexchange.com/questions/121406/how-to-remove-hide-elements-from-the-admin-menu