<?php
/**
 * Single service template — page-header banner, a sidebar (other
 * services + trust badge + phone), the service's own content with an
 * optional "Why Choose This Service?" checklist and feature-highlight
 * cards, an optional "Our Booking Process" block, and an optional FAQ
 * list — all Service CPT meta, set per-service in the block editor's
 * "Service Detail Page" sidebar panel (assets/js/admin-panels.js).
 * Deliberately NOT pulled from the Customizer or the sitewide "faq"
 * CPT: a wedding's booking flow/FAQs aren't an airport transfer's.
 */
get_header();

$about = royal_limo_about_section();

$other_services_query = new WP_Query( array(
	'post_type'      => 'service',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

while ( have_posts() ) :
	the_post();

	$current_id = get_the_ID();

	$benefits = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$benefit = get_post_meta( $current_id, "_service_benefit{$i}", true );
		if ( $benefit ) {
			$benefits[] = $benefit;
		}
	}

	$features = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$feature = get_post_meta( $current_id, "_service_feature{$i}_title", true );
		if ( $feature ) {
			$features[] = $feature;
		}
	}

	$process_steps = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$step_title = get_post_meta( $current_id, "_service_process_step{$i}_title", true );
		if ( $step_title ) {
			$process_steps[] = array(
				'title'       => $step_title,
				'description' => get_post_meta( $current_id, "_service_process_step{$i}_description", true ),
			);
		}
	}

	$service_faqs = get_post_meta( $current_id, '_service_faqs', true );
	if ( ! is_array( $service_faqs ) ) {
		$service_faqs = array();
	}

	$additional_content = get_post_meta( $current_id, '_service_additional_content', true );

	// Section headings — editable per service (Service Detail Page
	// panel); fall back to the same text this template always used to
	// hardcode, so existing services look unchanged until edited.
	$benefits_heading  = get_post_meta( $current_id, '_service_benefits_heading', true );
	$benefits_heading  = $benefits_heading ? $benefits_heading : sprintf( __( 'Why Choose %s?', 'royal-limo' ), get_the_title() );
	$features_heading  = get_post_meta( $current_id, '_service_features_heading', true );
	$features_heading  = $features_heading ? $features_heading : __( "What's Included", 'royal-limo' );
	$process_eyebrow   = get_post_meta( $current_id, '_service_process_eyebrow', true );
	$process_eyebrow   = $process_eyebrow ? $process_eyebrow : __( 'How It Works', 'royal-limo' );
	$process_heading   = get_post_meta( $current_id, '_service_process_heading', true );
	$process_heading   = $process_heading ? $process_heading : sprintf( __( '%s Booking Process', 'royal-limo' ), get_the_title() );
	$faq_eyebrow       = get_post_meta( $current_id, '_service_faq_eyebrow', true );
	$faq_eyebrow       = $faq_eyebrow ? $faq_eyebrow : __( 'Good To Know', 'royal-limo' );
	$faq_heading       = get_post_meta( $current_id, '_service_faq_heading', true );
	$faq_heading       = $faq_heading ? $faq_heading : sprintf( __( '%s — Frequently Asked Questions', 'royal-limo' ), get_the_title() );

	// Banner image is a dedicated field (Service Detail Page panel) so
	// the page-header banner and the in-content photo further down can
	// be two different pictures — falls back to the featured image if
	// the admin hasn't set one yet, rather than showing nothing.
	$banner_image_url = get_post_meta( $current_id, '_service_banner_image', true );
	if ( ! $banner_image_url && has_post_thumbnail() ) {
		$banner_image_url = get_the_post_thumbnail_url( $current_id, 'large' );
	}
	?>

	<section class="rl-page-header rl-reveal" <?php if ( $banner_image_url ) : ?>style="background-image: linear-gradient(180deg, rgba(10, 10, 10, .55) 0%, rgba(10, 10, 10, .8) 100%), url('<?php echo esc_url( $banner_image_url ); ?>');"<?php endif; ?>>
		<div class="rl-page-header__inner">
			<h1><?php the_title(); ?></h1>
			<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'royal-limo' ); ?></a>
				<span aria-hidden="true">/</span>
				<span class="rl-breadcrumb__current"><?php the_title(); ?></span>
			</nav>
		</div>
	</section>

	<section class="rl-section rl-service-detail">
		<div class="container">
			<div class="rl-service-detail__layout">
				<aside class="rl-service-sidebar rl-reveal">
					<?php if ( $other_services_query->have_posts() ) : ?>
						<div class="rl-service-sidebar__list-card">
							<div class="rl-service-sidebar__list-header"><?php esc_html_e( 'Explore Our Services', 'royal-limo' ); ?></div>
							<ul class="rl-service-sidebar__list">
								<?php while ( $other_services_query->have_posts() ) : $other_services_query->the_post(); ?>
									<li>
										<a href="<?php the_permalink(); ?>"<?php echo ( get_the_ID() === $current_id ) ? ' class="is-active"' : ''; ?>>
											<?php the_title(); ?>
											<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
										</a>
									</li>
								<?php endwhile; wp_reset_postdata(); ?>
							</ul>
						</div>
					<?php endif; ?>

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
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="rl-service-detail__media rl-reveal">
							<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
							<a href="<?php echo esc_url( royal_limo_booking_url() ); ?>" class="rl-btn rl-btn--neu rl-btn--gold rl-btn--sm">
								<?php esc_html_e( 'Reserve Now', 'royal-limo' ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="rl-page__content rl-reveal">
						<?php the_content(); ?>
					</div>

					<?php if ( $benefits ) : ?>
						<div class="rl-service-detail__section rl-reveal">
							<h2><?php echo esc_html( $benefits_heading ); ?></h2>
							<div class="rl-service-detail__checklist">
								<?php foreach ( $benefits as $benefit ) : ?>
									<span class="rl-service-detail__check">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
										<?php echo esc_html( $benefit ); ?>
									</span>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $features ) : ?>
						<div class="rl-service-detail__section rl-reveal">
							<h2><?php echo esc_html( $features_heading ); ?></h2>
							<div class="rl-grid rl-grid--3">
								<?php foreach ( $features as $feature ) : ?>
									<div class="rl-feature-card">
										<span class="rl-icon-tile" aria-hidden="true">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
										</span>
										<h3 class="rl-card__title"><?php echo esc_html( $feature ); ?></h3>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<div class="rl-single-fleet__cta rl-reveal" style="text-align:left;">
						<a href="<?php echo esc_url( royal_limo_booking_url() ); ?>" class="rl-btn rl-btn--neu rl-btn--gold">
							<?php esc_html_e( 'Reserve This Service', 'royal-limo' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( $process_steps ) : ?>
		<section class="rl-section rl-service-process">
			<div class="container">
				<div class="rl-section__header rl-reveal">
					<p class="rl-eyebrow"><?php echo esc_html( $process_eyebrow ); ?></p>
					<h2><?php echo esc_html( $process_heading ); ?></h2>
				</div>

				<div class="rl-service-process__layout">
					<div class="rl-service-process__steps rl-reveal">
						<?php foreach ( $process_steps as $i => $step ) : ?>
							<div class="rl-service-process__step">
								<span class="rl-icon-tile" aria-hidden="true"><?php echo esc_html( $i + 1 ); ?></span>
								<div>
									<h3><?php echo esc_html( $step['title'] ); ?></h3>
									<?php if ( $step['description'] ) : ?>
										<p><?php echo esc_html( $step['description'] ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $additional_content ) : ?>
		<section class="rl-section rl-service-additional">
			<div class="container">
				<div class="rl-page__content rl-reveal">
					<?php echo apply_filters( 'the_content', $additional_content ); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $service_faqs ) : ?>
		<section class="rl-section rl-faq" id="service-faq">
			<div class="container">
				<div class="rl-faq__header rl-reveal">
					<p class="rl-eyebrow"><?php echo esc_html( $faq_eyebrow ); ?></p>
					<h2><?php echo esc_html( $faq_heading ); ?></h2>
				</div>
				<div class="rl-faq-list" data-rl-accordion>
					<?php foreach ( $service_faqs as $i => $faq ) : ?>
						<div class="rl-faq-item rl-reveal<?php echo ( 0 === $i ) ? ' is-open' : ''; ?>">
							<button type="button" class="rl-faq-item__question" aria-expanded="<?php echo ( 0 === $i ) ? 'true' : 'false'; ?>" aria-controls="rl-service-faq-answer-<?php echo esc_attr( $i ); ?>">
								<span><?php echo esc_html( $faq['question'] ); ?></span>
								<svg class="rl-faq-item__chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
							</button>
							<div class="rl-faq-item__answer" id="rl-service-faq-answer-<?php echo esc_attr( $i ); ?>">
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
