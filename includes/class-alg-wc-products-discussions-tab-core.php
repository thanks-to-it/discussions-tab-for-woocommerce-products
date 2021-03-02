<?php
/**
 * Discussions Tab for WooCommerce Products - Core Class
 *
 * @version 1.2.6
 * @since   1.1.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_Core' ) ) :

class Alg_WC_Products_Discussions_Tab_Core {

	/**
	 * is_discussion_tab.
	 *
	 * @todo    [dev] (maybe) remove? (same to all classes properties)
	 */
	private $is_discussion_tab = false;

	/**
	 * discussions_respond_id_wrapper.
	 */
	public $discussions_respond_id_wrapper = 'alg_dtwp_respond';

	/**
	 * discussions_respond_id_location.
	 */
	public $discussions_respond_id_location = 'alg_dtwp_respond_location';

	/**
	 * Constructor.
	 *
	 * @version 1.2.5
	 * @since   1.1.0
	 * @todo    [dev] (maybe) `get_option()`: `filter_var()`?
	 * @todo    [dev] (maybe) create `class-alg-wc-products-discussions-tab-scripts.php`
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_dtwp_opt_enable', 'yes' ) ) {

			// Handle template
			add_filter( 'woocommerce_locate_template',             array( $this, 'locate_template' ), 10, 3 );
			add_filter( 'woocommerce_locate_core_template',        array( $this, 'locate_template' ), 10, 3 );

			// Scripts
			add_action( 'wp_enqueue_scripts',                      array( $this, 'load_scripts' ) );

			// Adds discussion tab in product page
			add_filter( 'woocommerce_product_tabs',                array( $this, 'add_discussions_tab' ) );

			// Inserts comments as discussion comment type in database
			add_action( 'comment_form_top',                        array( $this, 'add_discussions_comment_type_in_form' ) );
			add_filter( 'preprocess_comment',                      array( $this, 'add_discussions_comment_type_in_comment_data' ) );

			// Hides discussion comments on improper places
			add_action( 'pre_get_comments',                        array( $this, 'hide_discussion_comments_on_default_callings' ) );

			// Loads discussion comments
			add_filter( 'comments_template_query_args',            array( $this, 'filter_discussions_comments_template_query_args' ) );

			// Swaps woocommerce template (single-product-reviews.php) with default comments template
			add_filter( 'comments_template',                       array( $this, 'load_discussions_comments_template' ), 20 );

			// Opens discussions tab after a discussion comment is posted
			add_action( 'wp_footer',                               array( $this, 'js_open_discussions_tab' ) );

			// Tags the respond form so it can have it's ID changed
			add_action( 'comment_form_before',                     array( $this, 'create_respond_form_wrapper_start' ) );
			add_action( 'comment_form_after',                      array( $this, 'create_respond_form_wrapper_end' ) );

			// Change reply link respond id
			add_filter( 'comment_reply_link_args',                 array( $this, 'change_reply_link_respond_id' ) );

			// Fixes comments count
			add_filter( 'get_comments_number',                     array( $this, 'fix_discussions_comments_number' ), 10, 2 );
			add_filter( 'woocommerce_product_get_review_count',    array( $this, 'fix_reviews_number' ), 10, 2 );

			// Get avatar data
			add_filter( 'get_avatar_comment_types',                array( $this, 'add_discussions_to_avatar_comment_types' ) );

			// Filters params passed to `wp_list_comments` function
			add_filter( 'wp_list_comments_args',                   array( $this, 'filter_wp_list_comments_args' ) );

			// Filters the class of `wp_list_comments` wrapper
			add_filter( 'alg_dtwp_wp_list_comments_wrapper_class', array( $this, 'filter_wp_list_comments_wrapper_class' ) );

			// Filters the comment class
			add_filter( 'comment_class',                           array( $this, 'filter_comment_class' ) );

			// Changes comment link to `#discussion-`
			add_filter( 'get_comment_link',                        array( $this, 'change_comment_link' ), 10, 4 );

			// Handle shortcodes
			add_filter( 'comment_text',                            array( $this, 'handle_shortcodes' ), 10, 2 );

			// Open comments for product post type
			add_filter( 'comments_open',                           array( $this, 'comments_open' ), 20, 2 );

			// Display comments form
			add_action( 'alg_dtwp_comments_end',                   array( $this, 'setup_comments_form_position' ) );
			add_action( 'alg_dtwp_comments_start',                 array( $this, 'setup_comments_form_position' ) );

			// Compatibility
			require_once( 'class-alg-wc-products-discussions-tab-compatibility.php' );

		}
		// Core contentCalled
		do_action( 'alg_wc_products_discussions_tab_core_loaded' );

		/* Add WYSISYG editor to comment form. */

	}

	/**
	 * Add discussions comment type to avatar comment types.
	 *
	 * This will fix the problem of empty url from avatars.
	 *
	 * @version 1.2.5
	 * @since   1.2.5
	 *
	 * @param $comment_types
	 *
	 * @return array
	 */
	function add_discussions_to_avatar_comment_types( $comment_types ) {
		$comment_types[] = alg_wc_pdt_get_comment_type_id();
		return $comment_types;
	}

	/**
	 * setup_comments_form_position.
	 *
	 * @version 1.2.4
	 * @since   1.2.4
	 */
	function setup_comments_form_position() {
		$comment_form_position = apply_filters( 'alg_dtwp_opt_comment_form_position', 'alg_dtwp_comments_end' );
		if ( $comment_form_position == current_filter() ) {
			$this->display_comments_form();
		}
		if ( 'alg_dtwp_comments_start' == $comment_form_position ) {
			echo '<div style="margin-bottom:50px"></div>';
		}
	}

	/**
	 * display_comments_form.
	 *
	 * @version 1.2.4
	 * @since   1.2.4
	 */
	function display_comments_form() {
		$discussions_respond_title        = sanitize_text_field( get_option( 'alg_dtwp_discussions_respond_title', __( 'Leave a reply', 'discussions-tab-for-woocommerce-products' ) ) );
		$discussions_comment_btn_label    = sanitize_text_field( get_option( 'alg_dtwp_discussions_post_comment_label', __( 'Post Comment', 'discussions-tab-for-woocommerce-products' ) ) );
		$discussions_textarea_placeholder = sanitize_text_field( get_option( 'alg_dtwp_discussions_textarea_placeholder', '' ) );
		comment_form( array(
			'title_reply'     => $discussions_respond_title,
			'label_submit'    => $discussions_comment_btn_label,
			'class_container' => 'alg-dtwp-comment-respond',
			'id_form'         => 'discussionform',
			'id_submit'       => 'submit_discussion',
			'comment_field'   => sprintf(
				'<p class="comment-form-comment">%s %s</p>',
				sprintf(
					'<label for="discussion">%s</label>',
					_x( 'Comment', 'noun' )
				),
				'<textarea id="discussion" name="comment" cols="45" rows="8" maxlength="65525" required="required" placeholder="' . $discussions_textarea_placeholder . '"></textarea>'
			),
		) );
	}

	/**
	 * comments_open.
	 *
	 * @version 1.2.3
	 * @since   1.2.3
	 *
	 * @param $open
	 * @param $post_id
	 *
	 * @return boolean
	 */
	function comments_open( $open, $post_id ) {
		//if ( 'product' === get_post_type( $post_id ) && alg_wc_pdt_is_discussion_tab() ) {
		if ( 'product' === get_post_type( $post_id ) ) {
			$open = apply_filters( 'alg_dtwp_comments_open', filter_var( get_option( 'alg_dtwp_opt_open_comments', 'no' ), FILTER_VALIDATE_BOOLEAN ), $post_id );
		}
		return $open;
	}

	/**
	 * get_discussions_tab_id.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_discussions_tab_id() {
		return sanitize_title( sanitize_text_field( apply_filters( 'alg_dtwp_filter_tab_id', get_option( 'alg_dtwp_opt_tab_id', 'discussions' ) ) ) );
	}

	/**
	 * get_comment_link.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_comment_link() {
		return sanitize_title( sanitize_text_field( apply_filters( 'alg_dtwp_filter_comment_link', get_option( 'alg_dtwp_opt_comment_link', 'discussion' ) ) ) );
	}

	/**
	 * Adds discussions tab.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 * @param   $tabs
	 * @return  mixed
	 */
	function add_discussions_tab( $tabs ) {
		$discussions_label     = get_option( 'alg_dtwp_discussions_label', __( 'Discussions', 'discussions-tab-for-woocommerce-products' ) );
		$discussions_tab_title = get_option( 'alg_dtwp_discussions_tab_title', '%label% (%number_of_comments%)' );		
		if ( false !== strpos( $discussions_tab_title, '%number_of_comments%' ) ) {
			global $post;
			$count_replies_opt = filter_var( get_option( 'alg_dtwp_opt_count_replies', 'yes' ), FILTER_VALIDATE_BOOLEAN );
			$parent_opt        = $count_replies_opt ? '' : false;
			$comments          = get_comments( array(
				'post_id' => $post->ID,
				'status'  => 'approve',
				'count'   => true,
				'parent'  => $parent_opt,
				'type'    => alg_wc_pdt_get_comment_type_id(),
			) );
		} else {
			$comments = false;
		}
		$title = str_replace( array( '%label%', '%number_of_comments%' ), array( sanitize_text_field( $discussions_label ), $comments ), $discussions_tab_title );
		$tabs[ $this->get_discussions_tab_id() ] = array(
			'title'    => $title,
			'priority' => get_option( 'alg_dtwp_opt_tab_priority', 50 ),
			'callback' => array( $this, 'add_discussions_tab_content' ),
		);
		return $tabs;
	}

	/**
	 * Adds discussions comments.
	 *
	 * @version 1.2.3
	 * @since   1.0.0
	 */
	function add_discussions_tab_content() {
		do_action( 'alg_dtwp_tab_content' );
		if ( apply_filters( 'alg_dtwp_comments_template_output_validation', true ) ) {
			$this->comments_template();
		}
	}

	/**
	 * get_comments_template.
	 *
	 * @version 1.2.3
	 * @since   1.2.3
	 *
	 * @param bool $echo
	 *
	 * @return false|string
	 */
	function comments_template( $echo = true ) {
		$this->is_discussion_tab = true;
		ob_start();
		comments_template();
		$result = ob_get_contents();
		ob_end_clean();
		if ( $echo ) {
			echo $result;
		}
		do_action( 'alg_dtwp_after_comments_template' );
		$this->is_discussion_tab = false;
		return $result;
	}

	/**
	 * Check if is displaying discussion tab.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  bool
	 *
	 */
	function is_discussion_tab() {
		return $this->is_discussion_tab;
	}

	/**
	 * Override woocommerce locate template.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $template
	 * @param   $template_name
	 * @param   $template_path
	 * @return  string
	 * @todo    [dev] (check) this seems to be never called (i.e. `false !== strpos( $template_name, 'dtwp-' )`)
	 */
	function locate_template( $template, $template_name, $template_path ) {
		if ( false !== strpos( $template_name, 'dtwp-' ) ) {
			$template = locate_template( array( 'woocommerce/' . $template_name, $template_name ) );
			// Get default template
			if ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
				$template = alg_wc_products_discussions_tab()->plugin_path() . '/templates/' . $template_name;
			}
		}
		return $template;
	}

	/**
	 * Enqueues main scripts.
	 *
	 * @version 1.2.6
	 * @since   1.0.0
	 */
	function load_scripts(){
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		// Main css file
		wp_enqueue_style( 'alg-dtwp',
			alg_wc_products_discussions_tab()->plugin_url() . '/assets/css/frontend' . $suffix . '.css',
			array(),
			alg_wc_products_discussions_tab()->version
		);
		if ( is_product() ) {
			wp_enqueue_script( 'alg-dtwp', alg_wc_products_discussions_tab()->plugin_url() . '/assets/js/frontend' . $suffix . '.js', array(), 'false', true );
			wp_localize_script( 'alg-dtwp', 'alg_dtwp', apply_filters( 'alg_dtwp_localize_script', array(
				'respondID' => $this->discussions_respond_id_wrapper,
				'respondIDLocation' => $this->discussions_respond_id_location,
				'plugin_url'    => alg_wc_products_discussions_tab()->plugin_url(),
				'modulesToLoad' => apply_filters( 'alg_dtwp_js_modules_to_load', array() )
			) ) );
			// Action
		}
		do_action( 'alg_wc_pdt_load_scripts' );
	}

	/**
	 * Controls shortcodes in comments and discussions.
	 *
	 * @version 1.2.2
	 * @since   1.0.1
	 *
	 * @param   $comment_text
	 * @param   $comment
	 *
	 * @return string
	 */
	function handle_shortcodes( $comment_text, $comment ) {
		$allow_in_discussions = filter_var( get_option( 'alg_dtwp_opt_sc_discussions', false ), FILTER_VALIDATE_BOOLEAN );
		$allow_in_admin       = filter_var( get_option( 'alg_dtwp_opt_sc_admin',       false ), FILTER_VALIDATE_BOOLEAN );
		if ( ( ! $allow_in_admin && is_admin() ) || ! is_object( $comment ) ) {
			return $comment_text;
		}
		if ( $allow_in_discussions && $comment->comment_type == alg_wc_pdt_get_comment_type_id() ) {
			$comment_text = do_shortcode( $comment_text );
		}
		return $comment_text;
	}

	/**
	 * Adds discussions comment type in comment form.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function add_discussions_comment_type_in_form() {
		if ( ! alg_wc_pdt_is_discussion_tab() ) {
			return;
		}
		echo '<input type="hidden" name="' . esc_attr( alg_wc_pdt_get_comment_type_id() ) . '" value="1"/>';
	}

	/**
	 * Adds discussions comment type in comment data.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   $comment_data
	 *
	 * @return mixed
	 */
	function add_discussions_comment_type_in_comment_data( $comment_data ) {
		$comment_type_id = alg_wc_pdt_get_comment_type_id();
		if (
			(   isset( $_REQUEST[ $comment_type_id ] ) && filter_var( $_REQUEST[ $comment_type_id ], FILTER_VALIDATE_BOOLEAN ) ) ||
			( ! isset( $_REQUEST[ $comment_type_id ] ) && ! empty( $comment_data['comment_parent'] ) && get_comment_type( $comment_data['comment_parent'] ) == $comment_type_id )
		) {
			$comment_data['comment_type'] = $comment_type_id;
		}
		return $comment_data;
	}

	/**
	 * Hides discussions comments on default callings.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @todo    [dev] (maybe) `\WP_Comment_Query`
	 */
	function hide_discussion_comments_on_default_callings( $query ) {
		global $pagenow;
		if ( $query->query_vars['type'] === alg_wc_pdt_get_comment_type_id() || ! empty( $pagenow ) && 'edit-comments.php' == $pagenow ) {
			return;
		}
		$query->query_vars['type__not_in'] = array_merge( ( array ) $query->query_vars['type__not_in'], array( alg_wc_pdt_get_comment_type_id() ) );
	}

	/**
	 * Loads discussion comments.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $args
	 * @return  mixed
	 */
	function filter_discussions_comments_template_query_args( $args ) {
		if ( ! alg_wc_pdt_is_discussion_tab() ) {
			return $args;
		}
		$args['type'] = alg_wc_pdt_get_comment_type_id();
		return $args;
	}

	/**
	 * Swaps woocommerce template (single-product-reviews.php) with default comments template.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $template
	 * @return  mixed
	 * @todo    [fix] non-unique id `#_wp_unfiltered_html_comment_disabled` (see `wp_comment_form_unfiltered_html_nonce()` in `wp-includes/comment-template.php`)
	 * @todo    [fix] non-unique id `#comment_parent` (see `get_comment_id_fields()` and `comment_form_submit_field` filter in `wp-includes/comment-template.php`)
	 * @todo    [fix] non-unique id `#comment_post_ID` (see same as for `#comment_parent`)
	 */
	function load_discussions_comments_template( $template ) {
		if ( 'product' !== get_post_type() || ! alg_wc_pdt_is_discussion_tab() ) {
			return $template;
		}
		$check_dirs = array(
			trailingslashit( get_stylesheet_directory() ) . WC()->template_path(),
			trailingslashit( get_template_directory() )   . WC()->template_path(),
			trailingslashit( get_stylesheet_directory() ),
			trailingslashit( get_template_directory() ),
			trailingslashit( alg_wc_products_discussions_tab()->plugin_path() ) . 'templates/',
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
	 * Tags the respond form so it can have it's ID changed.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function create_respond_form_wrapper_start() {
		if ( ! alg_wc_pdt_is_discussion_tab() ) {
			return;
		}
		$tag      = $this->discussions_respond_id_wrapper;
		$location = $this->discussions_respond_id_location;
		echo "<div id='{$location}'></div>";
		echo "<div id='{$tag}'>";
	}

	/**
	 * Tags the respond form so it can have it's ID changed.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function create_respond_form_wrapper_end() {
		if ( ! alg_wc_pdt_is_discussion_tab() ) {
			return;
		}
		echo '</div>';
	}

	/**
	 * Change reply link respond id.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   $args
	 *
	 * @return mixed
	 */
	function change_reply_link_respond_id( $args ) {
		$tag = $this->discussions_respond_id_wrapper;
		if ( ! alg_wc_pdt_is_discussion_tab() ) {
			return $args;
		}
		$args['respond_id'] = $tag;
		return $args;
	}

	/**
	 * Fixes comments number.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   $count
	 * @param   $post_id
	 *
	 * @return array|int
	 */
	function fix_discussions_comments_number( $count, $post_id ) {
		if ( 'product' != get_post_type() || ! alg_wc_pdt_is_discussion_tab() ) {
			return $count;
		}
		$count_replies_opt = filter_var( get_option( 'alg_dtwp_opt_count_replies', 'yes' ), FILTER_VALIDATE_BOOLEAN );
		$parent_opt        = $count_replies_opt ? '' : false;
		$comments = get_comments( array(
			'post_id' => $post_id,
			'parent'  => $parent_opt,
			'status'  => 'approve',
			'count'   => true,
			'type'    => alg_wc_pdt_get_comment_type_id(),
		) );
		return $comments;
	}

	/**
	 * Fixes products reviews counting.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $count
	 * @param   $product
	 * @return  array|int
	 */
	function fix_reviews_number( $count, $product ) {
		return get_comments( array(
			'post_id'      => $product->get_id(),
			'count'        => true,
			'status'       => 'approve',
			'parent'       => 0,
			'type__not_in' => alg_wc_pdt_get_comment_type_id(),
		) );
	}

	/**
	 * Filters params passed to `wp_list_comments()` function.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $args
	 * @return  mixed
	 */
	function filter_wp_list_comments_args( $args ) {
		if ( ! alg_wc_pdt_is_discussion_tab() ) {
			return $args;
		}
		$args              = apply_filters( 'alg_dtwp_wp_list_comments_args', $args );
		$callback_function = sanitize_text_field( get_option( 'alg_dtwp_wp_list_comment_cb', '' ) );
		if ( ! empty( $callback_function ) ) {
			$args['callback'] = $callback_function;
		}
		return $args;
	}

	/**
	 * Filters the class of wp_list_comments wrapper.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   $class
	 *
	 * @return array
	 */
	function filter_wp_list_comments_wrapper_class( $class ) {
		if ( ! alg_wc_pdt_is_discussion_tab() ) {
			return $class;
		}
		return array_map( 'esc_attr', array_unique( $class ) );
	}

	/**
	 * Filters the comment class.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $class
	 * @return  mixed
	 */
	function filter_comment_class( $class ) {
		if ( ! alg_wc_pdt_is_discussion_tab() ) {
			return $class;
		}
		$class[] = 'comment';
		return $class;
	}

	/**
	 * Changes comment link to `#discussion-`.
	 *
	 * @version 1.1.0
	 * @since   1.0.2
	 * @param   $link
	 * @param   WP_Comment $comment
	 * @param   $args
	 * @param   $cpage
	 * @return  mixed
	 */
	function change_comment_link( $link, WP_Comment $comment, $args, $cpage ) {
		if ( $comment->comment_type != alg_wc_pdt_get_comment_type_id() ) {
			return $link;
		}
		return str_replace( '#comment-', '#' . $this->get_comment_link() . '-', $link );
	}

	/**
	 * Opens discussions tab in frontend after a discussion comment is posted.
	 *
	 * @version 1.1.0
	 * @since   1.0.2
	 */
	function js_open_discussions_tab() {
		if(!is_product()){
			return;
		}
		?>
		<script>
			jQuery( function( $ ) {
				$( document ).ready( function() {
					var alg_dtwp_tab          = '<?php echo $this->get_discussions_tab_id(); ?>';
					var alg_dtwp_comment_link = '<?php echo $this->get_comment_link(); ?>';
					window.onhashchange       = function() {
						go_to_discussion_tab();
					}
					function go_to_discussion_tab() {
						var hash = window.location.hash;
						if ( hash.toLowerCase().indexOf( alg_dtwp_comment_link + '-' ) >= 0 || hash === '#' + alg_dtwp_tab || hash === '#tab-' + alg_dtwp_tab ) {
							var hash_split = hash.split( '#' + alg_dtwp_comment_link + '-' );
							var comment_id = hash_split[1];
							$( '#tab-title-' + alg_dtwp_tab + ' a' ).trigger( 'click' );
							if ( $( '#comment-' + comment_id )[0] ) {
								$( '#comment-' + comment_id )[0].scrollIntoView( true );
							}
						}
					}
					go_to_discussion_tab();
				} );
			} );
		</script>
		<?php
	}

}

endif;

return new Alg_WC_Products_Discussions_Tab_Core();
