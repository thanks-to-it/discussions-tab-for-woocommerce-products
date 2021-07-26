<?php
/**
 * Discussions Tab for WooCommerce Products - Admin Class
 *
 * @version 1.3.0
 * @since   1.1.0
 * @author  Thanks to IT
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Admin' ) ) :

class Admin {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {

		// Admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );

		// Core
		if ( 'yes' === get_option( 'alg_dtwp_opt_enable', 'yes' ) ) {

			// Add discussion comments meta box
			add_action( 'add_meta_boxes',                array( $this, 'add_comments_cmb' ) );

			// Setups comments columns in admin
			add_filter( 'manage_edit-comments_columns',  array( $this, 'add_comment_type_column' ) );
			add_filter( 'manage_comments_custom_column', array( $this, 'add_comment_type_content' ), 10, 2 );

			// Adds discussions comment type in admin comment types dropdown
			add_filter( 'admin_comment_types_dropdown',  array( $this, 'add_discussions_in_admin_comment_types_dropdown' ) );

			// Conversions
			if ( 'yes' === get_option( 'alg_dtwp_admin_conversions_enable', 'yes' ) ) {
				new Conversions();
				//require_once( 'class-alg-wc-products-discussions-tab-conversions.php' );
			}
		}
	}

	/**
	 * Add discussions comment type in admin comment types dropdown.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $types
	 * @return  mixed
	 */
	function add_discussions_in_admin_comment_types_dropdown( $types ) {
		$types[ alg_wc_pdt_get_comment_type_id() ] = __( 'Discussions', 'discussions-tab-for-woocommerce-products' );
		return $types;
	}

	/**
	 * get_comment_column_comment_type.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_comment_column_comment_type() {
		return 'alg_dtwp_comment_type';
	}

	/**
	 * Add comment type column.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $columns
	 * @return  mixed
	 */
	function add_comment_type_column( $columns ) {
		$new = array();
		foreach ( $columns as $key => $title ) {
			$new[ $key ] = $title;
			if ( 'comment' == $key ) {
				$new[ $this->get_comment_column_comment_type() ] = __( 'Comment type', 'discussions-tab-for-woocommerce-products' );
			}
		}
		return $new;
	}

	/**
	 * Adds content to comment type column.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $column
	 * @param   $comment_ID
	 */
	function add_comment_type_content( $column, $comment_ID ) {
		if ( $this->get_comment_column_comment_type() == $column ) {
			$discussion_type_id = alg_wc_pdt_get_comment_type_id();
			$comment_type       = get_comment_type( $comment_ID );
			$comment_type_label = '';
			switch ( $comment_type ) {
				case 'comment':
					$comment_type_label = __( 'Comment', 'discussions-tab-for-woocommerce-products' );
				break;
				case $discussion_type_id:
					$comment_type_label = __( 'Discussion', 'discussions-tab-for-woocommerce-products' );
				break;
				default:
					$comment_type_label = ucfirst( $comment_type );
				break;
			}
			echo apply_filters( 'alg_dtwp_comment_type_column_label', $comment_type_label );
		}
	}

	/**
	 * Adds a discussions metabox in product edit page.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function add_comments_cmb() {
		add_meta_box( 'alg-dtwp-comments-cmb',
			__( 'Discussions', 'discussions-tab-for-woocommerce-products' ), array( $this, 'cmb_callback' ), 'product', 'normal', 'default' );
	}

	/**
	 * Function to display the custom meta box.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $post
	 */
	function cmb_callback( $post ) {

		$discussions_link = add_query_arg( array(
			'comment_type' => alg_wc_pdt_get_comment_type_id(),
			'p'            => $post->ID,
		), admin_url( 'edit-comments.php' ) );

		$comments_count   = get_comments( array(
			'post_id' => $post->ID,
			'status'  => 'approve',
			'count'   => true,
			'type'    => alg_wc_pdt_get_comment_type_id(),
		) );

		echo '<p><a href="' . $discussions_link . '">' . sprintf( __( 'See discussions (%s)', 'discussions-tab-for-woocommerce-products' ), $comments_count ) . '</a></p>';
	}

	/**
	 * Enqueues admin main scripts.
	 *
	 * @version 1.3.0
	 * @since   1.0.5
	 */
	function load_admin_scripts() {
		if (
			isset( $_GET['page'] )    && 'wc-settings'                     === $_GET['page'] &&
			isset( $_GET['tab'] )     && 'alg_wc_products_discussions_tab' === $_GET['tab'] &&
			isset( $_GET['section'] ) && 'labels'                          === $_GET['section']
		) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? current_time( 'timestamp' ) : alg_wc_products_discussions_tab()->version;
			// Font awesome iconpicker
			wp_enqueue_style( 'alg-dtwp-fa-iconpicker',
				alg_wc_products_discussions_tab()->plugin_url() . '/assets/vendor/fontawesome-iconpicker/css/fontawesome-iconpicker' . $suffix . '.css',
				array(),
				$version
			);
			wp_enqueue_script( 'alg-dtwp-fa-iconpicker',
				alg_wc_products_discussions_tab()->plugin_url() . '/assets/vendor/fontawesome-iconpicker/js/fontawesome-iconpicker' . $suffix . '.js',
				array( 'jquery' ),
				$version,
				true
			);
			// Font awesome
			if ( ! wp_script_is( 'alg-font-awesome' ) ) {
				wp_enqueue_style( 'alg-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css', array() );
			}
			// JS
			wp_enqueue_script( 'alg-dtwp-admin',
				alg_wc_products_discussions_tab()->plugin_url() . '/assets/js/admin' . $suffix . '.js',
				array( 'jquery' ),
				$version,
				true
			);
			// CSS
			wp_enqueue_style( 'alg-dtwp-admin',
				alg_wc_products_discussions_tab()->plugin_url() . '/assets/css/admin' . $suffix . '.css',
				array(),
				$version
			);
		}
	}

}

endif;
