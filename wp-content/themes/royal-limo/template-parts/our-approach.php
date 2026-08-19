<?php
/**
 * About page "Our Approach" section — eyebrow/heading + Mission/Vision
 * editorial blocks on the left, a photo (or stylised fallback) on the
 * right. Content set via Customizer > Our Approach Section.
 */
$approach = royal_limo_our_approach();
?>
<section class="rl-approach rl-section" id="approach">
	<div class="container">
		<div class="rl-grid rl-grid--2 rl-approach__layout rl-reveal">
			<div class="rl-approach__copy">
				<p class="rl-eyebrow"><?php echo esc_html( $approach['eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $approach['heading'] ); ?></h2>

				<div class="rl-approach__block">
					<span class="rl-icon-tile" aria-hidden="true">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 2l9 4.9V17L12 22l-9-5.1V6.9L12 2z"/>
						</svg>
					</span>
					<h3><?php echo esc_html( $approach['mission_heading'] ); ?></h3>
					<ul class="rl-approach__list">
						<?php foreach ( $approach['mission_points'] as $point ) : ?>
							<?php if ( $point ) : ?>
								<li>
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
									<?php echo esc_html( $point ); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>

				<div class="rl-approach__block">
					<span class="rl-icon-tile" aria-hidden="true">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="3"/>
							<path d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/>
						</svg>
					</span>
					<h3><?php echo esc_html( $approach['vision_heading'] ); ?></h3>
					<ul class="rl-approach__list">
						<?php foreach ( $approach['vision_points'] as $point ) : ?>
							<?php if ( $point ) : ?>
								<li>
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
									<?php echo esc_html( $point ); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<div class="rl-approach__media<?php echo $approach['image_url'] ? '' : ' rl-media-fallback'; ?>">
				<?php if ( $approach['image_url'] ) : ?>
					<img src="<?php echo esc_url( $approach['image_url'] ); ?>" alt="" loading="lazy">
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
