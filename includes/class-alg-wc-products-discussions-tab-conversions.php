<?php
/**
 * Discussions Tab for WooCommerce Products - Discussions Conversion Manager
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_Conversions' ) ) :

class Alg_WC_Products_Discussions_Tab_Conversions {

	/**
	 * comment_action_convert.
	 */
	public $comment_action_convert = 'alg_dtwp_convert';

	/**
	 * bulk_comments_converted.
	 */
	public $bulk_comments_converted = 0;

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {

		// Setups comments actions in admin
		add_filter( 'comment_row_actions',                 array( $this, 'add_action_convert' ), 10, 2 );
		add_action( 'init',                                array( $this, 'convert_comment' ) );

		// Setups bulk actions for comments
		$bulk_edit_id = 'edit-comments';
		add_filter( "bulk_actions-{$bulk_edit_id}",        array( $this, 'add_comments_bulk_actions' ) );
		add_filter( "handle_bulk_actions-{$bulk_edit_id}", array( $this, 'handle_comments_bulk_actions' ), 10, 3 );
		add_action( 'admin_notices',                       array( $this, 'handle_convert_notices' ) );
	}

	/**
	 * Converts comment type.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   array $args
	 */
	function convert_comment( $args = array() ) {
		if ( ! is_admin() ) {
			return false;
		}
		$args = wp_parse_args( $args, array(
			'action'     => isset( $_GET['alg_dtwp_action'] ) ? $_GET['alg_dtwp_action'] : null,
			'comment_id' => isset( $_GET['alg_dtwp_c'] )      ? $_GET['alg_dtwp_c']      : null,
			'nonce'      => isset( $_GET['convert_nonce'] )   ? $_GET['convert_nonce']   : null,
		) );

		$action     = sanitize_text_field( $args['action'] );
		$comment_id = filter_var( $args['comment_id'], FILTER_VALIDATE_INT );
		if ( 'convert_to_discussion' == $action ) {
			$comment_type = alg_wc_pdt_get_comment_type_id();
		} elseif ( 'convert_to_comment' == $action ) {
			$comment_type = '';
		} else {
			return false;
		}
		if ( ! wp_verify_nonce( $args['nonce'], 'convert_comment-' . $comment_id ) ) {
			return false;
		}
		wp_update_comment( array(
			'comment_ID'   => $comment_id,
			'comment_type' => $comment_type,
		) );
		$this->bulk_comments_converted = 1;
	}

	/**
	 * Handle notices for comments conversion.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   array $args
	 */
	function handle_convert_notices( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'bulk_comments_converted' => isset( $_REQUEST['bulk_comments_converted'] ) ? $_REQUEST['bulk_comments_converted'] : 0
		) );
		$args['bulk_comments_converted'] = $args['bulk_comments_converted'] == 0 ? $this->bulk_comments_converted : $args['bulk_comments_converted'];
		$comments_count = intval( $args['bulk_comments_converted'] );
		if ( $comments_count < 1 ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php printf( _n( 'Converted %s comment successfully.', 'Converted %s comments successfully.',
				$comments_count, 'discussions-tab-for-woocommerce-products' ), $comments_count ); ?></p>
		</div>
		<?php
	}

	/**
	 * Adds convert action.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $actions
	 * @param   $comment
	 * @return  mixed
	 */
	function add_action_convert( $actions, $comment ) {
		$parts = parse_url( home_url() );
		if ( $comment->comment_type != alg_wc_pdt_get_comment_type_id() ) {
			$uri = "{$parts['scheme']}://{$parts['host']}" . add_query_arg( null, array(
					'alg_dtwp_action' => 'convert_to_discussion',
					'alg_dtwp_c'      => $comment->comment_ID,
					'convert_nonce'   => wp_create_nonce( 'convert_comment-' . $comment->comment_ID ),
				) );
			$convert_title = __( 'Convert to Discussion', 'discussions-tab-for-woocommerce-products' );
			$actions[$this->comment_action_convert] = "<a href='{$uri}' title='{$convert_title}' aria-label='{$convert_title}'>" . $convert_title . '</a>';
		} else {
			$uri = "{$parts['scheme']}://{$parts['host']}" . add_query_arg( null, array(
					'alg_dtwp_action' => 'convert_to_comment',
					'alg_dtwp_c'      => $comment->comment_ID,
					'convert_nonce'   => wp_create_nonce( 'convert_comment-' . $comment->comment_ID ),
				) );
			$convert_title = __( 'Convert to Comment', 'discussions-tab-for-woocommerce-products' );
			if ( 'product' == get_post_type( $comment->comment_post_ID ) ) {
				$convert_title = __( 'Convert to Review', 'discussions-tab-for-woocommerce-products' );
			}
			$actions[ $this->comment_action_convert ] = "<a href='{$uri}' title='{$convert_title}' aria-label='{$convert_title}'>" . $convert_title . '</a>';
		}
		return $actions;
	}

	/**
	 * Adds convert bulk actions to comments.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $bulk_actions
	 * @return  mixed
	 */
	function add_comments_bulk_actions( $bulk_actions ) {
		$bulk_actions['convert_to_discussion'] = __( 'Convert to Discussion', 'discussions-tab-for-woocommerce-products' );
		$bulk_actions['convert_to_comment']    = __( 'Convert to Review', 'discussions-tab-for-woocommerce-products' );
		return $bulk_actions;
	}

	/**
	 * Handle custom bulk actions for comments.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   $redirect_to
	 * @param   $action_name
	 * @param   $post_ids
	 * @return  mixed
	 */
	function handle_comments_bulk_actions( $redirect_to, $action_name, $post_ids ) {
		if ( 'convert_to_comment' != $action_name && 'convert_to_discussion' != $action_name ) {
			return $redirect_to;
		}
		foreach ( $post_ids as $post_id ) {
			$this->convert_comment( array(
				'comment_id' => $post_id,
				'action'     => $action_name,
				'nonce'      => wp_create_nonce( 'convert_comment-' . $post_id ),
			) );
		}
		$redirect_to = add_query_arg( 'bulk_comments_converted', count( $post_ids ), $redirect_to );
		return $redirect_to;
	}
}

endif;

return new Alg_WC_Products_Discussions_Tab_Conversions();
