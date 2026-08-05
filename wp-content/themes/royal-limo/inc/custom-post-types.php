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
	foreach ( array( 'fleet', 'testimonial', 'banner' ) as $post_type ) {
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
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
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
	register_post_meta( 'fleet', '_fleet_price_full_day', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'number',
		'sanitize_callback' => 'floatval',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'fleet', '_fleet_price_half_day', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'number',
		'sanitize_callback' => 'floatval',
		'auth_callback'     => $auth_callback,
	) );
	register_post_meta( 'fleet', '_fleet_specs', array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => $auth_callback,
	) );
}
add_action( 'init', 'royal_limo_register_fleet_meta' );

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
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'royal_limo_register_service_cpt' );

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
add_filter( 'enter_title_here', 'royal_limo_faq_title_placeholder', 10, 2 );
