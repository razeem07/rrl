<?php
/**
 * Single blog post template.
 */
get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article <?php post_class( 'rl-single-post' ); ?>>
		<div class="container rl-section">
			<header class="rl-section__header rl-reveal">
				<p class="rl-eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
				<h1><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="glass-panel rl-single-fleet__media rl-reveal">
					<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
				</div>
			<?php endif; ?>

			<div class="glass-panel rl-page__content rl-reveal">
				<?php
				the_content();
				wp_link_pages( array(
					'before' => '<nav class="rl-page-links">' . esc_html__( 'Pages:', 'royal-limo' ),
					'after'  => '</nav>',
				) );
				?>
			</div>
		</div>
	</article>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<div class="container rl-section" style="padding-top: 0;">
			<div class="glass-panel rl-page__content">
				<?php comments_template(); ?>
			</div>
		</div>
	<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
