<?php
/**
 * Fleet Category archive (/fleet/category/{slug}/) — same layout as
 * archive-fleet.php, filtered to the current term by WP's default
 * taxonomy query. Tabs highlight whichever category is current.
 */
get_header();

$current_term     = get_queried_object();
$fleet_header     = royal_limo_fleet_section();
$fleet_categories = royal_limo_fleet_categories();
?>

<section class="rl-section rl-fleet-archive">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $fleet_header['eyebrow'] ); ?></p>
			<h1><?php echo esc_html( $current_term->name ); ?></h1>
			<?php if ( $current_term->description ) : ?>
				<p><?php echo esc_html( $current_term->description ); ?></p>
			<?php else : ?>
				<p><?php echo esc_html( $fleet_header['description'] ); ?></p>
			<?php endif; ?>
		</div>

		<div class="rl-filter-tabs rl-reveal">
			<a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>" class="rl-filter-tabs__link"><?php esc_html_e( 'All Vehicles', 'royal-limo' ); ?></a>
			<?php foreach ( $fleet_categories as $term ) : ?>
				<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="rl-filter-tabs__link<?php echo ( $term->term_id === $current_term->term_id ) ? ' is-active' : ''; ?>"><?php echo esc_html( $term->name ); ?></a>
			<?php endforeach; ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/fleet-card' ); ?>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'No vehicles in this category yet.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
