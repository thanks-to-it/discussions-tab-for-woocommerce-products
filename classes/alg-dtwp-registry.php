<?php
/**
 * Discussions tab for WooCommerce Products - Registry
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Registry' ) ) {

	class Alg_DTWP_Registry {
		protected $admin_settings;

		/**
		 * @return Alg_DTWP_Admin_Settings
		 */
		public function get_admin_settings() {
			if ( $this->admin_settings == null ) {
				$this->admin_settings = new Alg_DTWP_Admin_Settings();
			}
			return $this->admin_settings;
		}

	}
}