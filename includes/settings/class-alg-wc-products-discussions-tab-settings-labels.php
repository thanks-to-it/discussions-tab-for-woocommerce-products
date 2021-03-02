<?php
/**
 * Discussions Tab for WooCommerce Products - Labels Section Settings
 *
 * @version 1.2.6
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
	 * @version 1.1.1
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
				'title'    => __( 'Show label', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => '<strong>' . __( 'Enable', 'discussions-tab-for-woocommerce-products' ) . '</strong>',
				'desc_tip' => __( 'Displays a "verified owner" label on discussions comments.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Label icon', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Requires "Load Font Awesome" option to be enabled.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label_icon',
				'default'  => 'fas fa-check',
				'class'    => 'alg-dtwp-icon-picker',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Label color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Verified owner label color.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label_color',
				'default'  => '#0F834D',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Tip', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Enables a tip that will be displayed when mouse is over the label.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label_tip',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Tip text', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text that will be displayed in the tip.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_label_txt',
				'default'  => __( 'Verified owner', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_v_owner',
			),

			// Author settings
			array(
				'title'    => __( 'Authors', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'Users who own the products.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author',
			),
			array(
				'title'    => __( 'Show label', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => '<strong>' . __( 'Enable', 'discussions-tab-for-woocommerce-products' ) . '</strong>',
				'desc_tip' => __( 'Displays an "author" label on discussions comments.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Label icon', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Requires "Load Font Awesome" option to be enabled.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label_icon',
				'default'  => 'fas fa-user',
				'class'    => 'alg-dtwp-icon-picker',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Label color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Author label color', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label_color',
				'default'  => '#ec800d',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Tip', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Enables a tip that will be displayed when mouse is over the label.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label_tip',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Tip text', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text that will be displayed in the tip.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_author_label_txt',
				'default'  => __( 'Product author', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
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
				'title'    => __( 'Show label', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => '<strong>' . __( 'Enable', 'discussions-tab-for-woocommerce-products' ) . '</strong>',
				'desc_tip' => __( 'Displays a "support" label on discussions comments.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Support metabox', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Add a metabox on product pages to setup the support reps.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_product_metabox',
			),
			array(
				'title'    => __( 'Label icon', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Requires "Load Font Awesome" option to be enabled.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label_icon',
				'default'  => 'fas fa-life-ring',
				'class'    => 'alg-dtwp-icon-picker',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Label color', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'support label color', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label_color',
				'default'  => '#0085a2',
				'type'     => 'color',
			),
			array(
				'title'    => __( 'Tip', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Enable', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Enables a tip that will be displayed when mouse is over the label.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label_tip',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Tip text', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Text that will be displayed in the tip.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_support_label_txt',
				'default'  => __( 'Support', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
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
				'title'    => __( 'Load Font Awesome', 'wish-list-for-woocommerce' ),
				'desc'     => __( 'Enable', 'wish-list-for-woocommerce' ),
				'desc_tip' => __( 'Loads most recent version of Font Awesome.', 'discussions-tab-for-woocommerce-products' ) . ' ' .
					__( 'Only mark this if you are not loading Font Awesome anywhere else. Font Awesome is responsible for creating icons.', 'wish-list-for-woocommerce' ),
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
