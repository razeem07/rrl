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
					<?php get_template_part( 'template-parts/service-card' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Services will appear here once added under Services in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
