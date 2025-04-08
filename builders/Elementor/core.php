<?php

function efbEnqueueScripts() {
	
    $gSiteKey = get_option('efb_grecaptcha_key');
    $gSiteSecret = get_option('efb_grecaptcha_secret');

    if (!empty($gSiteKey) && !empty($gSiteSecret)) {
        wp_register_script(
            'google-recaptcha',
            'https://www.google.com/recaptcha/api.js',
            [],
            null,
            true
        );
    }

    wp_register_style(
        'emu-login-handler',
        EFB_PLUGIN_URL . 'assets/css/form.css',
        array(),
        time()
    );

    wp_register_script(
        'emu-form-js',
        EFB_PLUGIN_URL . 'assets/js/form.js',
        array(),
        time(),
        true
    );
     
    wp_register_script(
        'emu-login-handler',
        EFB_PLUGIN_URL . 'assets/js/login.js',
        array(),
        time(),
        true
    );

}
add_action( 'elementor/frontend/before_register_scripts', 'efbEnqueueScripts' );


function efbWidgetsRegisterElementor( $widgets_manager ) {

    require_once EFB_PLUGIN_PATH . '/inc/classes/EmuAuthenticate.php';

    require_once( __DIR__ . '/widgets/login-form/widget.php' );
    $widgets_manager->register( new \EfbLoginFormElementor() );

}

add_action( 'elementor/widgets/register', 'efbWidgetsRegisterElementor' );