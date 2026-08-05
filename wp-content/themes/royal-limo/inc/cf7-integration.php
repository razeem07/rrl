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

/**
 * Add our own utility classes onto CF7's generated form wrapper.
 */
function royal_limo_cf7_form_class( $class ) {
	return $class . ' rl-quote-form glass-panel';
}
add_filter( 'wpcf7_form_class_attr', 'royal_limo_cf7_form_class' );

/**
 * Style the submit button as a neumorphic CTA.
 */
function royal_limo_cf7_form_elements( $content ) {
	$content = str_replace( 'wpcf7-form-control wpcf7-submit', 'wpcf7-form-control wpcf7-submit rl-btn rl-btn--neu', $content );
	return $content;
}
add_filter( 'wpcf7_form_elements', 'royal_limo_cf7_form_elements' );
