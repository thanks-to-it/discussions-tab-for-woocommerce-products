<?php
/**
 * Discussions tab for WooCommerce Products - Discussions tab
 *
 * @version 1.0.2
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Discussions_Tab' ) ) {

	class Alg_DTWP_Discussions_Tab {

		public static $discussions_tab_id = 'alg_dtwp';
		private $is_discussion_tab = false;

		/**
		 * Adds discussions tab
		 *
		 * @version 1.0.2
		 * @since   1.0.0
		 *
		 * @param $tabs
		 *
		 * @return mixed
		 */
		public function add_discussions_tab( $tabs ) {
			$plugin = alg_dtwp_get_instance();
			global $post;

			$comments = get_comments( array(
				'post_id' => $post->ID,
				'status'  => 'approve',
				'count'   => true,
				'type'    => Alg_DTWP_Discussions::$comment_type_id
			) );

			$discussions_label                 = get_option( $plugin->registry->get_admin_section_texts()->option_discussions_label, __( 'Discussions', 'discussions-tab-for-woocommerce-products' ) );
			$tabs[ self::$discussions_tab_id ] = array(
				'title'    => sanitize_text_field( $discussions_label )." ({$comments})",
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