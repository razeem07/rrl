<?php
/**
 * About page "What We Do" section — a photo (or stylised fallback) on
 * the left, eyebrow/heading/description + a 2x2 grid of feature items
 * on the right. Content set on the About page itself (sidebar panel).
 */
$wwd = royal_limo_what_we_do( get_the_ID() );

$icons = array(
	// Calendar-check (booking).
	'<rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><path d="M9 15l2 2 4-4"></path>',
	// Map pin (pickups).
	'<path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0118 0z"></path><circle cx="12" cy="10" r="3"></circle>',
	// Sliders (flexible packages).
	'<line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><circle cx="4" cy="12" r="2"></circle><circle cx="12" cy="10" r="2"></circle><circle cx="20" cy="14" r="2"></circle>',
	// Phone/support.
	'<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"></path>',
);
?>
<section class="rl-what-we-do rl-section" id="what-we-do">
	<div class="container">
		<div class="rl-grid rl-grid--2 rl-what-we-do__layout rl-reveal">
			<div class="rl-what-we-do__media<?php echo $wwd['image_url'] ? '' : ' rl-media-fallback'; ?>">
				<?php if ( $wwd['image_url'] ) : ?>
					<img src="<?php echo esc_url( $wwd['image_url'] ); ?>" alt="" loading="lazy">
				<?php endif; ?>
			</div>

			<div class="rl-what-we-do__copy">
				<p class="rl-eyebrow"><?php echo esc_html( $wwd['eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $wwd['heading'] ); ?></h2>
				<p class="rl-what-we-do__description"><?php echo esc_html( $wwd['description'] ); ?></p>

				<div class="rl-what-we-do__features">
					<?php foreach ( $wwd['features'] as $i => $feature ) : ?>
						<div class="rl-what-we-do__feature">
							<span class="rl-icon-tile" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><?php echo $icons[ $i % count( $icons ) ]; ?></svg>
							</span>
							<h4><?php echo esc_html( $feature['heading'] ); ?></h4>
							<p><?php echo esc_html( $feature['description'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
