<?php
/**
 * Generic page template.
 */
get_header();
?>

<div class="container rl-section rl-page">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'glass-panel rl-page__content' ); ?>>
			<header class="rl-page__header">
				<h1 class="rl-page__title"><?php the_title(); ?></h1>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="rl-page__thumb"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?></div>
			<?php endif; ?>
			<div class="rl-page__body">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</div>

<?php get_footer(); ?>
