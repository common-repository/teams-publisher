<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.cownected.com
 * @since      1.0.0
 *
 * @package    Ms_Teams_Publisher
 * @subpackage Ms_Teams_Publisher/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ms_Teams_Publisher
 * @subpackage Ms_Teams_Publisher/includes
 * @author     Thibaut Colson <hello@cownected.com>
 */
class Ms_Teams_Publisher_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'teams-publisher',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
