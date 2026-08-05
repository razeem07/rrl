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

<?php wp_footer(); ?>
</body>
</html>
