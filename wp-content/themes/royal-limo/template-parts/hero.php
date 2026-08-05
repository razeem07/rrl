<?php
/**
 * Hero banner carousel — pulls from the "banner" CPT (admin-only, wp-admin
 * managed). Falls back to a single static slide built from Customizer
 * values when no banners have been added yet, so the homepage never
 * ships an empty hero.
 */
$banner_query = new WP_Query( array(
	'post_type'      => 'banner',
	'posts_per_page' => 10,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$slides = array();

if ( $banner_query->have_posts() ) {
	while ( $banner_query->have_posts() ) {
		$banner_query->the_post();
		$hero_title = get_post_meta( get_the_ID(), '_banner_hero_title', true );
		$eyebrow    = get_post_meta( get_the_ID(), '_banner_eyebrow', true );
		$slides[] = array(
			'eyebrow'     => $eyebrow ? $eyebrow : ROYAL_LIMO_DEFAULT_EYEBROW,
			'title'       => $hero_title ? $hero_title : get_the_title(),
			'description' => get_post_meta( get_the_ID(), '_banner_hero_description', true ),
			'image_url'   => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '',
			'cta1_label'  => get_post_meta( get_the_ID(), '_banner_cta1_label', true ),
			'cta1_url'    => get_post_meta( get_the_ID(), '_banner_cta1_url', true ),
			'cta2_label'  => get_post_meta( get_the_ID(), '_banner_cta2_label', true ),
			'cta2_url'    => get_post_meta( get_the_ID(), '_banner_cta2_url', true ),
		);
	}
	wp_reset_postdata();
} else {
	$slides[] = array(
		'eyebrow'     => ROYAL_LIMO_DEFAULT_EYEBROW,
		'title'       => get_theme_mod( 'royal_limo_hero_heading', 'Arrive in Absolute Style' ),
		'description' => get_theme_mod( 'royal_limo_hero_subheading', ROYAL_LIMO_DEFAULT_HERO_SUBHEADING ),
		'image_url'   => '',
		'cta1_label'  => __( 'Get a Quote', 'royal-limo' ),
		'cta1_url'    => royal_limo_booking_url(),
		'cta2_label'  => __( 'View Fleet', 'royal-limo' ),
		'cta2_url'    => home_url( '/fleet/' ),
	);
}

$hero_stats = royal_limo_hero_stats();
?>
<section class="rl-hero-carousel" id="hero" data-rl-hero-carousel>
	<div class="rl-hero-track">
		<?php foreach ( $slides as $i => $slide ) : ?>
			<div class="rl-hero-slide" <?php if ( $slide['image_url'] ) : ?>style="background-image: linear-gradient(180deg, rgba(10,10,10,.55), rgba(10,10,10,.85)), url('<?php echo esc_url( $slide['image_url'] ); ?>');"<?php endif; ?>>
				<div class="container rl-hero-slide__inner">
					<div class="rl-hero-slide__copy">
						<span class="rl-pill-badge">
							<span class="rl-pill-badge__dot" aria-hidden="true"></span>
							<?php echo esc_html( $slide['eyebrow'] ); ?>
						</span>
						<h1 class="rl-hero-slide__title"><?php echo esc_html( $slide['title'] ); ?></h1>
						<?php if ( $slide['description'] ) : ?>
							<p class="rl-hero-slide__description"><?php echo esc_html( $slide['description'] ); ?></p>
						<?php endif; ?>
						<div class="rl-hero-slide__ctas">
							<?php if ( $slide['cta1_label'] && $slide['cta1_url'] ) : ?>
								<a href="<?php echo esc_url( $slide['cta1_url'] ); ?>" class="rl-btn rl-btn--neu rl-btn--gold rl-btn--icon-left">
									<span class="rl-btn__icon" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
									<?php echo esc_html( $slide['cta1_label'] ); ?>
								</a>
							<?php endif; ?>
							<?php if ( $slide['cta2_label'] && $slide['cta2_url'] ) : ?>
								<a href="<?php echo esc_url( $slide['cta2_url'] ); ?>" class="rl-btn rl-btn--neu rl-btn--icon-left">
									<span class="rl-btn__icon" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
									<?php echo esc_html( $slide['cta2_label'] ); ?>
								</a>
							<?php endif; ?>
						</div>

						<?php if ( $hero_stats ) : ?>
							<div class="rl-hero-stats">
								<?php foreach ( $hero_stats as $si => $stat ) : ?>
									<?php if ( $si > 0 ) : ?><span class="rl-hero-stats__divider" aria-hidden="true"></span><?php endif; ?>
									<div class="rl-hero-stat">
										<div class="rl-hero-stat__number" data-rl-hero-counter data-target="<?php echo esc_attr( $stat['number'] ); ?>" data-suffix="<?php echo esc_attr( $stat['suffix'] ); ?>"><?php echo esc_html( $stat['number'] . $stat['suffix'] ); ?></div>
										<div class="rl-hero-stat__label"><?php echo esc_html( $stat['label'] ); ?></div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( count( $slides ) > 1 ) : ?>
		<button type="button" class="rl-hero-arrow rl-hero-arrow--prev" data-rl-hero-prev aria-label="<?php esc_attr_e( 'Previous slide', 'royal-limo' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
		</button>
		<button type="button" class="rl-hero-arrow rl-hero-arrow--next" data-rl-hero-next aria-label="<?php esc_attr_e( 'Next slide', 'royal-limo' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
		</button>
	<?php endif; ?>
</section>
