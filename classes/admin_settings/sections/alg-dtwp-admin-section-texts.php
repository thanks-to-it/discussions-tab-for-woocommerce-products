<?php
/**
 * Discussions tab for WooCommerce Products - Admin Section - Texts
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Admin_Section_Texts' ) ) {

	class Alg_DTWP_Admin_Section_Texts extends Alg_DTWP_Admin_Section {

		public $option_discussions_label = 'alg_dtwp_discussions_label';
		public $option_discussions_title_single = 'alg_dtwp_discussions_title_single';
		public $option_discussions_title_plural = 'alg_dtwp_discussions_title_plural';
		public $option_discussions_respond_title = 'alg_dtwp_discussions_respond_title';
		public $option_discussions_post_comment_label = 'alg_dtwp_discussions_post_comment_label';

		/**
		 * Constructor
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function __construct() {
			$this->section_id    = 'texts';
			$this->section_label = __( 'Texts', 'discussions-tab-for-woocommerce-products' );
		}

		/**
		 * Gets discussion title example
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function get_discussions_title_example( $count = 1, $title = 'Product Title' ) {
			$plugin                           = Alg_DTWP_Core::get_instance();
			$discussions_title_label_singular = get_option( $plugin->registry->get_admin_section_texts()->option_discussions_title_single, __( 'One thought on', 'discussions-tab-for-woocommerce-products' ) );
			$discussions_title_label_plural   = get_option( $plugin->registry->get_admin_section_texts()->option_discussions_title_plural, __( 'thoughts on', 'discussions-tab-for-woocommerce-products' ) );
			return sprintf(
				esc_html( _nx( '%3$s &ldquo;%2$s&rdquo;', '%1$s %4$s &ldquo;%2$s&rdquo;', $count, 'comments title', 'discussions-tab-for-woocommerce-products' ) ),
				number_format_i18n( $count ),
				'<span>' . $title . '</span>',
				$discussions_title_label_singular,
				$discussions_title_label_plural
			);
		}

		/**
		 * Get settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function get_settings( $settings ) {
			$new_settings = array(
				array(
					'title' => __( 'Text options', 'discussions-tab-for-woocommerce-products' ),
					'type'  => 'title',
					'id'    => 'alg_dtwp_opt_texts',
				),
				array(
					'title'   => __( 'Discussions label', 'discussions-tab-for-woocommerce-products' ),
					'desc'    => __( 'How discussions will be labeled in front-end', 'discussions-tab-for-woocommerce-products' ),
					'id'      => $this->option_discussions_label,
					'default' => __( 'Discussions', 'discussions-tab-for-woocommerce-products' ),
					'type'    => 'text',
					'class'   => 'regular-input',
				),
				array(
					'title'   => __( 'Title - singular', 'discussions-tab-for-woocommerce-products' ),
					'desc'    => __( 'The discussions title for tab content in front-end (singular)', 'discussions-tab-for-woocommerce-products' ) . '<br />' . '<strong>' . __( 'Example: ', 'discussions-tab-for-woocommerce-products' ) . '</strong>' . $this->get_discussions_title_example( 1 ),
					'id'      => $this->option_discussions_title_single,
					'default' => __( 'One thought on', 'discussions-tab-for-woocommerce-products' ),
					'type'    => 'text',
					'class'   => 'regular-input',
				),
				array(
					'title'   => __( 'Title - plural', 'discussions-tab-for-woocommerce-products' ),
					'desc'    => __( 'The discussions title for tab content in front-end (plural)', 'discussions-tab-for-woocommerce-products' ) . '<br />' . '<strong>' . __( 'Example: ', 'discussions-tab-for-woocommerce-products' ) . '</strong>' . $this->get_discussions_title_example( 5 ),
					'id'      => $this->option_discussions_title_plural,
					'default' => __( 'thoughts on', 'discussions-tab-for-woocommerce-products' ),
					'type'    => 'text',
					'class'   => 'regular-input',
				),
				array(
					'title'   => __( 'Respond label', 'discussions-tab-for-woocommerce-products' ),
					'desc'    => __( 'Title displayed on respond form', 'discussions-tab-for-woocommerce-products' ),
					'id'      => $this->option_discussions_respond_title,
					'default' => __( 'Leave a Reply', 'discussions-tab-for-woocommerce-products' ),
					'type'    => 'text',
					'class'   => 'regular-input',
				),
				array(
					'title'   => __( 'Comment button', 'discussions-tab-for-woocommerce-products' ),
					'desc'    => __( 'Label for post comment button', 'discussions-tab-for-woocommerce-products' ),
					'id'      => $this->option_discussions_post_comment_label,
					'default' => __( 'Post Comment', 'discussions-tab-for-woocommerce-products' ),
					'type'    => 'text',
					'class'   => 'regular-input',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_dtwp_opt_texts',
				),
			);
			return parent::get_settings( array_merge( $new_settings, $settings ) );
		}


	}
}