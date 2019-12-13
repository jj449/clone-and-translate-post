<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wa.hauchat.com/
 * @since      1.0.0
 *
 * @package    Clone_And_Translate_Post
 * @subpackage Clone_And_Translate_Post/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Clone_And_Translate_Post
 * @subpackage Clone_And_Translate_Post/includes
 * @author     YongHuaGao <jeffrey.kao.taipei@gmail.com>
 */
class Clone_And_Translate_Post_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'clone-and-translate-post',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
