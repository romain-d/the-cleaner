<?php

class WPCleanerGeneral {

    private $options_general;

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'wpcleaner_general_load_js'));
        add_action('admin_init', array($this, 'wpcleaner_initialize_general_options'));
    }

    public function wpcleaner_general_load_js() {
        wp_enqueue_script('wp-cleaner-admin', WPCLEANER_URL.'assets/wp-cleaner-admin.js', array('jquery'), false, false);
        wp_enqueue_media();
    }

    public function wpcleaner_initialize_general_options() {  

        $this->options_general = get_option('wpcleaner_general_option');

        register_setting(
            'wpcleaner_general_option_group',
            'wpcleaner_general_option'
        );

        add_settings_section(
            'wpcleaner_general_settings_section',
            '',
            '',
            'wpcleaner_general_settings_section'
        );  

        /**
         *  Changer le logo du wp-login
         */
        add_settings_field( 
            'wpcleaner_general_login_logo',
            __('Logo connexion', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_general_uploader_calback'),
            'wpcleaner_general_settings_section',
            'wpcleaner_general_settings_section',
            array(
                'name' => 'wpcleaner_general_login_logo',
                'button_add' => __('Choisir une image', WPCLEANER_TEXT_DOMAIN),
                'button_del' => __('Supprimer l\'image', WPCLEANER_TEXT_DOMAIN)
            )
        );

        add_settings_field( 
            'wpcleaner_general_login_logo_height',
            '',
            array($this, 'wpcleaner_general_input_calback'),
            'wpcleaner_general_settings_section',
            'wpcleaner_general_settings_section',
            array(
                'name' => 'wpcleaner_general_login_logo_height',
                'description' => __('Hauteur du logo (en pixels)', WPCLEANER_TEXT_DOMAIN),
                'size' => '5'
            )
        );

        add_settings_field( 
            'wpcleaner_general_login_logo_width',
            '',
            array($this, 'wpcleaner_general_input_calback'),
            'wpcleaner_general_settings_section',
            'wpcleaner_general_settings_section',
            array(
                'name' => 'wpcleaner_general_login_logo_width',
                'description' => __('Largeur du logo (en pixels)', WPCLEANER_TEXT_DOMAIN),
                'size' => '5'
            )
        );

        /**
         *  Désactiver les flux RSS
         */
        add_settings_field( 
            'wpcleaner_general_disable_rss',
            __('Flux RSS', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_general_checkbox_calback'),
            'wpcleaner_general_settings_section',
            'wpcleaner_general_settings_section',
            array(
                'name' => 'wpcleaner_general_disable_rss',
                'description' => __('Désactiver les flux RSS (RSS, RDF, ATOM).', WPCLEANER_TEXT_DOMAIN),
            )
        );

        /**
         *  Désactiver les MAJ Automatiques pour le Core, les Plugins, les Thèmes et les Traductions
         */
        add_settings_field( 
            'wpcleaner_general_disable_auto_update',
            __('Mises à jour WordPress', WPCLEANER_TEXT_DOMAIN),
            array($this, 'wpcleaner_general_checkbox_calback'),
            'wpcleaner_general_settings_section',
            'wpcleaner_general_settings_section',
            array(
                'name' => 'wpcleaner_general_disable_auto_update',
                'description' => __('Désactiver les mises à jour automatiques de WordPress (Core, Plugins, Thèmes, Traductions).', WPCLEANER_TEXT_DOMAIN),
            )
        );
    }

    public function wpcleaner_general_checkbox_calback($args) {

        $html = '<input type="checkbox" id="'.$args['name'].'" name="wpcleaner_general_option['.$args['name'].']" value="1" '.wpcleaner_checked($this->options_general, $args['name']).'/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';
         
        echo $html;
    }

    public function wpcleaner_general_input_calback($args) {

        if(isset($this->options_general[$args['name']])) {
            $input = esc_attr($this->options_general[$args['name']]);
        }
        else {
            $input = '';
        }

        $size = (isset($args['size'])) ? $args['size'] : '20';

        $html = '<input type="text" id="'.$args['name'].'" name="wpcleaner_general_option['.$args['name'].']" value="'.$input.'" size="'.$size.'"/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';

        echo $html;
    }

    public function wpcleaner_general_uploader_calback($args) {

        if(isset($this->options_general[$args['name']])) {
            $input = esc_attr($this->options_general[$args['name']]);
        }
        else {
            $input = '';
        }

        $html = '<div><img src="'.$input.'" width="250" /></div>';
        $html .= '<input type="text" id="'.$args['name'].'" name="wpcleaner_general_option['.$args['name'].']" value="'.$input.'" size="50" readOnly="readOnly" />';
        $html .= '<a href="#" class="button add-logo"> '.$args['button_add'].'</a>';

        $hidden = (empty($input)) ? 'hidden' : '';

        $html .= '<a href="#" class="button del-logo '.$hidden.'"> '.$args['button_del'].'</a>';
        
        echo $html;
    }
}

if(is_admin()) {
    $wpcleaner_general = new WPCleanerGeneral();
}