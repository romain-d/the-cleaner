<?php

class CleanerUserFunctions {

    private $options_user;

    public function __construct() {
        $this->options_user = get_option('cleaner_user_option');

        add_action('update_option_cleaner_user_option', array($this, 'cleaner_user_role'), 1, 2);
    }

    public function cleaner_user_role($old_value, $new_value) {

        $capabilities = array();
        $role_slug = 'cleaner-role';
        $role_name = esc_attr($new_value['cleaner_user_role_name']);

        // delete the cleaner_user_role_name to loop just all selected capabilities
        unset($new_value['cleaner_user_role_name']);

        foreach ($new_value as $option => $value) {
            $capabilities[$option] = TRUE;
        }

        remove_role($role_slug);
        add_role($role_slug, $role_name, $capabilities);
    }
}

$cleaner_user_functions = new CleanerUserFunctions();
