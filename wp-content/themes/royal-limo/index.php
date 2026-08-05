<?php
/**
 * Fallback template.
 */
get_header();
?>

<div class="container rl-section">
	<?php if ( have_posts() ) : ?>
		<div class="rl-post-grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'glass-panel rl-post-card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="rl-post-card__thumb">
							<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
						</a>
					<?php endif; ?>
					<h2 class="rl-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="rl-post-card__excerpt"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'royal-limo' ); ?></p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
