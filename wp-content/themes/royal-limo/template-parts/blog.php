<?php
/**
 * Latest Blog Posts — pulls from core WordPress posts (no CPT needed,
 * this is exactly what the built-in blog is for). The default "Hello
 * world!" sample post is excluded so this only ever shows real content.
 */
$blog_query = new WP_Query( array(
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'post__not_in'   => array( 1 ),
	'no_found_rows'  => true,
) );
$blog_header = royal_limo_blog_section();
?>
<section class="rl-blog rl-section" id="blog">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $blog_header['eyebrow'] ); ?></p>
			<h2><?php echo esc_html( $blog_header['heading'] ); ?></h2>
			<p><?php echo esc_html( $blog_header['description'] ); ?></p>
		</div>

		<?php if ( $blog_query->have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
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
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Blog posts will appear here once published.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
