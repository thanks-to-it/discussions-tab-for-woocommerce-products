<?php
/**
 * Discussions tab for WooCommerce Products - Admin section
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Admin_Section' ) ) {

	class Alg_DTWP_Admin_Section {
		public $section_id = '';
		public $section_label = 'New Section';
		public $tab_id = '';
		public $section_priority = 10;

		/**
		 * Initializes
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param array $args
		 */
		public function init( $args = array() ) {
			$args = wp_parse_args( $args, array(
				'tab_id'           => $this->tab_id,
				'section_label'    => $this->section_label,
				'section_id'       => $this->section_id,
				'section_priority' => $this->section_priority,
			) );
			$this->tab_id = $args['tab_id'];
			$this->section_label = $args['section_label'];
			$this->section_id = $args['section_id'];
			$this->section_priority = $args['section_priority'];

			add_filter( "woocommerce_get_sections_{$this->tab_id}", array( $this, 'create_section' ), $this->section_priority );
			add_filter( "woocommerce_get_settings_{$this->tab_id}_" . "{$this->section_id}", array( $this, 'get_settings' ), PHP_INT_MAX );
		}

		/**
		 * Creates the section
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $sections
		 *
		 * @return mixed
		 */
		public function create_section( $sections ) {
			$sections[ $this->section_id ] = $this->section_label;
			return $sections;
		}

		/**
		 * Gets settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function get_settings( $settings ) {
			return $settings;
		}
	}
}