<?php
/**
 * Discussions tab for WooCommerce Products - Core Class
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Core' ) ) {

	class Alg_DTWP_Core extends Alg_DTWP_WP_Plugin {

		/**
		 * @var Alg_DTWP_Callbacks
		 */
		public $callbacks;

		/**
		 * @var Alg_DTWP_Registry
		 */
		public $registry;

		/**
		 * Initializes the plugin
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function init() {
			parent::init();

			// Get registry
			$this->registry = new Alg_DTWP_Registry();

			// Get callbacks
			$this->callbacks = new Alg_DTWP_Callbacks( $this );

			// Handle admin settings
			$this->handle_admin_settings();

			// Handles localization
			add_action( 'init', array( $this, 'handle_localization' ) );

			// Load the plugin if it's enabled
			if ( filter_var( get_option( $this->registry->get_admin_section_general()->option_enable, true ), FILTER_VALIDATE_BOOLEAN ) ) {

				// Handle general functions
				$this->handle_general_functions();

				// Handle discussions
				$this->handle_discussions();

			}
		}

		/**
		 * Handles general plugin functions
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		private function handle_general_functions() {
			$callbacks = $this->callbacks;

			// Handle template
			add_filter( 'woocommerce_locate_template', array( $callbacks, 'functions_woocommerce_locate_template' ), 10, 3 );
			add_filter( 'woocommerce_locate_core_template', array( $callbacks, 'functions_woocommerce_locate_template' ), 10, 3 );

			add_filter( 'wp_enqueue_scripts', array( $callbacks, 'functions_load_main_scripts' ) );
		}

		/**
		 * Handles discussions
		 *
		 * @version 1.0.1
		 * @since   1.0.0
		 */
		private function handle_discussions() {

			$callbacks = $this->callbacks;

			// Adds discussion tab in product page
			add_filter( 'woocommerce_product_tabs', array( $callbacks, 'discussions_add_discussions_tab' ) );

			// Inserts comments as discussion comment type in database
			add_action( 'comment_form_top', array( $callbacks, 'discussions_comment_form' ) );
			add_filter( 'preprocess_comment', array( $callbacks, 'discussions_preprocess_comment' ) );

			// Hides discussion comments on improper places
			add_action( 'pre_get_comments', array( $callbacks, 'discussions_pre_get_comments' ) );

			// Loads discussion comments
			add_filter( 'comments_template_query_args', array( $callbacks, 'discussions_filter_comments_template_query_args' ) );

			// Swaps woocommerce template (single-product-reviews.php) with default comments template
			add_filter( 'comments_template', array( $callbacks, 'discussions_comments_template_loader' ) );

			// Fixes comment parent_id and cancel btn
			add_action( 'alg_dtwp_after_comments_template', array( $callbacks, 'discussions_js_fix_comment_parent_id_and_cancel_btn' ) );

			// Opens discussions tab after a discussion comment is posted
			add_action( 'alg_dtwp_after_comments_template', array( $callbacks, 'discussions_js_open_discussions_tab' ) );

			// Tags the respond form so it can have it's ID changed
			add_action( 'comment_form_before', array( $callbacks, 'discussions_create_respond_form_wrapper_start' ) );
			add_action( 'comment_form_after', array( $callbacks, 'discussions_create_respond_form_wrapper_end' ) );

			// Change reply link respond id
			add_filter( 'comment_reply_link_args', array( $callbacks, 'discussions_change_reply_link_respond_id' ) );

			// Fixes comments count
			add_filter( 'get_comments_number', array( $callbacks, 'discussions_fix_comments_number' ), 10, 2 );
			add_filter( 'woocommerce_product_get_review_count', array( $callbacks, 'discussions_fix_reviews_number' ), 10, 2 );

			// Adds discussions comment type in admin comment types dropdown
			add_filter( 'admin_comment_types_dropdown', array( $callbacks, 'discussions_admin_comment_types_dropdown' ) );

			// Add discussion comments meta box
			add_action( 'add_meta_boxes', array( $callbacks, 'discussions_add_comments_cmb' ) );

			// Get avatar
			add_filter( 'pre_get_avatar', array( $callbacks, 'discussions_get_avatar' ), 10, 3 );

			// Filters params passed to wp_list_comments function
			add_filter( 'wp_list_comments_args', array( $callbacks, 'discussions_filter_wp_list_comments_args' ) );

			// Filters the class of wp_list_comments wrapper
			add_filter( 'alg_dtwp_wp_list_comments_wrapper_class', array( $callbacks, 'discussions_filter_wp_list_comments_wrapper_class' ) );

			// Filters the comment class
			add_filter( 'comment_class', array( $callbacks, 'discussions_filter_comment_class' ) );

			// Fixes Hub theme get_comment_type()
			add_filter('get_comment_type', array($callbacks, 'discussions_fix_hub_get_comment_type') );

			// Changes comment link to "#discussion-"
			add_filter( 'get_comment_link', array( $callbacks, 'discussions_change_comment_link' ), 10, 4 );
		}

		/**
		 * Creates admin settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		private function handle_admin_settings() {
			$callbacks = $this->callbacks;

			// Plugin settings link
			add_filter( 'plugin_action_links_' . $this->get_plugin_basename(), array( $callbacks, 'admin_plugin_action_links' ) );

			// Creates settings pages
			add_filter( 'woocommerce_get_settings_pages', array( $callbacks, 'admin_wc_get_settings_pages' ) );

			// Creates admin sections
			add_action( 'admin_init', array( $callbacks, 'admin_create_sections' ) );
		}
	}
}