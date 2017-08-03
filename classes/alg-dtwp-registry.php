<?php
/**
 * Discussions tab for WooCommerce Products - Registry
 *
 * @version 1.0.5
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
		 * @var Alg_DTWP_Discussions_Comments_CMB
		 */
		private $dicussions_comments_cmb;

		/**
		 * @var Alg_DTWP_Discussions
		 *
		 */
		private $discussions;

		/**
		 * @var Alg_DTWP_Admin_Section_General
		 */
		private $admin_section_general;

		/**
		 * @var Alg_DTWP_Admin_Section_Texts
		 */
		private $admin_section_texts;

		/**
		 * @var Alg_DTWP_Admin_Section_Advanced
		 */
		private $admin_section_advanced;

		/**
		 * @var Alg_DTWP_Functions
		 */
		private $functions;

		/**
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return Alg_DTWP_Functions
		 */
		public function get_functions() {
			if ( $this->functions == null ) {
				$this->functions = new Alg_DTWP_Functions();
			}
			return $this->functions;
		}

		/**
		 * @version 1.0.5
		 * @since   1.0.5
		 * @return Alg_DTWP_Admin_Section_Advanced
		 */
		public function get_admin_section_advanced() {
			if ( $this->admin_section_advanced == null ) {
				$this->admin_section_advanced = new Alg_DTWP_Admin_Section_Advanced();
			}
			return $this->admin_section_advanced;
		}

		/**
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return Alg_DTWP_Admin_Section_Texts
		 */
		public function get_admin_section_texts() {
			if ( $this->admin_section_texts == null ) {
				$this->admin_section_texts = new Alg_DTWP_Admin_Section_Texts();
			}
			return $this->admin_section_texts;
		}

		/**
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return Alg_DTWP_Admin_Section_General
		 */
		public function get_admin_section_general() {
			if ( $this->admin_section_general == null ) {
				$this->admin_section_general = new Alg_DTWP_Admin_Section_General();
			}
			return $this->admin_section_general;
		}

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

		/**
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return Alg_DTWP_Discussions_Comments_CMB
		 */
		public function get_discussions_comments_cmb() {
			if ( $this->dicussions_comments_cmb == null ) {
				$this->dicussions_comments_cmb = new Alg_DTWP_Discussions_Comments_CMB();
			}
			return $this->dicussions_comments_cmb;
		}

	}
}