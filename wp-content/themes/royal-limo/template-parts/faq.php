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
			<div class="rl-faq-list" data-rl-accordion>
				<?php
				$i = 0;
				while ( $faqs_query->have_posts() ) :
					$faqs_query->the_post();
					$is_first = ( 0 === $i );
					?>
					<div class="rl-faq-item rl-reveal<?php echo $is_first ? ' is-open' : ''; ?>">
						<button type="button" class="rl-faq-item__question" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" aria-controls="rl-faq-answer-<?php echo esc_attr( get_the_ID() ); ?>">
							<span><?php the_title(); ?></span>
							<svg class="rl-faq-item__chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
						</button>
						<div class="rl-faq-item__answer" id="rl-faq-answer-<?php echo esc_attr( get_the_ID() ); ?>">
							<div class="rl-faq-item__answer-inner">
								<?php the_content(); ?>
							</div>
						</div>
					</div>
					<?php
					$i++;
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'FAQs will appear here once added under FAQs in wp-admin.', 'royal-limo' ); ?></p>
		<?php endif; ?>
	</div>
</section>
