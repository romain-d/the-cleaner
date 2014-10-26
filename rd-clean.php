<?php
/*
Plugin Name: RD Clean
Version: 1.0
Description: Plugin Clean for Back Office and other features
Author: Romain DORR
Author URI: https://romaindorr.fr
Text Domain: bo-clean
Domain Path: /languages/
License: GPL v3
*/

function printr($data) {
   echo "<pre>";
      print_r($data);
   echo "</pre>";
}

// don't load directly
if ( !defined('ABSPATH') )
    die('-1');

add_action( 'admin_menu', 'rd_clean_add_admin_menu' );


function rd_clean_add_admin_menu(  ) { 
    add_options_page( 'RD Clean', 'RD Clean', 'manage_options', 'rd_clean', 'rd_clean_options_page' );
}


function rd_clean_checkbox_field( $args ) { 

    $name = 'rd_clean_checkbox_'.$args['type'].'_'.$args['post_type'];

    $options = get_option( 'rd_clean_settings' );
    ?>
    <input type="checkbox" name="rd_clean_settings[<?php echo $name; ?>]" <?php checked($options[$name], 1); ?> value="1">
    <?php

}

function rd_clean_options_page() { 

    ?>
    <form action='options.php' method='post'>
        
        <h2>RD Clean</h2>
        
        <?php

        $args = array(
           'public'   => true
        );

        $output = 'objects';

        $post_types = get_post_types($args, $output);

        foreach ($post_types as $post_type) {
            global $wp_taxonomies; 
            // printr($wp_taxonomies);
            // printr($post_type);
            $label = $post_type->label;
            $name = $post_type->name;

            $tag = 0;
            $category = 0;

            foreach ($wp_taxonomies as $taxonomy) {
                if($taxonomy->object_type[0] == $name) {
                    if(true == $taxonomy->hierarchical) {
                        $category++;
                    }
                    else {
                        $tag ++;
                    }
                }
            }

            add_settings_section(
                'rd_clean_'.$name.'_section', 
                $label, 
                '', 
                'pluginPage'
            );

            add_settings_field( 
                'rd_clean_'.$name.'_menu_field', 
                __( 'Désactiver le menu', RD_CLEAN ), 
                'rd_clean_checkbox_field', 
                'pluginPage', 
                'rd_clean_'.$name.'_section',
                array('type' => 'menu', 'post_type' => $name)
            );

            register_setting( 'pluginPage', 'rd_clean_'.$name.'_menu_field');

            if(!empty($category)) {
                add_settings_field( 
                    'rd_clean_'.$name.'_category_field', 
                    __( 'Désactiver les catégories', RD_CLEAN ), 
                    'rd_clean_checkbox_field', 
                    'pluginPage', 
                    'rd_clean_'.$name.'_section',
                    array('type' => 'category', 'post_type' => $name)
                );

                register_setting( 'pluginPage', 'rd_clean_'.$name.'_category_field');
            }

            if(!empty($tag)) {
                add_settings_field( 
                    'rd_clean_'.$name.'_tag_field', 
                    __( 'Désactiver les mots clés', RD_CLEAN ), 
                    'rd_clean_checkbox_field', 
                    'pluginPage', 
                    'rd_clean_'.$name.'_section',
                    array('type' => 'tag', 'post_type' => $name)
                );

                register_setting( 'pluginPage', 'rd_clean_'.$name.'_tag_field');
            }

            if(post_type_supports($name, 'comments')) {
                add_settings_field( 
                    'rd_clean_'.$name.'_comment_field', 
                    __( 'Désactiver les commentaires', RD_CLEAN ), 
                    'rd_clean_checkbox_field', 
                    'pluginPage', 
                    'rd_clean_'.$name.'_section',
                    array('type' => 'comment', 'post_type' => $name)
                );

                register_setting( 'pluginPage', 'rd_clean_'.$name.'_comment_field');
            }
        }

        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
        ?>
        
    </form>
    <?php

}