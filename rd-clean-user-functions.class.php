<?php

class RDCleanUserFunctions {

    private $options_user;

    public function __construct() {
        $this->options_user = get_option('rd_clean_user_option');

        $count = count($this->options_user);

        if(!empty($this->options_user['rd_clean_user_role_name']) && $count > 1) {
            add_action('init', array($this, 'rd_clean_user_role'));
        }
    }

    public function rd_clean_user_role() {

        $capabilities = array();
        $role_slug = 'rd-clean-role';
        $role_name = esc_attr($this->options_user['rd_clean_user_role_name']);

        foreach ($this->options_user as $option => $value) {
            if($option != 'rd_clean_user_role_name') {
                $capabilities[$option] = TRUE;
            }
        }
        remove_role($role_slug);
        add_role($role_slug, $role_name, $capabilities);
    }
}

$rd_clean_user_functions = new RDCleanUserFunctions();
