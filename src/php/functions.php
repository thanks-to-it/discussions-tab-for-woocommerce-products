<?php
/**
 * Discussions Tab for WooCommerce Products - Functions
 *
 * @version 1.3.7
 * @since   1.1.0
 * @author  WPFactory
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

if ( ! function_exists( 'alg_dtwp_get_transient' ) ) {
	/**
	 * alg_dtwp_get_transient.
	 *
	 * @version 1.3.7
	 * @since   1.3.7
	 *
	 * @param null $args
	 *
	 * @return mixed
	 */
	function alg_dtwp_get_transient( $args = null ) {
		$args  = wp_parse_args( $args, array(
			'transient'               => '',
			'use_decompression'       => false,
			'decompression_function'  => 'gzinflate', // gzinflate | gzuncompress | gzdecode
			'decompression_max_level' => 0
		) );
		$value = get_transient( $args['transient'] );
		if (
			$args['use_decompression'] &&
			function_exists( $args['decompression_function'] )
		) {
			$value = call_user_func_array( $args['decompression_function'], array( base64_decode( $value ), $args['decompression_max_level'] ) );
		}
		return $value;
	}
}

if ( ! function_exists( 'alg_dtwp_set_transient' ) ) {
	/**
	 * alg_dtwp_set_transient.
	 *
	 * @version 1.3.7
	 * @since   1.3.7
	 *
	 * @param null $args
	 *
	 * @return bool
	 */
	function alg_dtwp_set_transient( $args = null ) {
		$args = wp_parse_args( $args, array(
			'transient'            => '',
			'value'                => '',
			'expiration'           => 0,
			'use_compression'      => false,
			'compression_function' => 'gzdeflate', // gzdeflate | gzcompress | gzencode
			'compression_level'    => 9
		) );
		if (
			$args['use_compression'] &&
			function_exists( $args['compression_function'] )
		) {
			$args['value'] = base64_encode( call_user_func_array( $args['compression_function'], array( $args['value'], $args['compression_level'] ) ) );
		}
		return set_transient( $args['transient'], $args['value'], $args['expiration'] );
	}
}