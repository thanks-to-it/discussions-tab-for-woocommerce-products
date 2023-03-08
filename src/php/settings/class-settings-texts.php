<?php
/**
 * Discussions Tab for WooCommerce Products - Texts Section Settings
 *
 * @version 1.2.0
 * @since   1.1.0
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab\Settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Texts' ) ) :

class Settings_Texts extends Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id   = 'texts';
		$this->desc = __( 'Texts', 'discussions-tab-for-woocommerce-products' );
		parent::__construct();
	}

	/**
	 * Gets discussion title example.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_discussions_title_example( $count = 1, $title = false ) {
		if ( false === $title ) {
			$title = __( 'Product Title', 'discussions-tab-for-woocommerce-products' );
		}
		$discussions_title_label_singular = get_option( 'alg_dtwp_discussions_title_single', __( 'One thought on "%1$s"', 'discussions-tab-for-woocommerce-products' ) );
		$discussions_title_label_plural   = get_option( 'alg_dtwp_discussions_title_plural', __( '%2$d thoughts on "%1$s"', 'discussions-tab-for-woocommerce-products' ) );
		$text = $count == 1 ? $discussions_title_label_singular : $discussions_title_label_plural;
		return sprintf( $text, '<span>' . $title . '</span>', $count );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function get_settings() {
		$texts_settings = array(
			array(
				'title'    => __( 'Texts Options', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_opt_texts',
			),
			array(
				'title'    => __( 'Discussions label', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'How discussions will be labeled in front-end.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_discussions_label',
				'default'  => __( 'Discussions', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
			),
			array(
				'title'    => __( 'Tab title', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Discussions tab title.', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => sprintf( __( 'Placeholders: %s', 'discussions-tab-for-woocommerce-products' ), '<code>%label%</code>, <code>%number_of_comments%</code>' ),
				'id'       => 'alg_dtwp_discussions_tab_title',
				'default'  => '%label% (%number_of_comments%)',
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
				'alg_wc_products_discussions_tab_raw' => true,
			),
			array(
				'title'    => __( 'No discussions', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'When there is still no comments.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_discussions_none',
				'default'  => __( 'There are no discussions yet.', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
			),
			array(
				'title'    => __( 'Title - singular', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'The discussions title for tab content in front-end (singular).', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => sprintf( __( '%s is the Product title. %s stands for the Comments count.', 'discussions-tab-for-woocommerce-products' ),
						'<code>%1$s</code>', '<code>%2$d</code>' ) . ' ' .
					'<strong>' . __( 'Example', 'discussions-tab-for-woocommerce-products' ) . ': </strong>' . $this->get_discussions_title_example( 1 ),
				'id'       => 'alg_dtwp_discussions_title_single',
				'default'  => __( 'One thought on "%1$s"', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
			),
			array(
				'title'    => __( 'Title - plural', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'The discussions title for tab content in front-end (plural).', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => sprintf( __( '%s is the Product title. %s stands for the Comments count.', 'discussions-tab-for-woocommerce-products' ),
						'<code>%1$s</code>', '<code>%2$d</code>' ) . ' ' .
					'<strong>' . __( 'Example', 'discussions-tab-for-woocommerce-products' ) . ': </strong>' . $this->get_discussions_title_example( 5 ),
				'id'       => 'alg_dtwp_discussions_title_plural',
				'default'  => __( '%2$d thoughts on "%1$s"', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
			),
			array(
				'title'    => __( 'Respond label', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Title displayed on respond form.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_discussions_respond_title',
				'default'  => __( 'Leave a Reply', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
			),
			array(
				'title'    => __( 'Comment button', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Label for post comment button.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_discussions_post_comment_label',
				'default'  => __( 'Post Comment', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
			),
			array(
				'title'    => __( 'Textarea placeholder', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_discussions_textarea_placeholder',
				'default'  => '',
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_texts',
			),
		);
		return $texts_settings;
	}

}

endif;