<?php
/**
 * Quote/booking form renderer. Uses the CF7 shortcode configured in the
 * Customizer when available; otherwise falls back to a static, styled
 * placeholder so the design is complete before the plugin is installed.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function royal_limo_quote_form( $variant = 'compact' ) {
	$shortcode = get_theme_mod( 'royal_limo_quote_shortcode', '' );

	if ( $shortcode ) {
		echo do_shortcode( $shortcode );
		return;
	}
	?>
	<form class="rl-quote-form glass-panel rl-quote-form--<?php echo esc_attr( $variant ); ?>" method="post" action="#">
		<div class="rl-quote-form__row">
			<label class="screen-reader-text" for="rl-pickup-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Pickup location', 'royal-limo' ); ?></label>
			<input type="text" id="rl-pickup-<?php echo esc_attr( $variant ); ?>" name="pickup" placeholder="<?php esc_attr_e( 'Pickup location', 'royal-limo' ); ?>" class="rl-input rl-input--neu" required>

			<label class="screen-reader-text" for="rl-dropoff-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Drop-off location', 'royal-limo' ); ?></label>
			<input type="text" id="rl-dropoff-<?php echo esc_attr( $variant ); ?>" name="dropoff" placeholder="<?php esc_attr_e( 'Drop-off location', 'royal-limo' ); ?>" class="rl-input rl-input--neu" required>
		</div>

		<div class="rl-quote-form__row">
			<label class="screen-reader-text" for="rl-date-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Date', 'royal-limo' ); ?></label>
			<input type="date" id="rl-date-<?php echo esc_attr( $variant ); ?>" name="date" class="rl-input rl-input--neu" required>

			<label class="screen-reader-text" for="rl-time-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Time', 'royal-limo' ); ?></label>
			<input type="time" id="rl-time-<?php echo esc_attr( $variant ); ?>" name="time" class="rl-input rl-input--neu" required>
		</div>

		<?php if ( 'full' === $variant ) : ?>
			<div class="rl-quote-form__row">
				<label class="screen-reader-text" for="rl-name-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Full name', 'royal-limo' ); ?></label>
				<input type="text" id="rl-name-<?php echo esc_attr( $variant ); ?>" name="your-name" placeholder="<?php esc_attr_e( 'Full name', 'royal-limo' ); ?>" class="rl-input rl-input--neu" required>

				<label class="screen-reader-text" for="rl-passengers-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Passengers', 'royal-limo' ); ?></label>
				<input type="number" min="1" id="rl-passengers-<?php echo esc_attr( $variant ); ?>" name="passengers" placeholder="<?php esc_attr_e( 'Passengers', 'royal-limo' ); ?>" class="rl-input rl-input--neu">
			</div>
			<div class="rl-quote-form__row">
				<label class="screen-reader-text" for="rl-email-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Email', 'royal-limo' ); ?></label>
				<input type="email" id="rl-email-<?php echo esc_attr( $variant ); ?>" name="your-email" placeholder="<?php esc_attr_e( 'Email address', 'royal-limo' ); ?>" class="rl-input rl-input--neu" required>

				<label class="screen-reader-text" for="rl-phone-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Phone', 'royal-limo' ); ?></label>
				<input type="tel" id="rl-phone-<?php echo esc_attr( $variant ); ?>" name="tel" placeholder="<?php esc_attr_e( 'Phone number', 'royal-limo' ); ?>" class="rl-input rl-input--neu" required>
			</div>
			<div class="rl-quote-form__row">
				<label class="screen-reader-text" for="rl-message-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Message', 'royal-limo' ); ?></label>
				<textarea id="rl-message-<?php echo esc_attr( $variant ); ?>" name="your-message" placeholder="<?php esc_attr_e( 'Tell us about your trip (optional)', 'royal-limo' ); ?>" class="rl-input rl-input--neu" rows="4"></textarea>
			</div>
		<?php else : ?>
			<label class="screen-reader-text" for="rl-phone-<?php echo esc_attr( $variant ); ?>"><?php esc_html_e( 'Phone', 'royal-limo' ); ?></label>
			<input type="tel" id="rl-phone-<?php echo esc_attr( $variant ); ?>" name="tel" placeholder="<?php esc_attr_e( 'Phone number', 'royal-limo' ); ?>" class="rl-input rl-input--neu" required>
		<?php endif; ?>

		<button type="submit" class="rl-btn rl-btn--neu rl-btn--gold rl-quote-form__submit">
			<?php esc_html_e( 'Get a Quote', 'royal-limo' ); ?>
		</button>
	</form>
	<?php
}
