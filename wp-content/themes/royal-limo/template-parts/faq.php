<?php
/**
 * FAQ accordion — pulls from the "faq" CPT (admin-only, title = question,
 * editor content = answer). Single-open accordion, first item expanded
 * by default; JS toggle lives in main.js, entrance animation in
 * animations.js. Section header set via Customizer > FAQ Section.
 */
$faq_header = royal_limo_faq_section();

$faqs_query = new WP_Query( array(
	'post_type'      => 'faq',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );
?>
<section class="rl-faq rl-section" id="faq">
	<div class="container">
		<div class="rl-faq__header rl-reveal">
			<p class="rl-eyebrow"><?php echo esc_html( $faq_header['eyebrow'] ); ?></p>
			<h2><?php echo esc_html( $faq_header['heading'] ); ?></h2>
			<p><?php echo esc_html( $faq_header['description'] ); ?></p>
		</div>

		<?php if ( $faqs_query->have_posts() ) : ?>
			<?php get_template_part( 'template-parts/faq-list', null, array( 'faqs_query' => $faqs_query ) ); ?>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'FAQs will appear here once added under FAQs in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
