<?php
/**
 * Discussions tab for WooCommerce Products - Callbacks
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Callbacks' ) ) {

	class Alg_DTWP_Callbacks {

		/**
		 * @var Alg_DTWP_Core
		 */
		public $core;

		/**
		 * @var Alg_DTWP_Registry
		 */
		public $registry;

		/**
		 * Alg_DTWP_Callbacks constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param Alg_DTWP_Core $core
		 */
		function __construct( Alg_DTWP_Core $core ) {
			$this->core     = $core;
			$this->registry = $this->core->registry;
		}

		/**
		 * Filters "plugin_action_link" for the admin_settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $links
		 *
		 * @return array
		 */
		public function plugin_action_links_admin_settings( $links ) {
			$registry     = $this->registry;
			$custom_links = $registry->get_admin_settings()->get_action_links();
			return array_merge( $custom_links, $links );
		}

		/**
		 * Creates settings page
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $settings
		 *
		 * @return array
		 */
		public function create_settings_page( $settings ) {
			$settings[] = new Alg_DTWP_Admin_Settings_Page();
			return $settings;
		}

		/**
		 * Creates settings sections
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $sections
		 *
		 * @return array
		 */
		public function create_sections( $sections ) {
			$new_sections = $this->registry->get_admin_settings()->get_sections();
			return array_merge( $new_sections, $sections );
		}

		/**
		 * Creates initial general settings (enable|disable plugin)
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $settings
		 *
		 * @return array
		 */
		public function create_main_general_settings( $settings ) {
			$new_settings = $this->registry->get_admin_settings()->create_main_general_settings();
			return array_merge( $new_settings, $settings );
		}

	}
}