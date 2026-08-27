<?php
/**
 * Single vehicle template — full-bleed page-header banner, a sidebar
 * (price/specs/reserve + trust badge + phone) on the left, an image
 * gallery carousel and the vehicle's own content on the right, an
 * optional per-vehicle FAQ list, then the sitewide booking CTA.
 * Deliberately mirrors single-service.php's structure/classes (same
 * two-column layout, sidebar card, FAQ accordion) rather than
 * duplicating a parallel set of CSS for an identical layout.
 */
get_header();

$about = royal_limo_about_section();

while ( have_posts() ) :
	the_post();

	$current_id = get_the_ID();

	$luggage  = get_post_meta( $current_id, '_fleet_luggage_capacity', true );
	$pax      = get_post_meta( $current_id, '_fleet_pax', true );
	$full_day = get_post_meta( $current_id, '_fleet_price_full_day', true );
	$half_day = get_post_meta( $current_id, '_fleet_price_half_day', true );
	$specs    = get_post_meta( $current_id, '_fleet_specs', true );

	$terms = get_the_terms( $current_id, 'fleet_category' );
	$terms = ( $terms && ! is_wp_error( $terms ) ) ? $terms : array();

	// Gallery is a dedicated field (Vehicle Details panel) so the page
	// can show several angles/shots — falls back to the featured image
	// alone (as a single-slide "carousel") if no gallery is set.
	$gallery = get_post_meta( $current_id, '_fleet_gallery', true );
	if ( ! is_array( $gallery ) ) {
		$gallery = array();
	}
	if ( ! $gallery && has_post_thumbnail() ) {
		$gallery = array( get_the_post_thumbnail_url( $current_id, 'fleet-full' ) );
	}

	$fleet_faqs = get_post_meta( $current_id, '_fleet_faqs', true );
	if ( ! is_array( $fleet_faqs ) ) {
		$fleet_faqs = array();
	}

	$banner_image_url = has_post_thumbnail() ? get_the_post_thumbnail_url( $current_id, 'large' ) : '';
	?>

	<section class="rl-page-header rl-reveal" <?php if ( $banner_image_url ) : ?>style="background-image: url('<?php echo esc_url( $banner_image_url ); ?>');"<?php endif; ?>>
		<div class="rl-page-header__inner">
			<h1><?php the_title(); ?></h1>
			<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( home_url( '/fleet/' ) ); ?>"><?php esc_html_e( 'Fleet', 'royal-limo' ); ?></a>
				<span aria-hidden="true">/</span>
				<span class="rl-breadcrumb__current"><?php the_title(); ?></span>
			</nav>
		</div>
	</section>

	<section class="rl-section rl-service-detail">
		<div class="container">
			<div class="rl-service-detail__layout">
				<aside class="rl-service-sidebar rl-reveal">
					<div class="rl-service-sidebar__card">
						<?php if ( $full_day || $half_day ) : ?>
							<div class="rl-fleet-price">
							<?php if ( $full_day ) : ?>
								<div class="rl-fleet-price__col rl-fleet-price__col--full">
									<span class="rl-fleet-price__label"><?php esc_html_e( 'Full Day', 'royal-limo' ); ?></span>
								<span class="rl-fleet-price__amount">AED <?php echo esc_html( number_format_i18n( $full_day, 0 ) ); ?></span>
								</div>
							<?php endif; ?>
							<?php if ( $half_day ) : ?>
								<div class="rl-fleet-price__col rl-fleet-price__col--half">
									<span class="rl-fleet-price__label"><?php esc_html_e( 'Half Day', 'royal-limo' ); ?></span>
									<span class="rl-fleet-price__amount">AED <?php echo esc_html( number_format_i18n( $half_day, 0 ) ); ?></span>
								</div>
							<?php endif; ?>
							</div>
						<?php endif; ?>

						<ul class="rl-fleet-specs-list">
							<?php if ( $luggage ) : ?>
								<li>
									<span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M9 8V6a3 3 0 016 0v2"/></svg><?php esc_html_e( 'Luggage', 'royal-limo' ); ?></span>
									<strong><?php echo esc_html( $luggage ); ?></strong>
								</li>
							<?php endif; ?>
							<?php if ( $pax ) : ?>
								<li>
									<span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path d="M5 21v-2a7 7 0 0114 0v2"/></svg><?php esc_html_e( 'Max Pax', 'royal-limo' ); ?></span>
									<strong><?php echo esc_html( $pax ); ?></strong>
								</li>
							<?php endif; ?>
						</ul>

						<a href="<?php echo esc_url( royal_limo_booking_url() ); ?>" class="rl-btn rl-btn--neu rl-btn--gold" style="width:100%;">
							<?php esc_html_e( 'Reserve This Vehicle', 'royal-limo' ); ?>
						</a>
					</div>

					<?php if ( $about['reviews_rating'] ) : ?>
						<div class="rl-service-sidebar__card">
							<div class="rl-reviews-badge">
								<span class="rl-reviews-badge__icon" aria-hidden="true">
									<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.7 7-6.3-3.9-6.3 3.9 1.7-7L2 9.2l7.1-.6L12 2z"/></svg>
								</span>
								<span class="rl-reviews-badge__body">
									<span class="rl-reviews-badge__row">
										<span class="rl-reviews-badge__label"><?php esc_html_e( 'Google Reviews', 'royal-limo' ); ?></span>
									</span>
									<span class="rl-reviews-badge__row">
										<span class="rl-reviews-badge__rating"><?php echo esc_html( $about['reviews_rating'] ); ?></span>
										<?php echo royal_limo_star_rating( round( (float) $about['reviews_rating'] ) ); ?>
									</span>
									<span class="rl-reviews-badge__count"><?php echo esc_html( sprintf( __( 'See all %s reviews', 'royal-limo' ), $about['reviews_count'] ) ); ?></span>
								</span>
							</div>
						</div>
					<?php endif; ?>

					<a href="<?php echo esc_attr( royal_limo_phone_href( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ) ); ?>" class="rl-service-sidebar__phone">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
						<?php echo esc_html( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ); ?>
					</a>
				</aside>

				<div class="rl-service-detail__main">
					<?php if ( $gallery ) : ?>
						<div class="rl-fleet-gallery rl-reveal" data-rl-fleet-gallery>
							<div class="rl-fleet-gallery__track">
								<?php foreach ( $gallery as $image_url ) : ?>
									<div class="rl-fleet-gallery__slide" style="background-image: url('<?php echo esc_url( $image_url ); ?>');"></div>
								<?php endforeach; ?>
							</div>
							<?php if ( count( $gallery ) > 1 ) : ?>
								<div class="rl-fleet-gallery__dots">
									<?php foreach ( $gallery as $i => $image_url ) : ?>
										<button type="button" class="rl-fleet-gallery__dot<?php echo ( 0 === $i ) ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Photo %d', 'royal-limo' ), $i + 1 ) ); ?>"></button>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $terms ) : ?>
						<p class="rl-single-fleet__categories rl-reveal">
							<?php foreach ( $terms as $i => $term ) : ?>
								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a><?php echo ( $i < count( $terms ) - 1 ) ? ', ' : ''; ?>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>

					<div class="rl-page__content rl-reveal">
						<?php the_content(); ?>
					</div>

					<?php if ( $specs ) : ?>
						<p class="rl-single-fleet__specline rl-reveal"><?php echo esc_html( $specs ); ?></p>
					<?php endif; ?>

					<div class="rl-single-fleet__cta rl-reveal" style="text-align:left;">
						<a href="<?php echo esc_url( royal_limo_booking_url() ); ?>" class="rl-btn rl-btn--neu rl-btn--gold">
							<?php esc_html_e( 'Reserve This Vehicle', 'royal-limo' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( $fleet_faqs ) : ?>
		<section class="rl-section rl-faq" id="fleet-faq">
			<div class="container">
				<div class="rl-faq__header rl-reveal">
					<p class="rl-eyebrow"><?php esc_html_e( 'Good To Know', 'royal-limo' ); ?></p>
					<h2><?php echo esc_html( sprintf( __( '%s — Frequently Asked Questions', 'royal-limo' ), get_the_title() ) ); ?></h2>
				</div>
				<div class="rl-faq-list" data-rl-accordion>
					<?php foreach ( $fleet_faqs as $i => $faq ) : ?>
						<div class="rl-faq-item rl-reveal<?php echo ( 0 === $i ) ? ' is-open' : ''; ?>">
							<button type="button" class="rl-faq-item__question" aria-expanded="<?php echo ( 0 === $i ) ? 'true' : 'false'; ?>" aria-controls="rl-fleet-faq-answer-<?php echo esc_attr( $i ); ?>">
								<span><?php echo esc_html( $faq['question'] ); ?></span>
								<svg class="rl-faq-item__chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
							</button>
							<div class="rl-faq-item__answer" id="rl-fleet-faq-answer-<?php echo esc_attr( $i ); ?>">
								<div class="rl-faq-item__answer-inner">
									<p><?php echo esc_html( $faq['answer'] ); ?></p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/booking-cta' ); ?>

<?php endwhile; ?>

<?php get_footer(); ?>
