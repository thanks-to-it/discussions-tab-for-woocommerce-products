<?php
/**
 * Discussions Tab for WooCommerce Products - New comment email.
 *
 * @version 1.3.6
 * @since   1.3.3
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\New_Comment_Email' ) ) :

	class New_Comment_Email {

		/**
		 * Constructor.
		 *
		 * @version 1.3.6
		 * @since   1.3.3
		 */
		function __construct() {
			// Notify via email.
			add_filter( 'comment_edit_redirect', array( $this, 'notify_via_email' ), 10, 2 );
			add_action( 'admin_notices', array( $this, 'show_notification_notice' ) );
			// Removes undesired text.
			add_filter( 'comment_notification_text', array( $this, 'remove_native_actions' ), 10, 2 );
			// Replaces comments anchor.
			add_filter( 'comment_notification_text', array( $this, 'replace_comments_anchor' ), 10, 2 );
		}

		/**
		 * notify_via_email.
		 *
		 * @version 1.3.3
		 * @since   1.3.3
		 */
		function notify_via_email( $location, $comment_id ) {
			if (
				isset( $_POST['alg_dtwp_manually_notify'] ) &&
				isset( $_POST['alg_dtwp_email_notification_nonce'] ) &&
				! empty( $comment_id ) &&
				wp_verify_nonce( $_POST['alg_dtwp_email_notification_nonce'], 'notify_via_email' )
			) {
				$response = wp_notify_postauthor( $comment_id );
				$location = add_query_arg( array(
					'alg_dtwp_notification_response' => $response
				), $location );
			}
			return $location;
		}

		/**
		 * show_notification_notice.
		 *
		 * @version 1.3.3
		 * @since   1.3.3
		 */
		function show_notification_notice() {
			if ( isset( $_GET['alg_dtwp_notification_response'] ) ) {
				if ( filter_var( $_GET['alg_dtwp_notification_response'], FILTER_VALIDATE_BOOLEAN ) ) {
					$class   = 'notice notice-success is-dismissible';
					$message = __( 'The notification has been sent successfully.', 'discussions-tab-for-woocommerce-products' );
					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
				} else {
					$class   = 'notice notice-error is-dismissible';
					$message = __( 'There has been a problem with the notification.', 'discussions-tab-for-woocommerce-products' );
					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
				}
			}
		}

		/**
		 * Removes trash, spam and delete actions from email.
		 *
		 * @version 1.3.4
		 * @since   1.0.7
		 *
		 * @param $email_text
		 *
		 * @return null|string|string[]
		 */
		function remove_native_actions( $email_text, $comment_id ) {
			if (
				'yes' === get_option( 'alg_dtwp_remove_undesired_texts_from_notification', 'yes' ) &&
				! empty( $comment = get_comment( $comment_id ) ) &&
				alg_wc_pdt_get_comment_type_id() === $comment->comment_type
			) {
				$email_text = preg_replace( "/(Trash it:.*)|(Spam it:.*)|(Delete it:.*)|(In reply to:.*)|( \(IP address:.*)/", "", $email_text );
				// Trims at the end
				$email_text = preg_replace( "/\s*\z/", "", $email_text );
			}
			return $email_text;
		}

		/**
		 * replace_comments_anchor.
		 *
		 * @version 1.3.6
		 * @since   1.3.6
		 *
		 * @param $email_text
		 * @param $comment_id
		 *
		 * @return string
		 */
		function replace_comments_anchor( $email_text, $comment_id ) {
			if (
				'yes' === get_option( 'alg_dtwp_new_comment_email_replace_comments_anchor', 'yes' ) &&
				! empty( $comment = get_comment( $comment_id ) ) &&
				alg_wc_pdt_get_comment_type_id() === $comment->comment_type
			) {
				$email_text = str_replace( '#comments', '#tab-' . alg_wc_products_discussions_tab()->core->get_discussions_tab_id(), $email_text );
			}
			return $email_text;
		}

	}
endif;