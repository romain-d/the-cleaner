<?php

class WPCleanerDeactivation {

    private $options_deactivation;
    
    public function __construct() {
        add_action('admin_init', array($this, 'wpcleaner_initialize_deactivation_options'));
    }

    public function wpcleaner_initialize_deactivation_options() {

        $this->options_deactivation = get_option( 'wpcleaner_deactivation_option' );

        register_setting(
            'wpcleaner_deactivation_option_group',
            'wpcleaner_deactivation_option'
        );

        /*
            POSTS
         */
        
        add_settings_section(
            'wpcleaner_deactivation_post_settings_section', 
            __('Articles', WPCLEANER_TEXT_DOMAIN), 
            '', 
            'wpcleaner_deactivation_post_settings_section'
        );

        add_settings_field( 
            'wpcleaner_deactivation_post_disable', 
            __('Désactiver les articles', WPCLEANER_TEXT_DOMAIN), 
            array($this, 'wpcleaner_deactivation_checkbox_calback'),
            'wpcleaner_deactivation_post_settings_section', 
            'wpcleaner_deactivation_post_settings_section',
            array(
                'name' => 'wpcleaner_deactivation_post_disable'
            )
        );

        add_settings_field( 
            'wpcleaner_deactivation_post_category', 
            __( 'Désactiver les catégories', WPCLEANER_TEXT_DOMAIN ), 
            array($this, 'wpcleaner_deactivation_checkbox_calback'),
            'wpcleaner_deactivation_post_settings_section', 
            'wpcleaner_deactivation_post_settings_section',
            array(
                'name' => 'wpcleaner_deactivation_post_category'
            )
        );

        add_settings_field( 
            'wpcleaner_deactivation_post_tag', 
            __( 'Désactiver les mots clés', WPCLEANER_TEXT_DOMAIN ), 
            array($this, 'wpcleaner_deactivation_checkbox_calback'),
            'wpcleaner_deactivation_post_settings_section', 
            'wpcleaner_deactivation_post_settings_section',
            array(
                'name' => 'wpcleaner_deactivation_post_tag'
            )
        );

        add_settings_field( 
            'wpcleaner_deactivation_post_comment', 
            __( 'Désactiver les commentaires', WPCLEANER_TEXT_DOMAIN ), 
            array($this, 'wpcleaner_deactivation_checkbox_calback'), 
            'wpcleaner_deactivation_post_settings_section', 
            'wpcleaner_deactivation_post_settings_section',
            array(
                'name' => 'wpcleaner_deactivation_post_comment'
            )
        );

        /*
            PAGES
         */
        
        add_settings_section(
            'wpcleaner_deactivation_page_settings_section', 
            __('Pages', WPCLEANER_TEXT_DOMAIN), 
            '', 
            'wpcleaner_deactivation_page_settings_section'
        );

        add_settings_field( 
            'wpcleaner_deactivation_page_disable', 
            __('Désactiver les pages', WPCLEANER_TEXT_DOMAIN), 
            array($this, 'wpcleaner_deactivation_checkbox_calback'),
            'wpcleaner_deactivation_page_settings_section', 
            'wpcleaner_deactivation_page_settings_section',
            array(
                'name' => 'wpcleaner_deactivation_page_disable'
            )
        );

        add_settings_field( 
            'wpcleaner_deactivation_page_comment', 
            __( 'Désactiver les commentaires', WPCLEANER_TEXT_DOMAIN ), 
            array($this, 'wpcleaner_deactivation_checkbox_calback'), 
            'wpcleaner_deactivation_page_settings_section', 
            'wpcleaner_deactivation_page_settings_section',
            array(
                'name' => 'wpcleaner_deactivation_page_comment'
            )
        );

        /*
            COMMENTS
         */
        
        add_settings_section(
            'wpcleaner_deactivation_comment_settings_section', 
            __('Commentaires', WPCLEANER_TEXT_DOMAIN), 
            '', 
            'wpcleaner_deactivation_comment_settings_section'
        );

        add_settings_field( 
            'wpcleaner_deactivation_comment_disable', 
            __('Désactiver les commentaires', WPCLEANER_TEXT_DOMAIN), 
            array($this, 'wpcleaner_deactivation_checkbox_calback'),
            'wpcleaner_deactivation_comment_settings_section', 
            'wpcleaner_deactivation_comment_settings_section',
            array(
                'name' => 'wpcleaner_deactivation_comment_disable'
            )
        );

        /*
            CUSTOM POST TYPES
         */
        $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');

        foreach ($post_types as $post_type) {
            global $wp_taxonomies; 
            $label = $post_type->label;
            $name = $post_type->name;
            
            $tag = 0;
            $category = 0;

            foreach ($wp_taxonomies as $taxonomy) {
                if($taxonomy->object_type[0] == $name) {
                    if(true == $taxonomy->hierarchical) {
                        $category++;
                    }
                    else {
                        $tag ++;
                    }
                }
            }

            add_settings_section(
                'wpcleaner_deactivation_'.$name.'_settings_section', 
                $label, 
                '', 
                'wpcleaner_deactivation_'.$name.'_settings_section'
            );

            add_settings_field( 
                'wpcleaner_deactivation_'.$name.'_disable', 
                __( 'Désactiver les ', WPCLEANER_TEXT_DOMAIN).'"'.$label.'"', 
                array($this, 'wpcleaner_deactivation_checkbox_calback'),
                'wpcleaner_deactivation_'.$name.'_settings_section', 
                'wpcleaner_deactivation_'.$name.'_settings_section',
                array(
                    'name' => 'wpcleaner_deactivation_'.$name.'_disable'
                )
            );

            if(!empty($category)) {
                add_settings_field( 
                    'wpcleaner_deactivation_'.$name.'_category', 
                    __( 'Désactiver les catégories', WPCLEANER_TEXT_DOMAIN ), 
                    array($this, 'wpcleaner_deactivation_checkbox_calback'),
                    'wpcleaner_deactivation_'.$name.'_settings_section', 
                    'wpcleaner_deactivation_'.$name.'_settings_section',
                    array(
                        'name' => 'wpcleaner_deactivation_'.$name.'_category'
                    )
                );
            }

            if(!empty($tag)) {
                add_settings_field( 
                    'wpcleaner_deactivation_'.$name.'_tag', 
                    __( 'Désactiver les mots clés', WPCLEANER_TEXT_DOMAIN ), 
                    array($this, 'wpcleaner_deactivation_checkbox_calback'),
                    'wpcleaner_deactivation_'.$name.'_settings_section', 
                    'wpcleaner_deactivation_'.$name.'_settings_section',
                    array(
                        'name' => 'wpcleaner_deactivation_'.$name.'_tag'
                    )
                );
            }

            if(post_type_supports($name, 'comments')) {
                add_settings_field( 
                    'wpcleaner_deactivation_'.$name.'_comment', 
                    __( 'Désactiver les commentaires', WPCLEANER_TEXT_DOMAIN ), 
                    array($this, 'wpcleaner_deactivation_checkbox_calback'), 
                    'wpcleaner_deactivation_'.$name.'_settings_section', 
                    'wpcleaner_deactivation_'.$name.'_settings_section',
                    array(
                        'name' => 'wpcleaner_deactivation_'.$name.'_comment'
                    )
                );
            }
        }
    }

    public function wpcleaner_deactivation_checkbox_calback($args) {

        $html = '<input type="checkbox" name="wpcleaner_deactivation_option['.$args['name'].']" value="1" '.wpcleaner_checked($this->options_deactivation, $args['name']).'/>';
         
        echo $html;
    }
}

if(is_admin()) {
    $wpcleaner_deactivation = new WPCleanerDeactivation();
}