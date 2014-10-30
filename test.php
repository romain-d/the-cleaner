<?php

class rd_clean {
    public function __construct() {
        add_action('admin_menu', array(&$this, 'rd_clean_plugin_page'));
        add_action('admin_init', array(&$this, 'rd_clean_initialize_plugin_options'));
    }
    
    public function rd_clean_plugin_page() {
        add_options_page( 'RD Clean 2', 'RD Clean 2', 'manage_options','rd_clean_seo_page_slug', array( $this, 'rd_clean_settings_page' ) );
    }

    public function rd_clean_settings_page() {
        ?>
        <form method="POST" action="options.php">
        <?php
        settings_fields( 'rd_clean_seo_page_slug' );
        do_settings_sections( 'rd_clean_seo_page_slug' );
        submit_button();
        ?>
        </form>
        <?php
    }

    public function rd_clean_initialize_plugin_options() {
        add_settings_section(
            'rd_clean_seo_settings_section',
            'SEO',
            '',
            'rd_clean_seo_page_slug'
        );

        add_settings_field( 
            'rd_clean_internal_ping',                      // ID used to identify the field throughout the theme
            __('Pings internes', RD_CLEAN_TEXT_DOMAIN),    // The label to the left of the option interface element
            array(&$this, 'rd_clean_internal_ping_calback'),   // The name of the function responsible for rendering the option interface
            'rd_clean_seo_page_slug',                          // The page on which this option will be displayed
            'rd_clean_seo_settings_section'        // The name of the section to which this field belongs
        );

        register_setting( 'rd_clean_seo_page_slug', 'rd_clean_internal_ping' );
    }

    public function rd_clean_internal_ping_calback() {
     
        // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
        $html = '<input type="checkbox" id="rd_clean_internal_ping" name="rd_clean_internal_ping" value="1" ' . checked(1, get_option('rd_clean_internal_ping'), false) . '/>';
         
        // Here, we will take the first argument of the array and add it to a label next to the checkbox
        $html .= '<label for="rd_clean_internal_ping"> '.__('Désactiver les pings internes.', RD_CLEAN_TEXT_DOMAIN).'</label>';
         
        echo $html;
         
    }
}

new rd_clean;