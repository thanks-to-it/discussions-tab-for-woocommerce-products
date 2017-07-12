<?php
/**
 * Discussions tab for WooCommerce Products - Discussions tab
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Discussions_Tab' ) ) {

	class Alg_DTWP_Discussions_Tab {

		public static $discussions_tab_id = 'alg_dtwp_product_tab';
		private $is_discussion_tab = false;

		/**
		 * Adds discussions tab
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tabs
		 *
		 * @return mixed
		 */
		public function add_discussions_tab( $tabs ) {
			$tabs[ self::$discussions_tab_id ] = array(
				'title'    => __( 'Discussions', 'discussions-tab-for-woocommerce-products' ),
				'priority' => 50,
				'callback' => array( $this, 'add_discussions_tab_content' )
			);
			return $tabs;
		}

		/**
		 * Adds discussions comments
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 */
		function add_discussions_tab_content() {
			$this->is_discussion_tab = true;
			comments_template();
			do_action('alg_dtwp_after_comments_template');
			$this->is_discussion_tab = false;
		}

		/**
		 * Check if is displaying discussion tab
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return bool
		 *
		 */
		public function is_discussion_tab() {
			return $this->is_discussion_tab;
		}


	}
}