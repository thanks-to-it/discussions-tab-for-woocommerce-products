<?php
/**
 * Discussions Tab for WooCommerce Products - Labels Section Settings
 *
 * @version 1.3.0
 * @since   1.1.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_Settings_Labels' ) ) :

class Alg_WC_Products_Discussions_Tab_Settings_Labels extends Alg_WC_Products_Discussions_Tab_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id   = 'labels';
		$this->desc = __( 'Labels', 'discussions-tab-for-woocommerce-products' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.3.0
	 * @since   1.1.0
	 */
	function get_settings() {
		$labels_settings = array(

			// Header
			array(
				'title'    => __( 'Labels Options', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_opt_labels',
			),
			array(
				'title'    => __( 'Labels', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => '<strong>' . __( 'Enable section', 'discussions-tab-for-woocommerce-products' ) . '</strong>',
				'id'       => 'alg_dtwp_labels_enable',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_labels',
			),

			// Verify Owner settings
			array(
				'title'    => __( 'Verified Owners', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'Customers who bought products.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner',
			),
			array(
				'title'    => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Detect comments left by customers who have purchased products', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Icon', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Requires "Load Font Awesome" option to be enabled.', 'discussions-tab-for-woocommerce-products' ) . '<br />'
				              . __( 'Leave empty to disable.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label_icon',
				'default'  => 'fas fa-check',
				'class'    => 'alg-dtwp-icon-picker',
				'css'      => 'width:435px',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Icon tip text', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text displayed when mouse is over the icon.', 'discussions-tab-for-woocommerce-products' ) . '<br />'
				              . __( 'Leave empty to disable.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label_txt',
				'default'  => __( 'Verified owner', 'discussions-tab-for-woocommerce-products' ),
				'css'      => 'width:435px',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Background color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Background color for the icon.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label_color',
				'default'  => '#0F834D',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Text color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text color for the icon', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_txt_color',
				'default'  => '#fff',
				'type'     => 'color',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_v_owner',
			),

			// Author settings
			array(
				'title'    => __( 'Product authors', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'Users who own the products.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author',
			),
			array(
				'title'    => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Detect comments left by product authors', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Icon', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Requires "Load Font Awesome" option to be enabled.', 'discussions-tab-for-woocommerce-products' ) . '<br />'
				              . __( 'Leave empty to disable.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label_icon',
				'default'  => 'fas fa-user',
				'class'    => 'alg-dtwp-icon-picker',
				'css'      => 'width:435px',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Icon tip text', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text displayed when mouse is over the icon.', 'discussions-tab-for-woocommerce-products' ) . '<br />'
				              . __( 'Leave empty to disable.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label_txt',
				'default'  => __( 'Product author', 'discussions-tab-for-woocommerce-products' ),
				'css'      => 'width:435px',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Title', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text displayed above profile picture.', 'discussions-tab-for-woocommerce-products' ) . '<br />' .
				              __( 'Leave empty to disable.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_title',
				'default'  => __( 'Product author', 'discussions-tab-for-woocommerce-products' ),
				'css'      => 'width:435px',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Background color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Background color for the icon and title.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label_color',
				'default'  => '#ec800d',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Text color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text color for the icon and title.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_txt_color',
				'default'  => '#fff',
				'type'     => 'color',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_author',
			),

			// Support Reps
			array(
				'title'    => __( 'Support Reps', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'Users who will be marked as support representatives.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support',
			),
			array(
				'title'    => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Detect comments left by support reps', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Icon', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Requires "Load Font Awesome" option to be enabled.', 'discussions-tab-for-woocommerce-products' ) . '<br />'
				              . __( 'Leave empty to disable.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label_icon',
				'default'  => 'fas fa-life-ring',
				'class'    => 'alg-dtwp-icon-picker',
				'css'      => 'width:435px',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Icon tip text', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text displayed when mouse is over the icon.', 'discussions-tab-for-woocommerce-products' ) . '<br />'
				              . __( 'Leave empty to disable.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label_txt',
				'css'      => 'width:435px',
				'default'  => __( 'Support', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Title', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text displayed above profile picture.', 'discussions-tab-for-woocommerce-products' ) . '<br />' .
				              __( 'Leave empty to disable.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_title',
				'default'  => __( 'Support', 'discussions-tab-for-woocommerce-products' ),
				'css'      => 'width:435px',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Background color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Background color for the icon and title.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label_color',
				'default'  => '#0085a2',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Text color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text color for the icon and title.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_txt_color',
				'default'  => '#ffffff',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'My account tab', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'checkbox',
				'default'  => 'yes',
				'desc'     => sprintf( __( 'Add discussions tab on <a href="%s">My Account page</a> to setup the support reps', 'discussions-tab-for-woocommerce-products' ), wc_get_account_endpoint_url( 'dashboard' ) ),
				'desc_tip' => sprintf( __( 'Only users with the %s capability will be able to see the tab.', 'discussions-tab-for-woocommerce-products' ), '<code>' . 'edit_products' . '</code>' ),
				'id'       => 'alg_dtwp_opt_support_my_account_tab',
			),
			array(
				'title'    => __( 'Support metabox', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'checkbox',
				'default'  => 'no',
				'desc'     => __( 'Add metabox on product pages to setup the support reps', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'If enabled and not empty will overwrite the my account tab setup.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_product_metabox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_support',
			),

			// Tips style
			array(
				'title'    => __( 'Tips Style', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'Style for tips.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_tip_style',
			),
			array(
				'title'    => __( 'Tip text color', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_label_tip_txt_color',
				'default'  => '#222',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Tip background color', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_label_tip_bkg_color',
				'default'  => '#fef4c5',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Tip border color', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_label_tip_border_color',
				'default'  => '#d4b943',
				'type'     => 'color',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_tip_style',
			),

			// Advanced
			array(
				'title'    => __( 'Advanced Options', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_opt_labels_advanced',
			),
			array(
				'title'    => __( 'Load Font Awesome', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Loads most recent version of Font Awesome.', 'discussions-tab-for-woocommerce-products' ) . ' ' .
					__( 'Only mark this if you are not loading Font Awesome anywhere else. Font Awesome is responsible for creating icons.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_font_awesome',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_labels_advanced',
			),
		);

		return $labels_settings;
	}

}

endif;

return new Alg_WC_Products_Discussions_Tab_Settings_Labels();
