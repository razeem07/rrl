<?php
/**
 * Template Name: Contact / Booking
 */
get_header();
$address = get_theme_mod( 'royal_limo_address', '' );
?>

<section class="rl-section rl-contact">
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php esc_html_e( 'Book Your Ride', 'royal-limo' ); ?></p>
			<h1><?php the_title(); ?></h1>
		</div>

		<div class="rl-grid rl-grid--2">
			<div class="rl-reveal">
				<?php royal_limo_quote_form( 'full' ); ?>
			</div>

			<div class="rl-reveal">
				<div class="glass-panel rl-card" style="margin-bottom: var(--space-md);">
					<h3 class="rl-card__title"><?php esc_html_e( 'Reach Us Directly', 'royal-limo' ); ?></h3>
					<p><a href="<?php echo esc_attr( royal_limo_phone_href( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ); ?></a></p>
					<p><a href="mailto:<?php echo esc_attr( get_theme_mod( 'royal_limo_email', '' ) ); ?>"><?php echo esc_html( get_theme_mod( 'royal_limo_email', '' ) ); ?></a></p>
					<?php if ( $address ) : ?><p><?php echo esc_html( $address ); ?></p><?php endif; ?>
				</div>

				<?php if ( $address ) : ?>
					<div class="glass-panel" style="overflow:hidden;">
						<iframe
							src="https://www.google.com/maps?q=<?php echo rawurlencode( $address ); ?>&output=embed"
							width="100%" height="320" style="border:0; display:block;"
							loading="lazy" referrerpolicy="no-referrer-when-downgrade"
							title="<?php esc_attr_e( 'Location map', 'royal-limo' ); ?>">
						</iframe>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
