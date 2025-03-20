<?php

// Functions

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

function efb_metabox($post) {
    // Verifica se está no Post Type correto
    if ($post->post_type !== 'emu_form_builder') {
        return;
    }

    $post_id = $post->ID;

    echo 
    
    '<div class="postbox">

        <div class="inside">
            [emu_form_builder id='. $post_id .']
        </div>

        <div class="inside">

            <select>

            <option value="">Select an type</option>
            <option value="loginRegister">Login and Register form</option>
            
            </select>

        </div>

    </div>';
}

function efb_shortcode($atts){
    $atts = shortcode_atts(array(
        'id' => NULL
    ), $atts);

    include_once PLUGIN_PATH . '/inc/form-templates/login-register.php';
    
    return efb_login_register();
}

// Actions
add_shortcode('emu_form_builder', 'efb_shortcode');

add_action('init', 'efb_post_type');

add_action('edit_form_after_title', 'efb_metabox');