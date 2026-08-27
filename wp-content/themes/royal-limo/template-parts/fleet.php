<?php
/**
 * Fleet showcase — a homepage teaser of a handful of vehicles from the
 * "fleet" CPT, ordered the same way the full listing is (menu_order,
 * with post ID as a tiebreaker). No filter tabs here — the full
 * category-filterable listing lives at /fleet/ (archive-fleet.php)
 * and its per-category archives (taxonomy-fleet_category.php); this
 * section is just a preview with a "View All Fleet" link through to it.
 */
$fleet_query = new WP_Query( array(
	'post_type'      => 'fleet',
	'posts_per_page' => 6,
	'orderby'        => 'menu_order ID',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
$fleet_header = royal_limo_fleet_section();
?>
<section class="rl-fleet rl-section" id="fleet">
	<div class="container">
		<div class="rl-fleet__header rl-reveal">
			<div class="rl-fleet__header-copy">
				<p class="rl-eyebrow"><?php echo esc_html( $fleet_header['eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $fleet_header['heading'] ); ?></h2>
				<p><?php echo esc_html( $fleet_header['description'] ); ?></p>
			</div>
			<a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>" class="rl-fleet__view-all">
				<?php esc_html_e( 'View All Fleet', 'royal-limo' ); ?>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</a>
		</div>

		<?php if ( $fleet_query->have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( $fleet_query->have_posts() ) : $fleet_query->the_post(); ?>
					<?php get_template_part( 'template-parts/fleet-card' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Vehicles will appear here once added under Fleet in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
