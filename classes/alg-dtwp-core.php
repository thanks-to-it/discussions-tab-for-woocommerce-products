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
		}

		/**
		 * Creates admin settings placeholder
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		private function handle_admin_settings() {
			$callbacks = $this->callbacks;

			// Plugin settings link
			add_filter( 'plugin_action_links_' . $this->get_plugin_basename(), array( $callbacks, 'plugin_action_links_admin_settings' ) );

			// Creates settings pages
			add_filter( 'woocommerce_get_settings_pages', array( $callbacks, 'create_settings_page' ) );

			$admin_settings = $this->registry->get_admin_settings();
			$admin_id = Alg_DTWP_Admin_Settings::$admin_tab_id;

			// Creates settings sections
			add_filter( "woocommerce_get_sections_{$admin_id}", array( $callbacks, 'create_sections' ) );

			// Creates the initial general settings
			add_filter( "woocommerce_get_settings_{$admin_id}_" . $admin_settings->section_general, array( $callbacks, 'create_main_general_settings' ), PHP_INT_MAX );
		}
	}
}