<?php
/**
 * Blog posts page (/blog/) — full-bleed banner (same pattern as
 * single-service.php/page-about.php), then the full post listing,
 * branded to match the homepage teaser (same blog-card partial) rather
 * than the generic index.php post-card fallback. This is WordPress's
 * "home.php" template slot: used automatically for the designated
 * Posts page when the front page is a static page (Settings >
 * Reading), which is the case here (front-page.php is the real
 * homepage). Banner image is managed dynamically via Customizer >
 * Blog Section > Banner Image (this is a template slot, not an
 * editable page in the normal sense, so there's no page-editor screen
 * to attach it to instead).
 */
get_header();

$blog_header = royal_limo_blog_section();
?>

<section class="rl-page-header rl-reveal" <?php if ( $blog_header['image_url'] ) : ?>style="background-image: linear-gradient(180deg, rgba(10, 10, 10, .55) 0%, rgba(10, 10, 10, .8) 100%), url('<?php echo esc_url( $blog_header['image_url'] ); ?>');"<?php endif; ?>>
	<div class="rl-page-header__inner">
		<h1><?php echo esc_html( $blog_header['heading'] ); ?></h1>
		<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
			<span aria-hidden="true">/</span>
			<span class="rl-breadcrumb__current"><?php esc_html_e( 'Blog', 'royal-limo' ); ?></span>
		</nav>
	</div>
</section>

<section class="rl-section rl-blog-archive">
	<div class="container">
		<?php if ( $blog_header['eyebrow'] || $blog_header['description'] ) : ?>
			<div class="rl-section__header rl-reveal">
				<?php if ( $blog_header['eyebrow'] ) : ?>
					<p class="rl-eyebrow"><?php echo esc_html( $blog_header['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( $blog_header['description'] ) : ?>
					<p><?php echo esc_html( $blog_header['description'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="rl-grid rl-grid--3">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/blog-card' ); ?>
				<?php endwhile; ?>
			</div>
			<?php royal_limo_infinite_scroll_sentinel( 'post' ); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'Blog posts will appear here once published.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
