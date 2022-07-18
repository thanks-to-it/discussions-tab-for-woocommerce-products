<?php
/**
 * Discussions Tab for WooCommerce Products - Advanced Section Settings.
 *
 * @version 1.4.0
 * @since   1.1.0
 * @author  Thanks to IT
 */

namespace WPFactory\WC_Products_Discussions_Tab\Settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Advanced' ) ) :

class Settings_Advanced extends Settings_Section {

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
	 * @version 1.4.0
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
			),
		);
		$comment_content = array(
			array(
				'title'    => __( 'Comment content', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'The content from discussions comments.', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_opt_comment_content',
			),
			array(
				'title'    => __( 'Shortcodes', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Enable shortcodes in discussion comments', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_sc_discussions',
				'default'  => 'no',
				'type'     => 'checkbox',
				'checkboxgroup' => 'start',
			),
			array(
				'desc'     => sprintf( __( 'Enable shortcodes to be viewed in <a href="%s" target="_blank">edit comments page</a> on admin', 'discussions-tab-for-woocommerce-products' ), admin_url( 'edit-comments.php' ) ),
				'desc_tip' => sprintf( __( '"%s" option must be enabled.', 'discussions-tab-for-woocommerce-products' ), __( 'Enable shortcodes in discussion comments', 'discussions-tab-for-woocommerce-products' ) ),
				'id'       => 'alg_dtwp_opt_sc_admin',
				'default'  => 'no',
				'type'     => 'checkbox',
				'checkboxgroup' => 'end',
			),
			array(
				'title'    => __( 'Content sanitization', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Enable custom sanitization for discussion comments', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_custom_sanitization',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'desc'     => $this->get_sanitization_content_desc(),
				'id'       => 'alg_dtwp_opt_custom_sanitization_content',
				'default'  => wp_json_encode( alg_wc_products_discussions_tab()->core->get_default_allowed_comment_html(), JSON_PRETTY_PRINT ),
				'css'      => $this->get_sanitization_content_css(),
				'type'     => 'textarea',
			),
			array(
				'title'    => __( 'Content escaping', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => sprintf(__( 'Escape content between %s and %s tags using %s', 'discussions-tab-for-woocommerce-products' ),'<code>code</code>','<code>pre</code>','<a href="https://developer.wordpress.org/reference/functions/esc_html/" target="_blank"><code>esc_html()</code></code></a>'),
				'id'       => 'alg_dtwp_escape_code_and_pre',
				'default'  => 'no',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Content removal', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Remove content from discussion comments', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => sprintf( __( 'The content won\'t be removed from db. It will be only filtered with the %s hook.', 'discussions-tab-for-woocommerce-products' ), '<code>' . 'comment_text' . '</code>' ),
				'id'       => 'alg_dtwp_opt_remove_content',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'desc'                                => __( 'Content that will be removed:', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip'                            => __( 'Add one value per line.', 'discussions-tab-for-woocommerce-products' ),
				'id'                                  => 'alg_dtwp_opt_content_to_remove',
				'alg_wc_products_discussions_tab_raw' => true,
				'default'                             => '<p>&nbsp;</p>',
				'type'                                => 'textarea',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_comment_content',
			),
		);
		$wc_compatibility_opts = array(
			array(
				'title' => __( 'WooCommerce compatibility', 'discussions-tab-for-woocommerce-products' ),
				'desc'  => __( 'From time to time, WooCommerce changes some details in the code that might affect the Discussions plugin. This is how you can handle with them.', 'discussions-tab-for-woocommerce-products' ),
				'type'  => 'title',
				'id'    => 'alg_dtwp_opt_wc_compatibility_options',
			),
			array(
				'title'         => __( 'Reviews change', 'discussions-tab-for-woocommerce-products' ),
				'desc'          => __( 'Fix product reviews change from WooCommerce 6.7.0', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip'      => __( 'WooCommerce tried to separate the reviews from comments and that created some problems on admin.', 'discussions-tab-for-woocommerce-products' ),
				'id'            => 'alg_dtwp_fix_reviews_change',
				'default'       => 'yes',
				'type'          => 'checkbox',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'alg_dtwp_opt_wc_compatibility_options',
			),
		);
		return array_merge( $advanced_settings, $comment_content, $wc_compatibility_opts );
	}

	/**
	 * get_sanitization_content_desc.
	 *
	 * @version 1.3.1
	 * @since   1.3.1
	 *
	 * @return string
	 */
	function get_sanitization_content_desc() {
		$desc = __( 'HTML tags allowed:', 'discussions-tab-for-woocommerce-products' );
		$desc .= ! alg_wc_products_discussions_tab()->core->sanitization_content_valid() ? '<br />' . '<span style="color:red">' . __( 'JSON not valid. Please check the content.', 'discussions-tab-for-woocommerce-products' ) . '</span>' : '';
		return $desc;
	}

	/**
	 * get_sanitization_content_css.
	 *
	 * @version 1.3.1
	 * @since   1.3.1
	 *
	 * @return string
	 */
	function get_sanitization_content_css() {
		$css = 'min-height:170px;';
		if ( ! alg_wc_products_discussions_tab()->core->sanitization_content_valid() ) {
			$css .= 'border:1px solid red;';
		}
		return $css;
	}

}

endif;
