<?php
/**
 * Discussions tab for WooCommerce Products - Discussions Custom Meta Box
 *
 * @version 1.0.2
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Discussions_CMB' ) ) {

	class Alg_DTWP_Discussions_Comments_CMB {

		/**
		 * Adds a discussions metabox in product edit page
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_comments_cmb() {
			add_meta_box( 'alg-dtwp-comments-cmb', 'Discussions', array( $this, 'cmb_callback' ), 'product', 'normal', 'default' );
		}

		/**
		 * Function to display the custom meta box
		 *
		 * @version 1.0.2
		 * @since   1.0.0
		 *
		 * @param $post
		 */
		public function cmb_callback( $post ) {
			$plugin = alg_dtwp_get_instance();

			$discussions_link = add_query_arg( array(
				'comment_type' => Alg_DTWP_Discussions::$comment_type_id,
				'p'            => $post->ID
			), admin_url( 'edit-comments.php' ) );

			$comments_count = get_comments( array(
				'post_id' => $post->ID,
				'status'  => 'approve',
				'count'   => true,
				'type'    => Alg_DTWP_Discussions::$comment_type_id
			) );

			echo '<p><a href="' . $discussions_link . '">' . sprintf( __( 'See discussions (%s)', 'discussions-tab-for-woocommerce-products' ), $comments_count ) . '</a></p>';
		}
	}
}