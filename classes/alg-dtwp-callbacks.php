<?php
/**
 * Discussions tab for WooCommerce Products - Callbacks
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Callbacks' ) ) {

	class Alg_DTWP_Callbacks {

		/**
		 * @var Alg_DTWP_Core
		 */
		public $core;

		/**
		 * @var Alg_DTWP_Registry
		 */
		public $registry;

		/**
		 * Alg_DTWP_Callbacks constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param Alg_DTWP_Core $core
		 */
		function __construct( Alg_DTWP_Core $core ) {
			$this->core     = $core;
			$this->registry = $this->core->registry;
		}

		/**
		 * Filters "plugin_action_link" for the admin_settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $links
		 *
		 * @return array
		 */
		public function admin_plugin_action_links( $links ) {
			return $this->registry->get_admin_settings()->get_action_links( $links );
		}

		/**
		 * Creates settings page
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $settings
		 *
		 * @return array
		 */
		public function admin_wc_get_settings_pages( $settings ) {
			$settings[] = new Alg_DTWP_Admin_Settings_Page();
			return $settings;
		}

		/**
		 * Creates settings sections
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $sections
		 *
		 * @return array
		 */
		public function admin_wc_get_sections( $sections ) {
			return $this->registry->get_admin_settings()->get_sections( $sections );
		}

		/**
		 * Creates initial general settings (enable|disable plugin)
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $settings
		 *
		 * @return array
		 */
		public function admin_wc_get_settings_general( $settings ) {
			return $this->registry->get_admin_settings()->create_main_general_settings( $settings );
		}

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
		public function discussions_wc_product_tabs( $tabs ) {
			return $this->registry->get_discussions_tab()->add_discussions_tab( $tabs );
		}

		/**
		 * Filters comments
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $comments_flat
		 * @param $post_id
		 */
		public function discussions_comments_array( $comments_flat, $post_id ) {
			return $this->registry->get_discussions()->filter_discussions_comments( $comments_flat, $post_id );
		}

		/**
		 * Adds discussions comment type in comment form
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function discussions_comment_form() {
			$this->registry->get_discussions()->add_discussions_comment_type_in_form();
		}

		/**
		 * Adds discussions comment type in comment data
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $comment_data
		 *
		 * @return mixed
		 */
		public function discussions_preprocess_comment( $comment_data ) {
			return $this->registry->get_discussions()->add_discussions_comment_type_in_comment_data( $comment_data );
		}

		/**
		 * Hides discussions comments on default callings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		/*public function discussions_pre_get_comments( \WP_Comment_Query $query ) {
			$this->registry->get_discussions()->hide_discussion_comments_on_default_callings( $query );
		}*/

		/**
		 * Adds dicussions comment type to wp_list_comments
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $query
		 */
		/*public function discussions_wc_product_review_list_args( $args ) {
			return $this->registry->get_discussions()->add_discussions_comment_type_to_wp_list_comments( $args );
		}*/

	}
}