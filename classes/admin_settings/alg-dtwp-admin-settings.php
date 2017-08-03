<?php
/**
 * Discussions tab for WooCommerce Products - Admin settings
 *
 * @version 1.0.5
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Admin_Settings' ) ) {

	class Alg_DTWP_Admin_Settings {
		public static $admin_tab_id = 'alg_dtwp_admin_tab';
		public $section_general = '';


		/**
		 * Get action links for the plugins page
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return array
		 */
		public function get_action_links( $links ) {
			$custom_links = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . self::$admin_tab_id . '' ) . '">' . __( 'Settings', 'discussions-tab-for-woocommerce-products' ) . '</a>' );
			return array_merge( $custom_links, $links );
		}

		/**
		 * Create sections
		 *
		 * @version 1.0.5
		 * @since   1.0.0
		 */
		public function create_sections() {
			$plugin = alg_dtwp_get_instance();

			$section = $plugin->registry->get_admin_section_general();
			$section->init( array('tab_id' => self::$admin_tab_id) );

			$section = $plugin->registry->get_admin_section_texts();
			$section->init( array('tab_id' => self::$admin_tab_id) );

			$section = $plugin->registry->get_admin_section_advanced();
			$section->init( array('tab_id' => self::$admin_tab_id) );
		}

	}
}