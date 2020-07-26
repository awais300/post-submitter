<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Post_Submitter
 *
 * @wordpress-plugin
 * Plugin Name:       Post Submitter
 * Plugin URI:        http://example.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Awais
 * Author URI:        http://example.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-submitter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'POST_SUBMITTER_VERSION', '1.0.0' );
define( 'POST_SUBMITTER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-post-submitter-activator.php
 */
function activate_post_submitter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-submitter-activator.php';
	Post_Submitter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-post-submitter-deactivator.php
 */
function deactivate_post_submitter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-submitter-deactivator.php';
	Post_Submitter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_post_submitter' );
register_deactivation_hook( __FILE__, 'deactivate_post_submitter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-post-submitter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_post_submitter() {

	$plugin = new Post_Submitter();
	$plugin->run();

}

run_post_submitter();
