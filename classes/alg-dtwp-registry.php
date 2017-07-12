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

		/**
		 * @var Alg_DTWP_Admin_Settings
		 */
		private $admin_settings;

		/**
		 * @var Alg_DTWP_Discussions_Tab
		 */
		private $discussions_tab;

		/**
		 * @var Alg_DTWP_Discussions
		 *
		 */
		private $discussions;

		/**
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return Alg_DTWP_Admin_Settings
		 */
		public function get_admin_settings() {
			if ( $this->admin_settings == null ) {
				$this->admin_settings = new Alg_DTWP_Admin_Settings();
			}
			return $this->admin_settings;
		}

		/**
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return Alg_DTWP_Discussions_Tab
		 */
		public function get_discussions_tab() {
			if ( $this->discussions_tab == null ) {
				$this->discussions_tab = new Alg_DTWP_Discussions_Tab();
			}
			return $this->discussions_tab;
		}

		/**
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return Alg_DTWP_Discussions
		 */
		public function get_discussions() {
			if ( $this->discussions == null ) {
				$this->discussions = new Alg_DTWP_Discussions();
			}
			return $this->discussions;
		}

	}
}