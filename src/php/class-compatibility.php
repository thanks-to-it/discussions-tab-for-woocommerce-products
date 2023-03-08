<?php
/**
 * Discussions Tab for WooCommerce Products - Compatibility Class
 *
 * @version 1.4.0
 * @since   1.1.0
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Compatibility' ) ) :

class Compatibility {

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function __construct() {
		add_filter( 'get_comment_type',               array( $this, 'fix_hub_get_comment_type' ) );
		add_filter( 'alg_dtwp_wp_list_comments_args', array( $this, 'filter_wp_list_comments_args' ) );
		$wc_compatibility = new WC_Compatibility();
		$wc_compatibility->init();
	}

	/**
	 * Filters params passed to `wp_list_comments()` function.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $args
	 * @return  mixed
	 * @todo    [dev] (check) `wf_get_version()`: "woo_framework_version"?
	 */
	function filter_wp_list_comments_args( $args ) {
		if ( class_exists( 'Storefront' ) ) {
			$args['style']      = 'ol';
			$args['short_ping'] = true;
			$args['callback']   = 'storefront_comment';
		} elseif ( function_exists( 'wf_get_version' ) ) {
			$args['avatar_size'] = 50;
			$args['callback']    = 'custom_comment';
		}

		$theme_name = wp_get_theme()->get( 'Name' );
		switch ( $theme_name ) {
			case 'Enfold':
				$args['callback'] = 'avia_inc_custom_comments';
			break;
			case 'Themify Corporate':
				$args['callback'] = 'themify_theme_comment';
			break;
		}

		return $args;
	}

	/**
	 * Fixes Hub theme `get_comment_type()`.
	 *
	 * @version 1.1.0
	 * @since   1.0.1
	 * @param   $type
	 * @return  string
	 */
	function fix_hub_get_comment_type( $type ) {
		$theme_name = wp_get_theme()->get( 'Name' );
		if ( 'Hub' != $theme_name || ! alg_wc_pdt_is_discussion_tab() ) {
			return $type;
		}
		$type = 'comment';
		return $type;
	}

}

endif;