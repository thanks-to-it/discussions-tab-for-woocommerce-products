<?php
/**
 * Discussions Tab for WooCommerce Products - Support Representative
 *
 * @version 1.2.9
 * @since   1.2.7
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_My_Account' ) ) :

	class Alg_WC_Products_Discussions_Tab_My_Account {

		protected $tab_title='';

		/**
		 * @version 1.2.9
		 * @since   1.2.7
		 *
		 * Alg_WC_Products_Discussions_Tab_My_Account constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'my_custom_endpoints' ) );
			add_filter( 'query_vars', array( $this, 'my_custom_query_vars' ), 0 );
			add_action( 'after_switch_theme', array( $this, 'my_custom_flush_rewrite_rules' ) );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'my_custom_my_account_menu_items' ) );
			add_action( 'woocommerce_account_' . $this->get_tab_id() . '_endpoint', array( $this, 'my_custom_endpoint_content' ) );
			add_filter( 'the_title', array( $this, 'my_custom_endpoint_title' ) );
			register_activation_hook( alg_wc_products_discussions_tab()->get_filename_path(), array( $this, 'my_custom_flush_rewrite_rules' ) );
			register_deactivation_hook( alg_wc_products_discussions_tab()->get_filename_path(), array( $this, 'my_custom_flush_rewrite_rules' ) );
			add_action( 'alg_wc_products_discussions_tab_plugin_update', array( $this, 'my_custom_flush_rewrite_rules' ) );
			add_action( 'woocommerce_settings_save_' . 'alg_wc_products_discussions_tab', array( $this, 'my_custom_flush_rewrite_rules' ) );
		}

		/**
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 * @return string
		 */
		function get_tab_id(){
			return sanitize_title( sanitize_text_field( apply_filters( 'alg_dtwp_filter_tab_id', get_option( 'alg_dtwp_opt_tab_id', 'discussions' ) ) ) );
		}

		/**
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 * @return string
		 */
		function get_tab_title() {
			if ( empty( $this->tab_title ) ) {
				$this->tab_title = get_option( 'alg_dtwp_discussions_label', __( 'Discussions', 'discussions-tab-for-woocommerce-products' ) );
			}
			return $this->tab_title;
		}

		/**
		 * Register new endpoint to use inside My Account page.
		 *
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
		 */
		function my_custom_endpoints() {
			if ( ! apply_filters( 'alg_dtwp_my_account_tab_validation', false ) ) {
				return;
			}
			add_rewrite_endpoint( $this->get_tab_id(), EP_ROOT | EP_PAGES );
		}

		/**
		 * Add new query var.
		 *
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 * @param array $vars
		 * @return array
		 */
		function my_custom_query_vars( $vars ) {
			$vars[] = $this->get_tab_id();

			return $vars;
		}

		/**
		 * Flush rewrite rules on plugin activation.
		 *
		 * @version 1.2.7
		 * @since   1.2.7
		 */
		function my_custom_flush_rewrite_rules() {
			add_rewrite_endpoint( $this->get_tab_id(), EP_ROOT | EP_PAGES );
			flush_rewrite_rules();
		}

		/**
		 * Insert the new endpoint into the My Account menu.
		 *
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 * @param array $items
		 * @return array
		 */
		function my_custom_my_account_menu_items( $items ) {
			if ( ! apply_filters( 'alg_dtwp_my_account_tab_validation', false ) ) {
				return $items;
			}
			// Remove the logout menu item.
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );

			// Insert your custom endpoint.
			$items[ $this->get_tab_id() ] = $this->get_tab_title();

			// Insert back the logout item.
			$items['customer-logout'] = $logout;

			return $items;
		}

		/**
		 * Endpoint HTML content.
		 *
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 */
		function my_custom_endpoint_content() {
			do_action( 'alg_dtwp_my_account_tab_content' );
		}

		/**
		 * Change endpoint title.
		 *
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 * @param string $title
		 *
		 * @return string
		 */
		function my_custom_endpoint_title( $title ) {
			global $wp_query;

			$is_endpoint = isset( $wp_query->query_vars[$this->get_tab_id()] );

			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = $this->get_tab_title();

				remove_filter( 'the_title', array($this,'my_custom_endpoint_title' ));
			}

			return $title;
		}


	}

endif;

return new Alg_WC_Products_Discussions_Tab_My_Account();
