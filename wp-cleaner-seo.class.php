<?php

class WPCleanerSEO {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options_seo;

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_init', array($this, 'wpcleaner_initialize_seo_options'));
    }

    /**
     * Register and add settings
     */
    public function wpcleaner_initialize_seo_options() {  

        $this->options_seo = get_option( 'wpcleaner_seo_option' );

        register_setting(
            'wpcleaner_seo_option_group', // Option group
            'wpcleaner_seo_option' // Option name
        );

        add_settings_section(
            'wpcleaner_seo_settings_section', // ID
            '',
            '', // Callback
            'wpcleaner_seo_settings_section' // Page
        );  

        /**
         *  Pings Internes
         */
        add_settings_field( 
            'wpcleaner_seo_internal_ping',                      // ID used to identify the field throughout the theme
            __('Pings internes', WPCLEANER_TEXT_DOMAIN),    // The label to the left of the option interface element
            array($this, 'wpcleaner_seo_checkbox_calback'),   // The name of the function responsible for rendering the option interface
            'wpcleaner_seo_settings_section',                          // The page on which this option will be displayed
            'wpcleaner_seo_settings_section',        // The name of the section to which this field belongs
            array(
                'name' => 'wpcleaner_seo_internal_ping',
                'description' => __('Désactiver les pings internes.', WPCLEANER_TEXT_DOMAIN)
            )
        );

        /**
         * Mots clés de moins de 3 articles
         */
        add_settings_field( 
            'wpcleaner_seo_show_terms',
            __('Mots clés de moins de 3 articles', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_seo_checkbox_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_show_terms',
                'description' => __('Ne pas afficher les mots clés de moins de 3 articles et les rediriger vers la page d\'accueil.', WPCLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Mots clés de moins de 3 articles
         */
        add_settings_field( 
            'wpcleaner_seo_comment_nofollow',
            __('Nofollow commentaires', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_seo_checkbox_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_comment_nofollow',
                'description' => __('Enlever le "Nofollow" sur les liens des commentaires.', WPCLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Styles des paragraphes de l'éditeur
         */
        add_settings_field( 
            'wpcleaner_seo_editor_styles',
            __('Styles de l\'editeur', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_seo_checkbox_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_editor_styles',
                'description' => __('Limiter les styles de l\'editeur.', WPCLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Nettoyage de la section <head>
         */
        add_settings_field( 
            'wpcleaner_seo_clean_head',
            __('Nettoyage du Header', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_seo_checkbox_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_clean_head',
                'description' => __('Nettoyer la section &lt;head&gt;.', WPCLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Supprimer le lien court des Post
         */
        add_settings_field( 
            'wpcleaner_seo_remove_short_link',
            __('Liens courts', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_seo_checkbox_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_remove_short_link',
                'description' => __('Supprimer le lien court des Posts.', WPCLEANER_TEXT_DOMAIN)
            )
        );

        /**
         * Taille des extraits
         */
        add_settings_field( 
            'wpcleaner_seo_excerpt_length',
            __('Taille des extraits', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_seo_checkbox_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_excerpt_length',
                'description' => __('Activer les tailles personnalisées pour les extraits.', WPCLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Taille des extraits - Flux RSS
         */
        add_settings_field( 
            'wpcleaner_seo_excerpt_length_rss',
            '',
            array($this, 'wpcleaner_seo_input_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_excerpt_length_rss',
                'description' => __('Flux RSS', WPCLEANER_TEXT_DOMAIN)
            )
        );

        /**
         * Taille des extraits - Mots clés
         */
        add_settings_field( 
            'wpcleaner_seo_excerpt_length_tag',
            '',
            array($this, 'wpcleaner_seo_input_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_excerpt_length_tag',
                'description' => __('Mots Clés', WPCLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Taille des extraits - Autres
         */
        add_settings_field( 
            'wpcleaner_seo_excerpt_length_default',
            '',
            array($this, 'wpcleaner_seo_input_calback'),
            'wpcleaner_seo_settings_section',
            'wpcleaner_seo_settings_section',
            array(
                'name' => 'wpcleaner_seo_excerpt_length_default',
                'description' => __('Par défaut', WPCLEANER_TEXT_DOMAIN)
            )
        );

     
    }

    public function wpcleaner_seo_checkbox_calback($args) {

        $html = '<input type="checkbox" id="'.$args['name'].'" name="wpcleaner_seo_option['.$args['name'].']" value="1" '.wpcleaner_checked($this->options_seo, $args['name']).'/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';
         
        echo $html;
    }

    public function wpcleaner_seo_input_calback($args) {

        if(isset($this->options_seo[$args['name']])) {
            $input = esc_attr($this->options_seo[$args['name']]);
        }
        else {
            $input = '';
        }

        $html = '<input type="text" size="4" id="'.$args['name'].'" name="wpcleaner_seo_option['.$args['name'].']" value="'.$input.'" />';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';

        echo $html;
    }
}

if(is_admin()) {
    $wpcleaner_seo = new WPCleanerSEO();
}