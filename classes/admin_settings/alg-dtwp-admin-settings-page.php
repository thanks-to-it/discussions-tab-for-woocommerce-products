<?php
/**
 * Discussions tab for WooCommerce Products - Settings page
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

		/**
		 * Constructor
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->id    = Alg_DTWP_Admin_Settings::$admin_tab_id;
			$this->label = __( 'Discussions', 'discussions-tab-for-woocommerce-products' );
			parent::__construct();
		}

		/**
		 * Get settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return mixed|void
		 */
		public function get_settings() {
			global $current_section;
			return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );
		}

	}
}