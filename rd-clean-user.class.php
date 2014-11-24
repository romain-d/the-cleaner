<?php

class RDCleanUser {

    private $options_user;

    public $roles = array(
        'administrator' => 'Administrateur',
        'editor' => 'Éditeur',
        'author' => 'Auteur',
        'contributor' => 'Contributeur',
        'subscriber' => 'Abonné',
    );

    public $capabilities = array(
        'administrator' => array(
            'activate_plugins',
            'create_users',
            'delete_plugins',
            'delete_themes',
            'delete_users',
            'edit_files',
            'edit_plugins',
            'edit_theme_options',
            'edit_themes',
            'edit_users',
            'export',
            'import',
            'install_plugins',
            'install_themes',
            'list_users',
            'manage_options',
            'promote_users',
            'remove_users',
            'switch_themes',
            'update_core',
            'update_plugins',
            'update_themes',
            'edit_dashboard',
        ),
        'editor' => array(
            'moderate_comments',
            'manage_categories',
            'manage_links',
            'edit_others_posts',
            'edit_pages',
            'edit_others_pages',
            'edit_published_pages',
            'publish_pages',
            'delete_pages',
            'delete_others_pages',
            'delete_published_pages',
            'delete_others_posts',
            'delete_private_posts',
            'edit_private_posts',
            'read_private_posts',
            'delete_private_pages',
            'edit_private_pages',
            'read_private_pages',
        ),
        'author' => array(
            'edit_published_posts',
            'upload_files',     
            'publish_posts',      
            'delete_published_posts',
        ),
        'contributor' => array(
            'edit_posts',
            'delete_posts'
        ),
        'subscriber' => array(
            'read'
        ),
    );

    public function __construct() {
        add_action('admin_init', array($this, 'rd_clean_initialize_user_options'));
    }

    public function rd_clean_initialize_user_options() {  

        // global $wp_roles;
        // printr($wp_roles->roles);

        $this->options_user = get_option('rd_clean_user_option');

        register_setting(
            'rd_clean_user_option_group',
            'rd_clean_user_option'
        );

        add_settings_section(
            'rd_clean_user_settings_section',
            '',
            '',
            'rd_clean_user_settings_section'
        );  

        add_settings_field( 
            'rd_clean_user_role_name',
            __('Rôle', RD_CLEAN_TEXT_DOMAIN),
            array($this, 'rd_clean_user_input_calback'),
            'rd_clean_user_settings_section',
            'rd_clean_user_settings_section',
            array(
                'name' => 'rd_clean_user_role_name',
                'description' => __('Nom du nouveau rôle', RD_CLEAN_TEXT_DOMAIN),
            )
        );

        foreach ($this->roles as $role => $label) {
            $count = 0;
            foreach ($this->capabilities[$role] as $capability) {
                if($count == 0) {
                    $role_name = $label;
                    $count++;
                }
                else {
                    $role_name = '';
                }
                 
                add_settings_field( 
                    $capability,
                    __($role_name, RD_CLEAN_TEXT_DOMAIN),
                    array($this, 'rd_clean_user_checkbox_calback'),
                    'rd_clean_user_settings_section',
                    'rd_clean_user_settings_section',
                    array(
                        'name' => $capability,
                        'description' => $capability
                    )
                );
            }
        }
    }

    public function rd_clean_user_checkbox_calback($args) {   

        $html = '<input type="checkbox" id="'.$args['name'].'" name="rd_clean_user_option['.$args['name'].']" value="1" '.rd_clean_checked($this->options_user, $args['name']).'/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';
         
        echo $html;
    }

    public function rd_clean_user_input_calback($args) {

        if(isset($this->options_user[$args['name']])) {
            $input = esc_attr($this->options_user[$args['name']]);
        }
        else {
            $input = '';
        }

        $size = (isset($args['size'])) ? $args['size'] : '20';

        $html = '<input type="text" id="'.$args['name'].'" name="rd_clean_user_option['.$args['name'].']" value="'.$input.'" size="'.$size.'"/>';
        $html .= '<label for="'.$args['name'].'"> '.$args['description'].'</label>';

        echo $html;
    }
}

if(is_admin()) {
    $rd_clean_user = new RDCleanUser();
}