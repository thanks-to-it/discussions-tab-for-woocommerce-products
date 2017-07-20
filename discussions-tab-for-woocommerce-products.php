<?php
/*
Plugin Name: Discussions Tab for WooCommerce Products
Description: Creates a discussions tab for WooCommerce Products
Version: 1.0.1
Author: Algoritmika Ltd
Author URI: http://algoritmika.com
Copyright: © 2017 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: discussions-tab-for-woocommerce-products
Domain Path: /languages
*/

add_action( 'plugins_loaded', 'alg_dtwp_start_plugin' );
if ( ! function_exists( 'alg_dtwp_start_plugin' ) ) {

	/**
	 * Starts the plugin
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_dtwp_start_plugin() {
		require __DIR__ . '/vendor/autoload.php';
		$plugin = alg_dtwp_get_instance();
		$plugin->config( array(
			'file'        => __FILE__,
			'text_domain' => 'discussions-tab-for-woocommerce-products'
		) );
		$plugin->init();
	}
}

if ( ! function_exists( 'alg_dtwp_get_instance' ) ) {

	/**
	 * Gets the plugin's instance
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return Alg_DTWP_Core
	 */
	function alg_dtwp_get_instance() {
		$plugin = Alg_DTWP_Core::get_instance();
		return $plugin;
	}
}