<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://miniorange.com
 * @since             1.0.0
 * @package           Mo_Revisions
 *
 * @wordpress-plugin
 * Plugin Name:       miniOrange Revisions
 * Plugin URI:        https://miniorange.com
 * Description:       miniOrange Revisions is an ultimate tool that helps you to revert back the content changes that have been made in Wordpress website. It allows you to control the updates to published content on your website. After all the changes have been made, the administrator can decide whether these changes need to be reflected on the main website.
 * Version:           1.0.1
 * Author:            miniOrange
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       page-post-revisions-public-post-preview
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

define( 'MO_REVISIONS_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mo-revisions-activator.php
 */
function activate_mo_revisions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mo-revisions-activator.php';
	Mo_Revisions_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mo-revisions-deactivator.php
 */
function deactivate_mo_revisions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mo-revisions-deactivator.php';
	Mo_Revisions_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mo_revisions' );
register_deactivation_hook( __FILE__, 'deactivate_mo_revisions' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mo-revisions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mo_revisions() {

	$plugin = new Mo_Revisions();
	$plugin->run();

}
run_mo_revisions();
