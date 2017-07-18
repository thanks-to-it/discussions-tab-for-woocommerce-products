<?php
/**
 * Discussions tab for WooCommerce Products - Comments template
 *
 * @author  Algoritmika Ltd.
 * @version 1.0.0
 * @since   1.0.0
 */
?>

<?php $plugin = Alg_DTWP_Core::get_instance(); ?>

<?php
// Get texts from admin settings
$discussions_title_label_singular = sanitize_text_field( get_option( $plugin->registry->get_admin_section_texts()->option_discussions_title_single, __( 'One thought on "%1$s"', 'discussions-tab-for-woocommerce-products' ) ) );
$discussions_title_label_plural   = sanitize_text_field( get_option( $plugin->registry->get_admin_section_texts()->option_discussions_title_plural, __( '%2$d thoughts on "%1$s"', 'discussions-tab-for-woocommerce-products' ) ) );
$discussions_respond_title        = sanitize_text_field( get_option( $plugin->registry->get_admin_section_texts()->option_discussions_respond_title, __( 'Leave a reply', 'discussions-tab-for-woocommerce-products' ) ) );
$discussions_comment_btn_label    = sanitize_text_field( get_option( $plugin->registry->get_admin_section_texts()->option_discussions_post_comment_label, __( 'Post Comment', 'discussions-tab-for-woocommerce-products' ) ) );
?>

<?php
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area" aria-label="Post Comments">

	<?php
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$count = get_comments_number();
			$text  = $count == 1 ? $discussions_title_label_singular : $discussions_title_label_plural;
			echo sprintf( $text, '<span>' . get_the_title() . '</span>', (int) get_comments_number());
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
			<nav id="comment-nav-above" class="comment-navigation" role="navigation" aria-label="Comment Navigation Above">
				<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'discussions-tab-for-woocommerce-products' ); ?></span>
				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'discussions-tab-for-woocommerce-products' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'discussions-tab-for-woocommerce-products' ) ); ?></div>
			</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php
			$list_comments_args = array();
			if ( class_exists( 'Storefront' ) ) {
				$list_comments_args = array(
					'style'      => 'ol',
					'short_ping' => true,
					'callback'   => 'storefront_comment',
				);
			}
			wp_list_comments( $list_comments_args );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
			<nav id="comment-nav-below" class="comment-navigation" role="navigation" aria-label="Comment Navigation Below">
				<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'discussions-tab-for-woocommerce-products' ); ?></span>
				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'discussions-tab-for-woocommerce-products' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'discussions-tab-for-woocommerce-products' ) ); ?></div>
			</nav><!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation.

	endif;

	if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'discussions-tab-for-woocommerce-products' ); ?></p>
	<?php endif;

	comment_form( array(
		'title_reply'  => $discussions_respond_title,
		'label_submit' => $discussions_comment_btn_label
	) );
	?>

</section><!-- #comments -->
