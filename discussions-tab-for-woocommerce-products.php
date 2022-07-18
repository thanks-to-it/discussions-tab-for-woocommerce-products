<?php
/*
Plugin Name: Discussions Tab for WooCommerce Products
Plugin URI: https://wpfactory.com/item/discussions-tab-for-woocommerce-products/
Description: Creates a discussions tab for WooCommerce products.
Version: 1.4.1
Author: Thanks to IT
Author URI: http://github.com/thanks-to-it
Text Domain: discussions-tab-for-woocommerce-products
Domain Path: /langs
Copyright: © 2022 Thanks to IT
WC requires at least: 3.0.0
WC tested up to: 6.7
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Handle is_plugin_active function
if ( ! function_exists( 'alg_wc_products_discussions_tab_is_plugin_active' ) ) {
	/**
	 * alg_wc_products_discussions_tab_is_plugin_active.
	 *
	 * @version 1.3.7
	 * @since   1.3.7
	 */
	function alg_wc_products_discussions_tab_is_plugin_active( $plugin ) {
		return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', ( array ) get_option( 'active_plugins', array() ) ) ) ||
				( is_multisite() && array_key_exists( $plugin, ( array ) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
		);
	}
}

// Check for active plugins
if (
	! alg_wc_products_discussions_tab_is_plugin_active( 'woocommerce/woocommerce.php' ) ||
	( 'discussions-tab-for-woocommerce-products.php' === basename( __FILE__ ) && alg_wc_products_discussions_tab_is_plugin_active( 'discussions-tab-for-woocommerce-products-pro/discussions-tab-for-woocommerce-products-pro.php' ) )
) {
	return;
}

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab' ) ) :
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
endif;

// Autoloader
$autoloader = new WPFactory\WPFactory_Autoloader\WPFactory_Autoloader();
$autoloader->add_namespace( 'WPFactory\WC_Products_Discussions_Tab', plugin_dir_path( __FILE__ ) . '/src/php' );
$autoloader->init();

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab' ) ) :

/**
 * Main Alg_WC_Products_Discussions_Tab Class
 *
 * @class   Alg_WC_Products_Discussions_Tab
 * @version 1.1.1
 * @since   1.1.0
 */
final class Alg_WC_Products_Discussions_Tab {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.1.0
	 */
	public $version = '1.4.1';

	/**
	 * @var   Alg_WC_Products_Discussions_Tab The single instance of the class
	 * @since 1.1.0
	 */
	protected static $_instance = null;

	/**
	 * Core.
	 *
	 * @since 1.3.4
	 *
	 * @var \WPFactory\WC_Products_Discussions_Tab\Core
	 */
	public $core;

	/**
	 * Main Alg_WC_Products_Discussions_Tab Instance
	 *
	 * Ensures only one instance of Alg_WC_Products_Discussions_Tab is contentCalled or can be contentCalled.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @static
	 * @return  Alg_WC_Products_Discussions_Tab - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Products_Discussions_Tab Constructor.
	 *
	 * @version 1.1.1
	 * @since   1.1.0
	 * @access  public
	 */
	function __construct() {

	}

	/**
	 * init.
	 *
	 * @version 1.3.4
	 * @since   1.3.4
	 *
	 * @todo    [dev] readme.txt: Premium Version: "Support"?
	 * @todo    [dev] add translation (i.e. WPML/Polylang) shortcode
	 */
	function init(){
		// Set up localisation
		load_plugin_textdomain( 'discussions-tab-for-woocommerce-products', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Pro
		if ( 'discussions-tab-for-woocommerce-products-pro.php' === basename( __FILE__ ) ) {
			new \WPFactory\WC_Products_Discussions_Tab\Pro\Pro();
		}

		// Core
		$this->core = new \WPFactory\WC_Products_Discussions_Tab\Core();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}
	}

	/**
	 * includes.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function includes() {
		// Functions
		require_once( 'src/php/functions.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.3.4
	 * @since   1.1.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		// Admin core
		new \WPFactory\WC_Products_Discussions_Tab\Admin();
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );

		$this->settings               = array();
		$this->settings['general']    = new \WPFactory\WC_Products_Discussions_Tab\Settings\Settings_General();
		$this->settings['email']      = new \WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Email();
		$this->settings['texts']      = new \WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Texts();
		$this->settings['labels']     = new \WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Labels();
		$this->settings['social']     = new \WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Social();
		$this->settings['advanced']   = new \WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Advanced();
		// Version updated
		if ( get_option( 'alg_wc_products_discussions_tab_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * action_links.
	 *
	 * @version 1.3.3
	 * @since   1.1.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_products_discussions_tab' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'discussions-tab-for-woocommerce-products.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a target="_blank" href="https://wpfactory.com/item/discussions-tab-for-woocommerce-products/">' .
				__( 'Unlock All', 'discussions-tab-for-woocommerce-products' ) . '</a>';
		}
		return array_unique(array_merge( $custom_links, $links ));
	}

	/**
	 * add_woocommerce_settings_tab.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = new \WPFactory\WC_Products_Discussions_Tab\Settings\Settings();
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function version_updated() {
		update_option( 'alg_wc_products_discussions_tab_version', $this->version );
	}

	/**
	 * plugin_url.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * get_filename_path.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 *
	 * @return string
	 */
	function get_filename_path(){
		return __FILE__;
	}

	/**
	 * plugin_path.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_wc_products_discussions_tab' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Products_Discussions_Tab to prevent the need to use globals.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @return  Alg_WC_Products_Discussions_Tab
	 * @todo    [dev] (maybe) `plugins_loaded`?
	 */
	function alg_wc_products_discussions_tab() {
		return Alg_WC_Products_Discussions_Tab::instance();
	}
}

$plugin = alg_wc_products_discussions_tab();
$plugin->init();