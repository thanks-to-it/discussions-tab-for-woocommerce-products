<?php
/**
 * Discussions Tab for WooCommerce Products - Social Section Settings
 *
 * @version 1.1.0
 * @since   1.1.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_Settings_Social' ) ) :

class Alg_WC_Products_Discussions_Tab_Settings_Social extends Alg_WC_Products_Discussions_Tab_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id   = 'social';
		$this->desc = __( 'Social', 'discussions-tab-for-woocommerce-products' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_settings() {
		$social_settings = array(
			array(
				'title'    => __( 'Facebook Options', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_opt_form_af',
			),
			array(
				'title'    => __( 'Social', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => '<strong>' . __( 'Enable section', 'discussions-tab-for-woocommerce-products' ) . '</strong>',
				'desc_tip' => apply_filters( 'alg_wc_products_discussions_tab_settings', $this->get_pro_message() ),
				'id'       => 'alg_dtwp_social_enable',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Facebook App ID', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => '<br>' . sprintf( __( 'To get your App ID, you need to create a Facebook App <a href="%s" target="_blank">following these steps</a>.', 'discussions-tab-for-woocommerce-products' ),
					'https://developers.facebook.com/docs/apps/register' ),
				'id'       => 'alg_dtwp_fb_app_id',
				'default'  => '',
				'class'    => 'regular-input',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Autofill', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Use Facebook to fill the discussions form with name and e-mail.', 'discussions-tab-for-woocommerce-products' ) . ' ' .
					__( 'Facebook App ID is necessary.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_fb_autofill',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Autofill avatar', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Use Facebook profile picture to replace the avatar.', 'discussions-tab-for-woocommerce-products' ) . ' ' .
					__( 'Requires the autofill option enabled.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_fb_autofill_avatar',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Autofill button label', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_fb_autofill_btn_label',
				'default'  => __( 'Fill with Facebook', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
				'class'    => 'regular-input',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_form_af',
			),
		);
		return $social_settings;
	}

}

endif;

return new Alg_WC_Products_Discussions_Tab_Settings_Social();
