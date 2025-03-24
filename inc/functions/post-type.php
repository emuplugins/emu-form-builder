<?php

if ( ! defined('ABSPATH') ) exit;

// Functions

// post type
function efb_post_type(){
    $labels = [
        'name'               => __('Forms', 'emu-form-builder'),
        'singular_name'      => __('Form', 'emu-form-builder'),
        'menu_name'          => __('Forms', 'emu-form-builder'),
        'add_new'            => __('Add New', 'emu-form-builder'),
        'add_new_item'       => __('Add New Form', 'emu-form-builder'),
        'edit_item'          => __('Edit Form', 'emu-form-builder'),
        'new_item'           => __('New Form', 'emu-form-builder'),
        'view_item'          => __('View Form', 'emu-form-builder'),
        'search_items'       => __('Search Forms', 'emu-form-builder'),
        'not_found'          => __('No Forms found', 'emu-form-builder'),
        'not_found_in_trash' => __('No Forms found in Trash', 'emu-form-builder'),
    ];

    $args = [
        'label'               => __('Forms', 'emu-form-builder'),
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-feedback',
        'capability_type'     => 'post',
        'supports'            => ['title'],
        'has_archive'         => false,
        'exclude_from_search' => true,
        'show_in_rest'        => false
    ];

    register_post_type('emu_form_builder', $args);
};
add_action('init', 'efb_post_type');

function efb_add_options_page() {
    add_submenu_page(
        'edit.php?post_type=emu_form_builder', // Adiciona no menu do post type
        __('reCAPTCHA Settings', 'emu-form-builder'),
        __('reCAPTCHA', 'emu-form-builder'),
        'manage_options',
        'efb-recaptcha-settings',
        'efb_render_options_page'
    );
}
add_action('admin_menu', 'efb_add_options_page');

function efb_render_options_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('reCAPTCHA Settings', 'emu-form-builder'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('efb_recaptcha_settings_group');
            do_settings_sections('efb-recaptcha-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function efb_register_settings() {
    register_setting('efb_recaptcha_settings_group', 'efb_grecaptcha_key');
    register_setting('efb_recaptcha_settings_group', 'efb_grecaptcha_secret');

    add_settings_section(
        'efb_recaptcha_section',
        __('reCAPTCHA Configuration', 'emu-form-builder'),
        null,
        'efb-recaptcha-settings'
    );

    add_settings_field(
        'efb_grecaptcha_key',
        __('Site Key', 'emu-form-builder'),
        'efb_grecaptcha_key_callback',
        'efb-recaptcha-settings',
        'efb_recaptcha_section'
    );

    add_settings_field(
        'efb_grecaptcha_secret',
        __('Secret Key', 'emu-form-builder'),
        'efb_grecaptcha_secret_callback',
        'efb-recaptcha-settings',
        'efb_recaptcha_section'
    );
}
add_action('admin_init', 'efb_register_settings');

function efb_grecaptcha_key_callback() {
    $key = get_option('efb_grecaptcha_key', '');
    echo '<input type="text" name="efb_grecaptcha_key" value="' . esc_attr($key) . '" class="regular-text" />';
}

function efb_grecaptcha_secret_callback() {
    $secret = get_option('efb_grecaptcha_secret', '');
    echo '<input type="text" name="efb_grecaptcha_secret" value="' . esc_attr($secret) . '" class="regular-text" />';
}


// // metabox
// function efb_metabox($post) {

//     // Verifica se está no Post Type correto
//     if ($post->post_type !== 'emu_form_builder') {
//         return;
//     }

//     $post_id = $post->ID;

//     echo 
    
//     '<div class="postbox">

//         <div class="inside">
//             [emu_form_builder id='. $post_id .']
//         </div>

//         <div class="inside">

//             <select>

//             <option value="">Select an type</option>
//             <option value="loginRegister">Login and Register form</option>
            
//             </select>

//         </div>

//     </div>';
// }

// add_action('edit_form_after_title', 'efb_metabox');