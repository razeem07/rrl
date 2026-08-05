<?php
/**
 * Royal Limo theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROYAL_LIMO_VERSION', '1.0.0' );
define( 'ROYAL_LIMO_DIR', get_template_directory() );
define( 'ROYAL_LIMO_URI', get_template_directory_uri() );
define( 'ROYAL_LIMO_DEFAULT_PHONE', '+1 (555) 010-2026' );
define( 'ROYAL_LIMO_DEFAULT_HERO_SUBHEADING', 'Premium chauffeured limousines for airport transfers, weddings, corporate travel and nights out.' );
define( 'ROYAL_LIMO_DEFAULT_EYEBROW', 'Experience The Road In Style' );

/**
 * tel: href from a Customizer phone string — strips everything except
 * digits and a leading +, since get_theme_mod() values are free text.
 */
function royal_limo_phone_href( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Where "Get a Quote" / "Reserve" buttons should point: the page assigned
 * in Customizer > Contact Info > Booking Page, falling back to the
 * homepage's own quote form if none has been set yet.
 */
function royal_limo_booking_url() {
	$page_id = get_theme_mod( 'royal_limo_booking_page_id', 0 );
	return $page_id ? get_permalink( $page_id ) : home_url( '/#hero' );
}

/**
 * The hero's trust-stats row (e.g. "15+ Years of Service") — the same 3
 * values shown under the CTAs on every carousel slide, set once via
 * Customizer > Hero Section rather than per-banner.
 */
function royal_limo_hero_stats() {
	// Must match the 'default' values registered for these settings in
	// inc/customizer.php — get_theme_mod()'s fallback is independent of
	// the Customizer control's own registered default, so both need to
	// agree or an unsaved setting silently renders as empty.
	$defaults = array(
		1 => array( 'number' => '15', 'suffix' => '+', 'label' => __( 'Years of Service', 'royal-limo' ) ),
		2 => array( 'number' => '40', 'suffix' => '+', 'label' => __( 'Fleet Vehicles', 'royal-limo' ) ),
		3 => array( 'number' => '10', 'suffix' => 'K+', 'label' => __( 'Happy Clients', 'royal-limo' ) ),
	);

	$stats = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$number = get_theme_mod( "royal_limo_hero_stat{$i}_number", $defaults[ $i ]['number'] );
		$label  = get_theme_mod( "royal_limo_hero_stat{$i}_label", $defaults[ $i ]['label'] );
		if ( '' === $number && '' === $label ) {
			continue;
		}
		$stats[] = array(
			'number' => $number,
			'suffix' => get_theme_mod( "royal_limo_hero_stat{$i}_suffix", $defaults[ $i ]['suffix'] ),
			'label'  => $label,
		);
	}
	return $stats;
}

/**
 * The "Where We Serve" section content: eyebrow/heading/description plus
 * the 5 route stops. Fallback defaults here must match the 'default'
 * values registered in inc/customizer.php (see royal_limo_hero_stats()
 * above for why — get_theme_mod()'s fallback is independent of the
 * Customizer control's own default).
 */
function royal_limo_service_areas() {
	$location_defaults = array(
		1 => 'LAX Airport',
		2 => 'Downtown LA',
		3 => 'Beverly Hills',
		4 => 'Santa Monica',
		5 => 'Hollywood',
	);

	$locations = array();
	foreach ( $location_defaults as $i => $default_label ) {
		$label = get_theme_mod( "royal_limo_service_area_{$i}", $default_label );
		if ( '' !== $label ) {
			$locations[] = $label;
		}
	}

	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_service_areas_eyebrow', 'Where We Serve' ),
		'heading'     => get_theme_mod( 'royal_limo_service_areas_heading', 'Serving You Across Los Angeles' ),
		'description' => get_theme_mod( 'royal_limo_service_areas_description', 'Enjoy complimentary pickup and drop-off across Los Angeles and the Westside — including LAX, Downtown, Beverly Hills, Santa Monica, and Hollywood. Additional areas available by request.' ),
		'locations'   => $locations,
	);
}

/**
 * The homepage "About" section content: eyebrow/heading/description plus
 * the trust bar (heading/tagline/Google rating). Fallback defaults here
 * must match the 'default' values registered in inc/customizer.php (see
 * royal_limo_hero_stats() above for why).
 */
function royal_limo_about_section() {
	$default_description = "Royal Luxury Limousine is a premium chauffeured car service in Los Angeles, offering polished vehicles and professional drivers for customers who expect comfort, discretion, and a smooth ride from pickup to drop-off.\n\nFrom airport transfers to weddings and executive travel, every booking is handled by chauffeurs who know the city and treat every trip like it matters — because to you, it does.\n\nWith fast booking support, a meticulously maintained fleet, and fifteen years of trust behind it, Royal Luxury Limousine has built a reputation that matches Los Angeles' standard for style.";

	return array(
		'eyebrow'        => get_theme_mod( 'royal_limo_about_eyebrow', 'About Royal Luxury Limousine' ),
		'heading'        => get_theme_mod( 'royal_limo_about_heading', 'Experience Chauffeured Luxury in Los Angeles with Confidence and Style' ),
		'description'    => get_theme_mod( 'royal_limo_about_description', $default_description ),
		'bar_heading'    => get_theme_mod( 'royal_limo_about_bar_heading', 'Why Book With Us?' ),
		'bar_text'       => get_theme_mod( 'royal_limo_about_bar_text', 'Top-Rated Chauffeur Service in Los Angeles' ),
		'reviews_rating' => get_theme_mod( 'royal_limo_about_reviews_rating', '4.9' ),
		'reviews_count'  => get_theme_mod( 'royal_limo_about_reviews_count', '500+' ),
		'reviews_url'    => get_theme_mod( 'royal_limo_about_reviews_url', '' ),
	);
}

/**
 * The "Why Choose Us" section content: eyebrow/heading plus 4 fixed
 * reason columns (icon is fixed per slot in the template — only
 * heading/description are editable). Fallback defaults here must match
 * the 'default' values registered in inc/customizer.php.
 */
function royal_limo_why_choose_us() {
	$defaults = array(
		1 => array( 'heading' => 'Exclusive Fleet', 'description' => 'Hand-picked luxury sedans, SUVs, and limousines for every occasion.' ),
		2 => array( 'heading' => 'Licensed & Insured', 'description' => 'Every vehicle and chauffeur meets rigorous safety and licensing standards.' ),
		3 => array( 'heading' => 'Client First', 'description' => 'Your comfort, privacy, and satisfaction are always our top priority.' ),
		4 => array( 'heading' => 'Citywide Service', 'description' => 'Reliable pickup and drop-off availability across Los Angeles and beyond.' ),
	);

	$reasons = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$reasons[] = array(
			'heading'     => get_theme_mod( "royal_limo_why_us_{$i}_heading", $defaults[ $i ]['heading'] ),
			'description' => get_theme_mod( "royal_limo_why_us_{$i}_description", $defaults[ $i ]['description'] ),
		);
	}

	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_why_us_eyebrow', 'Why Choose Us' ),
		'heading'     => get_theme_mod( 'royal_limo_why_us_heading', 'The Royal Luxury Difference' ),
		'description' => get_theme_mod( 'royal_limo_why_us_description', 'What sets a Royal Luxury ride apart, from the moment you book to the moment you arrive.' ),
		'reasons'     => $reasons,
	);
}

/**
 * The "Video Showcase" section content. Fallback defaults here must
 * match the 'default' values registered in inc/customizer.php.
 */
function royal_limo_video_showcase() {
	$image_id = get_theme_mod( 'royal_limo_video_image', '' );

	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_video_eyebrow', 'Watch Video' ),
		'heading'     => get_theme_mod( 'royal_limo_video_heading', 'A Premium Chauffeured Experience' ),
		'description' => get_theme_mod( 'royal_limo_video_description', 'Discover how easy and comfortable it is to book with us. This short video gives you a quick look at our fleet, our chauffeurs, and how simple the booking process really is.' ),
		'check1'      => get_theme_mod( 'royal_limo_video_check1', 'See Our Fleet Quality and Comfort' ),
		'check2'      => get_theme_mod( 'royal_limo_video_check2', 'Learn About Easy Booking Steps' ),
		'image_url'   => $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '',
		'video_url'   => get_theme_mod( 'royal_limo_video_url', '' ),
	);
}

/**
 * Fleet section header (eyebrow/heading) — the cards themselves come
 * straight from the "fleet" CPT in the template.
 */
function royal_limo_fleet_section() {
	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_fleet_eyebrow', 'Our Collection' ),
		'heading'     => get_theme_mod( 'royal_limo_fleet_heading', 'Featured Luxury Fleet' ),
		'description' => get_theme_mod( 'royal_limo_fleet_description', 'Explore a hand-selected range of executive sedans, SUVs, and stretch limousines — each maintained to the highest standard and ready for your next reservation.' ),
	);
}

/**
 * Services section header (eyebrow/heading) — the cards themselves come
 * straight from the "service" CPT in the template.
 */
function royal_limo_services_section() {
	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_services_eyebrow', 'What We Offer' ),
		'heading'     => get_theme_mod( 'royal_limo_services_heading', 'Services Built Around You' ),
		'description' => get_theme_mod( 'royal_limo_services_description', 'From airport transfers to weddings and corporate travel, explore the chauffeured services designed to fit every occasion.' ),
	);
}

/**
 * Testimonials section header (eyebrow/heading) — the slides themselves
 * come straight from the "testimonial" CPT in the template.
 */
function royal_limo_testimonials_section() {
	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_testimonials_eyebrow', 'Testimonials' ),
		'heading'     => get_theme_mod( 'royal_limo_testimonials_heading', 'Trusted by Our Riders' ),
		'description' => get_theme_mod( 'royal_limo_testimonials_description', 'Real experiences from riders who trust us for reliable, comfortable, and professional chauffeured service.' ),
	);
}

/**
 * Blog section header (eyebrow/heading/description) — the cards
 * themselves come straight from core WordPress posts in the template.
 */
function royal_limo_blog_section() {
	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_blog_eyebrow', 'Latest Blogs' ),
		'heading'     => get_theme_mod( 'royal_limo_blog_heading', 'Insights and Stories That Drive' ),
		'description' => get_theme_mod( 'royal_limo_blog_description', 'Discover expert tips, helpful advice, and inspiring stories designed to make every journey smarter, smoother, and more enjoyable.' ),
	);
}

/**
 * FAQ section header (eyebrow/heading) — the accordion items themselves
 * come straight from the "faq" CPT in the template.
 */
function royal_limo_faq_section() {
	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_faq_eyebrow', 'Good To Know' ),
		'heading'     => get_theme_mod( 'royal_limo_faq_heading', 'Frequently Asked Questions' ),
		'description' => get_theme_mod( 'royal_limo_faq_description', 'Answers to the questions we hear most from riders booking a chauffeured trip with us.' ),
	);
}

/**
 * Booking CTA banner — background photo, heading/description/checklist.
 * Content set via Customizer > Booking CTA Section.
 */
function royal_limo_booking_cta() {
	$image_id = get_theme_mod( 'royal_limo_booking_cta_image', '' );

	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_booking_cta_eyebrow', 'Contact Today' ),
		'heading'     => get_theme_mod( 'royal_limo_booking_cta_heading', 'Connect With Us for Booking Support Today!' ),
		'description' => get_theme_mod( 'royal_limo_booking_cta_description', 'Our friendly and professional team is ready to help you plan the perfect ride for any occasion — whether you have questions, need assistance with booking, or want to confirm the details of an upcoming trip.' ),
		'check1'      => get_theme_mod( 'royal_limo_booking_cta_check1', 'Speak With a Reservations Specialist' ),
		'check2'      => get_theme_mod( 'royal_limo_booking_cta_check2', 'Get Real-Time Booking Confirmation' ),
		'image_url'   => $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '',
	);
}

/**
 * Star rating markup (filled/empty SVG stars), shared by testimonials
 * and the About section's reviews badge.
 */
function royal_limo_star_rating( $rating ) {
	$rating = max( 1, min( 5, (int) $rating ) );
	$out    = '<span class="rl-stars" aria-label="' . esc_attr( sprintf( _n( '%d out of 5 stars', '%d out of 5 stars', $rating, 'royal-limo' ), $rating ) ) . '">';
	for ( $i = 1; $i <= 5; $i++ ) {
		$out .= $i <= $rating
			? '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>'
			: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
	}
	return $out . '</span>';
}

/**
 * Theme setup.
 */
function royal_limo_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'royal-limo' ),
		'footer'  => __( 'Footer Menu', 'royal-limo' ),
	) );

	add_image_size( 'fleet-card', 640, 420, true );
	add_image_size( 'fleet-full', 1280, 800, true );
	add_image_size( 'service-card', 640, 800, true );
	add_image_size( 'blog-card', 640, 460, true );
}
add_action( 'after_setup_theme', 'royal_limo_setup' );

/**
 * Enqueue styles/scripts. Plain files, no build step, loaded in dependency order.
 */
function royal_limo_assets() {
	$css_dir = ROYAL_LIMO_URI . '/assets/css/';
	$js_dir  = ROYAL_LIMO_URI . '/assets/js/';

	wp_enqueue_style( 'royal-limo-base', $css_dir . 'base.css', array(), ROYAL_LIMO_VERSION );
	wp_enqueue_style( 'royal-limo-glass', $css_dir . 'glass.css', array( 'royal-limo-base' ), ROYAL_LIMO_VERSION );
	wp_enqueue_style( 'royal-limo-neumorph', $css_dir . 'neumorph.css', array( 'royal-limo-base' ), ROYAL_LIMO_VERSION );
	wp_enqueue_style( 'royal-limo-layout', $css_dir . 'layout.css', array( 'royal-limo-base' ), ROYAL_LIMO_VERSION );
	wp_enqueue_style( 'royal-limo-animations-css', $css_dir . 'animations.css', array( 'royal-limo-layout' ), ROYAL_LIMO_VERSION );
	wp_enqueue_style( 'royal-limo-style', get_stylesheet_uri(), array( 'royal-limo-animations-css' ), ROYAL_LIMO_VERSION );

	// GSAP from CDN (jsdelivr), core + ScrollTrigger only.
	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '3.12.5', true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', array( 'gsap' ), '3.12.5', true );

	wp_enqueue_script( 'royal-limo-main', $js_dir . 'main.js', array(), ROYAL_LIMO_VERSION, true );
	wp_enqueue_script( 'royal-limo-animations', $js_dir . 'animations.js', array( 'gsap', 'gsap-scrolltrigger' ), ROYAL_LIMO_VERSION, true );

	wp_localize_script( 'royal-limo-main', 'royalLimoData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'royal_limo_assets' );

/**
 * Defer non-critical scripts (GSAP + our own JS) for faster first paint.
 */
function royal_limo_defer_scripts( $tag, $handle ) {
	$defer_handles = array( 'gsap', 'gsap-scrolltrigger', 'royal-limo-main', 'royal-limo-animations' );
	if ( in_array( $handle, $defer_handles, true ) && false === strpos( $tag, 'defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'royal_limo_defer_scripts', 10, 2 );

/**
 * Native block-editor sidebar panels for the Fleet/Testimonial custom
 * fields (assets/js/admin-panels.js), replacing the old classic meta
 * boxes. Only loaded on those two post types' editor screens.
 */
function royal_limo_admin_assets( $hook ) {
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'fleet', 'testimonial', 'banner' ), true ) ) {
		return;
	}

	wp_enqueue_script(
		'royal-limo-admin-panels',
		ROYAL_LIMO_URI . '/assets/js/admin-panels.js',
		array( 'wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n' ),
		ROYAL_LIMO_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'royal_limo_admin_assets' );

require ROYAL_LIMO_DIR . '/inc/custom-post-types.php';
require ROYAL_LIMO_DIR . '/inc/customizer.php';
require ROYAL_LIMO_DIR . '/inc/cf7-integration.php';
require ROYAL_LIMO_DIR . '/inc/quote-form.php';
