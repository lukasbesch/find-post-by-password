<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://lukasbesch.com
 * @since      1.0.0
 *
 * @package    Find_Post_By_Password
 * @subpackage Find_Post_By_Password/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Find_Post_By_Password
 * @subpackage Find_Post_By_Password/includes
 * @author     Lukas Besch <connect@lukasbesch.com>
 */
class Find_Post_By_Password_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'find-post-by-password',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
