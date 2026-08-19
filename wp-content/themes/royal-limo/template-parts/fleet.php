<?php
/**
 * Fleet showcase — pulls from the "fleet" CPT. If Fleet Categories are
 * in use, shows client-side filter tabs above the grid (see
 * [data-rl-filter-group] in assets/js/main.js) — this is a homepage
 * teaser of a limited set of vehicles, not a paginated archive, so
 * instant show/hide is a better fit here than real navigation. The
 * full /fleet/ archive and per-category archives (archive-fleet.php,
 * taxonomy-fleet_category.php) use real linked tabs instead.
 */
$fleet_query = new WP_Query( array(
	'post_type'      => 'fleet',
	'posts_per_page' => 6,
	'no_found_rows'  => true,
) );
$fleet_header     = royal_limo_fleet_section();
$fleet_categories = royal_limo_fleet_categories();
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

		<?php if ( $fleet_categories ) : ?>
			<div class="rl-filter-tabs rl-reveal" data-rl-filter-group>
				<button type="button" class="rl-filter-tabs__btn is-active" data-filter="all"><?php esc_html_e( 'All', 'royal-limo' ); ?></button>
				<?php foreach ( $fleet_categories as $term ) : ?>
					<button type="button" class="rl-filter-tabs__btn" data-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $fleet_query->have_posts() ) : ?>
			<div class="rl-grid rl-grid--3" data-rl-filter-target>
				<?php while ( $fleet_query->have_posts() ) : $fleet_query->the_post(); ?>
					<?php get_template_part( 'template-parts/fleet-card' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Vehicles will appear here once added under Fleet in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
