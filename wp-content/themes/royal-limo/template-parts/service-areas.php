<?php
/**
 * "Where We Serve" + "Our Ventures" — two independently-optional content
 * blocks sharing one physical <section> (and so one background) rather
 * than two adjacent sections, so they read as a single merged block in
 * the "Black & Off-White" alternate color scheme instead of alternating
 * against each other — and so inserting/removing "Our Ventures" never
 * shifts every later section's light/dark position (see scheme-alt.css).
 * Content set via Customizer > Service Areas / Our Ventures
 * (royal_limo_service_areas() / royal_limo_ventures() in functions.php).
 */
$service_areas = royal_limo_service_areas();
$ventures_data = royal_limo_ventures();

$has_areas    = ! empty( $service_areas['locations'] );
$has_ventures = ! empty( $ventures_data['ventures'] );

if ( ! $has_areas && ! $has_ventures ) {
	return;
}
?>
<section class="rl-service-areas rl-section" id="service-areas">
	<div class="container">
		<?php if ( $has_areas ) : ?>
			<div class="rl-section__header rl-reveal">
				<p class="rl-eyebrow"><?php echo esc_html( $service_areas['eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $service_areas['heading'] ); ?></h2>
				<?php if ( $service_areas['description'] ) : ?>
					<p><?php echo esc_html( $service_areas['description'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="rl-route rl-reveal" data-rl-route>
				<div class="rl-route__line" data-rl-route-line></div>
				<div class="rl-route__car" data-rl-route-car aria-hidden="true">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M5 17h14M5 17a2 2 0 100 4 2 2 0 000-4zm14 0a2 2 0 100 4 2 2 0 000-4zM5 17l1.2-6.4A2 2 0 018.15 9h7.7a2 2 0 011.95 1.6L19 17M5 17V9.5A1.5 1.5 0 016.5 8h11A1.5 1.5 0 0119 9.5V17"/>
					</svg>
				</div>
				<?php foreach ( $service_areas['locations'] as $location ) : ?>
					<div class="rl-route__stop">
						<span class="rl-route__dot" aria-hidden="true"></span>
						<span class="rl-route__label"><?php echo esc_html( $location ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $has_ventures ) : ?>
			<div class="rl-ventures<?php echo $has_areas ? ' rl-ventures--stacked' : ''; ?>" id="ventures">
				<div class="rl-section__header rl-reveal">
					<p class="rl-eyebrow"><?php echo esc_html( $ventures_data['eyebrow'] ); ?></p>
					<h2><?php echo esc_html( $ventures_data['heading'] ); ?></h2>
				</div>

				<div class="rl-ventures__grid rl-reveal">
					<?php foreach ( $ventures_data['ventures'] as $venture ) :
						$tag   = $venture['url'] ? 'a' : 'div';
						$attrs = $venture['url'] ? ' href="' . esc_url( $venture['url'] ) . '" target="_blank" rel="noopener noreferrer"' : '';
						?>
						<<?php echo esc_html( $tag ) . $attrs; ?> class="rl-venture-card">
							<img src="<?php echo esc_url( $venture['logo'] ); ?>" alt="<?php echo esc_attr( $venture['name'] ); ?>" class="rl-venture-card__logo" loading="lazy">
							<?php if ( $venture['tagline'] ) : ?>
								<span class="rl-venture-card__tagline"><?php echo esc_html( $venture['tagline'] ); ?></span>
							<?php endif; ?>
						</<?php echo esc_html( $tag ); ?>>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
