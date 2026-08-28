<?php
/**
 * Forminator integration — skip quietly if the plugin isn't active (theme
 * must not hard-depend on it). Forminator's own stylesheet still loads
 * (unlike the old CF7 setup); assets/css/layout.css re-skins its actual
 * markup classes (.forminator-input, .forminator-button-submit, etc.) to
 * match the site's neumorphic/glass look instead of fighting to disable it.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Forminator_API' ) ) {
	return;
}
