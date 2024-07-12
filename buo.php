<?php
/**
 * Plugin Name: BUO - Better User Organization
 * Plugin URI: https://github.com/your-repo/buo
 * Description: Core plugin for the BUO ecosystem, managing Google Cloud integration and plugin coordination.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: buo
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BUO_VERSION', '1.0.0');
define('BUO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BUO_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once BUO_PLUGIN_DIR . 'includes/class-buo-core.php';

/**
 * Begins execution of the plugin.
 */
function run_buo() {
    $plugin = new BUO_Core();
    $plugin->run();
}

/**
 * Load plugin textdomain.
 */
function buo_load_textdomain() {
    load_plugin_textdomain('buo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'buo_load_textdomain');

// Run the plugin
run_buo();