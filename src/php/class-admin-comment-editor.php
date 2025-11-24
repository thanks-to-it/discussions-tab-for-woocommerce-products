<?php
/**
 * Discussions Tab for WooCommerce Products - Admin Comment Editor.
 *
 * @version 1.3.7
 * @since   1.3.3
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Admin_Comment_Editor' ) ) :

	class Admin_Comment_Editor {

		/**
		 * Constructor.
		 *
		 * @version 1.3.5
		 * @since   1.3.3
		 */
		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_filter( 'woocommerce_screen_ids', array( $this, 'add_comment_to_wc_screen_ids' ) );
			// Check errors on comment update
			add_filter( 'wp_update_comment_data', array( $this, 'check_errors_on_comment_meta_change' ), 10, 2 );
			// Handle notice regarding meta change
			add_filter( 'wp_update_comment_data', array( $this, 'get_comment_before_update' ), 10, 2 );
			add_action( 'edit_comment', array( $this, 'save_notice_info_about_comment_meta_change' ), 10, 2 );
			add_action( 'admin_notices', array( $this, 'show_comment_meta_change_notice' ) );
			// Fix comment author on user_id change.
			add_filter( 'wp_update_comment_data', array( $this, 'fix_comment_author_on_user_id_change' ), 11, 2 );
		}

		/**
		 * fix_comment_author_on_user_id_change.
		 *
		 * @version 1.3.6
		 * @since   1.3.3
		 *
		 * @param $data
		 * @param $comment
		 *
		 * @return mixed
		 */
		function fix_comment_author_on_user_id_change( $data, $comment ) {
			if (
				$this->need_to_edit_comment_info( $comment ) &&
				! is_wp_error( $data ) &&
				( ! empty( $user_id = $data['user_id'] ) || 0 === (int) $data['user_id'] ) &&
				$user_id != $comment['user_id']
			) {
				if ( 0 === (int) $comment['user_id'] ) {
					update_comment_meta( $comment['comment_ID'], 'dtwp_guest_comment_author_email', $comment['comment_author_email'] );
					update_comment_meta( $comment['comment_ID'], 'dtwp_guest_comment_author', $comment['comment_author'] );
					update_comment_meta( $comment['comment_ID'], 'dtwp_guest_comment_author_url', $comment['comment_author_url'] );
				}
				if ( ! empty( $user = get_user_by( 'ID', $user_id ) ) ) {
					$data['comment_author_email'] = $user->user_email;
					$data['comment_author']       = $user->user_login;
					$data['comment_author_url']   = $user->user_url;
				} elseif ( 0 === (int) $user_id ) {
					$data['comment_author_email'] = get_comment_meta( $comment['comment_ID'], 'dtwp_guest_comment_author_email', true );
					$data['comment_author']       = get_comment_meta( $comment['comment_ID'], 'dtwp_guest_comment_author', true );
					$data['comment_author_url']   = get_comment_meta( $comment['comment_ID'], 'dtwp_guest_comment_author_url', true );
				}
			}
			return $data;
		}

		/**
		 * check_errors_on_comment_meta_change.
		 *
		 * @version 1.3.6
		 * @since   1.3.3
		 *
		 * @param $data
		 * @param $comment
		 *
		 * @return \WP_Error
		 */
		function check_errors_on_comment_meta_change( $data, $comment ) {
			if ( $this->need_to_edit_comment_info( $comment ) ) {
				$user_id           = $data['user_id'];
				$post_id           = $data['comment_post_ID'];
				$comment_parent_id = $data['comment_parent'];
				$errors            = new \WP_Error();
				if ( null === ( $product = wc_get_product( $post_id ) ) ) {
					$errors->add( 'post_does_not_exist', __( 'Product does not exist.', 'discussions-tab-for-woocommerce-products' ) );
				}
				if ( null === ( $comment_parent = get_comment( $comment_parent_id ) ) ) {
					$errors->add( 'comment_does_not_exist', sprintf( __( 'Comment parent ID %s does not exist.', 'discussions-tab-for-woocommerce-products' ), $comment_parent_id ) );
				} elseif ( $comment_parent->comment_type !== alg_wc_pdt_get_comment_type_id() ) {
					$errors->add( 'comment_does_not_exist', sprintf( __( 'Comment parent ID %s is not a discussion comment.', 'discussions-tab-for-woocommerce-products' ), $comment_parent_id ) );
				} elseif ( 0 !== (int) $comment_parent_id && $post_id !== $comment_parent->comment_post_ID ) {
					$errors->add( 'comment_does_not_match', sprintf( __( 'Parent comment ID %s does not belong to product %s.', 'discussions-tab-for-woocommerce-products' ), $comment_parent_id, $product->get_formatted_name() ) );
				} elseif ( 0 !== (int) $user_id && empty( $user = get_user_by( 'ID', $user_id ) ) ) {
					$errors->add( 'user_does_not_exist', sprintf( __( 'User %s does not exist.', 'discussions-tab-for-woocommerce-products' ), $user_id ) );
				}
				if ( $errors->has_errors() ) {
					return $errors;
				}
			}
			return $data;
		}

		/**
		 * show_comment_meta_change_notice.
		 *
		 * @version 1.3.3
		 * @since   1.3.3
		 */
		function show_comment_meta_change_notice() {
			if (
				is_user_logged_in()
				&& ! empty( $comment_meta_change = get_user_meta( get_current_user_id(), 'alg_dtwp_comment_meta_change', true ) )
			) {
				$changes_msg_arr = array();
				foreach ( $comment_meta_change as $comment_id => $change ) {
					$message = sprintf( __( 'Comment %s has been successfully updated.', 'discussions-tab-for-woocommerce-products' ), "<strong>{$comment_id}</strong>" );
					$message .= ' ';
					foreach ( $change as $change_key => $change_value ) {
						$option_result     = wp_list_filter( $this->get_options(), array( 'id' => $change_key ) );
						$option_title      = $this->get_options()[ array_keys( $option_result )[0] ]['title'];
						$changes_msg_arr[] = sprintf( __( '%s has changed from %s to %s', 'discussions-tab-for-woocommerce-products' ), "<strong>{$option_title}</strong>", "<strong>{$change_value[0]}</strong>", "<strong>{$change_value[1]}</strong>" );
					}
					$message .= implode( " and ", $changes_msg_arr ) . 'pro';
					$class   = 'notice notice-success is-dismissible';
					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
				}
				delete_user_meta( get_current_user_id(), 'alg_dtwp_comment_meta_change' );
			}
		}

		/**
		 * save_notice_info_about_comment_meta_change.
		 *
		 * @version 1.3.3
		 * @since   1.3.3
		 *
		 * @param $comment_ID
		 * @param $data
		 */
		function save_notice_info_about_comment_meta_change( $comment_ID, $data ) {
			if (
				is_user_logged_in()
				&& ! empty( $this->pre_update_comment )
				&& ! empty( $comment = get_comment( $comment_ID, ARRAY_A ) )
				&& $this->need_to_edit_comment_info( $comment )
				&& ! empty( ( $diff = @array_diff_assoc( $this->pre_update_comment, $comment ) ) )
				&& count( array_intersect( array_flip( $diff ), wp_list_pluck( $this->get_options(), 'id' ) ) ) > 0
			) {
				$change_info = get_user_meta( get_current_user_id(), 'alg_dtwp_comment_meta_change', true );
				$change_info = empty( $change_info ) ? array() : $change_info;
				foreach ( array_keys( $diff ) as $edited_comment_meta_key ) {
					$option_result = wp_list_filter( $this->get_options(), array( 'id' => $edited_comment_meta_key ) );
					if ( count( $option_result ) > 0 ) {
						$change_info[ $comment_ID ][ $edited_comment_meta_key ] = array( $this->pre_update_comment[ $edited_comment_meta_key ], $comment[ $edited_comment_meta_key ] );
					}
				}
				update_user_meta( get_current_user_id(), 'alg_dtwp_comment_meta_change', $change_info );
			}
		}

		/**
		 * get_comment_before_update.
		 *
		 * @version 1.3.3
		 * @since   1.3.3
		 *
		 * @param $data
		 * @param $comment
		 *
		 * @return mixed
		 */
		function get_comment_before_update( $data, $comment ) {
			if ( $this->need_to_edit_comment_info( $comment ) ) {
				$this->pre_update_comment = $comment;
			}
			return $data;
		}

		/**
		 * add_comment_to_wc_screen_ids.
		 *
		 * @version 1.3.3
		 * @since   1.3.3
		 *
		 * @param $screen_ids
		 *
		 * @return array
		 */
		function add_comment_to_wc_screen_ids( $screen_ids ) {
			if ( $this->need_to_edit_comment_info() ) {
				$screen_ids[] = 'comment';
			}
			return $screen_ids;
		}

		/**
		 * Adds the meta box container.
		 *
		 * @version 1.3.3
		 * @since   1.3.3
		 */
		public function add_meta_box( $post_type ) {
			if (
				in_array( $post_type, array( 'comment' ) )
				&& $this->need_to_edit_comment_info()
			) {
				add_meta_box(
					'alg-dtwp-edit-comment-info',
					__( 'Edit comment info', 'discussions-tab-for-woocommerce-products' ),
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
		 * @version 1.3.3
		 * @since   1.3.3
		 */
		function render_meta_box_content() {
			echo $this->display_options_table();
		}

		/**
		 * display_inputs_table.
		 *
		 * @version 1.3.7
		 * @since   1.3.3
		 *
		 * @return false|string
		 */
		function display_options_table() {
			$inputs = $this->get_options( array(
				'get_dynamic_data_from_current_comment' => true
			) );
			ob_start();
			?>
			<div class="woocommerce">
				<table style="margin-top:12px" class="alg-dtwp-metabox-table form-table widefat striped">
					<?php \WC_Admin_Settings::output_fields( $inputs ); ?>
				</table>
			</div>
			<style>
				.alg-dtwp-metabox-table th {
					padding-left: 10px !important;
				}

				@media (max-width: 782px) {
					.alg-dtwp-metabox-table td {
						padding: 4px 10px 10px 10px;

					}
				}
			</style>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		/**
		 * get_inputs.
		 *
		 * @version 1.3.7
		 * @since   1.3.3
		 *
		 * @return array
		 */
		function get_options($args=null) {
			$args = wp_parse_args( $args, array(
				'get_dynamic_data_from_current_comment' => false
			) );
			$get_dynamic_data_from_current_comment = $args['get_dynamic_data_from_current_comment'];
			$inputs = array();
			if ( 'yes' === get_option( 'alg_dtwp_edit_comment_post_id', 'no' ) ) {
				$inputs[] = array(
					'title'             => __( 'Product', 'discussions-tab-for-woocommerce-products' ),
					'id'                => 'comment_post_ID',
					'css'               => 'width:100%',
					'default'           => '',
					'type'              => 'select',
					'custom_attributes' => array(
						'data-action'      => 'woocommerce_json_search_products',
						'data-allow_clear' => "true",
						'aria-hidden'      => "true",
						'data-sortable'    => "true",
					),
					'options'           => $get_dynamic_data_from_current_comment ? $this->get_products_options( array( 'option_id' => 'comment_post_ID' ) ) : array(),
					'class'             => 'wc-product-search'
				);
			}
			if ( 'yes' === get_option( 'alg_dtwp_edit_comment_parent_id', 'no' ) ) {
				$inputs[] = array(
					'title'    => __( 'Parent comment ID', 'discussions-tab-for-woocommerce-products' ),
					'desc_tip' => __( 'Use zero if you don\'t want to set a parent comment ID', 'discussions-tab-for-woocommerce-products' ),
					'id'       => 'comment_parent',
					'css'      => 'width:100%',
					'default'  => '',
					'type'     => 'number',
				);
			}
			if ( 'yes' === get_option( 'alg_dtwp_edit_comment_user_id', 'no' ) ) {
				$inputs[] = array(
					'title'    => __( 'User ID', 'discussions-tab-for-woocommerce-products' ),
					'desc_tip' => __( 'Use zero if you don\'t want to set a user ID', 'discussions-tab-for-woocommerce-products' ),
					'id'       => 'user_id',
					'css'      => 'width:100%',
					'default'  => '',
					'type'     => 'number',
				);
			}
			global $comment;
			if ( $get_dynamic_data_from_current_comment && $comment ) {
				foreach ( $inputs as $input_key => $input_value ) {
					if ( property_exists( $comment, $input_value['id'] ) ) {
						$inputs[ $input_key ]['value'] = $comment->{$input_value['id']};
					}
				}
			}
			return $inputs;
		}

		/**
		 * get_products_options.
		 *
		 * @version 1.3.7
		 * @since   1.3.3
		 *
		 * @param null $args
		 *
		 * @return array
		 */
		function get_products_options( $args = null ) {
			$args = wp_parse_args( $args, array(
				'option_id' => ''
			) );
			global $comment;
			$options = array();
			if ( $comment ) {
				$object_id             = $comment->{$args['option_id']};
				$object_name           = is_a( $object = wc_get_product( $object_id ), 'WC_Product' ) ? $object->get_formatted_name() : get_post( $object_id )->post_title;
				$options[ $object_id ] = wp_kses_post( $object_name );
			}
			return $options;
		}

		/**
		 * need_to_edit_comment_info.
		 *
		 * @version 1.3.6
		 * @since   1.3.3
		 *
		 * @param $comment
		 *
		 * @return bool
		 */
		function need_to_edit_comment_info( $comment = null ) {
			if ( empty( $comment ) ) {
				global $comment;
			}
			$test_comment = $comment;
			if ( is_a( $test_comment, 'WP_Comment' ) ) {
				$test_comment = $comment->to_array();
			}
			if (
				$test_comment &&
				alg_wc_pdt_get_comment_type_id() === $test_comment['comment_type'] &&
				(
					'yes' === get_option( 'alg_dtwp_edit_comment_parent_id', 'no' )
					|| 'yes' === get_option( 'alg_dtwp_edit_comment_post_id', 'no' )
					|| 'yes' === get_option( 'alg_dtwp_edit_comment_user_id', 'no' )
				)
			) {
				return true;
			}
			return false;
		}
	}
endif;