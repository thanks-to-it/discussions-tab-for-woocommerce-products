<?php
/*
Plugin Name: Discussions Tab for WooCommerce Products
Plugin URI: https://wordpress.org/plugins/discussions-tab-for-woocommerce-products/
Description: Creates a discussions tab for WooCommerce products.
Version: 1.5.8
Author: Algoritmika Ltd
Author URI: https://profiles.wordpress.org/algoritmika/
Text Domain: discussions-tab-for-woocommerce-products
Domain Path: /langs
WC requires at least: 3.0.0
WC tested up to: 10.2
Requires Plugins: woocommerce
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
	if ( function_exists( 'alg_wc_products_discussions_tab' ) ) {
		$plugin = alg_wc_products_discussions_tab();
		if ( method_exists( $plugin, 'set_free_version_filesystem_path' ) ) {
			$plugin->set_free_version_filesystem_path( __FILE__ );
		}
	}
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
 * @version 1.5.7
 * @since   1.1.0
 */
final class Alg_WC_Products_Discussions_Tab {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.1.0
	 */
	public $version = '1.5.8';

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
	 * $file_system_path.
	 *
	 * @since 1.4.9
	 */
	protected $file_system_path;

	/**
	 * $free_version_file_system_path.
	 *
	 * @since 1.4.9
	 */
	protected $free_version_file_system_path;

	/**
	 * $settings.
	 *
	 * @since 1.5.2
	 *
	 * @var
	 */
	protected $settings;

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
	 * @version 1.4.9
	 * @since   1.3.4
	 *
	 * @todo    [dev] readme.txt: Premium Version: "Support"?
	 * @todo    [dev] add translation (i.e. WPML/Polylang) shortcode
	 */
	function init(){

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Adds compatibility with HPOS.
		add_action( 'before_woocommerce_init', function () {
			$this->declare_compatibility_with_hpos( $this->get_filesystem_path() );
			if ( ! empty( $this->get_free_version_filesystem_path() ) ) {
				$this->declare_compatibility_with_hpos( $this->get_free_version_filesystem_path() );
			}
		} );

		// Include required files
		$this->includes();

		// Pro
		if ( 'discussions-tab-for-woocommerce-products-pro.php' === basename( $this->get_filesystem_path() ) ) {
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
	 * localize.
	 *
	 * @version 1.4.9
	 * @since   1.4.9
	 *
	 */
	function localize() {
		// Set up localisation
		load_plugin_textdomain( 'discussions-tab-for-woocommerce-products', false, dirname( plugin_basename( $this->get_filesystem_path() ) ) . '/langs/' );
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
	 * @version 1.4.9
	 * @since   1.1.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( $this->get_filesystem_path() ), array( $this, 'action_links' ) );
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
	 * @version 1.5.7
	 * @since   1.1.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_products_discussions_tab' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
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
	 * Get the plugin url.
	 *
	 * @version 1.4.9
	 * @since   1.1.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( $this->get_filesystem_path() ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.4.9
	 * @since   1.1.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( $this->get_filesystem_path() ) );
	}

	/**
	 * get_filesystem_path.
	 *
	 * @version 1.4.9
	 * @since   1.4.9
	 *
	 * @return string
	 */
	function get_filesystem_path() {
		return $this->file_system_path;
	}

	/**
	 * set_filesystem_path.
	 *
	 * @version 1.4.9
	 * @since   1.4.9
	 *
	 * @param   mixed  $file_system_path
	 */
	public function set_filesystem_path( $file_system_path ) {
		$this->file_system_path = $file_system_path;
	}

	/**
	 * get_free_version_filesystem_path.
	 *
	 * @version 1.4.9
	 * @since   1.4.9
	 *
	 * @return mixed
	 */
	public function get_free_version_filesystem_path() {
		return $this->free_version_file_system_path;
	}

	/**
	 * set_free_version_filesystem_path.
	 *
	 * @version 1.4.9
	 * @since   1.4.9
	 *
	 * @param   mixed  $free_version_file_system_path
	 */
	public function set_free_version_filesystem_path( $free_version_file_system_path ) {
		$this->free_version_file_system_path = $free_version_file_system_path;
	}

	/**
	 * Declare compatibility with custom order tables for WooCommerce.
	 *
	 * @version 1.4.9
	 * @since   1.4.9
	 *
	 * @param $filesystem_path
	 *
	 * @return void
	 * @link    https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
	 *
	 */
	function declare_compatibility_with_hpos( $filesystem_path ) {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $filesystem_path, true );
		}
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

// Initializes the plugin.
add_action( 'plugins_loaded', function () {
	$plugin = alg_wc_products_discussions_tab();
	$plugin->set_filesystem_path( __FILE__ );
	$plugin->init();
} );