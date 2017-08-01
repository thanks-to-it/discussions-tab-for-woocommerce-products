<?php
/**
 * Discussions tab for WooCommerce Products - Discussions
 *
 * @version 1.0.2
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
		 * Adds discussions comment type in comment form
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_discussions_comment_type_in_form() {
			$plugin            = alg_dtwp_get_instance();
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
		 * Add discussions comment type in admin comment types dropdown
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $types
		 *
		 * @return mixed
		 */
		public function add_discussions_in_admin_comment_types_dropdown( $types ) {
			$types[ self::$comment_type_id ] = __( 'Discussions', 'discussions-tab-for-woocommerce-products' );
			return $types;
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
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return $args;
			}

			$args['type'] = self::$comment_type_id;
			return $args;
		}

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
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return $template;
			}

			$check_dirs = array(
				trailingslashit( get_stylesheet_directory() ) . WC()->template_path(),
				trailingslashit( get_template_directory() ) . WC()->template_path(),
				trailingslashit( get_stylesheet_directory() ),
				trailingslashit( get_template_directory() ),
				trailingslashit( $plugin->get_plugin_dir() ) . 'templates/',
			);

			if ( WC_TEMPLATE_DEBUG_MODE ) {
				$check_dirs = array( array_pop( $check_dirs ) );
			}

			foreach ( $check_dirs as $dir ) {
				if ( file_exists( trailingslashit( $dir ) . 'dtwp-comments.php' ) ) {
					return trailingslashit( $dir ) . 'dtwp-comments.php';
				}
			}

			return $template;
		}

		/**
		 * Fixes comment_parent input and cancel button
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function js_fix_comment_parent_id_and_cancel_btn() {
			$respond_id        = $this->discussions_respond_id_wrapper;
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}
			?>
            <script>
				jQuery(document).ready(function ($) {
					$('.comment-reply-link').on('click', function (e) {
						var respond_wrapper = $('#' + '<?php echo $respond_id;?>');
						if (!respond_wrapper.length) {
							e.preventDefault();
							return;
						}
						var comment_id = $(this).parent().parent().attr('id');
						var comment_id_arr = comment_id.split("-");
						var parent_post_id = comment_id_arr[comment_id_arr.length - 1];

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
			$plugin            = alg_dtwp_get_instance();
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
			$plugin            = alg_dtwp_get_instance();
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
			$plugin            = alg_dtwp_get_instance();
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
		 * @version 1.0.2
		 * @since   1.0.0
		 *
		 * @param $count
		 * @param $post_id
		 */
		public function fix_discussions_comments_number( $count, $post_id ) {
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if (
				get_post_type() != 'product' ||
				! $is_discussion_tab
			) {
				return $count;
			}

			$comments = get_comments( array(
				'post_id' => $post_id,
				'status'  => 'approve',
				'count'   => true,
				'type'    => self::$comment_type_id
			) );

			return $comments;
		}

		/**
		 * Fixes products reviews counting
		 *
		 * @version 1.0.2
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
				'status'       => 'approve',
				'parent'       => 0,
				'type__not_in' => self::$comment_type_id,
			) );

			return $comments;
		}

		/**
		 * Get avatar
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $avatar
		 * @param $id_or_email
		 * @param $args
		 *
		 * @return bool|string
		 */
		public function get_avatar( $avatar, $id_or_email, $args ) {
			if (
				! isset( $id_or_email->comment_type ) ||
				$id_or_email->comment_type != 'alg_dtwp_comment'
			) {
				return $avatar;
			}

			$id_or_email = $id_or_email->comment_author_email;

			$url2x = get_avatar_url( $id_or_email, array_merge( $args, array( 'size' => $args['size'] * 2 ) ) );

			$args = get_avatar_data( $id_or_email, $args );

			$url = $args['url'];

			if ( ! $url || is_wp_error( $url ) ) {
				return false;
			}

			$class = array( 'avatar', 'avatar-' . (int) $args['size'], 'photo' );

			if ( ! $args['found_avatar'] || $args['force_default'] ) {
				$class[] = 'avatar-default';
			}

			if ( $args['class'] ) {
				if ( is_array( $args['class'] ) ) {
					$class = array_merge( $class, $args['class'] );
				} else {
					$class[] = $args['class'];
				}
			}

			$avatar = sprintf(
				"<img alt='%s' src='%s' srcset='%s' class='%s' height='%d' width='%d' %s/>",
				esc_attr( $args['alt'] ),
				esc_url( $url ),
				esc_attr( "$url2x 2x" ),
				esc_attr( join( ' ', $class ) ),
				(int) $args['height'],
				(int) $args['width'],
				$args['extra_attr']
			);

			return $avatar;
		}

		/**
		 * Filters params passed to wp_list_comments function
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		public function filter_wp_list_comments_args( $args ) {
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return $args;
			}

			if ( class_exists( 'Storefront' ) ) {
				$args['style']      = 'ol';
				$args['short_ping'] = true;
				$args['callback']   = 'storefront_comment';
			} else if ( function_exists( 'wf_get_version' ) ) {
				$args['avatar_size'] = 50;
				$args['callback']    = 'custom_comment';
			}
			return $args;
		}

		/**
		 * Filters the class of wp_list_comments wrapper
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $class
		 */
		public function filter_wp_list_comments_wrapper_class( $class ) {
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return $class;
			}

			if ( function_exists( 'wf_get_version' ) ) {
				$class[] = 'commentlist';
			}
			return $class;
		}

		/**
		 * Filters the comment class
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $class
		 *
		 * @return mixed
		 */
		public function filter_comment_class( $class ) {
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return $class;
			}

			$class[] = 'comment';
			return $class;
		}

		/**
		 * Fixes Hub theme get_comment_type()
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 *
		 * @param $type
		 *
		 * @return string
		 */
		public function fix_hub_get_comment_type( $type ) {
			$theme_name        = wp_get_theme()->get( 'Name' );
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if (
				$theme_name != 'Hub' ||
				! $is_discussion_tab
			) {
				return $type;
			}

			$type = 'comment';
			return $type;
		}

		/**
		 * Changes comment link to "#discussion-"
		 *
		 * @version 1.0.2
		 * @since   1.0.2
		 *
		 * @param            $link
		 * @param WP_Comment $comment
		 * @param            $args
		 * @param            $cpage
		 *
		 * @return mixed
		 */
		public function change_comment_link( $link, WP_Comment $comment, $args, $cpage ) {
			if ( $comment->comment_type != self::$comment_type_id ) {
				return $link;
			}

			$link = str_replace( "#comment-", "#discussion-", $link );
			return $link;
		}

		/**
		 * Opens discussions tab in frontend after a discussion comment is posted
		 *
		 * @version 1.0.2
		 * @since   1.0.2
		 */
		public function js_open_discussions_tab() {
			$plugin            = alg_dtwp_get_instance();
			$is_discussion_tab = $plugin->registry->get_discussions_tab()->is_discussion_tab();
			if ( ! $is_discussion_tab ) {
				return;
			}
			?>
            <script>
				jQuery(function ($) {

					$(document).ready(function () {
						window.onhashchange = function () {
							go_to_discussion_tab();
						}
						function go_to_discussion_tab() {
							var hash = window.location.hash;
							if (hash.toLowerCase().indexOf('discussion-') >= 0 || hash === '#alg_dtwp' || hash === '#tab-alg_dtwp') {
								var hash_split = hash.split('#discussion-');
								var comment_id = hash_split[1];
								$('#tab-title-alg_dtwp a').trigger('click');
								if ($('#comment-' + comment_id)[0]) {
									$('#comment-' + comment_id)[0].scrollIntoView(true);
								}
							}
						}

						go_to_discussion_tab();
					});
				});
            </script>
			<?php
		}

	}
}