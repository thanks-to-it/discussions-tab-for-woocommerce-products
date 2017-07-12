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
			}else{
				return wp_filter_object_list( $comments_flat, array( 'comment_type' => self::$comment_type_id ),'NOT' );
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
			if ( isset( $request[ self::$comment_type_id ] ) && filter_var( $request[ self::$comment_type_id ], FILTER_VALIDATE_BOOLEAN ) != false ) {
				$comment_data['comment_type'] = self::$comment_type_id;
			}
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

		public function filter_discussions_comments_template_query_args($args){
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
	}
}