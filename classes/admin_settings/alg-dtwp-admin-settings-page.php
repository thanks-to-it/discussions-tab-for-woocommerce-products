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

if ( ! class_exists( 'Alg_DTWP_Admin_Settings_Page' ) ) {

	class Alg_DTWP_Admin_Settings_Page extends WC_Settings_Page {


		public function __construct() {
			$this->id    = Alg_DTWP_Admin_Settings::$admin_tab_id;
			$this->label = __( 'Discussions', 'discussions-tab-for-woocommerce-products' );
			parent::__construct();
		}

		/**
		 * get_settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		/*function get_settings() {
			global $current_section;
			return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );
		}*/

		public function get_settings() {
			global $current_section;
			//error_log(print_r('woocommerce_get_settings_' . $this->id . '_' . $current_section,true));
			return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );
		}

		/*function get_sections($sections){
			return apply_filters( 'alg_dtwp_admin_sections', array( $this->section_general => 'General' ) );
			//apply_filters('alg_dtwp_admin_sections',array());
			//$sections[ $this->id ] = $this->desc;
		}*/



		/**
		 * Update settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		/*public function update_settings() {
			woocommerce_update_options( $this->get_settings() );
		}*/

		/**
		 * Creates settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		/*public function settings_tab() {
			woocommerce_admin_fields( $this->get_settings() );
		}*/

		/**
		 * Gets settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		/*public function get_settings() {
			$settings = array(
				array(
					'name' => __( 'Discussions', 'discussions-tab-for-woocommerce-products' ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'alg_gotwc_general_opt',
				),
				array(
					'name'    => __( 'Tracking page url', 'discussions-tab-for-woocommerce-products' ),
					'type'    => 'text',
					'class'   => 'regular-input',
					'default' => home_url( '/tracking/' ),
					'desc'    => __( 'The url of the tracking page', 'discussions-tab-for-woocommerce-products' ),
					'id'      => 'test',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_gotwc_general_opt',
				),
			);
			return apply_filters( "wc_{$this->admin_tab_id}_settings", $settings );
		}*/

		/**
		 * Adds tab
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		/*public function add_tab( $settings_tabs ) {
			$settings_tabs[ $this->admin_tab_id ] = __( 'Discussions', 'discussions-tab-for-woocommerce-products' );
			return $settings_tabs;
		}*/
	}
}