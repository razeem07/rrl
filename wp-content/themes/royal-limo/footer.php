<?php
/**
 * Footer template — four columns (Brand/Address, Contact Us, Quick
 * Links, Info Links) plus a payment-icons row and a copyright bar.
 */

$footer_phone         = get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE );
$footer_whatsapp      = get_theme_mod( 'royal_limo_whatsapp_number', '' );
$footer_email         = get_theme_mod( 'royal_limo_email', '' );
$footer_address       = get_theme_mod( 'royal_limo_address', '' );
$footer_hours         = get_theme_mod( 'royal_limo_operating_hours', ROYAL_LIMO_DEFAULT_OPERATING_HOURS );
$footer_socials       = array(
	'facebook'  => get_theme_mod( 'royal_limo_social_facebook', '' ),
	'instagram' => get_theme_mod( 'royal_limo_social_instagram', '' ),
	'linkedin'  => get_theme_mod( 'royal_limo_social_linkedin', '' ),
);
$footer_privacy_url   = get_privacy_policy_url();
$footer_terms_page    = get_page_by_path( 'terms-conditions' );
$footer_social_icons  = array(
	'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>',
	'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"></line>',
	'linkedin'  => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle>',
);
$footer_payment_icons = array(
	'paypal'  => array( 'label' => 'PayPal', 'badge_text' => 'PayPal', 'image' => get_theme_mod( 'royal_limo_payment_icon_paypal', '' ) ),
	'visa'    => array( 'label' => 'Visa', 'badge_text' => 'VISA', 'image' => get_theme_mod( 'royal_limo_payment_icon_visa', '' ) ),
	'amex'    => array( 'label' => 'Amex', 'badge_text' => 'AMEX', 'image' => get_theme_mod( 'royal_limo_payment_icon_amex', '' ) ),
	'maestro' => array( 'label' => 'Maestro', 'badge_text' => 'Maestro', 'image' => get_theme_mod( 'royal_limo_payment_icon_maestro', '' ) ),
);
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__inner">
		<div class="site-footer__col site-footer__col--brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="site-title"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>

			<?php if ( $footer_address ) : ?>
				<h3 class="site-footer__heading"><?php esc_html_e( 'Address', 'royal-limo' ); ?></h3>
				<p class="site-footer__address"><?php echo esc_html( $footer_address ); ?></p>
			<?php endif; ?>

			<?php if ( $footer_hours ) : ?>
				<p class="site-footer__hours-label"><?php esc_html_e( 'Operating Hours - Office', 'royal-limo' ); ?></p>
				<p class="site-footer__hours"><?php echo esc_html( $footer_hours ); ?></p>
			<?php endif; ?>
		</div>

		<div class="site-footer__col">
			<h3 class="site-footer__heading"><?php esc_html_e( 'Contact Us', 'royal-limo' ); ?></h3>
			<ul class="site-footer__contact-list">
				<?php if ( $footer_email ) : ?>
					<li>
						<span class="site-footer__contact-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg></span>
						<a href="mailto:<?php echo esc_attr( $footer_email ); ?>"><?php echo esc_html( $footer_email ); ?></a>
					</li>
				<?php endif; ?>
				<li>
					<span class="site-footer__contact-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg></span>
					<a href="<?php echo esc_attr( royal_limo_phone_href( $footer_phone ) ); ?>"><?php echo esc_html( $footer_phone ); ?></a>
				</li>
				<?php if ( $footer_whatsapp ) : ?>
					<li>
						<span class="site-footer__contact-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg></span>
						<a href="<?php echo esc_attr( royal_limo_phone_href( $footer_whatsapp ) ); ?>"><?php echo esc_html( $footer_whatsapp ); ?></a>
					</li>
				<?php endif; ?>
			</ul>

			<div class="site-footer__socials">
				<?php foreach ( $footer_socials as $network => $url ) :
					if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="site-footer__social-icon" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $footer_social_icons[ $network ]; ?></svg>
						</a>
					<?php endif;
				endforeach; ?>
			</div>
		</div>

		<div class="site-footer__col">
			<h3 class="site-footer__heading"><?php esc_html_e( 'Quick Links', 'royal-limo' ); ?></h3>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'site-footer__list',
				'fallback_cb'    => false,
			) );
			?>
		</div>

		<div class="site-footer__col">
			<h3 class="site-footer__heading"><?php esc_html_e( 'Info Links', 'royal-limo' ); ?></h3>
			<?php if ( $footer_privacy_url || $footer_terms_page ) : ?>
				<ul class="site-footer__list">
					<?php if ( $footer_privacy_url ) : ?>
						<li><a href="<?php echo esc_url( $footer_privacy_url ); ?>"><?php esc_html_e( 'Privacy Policy', 'royal-limo' ); ?></a></li>
					<?php endif; ?>
					<?php if ( $footer_terms_page ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $footer_terms_page ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'royal-limo' ); ?></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>

			<div class="site-footer__payments" aria-label="<?php esc_attr_e( 'Accepted payment methods', 'royal-limo' ); ?>">
				<?php foreach ( $footer_payment_icons as $key => $data ) : ?>
					<?php if ( $data['image'] ) : ?>
						<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php echo esc_attr( $data['label'] ); ?>" class="site-footer__payment-img" loading="lazy">
					<?php else : ?>
						<span class="site-footer__payment-badge site-footer__payment-badge--<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $data['badge_text'] ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<div class="site-footer__bottom">
		<div class="container">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'royal-limo' ); ?></p>
		</div>
	</div>
</footer>

<?php
$whatsapp_url = royal_limo_whatsapp_url();
if ( $whatsapp_url ) :
?>
<a href="<?php echo esc_url( $whatsapp_url ); ?>" class="rl-whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'royal-limo' ); ?>">
<svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.46 3.45 1.32 4.94L2 22l5.25-1.38a9.96 9.96 0 004.79 1.22h.01c5.52 0 10-4.48 10-10s-4.48-10-10.01-10zm0 18.16a8.1 8.1 0 01-4.14-1.13l-.3-.18-3.12.82.83-3.04-.19-.31a8.11 8.11 0 01-1.24-4.32c0-4.49 3.65-8.14 8.15-8.14 2.18 0 4.22.85 5.76 2.39a8.09 8.09 0 012.38 5.76c0 4.49-3.65 8.15-8.13 8.15zm4.47-6.1c-.24-.12-1.44-.71-1.66-.79-.22-.08-.38-.12-.55.12-.16.24-.63.79-.77.95-.14.16-.28.18-.52.06-.24-.12-1.01-.37-1.92-1.18-.71-.63-1.19-1.42-1.33-1.66-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.55-1.33-.76-1.82-.2-.48-.4-.42-.55-.42-.14-.01-.3-.01-.46-.01a.9.9 0 00-.65.3c-.22.24-.85.83-.85 2.03s.87 2.36.99 2.52c.12.16 1.71 2.61 4.14 3.66.58.25 1.03.4 1.38.51.58.18 1.11.16 1.53.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z"/></svg>
</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
