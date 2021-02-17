<?php
/**
 * Discussions Tab for WooCommerce Products - Functions
 *
 * @version 1.1.0
 * @since   1.1.0
 * @author  Algoritmika Ltd
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'alg_wc_pdt_is_discussion_tab' ) ) {
	/**
	 * alg_wc_pdt_is_discussion_tab.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function alg_wc_pdt_is_discussion_tab(){
		return alg_wc_products_discussions_tab()->core->is_discussion_tab();
	}
}

if ( ! function_exists( 'alg_wc_pdt_get_comment_type_id' ) ) {
	/**
	 * alg_wc_pdt_get_comment_type_id.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function alg_wc_pdt_get_comment_type_id(){
		return 'alg_dtwp_comment';
	}
}
