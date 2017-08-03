<?php
/**
 * Discussions tab for WooCommerce Products - Admin Section - Advanced
 *
 * @version 1.0.5
 * @since   1.0.5
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Admin_Section_Advanced' ) ) {

	class Alg_DTWP_Admin_Section_Advanced extends Alg_DTWP_Admin_Section {

		public $option_wp_list_comments_callback = 'alg_dtwp_wp_list_comment_cb';

		/**
		 * Constructor
		 *
		 * @version 1.0.5
		 * @since   1.0.5
		 */
		function __construct() {
			$this->section_id    = 'advanced';
			$this->section_label = __( 'Advanced', 'discussions-tab-for-woocommerce-products' );
		}

		/**
		 * Get settings
		 *
		 * @version 1.0.5
		 * @since   1.0.5
		 *
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function get_settings( $settings ) {
			$new_settings = array(
				array(
					'title' => __( 'Template', 'discussions-tab-for-woocommerce-products' ),
					'desc'  => __( 'Options regarding comments template', 'discussions-tab-for-woocommerce-products' ),
					'type'  => 'title',
					'id'    => 'alg_dtwp_adv_template',
				),
				array(
					'title'   => __( 'Comments callback function', 'discussions-tab-for-woocommerce-products' ),
					'desc'    => '<br />'.__( 'It will be used to override the function that displays discussions comments.', 'discussions-tab-for-woocommerce-products' ) . '<br /><span style="color:#909090;">' . __( 'Note: You only need to worry about this option if your discussion comments look weird or ugly', 'discussions-tab-for-woocommerce-products' ) . '</span>',
					'desc_tip'=> __( 'To get your callback function name, find the file "comments.php" inside your theme and take a look at the "callback" paramater inside the "wp_list_comments()" function', 'discussions-tab-for-woocommerce-products' ),
					'id'      => $this->option_wp_list_comments_callback,
					'default' => '',
					'type'    => 'text',
					'class'   => 'regular-input',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_dtwp_adv_template',
				),
			);
			return parent::get_settings( array_merge( $new_settings, $settings ) );
		}


	}
}