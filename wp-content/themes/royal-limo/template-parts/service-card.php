<?php
/**
 * A single service card — expects to be called mid-loop (inside
 * have_posts()/the_post()). Shared by the homepage teaser and the
 * /services/ archive so both stay visually identical without copy-
 * pasting the markup twice.
 *
 * Full-bleed photo (or a stylised fallback background when a service
 * has no featured image yet). Only the title is visible at rest; on
 * hover a full overlay reveals the icon, excerpt and a "view" CTA.
 */
?>
<a href="<?php the_permalink(); ?>" class="rl-service-card<?php echo has_post_thumbnail() ? '' : ' rl-service-card--no-image'; ?>">
	<div class="rl-service-card__media">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'service-card', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
		<?php endif; ?>
	</div>

	<span class="rl-service-card__arrow" aria-hidden="true">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
			<path d="M7 17L17 7M17 7H9M17 7V15"></path>
		</svg>
	</span>

	<div class="rl-service-card__base">
		<h3 class="rl-card__title"><?php the_title(); ?></h3>
	</div>

	<div class="rl-service-card__overlay">
		<span class="rl-icon-tile rl-service-card__icon" aria-hidden="true">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<circle cx="12" cy="12" r="9"></circle>
			</svg>
		</span>
		<h3 class="rl-card__title"><?php the_title(); ?></h3>
		<p><?php the_excerpt(); ?></p>
		<span class="rl-service-card__cta">
			<?php esc_html_e( 'Explore Service', 'royal-limo' ); ?>
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M7 17L17 7M17 7H9M17 7V15"></path>
			</svg>
		</span>
	</div>
</a>
