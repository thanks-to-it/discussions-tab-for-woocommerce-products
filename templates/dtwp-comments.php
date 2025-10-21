<?php
/**
 * Discussions Tab for WooCommerce Products - Comments Template
 *
 * @version 1.5.8
 * @since   1.0.0
 * @author  WPFactory
 */
?>

<?php
// Get texts from admin settings
$discussions_title_label_singular = sanitize_text_field( get_option( 'alg_dtwp_discussions_title_single',
	__( 'One thought on "%1$s"', 'discussions-tab-for-woocommerce-products' ) ) );
$discussions_title_label_plural   = sanitize_text_field( get_option( 'alg_dtwp_discussions_title_plural',
	__( '%2$d thoughts on "%1$s"', 'discussions-tab-for-woocommerce-products' ) ) );
$discussions_none_label           = sanitize_text_field( get_option( 'alg_dtwp_discussions_none',
	__( 'There are no discussions yet.', 'discussions-tab-for-woocommerce-products' ) ) );
$discussions_label                = sanitize_text_field( get_option( 'alg_dtwp_discussions_label',
	__( 'Discussions', 'discussions-tab-for-woocommerce-products' ) ) );
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
<div class="alg-dtwp-wrapper">
	<div id="comments" class="comments-area <?php echo wp_get_theme()->get( 'Name' ); ?>" aria-label="Post Comments">

		<?php

		if ( have_comments() ) : ?>
			<h2 class="comments-title">
				<?php
				$count = get_comments_number();
				$text  = $count == 1 ? $discussions_title_label_singular : $discussions_title_label_plural;
				echo sprintf( $text, '<span>' . get_the_title() . '</span>', (int) get_comments_number() );
				?>
			</h2>

			<?php // Subscription. ?>
			<?php if ( is_user_logged_in() && 'yes' === get_option( 'alg_dtwp_unsubscribing_enabled', 'no' ) ) : ?>
				<?php
				$unsubscribed_users = get_post_meta( get_the_ID(), 'dtwp_unsubscribed', false );
				$subscribed         = in_array( wp_get_current_user()->user_email, $unsubscribed_users ) ? false : true;
				?>
				<div class="dtwp-subscription">
					<input type="checkbox" id="dtwp_subscribe_via_email" name="dtwp_subscribe_via_email" value="1" <?php checked( $subscribed, 1 ); ?>/>
					<label for="dtwp_subscribe_via_email"><?php _e( 'Subscribe via email', 'discussions-tab-for-woocommerce-products' ); ?></label>
					<?php if ( (int) get_current_user_id() !== (int) get_post_field( 'post_author', get_the_ID() ) ): ?>
						<p class="description desc"><?php _e( 'You\'ll only receive emails from the threads you\'ve commented on.', 'discussions-tab-for-woocommerce-products' ); ?></p>
					<?php else: ?>
						<p class="description desc"><?php _e( 'You\'ll receive emails for every comment posted.', 'discussions-tab-for-woocommerce-products' ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php do_action( 'alg_dtwp_comments_start' ); ?>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
				<nav id="comment-nav-above" class="comment-navigation" role="navigation" aria-label="Comment Navigation Above">
					<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'discussions-tab-for-woocommerce-products' ); ?></span>
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'discussions-tab-for-woocommerce-products' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'discussions-tab-for-woocommerce-products' ) ); ?></div>
				</nav><!-- #comment-nav-above -->
			<?php endif; // Check for comment navigation. ?>

			<ol class="<?php echo implode( ' ', apply_filters( 'alg_dtwp_wp_list_comments_wrapper_class', array_map( 'sanitize_text_field', array( 'comment-list', 'commentlist' ) ), wp_get_theme()->get( 'Name' ) ) ); ?>">
				<?php wp_list_comments(); ?>
			</ol><!-- .comment-list -->

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
				<nav id="comment-nav-below" class="comment-navigation" role="navigation" aria-label="Comment Navigation Below">
					<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'discussions-tab-for-woocommerce-products' ); ?></span>
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'discussions-tab-for-woocommerce-products' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'discussions-tab-for-woocommerce-products' ) ); ?></div>
				</nav><!-- #comment-nav-below -->
			<?php endif; // Check for comment navigation.

		else: ?>

			<h2 class="comments-title"><?php echo $discussions_label; ?></h2>
			<p class="woocommerce-noreviews"><?php echo $discussions_none_label; ?></p>
			<?php do_action( 'alg_dtwp_comments_start', get_the_ID() ); ?>

		<?php endif;

		if ( ! comments_open() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'discussions-tab-for-woocommerce-products' ); ?></p>
		<?php endif;


		do_action( 'alg_dtwp_comments_end', get_the_ID() );

		?>
	</div>
</div>
