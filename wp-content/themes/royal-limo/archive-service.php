<?php
/**
 * Services archive (/services/) — full-bleed banner (same pattern as
 * single-service.php/page-about.php/page-contact.php), then the
 * listing, branded to match the homepage teaser (same service-card
 * partial) rather than the generic index.php blog-post-card fallback.
 * Banner image is managed dynamically via Customizer > Services
 * Section > Banner Image (this is a CPT archive, not an editable page,
 * so there's no page-editor screen to attach it to instead).
 */
get_header();

$services_header = royal_limo_services_section();
?>

<section class="rl-page-header rl-reveal" <?php if ( $services_header['image_url'] ) : ?>style="background-image: linear-gradient(180deg, rgba(10, 10, 10, .55) 0%, rgba(10, 10, 10, .8) 100%), url('<?php echo esc_url( $services_header['image_url'] ); ?>');"<?php endif; ?>>
	<div class="rl-page-header__inner">
		<h1><?php echo esc_html( $services_header['heading'] ); ?></h1>
		<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
			<span aria-hidden="true">/</span>
			<span class="rl-breadcrumb__current"><?php esc_html_e( 'Services', 'royal-limo' ); ?></span>
		</nav>
	</div>
</section>

<section class="rl-section rl-services-archive">
	<div class="container">
		<?php if ( $services_header['eyebrow'] || $services_header['description'] ) : ?>
			<div class="rl-section__header rl-reveal">
				<?php if ( $services_header['eyebrow'] ) : ?>
					<p class="rl-eyebrow"><?php echo esc_html( $services_header['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( $services_header['description'] ) : ?>
					<p><?php echo esc_html( $services_header['description'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/service-card' ); ?>
				<?php endwhile; ?>
			</div>
			<?php royal_limo_infinite_scroll_sentinel( 'service' ); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Services will appear here once added under Services in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
