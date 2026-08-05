<?php
/**
 * Testimonials carousel — pulls from the "testimonial" CPT (admin-only,
 * no public single page). Plain CSS/JS carousel, no slider plugin.
 */
$testimonials_query = new WP_Query( array(
	'post_type'      => 'testimonial',
	'posts_per_page' => 8,
	'no_found_rows'  => true,
) );
$testimonials_header = royal_limo_testimonials_section();
?>
<section class="rl-testimonials rl-section" id="testimonials">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $testimonials_header['eyebrow'] ); ?></p>
			<h2><?php echo esc_html( $testimonials_header['heading'] ); ?></h2>
			<p><?php echo esc_html( $testimonials_header['description'] ); ?></p>
		</div>

		<?php if ( $testimonials_query->have_posts() ) : ?>
			<div class="glass-panel rl-reveal" data-rl-carousel>
				<div class="rl-testimonial-track">
					<?php $i = 0; while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post(); ?>
						<div class="rl-testimonial-slide">
							<?php echo royal_limo_star_rating( get_post_meta( get_the_ID(), '_testimonial_rating', true ) ); ?>
							<div class="rl-testimonial-slide__quote">&ldquo;<?php the_content(); ?>&rdquo;</div>
							<p class="rl-testimonial-slide__author"><?php the_title(); ?></p>
						</div>
					<?php $i++; endwhile; wp_reset_postdata(); ?>
				</div>
				<?php if ( $i > 1 ) : ?>
					<div class="rl-testimonial-dots" role="tablist">
						<?php for ( $d = 0; $d < $i; $d++ ) : ?>
							<button class="rl-testimonial-dot<?php echo 0 === $d ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr( $d ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Testimonial %d', 'royal-limo' ), $d + 1 ) ); ?>"></button>
						<?php endfor; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Testimonials will appear here once added under Testimonials in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
