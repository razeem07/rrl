<?php
/**
 * Custom post types (no ACF): Fleet (vehicles), Service, Testimonial.
 * Custom fields are registered as REST-visible post meta and edited via a
 * native block-editor sidebar panel (assets/js/admin-panels.js) instead of
 * classic add_meta_box() boxes, so the whole editing screen — including
 * these fields — feels like one consistent modern editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The 'custom-fields' post-type support flag is required for these CPTs'
 * registered meta to be exposed via the REST API (which the sidebar
 * panels in assets/js/admin-panels.js depend on) — but that flag also
 * adds WordPress's generic "Custom Fields" raw key/value meta box, which
 * would otherwise duplicate/clutter the purpose-built panel. Hide just
 * that one classic box, on these post types only.
 */
function royal_limo_hide_classic_custom_fields_box() {
	foreach ( array( 'fleet', 'testimonial', 'banner', 'service', 'team_member', 'page' ) as $post_type ) {
		remove_meta_box( 'postcustom', $post_type, 'normal' );
	}
}
add_action( 'do_meta_boxes', 'royal_limo_hide_classic_custom_fields_box', 20 );

/* ==========================================================================
   Fleet
   ========================================================================== */

function royal_limo_register_fleet_cpt() {
	register_post_type( 'fleet', array(
		'labels' => array(
			'name'          => __( 'Fleet', 'royal-limo' ),
			'singular_name' => __( 'Vehicle', 'royal-limo' ),
			'add_new_item'  => __( 'Add New Vehicle', 'royal-limo' ),
			'edit_item'     => __( 'Edit Vehicle', 'royal-limo' ),
			'all_items'     => __( 'Fleet', 'royal-limo' ),
		),
		'public'       => true,
		'has_archive'  => 'fleet',
		'rewrite'      => array( 'slug' => 'fleet' ),
		'menu_icon'    => 'dashicons-car',
		// 'custom-fields' is required here even though we hide its classic
		// UI box (see royal_limo_hide_classic_custom_fields_box below) —
		// WordPress's REST API only exposes registered post meta for a
		// post type that declares this support, regardless of each
		// field's own show_in_rest setting. Without it, the sidebar
		// panel's meta reads/writes silently stop working.
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'royal_limo_register_fleet_cpt' );

/**
 * REST-visible meta so the block editor's sidebar panel (see
 * assets/js/admin-panels.js) can read/write these without a classic
 * meta box. Front-end templates keep using plain get_post_meta().
 */
function royal_limo_register_fleet_meta() {
	$auth_callback = function () {
		return current_user_can( 'edit_posts' );
	};

	register_post_meta( 'fleet', '_fleet_seating_capacity', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'integer',
		'sanitize_callback' => 'absint',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'fleet', '_fleet_luggage_capacity', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'integer',
		'sanitize_callback' => 'absint',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'fleet', '_fleet_pax', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'integer',
		'sanitize_callback' => 'absint',
		'auth_callback'     => $auth_callback,
	) );
	// sanitize_callback is called as apply_filters( ..., $value, $meta_key,
	// $object_type, $object_subtype ) — 4 args. floatval() is a strict-
	// arity PHP builtin (only accepts 1 argument) and PHP 8+ throws an
	// uncaught ArgumentCountError when it's passed the extra 3, crashing
	// the whole REST update with a 500 ("update failed" in the editor).
	// absint() elsewhere in this file is a WP-defined userland function,
	// so it silently ignores the extra args and doesn't have this problem
	// — only bare PHP-internal function names as callbacks are at risk.
	$sanitize_price = function ( $value ) {
		return floatval( $value );
	};
	register_post_meta( 'fleet', '_fleet_price_full_day', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'number',
		'sanitize_callback' => $sanitize_price,
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'fleet', '_fleet_price_half_day', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'number',
		'sanitize_callback' => $sanitize_price,
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'fleet', '_fleet_specs', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => $auth_callback,
	) );

	// Gallery for the single vehicle page's image carousel — separate
	// from the featured image (still used for card thumbnails
	// elsewhere). An empty gallery just means the carousel falls back
	// to the featured image alone.
	register_post_meta( 'fleet', '_fleet_gallery', array(
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => $auth_callback,
		'show_in_rest'  => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array( 'type' => 'string' ),
			),
		),
		'sanitize_callback' => function ( $value ) {
			if ( ! is_array( $value ) ) {
				return array();
			}
			return array_values( array_filter( array_map( 'esc_url_raw', $value ) ) );
		},
	) );

	// Per-vehicle FAQs — same array-of-{question,answer} shape as the
	// Service CPT's _service_faqs, deliberately separate from the
	// sitewide "faq" CPT (a vehicle's FAQs are specific to it).
	register_post_meta( 'fleet', '_fleet_faqs', array(
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => $auth_callback,
		'show_in_rest'  => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'question' => array( 'type' => 'string' ),
						'answer'   => array( 'type' => 'string' ),
					),
				),
			),
		),
		'sanitize_callback' => 'royal_limo_sanitize_faq_pairs',
	) );
}
add_action( 'init', 'royal_limo_register_fleet_meta' );

/**
 * Shared sanitizer for array-of-{question,answer} FAQ meta — used by
 * both the Fleet and Service CPTs' _*_faqs fields.
 */
function royal_limo_sanitize_faq_pairs( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}
	$clean = array();
	foreach ( $value as $pair ) {
		$question = isset( $pair['question'] ) ? sanitize_text_field( $pair['question'] ) : '';
		$answer   = isset( $pair['answer'] ) ? sanitize_textarea_field( $pair['answer'] ) : '';
		if ( '' !== $question ) {
			$clean[] = array( 'question' => $question, 'answer' => $answer );
		}
	}
	return $clean;
}

/**
 * Fleet Category — a normal hierarchical taxonomy (like WP's built-in
 * Categories) so vehicles can be grouped (Sedans, SUVs, Limousines...)
 * and filtered on the homepage teaser, /fleet/ archive, and per-category
 * archive pages.
 *
 * The rewrite slug is "fleet-category" (a sibling of /fleet/), NOT
 * nested as "fleet/category" — nesting a taxonomy directly under a
 * CPT's own slug collides with WordPress's auto-generated attachment-
 * permalink rule for that CPT (fleet/[^/]+/([^/]+)/?$), which sits
 * above the taxonomy's rule in priority and swallows the request
 * first, 404ing every category archive. Confirmed by dumping the
 * actual registered rewrite_rules option after hitting this.
 */
function royal_limo_register_fleet_category_taxonomy() {
	register_taxonomy( 'fleet_category', 'fleet', array(
		'labels' => array(
			'name'          => __( 'Fleet Categories', 'royal-limo' ),
			'singular_name' => __( 'Fleet Category', 'royal-limo' ),
			'add_new_item'  => __( 'Add New Fleet Category', 'royal-limo' ),
			'edit_item'     => __( 'Edit Fleet Category', 'royal-limo' ),
			'all_items'     => __( 'Fleet Categories', 'royal-limo' ),
			'menu_name'     => __( 'Categories', 'royal-limo' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => false,
		// Required for the block editor's category panel on the Fleet
		// edit screen (same reason custom post meta needs show_in_rest —
		// see the notes on the CPTs below).
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'fleet-category' ),
	) );
}
add_action( 'init', 'royal_limo_register_fleet_category_taxonomy' );

/* ==========================================================================
   Service
   ========================================================================== */

function royal_limo_register_service_cpt() {
	register_post_type( 'service', array(
		'labels' => array(
			'name'          => __( 'Services', 'royal-limo' ),
			'singular_name' => __( 'Service', 'royal-limo' ),
			'add_new_item'  => __( 'Add New Service', 'royal-limo' ),
			'edit_item'     => __( 'Edit Service', 'royal-limo' ),
			'all_items'     => __( 'Services', 'royal-limo' ),
		),
		'public'       => true,
		'has_archive'  => 'services',
		'rewrite'      => array( 'slug' => 'services' ),
		'menu_icon'    => 'dashicons-star-filled',
		// 'custom-fields' required for REST meta exposure — see note on
		// the Fleet CPT above; classic box hidden via the hook below.
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'royal_limo_register_service_cpt' );

/**
 * REST-visible meta for the single service page's "Why Choose This
 * Service?" checklist (up to 4 bullets) and "What's Included" feature
 * highlights (3 cards, fixed icon per slot — see single-service.php).
 * Blank fields just don't render; a service isn't required to fill in
 * either group.
 */
function royal_limo_register_service_meta() {
	$auth_callback = function () {
		return current_user_can( 'edit_posts' );
	};

	// Section headings — editable per service (defaults match the
	// template's previous hardcoded strings, applied in single-
	// service.php since they include the service's own title).
	foreach ( array(
		'_service_benefits_heading',
		'_service_features_heading',
		'_service_process_eyebrow',
		'_service_process_heading',
		'_service_faq_eyebrow',
		'_service_faq_heading',
	) as $heading_key ) {
		register_post_meta( 'service', $heading_key, array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => $auth_callback,
		) );
	}

	for ( $i = 1; $i <= 4; $i++ ) {
		register_post_meta( 'service', "_service_benefit{$i}", array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => $auth_callback,
		) );
	}

	for ( $i = 1; $i <= 3; $i++ ) {
		register_post_meta( 'service', "_service_feature{$i}_title", array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => $auth_callback,
		) );
	}

	// Banner image for the page-header banner — deliberately separate
	// from the featured image (which is used for the in-content photo
	// further down); without this, both spots showed the same picture.
	register_post_meta( 'service', '_service_banner_image', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'     => $auth_callback,
	) );

	// "Our Booking Process" steps — per service (a wedding booking and
	// an airport transfer don't necessarily work the same way), not a
	// single shared Customizer block. Blank steps just don't render.
	for ( $i = 1; $i <= 3; $i++ ) {
		register_post_meta( 'service', "_service_process_step{$i}_title", array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => $auth_callback,
		) );
		register_post_meta( 'service', "_service_process_step{$i}_description", array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
			'auth_callback'     => $auth_callback,
		) );
	}

	// Per-service FAQs — a single array-type field (unlimited pairs, via
	// the repeater in the block editor sidebar panel) rather than a
	// fixed number of _service_faqN_question/_answer slots. Deliberately
	// separate from the sitewide "faq" CPT used on the homepage — a
	// service's FAQs are specific to it (e.g. "Do you provide a car
	// seat?" only makes sense under a particular service).
	register_post_meta( 'service', '_service_faqs', array(
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => $auth_callback,
		'show_in_rest'  => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'question' => array( 'type' => 'string' ),
						'answer'   => array( 'type' => 'string' ),
					),
				),
			),
		),
		'sanitize_callback' => 'royal_limo_sanitize_faq_pairs',
	) );

	// "Additional Content" — free-form rich text (own heading typed
	// inline via the Format dropdown, paragraphs, images, etc.), shown
	// between the booking process and FAQ sections. Edited on its own
	// dedicated admin page (see royal_limo_service_content_admin_page
	// below), NOT a meta box on the Service post's own edit screen.
	//
	// This went through two prior attempts that both failed in real
	// use despite testing fine in isolation: (1) a classic wp_editor()
	// meta box — the block editor injects classic meta boxes
	// asynchronously via JS, and a TinyMCE instance dropped into that
	// async-injected DOM node is a known source of "looks right, shows
	// content, but doesn't actually work" bugs tied to init timing;
	// (2) the block editor's native RichText control — reliable, but
	// not the actual rich toolbar experience that was asked for. A
	// plain wp_editor() on its own ordinary wp-admin page (not part of
	// post.php/the block editor at all) sidesteps the async-injection
	// timing issue entirely — this is the same mechanism WordPress
	// core has used for things like the Settings > General page for
	// over a decade without this failure class.
	register_post_meta( 'service', '_service_additional_content', array(
		'show_in_rest'      => false,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'wp_kses_post',
		'auth_callback'     => $auth_callback,
	) );
}
add_action( 'init', 'royal_limo_register_service_meta' );

/**
 * "Additional Content" admin page — under Services > Additional
 * Content. Pick a service, edit its rich-text content in a plain
 * wp_editor() on an ordinary admin page, save via normal form POST.
 */
function royal_limo_add_service_content_admin_page() {
	$hook = add_submenu_page(
		'edit.php?post_type=service',
		__( 'Additional Content', 'royal-limo' ),
		__( 'Additional Content', 'royal-limo' ),
		'edit_posts',
		'royal-limo-service-content',
		'royal_limo_render_service_content_admin_page'
	);
	// wp_editor()'s "Add Media" button needs wp.media's JS loaded to
	// actually do anything when clicked — WordPress auto-loads that on
	// normal post edit screens, but NOT on custom admin pages like this
	// one, so the button would otherwise just sit there inert.
	add_action( 'admin_enqueue_scripts', function ( $current_hook ) use ( $hook ) {
		if ( $current_hook === $hook ) {
			wp_enqueue_media();
		}
	} );
}
add_action( 'admin_menu', 'royal_limo_add_service_content_admin_page' );

function royal_limo_render_service_content_admin_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'royal-limo' ) );
	}

	$services = get_posts( array(
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );

	$selected_id = isset( $_GET['service_id'] ) ? absint( $_GET['service_id'] ) : 0;
	$notice      = '';

	if ( isset( $_POST['royal_limo_service_content_nonce'] )
		&& wp_verify_nonce( $_POST['royal_limo_service_content_nonce'], 'royal_limo_service_content_save' )
		&& isset( $_POST['royal_limo_service_id'] ) ) {

		$save_id = absint( $_POST['royal_limo_service_id'] );
		if ( $save_id && current_user_can( 'edit_post', $save_id ) && 'service' === get_post_type( $save_id ) ) {
			$content = isset( $_POST['royal_limo_service_additional_content'] ) ? wp_kses_post( wp_unslash( $_POST['royal_limo_service_additional_content'] ) ) : '';
			update_post_meta( $save_id, '_service_additional_content', $content );
			$selected_id = $save_id;
			$notice      = __( 'Saved.', 'royal-limo' );
		}
	}

	$current_content = $selected_id ? get_post_meta( $selected_id, '_service_additional_content', true ) : '';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Additional Content', 'royal-limo' ); ?></h1>
		<p><?php esc_html_e( 'Extra rich-text content shown on a service\'s page, right before its FAQ section.', 'royal-limo' ); ?></p>

		<?php if ( $notice ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>

		<form method="get" style="margin: 1em 0;">
			<input type="hidden" name="post_type" value="service">
			<input type="hidden" name="page" value="royal-limo-service-content">
			<label for="royal-limo-service-select"><strong><?php esc_html_e( 'Service:', 'royal-limo' ); ?></strong></label>
			<select name="service_id" id="royal-limo-service-select" onchange="this.form.submit()">
				<option value=""><?php esc_html_e( '— Select a service —', 'royal-limo' ); ?></option>
				<?php foreach ( $services as $service ) : ?>
					<option value="<?php echo esc_attr( $service->ID ); ?>" <?php selected( $selected_id, $service->ID ); ?>>
						<?php echo esc_html( $service->post_title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<noscript><button type="submit" class="button"><?php esc_html_e( 'Go', 'royal-limo' ); ?></button></noscript>
		</form>

		<?php if ( $selected_id ) : ?>
			<form method="post">
				<?php wp_nonce_field( 'royal_limo_service_content_save', 'royal_limo_service_content_nonce' ); ?>
				<input type="hidden" name="royal_limo_service_id" value="<?php echo esc_attr( $selected_id ); ?>">
				<?php
				wp_editor( $current_content, 'royal_limo_service_additional_content', array(
					'textarea_name' => 'royal_limo_service_additional_content',
					'textarea_rows' => 16,
					'media_buttons' => true,
					// Kitchen Sink expanded by default (toolbar2
					// normally hides behind the "more" toggle) so the
					// full toolbar is visible right away.
					'tinymce'       => array(
						'toolbar1' => 'formatselect,bold,italic,strikethrough,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,dfw,wp_adv',
						'toolbar2' => 'underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
					),
				) );
				?>
				<p class="submit">
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Additional Content', 'royal-limo' ); ?></button>
					<a href="<?php echo esc_url( get_edit_post_link( $selected_id ) ); ?>" class="button"><?php esc_html_e( 'Edit This Service', 'royal-limo' ); ?></a>
				</p>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/* ==========================================================================
   Testimonial (admin-only content type, no public single/archive)
   ========================================================================== */

function royal_limo_register_testimonial_cpt() {
	register_post_type( 'testimonial', array(
		'labels' => array(
			'name'          => __( 'Testimonials', 'royal-limo' ),
			'singular_name' => __( 'Testimonial', 'royal-limo' ),
			'add_new_item'  => __( 'Add New Testimonial', 'royal-limo' ),
			'edit_item'     => __( 'Edit Testimonial', 'royal-limo' ),
			'all_items'     => __( 'Testimonials', 'royal-limo' ),
		),
		'public'       => false,
		'show_ui'      => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-format-quote',
		// 'custom-fields' required for REST meta exposure — see note on
		// the Fleet CPT above; classic box hidden via the hook below.
		'supports'     => array( 'title', 'editor', 'custom-fields' ),
		// show_in_rest is required for the block editor AND for the
		// sidebar panel's meta read/write to work on this post type.
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'royal_limo_register_testimonial_cpt' );

function royal_limo_register_testimonial_meta() {
	register_post_meta( 'testimonial', '_testimonial_rating', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'integer',
		'default'           => 5,
		'sanitize_callback' => function ( $value ) {
			return max( 1, min( 5, absint( $value ) ) );
		},
		'auth_callback' => function () {
			return current_user_can( 'edit_posts' );
		},
	) );
}
add_action( 'init', 'royal_limo_register_testimonial_meta' );

/* ==========================================================================
   Banner (homepage hero carousel slides — admin-only content type)
   ========================================================================== */

function royal_limo_register_banner_cpt() {
	register_post_type( 'banner', array(
		'labels' => array(
			'name'          => __( 'Banners', 'royal-limo' ),
			'singular_name' => __( 'Banner', 'royal-limo' ),
			'add_new_item'  => __( 'Add New Banner', 'royal-limo' ),
			'edit_item'     => __( 'Edit Banner', 'royal-limo' ),
			'all_items'     => __( 'Banners', 'royal-limo' ),
		),
		'public'       => false,
		'show_ui'      => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-images-alt2',
		// 'editor' is required for the block editor to load at all — the
		// native title/content/excerpt fields it enables aren't actually
		// used for the visible slide content though (see the "enter title
		// here" filter below): Hero Title/Description/CTAs all live in the
		// custom sidebar panel as REST meta instead, edited together in
		// one place with full control over labels/sizing/helper text. The
		// native title is kept only as an internal admin-list label.
		// 'custom-fields' required for REST meta exposure — see note on
		// the Fleet CPT above; classic box hidden via the hook below.
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'royal_limo_register_banner_cpt' );

function royal_limo_banner_title_placeholder( $title, $post ) {
	if ( 'banner' === get_post_type( $post ) ) {
		return __( 'Internal admin label (not shown on site — set Hero Title in the panel below)', 'royal-limo' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'royal_limo_banner_title_placeholder', 10, 2 );

function royal_limo_register_banner_meta() {
	$auth_callback = function () {
		return current_user_can( 'edit_posts' );
	};

	register_post_meta( 'banner', '_banner_eyebrow', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'banner', '_banner_hero_title', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'banner', '_banner_hero_description', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_textarea_field',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'banner', '_banner_cta1_label', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'banner', '_banner_cta1_url', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'banner', '_banner_cta2_label', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'banner', '_banner_cta2_url', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'     => $auth_callback,
	) );
}
add_action( 'init', 'royal_limo_register_banner_meta' );

/* ==========================================================================
   Brand Logo (marquee strip in the Video Showcase section — admin-only)
   ========================================================================== */

function royal_limo_register_brand_logo_cpt() {
	register_post_type( 'brand_logo', array(
		'labels' => array(
			'name'          => __( 'Brand Logos', 'royal-limo' ),
			'singular_name' => __( 'Brand Logo', 'royal-limo' ),
			'add_new_item'  => __( 'Add New Brand Logo', 'royal-limo' ),
			'edit_item'     => __( 'Edit Brand Logo', 'royal-limo' ),
			'all_items'     => __( 'Brand Logos', 'royal-limo' ),
		),
		'public'      => false,
		'show_ui'     => true,
		'has_archive' => false,
		'menu_icon'   => 'dashicons-align-center',
		// 'editor' is required for the block editor to load at all (same
		// reason as the Banner CPT above) — no meta/REST needed here
		// though, it's just a title (used as the logo's alt text) plus a
		// featured image (the logo itself), so no sidebar panel required.
		'supports'    => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
	) );
}
add_action( 'init', 'royal_limo_register_brand_logo_cpt' );

/* ==========================================================================
   Team Member (About page "Key Persons" section — admin-only content type)
   ========================================================================== */

function royal_limo_register_team_member_cpt() {
	register_post_type( 'team_member', array(
		'labels' => array(
			'name'          => __( 'Team Members', 'royal-limo' ),
			'singular_name' => __( 'Team Member', 'royal-limo' ),
			'add_new_item'  => __( 'Add New Team Member', 'royal-limo' ),
			'edit_item'     => __( 'Edit Team Member', 'royal-limo' ),
			'all_items'     => __( 'Team Members', 'royal-limo' ),
		),
		'public'       => false,
		'show_ui'      => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-groups',
		// Title = person's name. 'editor' required for the block editor to
		// load at all (same reason as the other admin-only CPTs above).
		// 'custom-fields' required for REST meta exposure (role + social
		// links, edited via the sidebar panel).
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'royal_limo_register_team_member_cpt' );

function royal_limo_team_member_title_placeholder( $title, $post ) {
	if ( 'team_member' === get_post_type( $post ) ) {
		return __( 'Full Name', 'royal-limo' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'royal_limo_team_member_title_placeholder', 10, 2 );

function royal_limo_register_team_member_meta() {
	$auth_callback = function () {
		return current_user_can( 'edit_posts' );
	};

	register_post_meta( 'team_member', '_team_role', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => $auth_callback,
	) );
	foreach ( array( 'facebook', 'twitter', 'linkedin', 'instagram' ) as $network ) {
		register_post_meta( 'team_member', "_team_{$network}", array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'auth_callback'     => $auth_callback,
		) );
	}
}
add_action( 'init', 'royal_limo_register_team_member_meta' );

/* ==========================================================================
   FAQ (accordion in the FAQ section — admin-only content type)
   ========================================================================== */

function royal_limo_register_faq_cpt() {
	register_post_type( 'faq', array(
		'labels' => array(
			'name'          => __( 'FAQs', 'royal-limo' ),
			'singular_name' => __( 'FAQ', 'royal-limo' ),
			'add_new_item'  => __( 'Add New FAQ', 'royal-limo' ),
			'edit_item'     => __( 'Edit FAQ', 'royal-limo' ),
			'all_items'     => __( 'FAQs', 'royal-limo' ),
		),
		'public'      => false,
		'show_ui'     => true,
		'has_archive' => false,
		'menu_icon'   => 'dashicons-editor-help',
		// Title is the question, editor content is the answer — no meta/
		// REST needed, same reasoning as the Brand Logo CPT above.
		'supports'    => array( 'title', 'editor', 'page-attributes' ),
	) );
}
add_action( 'init', 'royal_limo_register_faq_cpt' );

function royal_limo_faq_title_placeholder( $title, $post ) {
	if ( 'faq' === get_post_type( $post ) ) {
		return __( 'Question', 'royal-limo' );
	}
	return $title;
}

/* ==========================================================================
   About page content (page-about.php) — every section on this page is
   editable from the page's own edit screen (sidebar panel in
   admin-panels.js) instead of the Customizer. Registered on the 'page'
   post type generally (simplest — 'page' has no per-template meta
   scoping), but the sidebar panel itself only renders these fields
   when editing a page using the "About Us" template, and no other page
   template reads them, so it's harmless for every other page on the
   site. Deliberately separate from the homepage's "About"/"Why Choose
   Us" Customizer sections (royal_limo_about_section() /
   royal_limo_why_choose_us() in functions.php) rather than sharing
   them — editing this page's content must not also change the
   homepage's.
   ========================================================================== */

function royal_limo_register_about_page_meta() {
	$auth_callback = function () {
		return current_user_can( 'edit_pages' );
	};

	$text_fields = array(
		// Banner.
		'_about_banner_image',
		// About Us intro.
		'_about_eyebrow',
		'_about_heading',
		'_about_image',
		'_about_bar_heading',
		'_about_bar_text',
		'_about_reviews_rating',
		'_about_reviews_count',
		'_about_reviews_url',
		// Our Approach.
		'_approach_eyebrow',
		'_approach_heading',
		'_approach_image',
		'_approach_mission_heading',
		'_approach_mission_point1',
		'_approach_mission_point2',
		'_approach_vision_heading',
		'_approach_vision_point1',
		'_approach_vision_point2',
		// Why Choose Us.
		'_why_us_eyebrow',
		'_why_us_heading',
		// Key Persons.
		'_team_eyebrow',
		'_team_heading',
		// What We Do.
		'_wwd_eyebrow',
		'_wwd_heading',
		'_wwd_image',
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		$text_fields[] = "_why_us_{$i}_heading";
		$text_fields[] = "_why_us_{$i}_description";
		$text_fields[] = "_wwd_{$i}_heading";
		$text_fields[] = "_wwd_{$i}_description";
	}

	// URL-type fields get esc_url_raw; the image fields are included
	// here too since (same note as elsewhere in this theme)
	// WP_Customize_Image_Control-style pickers store a URL, and the
	// MediaUpload equivalent in the sidebar panel does the same.
	$url_fields = array( '_about_banner_image', '_about_image', '_about_reviews_url', '_approach_image', '_wwd_image' );

	foreach ( $text_fields as $key ) {
		register_post_meta( 'page', $key, array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => in_array( $key, $url_fields, true ) ? 'esc_url_raw' : 'sanitize_text_field',
			'auth_callback'     => $auth_callback,
		) );
	}

	// These "description" fields are longer-form than the rest —
	// sanitize as textarea (preserves line breaks) instead of the
	// single-line text_field used for everything else above.
	foreach ( array( '_about_description', '_why_us_description', '_wwd_description', '_team_description' ) as $key ) {
		register_post_meta( 'page', $key, array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
			'auth_callback'     => $auth_callback,
		) );
	}
}
add_action( 'init', 'royal_limo_register_about_page_meta' );

/* ==========================================================================
   Contact page banner (page-contact.php) — a single image field, edited
   directly on the page instead of the Customizer, since the Contact
   page is a real editable page (unlike the Fleet/Services/Blog listing
   pages above, which are CPT archives/template slots with no page-
   editor screen of their own to attach a field to).
   ========================================================================== */

function royal_limo_register_contact_page_meta() {
	register_post_meta( 'page', '_contact_banner_image', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'     => function () {
			return current_user_can( 'edit_pages' );
		},
	) );
}
add_action( 'init', 'royal_limo_register_contact_page_meta' );
add_filter( 'enter_title_here', 'royal_limo_faq_title_placeholder', 10, 2 );

/* ==========================================================================
   Blog post banner (single.php) — a dedicated field, deliberately
   separate from the featured image (which also serves as the blog
   listing card thumbnail and the "Recent Posts" sidebar thumbnail —
   both far narrower/taller crops than a full-bleed page-header banner
   needs, so one image can't serve both well).
   ========================================================================== */

function royal_limo_register_post_banner_meta() {
	register_post_meta( 'post', '_post_banner_image', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	) );
}
add_action( 'init', 'royal_limo_register_post_banner_meta' );

/**
 * Manual ordering for Fleet and Service admin list tables. Both post types
 * support 'page-attributes', which already gives WordPress core's Quick
 * Edit an "Order" field — this just surfaces that value as a sortable
 * column and makes it the default sort, so the admin list matches what
 * visitors see on the front end (both are queried by menu_order there).
 */
function royal_limo_add_order_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $label ) {
		$new_columns[ $key ] = $label;
		if ( 'title' === $key ) {
			$new_columns['rl_order'] = __( 'Order', 'royal-limo' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_fleet_posts_columns', 'royal_limo_add_order_column' );
add_filter( 'manage_service_posts_columns', 'royal_limo_add_order_column' );

function royal_limo_render_order_column( $column, $post_id ) {
	if ( 'rl_order' === $column ) {
		echo esc_html( get_post_field( 'menu_order', $post_id ) );
	}
}
add_action( 'manage_fleet_posts_custom_column', 'royal_limo_render_order_column', 10, 2 );
add_action( 'manage_service_posts_custom_column', 'royal_limo_render_order_column', 10, 2 );

function royal_limo_order_column_sortable( $columns ) {
	$columns['rl_order'] = 'menu_order';
	return $columns;
}
add_filter( 'manage_edit-fleet_sortable_columns', 'royal_limo_order_column_sortable' );
add_filter( 'manage_edit-service_sortable_columns', 'royal_limo_order_column_sortable' );

function royal_limo_default_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( ! in_array( $query->get( 'post_type' ), array( 'fleet', 'service' ), true ) ) {
		return;
	}
	if ( ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'royal_limo_default_admin_order' );
