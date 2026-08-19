<?php
/**
 * A single blog post card — expects to be called mid-loop (inside
 * have_posts()/the_post()). Shared by the homepage teaser and the
 * /blog/ posts page (home.php) so both stay visually identical
 * without copy-pasting the markup twice.
 */
?>
<article class="rl-blog-card rl-reveal">
	<a href="<?php the_permalink(); ?>" class="rl-blog-card__media">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'blog-card', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( ROYAL_LIMO_URI . '/assets/img/blog-placeholder.svg' ); ?>" alt="" loading="lazy">
		<?php endif; ?>
	</a>
	<div class="rl-blog-card__body">
		<h3 class="rl-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<a href="<?php the_permalink(); ?>" class="rl-blog-card__link">
			<?php esc_html_e( 'Learn More', 'royal-limo' ); ?>
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
		</a>
	</div>
</article>
