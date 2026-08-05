<?php
/**
 * Comments template.
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="rl-comments">

	<?php if ( have_comments() ) : ?>
		<h2 class="rl-comments__title">
			<?php
			printf(
				/* translators: %s: comment count */
				esc_html( _n( '%s Comment', '%s Comments', get_comments_number(), 'royal-limo' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>

		<ol class="rl-comments__list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="rl-comments__closed"><?php esc_html_e( 'Comments are closed.', 'royal-limo' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form( array(
		'class_form'    => 'rl-quote-form rl-comment-form',
		'class_submit'  => 'rl-btn rl-btn--neu rl-btn--gold',
		'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" class="rl-input rl-input--neu" rows="5" required></textarea></p>',
	) );
	?>
</div>
