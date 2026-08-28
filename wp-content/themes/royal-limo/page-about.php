<?php
/**
 * Template Name: About Us
 *
 * Every section on this page — the banner image, About Us, Our
 * Approach (mission/vision), Why Choose Us, Key Persons (team), and
 * What We Do — is edited directly on this page in wp-admin (sidebar
 * panel, see assets/js/admin-panels.js and
 * royal_limo_register_about_page_meta() in inc/custom-post-types.php),
 * not the Customizer. The banner title/breadcrumb use the page's own
 * title, same as every other page-header banner in the theme.
 */
get_header();

$about_banner_image = get_post_meta( get_the_ID(), '_about_banner_image', true );
?>

<section class="rl-page-header rl-reveal" <?php if ( $about_banner_image ) : ?>style="background-image: linear-gradient(180deg, rgba(10, 10, 10, .55) 0%, rgba(10, 10, 10, .8) 100%), url('<?php echo esc_url( $about_banner_image ); ?>');"<?php endif; ?>>
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
get_template_part( 'template-parts/about-page-intro' );
get_template_part( 'template-parts/our-approach' );
get_template_part( 'template-parts/about-page-why-us' );
get_template_part( 'template-parts/team' );
get_template_part( 'template-parts/what-we-do' );
get_template_part( 'template-parts/booking-cta' );

get_footer();
