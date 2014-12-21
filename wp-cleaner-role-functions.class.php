<?php

class WPCleanerRoleFunctions {

    private $options_role;

    public function __construct() {
        $this->options_role = get_option('wpcleaner_role_option');

        add_action('update_option_wpcleaner_role_option', array($this, 'wpcleaner_update_role'), 1, 2);
    }

    public function wpcleaner_update_role($old_value, $new_value) {
        // Need to have the label of the role
        if(!empty($new_value['wpcleaner_role_name'])) {
            $capabilities = array();
            $role_slug = 'wpcleaner-role';
            $role_name = esc_attr($new_value['wpcleaner_role_name']);

            // delete the wpcleaner_role_name to loop just all selected capabilities
            unset($new_value['wpcleaner_role_name']);

            foreach ($new_value as $option => $value) {
                $capabilities[$option] = TRUE;
            }

            remove_role($role_slug);
            add_role($role_slug, $role_name, $capabilities);
        }
    }
}

$wpcleaner_role_functions = new WPCleanerRoleFunctions();
