<?php
/**
 * Discussions tab for WooCommerce Products - Core Class
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Core' ) ) {

	class Alg_DTWP_Core extends Alg_DTWP_WP_Plugin {

		/**
		 * @var Alg_DTWP_Callbacks
		 */
		public $callbacks;

		/**
		 * @var Alg_DTWP_Registry
		 */
		public $registry;

		/**
		 * Initializes the plugin
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function init() {
			parent::init();

			// Get registry
			$this->registry = new Alg_DTWP_Registry();

			// Get callbacks
			$this->callbacks = new Alg_DTWP_Callbacks( $this );

			// Handle admin settings
			$this->handle_admin_settings();

			// Handle discussions
			$this->handle_discussions();
		}

		/**
		 * Handles discussions
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		private function handle_discussions() {
			$callbacks = $this->callbacks;
			add_filter( 'woocommerce_product_tabs', array( $callbacks, 'discussions_wc_product_tabs' ) );
			add_filter( 'comments_array', array( $callbacks, 'discussions_comments_array' ), 10, 2 );
			add_action( 'comment_form', array( $callbacks, 'discussions_comment_form' ) );
			add_filter( 'preprocess_comment', array( $callbacks, 'discussions_preprocess_comment' ) );

			//add_action( 'pre_get_comments', array( $callbacks, 'discussions_pre_get_comments' ) );
			//add_filter( 'woocommerce_product_review_list_args', array( $callbacks, 'discussions_wc_product_review_list_args' ) );
		}

		/**
		 * Creates admin settings placeholder
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		private function handle_admin_settings() {
			$callbacks = $this->callbacks;
			$admin_settings = $this->registry->get_admin_settings();
			$admin_id       = Alg_DTWP_Admin_Settings::$admin_tab_id;

			// Plugin settings link
			add_filter( 'plugin_action_links_' . $this->get_plugin_basename(), array( $callbacks, 'admin_plugin_action_links' ) );

			// Creates settings pages
			add_filter( 'woocommerce_get_settings_pages', array( $callbacks, 'admin_wc_get_settings_pages' ) );

			// Creates settings sections
			add_filter( "woocommerce_get_sections_{$admin_id}", array( $callbacks, 'admin_wc_get_sections' ) );

			// Creates the initial general settings
			add_filter( "woocommerce_get_settings_{$admin_id}_" . $admin_settings->section_general, array( $callbacks, 'admin_wc_get_settings_general' ), PHP_INT_MAX );
		}
	}
}