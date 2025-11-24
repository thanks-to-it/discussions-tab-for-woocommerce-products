<?php
/**
 * Discussions Tab for WooCommerce Products - Discussions Labels.
 *
 * @version 1.5.9
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Labels' ) ) :

class Labels {

	/**
	 * $labels_info.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $labels_info = array();

	/**
	 * $checked_ids_for_product_author.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $checked_ids_for_product_author = array();

	/**
	 * $icon_positioning_option.
	 *
	 * @since 1.5.9
	 *
	 * @var
	 */
	protected $icon_positioning_option;

	/**
	 * Constructor.
	 *
	 * @version 1.3.4
	 * @since   1.1.0
	 */
	function __construct() {
		add_filter( 'comment_class', array( $this, 'add_classes_on_comments' ), 10, 5 );
		add_action( 'alg_wc_pdt_load_scripts', array( $this, 'maybe_load_fontawesome' ), 11 );
		add_action( 'alg_wc_pdt_load_scripts', array( $this, 'handle_labels_style' ), 11 );
		add_action( 'alg_wc_pdt_load_scripts', array( $this, 'handle_label_tip_style' ), 11 );
		add_filter( 'comment_text', array( $this, 'create_comment_icons_html' ), 10, 3 );
		add_filter( 'get_comment_author', array( $this, 'create_comment_author_icons' ), 10, 3 );
		// Product author
		add_filter( 'alg_dtwp_comment_tags', array( $this, 'create_product_author_classes' ), 10, 2 );
		add_filter( 'alg_dtwp_labels', array( $this, 'add_product_author_label' ), 10, 3 );
		add_filter( 'alg_dtwp_label_' .'alg-dtwp-item-author'. '_style', array( $this, 'add_product_author_style' ), 11 );
		add_filter( 'alg_dtwp_possible_comment_tags', array( $this, 'add_product_author_possible_tag' ), 20 );
		// Verified owner
		add_filter( 'alg_dtwp_comment_tags', array( $this, 'create_verified_owner_classes' ), 10, 2 );
		add_filter( 'alg_dtwp_labels', array( $this, 'add_verified_owner_label' ), 10, 3 );
		add_filter( 'alg_dtwp_label_' . 'alg-dtwp-verified' . '_style', array( $this, 'add_verified_owner_style' ) );
		add_filter( 'alg_dtwp_possible_comment_tags', array( $this, 'add_verified_owner_possible_tag' ) );
	}

	/**
	 * add_product_author_possible_tag.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @param $tags
	 *
	 * @return array
	 */
	function add_product_author_possible_tag( $tags ) {
		if ( 'yes' === get_option( 'alg_dtwp_opt_author_label', 'no' ) ) {
			$tags[] = 'alg-dtwp-item-author';
		}
		return $tags;
	}

	/**
	 * add_verified_owner_possible_tag.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @param $tags
	 *
	 * @return array
	 */
	function add_verified_owner_possible_tag( $tags ) {
		if ( 'yes' === get_option( 'alg_dtwp_opt_v_owner_label', 'no' ) ) {
			$tags[] = 'alg-dtwp-verified';
		}
		return $tags;
	}

	/**
	 * add_product_author_style.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @param $style
	 *
	 * @return mixed
	 */
	function add_product_author_style( $style ) {
		$style['bkg_color'] = get_option( 'alg_dtwp_opt_author_label_color', '#ec800d' );
		$style['color']     = get_option( 'alg_dtwp_opt_author_txt_color', '#ffffff' );
		$style['title']     = get_option( 'alg_dtwp_opt_author_title', __( 'Product author', 'discussions-tab-for-woocommerce-products' ) );
		return $style;
	}

	/**
	 * add_verified_owner_style.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @param $style
	 *
	 * @return mixed
	 */
	function add_verified_owner_style( $style ) {
		$style['bkg_color'] = get_option( 'alg_dtwp_opt_v_owner_label_color', '#0F834D' );
		$style['color'] = get_option( 'alg_dtwp_opt_v_owner_txt_color', '#ffffff' );
		return $style;
	}

	/**
	 * add_verified_owner_label.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @param $labels
	 * @param $comment
	 * @param $args
	 *
	 * @return array
	 */
	function add_verified_owner_label( $labels, $comment, $args ) {
		if (
			filter_var( get_option( 'alg_dtwp_opt_v_owner_label', 'no' ), FILTER_VALIDATE_BOOLEAN )
			&& wc_review_is_from_verified_owner( $comment->comment_ID )
		) {
			$label_id = 'alg-dtwp-verified';
			if ( ! isset( $this->labels_info[ $label_id ] ) ) {
				$this->labels_info[ $label_id ]['label_class'] = $label_id;
				$this->labels_info[ $label_id ]['icon_class']  = sanitize_text_field( get_option( 'alg_dtwp_opt_v_owner_label_icon', 'fas fa-check' ) );
				if ( ! empty( $label_tip_text = get_option( 'alg_dtwp_opt_v_owner_label_txt', __( 'Verified owner', 'discussions-tab-for-woocommerce-products' ) ) ) ) {
					$this->labels_info[ $label_id ]['tip'] = $label_tip_text;
				}
			}
			$labels[] = $this->labels_info[ $label_id ];
		}
		return $labels;
	}

	/**
	 * is_comment_from_product_author.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @param $comment
	 *
	 * @return bool
	 */
	function is_comment_from_product_author( $comment ) {
		// Cache
		if ( isset( $this->checked_ids_for_product_author[ $comment->user_id ] ) ) {
			return $this->checked_ids_for_product_author[ $comment->user_id ];
		}
		// Check
		if ( $comment->user_id === get_post_field( 'post_author', $comment->comment_post_ID ) ) {
			$this->checked_ids_for_product_author[ $comment->user_id ] = true;
			return true;
		}
		$this->checked_ids_for_product_author[ $comment->user_id ] = false;
		return false;
	}

	/**
	 * add_product_author_label.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @param $labels
	 * @param $comment
	 * @param $args
	 *
	 * @return array
	 */
	function add_product_author_label( $labels, $comment, $args ) {
		if (
			filter_var( get_option( 'alg_dtwp_opt_author_label', 'no' ), FILTER_VALIDATE_BOOLEAN )
			&& $this->is_comment_from_product_author( $comment )
		) {
			$label_id = 'alg-dtwp-item-author';
			if ( ! isset( $this->labels_info[ $label_id ] ) ) {
				$this->labels_info[ $label_id ]['label_class'] = $label_id;
				$this->labels_info[ $label_id ]['icon_class']  = sanitize_text_field( get_option( 'alg_dtwp_opt_author_label_icon', 'fas fa-user' ) );
				if ( ! empty( $label_tip_text = get_option( 'alg_dtwp_opt_author_label_txt', __( 'Verified owner', 'discussions-tab-for-woocommerce-products' ) ) ) ) {
					$this->labels_info[ $label_id ]['tip'] = $label_tip_text;
				}
				if ( ! empty( $label_title_text = get_option( 'alg_dtwp_opt_author_title', __( 'Verified owner', 'discussions-tab-for-woocommerce-products' ) ) ) ) {
					$this->labels_info[ $label_id ]['title'] = $label_title_text;
				}
			}
			$labels[] = $this->labels_info[ $label_id ];
		}
		return $labels;
	}

	/**
	 * Create authors label.
	 *
	 * @version 1.3.0
	 * @since   1.0.5
	 *
	 * @param   $new_classes
	 * @param   $comment
	 *
	 * @return  array
	 */
	function create_product_author_classes( $new_classes, $comment ) {
		if (
			filter_var( get_option( 'alg_dtwp_opt_author_label', 'no' ), FILTER_VALIDATE_BOOLEAN )
			&& $this->is_comment_from_product_author( $comment )
		) {
			$new_classes[] = 'alg-dtwp-item-author';
		}
		return $new_classes;
	}

	/**
	 * create_verified_author_labels
	 *
	 * @version 1.3.0
	 * @since   1.2.4
	 *
	 * @param $new_classes
	 * @param $comment
	 *
	 * @return array
	 */
	function create_verified_owner_classes( $new_classes, $comment ) {
		if (
			filter_var( get_option( 'alg_dtwp_opt_v_owner_label', 'no' ), FILTER_VALIDATE_BOOLEAN )
			&& wc_review_is_from_verified_owner( $comment->comment_ID )
		) {
			$new_classes[] = 'alg-dtwp-verified';
		}
		return $new_classes;
	}

	/**
	 * create_comment_author_icons.
	 *
	 * @version 1.3.4
	 * @since   1.3.3
	 *
	 * @param $author_link
	 * @param $author
	 * @param $comment_id
	 *
	 * @return mixed
	 */
	function create_comment_author_icons( $author_link, $author, $comment_id ) {
		if (
			( ! is_admin() || is_ajax() )
			&& 'comment_author' === $this->get_icon_positioning_option()
			&& ! empty( $comment = get_comment( $comment_id ) )
			&& alg_wc_pdt_get_comment_type_id() == $comment->comment_type
			&& count( $labels = apply_filters( 'alg_dtwp_labels', array(), $comment, '' ) ) > 0
		) {
			$icons       = $this->create_icons_html( $labels, array(
				'template' => '<span class="alg-dtwp-labels alg-dtwp-comment-author-labels">{icons}</span>'
			) );
			$author_link .= $icons;
		}
		return $author_link;
	}

	/**
	 * get_icon_positioning_option.
	 *
	 * @version 1.3.3
	 * @since   1.3.3
	 *
	 * @return mixed|void
	 */
	function get_icon_positioning_option() {
		if ( empty( $this->icon_positioning_option ) ) {
			$this->icon_positioning_option = get_option( 'alg_dtwp_icons_positioning', 'comment_text' );
		}
		return $this->icon_positioning_option;
	}

	/**
	 * create_comment_icons_html.
	 *
	 * @version 1.4.6
	 * @since   1.3.0
	 *
	 * @param $comment_text
	 * @param $comment
	 *
	 * @return string
	 */
	function create_comment_icons_html( $comment_text, $comment ) {
		$args = 3 === func_num_args() ? func_get_arg( 2 ) : array();
		if (
			( ! is_admin() || is_ajax() )
			&& 'comment_text' === $this->get_icon_positioning_option()
			&& $comment
			&& alg_wc_pdt_get_comment_type_id() == $comment->comment_type
			&& count( $labels = apply_filters( 'alg_dtwp_labels', array(), $comment, $args ) ) > 0
		) {
			$icons        = $this->create_icons_html( $labels, array(
				'template' => '<div class="alg-dtwp-labels alg-dtwp-comment-text-labels">{icons}</div>'
			) );
			$comment_text = $icons . $comment_text;
		}

		return $comment_text;
	}

	/**
	 * create_icons_html.
	 *
	 * @version 1.3.6
	 * @since   1.3.3
	 *
	 * @param $labels
	 *
	 * @return mixed|string
	 */
	function create_icons_html( $labels, $args = null ) {
		$args       = wp_parse_args( $args, array(
			'template'              => '<div class="alg-dtwp-labels">{icons}</div>',
			'template_needs_labels' => true,
		) );
		$labels_str = '';
		foreach ( $labels as $label ) {
			if ( ! empty( $label['icon_class'] ) ) {
				$labels_str .= '<div class="alg-dtwp-label ' . esc_attr( $label['label_class'] ) . '-label has-tip">';
				$labels_str .= '<i class="alg-dtwp-fa ' . esc_attr( $label['icon_class'] ) . '" aria-hidden="true"></i>';
				$labels_str .= isset( $label['tip'] ) && ! empty( $label['tip'] ) ? '<div class="alg-dtwp-tip">' . esc_html( $label['tip'] ) . '</div>' : '';
				$labels_str .= '</div>';
			}
		}
		if ( $args['template_needs_labels'] && empty( $labels_str ) ) {
			return '';
		}
		$labels_wrapper = str_replace( '{icons}', $labels_str, $args['template'] );
		return $labels_wrapper;
	}

	/**
	 * Adds css classes on comments.
	 *
	 * @version 1.5.3
	 * @since   1.0.2
	 * @param   $classes
	 * @param   $class
	 * @param   $comment_id
	 * @param   $comment
	 * @param   $post_id
	 * @return  array
	 */
	function add_classes_on_comments( $classes, $class, $comment_id, $comment, $post_id ) {
	
		if ( ! alg_wc_pdt_is_discussion_tab() && ! ( $comment && $comment->comment_type === 'review' ) ) {
			return $classes;
		}
		
		$new_classes = array();
		//$new_classes = apply_filters( 'alg_dtwp_comment_tags', $new_classes, $comment_id, $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID );
		$new_classes = apply_filters( 'alg_dtwp_comment_tags', $new_classes, $comment );
		$new_classes = array_map( 'sanitize_text_field', $new_classes );
		$new_classes = array_map( 'esc_attr', $new_classes );
		$new_classes = array_map( 'sanitize_title', $new_classes );
		return array_merge( $classes, $new_classes );
	}

	/**
	 * get_possible_comments_tags.
	 *
	 * @version 1.3.0
	 * @since   1.0.4
	 *
	 * @return mixed|void
	 */
	function get_possible_comments_tags() {
		$possible_comment_tags = apply_filters( 'alg_dtwp_possible_comment_tags', array() );
		return $possible_comment_tags;
	}

	/**
	 * Creates the labels style.
	 *
	 * @version 1.5.3
	 * @since   1.0.4
	 */
	function handle_labels_style() {
		if ( ! is_product() ) {
			return;
		}
		$tags  = $this->get_possible_comments_tags();
		$style = '';
		foreach ( $tags as $tag ) {
			$label_style = apply_filters( 'alg_dtwp_label_' . $tag . '_style', array( 'color' => '#ffffff', 'bkg_color' => '#ccc' ), $tag );
			$style       .= ".{$tag}-label{background:{$label_style['bkg_color']} !important;} .{$tag}-label .alg-dtwp-fa{color:{$label_style['color']}}";
			if ( ! empty( $label_style['title'] ) ) {
				$style .= ".{$tag}.comment > .comment-body .comment-meta:before{content:'{$label_style['title']}';color:{$label_style['color']};background-color:{$label_style['bkg_color']} !important;}";
				
				$style .= ".{$tag}.review > .comment_container:before{content:'{$label_style['title']}'!important;color:{$label_style['color']};background-color:{$label_style['bkg_color']} !important; margin-bottom: 10px; padding: 5px 10px;}";
			}
		}
		wp_add_inline_style( 'alg-dtwp', $style );
	}

	/**
	 * Creates the tip style.
	 *
	 * @version 1.1.0
	 * @since   1.0.5
	 */
	function handle_label_tip_style() {
		if ( ! is_product() ) {
			return;
		}
		$border_color = get_option( 'alg_dtwp_opt_label_tip_border_color', '#d4b943' );
		$bkg_color    = get_option( 'alg_dtwp_opt_label_tip_bkg_color',    '#fef4c5' );
		$txt_color    = get_option( 'alg_dtwp_opt_label_tip_txt_color',    '#222' );
		$style        = "
			.alg-dtwp-tip{
				 border:1px solid {$border_color};
				 background-color:{$bkg_color};
				 color:{$txt_color};
			}
		";
		wp_add_inline_style( 'alg-dtwp', $style );
	}

	/**
	 * maybe_load_fontawesome.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @todo    [dev] (maybe) set `alg_dtwp_font_awesome` default to `no`?
	 */
	function maybe_load_fontawesome() {
		if ( ! is_product() ) {
			return;
		}
		if ( 'yes' === get_option( 'alg_dtwp_font_awesome', 'yes' ) ) {
			if ( ! wp_script_is( 'alg-font-awesome' ) ) {
				wp_enqueue_style( 'alg-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css', array() );
			}
		}
	}

}

endif;