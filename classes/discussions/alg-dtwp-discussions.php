<?php
/**
 * Discussions tab for WooCommerce Products - Discussions
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Discussions' ) ) {

	class Alg_DTWP_Discussions {

		public static $comment_type_id = 'alg_dtwp_comment';
		public $discussions_respond_id_wrapper = 'alg_dtwp_respond';
		public $discussions_respond_id_location = 'alg_dtwp_respond_location';

		/**
		 * Filters comments
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $comments_flat
		 * @param $post_id
		 */
		public function filter_discussions_comments( $comments_flat, $post_id ) {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( $is_discussion_tab ) {
				return wp_filter_object_list( $comments_flat, array( 'comment_type' => self::$comment_type_id ) );
			} else {
				return wp_filter_object_list( $comments_flat, array( 'comment_type' => self::$comment_type_id ), 'NOT' );
			}

			//return wp_filter_object_list( $comments_flat, array( 'comment_type' => self::$comment_type_id ) );
		}

		/**
		 * Adds discussions comment type in comment form
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_discussions_comment_type_in_form() {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}
			echo '<input type="hidden" name="' . esc_attr( self::$comment_type_id ) . '" value="1"/>';
		}

		/**
		 * Adds discussions comment type in comment data
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $comment_data
		 */
		public function add_discussions_comment_type_in_comment_data( $comment_data ) {
			$request = $_REQUEST;

			// If discussion comment type isn't in request, do nothing
			if (
				isset( $request[ self::$comment_type_id ] ) &&
				! filter_var( $request[ self::$comment_type_id ], FILTER_VALIDATE_BOOLEAN )
			) {
				return $comment_data;
			}

			// If parent comment isn't discussion comment type, do nothing
			if (
				! isset( $request[ self::$comment_type_id ] ) &&
				isset( $comment_data['comment_parent'] ) &&
				get_comment_type( $comment_data['comment_parent'] ) != self::$comment_type_id
			) {
				return $comment_data;
			}

			$comment_data['comment_type'] = self::$comment_type_id;
			return $comment_data;
		}

		/**
		 * Hides discussions comments on default callings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function hide_discussion_comments_on_default_callings( \WP_Comment_Query $query ) {
			global $pagenow;
			if (
				$query->query_vars['type'] === self::$comment_type_id ||
				! empty( $pagenow ) && $pagenow == 'edit-comments.php'
			) {
				return;
			}

			$query->query_vars['type__not_in'] = array_merge(
				(array) $query->query_vars['type__not_in'],
				array( self::$comment_type_id )
			);
		}

		/**
		 * Adds dicussions comment type to wp_list_comments
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $args
		 */
		/*public function add_discussions_comment_type_to_wp_list_comments( $args ) {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}

			$args['type'] = self::$comment_type_id;
			//error_log( print_r( $args, true ) );
			return $args;
		}*/

		/**
		 * Loads discussion comments
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		/*public function filter_discussions_comments_template_query_args( $args ) {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if (
			! $is_discussion_tab
			) {
				return $args;
			}

			$args['type'] = self::$comment_type_id;
			return $args;
		}*/

		/**
		 * Swaps woocommerce template (single-product-reviews.php) with default comments template
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $template
		 *
		 * @return mixed
		 */
		public function load_discussions_comments_template( $template ) {
			if ( get_post_type() !== 'product' ) {
				return $template;
			}
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return $template;
			}
			$template = '/comments.php';
			return $template;
		}

		/**
		 * Changes respond form id
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function js_fix_comment_parent_id_and_cancel_btn() {
			$respond_id = $this->discussions_respond_id_wrapper;
			?>
            <script>
				jQuery(document).ready(function ($) {
					$('.comment-reply-link').on('click', function (e) {
						var respond_wrapper = $('#' + '<?php echo $respond_id;?>');
						if (!respond_wrapper.length) {
							e.preventDefault();
							return;
						}

						var edit_link = $(this).parent().find('.comment-edit-link').attr('href');
						var edit_link_arr = edit_link.split("&c=");
						var parent_post_id = edit_link_arr[1];
						var cancel_btn = respond_wrapper.find("#cancel-comment-reply-link");
						respond_wrapper.find("#comment_parent").val(parent_post_id);
						cancel_btn.show();
						cancel_btn.on('click', function () {
							cancel_btn.hide();
							respond_wrapper.find("#comment_parent").val(0);
							respond_wrapper.remove().insertAfter($('#' + '<?php echo $this->discussions_respond_id_location; ?>'));
						});
					})
				});
            </script>
			<?php
		}

		/**
		 * Tags the respond form so it can have it's ID changed
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function create_respond_form_wrapper_start() {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}

			$tag      = $this->discussions_respond_id_wrapper;
			$location = $this->discussions_respond_id_location;

			echo "<div id='{$location}'></div>";
			echo "<div id='{$tag}'>";
		}

		/**
		 * Tags the respond form so it can have it's ID changed
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function create_respond_form_wrapper_end() {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}

			echo '</div>';
		}

		/**
		 * Change reply link respond id
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $args
		 */
		public function change_reply_link_respond_id( $args ) {
			$tag               = $this->discussions_respond_id_wrapper;
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return $args;
			}
			$args['respond_id'] = $tag;
			return $args;
		}

		/**
		 * Fixes comments number
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $count
		 * @param $post_id
		 */
		public function fix_comments_number( $count, $post_id ) {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if (
				get_post_type() != 'product' ||
				! $is_discussion_tab
			) {
				return $count;
			}

			$comments = get_comments( array(
				'post_id' => $post_id,
				'count'   => true,
				'type'    => self::$comment_type_id
			) );

			return $comments;
		}

		/**
		 * Fixes products reviews count
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $count
		 * @param $product
		 *
		 * @return array|int
		 */
		public function fix_reviews_number( $count, $product ) {
			$comments = get_comments( array(
				'post_id'      => $product->get_id(),
				'count'        => true,
				'type__not_in' => self::$comment_type_id
			) );

			return $comments;
		}

	}
}