<?php
/**
 * Fleet archive (/fleet/) — full-bleed banner (same pattern as
 * single-fleet.php/page-about.php), then the full vehicle listing,
 * branded to match the homepage teaser (same fleet-card partial)
 * rather than the generic index.php blog-post-card fallback. Category
 * tabs are real links here (to each fleet_category archive) rather
 * than the homepage teaser's JS show/hide, since this is a paginated
 * page worth being able to link/share/bookmark per category. Banner
 * image is managed dynamically via Customizer > Fleet Section > Banner
 * Image (this is a CPT archive, not an editable page, so there's no
 * page-editor screen to attach it to instead) — shared with
 * taxonomy-fleet_category.php so every category page uses the same one.
 */
get_header();

$fleet_header     = royal_limo_fleet_section();
$fleet_categories = royal_limo_fleet_categories();
?>

<section class="rl-page-header rl-reveal" <?php if ( $fleet_header['image_url'] ) : ?>style="background-image: linear-gradient(180deg, rgba(10, 10, 10, .55) 0%, rgba(10, 10, 10, .8) 100%), url('<?php echo esc_url( $fleet_header['image_url'] ); ?>');"<?php endif; ?>>
	<div class="rl-page-header__inner">
		<h1><?php echo esc_html( $fleet_header['heading'] ); ?></h1>
		<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
			<span aria-hidden="true">/</span>
			<span class="rl-breadcrumb__current"><?php esc_html_e( 'Fleet', 'royal-limo' ); ?></span>
		</nav>
	</div>
</section>

<section class="rl-section rl-fleet-archive">
	<div class="container">
		<?php if ( $fleet_header['eyebrow'] || $fleet_header['description'] ) : ?>
			<div class="rl-section__header rl-reveal">
				<?php if ( $fleet_header['eyebrow'] ) : ?>
					<p class="rl-eyebrow"><?php echo esc_html( $fleet_header['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( $fleet_header['description'] ) : ?>
					<p><?php echo esc_html( $fleet_header['description'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $fleet_categories ) : ?>
			<div class="rl-filter-tabs rl-reveal">
				<a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>" class="rl-filter-tabs__link is-active"><?php esc_html_e( 'All Fleet', 'royal-limo' ); ?></a>
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
			<?php royal_limo_infinite_scroll_sentinel( 'fleet' ); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Vehicles will appear here once added under Fleet in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
