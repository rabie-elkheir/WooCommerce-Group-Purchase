<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/rabie-elkheir
 * @since      1.0.0
 *
 * @package    Woocommerce_Group_Purchase
 * @subpackage Woocommerce_Group_Purchase/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Group_Purchase
 * @subpackage Woocommerce_Group_Purchase/includes
 * @author     Rabie Alkheir <info@rabiie.com>
 */
class Woocommerce_Group_Purchase_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-group-purchase',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
