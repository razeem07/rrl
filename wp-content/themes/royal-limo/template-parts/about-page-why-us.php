<?php
/**
 * About page — "Why Choose Us" section. Same markup/classes as the
 * homepage's template-parts/why-choose-us.php (so it looks identical),
 * but a separate, independently-editable copy: content comes from this
 * page's own meta fields (edited on the About page itself — see
 * royal_limo_about_page_why_us() in functions.php) instead of the
 * Customizer, so editing this page never changes the homepage's Why
 * Choose Us section and vice versa.
 */
$why_us = royal_limo_about_page_why_us( get_the_ID() );

$icons = array(
	// Crown.
	'<path d="M4 18h16M4 18l-1-9 5 4 4-7 4 7 5-4-1 9"/>',
	// Shield with check.
	'<path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/>',
	// Person.
	'<circle cx="12" cy="8" r="3.5"/><path d="M5 21v-1a7 7 0 0114 0v1"/>',
	// Globe.
	'<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.8 5.8 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.8-3.8-9S9.5 5.5 12 3z"/>',
);
?>
<section class="rl-why-us rl-section" id="why-choose-us">
	<span class="rl-why-us__glow" aria-hidden="true"></span>
	<div class="container">
		<div class="rl-section__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $why_us['eyebrow'] ); ?></p>
			<h2><?php echo esc_html( $why_us['heading'] ); ?></h2>
			<p><?php echo esc_html( $why_us['description'] ); ?></p>
		</div>

		<div class="rl-grid rl-grid--4">
			<?php foreach ( $why_us['reasons'] as $i => $reason ) : ?>
				<div class="rl-why-us__item rl-reveal">
					<span class="rl-icon-tile rl-why-us__icon" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
							<?php echo $icons[ $i % count( $icons ) ]; ?>
						</svg>
					</span>
					<h3 class="rl-card__title"><?php echo esc_html( $reason['heading'] ); ?></h3>
					<p><?php echo esc_html( $reason['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
