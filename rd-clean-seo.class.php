<?php

class RDCleanSEO {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options_seo;

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_init', array($this, 'rd_clean_initialize_seo_options'));
    }

    /**
     * Register and add settings
     */
    public function rd_clean_initialize_seo_options() {  

        $this->options_seo = get_option( 'rd_clean_seo_option' );

        register_setting(
            'rd_clean_seo_option_group', // Option group
            'rd_clean_seo_option' // Option name
        );

        add_settings_section(
            'rd_clean_seo_settings_section', // ID
            '',
            '', // Callback
            'rd_clean_seo_settings_section' // Page
        );  

        /**
         *  Pings Internes
         */
        add_settings_field( 
            'rd_clean_seo_internal_ping',                      // ID used to identify the field throughout the theme
            __('Pings internes', RD_CLEAN_TEXT_DOMAIN),    // The label to the left of the option interface element
            array($this, 'rd_clean_seo_checkbox_calback'),   // The name of the function responsible for rendering the option interface
            'rd_clean_seo_settings_section',                          // The page on which this option will be displayed
            'rd_clean_seo_settings_section',        // The name of the section to which this field belongs
            array(
                'name' => 'rd_clean_seo_internal_ping',
                'description' => __('Désactiver les pings internes.', RD_CLEAN_TEXT_DOMAIN)
            )
        );

        /**
         * Description détaillée de l'auteur
         */
        add_settings_field( 
            'rd_clean_seo_author_description',
            __('Description détaillée de l\'auteur', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_author_description',
                'description' => __('Activer la description détaillée des auteurs.', RD_CLEAN_TEXT_DOMAIN)
            )
        );

        /**
         * Mots clés de moins de 3 articles
         */
        add_settings_field( 
            'rd_clean_seo_show_terms',
            __('Mots clés de moins de 3 articles', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_show_terms',
                'description' => __('Afficher les mots clés de moins de 3 articles.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        
        add_settings_field( 
            'rd_clean_seo_terms_links',
            '',
            array($this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_terms_links',
                'description' => __('Rediriger les mots clés de moins de 3 articles vers la page d\'accueil.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        
        /**
         * Mots clés de moins de 3 articles
         */
        add_settings_field( 
            'rd_clean_seo_comment_nofollow',
            __('Nofollow commentaires', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_comment_nofollow',
                'description' => __('Enlever le "Nofollow" sur les liens dans les commentaires.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        
        /**
         * Styles des paragraphes de l'éditeur
         */
        add_settings_field( 
            'rd_clean_seo_editor_styles',
            __('Styles de l\'editeur', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_editor_styles',
                'description' => __('Limiter les styles de l\'editeur.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        
        /**
         * Nettoyage de la section <head>
         */
        add_settings_field( 
            'rd_clean_seo_clean_head',
            __('Nettoyage du Header', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_clean_head',
                'description' => __('Nettoyer la section &lt;head&gt;.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        
        /**
         * Taille des extraits
         */
        add_settings_field( 
            'rd_clean_seo_excerpt_length',
            __('Taille des extraits', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_excerpt_length',
                'description' => __('Activer les tailles personnalisées pour les extraits.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        
        /**
         * Taille des extraits - Flux RSS
         */
        add_settings_field( 
            'rd_clean_seo_excerpt_length_rss',
            '',
            array($this, 'rd_clean_seo_input_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_excerpt_length_rss',
                'description' => __('Flux RSS', RD_CLEAN_TEXT_DOMAIN)
            )
        );

        /**
         * Taille des extraits - Mots clés
         */
        add_settings_field( 
            'rd_clean_seo_excerpt_length_tag',
            '',
            array($this, 'rd_clean_seo_input_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_excerpt_length_tag',
                'description' => __('Mots Clés', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        
        /**
         * Taille des extraits - Autres
         */
        add_settings_field( 
            'rd_clean_seo_excerpt_length_default',
            '',
            array($this, 'rd_clean_seo_input_calback'),
            'rd_clean_seo_settings_section',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_excerpt_length_default',
                'description' => __('Par défaut', RD_CLEAN_TEXT_DOMAIN)
            )
        );

     
    }

    public function rd_clean_seo_checkbox_calback($args) {

        $html = '<input type="checkbox" id="'.$args['name'].'" name="rd_clean_seo_option['.$args['name'].']" value="1" '.rd_clean_checked($this->options_seo, $args['name']).'/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';
         
        echo $html;
    }

    public function rd_clean_seo_input_calback($args) {

        if(isset($this->options_seo[$args['name']])) {
            $input = esc_attr($this->options_seo[$args['name']]);
        }
        else {
            $input = '';
        }

        $html = '<input type="text" size="4" id="'.$args['name'].'" name="rd_clean_seo_option['.$args['name'].']" value="'.$input.'" />';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';

        echo $html;
    }
}

if(is_admin()) {
    $rd_clean_seo = new RDCleanSEO();
}

