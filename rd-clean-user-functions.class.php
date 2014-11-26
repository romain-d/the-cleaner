<?php

class RDCleanUserFunctions {

    private $options_user;

    public function __construct() {
        $this->options_user = get_option('rd_clean_user_option');

        add_action('update_option_rd_clean_user_option', array($this, 'rd_clean_user_role'), 1, 2);
    }

    public function rd_clean_user_role($old_value, $new_value) {

        $capabilities = array();
        $role_slug = 'rd-clean-role';
        $role_name = esc_attr($new_value['rd_clean_user_role_name']);

        // delete the rd_clean_user_role_name to loop just all selected capabilities
        unset($new_value['rd_clean_user_role_name']);

        foreach ($new_value as $option => $value) {
            $capabilities[$option] = TRUE;
        }

        remove_role($role_slug);
        add_role($role_slug, $role_name, $capabilities);
    }
}

$rd_clean_user_functions = new RDCleanUserFunctions();
