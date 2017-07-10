<?php
/**
 * Discussions tab for WooCommerce Products - Admin settings
 *
 * @version 1.0.0
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
		public $option_enable = 'alg_dtwp_opt_enable';

		/**
		 * Get action links for the plugins page
		 *
		 * @return array
		 */
		public function get_action_links() {
			$custom_links = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . self::$admin_tab_id . '' ) . '">' . __( 'Settings', 'discussions-tab-for-woocommerce-products' ) . '</a>' );
			return $custom_links;
		}

		public function create_main_general_settings() {
			$new_settings = array(
				array(
					'title' => __( 'General options', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_dtwp_opt_general',
				),
				array(
					'title'   => __( 'Enable plugin', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enables plugin "Discussions tab for WooCommerce Products".', 'wish-list-for-woocommerce' ),
					'id'      => $this->option_enable,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_dtwp_opt_general',
				),
			);

			return $new_settings;
		}

		public function get_sections() {
			return array( $this->section_general => 'General' );
		}
	}
}