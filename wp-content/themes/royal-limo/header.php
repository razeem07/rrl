<?php
/**
 * Header template.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>document.documentElement.classList.replace('no-js','js');</script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'royal-limo' ); ?></a>

<header id="site-header" class="site-header glass-panel glass-nav">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="rl-logo">
					<span class="rl-logo__badge" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M5 17h14M5 17a2 2 0 100 4 2 2 0 000-4zm14 0a2 2 0 100 4 2 2 0 000-4zM5 17l1.2-6.4A2 2 0 018.15 9h7.7a2 2 0 011.95 1.6L19 17M5 17V9.5A1.5 1.5 0 016.5 8h11A1.5 1.5 0 0119 9.5V17"/>
						</svg>
					</span>
					<span class="rl-logo__word"><?php bloginfo( 'name' ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<button id="nav-toggle" class="nav-toggle" aria-expanded="false" aria-controls="primary-menu">
			<span class="nav-toggle__bar"></span>
			<span class="nav-toggle__bar"></span>
			<span class="nav-toggle__bar"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'royal-limo' ); ?></span>
		</button>

		<nav id="primary-menu" class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'royal-limo' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'primary-nav__list',
				'fallback_cb'    => false,
			) );
			?>
			<a href="<?php echo esc_attr( royal_limo_phone_href( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ) ); ?>" class="primary-nav__phone-mobile">
				<?php echo esc_html( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ); ?>
			</a>
		</nav>

		<a href="<?php echo esc_attr( royal_limo_phone_href( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ) ); ?>" class="primary-nav__hotline" aria-label="<?php esc_attr_e( 'Call our 24/7 hotline', 'royal-limo' ); ?>">
			<span class="primary-nav__hotline-icon" aria-hidden="true">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M3 18v-6a9 9 0 0118 0v6"/>
					<path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/>
				</svg>
			</span>
			<span class="primary-nav__hotline-text">
				<span class="primary-nav__hotline-label"><?php esc_html_e( '24/7 Hotline', 'royal-limo' ); ?></span>
				<span class="primary-nav__hotline-number"><?php echo esc_html( get_theme_mod( 'royal_limo_phone', ROYAL_LIMO_DEFAULT_PHONE ) ); ?></span>
			</span>
		</a>
	</div>
</header>

<main id="main-content" class="site-main">
