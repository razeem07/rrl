<?php
/**
 * Infinite scroll for the Fleet, Services, and Blog archives — replaces
 * the_posts_pagination() with a sentinel element that loads 6 more
 * items via AJAX as the user scrolls near the bottom (see the
 * IntersectionObserver block in assets/js/main.js).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cap these archives to 6 items per page instead of the site's default
 * reading-settings count, so the first load matches what infinite
 * scroll then adds to 6 at a time. Fleet/Services also get an explicit
 * menu_order sort here — without it the main query falls back to WP's
 * default (date DESC), while the AJAX handler below sorts by
 * menu_order to match the homepage teasers; two different orderings of
 * the same posts caused items to duplicate across "pages" and others
 * to never appear at all. `menu_order ID` breaks ties deterministically
 * (several posts share menu_order 0), which a bare 'menu_order' does
 * not guarantee across separate paginated queries.
 */
function royal_limo_infinite_scroll_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'fleet' ) || $query->is_tax( 'fleet_category' ) || $query->is_post_type_archive( 'service' ) ) {
		$query->set( 'posts_per_page', 6 );
		$query->set( 'orderby', 'menu_order ID' );
		$query->set( 'order', 'ASC' );
	} elseif ( $query->is_home() ) {
		$query->set( 'posts_per_page', 6 );
	}
}
add_action( 'pre_get_posts', 'royal_limo_infinite_scroll_posts_per_page' );

/**
 * Renders the sentinel element an archive template ends its grid with.
 * Returns nothing (and renders nothing) if the current main query only
 * has one page — no point loading a scroll-triggered spinner that will
 * never have anything to fetch.
 */
function royal_limo_infinite_scroll_sentinel( $post_type, $category = '' ) {
	global $wp_query;
	$max_pages = max( 1, (int) $wp_query->max_num_pages );
	if ( $max_pages <= 1 ) {
		return;
	}
	?>
	<div class="rl-infinite-loader" data-rl-infinite-sentinel data-post-type="<?php echo esc_attr( $post_type ); ?>" data-category="<?php echo esc_attr( $category ); ?>" data-paged="1" data-max-pages="<?php echo esc_attr( $max_pages ); ?>">
		<span class="rl-infinite-loader__spinner" aria-hidden="true"></span>
		<span class="screen-reader-text"><?php esc_html_e( 'Loading more…', 'royal-limo' ); ?></span>
	</div>
	<?php
}

/**
 * AJAX handler — returns the next batch of cards as rendered HTML
 * (same template-parts as the initial page load, so markup never
 * drifts) plus whether there's still more after this batch.
 */
function royal_limo_ajax_load_more() {
	check_ajax_referer( 'royal_limo_infinite_scroll', 'nonce' );

	$post_type = isset( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : '';
	$paged     = isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1;
	$category  = isset( $_POST['category'] ) ? sanitize_title( wp_unslash( $_POST['category'] ) ) : '';

	if ( ! in_array( $post_type, array( 'fleet', 'service', 'post' ), true ) ) {
		wp_send_json_error();
	}

	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => 6,
		'paged'          => $paged,
		'post_status'    => 'publish',
	);

	if ( 'fleet' === $post_type ) {
		// Must match the main query's ordering exactly (see the
		// pre_get_posts filter above) — otherwise "page 2" from here is
		// a different ordering of the same posts, not a continuation of
		// "page 1" from the main query, and items duplicate or vanish.
		$args['orderby'] = 'menu_order ID';
		$args['order']   = 'ASC';
		if ( $category ) {
			$args['tax_query'] = array( array(
				'taxonomy' => 'fleet_category',
				'field'    => 'slug',
				'terms'    => $category,
			) );
		}
	} elseif ( 'service' === $post_type ) {
		$args['orderby'] = 'menu_order ID';
		$args['order']   = 'ASC';
	} else {
		// Blog — same "Hello world!" sample-post exclusion used
		// everywhere else this post type is queried on the front end.
		$args['post__not_in'] = array( 1 );
	}

	$query = new WP_Query( $args );

	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			if ( 'fleet' === $post_type ) {
				get_template_part( 'template-parts/fleet-card' );
			} elseif ( 'service' === $post_type ) {
				get_template_part( 'template-parts/service-card' );
			} else {
				get_template_part( 'template-parts/blog-card' );
			}
		}
		wp_reset_postdata();
	}
	$html = ob_get_clean();

	wp_send_json_success( array(
		'html'     => $html,
		'has_more' => $paged < $query->max_num_pages,
	) );
}
add_action( 'wp_ajax_royal_limo_load_more', 'royal_limo_ajax_load_more' );
add_action( 'wp_ajax_nopriv_royal_limo_load_more', 'royal_limo_ajax_load_more' );
