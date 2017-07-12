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
		public function get_action_links($links) {
			$custom_links = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . self::$admin_tab_id . '' ) . '">' . __( 'Settings', 'discussions-tab-for-woocommerce-products' ) . '</a>' );
			return array_merge( $custom_links, $links );
		}

		public function create_main_general_settings($settings) {
			$new_settings = array(
				array(
					'title' => __( 'General options', 'discussions-tab-for-woocommerce-products' ),
					'type'  => 'title',
					'id'    => 'alg_dtwp_opt_general',
				),
				array(
					'title'   => __( 'Enable plugin', 'discussions-tab-for-woocommerce-products' ),
					'desc'    => __( 'Enables plugin "Discussions tab for WooCommerce Products".', 'discussions-tab-for-woocommerce-products' ),
					'id'      => $this->option_enable,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_dtwp_opt_general',
				),
			);

			return array_merge( $new_settings, $settings );
		}

		public function get_sections($sections) {
			$new_sections = array( $this->section_general => 'General' );
			return array_merge( $new_sections, $sections );
		}
	}
}