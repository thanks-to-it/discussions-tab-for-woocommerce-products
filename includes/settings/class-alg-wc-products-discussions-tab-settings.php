<?php
/**
 * Discussions Tab for WooCommerce Products - Settings
 *
 * @version 1.3.3
 * @since   1.1.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_Settings' ) ) :

class Alg_WC_Products_Discussions_Tab_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.3.3
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id    = 'alg_wc_products_discussions_tab';
		$this->label = __( 'Discussions', 'discussions-tab-for-woocommerce-products' );
		parent::__construct();
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'maybe_unsanitize_option' ), PHP_INT_MAX, 3 );
		// Create notice about pro
		add_action( 'admin_init', array( $this, 'add_promoting_notice' ) );
	}

	/**
	 * add_promoting_notice.
	 *
	 * @version 1.3.3
	 * @since   1.3.3
	 */
	function add_promoting_notice() {
		$promoting_notice = wpfactory_promoting_notice();
		$promoting_notice->set_args( array(
			'url_requirements'              => array(
				'page_filename' => 'admin.php',
				'params'        => array( 'page' => 'wc-settings', 'tab' => $this->id ),
			),
			'enable'                        => true === apply_filters( 'alg_wc_products_discussions_tab_settings', true ),
			'optimize_plugin_icon_contrast' => true,
			'template_variables'            => array(
				'%pro_version_url%'    => 'https://wpfactory.com/item/discussions-tab-for-woocommerce-products/',
				'%plugin_icon_url%'    => 'https://ps.w.org/discussions-tab-for-woocommerce-products/assets/icon-128x128.png',
				'%pro_version_title%'  => __( 'Discussions Tab for WooCommerce Products Pro', 'emails-verification-for-woocommerce' ),
				'%main_text%'          => __( 'Disabled options can be unlocked using <a href="%pro_version_url%" target="_blank"><strong>%pro_version_title%</strong></a>', 'discussions-tab-for-woocommerce-products' ),
				'%btn_call_to_action%' => __( 'Upgrade to Pro version', 'discussions-tab-for-woocommerce-products' ),
			),
		) );
		$promoting_notice->init();
	}

	/**
	 * maybe_unsanitize_option.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function maybe_unsanitize_option( $value, $option, $raw_value ) {
		return ( ! empty( $option['alg_wc_products_discussions_tab_raw'] ) ? wp_kses_post( trim( $raw_value ) ) : $value );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'discussions-tab-for-woocommerce-products' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'discussions-tab-for-woocommerce-products' ),
				'desc'      => '<strong>' . __( 'Reset', 'discussions-tab-for-woocommerce-products' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'discussions-tab-for-woocommerce-products' ),
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notices_settings_reset_success' ), PHP_INT_MAX );
		}
	}

	/**
	 * admin_notices_settings_reset_success.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function admin_notices_settings_reset_success() {
		echo '<div class="notice notice-success is-dismissible"><p><strong>' .
			__( 'Your settings have been reset.', 'discussions-tab-for-woocommerce-products' ) . '</strong></p></div>';
	}

	/**
	 * save.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}

}

endif;

return new Alg_WC_Products_Discussions_Tab_Settings();
