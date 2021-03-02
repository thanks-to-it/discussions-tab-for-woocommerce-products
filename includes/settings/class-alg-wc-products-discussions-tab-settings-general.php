<?php
/**
 * Discussions Tab for WooCommerce Products - General Section Settings
 *
 * @version 1.2.6
 * @since   1.1.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_Settings_General' ) ) :

class Alg_WC_Products_Discussions_Tab_Settings_General extends Alg_WC_Products_Discussions_Tab_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'discussions-tab-for-woocommerce-products' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.2.6
	 * @since   1.1.0
	 * @todo    [dev] check if "Comment link" set to `comment` causes any issues; if so - add some description at least (see https://wordpress.org/support/topic/missing-source-files/)
	 */
	function get_settings() {

		$plugin_settings = array(
			array(
				'title'    => __( 'Discussions Tab Options', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_wc_products_discussions_tab_plugin_options',
			),
			array(
				'title'    => __( 'Discussions Tab for Products', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'discussions-tab-for-woocommerce-products' ) . '</strong>',
				'id'       => 'alg_dtwp_opt_enable',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_products_discussions_tab_plugin_options',
			),
		);

		$general_settings = array(
			array(
				'title'    => __( 'General Options', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_opt_general',
			),
			array(
				'title'    => __( 'Count replies', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Consider replies when counting the discussions comments total amount', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_count_replies',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Tab link', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Link that will automatically open your discussions tab.', 'discussions-tab-for-woocommerce-products' ) . '<br>' .
					sprintf( __( 'E.g.: %s', 'discussions-tab-for-woocommerce-products' ), '<code>' . $this->get_example_link( 'tab' ) . '</code>' ),
				'id'       => 'alg_dtwp_opt_tab_id',
				'default'  => 'discussions',
				'class'    => 'regular-input',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Comment link', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Link that will automatically open your discussions tab on a specific comment.', 'discussions-tab-for-woocommerce-products' ) . '<br>' .
					sprintf( __( 'E.g.: %s', 'discussions-tab-for-woocommerce-products' ), '<code>' . $this->get_example_link( 'comment' ) . '</code>' ),
				'desc_tip' => __( 'This link will be displayed after a comment is posted on frontend.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_comment_link',
				'default'  => 'discussion',
				'class'    => 'regular-input',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Tab position', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Tab priority (i.e. position).', 'discussions-tab-for-woocommerce-products' ) . ' ' .
					sprintf( __( 'Default WooCommerce tabs priorities are: %s.', 'discussions-tab-for-woocommerce-products' ),
						implode( ', ', array(
							__( 'Description', 'woocommerce' ) . ' - 10',
							__( 'Additional information', 'woocommerce' ) . ' - 20',
							__( 'Reviews', 'woocommerce' ) . ' - 30',
						) ) ),
				'id'       => 'alg_dtwp_opt_tab_priority',
				'default'  => 50,
				'type'     => 'number',
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
				'title'             => __( 'Comment form position', 'discussions-tab-for-woocommerce-products' ),
				'desc'              => __( 'The place where the "Leave a reply" form will be displayed.', 'discussions-tab-for-woocommerce-products' ),
				'id'                => 'alg_dtwp_opt_comment_form_position',
				'default'           => 'alg_dtwp_comments_end',
				'options'           => array(
					'alg_dtwp_comments_start' => __( 'Top', 'discussions-tab-for-woocommerce-products' ),
					'alg_dtwp_comments_end'   => __( 'Bottom', 'discussions-tab-for-woocommerce-products' ),
				),
				'type'              => 'select',
				'class'             => 'chosen_select',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'             => __( 'Rich text editor', 'discussions-tab-for-woocommerce-products' ),
				'desc'              => __( 'Enable TinyMCE on discussion comments', 'discussions-tab-for-woocommerce-products' ),
				'id'                => 'alg_dtwp_opt_tinymce',
				'default'           => 'no',
				'type'              => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_general',
			),
		);

		$extra_settings = array(
			array(
				'title'    => __( 'Extra Options', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_opt_extra',
			),
			array(
				'title'    => __( 'Open comments', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Open comments for product post type', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Enable if you can\'t see the comment form on the discussion tab or if you\'re getting the "Comments are closed" message. ', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_open_comments',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'AJAX tab', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Load discussions tab content via AJAX', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Enable if you have a lot of comments. ', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_ajax_tab',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Restrict discussions', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Discussions comments can only be left by "verified owners"', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_restrict',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Notify authors', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Notify comment authors via email when they receive replies', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'They can unsubscribe clicking on the "Unsubscribe" link on the notification email.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_notify_comment_authors',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'desc_tip' => __( 'Confirmation message displayed when a user does not want to receive notifications any more.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_unsubscribe_email_txt',
				'default'  => __( 'You have been successfully unsubscribed', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'text',
				'class'    => 'regular-input',
				'css'      => 'width:100%',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'readonly' => 'readonly' ) ),
			),
			array(
				'title'    =>  __( 'Pro version', 'discussions-tab-for-woocommerce-products' ),
				'enabled'  => apply_filters( 'alg_wc_products_discussions_tab_settings', true ),
				'type'     => 'alg_wc_pdtmb',
				'show_in_pro' => false,
				'accordion' => array(
					'title' => __( 'Take a look on some of its features:', 'discussions-tab-for-woocommerce-products' ),
					'items' => array(
						array(
							'trigger'     => __( 'Use social networks like Facebook at your favor', 'discussions-tab-for-woocommerce-products' ),
							'description' => __( 'Let your customers auto fill their names, e-mail and even get their Facebook profile picture with just one click.', 'discussions-tab-for-woocommerce-products' ),
							'img_src'     => plugins_url( '../../assets/images/autofill-frontend.png', __FILE__ ),
						),
						array(
							'trigger'     => __( 'Decide if comments can be posted by anyone or only the ones who bought the product', 'discussions-tab-for-woocommerce-products' ),
							'description' => __( 'You can also choose to simply add a label on comments made by customers.', 'discussions-tab-for-woocommerce-products' ),
						),
						array(
							'trigger'     => __( 'Notify comment authors via email', 'discussions-tab-for-woocommerce-products' ),
							'description' => __( 'Notify comment authors via email when they receive replies.', 'discussions-tab-for-woocommerce-products' ),
						),
						array(
							'trigger'     => __( 'Show if comments are being replied by product authors', 'discussions-tab-for-woocommerce-products' ),
							'description' => __( 'Display labels on comments/reviews that were written by product authors.', 'discussions-tab-for-woocommerce-products' ),
						),
						array(
							'trigger'     => __( 'Support', 'wish-list-for-woocommerce' ),
							'description' => __( 'We will be ready to help you in case of any issues or questions you may have.', 'discussions-tab-for-woocommerce-products' ),
						),
					),
				),
				'call_to_action' => array(
					'href'   => 'https://wpfactory.com/item/discussions-tab-for-woocommerce-products/',
					'label'  => __( 'Upgrade to Pro version now', 'discussions-tab-for-woocommerce-products' ),
				),
				'description' => __( 'Do you like the free version of this plugin? Imagine what the Pro version can do for you!', 'discussions-tab-for-woocommerce-products' ) . ' ' .
					sprintf( __( 'Check it out at <a target="_blank" href="%1$s">%1$s</a>', 'discussions-tab-for-woocommerce-products' ),
						'https://wpfactory.com/item/discussions-tab-for-woocommerce-products/' ),
				'id'       => 'alg_dtwp_cmb_pro',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_extra',
			),
		);

		return array_merge( $plugin_settings, $general_settings, $extra_settings );
	}

	/**
	 * Gets a example link (tab or comment).
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @return  string
	 */
	function get_example_link( $tab_or_comment ) {
		global $post;
		$return = '';
		$link   = ( 'tab' === $tab_or_comment ? get_option( 'alg_dtwp_opt_tab_id', 'discussions' ) : get_option( 'alg_dtwp_opt_comment_link', 'discussion' ) );
		$link   = sanitize_text_field( $link );
		$link   = sanitize_title( $link );
		$link   = ( 'tab' === $tab_or_comment ? '#tab-' . $link : '#' . $link . '-5' );
		$posts  = get_posts( array(
			'posts_per_page' => 1,
			'orderby'        => 'modified',
			'post_type'      => 'product',
		) );
		if ( $posts ) {
			foreach ( $posts as $post ) {
				setup_postdata( $post );
				$return = get_permalink( $post->ID ) . $link;
				break;
			}
			wp_reset_postdata();
		}
		return $return;
	}

}

endif;

return new Alg_WC_Products_Discussions_Tab_Settings_General();
