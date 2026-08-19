<?php
/**
 * Blog posts page (/blog/) — full post listing, branded to match the
 * homepage teaser (same blog-card partial) rather than the generic
 * index.php post-card fallback. This is WordPress's "home.php" template
 * slot: used automatically for the designated Posts page when the
 * front page is a static page (Settings > Reading), which is the case
 * here (front-page.php is the real homepage).
 */
get_header();

$blog_header = royal_limo_blog_section();
?>

<section class="rl-section rl-blog-archive">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $blog_header['eyebrow'] ); ?></p>
			<h1><?php echo esc_html( $blog_header['heading'] ); ?></h1>
			<p><?php echo esc_html( $blog_header['description'] ); ?></p>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/blog-card' ); ?>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Blog posts will appear here once published.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
