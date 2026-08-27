<?php
/**
 * About page — "About Us" intro section. Same markup/classes as the
 * homepage's template-parts/about.php (so it looks identical), but a
 * separate, independently-editable copy: content comes from this
 * page's own meta fields (edited on the About page itself — see
 * royal_limo_about_page_intro() in functions.php) instead of the
 * Customizer, so editing this page never changes the homepage's About
 * section and vice versa.
 */
$about = royal_limo_about_page_intro( get_the_ID() );
?>
<section class="rl-about rl-section" id="about">
	<div class="container">
		<div class="rl-grid rl-grid--2 rl-reveal">
			<div class="rl-about__copy">
				<p class="rl-eyebrow"><?php echo esc_html( $about['eyebrow'] ); ?></p>
				<h2><?php echo esc_html( $about['heading'] ); ?></h2>
				<div class="rl-about__description">
					<?php echo wp_kses_post( wpautop( esc_html( $about['description'] ) ) ); ?>
				</div>
			</div>

			<?php if ( $about['image_url'] ) : ?>
				<div class="rl-about-media">
					<img src="<?php echo esc_url( $about['image_url'] ); ?>" alt="" loading="lazy">
				</div>
			<?php else : ?>
			<div class="glass-panel rl-about-card">
				<span class="rl-about-card__glow" aria-hidden="true"></span>
				<span class="rl-about-card__badge" aria-hidden="true">
					<svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M5 17h14M5 17a2 2 0 100 4 2 2 0 000-4zm14 0a2 2 0 100 4 2 2 0 000-4zM5 17l1.2-6.4A2 2 0 018.15 9h7.7a2 2 0 011.95 1.6L19 17M5 17V9.5A1.5 1.5 0 016.5 8h11A1.5 1.5 0 0119 9.5V17"/>
					</svg>
				</span>
				<span class="rl-about-card__word"><?php bloginfo( 'name' ); ?></span>
				<span class="rl-about-card__tagline"><?php bloginfo( 'description' ); ?></span>
			</div>
			<?php endif; ?>
		</div>

		<div class="glass-panel rl-about-bar rl-reveal">
			<div class="rl-about-bar__text">
				<h3><?php echo esc_html( $about['bar_heading'] ); ?></h3>
				<p><?php echo esc_html( $about['bar_text'] ); ?></p>
			</div>

			<?php if ( $about['reviews_rating'] ) : ?>
				<?php
				$badge_content = '
					<span class="rl-reviews-badge__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20.4H24v7.2h11.3c-1.6 4.7-6.1 8.1-11.3 8.1-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.1-5.1C34 6.5 29.3 4.5 24 4.5 13.2 4.5 4.5 13.2 4.5 24S13.2 43.5 24 43.5 43.5 34.8 43.5 24c0-1.2-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l5.9 4.3C13.8 15.4 18.5 12 24 12c3.1 0 5.9 1.2 8 3.1l5.1-5.1C34 6.5 29.3 4.5 24 4.5c-7.4 0-13.8 4.2-17.7 10.2z"/><path fill="#4CAF50" d="M24 43.5c5.2 0 9.9-2 13.4-5.2l-6.2-5.2c-1.9 1.5-4.4 2.4-7.2 2.4-5.2 0-9.6-3.3-11.3-7.9l-6.1 4.7C10.1 39.2 16.6 43.5 24 43.5z"/><path fill="#1976D2" d="M43.6 20.5H42V20.4H24v7.2h11.3c-.8 2.3-2.2 4.3-4.1 5.7l6.2 5.2C40.9 36.1 43.5 30.6 43.5 24c0-1.2-.1-2.4-.4-3.5z"/></svg>
					</span>
					<span class="rl-reviews-badge__body">
						<span class="rl-reviews-badge__row">
							<span class="rl-reviews-badge__label">' . esc_html__( 'Google Reviews', 'royal-limo' ) . '</span>
						</span>
						<span class="rl-reviews-badge__row">
							<span class="rl-reviews-badge__rating">' . esc_html( $about['reviews_rating'] ) . '</span>
							' . royal_limo_star_rating( round( (float) $about['reviews_rating'] ) ) . '
						</span>
						<span class="rl-reviews-badge__count">' . esc_html( sprintf( __( 'See all %s reviews', 'royal-limo' ), $about['reviews_count'] ) ) . '</span>
					</span>
				';
				?>
				<?php if ( $about['reviews_url'] ) : ?>
					<a href="<?php echo esc_url( $about['reviews_url'] ); ?>" class="rl-reviews-badge" target="_blank" rel="noopener noreferrer"><?php echo $badge_content; ?></a>
				<?php else : ?>
					<div class="rl-reviews-badge"><?php echo $badge_content; ?></div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
