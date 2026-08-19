<?php
/**
 * FAQ accordion list — renders whatever WP_Query is passed in via
 * $args['faqs_query'] (not yet iterated). Shared by the homepage FAQ
 * section and the single service page's "Frequently Asked Questions"
 * block so both use the exact same accordion markup/behavior (JS
 * toggle in main.js via [data-rl-accordion], single-open, first item
 * expanded).
 *
 * Note: get_template_part()'s $args is NOT auto-extracted into named
 * variables (that's a different, unrelated extract() of $wp_query's
 * own query_vars inside load_template()) — it only ever arrives as the
 * $args array itself, so callers' values must be read via $args[...].
 *
 * Expects: $args['faqs_query'] (WP_Query).
 */
$faqs_query = isset( $args['faqs_query'] ) ? $args['faqs_query'] : null;
if ( ! $faqs_query || ! $faqs_query->have_posts() ) {
	return;
}
?>
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
