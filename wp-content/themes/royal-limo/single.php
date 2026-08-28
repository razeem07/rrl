<?php
/**
 * Single blog post template — full-bleed hero banner (title +
 * breadcrumb over the featured image, same pattern as single-service/
 * single-fleet), then content on the left with a "Recent Posts"
 * sidebar (featured image + title only) on the right. No comments
 * section, no glass-panel/bordered card look around the content.
 */
get_header();

while ( have_posts() ) :
	the_post();

	$recent_posts_query = new WP_Query( array(
		'post_type'      => 'post',
		'posts_per_page' => 5,
		'post__not_in'   => array( 1, get_the_ID() ), // exclude the default "Hello world!" sample post and this post itself.
		'no_found_rows'  => true,
	) );

	$banner_image_url = get_post_meta( get_the_ID(), '_post_banner_image', true );
	if ( ! $banner_image_url && has_post_thumbnail() ) {
		$banner_image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	}
	?>

	<section class="rl-page-header rl-page-header--blog rl-reveal" <?php if ( $banner_image_url ) : ?>style="background-image: linear-gradient(180deg, rgba(10, 10, 10, .55) 0%, rgba(10, 10, 10, .8) 100%), url('<?php echo esc_url( $banner_image_url ); ?>');"<?php endif; ?>>
		<div class="rl-page-header__inner">
			<h1><?php the_title(); ?></h1>
			<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'royal-limo' ); ?></a>
				<span aria-hidden="true">/</span>
				<span class="rl-breadcrumb__current"><?php the_title(); ?></span>
			</nav>
		</div>
	</section>

	<article <?php post_class( 'rl-single-post' ); ?>>
		<div class="container rl-section">
			<div class="rl-blog-detail__layout">
				<div class="rl-blog-detail__main">
					<p class="rl-single-post__date rl-reveal"><?php echo esc_html( get_the_date() ); ?></p>

					<div class="rl-page__content rl-reveal">
						<?php
						the_content();
						wp_link_pages( array(
							'before' => '<nav class="rl-page-links">' . esc_html__( 'Pages:', 'royal-limo' ),
							'after'  => '</nav>',
						) );
						?>
					</div>
				</div>

				<?php if ( $recent_posts_query->have_posts() ) : ?>
					<aside class="rl-blog-detail__sidebar rl-reveal">
						<div class="rl-service-sidebar__list-card">
							<div class="rl-service-sidebar__list-header"><?php esc_html_e( 'Recent Posts', 'royal-limo' ); ?></div>
							<ul class="rl-blog-sidebar__list">
								<?php while ( $recent_posts_query->have_posts() ) : $recent_posts_query->the_post(); ?>
									<li>
										<a href="<?php the_permalink(); ?>" class="rl-blog-sidebar__item">
											<span class="rl-blog-sidebar__thumb">
												<?php if ( has_post_thumbnail() ) : ?>
													<?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
												<?php else : ?>
													<img src="<?php echo esc_url( ROYAL_LIMO_URI . '/assets/img/blog-placeholder.svg' ); ?>" alt="" loading="lazy">
												<?php endif; ?>
											</span>
											<span class="rl-blog-sidebar__title"><?php the_title(); ?></span>
										</a>
									</li>
								<?php endwhile; wp_reset_postdata(); ?>
							</ul>
						</div>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	</article>

<?php endwhile; ?>

<?php get_footer(); ?>
