<?php
/**
 * "Where We Serve" section — eyebrow/heading/description + a route
 * graphic showing service-area stops. Content set via Customizer >
 * Service Areas (royal_limo_service_areas() in functions.php).
 */
$service_areas = royal_limo_service_areas();

if ( empty( $service_areas['locations'] ) ) {
	return;
}
?>
<section class="rl-service-areas rl-section" id="service-areas">
	<div class="container">
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
	</div>
</section>
