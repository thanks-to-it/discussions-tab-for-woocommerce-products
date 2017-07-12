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
		public $discussions_respond_id = 'alg_dtwp_respond';

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
		public function add_discussions_comment_type_to_wp_list_comments( $args ) {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}

			$args['type'] = self::$comment_type_id;
			//error_log( print_r( $args, true ) );
			return $args;
		}

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
		public function filter_discussions_comments_template_query_args( $args ) {
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if (
			! $is_discussion_tab
			) {
				return $args;
			}

			$args['type'] = self::$comment_type_id;
			return $args;
		}

		/**
		 * Changes discussions comments template
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
		public function change_respond_form_id() {
			$respond_id = $this->discussions_respond_id;
			?>
            <script>
				jQuery(document).ready(function ($) {
					var tag_input = $("input[name='<?php echo $respond_id?>']");
					if (tag_input.length) {
						var respond = tag_input.parent().find("#respond");
						if (respond.length) {
							respond.attr('id', '<?php echo $respond_id; ?>');
						}
					}
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
		public function tag_respond_form() {
			$tag               = $this->discussions_respond_id;
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}
			echo "<input type='hidden' name='{$tag}' value='1'>";
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
			$tag               = $this->discussions_respond_id;
			$plugin            = Alg_DTWP_Core::get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}

			$args['respond_id'] = $this->discussions_respond_id;
			return $args;
		}

	}
}