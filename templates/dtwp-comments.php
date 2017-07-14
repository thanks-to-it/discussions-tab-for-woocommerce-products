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
			printf( // WPCS: XSS OK.
				esc_html( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'discussions-tab-for-woocommerce-products' ) ),
				number_format_i18n( get_comments_number() ),
				'<span>' . get_the_title() . '</span>'
			);
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
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
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

	comment_form();
	?>

</section><!-- #comments -->
