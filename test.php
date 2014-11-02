<?php

class rd_clean {
    public function __construct() {
        add_action('admin_menu', array(&$this, 'rd_clean_plugin_page'));
        add_action('admin_init', array(&$this, 'rd_clean_initialize_plugin_options'));
    }
    
    public function rd_clean_plugin_page() {
        add_options_page('RD Clean 2', 'RD Clean 2', 'manage_options', 'rd_clean_seo_page_slug', array( $this, 'rd_clean_settings_page'));
    }

    public function rd_clean_settings_page() {

        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

        ?>
        <div class="wrap">
            <h2>RD Clean</h2>
            <?php settings_errors(); ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=rd_clean_page&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
                <a href="?page=rd_clean_page&tab=seo" class="nav-tab <?php echo $active_tab == 'seo' ? 'nav-tab-active' : ''; ?>">SEO</a>
            </h2>

            <form method="POST" action="options.php">
            <?php
            settings_fields('rd_clean_seo_settings_section');
            do_settings_sections('rd_clean_seo_page_slug');
            submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function rd_clean_initialize_plugin_options() {
        add_settings_section(
            'rd_clean_seo_settings_section',
            'SEO',
            '',
            'rd_clean_seo_page_slug'
        );

        /**
         *  Pings Internes
         */
        add_settings_field( 
            'rd_clean_seo_internal_ping',                      // ID used to identify the field throughout the theme
            __('Pings internes', RD_CLEAN_TEXT_DOMAIN),    // The label to the left of the option interface element
            array(&$this, 'rd_clean_seo_checkbox_calback'),   // The name of the function responsible for rendering the option interface
            'rd_clean_seo_page_slug',                          // The page on which this option will be displayed
            'rd_clean_seo_settings_section',        // The name of the section to which this field belongs
            array(
                'name' => 'rd_clean_seo_internal_ping',
                'description' => __('Désactiver les pings internes.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_internal_ping');

        /**
         * Description détaillée de l'auteur
         */
        add_settings_field( 
            'rd_clean_seo_author_description',
            __('Description détaillée de l\'auteur', RD_CLEAN_TEXT_DOMAIN),
            array(&$this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_author_description',
                'description' => __('Activer la description détaillée des auteurs.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_author_description');

        /**
         * Mots clés de moins de 3 articles
         */
        add_settings_field( 
            'rd_clean_seo_show_terms',
            __('Mots clés de moins de 3 articles', RD_CLEAN_TEXT_DOMAIN),
            array(&$this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_show_terms',
                'description' => __('Afficher les mots clés de moins de 3 articles.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_show_terms');

        add_settings_field( 
            'rd_clean_seo_terms_links',
            '',
            array(&$this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_terms_links',
                'description' => __('Rediriger les mots clés de moins de 3 articles vers la page d\'accueil.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_terms_links');

        /**
         * Mots clés de moins de 3 articles
         */
        add_settings_field( 
            'rd_clean_seo_comment_nofollow',
            __('Nofollow commentaires', RD_CLEAN_TEXT_DOMAIN),
            array(&$this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_comment_nofollow',
                'description' => __('Enlever le "Nofollow" sur les liens dans les commentaires.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_comment_nofollow');

        /**
         * Styles des paragraphes de l'éditeur
         */
        add_settings_field( 
            'rd_clean_seo_editor_styles',
            __('Styles de l\'editeur', RD_CLEAN_TEXT_DOMAIN),
            array(&$this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_editor_styles',
                'description' => __('Limiter les styles de l\'editeur.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_editor_styles');

        /**
         * Nettoyage de la section <head>
         */
        add_settings_field( 
            'rd_clean_seo_clean_head',
            __('Nettoyage du Header', RD_CLEAN_TEXT_DOMAIN),
            array(&$this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_clean_head',
                'description' => __('Nettoyer la section &lt;head&gt;.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_clean_head');

        /**
         * Taille des extraits
         */
        add_settings_field( 
            'rd_clean_seo_excerpt_length',
            __('Taille des extraits', RD_CLEAN_TEXT_DOMAIN),
            array(&$this, 'rd_clean_seo_checkbox_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_excerpt_length',
                'description' => __('Activer les tailles personnalisées pour les extraits.', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_excerpt_length');

        /**
         * Taille des extraits - Flux RSS
         */
        add_settings_field( 
            'rd_clean_seo_excerpt_length_rss',
            '',
            array(&$this, 'rd_clean_seo_input_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_excerpt_length_rss',
                'description' => __('Flux RSS', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_excerpt_length_rss');

        /**
         * Taille des extraits - Mots clés
         */
        add_settings_field( 
            'rd_clean_seo_excerpt_length_tag',
            '',
            array(&$this, 'rd_clean_seo_input_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_excerpt_length_tag',
                'description' => __('Mots Clés', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_excerpt_length_tag');

        /**
         * Taille des extraits - Autres
         */
        add_settings_field( 
            'rd_clean_seo_excerpt_length_others',
            '',
            array(&$this, 'rd_clean_seo_input_calback'),
            'rd_clean_seo_page_slug',
            'rd_clean_seo_settings_section',
            array(
                'name' => 'rd_clean_seo_excerpt_length_others',
                'description' => __('Autres', RD_CLEAN_TEXT_DOMAIN)
            )
        );
        register_setting('rd_clean_seo_page_slug', 'rd_clean_seo_excerpt_length_others');
    }

    public function rd_clean_seo_checkbox_calback($args) {
        $html = '<input type="checkbox" id="'.$args['name'].'" name="'.$args['name'].'" value="1" '.checked(1, get_option($args['name']), false).'/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';
         
        echo $html;
    }

    public function rd_clean_seo_input_calback($args) {
        $html = '<input type="text" size="4" id="'.$args['name'].'" name="'.$args['name'].'" value="'. get_option($args['name']).'" />';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';

        echo $html;
    }
}

new rd_clean;