<?php
/**
 * Discussions Tab for WooCommerce Products - General Section Settings.
 *
 * @version 1.5.5
 * @since   1.1.0
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab\Settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Settings\Settings_General' ) ) :

class Settings_General extends Settings_Section {

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
	 * @version 1.5.5
	 * @since   1.1.0
	 * @todo    [dev] check if "Comment link" set to `comment` causes any issues; if so - add some description at least (see https://wordpress.org/support/topic/missing-source-files/)
	 */
	function get_settings() {

		$general_settings = array(
			array(
				'title'    => __( 'General Options', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'id'       => 'alg_dtwp_opt_general',
			),
			array(
				'title'    => __( 'Enable plugin', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => sprintf( __( 'Enable %s plugin', 'discussions-tab-for-woocommerce-products' ), '<strong>' . __( 'Discussions Tab for WooCommerce Products', 'discussions-tab-for-woocommerce-products' ) . '</strong>' ),
				'id'       => 'alg_dtwp_opt_enable',
				'default'  => 'yes',
				'type'     => 'checkbox',
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
				'title'    => __( 'Comment Reply JS', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => sprintf( __( 'Enqueue the %s script on the product page', 'discussions-tab-for-woocommerce-products' ), '<code>comment-reply</code>' ),
				'desc_tip' => __( 'Enable it if the page reloads after clicking the Reply button from a Discussion comment.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_enqueue_comment_reply_on_product',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Verified owners', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Discussions comments can only be left by "verified owners"', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_restrict',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'AJAX discussions', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Load comments via AJAX if the discussions tab is triggered', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => sprintf( __( 'For now, the option %s needs to be disabled, i.e., the comments pagination needs to be disabled.', 'discussions-tab-for-woocommerce-products' ), '<a href="' . admin_url( 'options-discussion.php' ) . '">' . __( 'Break Comments' ) . '</a>' ),
				'id'       => 'alg_dtwp_opt_ajax_tab',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'             => __( 'Comment actions', 'discussions-tab-for-woocommerce-products' ),
				'desc'              => sprintf( __( 'Show "edit comment link" only for users with the %s capability', 'discussions-tab-for-woocommerce-products' ), '<code>' . 'moderate_comments' . '</code>' ),
				'id'                => 'alg_dtwp_edit_comments_link_requires_moderate_comments',
				'default'           => 'yes',
				'type'              => 'checkbox',
			),
			array(
				'title'             => __( 'Guest users', 'discussions-tab-for-woocommerce-products' ),
				'desc'              => __( 'Hide discussion comments from guest users', 'discussions-tab-for-woocommerce-products' ),
				'id'                => 'alg_dtwp_hide_discussion_comments_from_guests',
				'default'           => 'no',
				'type'              => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_general',
			),
		);

		$comment_meta = array(
			array(
				'title'    => __( 'Editable comment meta on admin', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'Data about the comment itself, like comment type, comment parent ID and the post/product in which the comment belongs to.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_comment_meta_section',
			),
			array(
				'title'    => __( 'Comment type conversion', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Allow admin to convert discussions to reviews and vice versa', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => sprintf( __( 'Will add "Convert to Review" and "Convert to Discussion" options to admin\'s %s section.', 'discussions-tab-for-woocommerce-products' ),
					'<a href="' . admin_url( 'edit-comments.php' ) . '" target="_blank">' . __( 'Comments', 'discussions-tab-for-woocommerce-products' ) . '</a>' ),
				'id'       => 'alg_dtwp_admin_conversions_enable',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'             => __( 'Parent ID', 'discussions-tab-for-woocommerce-products' ),
				'desc'              => __( 'Allow admin to edit discussion comment parent ID', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip'          => __( 'A new meta box will be added to the "edit comment" page.', 'discussions-tab-for-woocommerce-products' ),
				'id'                => 'alg_dtwp_edit_comment_parent_id',
				'default'           => 'no',
				'type'              => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'             => __( 'Post ID', 'discussions-tab-for-woocommerce-products' ),
				'desc'              => __( 'Allow admin to edit discussion comment post ID', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip'          => __( 'A new meta box will be added to the "edit comment" page.', 'discussions-tab-for-woocommerce-products' ),
				'id'                => 'alg_dtwp_edit_comment_post_id',
				'default'           => 'no',
				'type'              => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'             => __( 'User ID', 'discussions-tab-for-woocommerce-products' ),
				'desc'              => __( 'Allow admin to edit discussion comment user ID', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip'          => __( 'A new meta box will be added to the "edit comment" page.', 'discussions-tab-for-woocommerce-products' ),
				'id'                => 'alg_dtwp_edit_comment_user_id',
				'default'           => 'no',
				'type'              => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_comment_meta_section',
			),
		);

		$discussions_tab = array(
			array(
				'title'    => __( 'Discussions tab', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'A tab added to the product page with the discussion comments.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_discussions_tab_section',
			),
			array(
				'title'    => __( 'Count replies', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Consider replies when counting the discussions comments total amount', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => sprintf( __( 'Changes how the %s from the %s option works.', 'discussions-tab-for-woocommerce-products' ), '<code>' . '%number_of_comments%' . '</code>', '<strong>' . __( 'Texts > Tab title', 'discussions-tab-for-woocommerce-products' ) . '</strong>' ),
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
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_discussions_tab_section',
			),
		);

		$comment_form = array(
			array(
				'title'    => __( 'Discussions form', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'The form used to display the discussions comment field.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_discussions_form_section',
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
				'id'       => 'alg_dtwp_opt_discussions_form_section',
			),
		);

		return array_merge( $general_settings, $discussions_tab, $comment_form, $comment_meta );
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
