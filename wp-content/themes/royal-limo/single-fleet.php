<?php
/**
 * Single vehicle template.
 */
get_header();

while ( have_posts() ) :
	the_post();

	$seating  = get_post_meta( get_the_ID(), '_fleet_seating_capacity', true );
	$luggage  = get_post_meta( get_the_ID(), '_fleet_luggage_capacity', true );
	$pax      = get_post_meta( get_the_ID(), '_fleet_pax', true );
	$full_day = get_post_meta( get_the_ID(), '_fleet_price_full_day', true );
	$half_day = get_post_meta( get_the_ID(), '_fleet_price_half_day', true );
	$specs    = get_post_meta( get_the_ID(), '_fleet_specs', true );
	?>

	<section class="rl-section rl-single-fleet">
		<div class="container">
			<div class="rl-section__header rl-reveal">
				<p class="rl-eyebrow"><?php esc_html_e( 'Our Fleet', 'royal-limo' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="glass-panel rl-single-fleet__media rl-reveal">
					<?php the_post_thumbnail( 'fleet-full', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
				</div>
			<?php endif; ?>

			<div class="rl-stats rl-single-fleet__specs rl-reveal">
				<?php if ( $seating ) : ?>
					<div class="rl-stat"><div class="rl-stat__number"><?php echo esc_html( $seating ); ?></div><div class="rl-stat__label"><?php esc_html_e( 'Seats', 'royal-limo' ); ?></div></div>
				<?php endif; ?>
				<?php if ( $luggage ) : ?>
					<div class="rl-stat"><div class="rl-stat__number"><?php echo esc_html( $luggage ); ?></div><div class="rl-stat__label"><?php esc_html_e( 'Bags', 'royal-limo' ); ?></div></div>
				<?php endif; ?>
				<?php if ( $pax ) : ?>
					<div class="rl-stat"><div class="rl-stat__number"><?php echo esc_html( $pax ); ?></div><div class="rl-stat__label"><?php esc_html_e( 'Max Pax', 'royal-limo' ); ?></div></div>
				<?php endif; ?>
				<?php if ( $full_day || $half_day ) : ?>
					<div class="rl-stat">
						<div class="rl-stat__number">
							<?php echo $full_day ? esc_html( '$' . number_format_i18n( $full_day, 0 ) ) : '—'; ?>
						</div>
						<div class="rl-stat__label"><?php esc_html_e( 'Full Day', 'royal-limo' ); ?></div>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $half_day ) : ?>
				<p class="rl-single-fleet__half-day rl-reveal" style="text-align:center;">
					<?php echo esc_html( sprintf( __( 'Half day rate: $%s', 'royal-limo' ), number_format_i18n( $half_day, 0 ) ) ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $specs ) : ?>
				<p class="rl-single-fleet__specline rl-reveal" style="text-align:center;"><?php echo esc_html( $specs ); ?></p>
			<?php endif; ?>

			<div class="glass-panel rl-page__content rl-reveal">
				<?php the_content(); ?>
			</div>

			<div class="rl-single-fleet__cta rl-reveal" style="text-align:center;">
				<a href="<?php echo esc_url( royal_limo_booking_url() ); ?>" class="rl-btn rl-btn--neu rl-btn--gold">
					<?php esc_html_e( 'Reserve This Vehicle', 'royal-limo' ); ?>
				</a>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php get_footer(); ?>
