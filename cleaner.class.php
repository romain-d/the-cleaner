<?php
    class Cleaner {

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'cleaner_plugin_page'));
    }

    /**
     * Add options page
     */
    public function cleaner_plugin_page() {
        add_options_page(
            'RD Clean', 
            'RD Clean', 
            'manage_options', 
            'cleaner_page', 
            array($this, 'cleaner_settings_page')
        );
    }

    /**
     * Options page callback
     */
    public function cleaner_settings_page() {

        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

        ?>
        <div class="wrap">
            <h2><?php _e('RD Clean', CLEANER_TEXT_DOMAIN); ?></h2>

            <h2 class="nav-tab-wrapper">
                <a href="?page=cleaner_page&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('Général', CLEANER_TEXT_DOMAIN); ?></a>
                <a href="?page=cleaner_page&tab=seo" class="nav-tab <?php echo $active_tab == 'seo' ? 'nav-tab-active' : ''; ?>"><?php _e('SEO', CLEANER_TEXT_DOMAIN); ?></a>
                <a href="?page=cleaner_page&tab=deactivation" class="nav-tab <?php echo $active_tab == 'deactivation' ? 'nav-tab-active' : ''; ?>"><?php _e('Désactivation', CLEANER_TEXT_DOMAIN); ?></a>
                <a href="?page=cleaner_page&tab=user" class="nav-tab <?php echo $active_tab == 'user' ? 'nav-tab-active' : ''; ?>"><?php _e('Utilisateur', CLEANER_TEXT_DOMAIN); ?></a>
            </h2>

            <form method="POST" action="options.php">
            <?php
                if($active_tab == 'seo') {
                    settings_fields('cleaner_seo_option_group');   
                    do_settings_sections('cleaner_seo_settings_section');   
                }
                elseif($active_tab == 'deactivation') {
                    settings_fields('cleaner_deactivation_option_group');

                    do_settings_sections('cleaner_deactivation_post_settings_section');
                    do_settings_sections('cleaner_deactivation_page_settings_section');
                    do_settings_sections('cleaner_deactivation_comment_settings_section');

                    $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
                    foreach ($post_types as $post_type) {
                        $name = $post_type->name;
                        do_settings_sections('cleaner_deactivation_'.$name.'_settings_section'); 
                    }
                }
                elseif($active_tab == 'user') {
                    settings_fields('cleaner_user_option_group');   
                    do_settings_sections('cleaner_user_settings_section');   
                }
                else {
                    settings_fields('cleaner_general_option_group');   
                    do_settings_sections('cleaner_general_settings_section'); 
                }

                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }
}

if(is_admin()) {
    $cleaner = new Cleaner();
}