<?php
/**
 * Fleet archive (/fleet/) — full vehicle listing, branded to match the
 * homepage teaser (same fleet-card partial) rather than the generic
 * index.php blog-post-card fallback. Category tabs are real links here
 * (to each fleet_category archive) rather than the homepage teaser's
 * JS show/hide, since this is a paginated page worth being able to
 * link/share/bookmark per category.
 */
get_header();

$fleet_header     = royal_limo_fleet_section();
$fleet_categories = royal_limo_fleet_categories();
?>

<section class="rl-section rl-fleet-archive">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $fleet_header['eyebrow'] ); ?></p>
			<h1><?php echo esc_html( $fleet_header['heading'] ); ?></h1>
			<p><?php echo esc_html( $fleet_header['description'] ); ?></p>
		</div>

		<?php if ( $fleet_categories ) : ?>
			<div class="rl-filter-tabs rl-reveal">
				<a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>" class="rl-filter-tabs__link is-active"><?php esc_html_e( 'All Vehicles', 'royal-limo' ); ?></a>
				<?php foreach ( $fleet_categories as $term ) : ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="rl-filter-tabs__link"><?php echo esc_html( $term->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/fleet-card' ); ?>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Vehicles will appear here once added under Fleet in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
