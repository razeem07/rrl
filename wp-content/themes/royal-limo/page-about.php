<?php
/**
 * Template Name: About Us
 *
 * Full-bleed hero banner (title + breadcrumb), then: About Us, Our
 * Approach (mission/vision), Why Choose Us, Key Persons (team), What We
 * Do, and the shared booking CTA.
 */
get_header();
?>

<section class="rl-page-header rl-reveal">
	<div class="rl-page-header__inner">
		<h1><?php the_title(); ?></h1>
		<nav class="rl-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'royal-limo' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'royal-limo' ); ?></a>
			<span aria-hidden="true">/</span>
			<span class="rl-breadcrumb__current"><?php the_title(); ?></span>
		</nav>
	</div>
</section>

<?php
get_template_part( 'template-parts/about' );
get_template_part( 'template-parts/our-approach' );
get_template_part( 'template-parts/why-choose-us' );
get_template_part( 'template-parts/team' );
get_template_part( 'template-parts/what-we-do' );
get_template_part( 'template-parts/booking-cta' );

get_footer();
