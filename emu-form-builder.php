<?php
/**
 * Plugin Name: Emu Form Builder
 * Plugin URI:  https://seusite.com/
 * Description: A custom form builder for WordPress.
 * Version:     1.0.0
 * Author:      Seu Nome
 * Author URI:  https://seusite.com/
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: emu-form-builder
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// before header output

define('PLUGIN_PATH', dirname(__FILE__));
define('PLUGIN_URL', plugin_dir_url(__FILE__));

require_once PLUGIN_PATH . '/inc/api/login.php';
require_once PLUGIN_PATH . '/inc/api/register.php';
require_once PLUGIN_PATH . '/inc/api/reset-password.php';
require_once PLUGIN_PATH . '/inc/api/recaptcha-verify.php';
require_once(PLUGIN_PATH . '/inc/functions/post-type.php');
