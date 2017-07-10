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

if ( ! class_exists( 'Alg_DTWP_Singleton' ) ) {

	class Alg_DTWP_Singleton {
		/*--------------------------------------------*
     * Attributes
     *--------------------------------------------*/

		/**
		 * @var The single instance of the class
		 * @since 1.0.0
		 */
		protected static $instance = null;

		/*--------------------------------------------*
		 * Constructor
		 *--------------------------------------------*/

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @return static
		 */
		public static function get_instance() {
			if ( ! isset( static::$instance ) ) {
				static::$instance = new static;
			}

			return static::$instance;
		}

		/**
		 * Initializes the plugin by setting localization, filters, and administration functions.
		 */
		private function __construct() {

		} // end constructor

		/*--------------------------------------------*
		 * Functions
		 *--------------------------------------------*/
	}
}