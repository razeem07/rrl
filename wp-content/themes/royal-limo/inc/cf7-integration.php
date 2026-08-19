<?php
/**
 * Contact Form 7 integration — restyle CF7 markup with our glass/neumorphic
 * classes instead of loading CF7's own stylesheet, and skip everything
 * quietly if the plugin isn't active (theme must not hard-depend on it).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPCF7' ) ) {
	return;
}

// Don't load CF7's default stylesheet; our own CSS handles all styling.
add_filter( 'wpcf7_load_css', '__return_false' );

// The .rl-quote-form/.glass-panel/.rl-quote-form__row wrapper divs already
// live inside the form template itself (see the "form" property set when
// the contact form was created), so the outer <form> tag needs no extra
// classes — adding them here would double up the padding/background.

// CF7 auto-inserts <p>/<br> around lines in the form template by default,
// which breaks the custom .rl-quote-form__row grid wrapper divs. The
// template already provides its own structure, so turn that off.
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Style the submit button as a neumorphic CTA.
 */
function royal_limo_cf7_form_elements( $content ) {
	$content = str_replace( 'wpcf7-form-control wpcf7-submit', 'wpcf7-form-control wpcf7-submit rl-btn rl-btn--neu', $content );
	return $content;
}
add_filter( 'wpcf7_form_elements', 'royal_limo_cf7_form_elements' );
