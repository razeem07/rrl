<?php
/**
 * Final booking call-to-action banner — background photo (fixed
 * attachment on desktop, see layout.css), pill badge, heading,
 * description, checklist, CTA button, and phone. Content set via
 * Customizer > Booking CTA Section.
 */
$cta = royal_limo_booking_cta();
?>
<section class="rl-booking-cta-section rl-section" id="book">
	<div class="container">
		<div class="rl-booking-banner" <?php if ( $cta['image_url'] ) : ?>style="background-image: linear-gradient(90deg, rgba(10,10,10,.88) 0%, rgba(10,10,10,.55) 45%, rgba(10,10,10,.15) 100%), url('<?php echo esc_url( $cta['image_url'] ); ?>');"<?php endif; ?>>
			<div class="rl-booking-banner__copy rl-reveal">
				<span class="rl-pill-badge">
					<span class="rl-pill-badge__dot" aria-hidden="true"></span>
					<?php echo esc_html( $cta['eyebrow'] ); ?>
				</span>
				<h2><?php echo esc_html( $cta['heading'] ); ?></h2>
				<p><?php echo esc_html( $cta['description'] ); ?></p>

				<div class="rl-booking-banner__checks">
					<?php foreach ( array( $cta['check1'], $cta['check2'] ) as $check ) : ?>
						<?php if ( $check ) : ?>
							<span class="rl-booking-banner__check">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
								<?php echo esc_html( $check ); ?>
							</span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<div class="rl-booking-banner__footer">
					<a href="<?php echo esc_url( royal_limo_booking_url() ); ?>" class="rl-btn rl-btn--neu rl-btn--gold rl-btn--icon-left">
						<span class="rl-btn__icon" aria-hidden="true">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
						</span>
						<?php esc_html_e( 'Get Started Today', 'royal-limo' ); ?>
					</a>

					<a href="<?php echo esc_attr( royal_limo_phone_href( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ) ); ?>" class="rl-booking-banner__phone">
						<span class="rl-booking-banner__phone-icon" aria-hidden="true">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
						</span>
						<span class="rl-booking-banner__phone-text">
							<span class="rl-booking-banner__phone-label"><?php esc_html_e( '24/7 Support', 'royal-limo' ); ?></span>
							<span class="rl-booking-banner__phone-number"><?php echo esc_html( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ); ?></span>
						</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
