<?php
/**
 * Footer template.
 */
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__inner">
		<div class="site-footer__col">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="site-title"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
			<p class="site-footer__tagline"><?php bloginfo( 'description' ); ?></p>
			<div class="site-footer__socials">
				<?php foreach ( array( 'facebook', 'instagram', 'x' ) as $network ) :
					$url = get_theme_mod( "royal_limo_social_{$network}", '' );
					if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="rl-icon-tile" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
							<?php echo esc_html( strtoupper( substr( $network, 0, 1 ) ) ); ?>
						</a>
					<?php endif;
				endforeach; ?>
			</div>
		</div>

		<div class="site-footer__col">
			<h3 class="site-footer__heading"><?php esc_html_e( 'Explore', 'royal-limo' ); ?></h3>
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
			<h3 class="site-footer__heading"><?php esc_html_e( 'Contact', 'royal-limo' ); ?></h3>
			<p><a href="<?php echo esc_attr( royal_limo_phone_href( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ); ?></a></p>
			<p><a href="mailto:<?php echo esc_attr( get_theme_mod( 'royal_limo_email', '' ) ); ?>"><?php echo esc_html( get_theme_mod( 'royal_limo_email', '' ) ); ?></a></p>
			<p><?php echo esc_html( get_theme_mod( 'royal_limo_address', '' ) ); ?></p>
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
