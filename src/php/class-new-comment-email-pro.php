<?php
/**
 * Discussions Tab for WooCommerce Products - New comment email Pro.
 *
 * @version 1.3.8
 * @since   1.3.3
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\New_Comment_Email_Pro' ) ) :

	class New_Comment_Email_Pro {

		/**
		 * Constructor.
		 *
		 * @version 1.3.8
		 * @since   1.3.4
		 */
		function __construct() {
			// Add metabox.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			// Notifies comment authors (replies).
			add_filter( 'comment_notification_recipients', array( $this, 'notify_comment_authors' ), 10, 2 );
			// Add author label
			add_filter( 'comment_notification_text', array( $this, 'add_comment_author_label' ), 10, 2 );
			// Subscription
			add_action( 'wp_ajax_nopriv_' . 'alg_dtwp_toggle_subscription', array( $this, 'toggle_subscription' ) );
			add_action( 'wp_ajax_' . 'alg_dtwp_toggle_subscription', array( $this, 'toggle_subscription' ) );
			add_filter( 'alg_dtwp_js_modules_to_load', array( $this, 'load_subscription_module' ) );
			add_filter( 'alg_dtwp_localize_script', array( $this, 'js_subscription_params' ) );
			add_filter( 'comment_notification_recipients', array( $this, 'remove_unsubscribe_users_from_notification' ), 11, 2 );
		}

		/**
		 * remove_unsubscribe_users_from_notification.
		 *
		 * @version 1.3.8
		 * @since   1.3.8
		 *
		 * @param $emails
		 * @param $comment_id
		 *
		 * @return array
		 */
		function remove_unsubscribe_users_from_notification( $emails, $comment_id ) {
			if ( 'yes' === get_option( 'alg_dtwp_unsubscribing_enabled', 'no' ) ) {
				$comment             = get_comment( $comment_id );
				$unsubscribed_emails = get_post_meta( $comment->comment_post_ID, 'dtwp_unsubscribed', false );
				$emails              = array_diff( $emails, $unsubscribed_emails );
			}
			return $emails;
		}

		/**
		 * load_subscription_module.
		 *
		 * @version 1.3.8
		 * @since   1.3.8
		 *
		 * @param $modules_to_load
		 *
		 * @return array
		 */
		function load_subscription_module( $modules_to_load ) {
			if (
				is_product() &&
				'yes' === get_option( 'alg_dtwp_unsubscribing_enabled', 'no' )
			) {
				$modules_to_load[] = 'subscription';
			}
			return $modules_to_load;
		}

		/**
		 * js_subscription_params.
		 *
		 * @version 1.3.8
		 * @since   1.3.8
		 *
		 * @param $params
		 *
		 * @return mixed
		 */
		function js_subscription_params( $params ) {
			$params['subscription_nonce'] = wp_create_nonce( 'dtwp_subscription' );
			return $params;
		}

		/**
		 * toggle_subscription.
		 *
		 * @version 1.3.8
		 * @since   1.3.8
		 */
		function toggle_subscription() {
			check_ajax_referer( 'dtwp_subscription', 'security' );
			$post_id    = intval( $_POST['post_id'] );
			$subscribed = filter_var( $_POST['subscribed'], FILTER_VALIDATE_BOOLEAN );
			$product    = wc_get_product( $post_id );
			if ( ! is_a( $product, 'WC_Product' ) ) {
				wp_die();
			}
			$current_user       = wp_get_current_user();
			$unsubscribed_emails = get_post_meta( $post_id, 'dtwp_unsubscribed', false );
			if ( $subscribed && in_array( $current_user->user_email, $unsubscribed_emails ) ) {
				delete_post_meta( $post_id, 'dtwp_unsubscribed', $current_user->user_email );
			} elseif ( ! $subscribed && ! in_array( $current_user->user_email, $unsubscribed_emails ) ) {
				add_post_meta( $post_id, 'dtwp_unsubscribed', $current_user->user_email );
			}
		}

		/**
		 * add_comment_author_label.
		 *
		 * @version 1.3.4
		 * @since   1.3.4
		 *
		 * @param $email_text
		 * @param $comment_id
		 *
		 * @return null|string|string[]
		 */
		function add_comment_author_label( $email_text, $comment_id ) {
			if (
				'yes' === get_option( 'alg_dtwp_new_comment_email_author_label', 'no' ) &&
				! empty( $comment = get_comment( $comment_id ) ) &&
				alg_wc_pdt_get_comment_type_id() === $comment->comment_type
			) {
				$labels       = apply_filters( 'alg_dtwp_labels', array(), $comment, '' );
				$html_allowed = false;
				$template     = $html_allowed ? '<span style="color:{color};background-color:{bkg_color};margin:{margin};padding:{padding}">{text}</span>' : '{text}';
				$html         = '';
				$html_arr     = array();
				foreach ( $labels as $label ) {
					$style      = apply_filters( 'alg_dtwp_label_' . 'alg-dtwp-item-author' . '_style', array(), $label );
					$replace    = array(
						'{color}'     => $style['color'],
						'{bkg_color}' => $style['bkg_color'],
						'{text}'      => isset( $label['title'] ) ? strtoupper( $label['title'] ) : strtoupper( $label['tip'] ),
						'{margin}'    => '0 5px 0 0',
						'{padding}'   => '3px 6px'
					);
					$value      = str_replace( array_keys( $replace ), $replace, $template );
					$html       .= $value;
					$html_arr[] = $value;
				}
				if ( ! empty( $html_arr ) || ! empty( $html ) ) {
					$final_replace = $html_allowed ? '$0 ' . $html : '$0 ' . '(' . implode( ", ", $html_arr ) . ')';
				}
				if ( ! empty( $final_replace ) ) {
					$email_text = preg_replace( '/Author:.*/', $final_replace, $email_text );
				}
			}
			return $email_text;
		}

		/**
		 * Adds the meta box container.
		 *
		 * @version 1.3.4
		 * @since   1.3.4
		 */
		public function add_meta_box( $post_type ) {
			if (
				in_array( $post_type, array( 'comment' ) )
				&& $this->need_to_add_manual_notification()
			) {
				add_meta_box(
					'alg-dtwp-manual-notification',
					__( 'New comment email', 'discussions-tab-for-woocommerce-products' ),
					array( $this, 'render_meta_box_content' ),
					$post_type,
					'normal',
					'high'
				);
			}
		}

		/**
		 * render_meta_box_content.
		 *
		 * @version 1.3.4
		 * @since   1.3.4
		 */
		function render_meta_box_content() {
			wp_nonce_field( 'notify_via_email', 'alg_dtwp_email_notification_nonce' );
			?>
			<?php submit_button( 'Notify product author via email', 'primary', 'alg_dtwp_manually_notify', false, array( 'style' => 'margin:5px 0 0 0' ) ); ?>
			<?php
		}

		/**
		 * need_to_add_manual_notification.
		 *
		 * @version 1.3.4
		 * @since   1.3.4
		 *
		 * @param \WP_Comment $comment
		 *
		 * @return bool
		 */
		function need_to_add_manual_notification( $comment = null ) {
			if ( empty( $comment ) ) {
				global $comment;
			}
			if (
				$comment &&
				alg_wc_pdt_get_comment_type_id() === $comment->comment_type &&
				'yes' === get_option( 'alg_dtwp_manual_notifications_enabled', 'no' )
			) {
				return true;
			}
			return false;
		}

		/**
		 * handle_notifications.
		 *
		 * @version 1.3.8
		 * @since   1.3.8
		 *
		 * @param $emails
		 * @param $comment_id
		 *
		 * @return mixed
		 */
		function notify_comment_authors( $emails, $comment_id ) {
			if (
				'yes' === get_option( 'alg_dtwp_notify_comment_authors', 'no' ) &&
				! empty( $comment = get_comment( $comment_id ) ) &&
				! empty( $comment_parent_id = $comment->comment_parent )
			) {
				global $wpdb;
				$comment_children_emails = $wpdb->get_col( $wpdb->prepare(
					"
			        SELECT      distinct comment_author_email
			        FROM        $wpdb->comments
			        WHERE comment_parent = %d || comment_id = %d
			    ",
					$comment_parent_id,
					$comment_parent_id
				) );
				$emails = array_unique( array_merge( $emails, $comment_children_emails ) );
				$emails = array_diff( $emails, array( $comment->comment_author_email ) );
			}
			return $emails;
		}

	}
endif;