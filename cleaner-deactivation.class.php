<?php

class CleanerDeactivation {

    private $options_deactivation;
    
    public function __construct() {
        add_action('admin_init', array($this, 'cleaner_initialize_deactivation_options'));
    }

    public function cleaner_initialize_deactivation_options() {

        $this->options_deactivation = get_option( 'cleaner_deactivation_option' );

        register_setting(
            'cleaner_deactivation_option_group',
            'cleaner_deactivation_option'
        );

        /*
            POSTS
         */
        
        add_settings_section(
            'cleaner_deactivation_post_settings_section', 
            __('Articles', CLEANER_TEXT_DOMAIN), 
            '', 
            'cleaner_deactivation_post_settings_section'
        );

        add_settings_field( 
            'cleaner_deactivation_post_disable', 
            __('Désactiver les articles', CLEANER_TEXT_DOMAIN), 
            array($this, 'cleaner_deactivation_checkbox_calback'),
            'cleaner_deactivation_post_settings_section', 
            'cleaner_deactivation_post_settings_section',
            array(
                'name' => 'cleaner_deactivation_post_disable'
            )
        );

        add_settings_field( 
            'cleaner_deactivation_post_category', 
            __( 'Désactiver les catégories', CLEANER_TEXT_DOMAIN ), 
            array($this, 'cleaner_deactivation_checkbox_calback'),
            'cleaner_deactivation_post_settings_section', 
            'cleaner_deactivation_post_settings_section',
            array(
                'name' => 'cleaner_deactivation_post_category'
            )
        );

        add_settings_field( 
            'cleaner_deactivation_post_tag', 
            __( 'Désactiver les mots clés', CLEANER_TEXT_DOMAIN ), 
            array($this, 'cleaner_deactivation_checkbox_calback'),
            'cleaner_deactivation_post_settings_section', 
            'cleaner_deactivation_post_settings_section',
            array(
                'name' => 'cleaner_deactivation_post_tag'
            )
        );

        add_settings_field( 
            'cleaner_deactivation_post_comment', 
            __( 'Désactiver les commentaires', CLEANER_TEXT_DOMAIN ), 
            array($this, 'cleaner_deactivation_checkbox_calback'), 
            'cleaner_deactivation_post_settings_section', 
            'cleaner_deactivation_post_settings_section',
            array(
                'name' => 'cleaner_deactivation_post_comment'
            )
        );

        /*
            PAGES
         */
        
        add_settings_section(
            'cleaner_deactivation_page_settings_section', 
            __('Pages', CLEANER_TEXT_DOMAIN), 
            '', 
            'cleaner_deactivation_page_settings_section'
        );

        add_settings_field( 
            'cleaner_deactivation_page_disable', 
            __('Désactiver les pages', CLEANER_TEXT_DOMAIN), 
            array($this, 'cleaner_deactivation_checkbox_calback'),
            'cleaner_deactivation_page_settings_section', 
            'cleaner_deactivation_page_settings_section',
            array(
                'name' => 'cleaner_deactivation_page_disable'
            )
        );

        add_settings_field( 
            'cleaner_deactivation_page_comment', 
            __( 'Désactiver les commentaires', CLEANER_TEXT_DOMAIN ), 
            array($this, 'cleaner_deactivation_checkbox_calback'), 
            'cleaner_deactivation_page_settings_section', 
            'cleaner_deactivation_page_settings_section',
            array(
                'name' => 'cleaner_deactivation_page_comment'
            )
        );

        /*
            COMMENTS
         */
        
        add_settings_section(
            'cleaner_deactivation_comment_settings_section', 
            __('Commentaires', CLEANER_TEXT_DOMAIN), 
            '', 
            'cleaner_deactivation_comment_settings_section'
        );

        add_settings_field( 
            'cleaner_deactivation_comment_disable', 
            __('Désactiver les commentaires', CLEANER_TEXT_DOMAIN), 
            array($this, 'cleaner_deactivation_checkbox_calback'),
            'cleaner_deactivation_comment_settings_section', 
            'cleaner_deactivation_comment_settings_section',
            array(
                'name' => 'cleaner_deactivation_comment_disable'
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
                'cleaner_deactivation_'.$name.'_settings_section', 
                $label, 
                '', 
                'cleaner_deactivation_'.$name.'_settings_section'
            );

            add_settings_field( 
                'cleaner_deactivation_'.$name.'_disable', 
                __( 'Désactiver les ', CLEANER_TEXT_DOMAIN).'"'.$label.'"', 
                array($this, 'cleaner_deactivation_checkbox_calback'),
                'cleaner_deactivation_'.$name.'_settings_section', 
                'cleaner_deactivation_'.$name.'_settings_section',
                array(
                    'name' => 'cleaner_deactivation_'.$name.'_disable'
                )
            );

            if(!empty($category)) {
                add_settings_field( 
                    'cleaner_deactivation_'.$name.'_category', 
                    __( 'Désactiver les catégories', CLEANER_TEXT_DOMAIN ), 
                    array($this, 'cleaner_deactivation_checkbox_calback'),
                    'cleaner_deactivation_'.$name.'_settings_section', 
                    'cleaner_deactivation_'.$name.'_settings_section',
                    array(
                        'name' => 'cleaner_deactivation_'.$name.'_category'
                    )
                );
            }

            if(!empty($tag)) {
                add_settings_field( 
                    'cleaner_deactivation_'.$name.'_tag', 
                    __( 'Désactiver les mots clés', CLEANER_TEXT_DOMAIN ), 
                    array($this, 'cleaner_deactivation_checkbox_calback'),
                    'cleaner_deactivation_'.$name.'_settings_section', 
                    'cleaner_deactivation_'.$name.'_settings_section',
                    array(
                        'name' => 'cleaner_deactivation_'.$name.'_tag'
                    )
                );
            }

            if(post_type_supports($name, 'comments')) {
                add_settings_field( 
                    'cleaner_deactivation_'.$name.'_comment', 
                    __( 'Désactiver les commentaires', CLEANER_TEXT_DOMAIN ), 
                    array($this, 'cleaner_deactivation_checkbox_calback'), 
                    'cleaner_deactivation_'.$name.'_settings_section', 
                    'cleaner_deactivation_'.$name.'_settings_section',
                    array(
                        'name' => 'cleaner_deactivation_'.$name.'_comment'
                    )
                );
            }
        }
    }

    public function cleaner_deactivation_checkbox_calback($args) {

        $html = '<input type="checkbox" name="cleaner_deactivation_option['.$args['name'].']" value="1" '.cleaner_checked($this->options_deactivation, $args['name']).'/>';
         
        echo $html;
    }
}

if(is_admin()) {
    $cleaner_deactivation = new CleanerDeactivation();
}