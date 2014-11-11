<?php
    class RDClean {

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'rd_clean_plugin_page'));
    }

    /**
     * Add options page
     */
    public function rd_clean_plugin_page() {
        add_options_page(
            'RD Clean', 
            'RD Clean', 
            'manage_options', 
            'rd_clean_page', 
            array( $this, 'rd_clean_settings_page' )
        );
    }

    /**
     * Options page callback
     */
    public function rd_clean_settings_page() {

        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

        ?>
        <div class="wrap">
            <h2><?php _e('RD Clean', RD_CLEAN_TEXT_DOMAIN); ?></h2>

            <h2 class="nav-tab-wrapper">
                <a href="?page=rd_clean_page&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('Général', RD_CLEAN_TEXT_DOMAIN); ?></a>
                <a href="?page=rd_clean_page&tab=seo" class="nav-tab <?php echo $active_tab == 'seo' ? 'nav-tab-active' : ''; ?>"><?php _e('SEO', RD_CLEAN_TEXT_DOMAIN); ?></a>
                <a href="?page=rd_clean_page&tab=deactivation" class="nav-tab <?php echo $active_tab == 'deactivation' ? 'nav-tab-active' : ''; ?>"><?php _e('Désactivation', RD_CLEAN_TEXT_DOMAIN); ?></a>
            </h2>

            <form method="POST" action="options.php">
            <?php
                if($active_tab == 'seo') {
                    settings_fields('rd_clean_seo_option_group');   
                    do_settings_sections('rd_clean_seo_settings_section');   
                }
                elseif($active_tab == 'deactivation') {
                    settings_fields('rd_clean_deactivation_option_group');

                    $post_types = get_post_types(array('public' => true), 'objects');
                    foreach ($post_types as $post_type) {
                        $name = $post_type->name;
                        do_settings_sections('rd_clean_deactivation_'.$name.'_settings_section'); 
                    }
                }
                else {
                    settings_fields('rd_clean_general_option_group');   
                    do_settings_sections('rd_clean_general_settings_section'); 
                }

                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }
}

if(is_admin()) {
    $rd_clean = new RDClean();
}