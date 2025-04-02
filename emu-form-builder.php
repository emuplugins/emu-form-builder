<?php
/**
 * Plugin Name: Emu Form Builder
 * Description: A custom form builder for WordPress.
 * Version:     1.0.2
 * Author:      Emu Plugins
 * License:     GPL-2.0+
 * Text Domain: emu-form-builder
 * Domain Path: /languages
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
// before header output

define('EFB_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EFB_PLUGIN_URL', plugin_dir_url(__FILE__));

if (is_admin()) {
    require_once EFB_PLUGIN_PATH . '/update-handler.php';
}

require_once EFB_PLUGIN_PATH . '/inc/classes/EmuAuthenticate.php';
require_once EFB_PLUGIN_PATH . '/inc/api/login.php';
require_once EFB_PLUGIN_PATH . '/inc/api/register.php';
require_once EFB_PLUGIN_PATH . '/inc/api/reset-password.php';
require_once EFB_PLUGIN_PATH . '/inc/api/send-password-code.php';
require_once EFB_PLUGIN_PATH . '/inc/api/confirm-code.php';
require_once(EFB_PLUGIN_PATH . '/inc/functions/post-type.php');
if(!is_admin()){
    require_once EFB_PLUGIN_PATH . '/inc/form-templates/login-register.php';
}
require_once EFB_PLUGIN_PATH . '/builders/core.php';
