<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.cownected.com
 * @since             1.0.0
 * @package           Ms_Teams_Publisher
 *
 * @wordpress-plugin
 * Plugin Name:       Teams Publisher
 * Plugin URI:        https://www.cownected.com
 * Description:       Teams Share: Easily share your WordPress posts to Microsoft Teams.
 * Version:           1.1.2
 * Author:            cownected
 * Author URI:        https://www.cownected.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       teams-publisher
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once 'lib/constants.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MS_TEAMS_PUBLISHER_VERSION', '1.0.30' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-teams-publisher-activator.php
 */
function teams_publisher_publisher_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-teams-publisher-activator.php';
	Ms_Teams_Publisher_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-teams-publisher-deactivator.php
 */
function teams_publisher_publisher_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-teams-publisher-deactivator.php';
	Ms_Teams_Publisher_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'teams_publisher_publisher_activate' );
register_deactivation_hook( __FILE__, 'teams_publisher_publisher_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-teams-publisher.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function teams_publisher_publisher_run() {

	$plugin = new Ms_Teams_Publisher();
	$plugin->run();

}
teams_publisher_publisher_run();
