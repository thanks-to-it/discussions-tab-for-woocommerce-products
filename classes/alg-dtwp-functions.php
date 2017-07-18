<?php
/**
 * Discussions tab for WooCommerce Products - General Functions
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_Functions' ) ) {
	class Alg_DTWP_Functions {

		/**
		 * Override woocommerce locate template
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $template
		 * @param $template_name
		 * @param $template_path
		 *
		 * @return string
		 */
		public function woocommerce_locate_template( $template, $template_name, $template_path ) {
			if ( strpos( $template_name, 'dtwp-' ) !== false ) {

				$template_path = 'woocommerce';
				$plugin   = alg_dtwp_get_instance();
				$default_path  = $plugin->get_plugin_dir() . 'templates' . DIRECTORY_SEPARATOR;
				$template      = locate_template(
					array(
						trailingslashit( $template_path ) . $template_name,
						$template_name,
					)
				);

				// Get default template/
				if ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
					$template = $default_path . $template_name;
				}
			}
			return $template;
		}

		/**
		 * Enqueues main scripts
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function load_main_scripts(){
			$plugin = alg_dtwp_get_instance();
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Main js file
			/*$js_file = 'assets/dist/frontend/js/alg-dtwp' . $suffix . '.js';
			$js_ver  = date( "ymd-Gis", filemtime( ALG_WC_CIVS_DIR . $js_file ) );
			wp_register_script( 'alg-dtwp', ALG_WC_CIVS_URL . $js_file, array( 'jquery' ), $js_ver, true );
			wp_enqueue_script( 'alg-dtwp' );*/

			// Main css file
			$css_file = 'assets/dist/frontend/css/alg-dtwp' . $suffix . '.css';
			$css_ver  = date( "ymd-Gis", filemtime( $plugin->get_plugin_dir() . $css_file ) );
			wp_register_style( 'alg-dtwp', $plugin->get_plugin_dir_url() . $css_file, array(), $css_ver );
			wp_enqueue_style( 'alg-dtwp' );
		}
	}
}