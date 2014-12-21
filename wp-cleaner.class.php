<?php
    class WPCleaner {

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'wpcleaner_plugin_page'));
    }

    /**
     * Add options page
     */
    public function wpcleaner_plugin_page() {
        add_options_page(
            'WP Cleaner', 
            'WP Cleaner', 
            'manage_options', 
            'wpcleaner_page', 
            array($this, 'wpcleaner_settings_page')
        );
    }

    /**
     * Options page callback
     */
    public function wpcleaner_settings_page() {

        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

        ?>
        <div class="wrap">
            <h2><?php _e('WP Cleaner', WPCLEANER_TEXT_DOMAIN); ?></h2>

            <h2 class="nav-tab-wrapper">
                <a href="?page=wpcleaner_page&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('Général', WPCLEANER_TEXT_DOMAIN); ?></a>
                <a href="?page=wpcleaner_page&tab=seo" class="nav-tab <?php echo $active_tab == 'seo' ? 'nav-tab-active' : ''; ?>"><?php _e('SEO', WPCLEANER_TEXT_DOMAIN); ?></a>
                <a href="?page=wpcleaner_page&tab=deactivation" class="nav-tab <?php echo $active_tab == 'deactivation' ? 'nav-tab-active' : ''; ?>"><?php _e('Désactivation', WPCLEANER_TEXT_DOMAIN); ?></a>
                <a href="?page=wpcleaner_page&tab=role" class="nav-tab <?php echo $active_tab == 'role' ? 'nav-tab-active' : ''; ?>"><?php _e('Rôle', WPCLEANER_TEXT_DOMAIN); ?></a>
            </h2>

            <form method="POST" action="options.php">
            <?php
                if($active_tab == 'seo') {
                    settings_fields('wpcleaner_seo_option_group');   
                    do_settings_sections('wpcleaner_seo_settings_section');   
                }
                elseif($active_tab == 'deactivation') {
                    settings_fields('wpcleaner_deactivation_option_group');

                    do_settings_sections('wpcleaner_deactivation_post_settings_section');
                    do_settings_sections('wpcleaner_deactivation_page_settings_section');
                    do_settings_sections('wpcleaner_deactivation_comment_settings_section');

                    $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
                    foreach ($post_types as $post_type) {
                        $name = $post_type->name;
                        do_settings_sections('wpcleaner_deactivation_'.$name.'_settings_section'); 
                    }
                }
                elseif($active_tab == 'role') {
                    settings_fields('wpcleaner_role_option_group');   
                    do_settings_sections('wpcleaner_role_settings_section');   
                }
                else {
                    settings_fields('wpcleaner_general_option_group');   
                    do_settings_sections('wpcleaner_general_settings_section'); 
                }

                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }
}

if(is_admin()) {
    $cleaner = new WPCleaner();
}