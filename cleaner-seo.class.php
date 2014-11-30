<?php

class CleanerSEO {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options_seo;

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_init', array($this, 'cleaner_initialize_seo_options'));
    }

    /**
     * Register and add settings
     */
    public function cleaner_initialize_seo_options() {  

        $this->options_seo = get_option( 'cleaner_seo_option' );

        register_setting(
            'cleaner_seo_option_group', // Option group
            'cleaner_seo_option' // Option name
        );

        add_settings_section(
            'cleaner_seo_settings_section', // ID
            '',
            '', // Callback
            'cleaner_seo_settings_section' // Page
        );  

        /**
         *  Pings Internes
         */
        add_settings_field( 
            'cleaner_seo_internal_ping',                      // ID used to identify the field throughout the theme
            __('Pings internes', CLEANER_TEXT_DOMAIN),    // The label to the left of the option interface element
            array($this, 'cleaner_seo_checkbox_calback'),   // The name of the function responsible for rendering the option interface
            'cleaner_seo_settings_section',                          // The page on which this option will be displayed
            'cleaner_seo_settings_section',        // The name of the section to which this field belongs
            array(
                'name' => 'cleaner_seo_internal_ping',
                'description' => __('Désactiver les pings internes.', CLEANER_TEXT_DOMAIN)
            )
        );

        /**
         * Mots clés de moins de 3 articles
         */
        add_settings_field( 
            'cleaner_seo_show_terms',
            __('Mots clés de moins de 3 articles', CLEANER_TEXT_DOMAIN),
            array($this, 'cleaner_seo_checkbox_calback'),
            'cleaner_seo_settings_section',
            'cleaner_seo_settings_section',
            array(
                'name' => 'cleaner_seo_show_terms',
                'description' => __('Ne pas afficher les mots clés de moins de 3 articles et les rediriger vers la page d\'accueil.', CLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Mots clés de moins de 3 articles
         */
        add_settings_field( 
            'cleaner_seo_comment_nofollow',
            __('Nofollow commentaires', CLEANER_TEXT_DOMAIN),
            array($this, 'cleaner_seo_checkbox_calback'),
            'cleaner_seo_settings_section',
            'cleaner_seo_settings_section',
            array(
                'name' => 'cleaner_seo_comment_nofollow',
                'description' => __('Enlever le "Nofollow" sur les liens des commentaires.', CLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Styles des paragraphes de l'éditeur
         */
        add_settings_field( 
            'cleaner_seo_editor_styles',
            __('Styles de l\'editeur', CLEANER_TEXT_DOMAIN),
            array($this, 'cleaner_seo_checkbox_calback'),
            'cleaner_seo_settings_section',
            'cleaner_seo_settings_section',
            array(
                'name' => 'cleaner_seo_editor_styles',
                'description' => __('Limiter les styles de l\'editeur.', CLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Nettoyage de la section <head>
         */
        add_settings_field( 
            'cleaner_seo_clean_head',
            __('Nettoyage du Header', CLEANER_TEXT_DOMAIN),
            array($this, 'cleaner_seo_checkbox_calback'),
            'cleaner_seo_settings_section',
            'cleaner_seo_settings_section',
            array(
                'name' => 'cleaner_seo_clean_head',
                'description' => __('Nettoyer la section &lt;head&gt;.', CLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Taille des extraits
         */
        add_settings_field( 
            'cleaner_seo_excerpt_length',
            __('Taille des extraits', CLEANER_TEXT_DOMAIN),
            array($this, 'cleaner_seo_checkbox_calback'),
            'cleaner_seo_settings_section',
            'cleaner_seo_settings_section',
            array(
                'name' => 'cleaner_seo_excerpt_length',
                'description' => __('Activer les tailles personnalisées pour les extraits.', CLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Taille des extraits - Flux RSS
         */
        add_settings_field( 
            'cleaner_seo_excerpt_length_rss',
            '',
            array($this, 'cleaner_seo_input_calback'),
            'cleaner_seo_settings_section',
            'cleaner_seo_settings_section',
            array(
                'name' => 'cleaner_seo_excerpt_length_rss',
                'description' => __('Flux RSS', CLEANER_TEXT_DOMAIN)
            )
        );

        /**
         * Taille des extraits - Mots clés
         */
        add_settings_field( 
            'cleaner_seo_excerpt_length_tag',
            '',
            array($this, 'cleaner_seo_input_calback'),
            'cleaner_seo_settings_section',
            'cleaner_seo_settings_section',
            array(
                'name' => 'cleaner_seo_excerpt_length_tag',
                'description' => __('Mots Clés', CLEANER_TEXT_DOMAIN)
            )
        );
        
        /**
         * Taille des extraits - Autres
         */
        add_settings_field( 
            'cleaner_seo_excerpt_length_default',
            '',
            array($this, 'cleaner_seo_input_calback'),
            'cleaner_seo_settings_section',
            'cleaner_seo_settings_section',
            array(
                'name' => 'cleaner_seo_excerpt_length_default',
                'description' => __('Par défaut', CLEANER_TEXT_DOMAIN)
            )
        );

     
    }

    public function cleaner_seo_checkbox_calback($args) {

        $html = '<input type="checkbox" id="'.$args['name'].'" name="cleaner_seo_option['.$args['name'].']" value="1" '.cleaner_checked($this->options_seo, $args['name']).'/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';
         
        echo $html;
    }

    public function cleaner_seo_input_calback($args) {

        if(isset($this->options_seo[$args['name']])) {
            $input = esc_attr($this->options_seo[$args['name']]);
        }
        else {
            $input = '';
        }

        $html = '<input type="text" size="4" id="'.$args['name'].'" name="cleaner_seo_option['.$args['name'].']" value="'.$input.'" />';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';

        echo $html;
    }
}

if(is_admin()) {
    $cleaner_seo = new CleanerSEO();
}

