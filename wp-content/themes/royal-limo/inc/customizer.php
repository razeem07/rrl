<?php
/**
 * Theme Customizer: contact info, hero image, socials, accent color.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function royal_limo_customize_register( $wp_customize ) {

	$wp_customize->add_section( 'royal_limo_contact', array(
		'title'    => __( 'Contact Info', 'royal-limo' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'royal_limo_phone', array(
		'default'           => ROYAL_LIMO_DEFAULT_PHONE,
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_phone', array(
		'label'   => __( 'Phone Number', 'royal-limo' ),
		'section' => 'royal_limo_contact',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_whatsapp_number', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_whatsapp_number', array(
		'label'       => __( 'WhatsApp Number', 'royal-limo' ),
		'description' => __( 'Include the country code (e.g. +1 555 010 2026). Separate from the Phone Number above — powers the floating WhatsApp button shown sitewide. Leave blank to hide that button.', 'royal-limo' ),
		'section'     => 'royal_limo_contact',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_whatsapp_message', array(
		'default'           => "Hi! I'd like to book a ride.",
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_whatsapp_message', array(
		'label'       => __( 'WhatsApp Pre-filled Message', 'royal-limo' ),
		'description' => __( 'Appears already typed in the chat when a visitor taps the WhatsApp button.', 'royal-limo' ),
		'section'     => 'royal_limo_contact',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_email', array(
		'default'           => 'reservations@example.com',
		'sanitize_callback' => 'sanitize_email',
	) );
	$wp_customize->add_control( 'royal_limo_email', array(
		'label'   => __( 'Reservations Email', 'royal-limo' ),
		'section' => 'royal_limo_contact',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_address', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_address', array(
		'label'   => __( 'Address', 'royal-limo' ),
		'section' => 'royal_limo_contact',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_operating_hours', array(
		'default'           => ROYAL_LIMO_DEFAULT_OPERATING_HOURS,
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_operating_hours', array(
		'label'   => __( 'Operating Hours', 'royal-limo' ),
		'section' => 'royal_limo_contact',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_map_embed_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'royal_limo_map_embed_url', array(
		'label'       => __( 'Google Maps Embed URL', 'royal-limo' ),
		'description' => __( 'From Google Maps: Share > Embed a map > copy the src="…" URL. Leave blank to fall back to a plain pin generated from the Address field above.', 'royal-limo' ),
		'section'     => 'royal_limo_contact',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'royal_limo_booking_page_id', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'royal_limo_booking_page_id', array(
		'label'       => __( 'Booking / Contact Page', 'royal-limo' ),
		'description' => __( '"Get a Quote" and "Reserve" buttons across the site link here. Leave unset to fall back to the homepage quote form.', 'royal-limo' ),
		'section'     => 'royal_limo_contact',
		'type'        => 'dropdown-pages',
	) );

	$payment_methods = array(
		'paypal'  => __( 'PayPal', 'royal-limo' ),
		'visa'    => __( 'Visa', 'royal-limo' ),
		'amex'    => __( 'Amex', 'royal-limo' ),
		'maestro' => __( 'Maestro', 'royal-limo' ),
	);
	foreach ( $payment_methods as $key => $label ) {
		$wp_customize->add_setting( "royal_limo_payment_icon_{$key}", array(
			'default'           => '',
			// See the note on royal_limo_video_image above — this control
			// stores a URL, not an attachment ID.
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "royal_limo_payment_icon_{$key}", array(
			'label'       => sprintf( __( 'Payment Icon — %s', 'royal-limo' ), $label ),
			'description' => __( 'Shown in the footer\'s payment methods row. Leave blank to show a plain text badge instead.', 'royal-limo' ),
			'section'     => 'royal_limo_contact',
		) ) );
	}

	$wp_customize->add_setting( 'royal_limo_quote_shortcode', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'royal_limo_quote_shortcode', array(
		'label'       => __( 'Quote Form Shortcode (e.g. Contact Form 7 shortcode)', 'royal-limo' ),
		'description' => __( 'Leave blank to show a static placeholder form until the form plugin is configured.', 'royal-limo' ),
		'section'     => 'royal_limo_contact',
		'type'        => 'text',
	) );

	$social_labels = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'x'         => 'X (Twitter)',
	);
	foreach ( $social_labels as $network => $label ) {
		$wp_customize->add_setting( "royal_limo_social_{$network}", array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( "royal_limo_social_{$network}", array(
			'label'   => sprintf( __( '%s URL', 'royal-limo' ), $label ),
			'section' => 'royal_limo_contact',
			'type'    => 'url',
		) );
	}

	// Hero section — heading/subheading/background image used to live
	// here as Customizer fields, but the hero carousel is now driven
	// entirely by the "banner" CPT (see template-parts/hero.php); those
	// fields were removed since they no longer did anything once real
	// banners existed. Only the trust-stats row (shared across every
	// slide) remains a Customizer-managed value.
	$wp_customize->add_section( 'royal_limo_hero', array(
		'title'    => __( 'Hero Section', 'royal-limo' ),
		'priority' => 25,
	) );

	// Hero trust stats — the small "50+ Premium Brands" style row shown
	// under the CTAs. Same 3 values appear on every carousel slide (it's
	// a site-wide trust signal, not per-slide content like the banner
	// title/description/CTAs), so it lives here in the Customizer.
	$hero_stat_defaults = array(
		1 => array( 'number' => '15', 'suffix' => '+', 'label' => 'Years of Service' ),
		2 => array( 'number' => '40', 'suffix' => '+', 'label' => 'Fleet Vehicles' ),
		3 => array( 'number' => '10', 'suffix' => 'K+', 'label' => 'Happy Clients' ),
	);
	foreach ( $hero_stat_defaults as $i => $defaults ) {
		$wp_customize->add_setting( "royal_limo_hero_stat{$i}_number", array(
			'default'           => $defaults['number'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "royal_limo_hero_stat{$i}_number", array(
			'label'   => sprintf( __( 'Hero Stat %d — Number', 'royal-limo' ), $i ),
			'section' => 'royal_limo_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( "royal_limo_hero_stat{$i}_suffix", array(
			'default'           => $defaults['suffix'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "royal_limo_hero_stat{$i}_suffix", array(
			'label'   => sprintf( __( 'Hero Stat %d — Suffix (e.g. "+", "K+")', 'royal-limo' ), $i ),
			'section' => 'royal_limo_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( "royal_limo_hero_stat{$i}_label", array(
			'default'           => $defaults['label'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "royal_limo_hero_stat{$i}_label", array(
			'label'   => sprintf( __( 'Hero Stat %d — Label', 'royal-limo' ), $i ),
			'section' => 'royal_limo_hero',
			'type'    => 'text',
		) );
	}

	// Service Areas — the "Where We Serve" section right after the hero:
	// eyebrow/heading/description plus a fixed set of 5 location stops
	// shown along a route graphic. A bounded, rarely-changed list, so
	// fixed Customizer fields (like the hero stats above) rather than a
	// full admin-manageable CPT.
	$wp_customize->add_section( 'royal_limo_service_areas', array(
		'title'    => __( 'Service Areas', 'royal-limo' ),
		'priority' => 26,
	) );

	$wp_customize->add_setting( 'royal_limo_service_areas_eyebrow', array(
		'default'           => 'Where We Serve',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_service_areas_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_service_areas',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_service_areas_heading', array(
		'default'           => 'Serving You Across the UAE',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_service_areas_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_service_areas',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_service_areas_description', array(
		'default'           => 'Enjoy complimentary pickup and drop-off across Dubai and the wider UAE — including Dubai International Airport, Downtown Dubai, Dubai Marina, Palm Jumeirah, and Abu Dhabi. Additional areas available by request.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_service_areas_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_service_areas',
		'type'    => 'textarea',
	) );

	$service_area_defaults = array(
		1 => 'Dubai Airport',
		2 => 'Downtown Dubai',
		3 => 'Dubai Marina',
		4 => 'Palm Jumeirah',
		5 => 'Abu Dhabi',
	);
	foreach ( $service_area_defaults as $i => $default_label ) {
		$wp_customize->add_setting( "royal_limo_service_area_{$i}", array(
			'default'           => $default_label,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "royal_limo_service_area_{$i}", array(
			'label'   => sprintf( __( 'Stop %d', 'royal-limo' ), $i ),
			'section' => 'royal_limo_service_areas',
			'type'    => 'text',
		) );
	}

	// About section — eyebrow/heading/description, a brand-mark showcase
	// card, and a bottom trust bar with a Google-reviews badge.
	$wp_customize->add_section( 'royal_limo_about', array(
		'title'    => __( 'About Section', 'royal-limo' ),
		'priority' => 27,
	) );

	$wp_customize->add_setting( 'royal_limo_about_eyebrow', array(
		'default'           => 'About Royal Luxury Limousine',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_about_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_about',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_about_heading', array(
		'default'           => 'Experience Chauffeured Luxury in Los Angeles with Confidence and Style',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_about_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_about',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_about_description', array(
		'default'           => "Royal Luxury Limousine is a premium chauffeured car service in Los Angeles, offering polished vehicles and professional drivers for customers who expect comfort, discretion, and a smooth ride from pickup to drop-off.\n\nFrom airport transfers to weddings and executive travel, every booking is handled by chauffeurs who know the city and treat every trip like it matters — because to you, it does.\n\nWith fast booking support, a meticulously maintained fleet, and fifteen years of trust behind it, Royal Luxury Limousine has built a reputation that matches Los Angeles' standard for style.",
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_about_description', array(
		'label'       => __( 'Description', 'royal-limo' ),
		'description' => __( 'Separate paragraphs with a blank line.', 'royal-limo' ),
		'section'     => 'royal_limo_about',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'royal_limo_about_image', array(
		'default'           => '',
		// See the note on royal_limo_video_image above — this control
		// stores a URL, not an attachment ID.
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'royal_limo_about_image', array(
		'label'       => __( 'Image', 'royal-limo' ),
		'description' => __( 'Shown on the right side of the About section. Leave blank to show a placeholder brand card instead. Recommended: a portrait/vertical photo, at least 1200×1500px.', 'royal-limo' ),
		'section'     => 'royal_limo_about',
	) ) );

	$wp_customize->add_setting( 'royal_limo_about_bar_heading', array(
		'default'           => 'Why Book With Us?',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_about_bar_heading', array(
		'label'   => __( 'Trust Bar Heading', 'royal-limo' ),
		'section' => 'royal_limo_about',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_about_bar_text', array(
		'default'           => 'Top-Rated Chauffeur Service in Los Angeles',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_about_bar_text', array(
		'label'   => __( 'Trust Bar Tagline', 'royal-limo' ),
		'section' => 'royal_limo_about',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_about_reviews_rating', array(
		'default'           => '4.9',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_about_reviews_rating', array(
		'label'       => __( 'Google Rating', 'royal-limo' ),
		'description' => __( 'Placeholder default — replace with your real Google Business rating once available.', 'royal-limo' ),
		'section'     => 'royal_limo_about',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_about_reviews_count', array(
		'default'           => '500+',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_about_reviews_count', array(
		'label'       => __( 'Review Count', 'royal-limo' ),
		'description' => __( 'Placeholder default — replace with your real review count.', 'royal-limo' ),
		'section'     => 'royal_limo_about',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_about_reviews_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'royal_limo_about_reviews_url', array(
		'label'   => __( 'Google Business Profile URL', 'royal-limo' ),
		'section' => 'royal_limo_about',
		'type'    => 'url',
	) );

	// Why Choose Us — 4 fixed reason columns (icon per slot is fixed in
	// the template; only heading/description are editable), rarely
	// changing marketing copy, same fixed-Customizer-fields pattern as
	// the hero stats and service areas above.
	$wp_customize->add_section( 'royal_limo_why_us', array(
		'title'    => __( 'Why Choose Us Section', 'royal-limo' ),
		'priority' => 28,
	) );

	$wp_customize->add_setting( 'royal_limo_why_us_eyebrow', array(
		'default'           => 'Why Choose Us',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_why_us_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_why_us',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_why_us_heading', array(
		'default'           => 'The Royal Luxury Difference',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_why_us_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_why_us',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_why_us_description', array(
		'default'           => 'What sets a Royal Luxury ride apart, from the moment you book to the moment you arrive.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_why_us_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_why_us',
		'type'    => 'textarea',
	) );

	$why_us_defaults = array(
		1 => array(
			'heading'     => 'Exclusive Fleet',
			'description' => 'Hand-picked luxury sedans, SUVs, and limousines for every occasion.',
		),
		2 => array(
			'heading'     => 'Licensed & Insured',
			'description' => 'Every vehicle and chauffeur meets rigorous safety and licensing standards.',
		),
		3 => array(
			'heading'     => 'Client First',
			'description' => 'Your comfort, privacy, and satisfaction are always our top priority.',
		),
		4 => array(
			'heading'     => 'Citywide Service',
			'description' => 'Reliable pickup and drop-off availability across Los Angeles and beyond.',
		),
	);
	foreach ( $why_us_defaults as $i => $defaults ) {
		$wp_customize->add_setting( "royal_limo_why_us_{$i}_heading", array(
			'default'           => $defaults['heading'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "royal_limo_why_us_{$i}_heading", array(
			'label'   => sprintf( __( 'Reason %d — Heading', 'royal-limo' ), $i ),
			'section' => 'royal_limo_why_us',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( "royal_limo_why_us_{$i}_description", array(
			'default'           => $defaults['description'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "royal_limo_why_us_{$i}_description", array(
			'label'   => sprintf( __( 'Reason %d — Description', 'royal-limo' ), $i ),
			'section' => 'royal_limo_why_us',
			'type'    => 'text',
		) );
	}

	// Video Showcase — "Watch Video" panel with a promo video, shown
	// after the Services section on the homepage.
	$wp_customize->add_section( 'royal_limo_video', array(
		'title'    => __( 'Video Showcase Section', 'royal-limo' ),
		'priority' => 29,
	) );

	$wp_customize->add_setting( 'royal_limo_video_eyebrow', array(
		'default'           => 'Watch Video',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_video_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_video',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_video_heading', array(
		'default'           => 'A Premium Chauffeured Experience',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_video_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_video',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_video_description', array(
		'default'           => 'Discover how easy and comfortable it is to book with us. This short video gives you a quick look at our fleet, our chauffeurs, and how simple the booking process really is.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_video_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_video',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'royal_limo_video_check1', array(
		'default'           => 'See Our Fleet Quality and Comfort',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_video_check1', array(
		'label'   => __( 'Checklist Item 1', 'royal-limo' ),
		'section' => 'royal_limo_video',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_video_check2', array(
		'default'           => 'Learn About Easy Booking Steps',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_video_check2', array(
		'label'   => __( 'Checklist Item 2', 'royal-limo' ),
		'section' => 'royal_limo_video',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_video_image', array(
		'default'           => '',
		// WP_Customize_Image_Control writes the attachment URL (not its
		// ID) to the setting — sanitize as a URL, not absint(), or every
		// real selection gets silently mangled to 0. Same note applies
		// to every other image control below.
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'royal_limo_video_image', array(
		'label'   => __( 'Showcase Image', 'royal-limo' ),
		'section' => 'royal_limo_video',
	) ) );

	$wp_customize->add_setting( 'royal_limo_video_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'royal_limo_video_url', array(
		'label'       => __( 'Promo Video URL', 'royal-limo' ),
		'description' => __( 'YouTube, Vimeo, or a direct video file URL. Leave blank to hide the play button.', 'royal-limo' ),
		'section'     => 'royal_limo_video',
		'type'        => 'url',
	) );

	// Fleet — header text only; the cards themselves come from the
	// "fleet" CPT (variable/admin-manageable count), same split as
	// Services/Testimonials/Blog below.
	$wp_customize->add_section( 'royal_limo_fleet', array(
		'title'    => __( 'Fleet Section', 'royal-limo' ),
		'priority' => 28,
	) );

	$wp_customize->add_setting( 'royal_limo_fleet_eyebrow', array(
		'default'           => 'Our Collection',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_fleet_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_fleet',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_fleet_heading', array(
		'default'           => 'Featured Luxury Fleet',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_fleet_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_fleet',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_fleet_description', array(
		'default'           => 'Explore a hand-selected range of executive sedans, SUVs, and stretch limousines — each maintained to the highest standard and ready for your next reservation.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_fleet_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_fleet',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'royal_limo_fleet_banner_image', array(
		'default'           => '',
		// See the note on royal_limo_video_image above — this control
		// stores a URL, not an attachment ID.
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'royal_limo_fleet_banner_image', array(
		'label'       => __( 'Banner Image', 'royal-limo' ),
		'description' => __( 'Background photo for the full-width banner at the top of the /fleet/ listing page and its category pages. Leave blank for a plain dark background.', 'royal-limo' ),
		'section'     => 'royal_limo_fleet',
	) ) );

	// Services — header text only; cards come from the "service" CPT.
	$wp_customize->add_section( 'royal_limo_services', array(
		'title'    => __( 'Services Section', 'royal-limo' ),
		'priority' => 28,
	) );

	$wp_customize->add_setting( 'royal_limo_services_eyebrow', array(
		'default'           => 'What We Offer',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_services_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_services',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_services_heading', array(
		'default'           => 'Services Built Around You',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_services_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_services',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_services_description', array(
		'default'           => 'From airport transfers to weddings and corporate travel, explore the chauffeured services designed to fit every occasion.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_services_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_services',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'royal_limo_services_banner_image', array(
		'default'           => '',
		// See the note on royal_limo_video_image above — this control
		// stores a URL, not an attachment ID.
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'royal_limo_services_banner_image', array(
		'label'       => __( 'Banner Image', 'royal-limo' ),
		'description' => __( 'Background photo for the full-width banner at the top of the /services/ listing page. Leave blank for a plain dark background.', 'royal-limo' ),
		'section'     => 'royal_limo_services',
	) ) );

	// Testimonials — header text only; slides come from the
	// "testimonial" CPT.
	$wp_customize->add_section( 'royal_limo_testimonials', array(
		'title'    => __( 'Testimonials Section', 'royal-limo' ),
		'priority' => 29,
	) );

	$wp_customize->add_setting( 'royal_limo_testimonials_eyebrow', array(
		'default'           => 'Testimonials',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_testimonials_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_testimonials',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_testimonials_heading', array(
		'default'           => 'Trusted by Our Riders',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_testimonials_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_testimonials',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_testimonials_description', array(
		'default'           => 'Real experiences from riders who trust us for reliable, comfortable, and professional chauffeured service.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_testimonials_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_testimonials',
		'type'    => 'textarea',
	) );

	// Blog — header text only; cards come from core WordPress posts.
	$wp_customize->add_section( 'royal_limo_blog', array(
		'title'    => __( 'Blog Section', 'royal-limo' ),
		'priority' => 29,
	) );

	$wp_customize->add_setting( 'royal_limo_blog_eyebrow', array(
		'default'           => 'Latest Blogs',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_blog_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_blog',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_blog_heading', array(
		'default'           => 'Insights and Stories That Drive',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_blog_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_blog',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_blog_description', array(
		'default'           => 'Discover expert tips, helpful advice, and inspiring stories designed to make every journey smarter, smoother, and more enjoyable.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_blog_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_blog',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'royal_limo_blog_banner_image', array(
		'default'           => '',
		// See the note on royal_limo_video_image above — this control
		// stores a URL, not an attachment ID.
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'royal_limo_blog_banner_image', array(
		'label'       => __( 'Banner Image', 'royal-limo' ),
		'description' => __( 'Background photo for the full-width banner at the top of the /blog/ listing page. Leave blank for a plain dark background.', 'royal-limo' ),
		'section'     => 'royal_limo_blog',
	) ) );

	// Note: the single service page's "Our Booking Process" steps and
	// FAQs are deliberately NOT here — they're per-service content (see
	// the "Service Detail Page" panel in the block editor sidebar when
	// editing a Service post), since a wedding's booking flow and FAQs
	// aren't the same as an airport transfer's. Only content that's
	// genuinely the same across every service belongs in the Customizer.

	// FAQ — "Good To Know" accordion, content pulled from the "faq" CPT
	// (variable/admin-manageable count, so only the section header lives
	// here — same split as Testimonials).
	$wp_customize->add_section( 'royal_limo_faq', array(
		'title'    => __( 'FAQ Section', 'royal-limo' ),
		'priority' => 29,
	) );

	$wp_customize->add_setting( 'royal_limo_faq_eyebrow', array(
		'default'           => 'Good To Know',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_faq_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_faq',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_faq_heading', array(
		'default'           => 'Frequently Asked Questions',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_faq_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_faq',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_faq_description', array(
		'default'           => 'Answers to the questions we hear most from riders booking a chauffeured trip with us.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_faq_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_faq',
		'type'    => 'textarea',
	) );

	// Booking CTA — the final "Connect with us" banner near the footer:
	// a background photo (fixed-attachment, see layout.css) with a pill
	// badge, heading, description, checklist, CTA button, and phone.
	$wp_customize->add_section( 'royal_limo_booking_cta', array(
		'title'    => __( 'Booking CTA Section', 'royal-limo' ),
		'priority' => 31,
	) );

	$wp_customize->add_setting( 'royal_limo_booking_cta_eyebrow', array(
		'default'           => 'Contact Today',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_booking_cta_eyebrow', array(
		'label'   => __( 'Eyebrow Text', 'royal-limo' ),
		'section' => 'royal_limo_booking_cta',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_booking_cta_heading', array(
		'default'           => 'Connect With Us for Booking Support Today!',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_booking_cta_heading', array(
		'label'   => __( 'Heading', 'royal-limo' ),
		'section' => 'royal_limo_booking_cta',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_booking_cta_description', array(
		'default'           => 'Our friendly and professional team is ready to help you plan the perfect ride for any occasion — whether you have questions, need assistance with booking, or want to confirm the details of an upcoming trip.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'royal_limo_booking_cta_description', array(
		'label'   => __( 'Description', 'royal-limo' ),
		'section' => 'royal_limo_booking_cta',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'royal_limo_booking_cta_check1', array(
		'default'           => 'Speak With a Reservations Specialist',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_booking_cta_check1', array(
		'label'   => __( 'Checklist Item 1', 'royal-limo' ),
		'section' => 'royal_limo_booking_cta',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_booking_cta_check2', array(
		'default'           => 'Get Real-Time Booking Confirmation',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'royal_limo_booking_cta_check2', array(
		'label'   => __( 'Checklist Item 2', 'royal-limo' ),
		'section' => 'royal_limo_booking_cta',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'royal_limo_booking_cta_image', array(
		'default'           => '',
		// See the note on royal_limo_video_image above — this control
		// stores a URL, not an attachment ID.
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'royal_limo_booking_cta_image', array(
		'label'       => __( 'Background Image', 'royal-limo' ),
		'description' => __( 'Displayed with a fixed (parallax-style) background attachment on desktop. Leave blank to use a plain dark background.', 'royal-limo' ),
		'section'     => 'royal_limo_booking_cta',
	) ) );

	// Our Approach / Key Persons / What We Do — these used to be
	// Customizer sections here (About page only, never shared with the
	// homepage), but moved to page-level meta fields edited directly on
	// the About page (see royal_limo_register_about_page_meta() in
	// inc/custom-post-types.php and the sidebar panel in
	// assets/js/admin-panels.js), so the whole About page is editable
	// in one place instead of split across wp-admin screens.

	// Accent color.
	$wp_customize->add_setting( 'royal_limo_accent_color', array(
		'default'           => '#c9a24b',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'royal_limo_accent_color', array(
		'label'   => __( 'Accent Color', 'royal-limo' ),
		'section' => 'colors',
	) ) );
}
add_action( 'customize_register', 'royal_limo_customize_register' );

/**
 * Print accent color override as inline CSS custom property.
 */
function royal_limo_customizer_css() {
	$accent = get_theme_mod( 'royal_limo_accent_color', '#c9a24b' );
	echo '<style id="royal-limo-customizer-css">:root{--gold:' . esc_html( $accent ) . ';}</style>';
}
add_action( 'wp_head', 'royal_limo_customizer_css' );
