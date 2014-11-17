<?php

class RDCleanGeneral {

    private $options_general;

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'rd_clean_general_load_js'));
        add_action('admin_init', array($this, 'rd_clean_initialize_general_options'));
    }

    public function rd_clean_general_load_js() {
        wp_enqueue_script('adminjs', RD_CLEAN_URL.'assets/admin.js', array( 'jquery' ), false, false);
        wp_enqueue_media();
    }

    public function rd_clean_initialize_general_options() {  

        $this->options_general = get_option('rd_clean_general_option');

        register_setting(
            'rd_clean_general_option_group',
            'rd_clean_general_option'
        );

        add_settings_section(
            'rd_clean_general_settings_section',
            '',
            '',
            'rd_clean_general_settings_section'
        );  

        /**
         *  Changer le logo du wp-login
         */
        add_settings_field( 
            'rd_clean_general_login_logo',
            __('Logo connexion', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_general_uploader_calback'),
            'rd_clean_general_settings_section',
            'rd_clean_general_settings_section',
            array(
                'name' => 'rd_clean_general_login_logo',
                'button' => __('Choisir une image', RD_CLEAN_TEXT_DOMAIN)
            )
        );

        add_settings_field( 
            'rd_clean_general_login_logo_height',
            '',
            array($this, 'rd_clean_general_input_calback'),
            'rd_clean_general_settings_section',
            'rd_clean_general_settings_section',
            array(
                'name' => 'rd_clean_general_login_logo_height',
                'description' => __('Hauteur du logo (en pixels)', RD_CLEAN_TEXT_DOMAIN),
                'size' => '5'
            )
        );

        add_settings_field( 
            'rd_clean_general_login_logo_width',
            '',
            array($this, 'rd_clean_general_input_calback'),
            'rd_clean_general_settings_section',
            'rd_clean_general_settings_section',
            array(
                'name' => 'rd_clean_general_login_logo_width',
                'description' => __('Largeur du logo (en pixels)', RD_CLEAN_TEXT_DOMAIN),
                'size' => '5'
            )
        );

        /**
         *  Désactiver les flux RSS
         */
        add_settings_field( 
            'rd_clean_general_desactive_rss',
            __('Flux RSS', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_general_checkbox_calback'),
            'rd_clean_general_settings_section',
            'rd_clean_general_settings_section',
            array(
                'name' => 'rd_clean_general_desactive_rss',
                'description' => __('Désactiver les flux RSS (RSS, RDF, ATOM).', RD_CLEAN_TEXT_DOMAIN),
            )
        );
    }

    public function rd_clean_general_checkbox_calback($args) {

        $html = '<input type="checkbox" id="'.$args['name'].'" name="rd_clean_general_option['.$args['name'].']" value="1" '.rd_clean_checked($this->options_general, $args['name']).'/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';
         
        echo $html;
    }

    public function rd_clean_general_input_calback($args) {

        if(isset($this->options_general[$args['name']])) {
            $input = esc_attr($this->options_general[$args['name']]);
        }
        else {
            $input = '';
        }

        $size = (isset($args['size'])) ? $args['size'] : '20';

        $html = '<input type="text" id="'.$args['name'].'" name="rd_clean_general_option['.$args['name'].']" value="'.$input.'" size="'.$size.'"/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';

        echo $html;
    }

    public function rd_clean_general_uploader_calback($args) {

        if(isset($this->options_general[$args['name']])) {
            $input = esc_attr($this->options_general[$args['name']]);
        }
        else {
            $input = '';
        }

        $html = '<img src="'.$input.'" width="250" />';
        $html .= '<input type="text" id="'.$args['name'].'" name="rd_clean_general_option['.$args['name'].']" value="'.$input.'" size="75" readOnly="readOnly" />';
        $html .= '<a href="#" class="button add-logo"> '.$args['button'].'</a>';

        echo $html;
    }
}

if(is_admin()) {
    $rd_clean_general = new RDCleanGeneral();
}