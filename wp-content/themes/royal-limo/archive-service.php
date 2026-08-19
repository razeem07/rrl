<?php
/**
 * Services archive (/services/) — full listing, branded to match the
 * homepage teaser (same service-card partial) rather than the generic
 * index.php blog-post-card fallback.
 */
get_header();

$services_header = royal_limo_services_section();
?>

<section class="rl-section rl-services-archive">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $services_header['eyebrow'] ); ?></p>
			<h1><?php echo esc_html( $services_header['heading'] ); ?></h1>
			<p><?php echo esc_html( $services_header['description'] ); ?></p>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/service-card' ); ?>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Services will appear here once added under Services in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
