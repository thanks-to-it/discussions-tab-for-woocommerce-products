<?php
/**
 * Discussions tab for WooCommerce Products - Admin Section - General
 *
 * @version 1.0.1
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Admin_Section_General' ) ) {

	class Alg_DTWP_Admin_Section_General extends Alg_DTWP_Admin_Section {

		public $option_metabox_pro = 'alg_dtwp_cmb_pro';
		public $option_enable = 'alg_dtwp_opt_enable';
		protected $pro_version_url = 'https://wpcodefactory.com/item/discussions-tab-for-woocommerce-products/';

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
		 * @version 1.0.1
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
					'title'          => 'Pro version',
					'enabled'        => !function_exists('alg_dtwp_pro_start_plugin'),
					'type'           => 'wccso_metabox',
					'show_in_pro'    => false,
					'accordion' => array(
						'title' => __( 'Take a look on some of its features:', 'discussions-tab-for-woocommerce-products' ),
						'items' => array(
							array(
								'trigger'     => __( 'Use Social Networks like Facebook at your favor', 'discussions-tab-for-woocommerce-products' ),
								'description' => __( 'Let your customers auto fill their names, e-mail and even get their Facebook profile picture with just one click.', 'discussions-tab-for-woocommerce-products' ),
								'img_src'     => plugins_url( '../../../assets/images/autofill-frontend.png', __FILE__ ),
							),
							array(
								'trigger'     => __( 'Convert your native WooCommerce reviews to discussions if you want, and vice-versa', 'discussions-tab-for-woocommerce-products' ),
								'img_src'     => plugins_url( '../../../assets/images/convert-comments.png', __FILE__ ),
							),
							array(
								'trigger'     => __( 'Filter your discussions comments if you want', 'discussions-tab-for-woocommerce-products' ),
								'img_src'     => plugins_url( '../../../assets/images/filter-comments.png', __FILE__ ),
							),
							array(
								'trigger'     =>__( 'Support', 'wish-list-for-woocommerce' ),
								'description' => __( 'We will be ready to help you in case of any issues or questions you may have.', 'discussions-tab-for-woocommerce-products' ),
							),
						),
					),
					'call_to_action' => array(
						'href'   => $this->pro_version_url,
						'label'  => 'Upgrade to Pro version now',
					),
					'description'    => __( 'Do you like the free version of this plugin? Imagine what the Pro version can do for you!', 'discussions-tab-for-woocommerce-products' ) . '<br />' . sprintf( __( 'Check it out <a target="_blank" href="%1$s">here</a> or on this link: <a target="_blank" href="%1$s">%1$s</a>', 'discussions-tab-for-woocommerce-products' ), esc_url( $this->pro_version_url ) ),
					'id'             => $this->option_metabox_pro,
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