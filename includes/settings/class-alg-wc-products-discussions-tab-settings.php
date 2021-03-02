<?php
/**
 * Discussions Tab for WooCommerce Products - Settings
 *
 * @version 1.2.6
 * @since   1.1.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Products_Discussions_Tab_Settings' ) ) :

class Alg_WC_Products_Discussions_Tab_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.2.6
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id    = 'alg_wc_products_discussions_tab';
		$this->label = __( 'Discussions', 'discussions-tab-for-woocommerce-products' );
		parent::__construct();
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'maybe_unsanitize_option' ), PHP_INT_MAX, 3 );
		add_action( 'woocommerce_admin_field_' . 'alg_wc_pdtmb',  array( $this, 'add_alg_wc_pdtmb_meta_box' ), 10, 2 );
		add_action( 'admin_notices', array( $this, 'add_plugins_page_notices' ), 999 );
	}

	/**
	 * add_plugins_page_notices.
	 *
	 * @version 1.2.6
	 * @since   1.2.6
	 */
	function add_plugins_page_notices() {
		if (
			! isset( $_GET['page'] )
			|| 'wc-settings' !== $_GET['page']
			|| ! isset( $_GET['tab'] )
			|| 'alg_wc_products_discussions_tab' !== $_GET['tab']
			|| 'discussions-tab-for-woocommerce-products-pro.php' === basename( alg_wc_products_discussions_tab()->get_filename_path() )
		) {
			return;
		}
		$class    = 'notice notice-info';
		$title    = __( 'Pro version', 'discussions-tab-for-woocommerce-products' );
		$pro_link = 'https://wpfactory.com/item/discussions-tab-for-woocommerce-products/';
		$message  = sprintf( __( 'Disabled options can be unlocked using the <a href="%s">pro version</a>', 'discussions-tab-for-woocommerce-products' ), $pro_link ) .
		            '<p><a style="margin-top:11px" target="_blank" class="button-primary alg-dtwp-call-to-action" href="' . $pro_link . '"><span style="vertical-align:middle;position:relative;top:2px;left:-2px;" class="dashicons-before dashicons-unlock"></span>' . __( 'Upgrade to Pro', 'discussions-tab-for-woocommerce-products' ) . '</a></p>';
		printf( '<div class="%1$s"><h3 class="title">%2$s</h3><p>%3$s</p></div>', esc_attr( $class ), $title, $message );
	}

	/**
	 * Creates meta box.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function add_alg_wc_pdtmb_meta_box( $value ) {
		// Doesn't show metabox if enable = false
		if ( ( isset( $value['enabled'] ) && false == $value['enabled'] ) || ( isset( $value['enable'] ) && false == $value['enable'] ) ) {
			return;
		}

		$option_description    = isset( $value['description'] ) ? '<p>' . $value['description'] : '' . '</p>';
		$option_accordion      = $this->get_accordion( $value );
		$option_call_to_action = $this->get_call_to_action( $value );
		$option_accordion_str  = ! empty( $option_accordion ) ? $option_accordion : '';
		$option_title          = $value['title'];
		$option_id             = esc_attr( $value['id'] );

		echo '
			<tr><th scope="row" class="titledesc">' . $option_title . '</th><td>
			<div id="poststuff">
				<div id="' . $option_id . '" class="postbox">
					<div class="inside">
						' . $option_description . $option_accordion_str . $option_call_to_action. '
					</div>
				</div>
			</div></td></tr>
		';

		$style = $this->get_inline_style();
		$js    = $this->get_inline_js();
		echo $style . $js;
	}

	/**
	 * Gets the html for the accordion.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_accordion( $value ) {
		$accordion = isset( $value['accordion'] ) ? $value['accordion'] : false;
		if ( ! $accordion ) {
			return '';
		}

		$items = ! empty( $accordion['items'] ) ? $accordion['items'] : false;
		if ( ! $items ) {
			return '';
		}

		$title = ! empty( $accordion['title'] ) ? $accordion['title'] : '';

		$final_items = " <ul class='alg_wc_pdtmb-admin-accordion' > ";
		foreach ( $items as $item ) {
			$li_class = 'item';
			if ( ! empty( $item['hidden_content'] ) ) {
				$li_class    .= ' accordion-item';
				$trigger     = ! empty( $item['trigger'] ) ? '<span class="trigger">' . esc_html( $item['trigger'] ) . '</span>' : '';
				$final_items .= " <li class='" . esc_attr( $li_class ) . "' >{$trigger}<div class='details-container' > " . esc_html( $item['hidden_content'] ) . " </div></li> ";
			} else {
				$trigger     = ! empty( $item['trigger'] ) ? '<span class="trigger">' . esc_html( $item['trigger'] ) . '</span>' : '';
				$img         = ! empty( $item['img_src'] ) ? '<div class="img-container"><img src="' . esc_attr( $item['img_src'] ) . '"></div>' : '';
				$description = ! empty( $item['description'] ) ? '<div class="desc_container">' . $item['description'] . '</div>' : '';
				if ( ! empty( $img ) || ! empty( $description ) ) {
					$li_class .= ' accordion-item';
				}
				$final_items .= " <li class='" . esc_attr( $li_class ) . "' >{$trigger}<div class='details-container' >{$description}{$img}</div ></li> ";
			}
		}
		$final_items .= '</ul>';

		return "
			<div class='alg_wc_pdtmb-admin-accordion-title' >
				<strong>{$title}</strong >
			</div>
			{$final_items}
		";
	}

	/**
	 * Gets the button.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_call_to_action( $value ) {
		$call_to_action = isset( $value['call_to_action'] ) ? $value['call_to_action'] : false;
		if ( ! $call_to_action ) {
			return '';
		}

		$args = wp_parse_args( $call_to_action, array(
			'href'   => '',
			'label'  => __( 'Check it', 'discussions-tab-for-woocommerce-products' ),
			'href'   => '',
			'target' => '_blank',
			'class'  => 'button-primary',
		) );

		if ( empty( $args['href'] ) ) {
			return '';
		}

		return sprintf( "<a target='%s' class='%s alg_wc_pdtmb-call-to-action' href='%s'>%s</a>",
			esc_attr( $args['target'] ), esc_attr( $args['class'] ), esc_url( $args['href'] ), esc_html( $args['label'] ) );
	}

	/**
	 * Gets the style for the metabox.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_inline_style() {
		$style = '
			<style>
				.alg_wc_pdtmb-admin-accordion .details-container{
					display:none;
					margin-top:10px;
					margin-bottom:15px;
					background:#f9f9f9;
					padding:13px;
				}
				.alg_wc_pdtmb-admin-accordion .desc_container{
					color:#999;
				}
				.alg_wc_pdtmb-admin-accordion .accordion-item .trigger{
					color:#0073aa;
					cursor:pointer;
				}
				.alg_wc_pdtmb-admin-accordion .img-container img{
					border:4px solid #ddd;
					margin-top:10px;
					max-width:100%;
				}
				.alg_wc_pdtmb-admin-accordion .accordion-item .trigger:hover{
				text-decoration: underline;
				}
				.alg_wc_pdtmb-admin-accordion .item:not(.accordion-item):before{
					width:8px;
					height:8px;
					content:" ";
					display:inline-block;
					background:#000;
					margin-right:8px;
				}
				.alg_wc_pdtmb-admin-accordion .accordion-item:before{
					content:" ";
					width: 0;
					height: 0;
					border-left: 5px solid transparent;
					border-right: 5px solid transparent;
					border-top: 9px solid #0073aa;
					display:inline-block;
					margin-right:7px;
					transition:all 0.3s ease-in-out;
					transform: rotate(-90deg);
				}
				.alg_wc_pdtmb-admin-accordion .accordion-item.active:before{
				transform: rotate(0deg);
					transform-origin: 50% 50%;
				}
				.alg_wc_pdtmb-admin-accordion-title{
					margin-top:23px;
				}
				.alg_wc_pdtmb-call-to-action{
					margin:17px 0 15px 0 !important;
				}
			</style>
		';

		return $style;
	}

	/**
	 * Gets the inline js for the metabox.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_inline_js() {
		$js = "
			<script>
				jQuery(document).ready(function($){
					$('.alg_wc_pdtmb-admin-accordion .accordion-item .trigger').on('click',function(){
						if($(this).parent().hasClass('active')){
							$(this).parent().removeClass('active');
							$(this).parent().find('.details-container').slideUp();
						}else{
							$('.alg_wc_pdtmb-admin-accordion .accordion-item .details-container').slideUp();
							$('.alg_wc_pdtmb-admin-accordion .accordion-item').removeClass('active');
							$(this).parent().addClass('active');
							$(this).parent().find('.details-container').slideDown();
						}
					})
				})
			</script>
		";

		return $js;
	}

	/**
	 * maybe_unsanitize_option.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function maybe_unsanitize_option( $value, $option, $raw_value ) {
		return ( ! empty( $option['alg_wc_products_discussions_tab_raw'] ) ? wp_kses_post( trim( $raw_value ) ) : $value );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'discussions-tab-for-woocommerce-products' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'discussions-tab-for-woocommerce-products' ),
				'desc'      => '<strong>' . __( 'Reset', 'discussions-tab-for-woocommerce-products' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'discussions-tab-for-woocommerce-products' ),
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notices_settings_reset_success' ), PHP_INT_MAX );
		}
	}

	/**
	 * admin_notices_settings_reset_success.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function admin_notices_settings_reset_success() {
		echo '<div class="notice notice-success is-dismissible"><p><strong>' .
			__( 'Your settings have been reset.', 'discussions-tab-for-woocommerce-products' ) . '</strong></p></div>';
	}

	/**
	 * save.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}

}

endif;

return new Alg_WC_Products_Discussions_Tab_Settings();
