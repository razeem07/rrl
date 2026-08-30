<?php
/**
 * Block search engines from crawling/indexing the staging subdomain
 * while the real domain isn't live yet. Checks the actual request host
 * rather than a hardcoded flag, so this protection switches itself off
 * automatically the moment this code runs on a different domain (e.g.
 * once moved to the real production domain) — nothing to remember to
 * remove later.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROYAL_LIMO_STAGING_HOST', 'rll.codxelglobal.com' );

function royal_limo_is_staging_host() {
	return isset( $_SERVER['HTTP_HOST'] ) && ROYAL_LIMO_STAGING_HOST === strtolower( $_SERVER['HTTP_HOST'] );
}

function royal_limo_staging_noindex_meta() {
	if ( royal_limo_is_staging_host() ) {
		echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
	}
}
add_action( 'wp_head', 'royal_limo_staging_noindex_meta', 1 );

function royal_limo_staging_noindex_header() {
	if ( royal_limo_is_staging_host() ) {
		header( 'X-Robots-Tag: noindex, nofollow', true );
	}
}
add_action( 'send_headers', 'royal_limo_staging_noindex_header' );

function royal_limo_staging_robots_txt( $output ) {
	if ( royal_limo_is_staging_host() ) {
		return "User-agent: *\nDisallow: /\n";
	}
	return $output;
}
add_filter( 'robots_txt', 'royal_limo_staging_robots_txt' );
