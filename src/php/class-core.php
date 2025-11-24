<?php
/**
 * Discussions Tab for WooCommerce Products - Core Class.
 *
 * @version 1.5.9
 * @since   1.1.0
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Core' ) ) :

class Core {

	/**
	 * is_discussion_tab.
	 *
	 * @todo    [dev] (maybe) remove? (same to all classes properties)
	 */
	private $is_discussion_tab = false;

	private $discussion_comment_on_update = false;

	/**
	 * discussions_respond_id_wrapper.
	 */
	public $discussions_respond_id_wrapper = 'alg_dtwp_respond';

	/**
	 * discussions_respond_id_location.
	 */
	public $discussions_respond_id_location = 'alg_dtwp_respond_location';

	/**
	 * $escape_code_and_pre.
	 *
	 * @since 1.5.9.
	 *
	 * @var
	 */
	public $escape_code_and_pre;

	/**
	 * $need_to_remove_content_from_comments.
	 *
	 * @since 1.5.9.
	 *
	 * @var
	 */
	public $need_to_remove_content_from_comments;

	/**
	 * $content_to_remove_from_comments.
	 *
	 * @since 1.5.9.
	 *
	 * @var
	 */
	public $content_to_remove_from_comments;

	/**
	 * Constructor.
	 *
	 * @version 1.5.9
	 * @since   1.1.0
	 * @todo    [dev] (maybe) `get_option()`: `filter_var()`?
	 * @todo    [dev] (maybe) create `class-alg-wc-products-discussions-tab-scripts.php`
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_dtwp_opt_enable', 'yes' ) ) {

			// Handle template.
			add_filter( 'woocommerce_locate_template',             array( $this, 'locate_template' ), 10, 3 );
			add_filter( 'woocommerce_locate_core_template',        array( $this, 'locate_template' ), 10, 3 );

			// Scripts.
			add_action( 'wp_enqueue_scripts',                      array( $this, 'load_scripts' ) );

			// Adds discussion tab in product page.
			add_filter( 'woocommerce_product_tabs',                array( $this, 'add_discussions_tab' ) );

			// Inserts comments as discussion comment type in database.
			add_action( 'comment_form_top',                        array( $this, 'add_discussions_comment_type_in_form' ) );
			add_filter( 'preprocess_comment',                      array( $this, 'add_discussions_comment_type_in_comment_data' ) );

			// Hides discussion comments on improper places.
			add_action( 'pre_get_comments',                        array( $this, 'hide_discussion_comments_on_default_callings' ) );

			// Loads discussion comments.
			add_filter( 'comments_template_query_args',            array( $this, 'filter_discussions_comments_template_query_args' ) );

			// Swaps woocommerce template (single-product-reviews.php) with default comments template.
			add_filter( 'comments_template',                       array( $this, 'load_discussions_comments_template' ), 20 );

			// Tags the respond form so it can have it's ID changed.
			add_action( 'comment_form_before',                     array( $this, 'create_respond_form_wrapper_start' ) );
			add_action( 'comment_form_after',                      array( $this, 'create_respond_form_wrapper_end' ) );

			// Change reply link respond id.
			add_filter( 'comment_reply_link_args',                 array( $this, 'change_reply_link_respond_id' ) );

			// Fixes comments count.
			add_filter( 'get_comments_number',                     array( $this, 'fix_discussions_comments_number' ), 10, 2 );
			add_filter( 'woocommerce_product_get_review_count',    array( $this, 'fix_reviews_number' ), 10, 2 );

			// Get avatar data.
			add_filter( 'get_avatar_comment_types',                array( $this, 'add_discussions_to_avatar_comment_types' ) );

			// Filters params passed to `wp_list_comments` function.
			add_filter( 'wp_list_comments_args',                   array( $this, 'filter_wp_list_comments_args' ) );

			// Filters the class of `wp_list_comments` wrapper.
			add_filter( 'alg_dtwp_wp_list_comments_wrapper_class', array( $this, 'filter_wp_list_comments_wrapper_class' ) );

			// Filters the comment class.
			add_filter( 'comment_class',                           array( $this, 'filter_comment_class' ) );

			// Changes comment link to `#discussion-`.
			add_filter( 'get_comment_link',                        array( $this, 'change_comment_link' ), 10, 4 );

			// Handle shortcodes.
			add_filter( 'comment_text',                            array( $this, 'handle_shortcodes' ), 10, 2 );

			// Open comments for product post type.
			add_filter( 'comments_open',                           array( $this, 'comments_open' ), 20, 2 );

			// Display comments form.
			add_action( 'alg_dtwp_comments_end',                   array( $this, 'setup_comments_form_position' ) );
			add_action( 'alg_dtwp_comments_start',                 array( $this, 'setup_comments_form_position' ) );

			// Detect plugin update.
			add_action( 'upgrader_process_complete',               array( $this, 'detect_plugin_update' ), 10, 2 );

			// Compatibility.
			new Compatibility();

			// My account tab.
			new My_Account();

			// New comment Email.
			new New_Comment_Email();

			// Filters and sanitize comment data.
			add_filter( 'pre_comment_content', array( $this, 'filter_and_sanitize_comment' ), 20 );

			// Fix comment edit redirect.
			add_filter( 'comment_edit_redirect', array( $this, 'fix_comment_edit_redirect_from_frontend' ), 11, 2 );

			// Edit comment link.
			add_filter( 'edit_comment_link', array( $this, 'handle_discussion_comment_edit_link' ), 10, 2 );

			// Fix pagination link.
			add_filter( 'get_comments_pagenum_link', array( $this, 'fix_pagination_link' ) );

			// Hide discussion comments for guest users.
			add_action( 'pre_get_comments', array( $this, 'hide_discussion_comments_for_guests' ) );

			// Triggers `alg_dtwp_pre_discussion_comment_on_post` action.
			add_action( 'pre_comment_on_post', array( $this, 'pre_discussion_comment_on_post' ) );

			// Check for errors before creating a discussion comment on a post.
			add_action( 'alg_dtwp_pre_discussion_comment_on_post', array( $this, 'validate_pre_discussion_comment_on_post' ) );
			add_filter( 'alg_dtwp_pre_discussion_comment_on_post_errors', array( $this, 'add_post_author_and_admin_exceptions_to_possible_errors' ), 100, 2 );

			$this->handle_previous_pro_features();
		}
		// Core content called.
		do_action( 'alg_wc_products_discussions_tab_core_loaded' );
	}

	/**
	 * handle_previous_pro_features.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * @return void
	 */
	function handle_previous_pro_features(){
		add_filter( 'alg_wc_products_discussions_tab_settings', array( $this, 'settings' ), 10, 3 );
		add_action( 'alg_wc_products_discussions_tab_core_loaded', array( $this, 'core_action' ) );
		// Ajax tab.
		add_filter( 'alg_dtwp_comments_template_output_validation', array( $this, 'handle_ajax_tab' ) );
		add_filter( 'alg_dtwp_js_modules_to_load', array( $this, 'load_ajax_tab' ) );
		add_action( 'wp_ajax_nopriv_' . 'alg_dtwp_get_tab_content', array( $this, 'load_comments_template_via_ajax' ) );
		add_action( 'wp_ajax_' . 'alg_dtwp_get_tab_content', array( $this, 'load_comments_template_via_ajax' ) );
		// Comment form position.
		add_filter( 'alg_dtwp_opt_comment_form_position', array( $this, 'filter_comments_form_position' ) );
		// TinyMCE.
		add_filter( 'alg_dtwp_js_modules_to_load', array( $this, 'load_wp_editor' ) );
		// Remove content.
		add_filter( 'comment_text', array( $this, 'remove_content_from_comments' ), 10, 2 );
		// Content escaping.
		add_filter( 'comment_text', array( $this, 'escape_code_and_pre' ), 1 );
	}

	/**
	 * escape_code_and_pre.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * escape_code_and_pre.
	 *
	 * @param $text
	 *
	 * @return string;
	 */
	function escape_code_and_pre( $text ) {
		$escape_code_and_pre = ! isset( $this->escape_code_and_pre ) ? $this->escape_code_and_pre = 'yes' === get_option( 'alg_dtwp_escape_code_and_pre', 'no' ) : $this->escape_code_and_pre;
		if ( $escape_code_and_pre ) {
			$text = preg_replace_callback( '/\<pre\>(.+?)\<\/pre\>/s', function ( $matches ) {
				return '<pre>' . esc_html( $matches[1] ) . '</pre>';
			}, $text );
			$text = preg_replace_callback( '/\<code\>(.+?)\<\/code\>/s', function ( $matches ) {
				return '<code>' . esc_html( $matches[1] ) . '</code>';
			}, $text );
		}
		return $text;
	}

	/**
	 * need_to_remove_content_from_comments.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * @return mixed|void
	 */
	function need_to_remove_content_from_comments() {
		if ( ! isset( $this->need_to_remove_content_from_comments ) ) {
			$this->need_to_remove_content_from_comments = get_option( 'alg_dtwp_opt_remove_content', 'no' );
		}
		return 'yes' === $this->need_to_remove_content_from_comments;
	}

	/**
	 * get_content_to_remove_from_comments.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * @return mixed|void
	 */
	function get_content_to_remove_from_comments() {
		if ( ! isset( $this->content_to_remove_from_comments ) ) {
			$this->content_to_remove_from_comments = get_option( 'alg_dtwp_opt_content_to_remove', '<p>&nbsp;</p>' );
		}
		return $this->content_to_remove_from_comments;
	}

	/**
	 * remove_content_from_comments.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * @param $comment_text
	 * @param $comment
	 *
	 * @return string
	 */
	function remove_content_from_comments( $comment_text, $comment ) {
		if (
			$this->need_to_remove_content_from_comments()
			&& ! empty( $content_to_remove = $this->get_content_to_remove_from_comments() )
			&& $comment
			&& ! empty( $comment->comment_type )
			&& alg_wc_pdt_get_comment_type_id() === $comment->comment_type
		) {
			$content_to_remove_arr = explode( "\n", str_replace( "\r", "", $content_to_remove ) );
			$comment_text          = str_replace( $content_to_remove_arr, '', $comment_text );
		}
		return $comment_text;
	}

	/**
	 * load_ajax_tab.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * @param $localize_data
	 *
	 * @return array
	 */
	function load_ajax_tab( $modules_to_load ) {
		if (
			'yes' !== get_option( 'alg_dtwp_opt_ajax_tab', 'no' )
			|| ! is_product()
		) {
			return $modules_to_load;
		}
		$modules_to_load[] = 'ajax-tab';
		return $modules_to_load;
	}

	/**
	 * load_wp_editor.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * @param $modules_to_load
	 *
	 * @return array
	 */
	function load_wp_editor( $modules_to_load ) {
		if (
			is_product()
			&& 'yes' === get_option( 'alg_dtwp_opt_tinymce', 'no' )
			&& user_can_richedit()
		) {
			wp_enqueue_editor();
			$modules_to_load[] = 'wp-editor';
		}
		return $modules_to_load;
	}

	/**
	 * filter_comments_form_position.
	 *
	 * @vesion 1.5.9
	 * @since  1.5.9
	 *
	 * @param $position
	 *
	 * @return mixed|void
	 */
	function filter_comments_form_position( $position ) {
		$position = get_option( 'alg_dtwp_opt_comment_form_position', 'alg_dtwp_comments_end' );
		return $position;
	}

	/**
	 * load_comments_template_via_ajax.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 */
	function load_comments_template_via_ajax() {
		global $withcomments, $post;
		$withcomments = true;
		$post         = get_post( intval( $_POST['post_id'] ) );
		if ( is_null( $post ) ) {
			wp_send_json_error();
		} else {
			$comments_template = alg_wc_products_discussions_tab()->core->get_comments_template( false );
			wp_send_json_success( array( 'content' => $comments_template ) );
		}
	}

	/**
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * @param $validation
	 *
	 * @return bool
	 */
	function handle_ajax_tab( $validation ) {
		if ( 'yes' === get_option( 'alg_dtwp_opt_ajax_tab', 'no' ) ) {
			$validation = false;
		}
		return $validation;
	}

	/**
	 * core_action.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 */
	function core_action() {
		// Core
		if ( 'yes' === get_option( 'alg_dtwp_opt_enable', 'yes' ) ) {

			// Tests if is verified owner before create discussion comment on post.
			add_filter( 'alg_dtwp_pre_discussion_comment_on_post_errors', array( $this, 'add_verified_owner_error_on_pre_discussion_comment_on_post' ), 10, 2 );

			// Social.
			if ( 'yes' === get_option( 'alg_dtwp_social_enable', 'no' ) ) {
				new Social();
			}

			// Labels.
			if ( 'yes' === get_option( 'alg_dtwp_labels_enable', 'no' ) ) {
				new Labels();
			}

			// Support reps.
			if ( 'yes' === get_option( 'alg_dtwp_opt_support_label', 'no' ) ) {
				new Support();
			}

			// Admin comment editor.
			new Admin_Comment_Editor();

			// New comment email.
			new New_Comment_Email_Pro();
		}
	}

	/**
	 * add_verified_owner_error_on_pre_discussion_comment_on_post.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 *
	 * @param $errors
	 * @param $comment_post_id
	 *
	 * @return mixed
	 */
	function add_verified_owner_error_on_pre_discussion_comment_on_post( $errors, $comment_post_id ) {
		if (
			'yes' === get_option( 'alg_dtwp_opt_v_owner_restrict', 'no' ) &&
			! wc_customer_bought_product( '', get_current_user_id(), $comment_post_id )
		) {
			$errors['verified_owner'] = __( 'It\'s required to be a verified owner to leave a discussion comment.', 'discussions-tab-for-woocommerce-products' );
		}

		return $errors;
	}

	/**
	 * settings.
	 *
	 * @version 1.5.9
	 * @since   1.5.9
	 */
	function settings( $value, $type = '', $args = array() ) {
		return '';
	}

	/**
	 * add_post_author_and_admin_exceptions_to_possible_errors.
	 *
	 * @version 1.5.8
	 * @since   1.5.8
	 *
	 * @param $errors
	 * @param $comment_post_id
	 *
	 * @return array|mixed
	 */
	function add_post_author_and_admin_exceptions_to_possible_errors( $errors, $comment_post_id ) {
		if (
			(
				'yes' === get_option( 'alg_dtwp_product_authors_post_discussion_comments', 'yes' ) &&
				intval( get_post_field( 'post_author', $comment_post_id ) ) === get_current_user_id()
			) ||
			(
				'yes' === get_option( 'alg_dtwp_administrator_post_discussion_comments', 'yes' ) &&
				current_user_can( 'administrator' )
			)
		) {
			$errors = array();
		}

		return $errors;
	}

	/**
	 * pre_discussion_comment_on_post.
	 *
	 * Fires before a discussion comment is posted.
	 *
	 * @version 1.5.8
	 * @since   1.5.8
	 *
	 * @return void
	 */
	function pre_discussion_comment_on_post() {
		if (
			! isset( $_REQUEST[ alg_wc_pdt_get_comment_type_id() ] ) ||
			! isset( $_REQUEST['comment_post_ID'] )
		) {
			return;
		}
		do_action( 'alg_dtwp_pre_discussion_comment_on_post', intval( $_REQUEST['comment_post_ID'] ) );
	}

	/**
	 * validate_pre_discussion_comment_on_post.
	 *
	 * @version 1.5.8
	 * @since   1.5.8
	 *
	 * @param $comment_post_id
	 *
	 * @return void
	 */
	function validate_pre_discussion_comment_on_post( $comment_post_id ) {
		if ( ! empty( $errors = $this->get_pre_discussion_comment_on_post_errors( $comment_post_id ) ) ) {
			$first_error = reset( $errors );
			wp_die(
				$first_error,
				rtrim( $first_error, '.' ),
				array(
					'code' => 403,
				)
			);
		}
	}

	/**
	 * get_pre_discussion_comment_on_post_errors.
	 *
	 * @version 1.5.8
	 * @since   1.5.8
	 *
	 * @param $comment_post_id
	 *
	 * @return mixed|null
	 */
	function get_pre_discussion_comment_on_post_errors( $comment_post_id ) {
		return apply_filters( 'alg_dtwp_pre_discussion_comment_on_post_errors', array(), $comment_post_id );
	}

	/**
	 * hide_discussion_comments_for_guests.
	 *
	 * @version 1.5.2
	 * @since   1.5.2
	 *
	 * @param $query
	 *
	 * @return void
	 */
	function hide_discussion_comments_for_guests( $query ) {
		if (
			( ! is_admin() || is_ajax() ) &&
			! is_user_logged_in() &&
			isset( $query->query_vars['type'] ) &&
			$query->query_vars['type'] === alg_wc_pdt_get_comment_type_id() &&
			'yes' === get_option( 'alg_dtwp_hide_discussion_comments_from_guests', 'no' )
		) {
			$query->query_vars['post__in'] = array( 0 );
		}
	}

	/**
	 * fix_pagination_link.
	 *
	 * @version 1.4.2
	 * @since   1.4.2
	 *
	 * @param $link
	 *
	 * @return null|string|string[]
	 */
	function fix_pagination_link( $link ) {
		if ( $this->is_discussion_tab ) {
			$tab_link = '#tab-' . $this->get_discussions_tab_id();
			$link     = preg_replace( '/#comments$/', $tab_link, $link );
		}
		return $link;
	}

	/**
	 * handle_discussion_comment_edit_link.
	 *
	 * @version 1.3.6
	 * @since   1.3.6
	 *
	 * @param $link
	 * @param $comment_id
	 *
	 * @return string
	 */
	function handle_discussion_comment_edit_link( $link, $comment_id ) {
		if (
			'yes' === get_option( 'alg_dtwp_edit_comments_link_requires_moderate_comments', 'yes' ) &&
			! empty( $comment = get_comment( $comment_id ) ) &&
			alg_wc_pdt_get_comment_type_id() === $comment->comment_type &&
			! current_user_can( 'moderate_comments' )
		) {
			$link = '';
		}
		return $link;
	}

	/**
	 * fix_comment_edit_redirect_from_frontend.
	 *
	 * @version 1.3.6
	 * @since   1.3.6
	 *
	 * @param $location
	 * @param $comment_id
	 *
	 * @return mixed
	 */
	function fix_comment_edit_redirect_from_frontend( $location, $comment_id ) {
		if (
			! empty( $comment = get_comment( $comment_id ) ) &&
			alg_wc_pdt_get_comment_type_id() === $comment->comment_type &&
			false === strpos( $location, 'edit-comments.php' )
		) {
			$location = get_comment_link( $comment_id );
		}
		return $location;
	}

	/**
	 * get_default_allowed_comment_html.
	 *
	 * @version 1.3.1
	 * @since   1.3.1
	 *
	 * @return array
	 */
	function get_default_allowed_comment_html(){
		return array(
			'a'          => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array( '_blank' )
			),
			'abbr'       => array( 'title' => true ),
			'acronym'    => array( 'title' => true ),
			'b'          => array(),
			'blockquote' => array( 'cite' => true ),
			'cite'       => array(),
			'code'       => array(),
			'pre'        => array(),
			'del'        => array( 'datetime' => true ),
			'em'         => array(),
			'i'          => array(),
			'q'          => array( 'cite' => true ),
			's'          => array(),
			'strike'     => array(),
			'strong'     => array(),
		);
	}

	/**
	 * sanitization_content_valid.
	 *
	 * @version 1.3.1
	 * @since   1.3.1
	 *
	 * @return bool
	 */
	function sanitization_content_valid() {
		$allowed_html = get_option( 'alg_dtwp_opt_custom_sanitization_content', wp_json_encode( $this->get_default_allowed_comment_html() ) );
		$ob           = json_decode( $allowed_html );
		if ( $ob === null ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * filter_and_sanitize_comment.
	 *
	 * @version 1.3.1
	 * @since   1.3.1
	 *
	 * @param $comment_data
	 *
	 * @return mixed
	 */
	function filter_and_sanitize_comment( $comment_data ) {
		if (
			$this->discussion_comment_on_update
			&& 'yes' === get_option( 'alg_dtwp_opt_custom_sanitization', 'no' )
		) {
			$allowed_html = get_option( 'alg_dtwp_opt_custom_sanitization_content', wp_json_encode( $this->get_default_allowed_comment_html() ) );
			$comment_data = wp_kses( $comment_data, json_decode( $allowed_html,true ) );
		}
		return $comment_data;
	}

	/**
	 * detect_plugin_update.
	 *
	 * @version 1.4.8
	 * @since   1.2.9
	 *
	 * @param $upgrader_object
	 * @param $options
	 */
	function detect_plugin_update( $upgrader_object, $options ) {
		$current_plugin_path_name = plugin_basename( alg_wc_products_discussions_tab()->get_filesystem_path() );
		if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
			foreach ( $options['plugins'] as $each_plugin ) {
				if ( $each_plugin == $current_plugin_path_name ) {
					do_action( 'alg_wc_products_discussions_tab_plugin_update' );
				}
			}
		}
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
	function setup_comments_form_position($comment_post_id) {
		$comment_form_position = apply_filters( 'alg_dtwp_opt_comment_form_position', 'alg_dtwp_comments_end' );
		if ( $comment_form_position == current_filter() ) {
			$this->display_comments_form($comment_post_id);
		}
		if ( 'alg_dtwp_comments_start' == $comment_form_position ) {
			echo '<div style="margin-bottom:50px"></div>';
		}
	}

	/**
	 * display_comments_form.
	 *
	 * @version 1.5.8
	 * @since   1.2.4
	 */
	function display_comments_form( $comment_post_id ) {
		$errors     = $this->get_pre_discussion_comment_on_post_errors( $comment_post_id );
		$has_errors = ! empty( $errors );

		$discussions_respond_title        = sanitize_text_field( get_option( 'alg_dtwp_discussions_respond_title', __( 'Leave a reply', 'discussions-tab-for-woocommerce-products' ) ) );
		$discussions_comment_btn_label    = sanitize_text_field( get_option( 'alg_dtwp_discussions_post_comment_label', __( 'Post Comment', 'discussions-tab-for-woocommerce-products' ) ) );
		$discussions_textarea_placeholder = sanitize_text_field( get_option( 'alg_dtwp_discussions_textarea_placeholder', '' ) );
		$comment_form_params              = array(
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
		);

		if ( $has_errors ) {
			$first_error = reset($errors);
			$comment_form_params['comment_notes_after'] = '<p style="color:red;margin-bottom:20px">'.$first_error.'</p>';
		}

		comment_form( $comment_form_params );

		$form_html = ob_get_clean();

		// Disable all input, textarea, and select fields.
		if ( $has_errors ) {
			$form_html = str_replace( '<textarea', '<textarea disabled="disabled"', $form_html );
			$form_html = str_replace( '<input', '<input disabled="disabled"', $form_html );
			$form_html = str_replace( '<select', '<select disabled="disabled"', $form_html );
		}

		echo $form_html;

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
			echo $this->get_comments_template();
		}
	}

	/**
	 * get_comments_template.
	 *
	 * @version 1.3.7
	 * @since   1.2.3
	 *
	 * @return false|string
	 */
	function get_comments_template() {
		$this->is_discussion_tab = true;
		global $post;
		if (
			empty( $post ) ||
			! is_a( wc_get_product( $post ), 'WC_Product' )
		) {
			return '';
		}
		ob_start();
		comments_template();
		$result = ob_get_contents();
		ob_end_clean();
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
	 * @version 1.5.5
	 * @since   1.0.0
	 */
	function load_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? current_time( 'timestamp' ) : alg_wc_products_discussions_tab()->version;

		// Main css file
		wp_enqueue_style( 'alg-dtwp',
			alg_wc_products_discussions_tab()->plugin_url() . '/assets/css/frontend' . $suffix . '.css',
			array(),
			$version
		);
		if ( is_product() ) {
			if ( 'yes' === get_option( 'alg_dtwp_enqueue_comment_reply_on_product', 'no' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			wp_enqueue_script( 'alg-dtwp', alg_wc_products_discussions_tab()->plugin_url() . '/assets/js/frontend' . $suffix . '.js', array(), $version, true );
			wp_localize_script( 'alg-dtwp', 'alg_dtwp', apply_filters( 'alg_dtwp_localize_script', array(
				'ajaxurl'           => admin_url( 'admin-ajax.php' ),
				'postID'            => get_the_ID(),
				'tabID'             => alg_wc_products_discussions_tab()->core->get_discussions_tab_id(),
				'commentLink'       => $this->get_comment_link(),
				'respondID'         => $this->discussions_respond_id_wrapper,
				'respondIDLocation' => $this->discussions_respond_id_location,
				'plugin_url'        => alg_wc_products_discussions_tab()->plugin_url(),
				'commentTypeID'     => alg_wc_pdt_get_comment_type_id(),
				'modulesToLoad'     => apply_filters( 'alg_dtwp_js_modules_to_load', array() )
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
	 * @version 1.3.1
	 * @since   1.0.0
	 *
	 * @param   $comment_data
	 *
	 * @return mixed
	 */
	function add_discussions_comment_type_in_comment_data( $comment_data ) {
		$comment_type_id = alg_wc_pdt_get_comment_type_id();
		if (
			( isset( $_REQUEST[ $comment_type_id ] ) && filter_var( $_REQUEST[ $comment_type_id ], FILTER_VALIDATE_BOOLEAN ) ) ||
			( ! isset( $_REQUEST[ $comment_type_id ] ) && ! empty( $comment_data['comment_parent'] ) && get_comment_type( $comment_data['comment_parent'] ) == $comment_type_id )
		) {
			$this->discussion_comment_on_update = true;
			if ( 'yes' === get_option( 'alg_dtwp_opt_custom_sanitization', 'no' ) ) {
				remove_filter( 'pre_comment_content', 'wp_filter_post_kses' );
				remove_filter( 'pre_comment_content', 'wp_filter_kses' );
			}
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
	 * @version 1.5.6
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
		$template_path = 'discussions-tab-for-woocommerce-products';
		$check_dirs    = array(
			trailingslashit( get_stylesheet_directory() ) . $template_path,
			trailingslashit( get_template_directory() ) . $template_path,
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
	 * @param   \WP_Comment $comment
	 * @param   $args
	 * @param   $cpage
	 * @return  mixed
	 */
	function change_comment_link( $link, \WP_Comment $comment, $args, $cpage ) {
		if ( $comment->comment_type != alg_wc_pdt_get_comment_type_id() ) {
			return $link;
		}
		return str_replace( '#comment-', '#' . $this->get_comment_link() . '-', $link );
	}

}

endif;
