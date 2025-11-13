<?php
/**
 * Plugin Name: Resources Custom Post Type
 * Plugin URI: https://github.com/rajzshah/grepsr-assessment-task
 * Description: A custom post type for Resources with a shortcode to display latest resources in a responsive grid/list.
 * Version: 1.1.01
 * Author: Raj Shah
 * Author URI: https://raj-shah.com.np/about/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: resources-cpt
 */

if (!defined('ABSPATH')) {
	exit;
}

// plugin constants
define('RESOURCES_CPT_VERSION', '1.1.0');
define('RESOURCES_CPT_POST_TYPE', 'resources');
define('RESOURCES_CPT_FILE', __FILE__);
define('RESOURCES_CPT_DIR', plugin_dir_path(__FILE__));
define('RESOURCES_CPT_URL', plugin_dir_url(__FILE__));

// activation: register cpt and flush rewrites
function resources_cpt_activate()
{
	require_once RESOURCES_CPT_DIR . 'includes/cpt.php';
	resources_cpt_register_post_type();
	flush_rewrite_rules();
}
register_activation_hook(RESOURCES_CPT_FILE, 'resources_cpt_activate');

// deactivation: flush rewrites
function resources_cpt_deactivate()
{
	flush_rewrite_rules();
}
register_deactivation_hook(RESOURCES_CPT_FILE, 'resources_cpt_deactivate');

// load text domain
function resources_cpt_load_textdomain()
{
	load_plugin_textdomain('resources-cpt', false, dirname(plugin_basename(RESOURCES_CPT_FILE)) . '/languages');
}
add_action('plugins_loaded', 'resources_cpt_load_textdomain');

// include plugin components
require_once RESOURCES_CPT_DIR . 'includes/cpt.php';
require_once RESOURCES_CPT_DIR . 'includes/assets.php';
require_once RESOURCES_CPT_DIR . 'includes/shortcode.php';
require_once RESOURCES_CPT_DIR . 'includes/admin.php';

