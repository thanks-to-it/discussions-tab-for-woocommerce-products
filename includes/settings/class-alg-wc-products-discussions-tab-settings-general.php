<?php
/**
 * Discussions Tab for WooCommerce Products - General Section Settings
 *
 * @version 1.3.3
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
	 * @version 1.3.3
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
				'title'    => __( 'Restrict discussions', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Discussions comments can only be left by "verified owners"', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_v_owner_restrict',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'AJAX discussions', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Load comments via AJAX if the discussions tab is triggered', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Enable if you have a lot of discussions comments. ', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_ajax_tab',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_general',
			),
		);

		$comment_meta = array(
			array(
				'title'    => __( 'Comment meta', 'discussions-tab-for-woocommerce-products' ),
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

		$email_notification_settings = array(
			array(
				'title'    => __( 'Notification via email', 'discussions-tab-for-woocommerce-products' ),
				'type'     => 'title',
				'desc'     => __( 'A notification sent via email when there is a new interaction on discussion comments.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_opt_email_notification_section',
			),
			array(
				'title'    => __( 'Enable notifications', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Send notifications via e-mail when a new comment is posted or its status is set to approved', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_email_notifications_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Comment authors', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Also notify comment authors via email when they receive replies', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'By default, only product authors are notified.', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_notify_comment_authors',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Manual notification', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Allow sending manual notifications to the product author on the "edit comment" page', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => sprintf( __( 'If the %s option is enabled and the comment is a reply the parent comment author will also be notified.', 'discussions-tab-for-woocommerce-products' ), '<strong>' . __( 'Replies', 'discussions-tab-for-woocommerce-products' ) . '</strong>' ),
				'id'       => 'alg_dtwp_manual_notifications_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Notification text', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Remove undesired texts and actions from notification', 'discussions-tab-for-woocommerce-products' ),
				'desc_tip' => __( 'Removes the IP address, texts like "In reply to:" and actions like "Trash it", "Spam it" and "Delete it".', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_remove_undesired_texts_from_notification',
				'default'  => 'yes',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Unsubscribing', 'discussions-tab-for-woocommerce-products' ),
				'desc'     => __( 'Create a "Unsubscribe" link on the notification email', 'discussions-tab-for-woocommerce-products' ),
				'id'       => 'alg_dtwp_unsubscribing_enabled',
				'default'  => 'yes',
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
				'type'     => 'sectionend',
				'id'       => 'alg_dtwp_opt_email_notification_section',
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

		return array_merge( $general_settings, $discussions_tab, $comment_form, $email_notification_settings, $comment_meta, $comment_content );
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
