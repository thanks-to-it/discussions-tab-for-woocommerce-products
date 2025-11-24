<?php
/**
 * Discussions Tab for WooCommerce Products - Facebook Manager
 *
 * @version 1.1.0
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WC_Products_Discussions_Tab;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WC_Products_Discussions_Tab\Social' ) ) :

class Social {

	/**
	 * input_fb_id.
	 */
	public $input_fb_id = 'alg_dtwp_fb_id';

	/**
	 * input_fb_id.
	 */
	public $option_fb_users = 'alg_dtwp_fb_users';

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {
		add_action( 'wp_footer',                 array( $this, 'init_facebook' ) );
		// Autofill
		add_action( 'comment_form_after_fields', array( $this, 'add_facebook_autofill_button' ) );
		add_action( 'alg_wc_pdt_load_scripts',   array( $this, 'init_autofill_button_js' ) );
		add_action( 'comment_form_top',          array( $this, 'add_fb_id_input' ) );
		add_filter( 'preprocess_comment',        array( $this, 'save_fb_id_meta' ) );
		add_filter( 'get_avatar_url',            array( $this, 'set_avatar_from_fb' ), 10, 3 );
	}

	/**
	 * Initializes the Facebook SDK.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function init_facebook() {
		$facebook_app_id = filter_var( get_option( 'alg_dtwp_fb_app_id', true ), FILTER_SANITIZE_NUMBER_INT );
		if ( ! $facebook_app_id || ! is_product() ) {
			return;
		}
		?>
		<script>
			window.fbAsyncInit = function () {
				FB.init({
					appId: '<?php echo $facebook_app_id; ?>',
					autoLogAppEvents: true,
					xfbml: true,
					version: 'v2.10'
				});
				FB.AppEvents.logPageView();
			};

			(function (d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {
					return;
				}
				js = d.createElement(s);
				js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
		<?php
	}

	/**
	 * Initializes the js responsible for autofilling.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function init_autofill_button_js() {
		$facebook_app_id    = filter_var( get_option( 'alg_dtwp_fb_app_id', true ), FILTER_SANITIZE_NUMBER_INT );
		$facebook_auto_fill = filter_var( get_option( 'alg_dtwp_fb_autofill', true ), FILTER_VALIDATE_BOOLEAN );
		if (
			! $facebook_auto_fill ||
			! $facebook_app_id ||
			! is_product()
		) {
			return;
		}

		if ( ! wp_script_is( 'jquery', 'done' ) ) {
			wp_enqueue_script( 'jquery' );
		}
		$js = "
			jQuery( document ).ready(function( $ ) {
				alg_dtwp_fb_autofill = {
					fb_response: null,
					autofill_btn_selector: '#alg_dtwp_fb_autofill',
					init: function() {
						var btn = $( this.autofill_btn_selector );
						if ( btn.length ) {
							btn.on( 'click', this.get_fb_infs );
							$( 'body' ).on( 'alg_dtwp_get_fb_infs', this.fill );
						}
					},
					get_fb_infs:function( e ) {
						e.preventDefault();
						if ( ! alg_dtwp_fb_autofill.fb_response ) {
							FB.login(
							function( response ) {
								if ( response.authResponse ) {
										FB.api( '/me', {fields: 'id,name,email,picture'}, function( response ) {
											alg_dtwp_fb_autofill.fb_response = response;
											$( 'body' ).trigger( {
												type: 'alg_dtwp_get_fb_infs',
												fb_response: response
											} );
										} );
									}
								},
								{scope: 'email'}
							);
						} else {
							$( 'body' ).trigger( {
								type: 'alg_dtwp_get_fb_infs',
								fb_response: alg_dtwp_fb_autofill.fb_response
							} );
						}
					},
					fill: function( e ) {
						var fb_response = e.fb_response;
						var btn         = $( alg_dtwp_fb_autofill.autofill_btn_selector );
						var name_field  = btn.parent().find( 'input[name=\"author\"]' );
						var email_field = btn.parent().find( 'input[name=\"email\"]' );
						var fb_id_field = btn.parent().find( $( '#alg_dtwp_fb_id' ) );
						if ( name_field.length ) {
							name_field.val( fb_response.name );
						}
						if ( email_field.length ) {
							email_field.val( fb_response.email );
						}
						if ( fb_id_field.length ) {
							fb_id_field.val( fb_response.id );
						}
					}
				}
				alg_dtwp_fb_autofill.init();
			} );
		";
		wp_add_inline_script( 'jquery-migrate', $js );
	}

	/**
	 * Adds an input that will get the facebook user id.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function add_fb_id_input() {
		$facebook_auto_fill        = filter_var( get_option( 'alg_dtwp_fb_autofill', true ), FILTER_VALIDATE_BOOLEAN );
		$facebook_auto_fill_avatar = filter_var( get_option( 'alg_dtwp_fb_autofill_avatar', true ), FILTER_VALIDATE_BOOLEAN );
		if ( ! alg_wc_pdt_is_discussion_tab() || ! $facebook_auto_fill || ! $facebook_auto_fill_avatar ) {
			return;
		}
		echo '<input type="hidden" id="' . esc_attr( $this->input_fb_id ) . '" name="' . esc_attr( $this->input_fb_id ) . '" />';
	}

	/**
	 * Adds the facebook autofill button
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_facebook_autofill_button() {
		echo $this->get_facebook_autofill_button();
	}

	/**
	 * Adds the facebook autofill button.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @return  string
	 */
	function get_facebook_autofill_button() {
		$facebook_app_id    = filter_var( get_option( 'alg_dtwp_fb_app_id', true ), FILTER_SANITIZE_NUMBER_INT );
		$facebook_auto_fill = filter_var( get_option( 'alg_dtwp_fb_autofill', true ), FILTER_VALIDATE_BOOLEAN );
		$autofill_btn_label = sanitize_text_field( get_option( 'alg_dtwp_fb_autofill_btn_label', __( 'Fill with Facebook', 'discussions-tab-for-woocommerce-products' ) ) );
		if ( ! alg_wc_pdt_is_discussion_tab() || ! $facebook_app_id || ! $facebook_auto_fill ) {
			return;
		}
		return sprintf(
			'<input name="alg_dtwp_fb_autofill" type="submit" id="alg_dtwp_fb_autofill" class="alg_dtwp_fb_autofill" value="%1$s" style="background-color:#3B5998;color:#fff;margin-bottom:22px;border:none">',
			$autofill_btn_label
		);
	}

	/**
	 * Saves on database the facebook user id on comment.
	 *
	 * Also saves on option 'alg_dtwp_fb_users' the facebook users (including email and fb id).
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $url
	 * @param   $id_or_email
	 * @param   null $args
	 * @return  string
	 */
	function save_fb_id_meta( $comment_data ) {
		$request                   = $_REQUEST;
		$facebook_auto_fill        = filter_var( get_option( 'alg_dtwp_fb_autofill', true ), FILTER_VALIDATE_BOOLEAN );
		$facebook_auto_fill_avatar = filter_var( get_option( 'alg_dtwp_fb_autofill_avatar', true ), FILTER_VALIDATE_BOOLEAN );
		if (
			isset( $request[ alg_wc_pdt_get_comment_type_id() ] ) && ! filter_var( $request[ alg_wc_pdt_get_comment_type_id() ], FILTER_VALIDATE_BOOLEAN ) ||
			isset( $request[ $this->input_fb_id ] ) && $request[ $this->input_fb_id ] == '' ||
			! isset( $request[ $this->input_fb_id ] ) ||
			! $facebook_auto_fill_avatar ||
			! $facebook_auto_fill
		) {
			return $comment_data;
		}
		$fb_id = filter_var( $request[ $this->input_fb_id ], FILTER_SANITIZE_NUMBER_INT );
		$email = filter_var( $request['email'], FILTER_SANITIZE_EMAIL );
		$comment_data['comment_meta'][ $this->input_fb_id ] = $fb_id;
		$option_fb_users = get_option( $this->option_fb_users, false );
		if ( empty( $option_fb_users ) ) {
			$option_fb_users = array(
				array( 'id' => $fb_id, 'email' => $email )
			);
		} else {
			$prev_email = wp_filter_object_list( $option_fb_users, array( 'email' => $email ) );
			if ( empty( $prev_email ) ) {
				$option_fb_users[] = array( 'id' => $fb_id, 'email' => $email );
			}
		}
		update_option( $this->option_fb_users, $option_fb_users );
		return $comment_data;
	}

	/**
	 * Gets the avatar from facebook.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   $url
	 * @param   $id_or_email
	 * @param   null $args
	 * @return  string
	 */
	function set_avatar_from_fb( $url, $id_or_email, $args = null ) {
		$facebook_auto_fill_avatar = filter_var( get_option( 'alg_dtwp_fb_autofill_avatar', true ), FILTER_VALIDATE_BOOLEAN );
		$facebook_auto_fill        = filter_var( get_option( 'alg_dtwp_fb_autofill', true ), FILTER_VALIDATE_BOOLEAN );
		if ( ! $facebook_auto_fill_avatar || ! $facebook_auto_fill || ! alg_wc_pdt_is_discussion_tab() ) {
			return $url;
		}
		$email = '';
		if ( is_object( $id_or_email ) ) {
			$email = $id_or_email->comment_author_email;
		} else if ( is_numeric( $id_or_email ) ) {
			$user  = get_user_by( 'id', $id_or_email );
			$email = $user->user_email;
		} else {
			$email = $id_or_email;
		}
		$option_fb_users = get_option( $this->option_fb_users, false );
		if ( ! empty( $option_fb_users ) ) {
			$prev_fb_id_arr = wp_filter_object_list( $option_fb_users, array( 'email' => $email ), 'and', 'id' );
			if ( count( $prev_fb_id_arr ) > 0 ) {
				$prev_fb_id = array_values( $prev_fb_id_arr )[0];
				$url        = add_query_arg( array(
					'type' => 'large'
				), "http://graph.facebook.com/{$prev_fb_id}/picture/" );
			}
		}
		return $url;
	}
}

endif;