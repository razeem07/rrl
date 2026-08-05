<?php
/**
 * Services strip — pulls from the "service" CPT, managed in wp-admin.
 * Each card is a full-bleed photo with a frosted glass overlay panel
 * (icon + title + excerpt) pinned to the bottom.
 */
$services_query = new WP_Query( array(
	'post_type'      => 'service',
	'posts_per_page' => 8,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
$services_header = royal_limo_services_section();
?>
<section class="rl-services rl-section" id="services">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $services_header['eyebrow'] ); ?></p>
			<h2><?php echo esc_html( $services_header['heading'] ); ?></h2>
			<p><?php echo esc_html( $services_header['description'] ); ?></p>
		</div>

		<?php if ( $services_query->have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( $services_query->have_posts() ) : $services_query->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="rl-service-card">
						<div class="rl-service-card__media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'service-card', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
							<?php endif; ?>
						</div>
						<div class="rl-service-card__overlay">
							<span class="rl-icon-tile rl-service-card__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<circle cx="12" cy="12" r="9"></circle>
								</svg>
							</span>
							<h3 class="rl-card__title"><?php the_title(); ?></h3>
							<p><?php the_excerpt(); ?></p>
						</div>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Services will appear here once added under Services in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
