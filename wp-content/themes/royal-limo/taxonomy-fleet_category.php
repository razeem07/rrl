<?php
/**
 * Fleet Category archive (/fleet-category/{slug}/) — same layout as
 * archive-fleet.php including the full-bleed banner, filtered to the
 * current term by WP's default taxonomy query. Tabs highlight whichever
 * category is current. Banner image is the same one set under
 * Customizer > Fleet Section > Banner Image (shared with archive-
 * fleet.php), but the title/breadcrumb reflect this specific category.
 */
get_header();

$current_term     = get_queried_object();
$fleet_header     = royal_limo_fleet_section();
$fleet_categories = royal_limo_fleet_categories();
?>

<section class="rl-page-header rl-reveal" <?php if ( $fleet_header['image_url'] ) : ?>style="background-image: linear-gradient(180deg, rgba(10, 10, 10, .55) 0%, rgba(10, 10, 10, .8) 100%), url('<?php echo esc_url( $fleet_header['image_url'] ); ?>');"<?php endif; ?>>
	<div class="rl-page-header__inner">
		<h1><?php echo esc_html( $current_term->name ); ?></h1>
		<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
			<span aria-hidden="true">/</span>
			<a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>"><?php esc_html_e( 'Fleet', 'royal-limo' ); ?></a>
			<span aria-hidden="true">/</span>
			<span class="rl-breadcrumb__current"><?php echo esc_html( $current_term->name ); ?></span>
		</nav>
	</div>
</section>

<section class="rl-section rl-fleet-archive">
	<div class="container">
		<?php if ( $fleet_header['eyebrow'] || $current_term->description || $fleet_header['description'] ) : ?>
			<div class="rl-section__header rl-reveal">
				<?php if ( $fleet_header['eyebrow'] ) : ?>
					<p class="rl-eyebrow"><?php echo esc_html( $fleet_header['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( $current_term->description ) : ?>
					<p><?php echo esc_html( $current_term->description ); ?></p>
				<?php else : ?>
					<p><?php echo esc_html( $fleet_header['description'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="rl-filter-tabs rl-reveal">
			<a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>" class="rl-filter-tabs__link"><?php esc_html_e( 'All Fleet', 'royal-limo' ); ?></a>
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
			<?php royal_limo_infinite_scroll_sentinel( 'fleet', $current_term->slug ); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'No vehicles in this category yet.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
