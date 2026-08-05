<?php
/**
 * Front page: hero, service areas, about, fleet, why-us, services,
 * video showcase, testimonials, blog, FAQ, booking CTA.
 */
get_header();
?>

<?php get_template_part( 'template-parts/hero' ); ?>
<?php get_template_part( 'template-parts/service-areas' ); ?>
<?php get_template_part( 'template-parts/about' ); ?>
<?php get_template_part( 'template-parts/fleet' ); ?>
<?php get_template_part( 'template-parts/why-choose-us' ); ?>
<?php get_template_part( 'template-parts/services' ); ?>
<?php get_template_part( 'template-parts/video-showcase' ); ?>
<?php get_template_part( 'template-parts/testimonials' ); ?>
<?php get_template_part( 'template-parts/blog' ); ?>
<?php get_template_part( 'template-parts/faq' ); ?>
<?php get_template_part( 'template-parts/booking-cta' ); ?>

<?php get_footer(); ?>
