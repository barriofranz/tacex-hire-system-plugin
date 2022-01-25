<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://morningstardigital.com.au/
 * @since      1.0.0
 *
 * @package    Md_Tacex_Main
 * @subpackage Md_Tacex_Main/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Md_Tacex_Main
 * @subpackage Md_Tacex_Main/includes
 * @author     Morningstar Digital <https://morningstardigital.com.au/>
 */
class Md_Tacex_Main_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'md-tacex-main',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
