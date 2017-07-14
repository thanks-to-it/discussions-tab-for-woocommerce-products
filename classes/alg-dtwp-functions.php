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
				$plugin   = Alg_DTWP_Core::get_instance();
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
	}
}