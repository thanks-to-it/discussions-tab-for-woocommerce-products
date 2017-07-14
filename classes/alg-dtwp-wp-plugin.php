<?php
/**
 * Discussions tab for WooCommerce Products - Singleton
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_DTWP_WP_Plugin' ) ) {
	class Alg_DTWP_WP_Plugin extends Alg_DTWP_Singleton {

		public $basename = '';
		public $dir_url;
		public $dir;
		public $config_args = array();

		/**
		 * Gets plugin basename
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function get_plugin_dir() {
			$config_args = $this->config_args;

			if ( ! $this->dir ) {
				$this->dir = untrailingslashit( plugin_dir_path( $config_args['file'] ) ) . DIRECTORY_SEPARATOR;
			}

			return $this->dir;
		}

		/**
		 * Handles plugin localization
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function handle_localization() {
			$args        = $this->config_args;
			$text_domain = sanitize_text_field( $args['text_domain'] );
			$locale      = apply_filters( 'plugin_locale', get_locale(), $text_domain );
			load_textdomain( $text_domain,WP_LANG_DIR . DIRECTORY_SEPARATOR. dirname( $this->get_plugin_basename() ) .DIRECTORY_SEPARATOR. $text_domain . '-' . $locale . '.mo');
			load_plugin_textdomain( $text_domain, false, dirname( $this->get_plugin_basename() ) . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR );
		}

		/**
		 * Gets plugin basename
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function get_plugin_dir_url() {
			$config_args = $this->config_args;

			if ( ! $this->dir_url ) {
				$this->dir_url = plugin_dir_url( $config_args['file'] );
			}

			return $this->dir_url;
		}

		/**
		 * Gets plugin basename
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function get_plugin_basename() {
			$config_args = $this->config_args;

			if ( ! $this->basename ) {
				$this->basename = plugin_basename( $config_args['file'] );
			}

			return $this->basename;
		}

		/**
		 * Initializes the plugin
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function init() {

		}

		/**
		 * Setups the plugin
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function config( $args = array() ) {
			$this->config_args = $args;
		}
	}
}