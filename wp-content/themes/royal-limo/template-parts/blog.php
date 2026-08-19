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
					<?php get_template_part( 'template-parts/blog-card' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Blog posts will appear here once published.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
