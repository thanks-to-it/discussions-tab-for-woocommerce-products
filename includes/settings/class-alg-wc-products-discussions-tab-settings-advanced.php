<?php
/**
 * Discussions Tab for WooCommerce Products - Advanced Section Settings
 *
 * @version 1.3.3
 * @since   1.1.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_Settings_Advanced' ) ) :

class Alg_WC_Products_Discussions_Tab_Settings_Advanced extends Alg_WC_Products_Discussions_Tab_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id   = 'advanced';
		$this->desc = __( 'Advanced', 'discussions-tab-for-woocommerce-products' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.3.3
	 * @since   1.1.0
	 * @todo    [dev] (maybe) add "Comments columns" option ("Setups comments columns in admin").
	 */
	function get_settings() {
		$advanced_settings = array(
			array(
				'title'    => __( 'Template Options', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Options regarding comments template.', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_adv_template',
			),
			array(
				'title'    => __( 'Comments callback function', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'It will be used to override the function that displays discussions comments.', 'discussions-tab-for-woocommerce-products' ) . ' ' .
					__( 'Note: You only need to worry about this option if your discussion comments look weird or ugly.', 'discussions-tab-for-woocommerce-products' ) . '<br>' .
					sprintf( __( 'To get your callback function name, find the file %s inside your theme and take a look at the %s parameter inside the %s function.', 'discussions-tab-for-woocommerce-products' ),
						'<code>comments.php</code>', '<code>callback</code>', '<code>wp_list_comments()</code>' ),
				'id'       => 'alg_dtwp_wp_list_comment_cb',
				'default'  => '',
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_adv_template',
			)
		);
		return $advanced_settings;
	}

}

endif;

return new Alg_WC_Products_Discussions_Tab_Settings_Advanced();
