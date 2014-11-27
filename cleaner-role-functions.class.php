<?php

class CleanerRoleFunctions {

    private $options_role;

    public function __construct() {
        $this->options_role = get_option('cleaner_role_option');

        add_action('update_option_cleaner_role_option', array($this, 'cleaner_update_role'), 1, 2);
    }

    public function cleaner_update_role($old_value, $new_value) {
        // Need to have the label of the role
        if(!empty($new_value['cleaner_role_name']) {
            $capabilities = array();
            $role_slug = 'cleaner-role';
            $role_name = esc_attr($new_value['cleaner_role_name']);

            // delete the cleaner_role_name to loop just all selected capabilities
            unset($new_value['cleaner_role_name']);

            foreach ($new_value as $option => $value) {
                $capabilities[$option] = TRUE;
            }

            remove_role($role_slug);
            add_role($role_slug, $role_name, $capabilities);
        }
    }
}

$cleaner_role_functions = new CleanerRoleFunctions();
