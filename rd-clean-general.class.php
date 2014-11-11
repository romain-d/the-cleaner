<?php

class RDCleanGeneral {

    private $options_general;

    public function __construct() {
        add_action('admin_init', array($this, 'rd_clean_initialize_general_options'));
    }

    public function rd_clean_initialize_general_options() {  

        $this->options_general = get_option( 'rd_clean_general_option' );

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
            array($this, 'rd_clean_general_input_calback'),
            'rd_clean_general_settings_section',
            'rd_clean_general_settings_section',
            array(
                'name' => 'rd_clean_general_login_logo',
                'description' => __('Changer le logo de la page de connexion.', RD_CLEAN_TEXT_DOMAIN)
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
                'description' => __('Désactiver les flux RSS (RSS, RDF, ATOM).', RD_CLEAN_TEXT_DOMAIN)
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

        $html = '<input type="text" id="'.$args['name'].'" name="rd_clean_general_option['.$args['name'].']" value="'.$input.'" />';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';

        echo $html;
    }
}

if(is_admin()) {
    $rd_clean_general = new RDCleanGeneral();
}