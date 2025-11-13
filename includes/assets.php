<?php
// enqueue styles; use minified in production, normal in debug
function resources_cpt_enqueue_styles() {
	if ( is_admin() ) {
		return;
	}

	// always use minified css for best performance
	$relative = 'assets/css/resources-style.min.css';

	$css_url = RESOURCES_CPT_URL . $relative;
	$css_path = RESOURCES_CPT_DIR . $relative;

	// version using filemtime when available to avoid stale cache
	$version = file_exists( $css_path ) ? filemtime( $css_path ) : RESOURCES_CPT_VERSION;

	wp_enqueue_style(
		'resources-cpt-style',
		$css_url,
		array(),
		$version
	);
}
add_action( 'wp_enqueue_scripts', 'resources_cpt_enqueue_styles' );


