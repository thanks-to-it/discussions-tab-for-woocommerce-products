<?php
/**
 * Discussions tab for WooCommerce Products - Admin Section - General
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Admin_Section_General' ) ) {

	class Alg_DTWP_Admin_Section_General extends Alg_DTWP_Admin_Section {

		public $option_enable = 'alg_dtwp_opt_enable';

		/**
		 * Constructor
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function __construct() {
			$this->section_id    = '';
			$this->section_label = __( 'General', 'discussions-tab-for-woocommerce-products' );
		}

		/**
		 * Get settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function get_settings( $settings ) {
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
			return parent::get_settings( array_merge( $new_settings, $settings ) );
		}


	}
}