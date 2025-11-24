<?php
/**
 * Discussions Tab for WooCommerce Products - Support Representative
 *
 * @version 1.3.4
 * @since   1.2.4
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Support' ) ) :

	class Support {

		public $label_id = 'alg-dtwp-support-reps';
		protected $checked_emails_for_support_reps = array();

		/**
		 * Constructor.
		 *
		 * @version 1.3.0
		 * @since   1.2.4
		 */
		function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
			add_action( 'save_post', array( $this, 'save_metabox_value' ) );
			add_filter( 'alg_dtwp_comment_tags', array( $this, 'create_support_reps_class' ), 10, 2 );
			add_filter( 'alg_dtwp_possible_comment_tags', array( $this, 'add_support_comment_tag' ) );
			add_filter( 'alg_dtwp_label_' . $this->label_id . '_style', array( $this, 'add_style' ) );
			// Support reps on My Account
			add_action( 'alg_dtwp_my_account_tab_content', array( $this, 'my_account_tab_content' ) );
			add_action( 'init', array( $this, 'save_my_account_reps' ) );
			add_filter( 'alg_dtwp_my_account_tab_validation', array( $this, 'enable_my_account_tab_for_support_reps' ) );
			add_filter( 'alg_dtwp_labels', array( $this, 'add_support_label' ), 10, 3 );
			//Address changed successfully.
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
		function add_style( $style ) {
			$style['bkg_color'] = get_option( 'alg_dtwp_opt_support_label_color', '#ffa22e' );
			$style['color']     = get_option( 'alg_dtwp_opt_support_txt_color', '#ffffff' );
			$style['title']     = get_option( 'alg_dtwp_opt_support_title', __( 'Support', 'discussions-tab-for-woocommerce-products' ) );
			return $style;
		}

		/**
		 * add_support_label.
		 *
		 * @version 1.3.4
		 * @since   1.3.0
		 *
		 * @param $labels
		 * @param $comment
		 * @param $args
		 *
		 * @return array
		 */
		function add_support_label( $labels, $comment, $args ) {
			if (
				filter_var( get_option( 'alg_dtwp_opt_support_label', 'no' ), FILTER_VALIDATE_BOOLEAN )
				&& $this->is_comment_from_support_reps( array( 'comment' => $comment ) )
			) {
				$label_id = $this->label_id;
				if ( ! isset( $this->labels_info[ $label_id ] ) ) {
					$this->labels_info[ $label_id ]['label_class'] = $label_id;
					$this->labels_info[ $label_id ]['icon_class']  = sanitize_text_field( get_option( 'alg_dtwp_opt_support_label_icon', 'fas fa-life-ring' ) );
					if ( ! empty( $label_tip_text = get_option( 'alg_dtwp_opt_support_label_txt', __( 'Support', 'discussions-tab-for-woocommerce-products' ) ) ) ) {
						$this->labels_info[ $label_id ]['tip'] = $label_tip_text;
					}
					if ( ! empty( $label_title_text = get_option( 'alg_dtwp_opt_support_title', __( 'Support', 'discussions-tab-for-woocommerce-products' ) ) ) ) {
						$this->labels_info[ $label_id ]['title'] = $label_title_text;
					}
				}
				$labels[] = $this->labels_info[ $label_id ];
			}
			return $labels;
		}

		/**
		 * enable_my_account_tab_for_support_reps.
		 *
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 * @param $validation
		 *
		 * @return bool
		 */
		function enable_my_account_tab_for_support_reps( $validation ) {
			if (
				'yes' === get_option( 'alg_dtwp_opt_support_label', 'no' )
				&& 'yes' === get_option( 'alg_dtwp_opt_support_my_account_tab', 'yes' )
				&& current_user_can( 'edit_products' )
			) {

				$validation = true;
			}
			return $validation;
		}

		/**
		 * add_support_comment_tag.
		 *
		 * @version 1.2.6
		 * @since   1.2.4
		 *
		 * @param $tags
		 *
		 * @return array
		 */
		function add_support_comment_tag( $tags ) {
			if ( 'yes' == get_option( 'alg_dtwp_opt_support_label', 'no' ) ) {
				$tags[] = $this->label_id;
			}
			return $tags;
		}

		/**
		 * is_comment_from_support_reps
		 *
		 * @version 1.3.0
		 * @since   1.3.0
		 *
		 * @param array $args
		 *
		 * @return bool
		 */
		function is_comment_from_support_reps( $args = array() ) {
			$args                 = wp_parse_args( $args, array(
				'comment' => null,
			) );
			$comment_post_ID      = $args['comment']->comment_post_ID;
			$comment_author_email = $args['comment']->comment_author_email;
			// Cache
			if ( isset( $this->checked_emails_for_support_reps[ $comment_author_email ] ) ) {
				return $this->checked_emails_for_support_reps[ $comment_author_email ];
			}
			// Check
			if (
				filter_var( get_option( 'alg_dtwp_opt_support_label', 'no' ), FILTER_VALIDATE_BOOLEAN )
				&&
				(
					(
						'yes' === get_option( 'alg_dtwp_opt_support_product_metabox', 'no' )
						&& ! empty( $support_reps_emails = get_post_meta( $comment_post_ID, '_alg_dtwp_support_reps_emails', true ) )
					)
					||
					(
						'yes' === get_option( 'alg_dtwp_opt_support_my_account_tab', 'yes' )
						&& ! empty( $support_reps_emails = get_user_meta( get_post_field( 'post_author', $comment_post_ID ), '_alg_dtwp_support_reps_emails', true ) )
					)
				)
				&& ( in_array( $comment_author_email, explode( "\n", str_replace( "\r", "", $support_reps_emails ) ) ) )
			) {
				$this->checked_emails_for_support_reps[ $comment_author_email ] = true;
				return true;
			}
			$this->checked_emails_for_support_reps[ $comment_author_email ] = false;
			return false;
		}

		/**
		 * create_support_reps_labels.
		 *
		 * @version 1.3.0
		 * @since   1.2.4
		 *
		 * @param $new_classes
		 *
		 * @param $comment
		 *
		 * @return array
		 */
		function create_support_reps_class( $new_classes, $comment ) {
			if ( $this->is_comment_from_support_reps( array( 'comment' => $comment ) ) ) {
				$new_classes[] = $this->label_id;
			}
			return $new_classes;
		}

		/**
		 * sanitize_emails_textarea_field.
		 *
		 * @version 1.2.9
		 * @since   1.2.9
		 *
		 * @param $field_value
		 *
		 * @return string
		 */
		function sanitize_emails_textarea_field( $field_value ) {
			$emails          = sanitize_textarea_field( $field_value );
			$reps_emails_arr = ! empty( $emails ) ? explode( "\n", str_replace( "\r", "", $emails ) ) : array();
			$reps_emails_arr = array_map( 'trim', $reps_emails_arr );
			$reps_emails_arr = array_map( 'sanitize_email', $reps_emails_arr );
			$emails          = implode( "\n", $reps_emails_arr );
			return $emails;
		}

		/**
		 * validate_emails_textarea_field.
		 *
		 * @version 1.2.9
		 * @since   1.2.9
		 *
		 * @param $field_value
		 *
		 * @return bool
		 */
		function validate_emails_textarea_field( $field_value ) {
			$emails_raw = $field_value;
			$emails     = sanitize_textarea_field( $emails_raw );
			$emails_arr = ! empty( $emails ) ? explode( "\n", str_replace( "\r", "", $emails ) ) : array();
			foreach ( $emails_arr as $value ) {
				if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
					return false;
				}
			}
			return true;
		}

		/**
		 * save_metabox_value.
		 *
		 * @version 1.2.9
		 * @since   1.2.7
		 *
		 */
		function save_my_account_reps() {
			if (
				! empty( $nonce_value = wc_get_var( $_REQUEST['alg_dtwp_support_reps_update-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ) )
				&& wp_verify_nonce( $nonce_value, 'alg_dtwp_support_reps_update' )
				&& 'yes' === get_option( 'alg_dtwp_opt_support_label', 'no' )
				&& 'yes' === get_option( 'alg_dtwp_opt_support_my_account_tab', 'yes' )
			) {
				if ( $this->validate_emails_textarea_field( $reps_emails_raw = array_key_exists( '_alg_dtwp_support_reps_emails', $_POST ) ? $_POST['_alg_dtwp_support_reps_emails'] : '' ) ) {
					$reps_emails = $this->sanitize_emails_textarea_field( $reps_emails_raw );
					update_user_meta(
						get_current_user_id(),
						'_alg_dtwp_support_reps_emails',
						$reps_emails
					);
					wc_add_notice( __( 'Support reps updated successfully.', 'discussions-tab-for-woocommerce-products' ) );
				} else {
					wc_add_notice( __( 'It was not possible to save the support reps. Please check if there is some invalid email and try again.', 'discussions-tab-for-woocommerce-products' ), 'error' );
				}
			}
		}

		/**
		 * save_metabox_value.
		 *
		 * @version 1.2.9
		 * @since   1.2.4
		 *
		 * @param $post_id
		 */
		function save_metabox_value( $post_id ) {
			if (
				'yes' === get_option( 'alg_dtwp_opt_support_label', 'no' ) ||
				'yes' === get_option( 'alg_dtwp_opt_support_product_metabox' )
			) {
				if ( $this->validate_emails_textarea_field( $reps_emails_raw = array_key_exists( '_alg_dtwp_support_reps_emails', $_POST ) ? $_POST['_alg_dtwp_support_reps_emails'] : '' ) ) {
					$reps_emails = $this->sanitize_emails_textarea_field( $reps_emails_raw );
					update_post_meta(
						$post_id,
						'_alg_dtwp_support_reps_emails',
						$reps_emails
					);
				}
			}
		}

		/**
		 * add_metabox.
		 *
		 * @version 1.2.9
		 * @since   1.2.4
		 */
		function add_metabox() {
			if (
				'yes' !== get_option( 'alg_dtwp_opt_support_label', 'no' ) ||
				'yes' !== get_option( 'alg_dtwp_opt_support_product_metabox' )
			) {
				return;
			}
			$screens = [ 'product' ];
			$tip = 'yes' === get_option( 'alg_dtwp_opt_support_my_account_tab', 'no' ) ? '<span class="woocommerce-help-tip" data-tip="' . __( 'It\'s also possible to add support reps via My Account page.', 'discussions-tab-for-woocommerce-products' ) . ' ' . __( 'If you do that, the support reps setup below will overwrite the settings from My Account page.', 'discussions-tab-for-woocommerce-products' ) . '"></span>' : '';
			foreach ( $screens as $screen ) {
				add_meta_box(
					'alg_dtwp_support_reps',
					'<span>' . __( 'Support reps', 'discussions-tab-for-woocommerce-products' ) . $tip . '</span>',
					array( $this, 'add_metabox_content' ),
					array( 'product' )
				);
			}
		}

		/**
		 * add_metabox_content.
		 *
		 * @version 1.2.9
		 * @since   1.2.4
		 *
		 * @param $post
		 */
		function add_metabox_content( $post ) {
			?>
			<p class="description">
				<?php echo sprintf( __( 'Users that will be publicly marked as "%s" if they use the Discussions comments from any of your products.', 'discussions-tab-for-woocommerce-products' ), get_option( 'alg_dtwp_opt_support_label_txt', __( 'Support', 'discussions-tab-for-woocommerce-products' ) ) ); ?>
			</p>
			<?php
			echo $this->get_reps_textarea( array( 'source' => 'post_meta', 'source_id' => $post->ID ) );
		}

		/**
		 * get_reps_textarea.
		 *
		 * @version 1.2.9
		 * @since   1.2.9
		 *
		 * @param array $args
		 *
		 * @return false|string
		 */
		function get_reps_textarea( $args = array() ) {
			$args        = wp_parse_args( $args, array(
				'source'      => 'user_meta', // user_meta | post_meta
				'source_id'   => get_current_user_id(),
				'textarea_id' => '_alg_dtwp_support_reps_emails'
			) );
			$reps_emails = isset( $_POST[ $args['textarea_id'] ] ) ? $_POST[ $args['textarea_id'] ] : ( 'user_meta' === $args['source'] ? get_user_meta( $args['source_id'], '_alg_dtwp_support_reps_emails', true ) : get_post_meta( $args['source_id'], '_alg_dtwp_support_reps_emails', true ) );
			ob_start();
			?>
			<label style="cursor: pointer;display:block;margin:10px 0 5px;" for="<?php echo esc_attr( $args['textarea_id'] ) ?>" class=""><strong><?php _e( 'Support reps emails', 'discussions-tab-for-woocommerce-products' ); ?></strong>
				- <?php _e( 'One email per line', 'discussions-tab-for-woocommerce-products' ); ?></label>
			<textarea style="width:100%;min-height:97px;" class="input-text" id="<?php echo esc_attr( $args['textarea_id'] ) ?>" name="<?php echo esc_attr( $args['textarea_id'] ) ?>"><?php echo esc_textarea( $reps_emails ); ?></textarea>
			<?php
			$output = ob_get_contents(); // the actions output will now be stored in the variable as a string!
			ob_end_clean(); // never forget this or you will keep capturing output.
			return $output;
		}

		/**
		 * my_account_tab_content.
		 *
		 * @version 1.2.9
		 * @since   1.2.7
		 */
		function my_account_tab_content() {
			do_action( 'alg_dtwp_my_account_tab_content_support_reps_before' );
			?>
			<h3><?php _e( 'Support Reps', 'discussions-tab-for-woocommerce-products' ); ?></h3>
			<p>
				<?php echo sprintf( __( 'Users that will be publicly marked as "%s" if they use the Discussions comments from any of your products.', 'discussions-tab-for-woocommerce-products' ), get_option( 'alg_dtwp_opt_support_label_txt', __( 'Support', 'discussions-tab-for-woocommerce-products' ) ) ); ?>
			</p>
			<?php if ( 'yes' === get_option( 'alg_dtwp_opt_support_product_metabox', 'no' ) ): ?>
				<p>
					<?php _e( 'It\'s also possible to add specific Support reps by product accessing your product page.', 'discussions-tab-for-woocommerce-products' ); ?>
					<?php _e( 'If you do that,	the Support reps from the product page will overwrite the general settings below.', 'discussions-tab-for-woocommerce-products' ); ?>
				</p>
			<?php endif; ?>
			<form method="post">
				<p class="form-row form-row-wide" data-priority="110" style="">
					<?php echo $this->get_reps_textarea(); ?>
				</p>
				<p>
					<button type="submit" class="button" name="save_address" value="Save address">Update</button>
				</p>
				<?php wp_nonce_field( 'alg_dtwp_support_reps_update', 'alg_dtwp_support_reps_update-nonce' ); ?>
			</form>
			<?php do_action( 'alg_dtwp_my_account_tab_content_support_reps_after' ); ?>
			<?php
		}

	}

endif;