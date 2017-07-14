<?php
/**
 * Discussions tab for WooCommerce Products - Discussions comments Custom Meta Box
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Discussions_CMB' ) ) {

	class Alg_DTWP_Discussions_Comments_CMB {
		public function add_comments_cmb(){
			add_meta_box( 'alg-dtwp-comments-cmb', 'Discussions', array($this,'cmb_callback'), 'product', 'normal', 'default' );
		}

		public function cmb_callback($post){


			//$total = get_comments( array( 'post_id' => $post->ID, 'number' => 1, 'count' => true ) );
			//$wp_list_table = _get_list_table('WP_Post_Comments_List_Table');
			//$wp_list_table->display( true );


		}
	}
}