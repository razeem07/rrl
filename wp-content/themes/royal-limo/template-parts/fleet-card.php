<?php
/**
 * A single fleet card — expects to be called mid-loop (inside
 * have_posts()/the_post()). Shared by the homepage teaser, /fleet/
 * archive, and fleet_category archives so all three stay visually
 * identical without copy-pasting the markup three times.
 */
$luggage  = get_post_meta( get_the_ID(), '_fleet_luggage_capacity', true );
$pax      = get_post_meta( get_the_ID(), '_fleet_pax', true );
$full_day = get_post_meta( get_the_ID(), '_fleet_price_full_day', true );
$half_day = get_post_meta( get_the_ID(), '_fleet_price_half_day', true );
?>
<article class="rl-fleet-card">
	<a href="<?php the_permalink(); ?>" class="rl-fleet-card__media">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'fleet-card', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( ROYAL_LIMO_URI . '/assets/img/fleet-placeholder.svg' ); ?>" alt="" loading="lazy">
		<?php endif; ?>
	</a>
	<div class="rl-fleet-card__body">
		<h3 class="rl-card__title rl-fleet-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		<?php if ( $full_day || $half_day ) : ?>
			<div class="rl-fleet-card__rates">
				<?php if ( $full_day ) : ?>
					<div class="rl-fleet-card__rate rl-fleet-card__rate--full">
						<span class="rl-fleet-card__rate-label"><?php esc_html_e( 'Full Day', 'royal-limo' ); ?></span>
						<span class="rl-fleet-card__rate-amount">AED <?php echo esc_html( number_format_i18n( $full_day, 0 ) ); ?></span>
					</div>
				<?php endif; ?>
				<?php if ( $half_day ) : ?>
					<div class="rl-fleet-card__rate">
						<span class="rl-fleet-card__rate-label"><?php esc_html_e( 'Half Day', 'royal-limo' ); ?></span>
						<span class="rl-fleet-card__rate-amount">AED <?php echo esc_html( number_format_i18n( $half_day, 0 ) ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="rl-fleet-card__specs">
			<?php if ( $pax ) : ?>
				<span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="3"/><path d="M5 21v-2a7 7 0 0114 0v2"/></svg><?php echo esc_html( sprintf( _n( '%d Pax', '%d Pax', $pax, 'royal-limo' ), $pax ) ); ?></span>
			<?php endif; ?>
			<?php if ( $luggage ) : ?>
				<span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M9 8V6a3 3 0 016 0v2"/></svg><?php echo esc_html( sprintf( _n( '%d Bag', '%d Bags', $luggage, 'royal-limo' ), $luggage ) ); ?></span>
			<?php endif; ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="rl-fleet-card__link">
			<?php esc_html_e( 'View Details', 'royal-limo' ); ?>
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
		</a>
	</div>
</article>
