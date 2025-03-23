<?php
/**
 * Plugin Name: Emu Form Builder
 * Description: A custom form builder for WordPress.
 * Version:     1.0.0
 * Author:      Emu Plugins
 * License:     GPL-2.0+
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
require_once(PLUGIN_PATH . '/inc/functions/post-type.php');