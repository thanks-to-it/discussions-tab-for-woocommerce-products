<?php
/*
Plugin Name: Discussions Tab for WooCommerce Products
Plugin URI: https://wpfactory.com/item/discussions-tab-for-woocommerce-products/
Description: Creates a discussions tab for WooCommerce products.
Version: 1.2.4
Author: Thanks to IT
Author URI: http://github.com/thanks-to-it
Text Domain: discussions-tab-for-woocommerce-products
Domain Path: /langs
Copyright: © 2021 Thanks to IT
WC requires at least: 3.0.0
WC tested up to: 5.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
	public $version = '1.2.4';

	/**
	 * @var   Alg_WC_Products_Discussions_Tab The single instance of the class
	 * @since 1.1.0
	 */
	protected static $_instance = null;

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
	 * @todo    [dev] readme.txt: Premium Version: "Support"?
	 * @todo    [dev] add translation (i.e. WPML/Polylang) shortcode
	 */
	function __construct() {

		// Check for active plugins
		if (
			! $this->is_plugin_active( 'woocommerce/woocommerce.php' ) ||
			( 'discussions-tab-for-woocommerce-products.php' === basename( __FILE__ ) && $this->is_plugin_active( 'discussions-tab-for-woocommerce-products-pro/discussions-tab-for-woocommerce-products-pro.php' ) )
		) {
			return;
		}

		// Set up localisation
		load_plugin_textdomain( 'discussions-tab-for-woocommerce-products', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Pro
		if ( 'discussions-tab-for-woocommerce-products-pro.php' === basename( __FILE__ ) ) {
			require_once( 'includes/pro/class-alg-wc-products-discussions-tab-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * is_plugin_active.
	 *
	 * @version 1.1.1
	 * @since   1.1.1
	 */
	function is_plugin_active( $plugin ) {
		return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', ( array ) get_option( 'active_plugins', array() ) ) ) ||
				( is_multisite() && array_key_exists( $plugin, ( array ) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
		);
	}

	/**
	 * includes.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function includes() {
		// Functions
		require_once( 'includes/alg-wc-products-discussions-tab-functions.php' );
		// Core
		$this->core = require_once( 'includes/class-alg-wc-products-discussions-tab-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		// Admin core
		require_once( 'includes/class-alg-wc-products-discussions-tab-admin.php' );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		require_once( 'includes/settings/class-alg-wc-products-discussions-tab-settings-section.php' );
		$this->settings = array();
		$this->settings['general']  = require_once( 'includes/settings/class-alg-wc-products-discussions-tab-settings-general.php' );
		$this->settings['texts']    = require_once( 'includes/settings/class-alg-wc-products-discussions-tab-settings-texts.php' );
		$this->settings['labels']   = require_once( 'includes/settings/class-alg-wc-products-discussions-tab-settings-labels.php' );
		$this->settings['social']   = require_once( 'includes/settings/class-alg-wc-products-discussions-tab-settings-social.php' );
		$this->settings['advanced'] = require_once( 'includes/settings/class-alg-wc-products-discussions-tab-settings-advanced.php' );
		// Version updated
		if ( get_option( 'alg_wc_products_discussions_tab_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * action_links.
	 *
	 * @version 1.1.0
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
		return array_merge( $custom_links, $links );
	}

	/**
	 * add_woocommerce_settings_tab.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'includes/settings/class-alg-wc-products-discussions-tab-settings.php' );
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

add_action( 'plugins_loaded', 'alg_wc_products_discussions_tab' );
