<?php
/**
 * Discussions Tab for WooCommerce Products - Email Section Settings
 *
 * @version 1.5.8
 * @since   1.3.4
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab\Settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Email' ) ) :

	class Settings_Email extends Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.5.8
		 * @since   1.3.4
		 */
		function __construct() {
			$this->id   = 'email';
			parent::__construct();
		}

		/**
		 * set_variables.
		 *
		 * @version 1.5.8
		 * @since   1.5.8
		 *
		 * @return void
		 */
		public function set_variables() {
			$this->desc = __( 'Email', 'discussions-tab-for-woocommerce-products' );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.3.8
		 * @since   1.3.4
		 */
		function get_settings() {
			$new_comment_email_settings = array(
				array(
					'title'    => __( 'New comment email', 'discussions-tab-for-woocommerce-products' ),
					'type'     => 'title',
					'desc'  => __( 'A notification sent via email when there is a new discussion comment.', 'discussions-tab-for-woocommerce-products' ) . '<br />' .
					           sprintf( __( 'The email depends on the %s option.', 'discussions-tab-for-woocommerce-products' ), '<a href="' . admin_url( 'options-discussion.php' ) . '">' . __( 'Email me whenever > Anyone posts a comment', 'discussions-tab-for-woocommerce-products' ) . '</a>' ),
					'id'       => 'alg_dtwp_opt_email_notification_section',
				),
				array(
					'title'         => __( 'Email text', 'discussions-tab-for-woocommerce-products' ),
					'desc'          => __( 'Remove undesired texts and actions from the email', 'discussions-tab-for-woocommerce-products' ),
					'desc_tip'      => __( 'Removes the IP address, texts like "In reply to:" and actions like "Trash it", "Spam it" and "Delete it".', 'discussions-tab-for-woocommerce-products' ),
					'id'            => 'alg_dtwp_remove_undesired_texts_from_notification',
					'checkboxgroup' => 'start',
					'default'       => 'yes',
					'type'          => 'checkbox',
				),
				array(
					'desc'          => __( 'Replace <code>#comments</code> anchor by discussions tab anchor', 'discussions-tab-for-woocommerce-products' ),
					'desc_tip'      => sprintf( __( 'It should fix the permalink after texts like: %s', 'discussions-tab-for-woocommerce-products' ), '"' . __( 'You can see all comments on this post here:', 'discussions-tab-for-woocommerce-products' ) . '"' ),
					'checkboxgroup' => 'end',
					'id'            => 'alg_dtwp_new_comment_email_replace_comments_anchor',
					'default'       => 'yes',
					'type'          => 'checkbox',
				),
				array(
					'title'             => __( 'Labels', 'discussions-tab-for-woocommerce-products' ),
					'desc'              => __( 'Show comment author label next to author name', 'discussions-tab-for-woocommerce-products' ),
					'id'                => 'alg_dtwp_new_comment_email_author_label',
					'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
					'default'           => 'no',
					'type'              => 'checkbox',
				),
				array(
					'title'    => __( 'Comment authors', 'discussions-tab-for-woocommerce-products' ),
					'desc'     => __( 'Also notify comment authors via email when they receive replies', 'discussions-tab-for-woocommerce-products' ),
					'desc_tip' => __( 'By default, only product authors are notified. This option notifies all thread participants.', 'discussions-tab-for-woocommerce-products' ),
					'id'       => 'alg_dtwp_notify_comment_authors',
					'default'  => 'no',
					'type'     => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
				),
				array(
					'title'             => __( 'Unsubscribing', 'discussions-tab-for-woocommerce-products' ),
					'desc'              => __( 'Create a checkbox above the discussion comments allowing registered users to subscribe/unsubscribe.', 'discussions-tab-for-woocommerce-products' ),
					'desc_tip'          => sprintf( __( 'If enabled, it will make more sense to also enable the %s option.', 'discussions-tab-for-woocommerce-products' ), '<strong>' . __( 'Comment authors', 'discussions-tab-for-woocommerce-products' ) . '</strong>' ),
					'id'                => 'alg_dtwp_unsubscribing_enabled',
					'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
					'default'           => 'no',
					'type'              => 'checkbox',
				),
				/*array(
					'desc_tip' => __( 'Confirmation message displayed when a user does not want to receive notifications any more.', 'discussions-tab-for-woocommerce-products' ),
					'id'       => 'alg_dtwp_unsubscribe_email_txt',
					'default'  => __( 'You have been successfully unsubscribed.', 'discussions-tab-for-woocommerce-products' ),
					'type'     => 'text',
					'class'    => 'regular-input',
					'css'      => 'width:100%',
					'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'readonly' => 'readonly' ) ),
				),*/
				array(
					'title'    => __( 'Manual email', 'discussions-tab-for-woocommerce-products' ),
					'desc'     => __( 'Allow sending manual emails to the product author on the "edit comment" page', 'discussions-tab-for-woocommerce-products' ),
					'desc_tip' => sprintf( __( 'If the %s option is enabled and the comment is a reply the parent comment author will also be notified.', 'discussions-tab-for-woocommerce-products' ), '<strong>' . __( 'Comment authors', 'discussions-tab-for-woocommerce-products' ) . '</strong>' ),
					'id'       => 'alg_dtwp_manual_notifications_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
					'custom_attributes' => apply_filters( 'alg_wc_products_discussions_tab_settings', array( 'disabled' => 'disabled' ) ),
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_dtwp_opt_email_notification_section',
				),
			);
			return array_merge( $new_comment_email_settings, array() );
		}

	}

endif;