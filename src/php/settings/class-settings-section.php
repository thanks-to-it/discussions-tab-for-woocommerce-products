<?php
/**
 * Discussions Tab for WooCommerce Products - Section Settings
 *
 * @version 1.5.2
 * @since   1.1.0
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab\Settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Settings\Settings_Section' ) ) :

class Settings_Section {

	/**
	 * $id.
	 *
	 * @since 1.5.2
	 *
	 * @var
	 */
	public $id;

	/**
	 * $desc.
	 *
	 * @since 1.5.2
	 *
	 * @var
	 */
	public $desc;

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_sections_alg_wc_products_discussions_tab',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_wc_products_discussions_tab_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}


	/**
	 * get_pro_message.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_pro_message() {
		return '<div style="padding:15px;background-color:#ffdddd;">' .
			sprintf( 'You will need <a href="%s" target="_blank">Discussions Tab for WooCommerce Products Pro</a> plugin to use these options.',
				'https://wpfactory.com/item/discussions-tab-for-woocommerce-products/' ) .
		'</div>';
	}

}

endif;
