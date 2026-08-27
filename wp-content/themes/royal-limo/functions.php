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
 * wa.me click-to-chat link for the floating WhatsApp button — separate
 * number from the Phone Number field above (Customizer > Contact Info
 * > WhatsApp Number), with the pre-filled message URL-encoded. wa.me
 * wants digits only (no leading +), unlike the tel: href above.
 */
function royal_limo_whatsapp_url() {
	$number = get_theme_mod( 'royal_limo_whatsapp_number', '' );
	if ( ! $number ) {
		return '';
	}
	$digits  = preg_replace( '/[^0-9]/', '', $number );
	$message = get_theme_mod( 'royal_limo_whatsapp_message', '' );
	$url     = "https://wa.me/{$digits}";
	if ( $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
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
		1 => 'Dubai Airport',
		2 => 'Downtown Dubai',
		3 => 'Dubai Marina',
		4 => 'Palm Jumeirah',
		5 => 'Abu Dhabi',
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
		'heading'     => get_theme_mod( 'royal_limo_service_areas_heading', 'Serving You Across the UAE' ),
		'description' => get_theme_mod( 'royal_limo_service_areas_description', 'Enjoy complimentary pickup and drop-off across Dubai and the wider UAE — including Dubai International Airport, Downtown Dubai, Dubai Marina, Palm Jumeirah, and Abu Dhabi. Additional areas available by request.' ),
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
		// The image control's setting already holds the attachment URL
		// (see the sanitize_callback note in inc/customizer.php) — use
		// it as-is, no wp_get_attachment_image_url() lookup needed.
		'image_url'      => get_theme_mod( 'royal_limo_about_image', '' ),
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
 * All content for the About page (page-about.php) is edited directly on
 * that page (sidebar panel in assets/js/admin-panels.js, meta
 * registered in inc/custom-post-types.php) rather than the Customizer —
 * deliberately separate from the homepage's About/Why Choose Us
 * sections above, so editing one never changes the other. Every
 * function below takes the About page's post ID explicitly rather than
 * assuming "the current post", since page-about.php calls these outside
 * of a the_post() loop in a couple of spots.
 */

/**
 * About page — "About Us" intro: eyebrow/heading/description, an
 * image, and the trust bar (heading/tagline/Google rating).
 */
function royal_limo_about_page_intro( $post_id ) {
	$default_description = "Royal Luxury Limousine is a premium chauffeured car service in UAE, offering polished vehicles and professional drivers for customers who expect comfort, discretion, and a smooth ride from pickup to drop-off.\n\nFrom airport transfers to weddings and executive travel, every booking is handled by chauffeurs who know the city and treat every trip like it matters — because to you, it does.\n\nWith fast booking support, a meticulously maintained fleet, and fifteen years of trust behind it, Royal Luxury Limousine has built a reputation that matches Los Angeles' standard for style.";

	$get = function ( $key, $default ) use ( $post_id ) {
		$value = get_post_meta( $post_id, $key, true );
		return '' !== $value ? $value : $default;
	};

	return array(
		'eyebrow'        => $get( '_about_eyebrow', 'About Royal Luxury Limousine' ),
		'heading'        => $get( '_about_heading', 'Experience Chauffeured Luxury in UAE with Confidence and Style' ),
		'description'    => $get( '_about_description', $default_description ),
		'image_url'      => $get( '_about_image', '' ),
		'bar_heading'    => $get( '_about_bar_heading', 'Why Book With Us?' ),
		'bar_text'       => $get( '_about_bar_text', 'Top-Rated Chauffeur Service in UAE' ),
		'reviews_rating' => $get( '_about_reviews_rating', '4.9' ),
		'reviews_count'  => $get( '_about_reviews_count', '500+' ),
		'reviews_url'    => $get( '_about_reviews_url', '' ),
	);
}

/**
 * About page — "Why Choose Us": eyebrow/heading/description plus 4
 * fixed reason columns (icon is fixed per slot in the template — only
 * heading/description are editable).
 */
function royal_limo_about_page_why_us( $post_id ) {
	$defaults = array(
		1 => array( 'heading' => 'Exclusive Fleet', 'description' => 'Hand-picked luxury sedans, SUVs, and limousines for every occasion.' ),
		2 => array( 'heading' => 'Licensed & Insured', 'description' => 'Every vehicle and chauffeur meets rigorous safety and licensing standards.' ),
		3 => array( 'heading' => 'Client First', 'description' => 'Your comfort, privacy, and satisfaction are always our top priority.' ),
		4 => array( 'heading' => 'Citywide Service', 'description' => 'Reliable pickup and drop-off availability across Los Angeles and beyond.' ),
	);

	$get = function ( $key, $default ) use ( $post_id ) {
		$value = get_post_meta( $post_id, $key, true );
		return '' !== $value ? $value : $default;
	};

	$reasons = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$reasons[] = array(
			'heading'     => $get( "_why_us_{$i}_heading", $defaults[ $i ]['heading'] ),
			'description' => $get( "_why_us_{$i}_description", $defaults[ $i ]['description'] ),
		);
	}

	return array(
		'eyebrow'     => $get( '_why_us_eyebrow', 'Why Choose Us' ),
		'heading'     => $get( '_why_us_heading', 'The Royal Luxury Difference' ),
		'description' => $get( '_why_us_description', 'What sets a Royal Luxury ride apart, from the moment you book to the moment you arrive.' ),
		'reasons'     => $reasons,
	);
}

/**
 * About page — "Our Approach": eyebrow/heading, an image (or fallback
 * background if left blank), and two editorial blocks (Mission /
 * Vision), each with two checklist points.
 */
function royal_limo_our_approach( $post_id ) {
	$get = function ( $key, $default ) use ( $post_id ) {
		$value = get_post_meta( $post_id, $key, true );
		return '' !== $value ? $value : $default;
	};

	return array(
		'eyebrow'         => $get( '_approach_eyebrow', 'Our Approach' ),
		'heading'         => $get( '_approach_heading', 'Our Approach to Effortless Chauffeured Travel' ),
		'image_url'       => $get( '_approach_image', '' ),
		'mission_heading' => $get( '_approach_mission_heading', 'Our Mission' ),
		'mission_points'  => array(
			$get( '_approach_mission_point1', 'Deliver reliable, punctual transportation on every single trip' ),
			$get( '_approach_mission_point2', 'Treat every rider with the same care and discretion as our first' ),
		),
		'vision_heading'  => $get( '_approach_vision_heading', 'Our Vision' ),
		'vision_points'   => array(
			$get( '_approach_vision_point1', 'Set the standard for luxury chauffeured travel in Los Angeles' ),
			$get( '_approach_vision_point2', 'Build lasting trust with every client, one journey at a time' ),
		),
	);
}

/**
 * About page — "What We Do": eyebrow/heading/description, an image (or
 * fallback background if left blank), and 4 fixed feature items (icon
 * is fixed per slot in the template — only heading/description are
 * editable).
 */
function royal_limo_what_we_do( $post_id ) {
	$defaults = array(
		1 => array( 'heading' => 'Instant Online Booking', 'description' => 'Reserve your chauffeur in minutes with real-time confirmation.' ),
		2 => array( 'heading' => 'Flight-Tracked Pickups', 'description' => 'We monitor your flight and adjust pickup times automatically.' ),
		3 => array( 'heading' => 'Flexible Ride Packages', 'description' => 'Hourly, point-to-point, or full-day charters to suit your plans.' ),
		4 => array( 'heading' => '24/7 Client Support', 'description' => 'A reservations specialist is always available, day or night.' ),
	);

	$get = function ( $key, $default ) use ( $post_id ) {
		$value = get_post_meta( $post_id, $key, true );
		return '' !== $value ? $value : $default;
	};

	$features = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$features[] = array(
			'heading'     => $get( "_wwd_{$i}_heading", $defaults[ $i ]['heading'] ),
			'description' => $get( "_wwd_{$i}_description", $defaults[ $i ]['description'] ),
		);
	}

	return array(
		'eyebrow'     => $get( '_wwd_eyebrow', 'What We Do' ),
		'heading'     => $get( '_wwd_heading', 'Your Trusted Chauffeured Travel Partner' ),
		'description' => $get( '_wwd_description', "From the moment you book to the moment you arrive, we handle every detail of the ride so you don't have to." ),
		'image_url'   => $get( '_wwd_image', '' ),
		'features'    => $features,
	);
}

/**
 * About page — "Key Persons" / team section header (eyebrow/heading/
 * description) — the cards themselves come straight from the
 * "team_member" CPT in the template.
 */
function royal_limo_team_section( $post_id ) {
	$get = function ( $key, $default ) use ( $post_id ) {
		$value = get_post_meta( $post_id, $key, true );
		return '' !== $value ? $value : $default;
	};

	return array(
		'eyebrow'     => $get( '_team_eyebrow', 'Key Persons' ),
		'heading'     => $get( '_team_heading', 'Meet the People Behind Every Journey' ),
		'description' => $get( '_team_description', 'The experienced team ensuring every ride is punctual, professional, and worry-free.' ),
	);
}

/**
 * The "Video Showcase" section content. Fallback defaults here must
 * match the 'default' values registered in inc/customizer.php.
 */
function royal_limo_video_showcase() {
	// The image control's setting already holds the attachment URL (see
	// the sanitize_callback note in inc/customizer.php) — use it as-is,
	// no wp_get_attachment_image_url() lookup needed/possible.
	$video_url = get_theme_mod( 'royal_limo_video_url', '' );
	if ( $video_url && ! preg_match( '#^(https?:)?//#i', $video_url ) ) {
		// Admin pasted a site-relative path (e.g. "/wp-content/uploads/
		// ...") instead of a full URL — resolve it against the real
		// site URL rather than leaving it to resolve against the
		// browser's current origin, which breaks on any install that
		// isn't at the domain root (subdirectory installs, this local
		// XAMPP setup, etc).
		$video_url = home_url( '/' . ltrim( $video_url, '/' ) );
	}

	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_video_eyebrow', 'Watch Video' ),
		'heading'     => get_theme_mod( 'royal_limo_video_heading', 'A Premium Chauffeured Experience' ),
		'description' => get_theme_mod( 'royal_limo_video_description', 'Discover how easy and comfortable it is to book with us. This short video gives you a quick look at our fleet, our chauffeurs, and how simple the booking process really is.' ),
		'check1'      => get_theme_mod( 'royal_limo_video_check1', 'See Our Fleet Quality and Comfort' ),
		'check2'      => get_theme_mod( 'royal_limo_video_check2', 'Learn About Easy Booking Steps' ),
		'image_url'   => get_theme_mod( 'royal_limo_video_image', '' ),
		'video_url'   => $video_url,
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
		'image_url'   => get_theme_mod( 'royal_limo_fleet_banner_image', '' ),
	);
}

/**
 * Fleet Category terms that actually have at least one vehicle —
 * powers the filter tabs on the homepage teaser and the /fleet/
 * archive. Returns an empty array (not WP_Error) if the taxonomy has
 * no terms yet, so callers can just check truthiness.
 */
function royal_limo_fleet_categories() {
	$terms = get_terms( array(
		'taxonomy'   => 'fleet_category',
		'hide_empty' => true,
	) );
	return is_wp_error( $terms ) ? array() : $terms;
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
		// The image control's setting already holds the attachment URL
		// (see the sanitize_callback note in inc/customizer.php) — use
		// it as-is, no wp_get_attachment_image_url() lookup needed.
		'image_url'   => get_theme_mod( 'royal_limo_services_banner_image', '' ),
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
		'image_url'   => get_theme_mod( 'royal_limo_blog_banner_image', '' ),
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
	return array(
		'eyebrow'     => get_theme_mod( 'royal_limo_booking_cta_eyebrow', 'Contact Today' ),
		'heading'     => get_theme_mod( 'royal_limo_booking_cta_heading', 'Connect With Us for Booking Support Today!' ),
		'description' => get_theme_mod( 'royal_limo_booking_cta_description', 'Our friendly and professional team is ready to help you plan the perfect ride for any occasion — whether you have questions, need assistance with booking, or want to confirm the details of an upcoming trip.' ),
		'check1'      => get_theme_mod( 'royal_limo_booking_cta_check1', 'Speak With a Reservations Specialist' ),
		'check2'      => get_theme_mod( 'royal_limo_booking_cta_check2', 'Get Real-Time Booking Confirmation' ),
		// The image control's setting already holds the attachment URL
		// (see the sanitize_callback note in inc/customizer.php) — use
		// it as-is, no wp_get_attachment_image_url() lookup needed.
		'image_url'   => get_theme_mod( 'royal_limo_booking_cta_image', '' ),
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

	// 640x480 (4:3) matches .rl-fleet-card__media's CSS aspect-ratio
	// exactly — keeps the server-side crop and the browser's
	// object-fit:cover box in agreement instead of cropping twice.
	add_image_size( 'fleet-card', 640, 480, true );
	add_image_size( 'fleet-full', 1280, 800, true );
	add_image_size( 'service-card', 640, 800, true );
	add_image_size( 'blog-card', 640, 460, true );
}
add_action( 'after_setup_theme', 'royal_limo_setup' );

/**
 * Keep the default "Hello world!" sample post (ID 1) out of the real
 * blog listing (/blog/, WordPress's designated Posts page) — same
 * exclusion the homepage teaser applies via its own WP_Query args (see
 * template-parts/blog.php). Done via pre_get_posts rather than
 * skipping it mid-loop so pagination counts stay accurate.
 */
function royal_limo_exclude_default_post( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
		$query->set( 'post__not_in', array( 1 ) );
	}
}
add_action( 'pre_get_posts', 'royal_limo_exclude_default_post' );

/**
 * A file's mtime as its cache-busting version, instead of the static
 * ROYAL_LIMO_VERSION constant. During active theme development, a
 * hardcoded version string never changes as CSS/JS files are edited,
 * so browsers that already cached an asset keep serving the stale
 * copy indefinitely at the same ?ver= — this is exactly the "the
 * style looks broken" symptom, and it's silent (no error, no console
 * warning, it just quietly never re-fetches). Falls back to
 * ROYAL_LIMO_VERSION if the file can't be found for some reason.
 */
function royal_limo_asset_version( $relative_path ) {
	$file = ROYAL_LIMO_DIR . $relative_path;
	return file_exists( $file ) ? filemtime( $file ) : ROYAL_LIMO_VERSION;
}

/**
 * Enqueue styles/scripts. Plain files, no build step, loaded in dependency order.
 */
function royal_limo_assets() {
	$css_dir = ROYAL_LIMO_URI . '/assets/css/';
	$js_dir  = ROYAL_LIMO_URI . '/assets/js/';

	wp_enqueue_style( 'royal-limo-base', $css_dir . 'base.css', array(), royal_limo_asset_version( '/assets/css/base.css' ) );
	wp_enqueue_style( 'royal-limo-glass', $css_dir . 'glass.css', array( 'royal-limo-base' ), royal_limo_asset_version( '/assets/css/glass.css' ) );
	wp_enqueue_style( 'royal-limo-neumorph', $css_dir . 'neumorph.css', array( 'royal-limo-base' ), royal_limo_asset_version( '/assets/css/neumorph.css' ) );
	wp_enqueue_style( 'royal-limo-layout', $css_dir . 'layout.css', array( 'royal-limo-base' ), royal_limo_asset_version( '/assets/css/layout.css' ) );
	wp_enqueue_style( 'royal-limo-animations-css', $css_dir . 'animations.css', array( 'royal-limo-layout' ), royal_limo_asset_version( '/assets/css/animations.css' ) );
	wp_enqueue_style( 'royal-limo-style', get_stylesheet_uri(), array( 'royal-limo-animations-css' ), royal_limo_asset_version( '/style.css' ) );

	// GSAP from CDN (jsdelivr), core + ScrollTrigger only.
	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '3.12.5', true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', array( 'gsap' ), '3.12.5', true );

	wp_enqueue_script( 'royal-limo-main', $js_dir . 'main.js', array(), royal_limo_asset_version( '/assets/js/main.js' ), true );
	wp_enqueue_script( 'royal-limo-animations', $js_dir . 'animations.js', array( 'gsap', 'gsap-scrolltrigger' ), royal_limo_asset_version( '/assets/js/animations.js' ), true );

	wp_localize_script( 'royal-limo-main', 'royalLimoData', array(
		'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
		'infiniteScrollNonce' => wp_create_nonce( 'royal_limo_infinite_scroll' ),
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
 * Native block-editor sidebar panels for the Fleet/Testimonial/Banner/
 * Service/Team Member custom fields and the About page's content
 * (assets/js/admin-panels.js), replacing the old classic meta boxes.
 * Only loaded on those post types' editor screens — 'page' is included
 * for every page (not just the About page) since there's no cheap way
 * to check a page's assigned template this early; admin-panels.js
 * itself only renders the About Page Content panel when the template
 * actually matches, so this is harmless for every other page.
 */
function royal_limo_admin_assets( $hook ) {
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'fleet', 'testimonial', 'banner', 'service', 'team_member', 'page' ), true ) ) {
		return;
	}

	wp_enqueue_script(
		'royal-limo-admin-panels',
		ROYAL_LIMO_URI . '/assets/js/admin-panels.js',
		array( 'wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n' ),
		royal_limo_asset_version( '/assets/js/admin-panels.js' ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'royal_limo_admin_assets' );

require ROYAL_LIMO_DIR . '/inc/custom-post-types.php';
require ROYAL_LIMO_DIR . '/inc/customizer.php';
require ROYAL_LIMO_DIR . '/inc/cf7-integration.php';
require ROYAL_LIMO_DIR . '/inc/quote-form.php';
require ROYAL_LIMO_DIR . '/inc/infinite-scroll.php';
