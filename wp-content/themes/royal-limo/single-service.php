<?php
/**
 * Single service template.
 */
get_header();

while ( have_posts() ) :
	the_post();
	?>

	<section class="rl-section rl-single-service">
		<div class="container">
			<div class="rl-section__header rl-reveal">
				<p class="rl-eyebrow"><?php esc_html_e( 'Our Services', 'royal-limo' ); ?></p>
				<h1><?php the_title(); ?></h1>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="glass-panel rl-single-fleet__media rl-reveal">
					<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
				</div>
			<?php endif; ?>

			<div class="glass-panel rl-page__content rl-reveal">
				<?php the_content(); ?>
			</div>

			<div class="rl-single-fleet__cta rl-reveal" style="text-align:center;">
				<a href="<?php echo esc_url( royal_limo_booking_url() ); ?>" class="rl-btn rl-btn--neu rl-btn--gold">
					<?php esc_html_e( 'Get a Quote', 'royal-limo' ); ?>
				</a>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php get_footer(); ?>
