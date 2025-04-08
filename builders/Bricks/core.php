<?php

if ( get_template() !== 'bricks' ) return;

add_action('init', function() {
    \Bricks\Elements::register_element( EFB_PLUGIN_PATH . '/builders/Bricks/widgets/login-form/widget.php');
}, 11);

require_once EFB_PLUGIN_PATH . '/inc/classes/EmuAuthenticate.php';