<?php
/**
 * Template Name: Contact / Booking
 *
 * Full-bleed hero banner (title + breadcrumb), a two-column section
 * (contact info on the left, booking form card on the right), then
 * the "Where We Serve" section and a map embed for service areas.
 * Banner image is set directly on this page (sidebar panel — see
 * assets/js/admin-panels.js and royal_limo_register_contact_page_meta()
 * in inc/custom-post-types.php), not the Customizer, since this is a
 * real editable page.
 */
get_header();

$phone   = get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE );
$email   = get_theme_mod( 'royal_limo_email', '' );
$address = get_theme_mod( 'royal_limo_address', '' );

$contact_banner_image = get_post_meta( get_the_ID(), '_contact_banner_image', true );
?>

<section class="rl-page-header rl-reveal" <?php if ( $contact_banner_image ) : ?>style="background-image: url('<?php echo esc_url( $contact_banner_image ); ?>');"<?php endif; ?>>
	<div class="rl-page-header__inner">
		<h1><?php the_title(); ?></h1>
		<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
			<span aria-hidden="true">/</span>
			<span class="rl-breadcrumb__current"><?php the_title(); ?></span>
		</nav>
	</div>
</section>

<section class="rl-section rl-contact">
	<div class="container">
		<div class="rl-contact__layout">
			<div class="rl-contact__info rl-reveal">
				<h3><?php esc_html_e( 'Contact Information:', 'royal-limo' ); ?></h3>

				<div class="rl-contact-info-list">
					<div class="rl-contact-info-item">
						<span class="rl-contact-info-item__icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
						</span>
						<span>
							<span class="rl-contact-info-item__label"><?php esc_html_e( 'Need Help', 'royal-limo' ); ?></span>
							<a href="<?php echo esc_attr( royal_limo_phone_href( $phone ) ); ?>" class="rl-contact-info-item__value"><?php echo esc_html( $phone ); ?></a>
						</span>
					</div>

					<?php if ( $email ) : ?>
						<div class="rl-contact-info-item">
							<span class="rl-contact-info-item__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
							</span>
							<span>
								<span class="rl-contact-info-item__label"><?php esc_html_e( 'E-mail Us', 'royal-limo' ); ?></span>
								<a href="mailto:<?php echo esc_attr( $email ); ?>" class="rl-contact-info-item__value"><?php echo esc_html( $email ); ?></a>
							</span>
						</div>
					<?php endif; ?>

					<?php if ( $address ) : ?>
						<div class="rl-contact-info-item">
							<span class="rl-contact-info-item__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
							</span>
							<span>
								<span class="rl-contact-info-item__label"><?php esc_html_e( 'Find Us', 'royal-limo' ); ?></span>
								<span class="rl-contact-info-item__value"><?php echo esc_html( $address ); ?></span>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="rl-contact__form-card rl-reveal">
				<span class="rl-pill-badge">
					<span class="rl-pill-badge__dot" aria-hidden="true"></span>
					<?php esc_html_e( 'Contact Us', 'royal-limo' ); ?>
				</span>
				<h2><?php esc_html_e( 'Get in Touch With Us', 'royal-limo' ); ?></h2>
				<p><?php esc_html_e( "Whether you're booking a ride, asking about availability, or need help with your reservation, our team is here to support you.", 'royal-limo' ); ?></p>
				<?php royal_limo_quote_form( 'full' ); ?>
			</div>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/service-areas' ); ?>

<?php if ( $address ) : ?>
	<section class="rl-section rl-contact__map" style="padding-top: 0;">
		<div class="container">
			<div class="glass-panel" style="overflow:hidden;">
				<iframe
					src="https://www.google.com/maps?q=<?php echo rawurlencode( $address ); ?>&output=embed"
					width="100%" height="380" style="border:0; display:block;"
					loading="lazy" referrerpolicy="no-referrer-when-downgrade"
					title="<?php esc_attr_e( 'Location map', 'royal-limo' ); ?>">
				</iframe>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
