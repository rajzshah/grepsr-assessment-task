<?php
// enqueue styles; use minified in production, normal in debug
function resources_cpt_enqueue_styles() {
	if ( is_admin() ) {
		return;
	}

	$suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';
	$relative = 'assets/css/resources-style' . $suffix . '.css';

	$css_url = RESOURCES_CPT_URL . $relative;
	$css_path = RESOURCES_CPT_DIR . $relative;

	// version using filemtime in dev to avoid stale cache
	$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG && file_exists( $css_path ) )
		? filemtime( $css_path )
		: RESOURCES_CPT_VERSION;

	wp_enqueue_style(
		'resources-cpt-style',
		$css_url,
		array(),
		$version
	);
}
add_action( 'wp_enqueue_scripts', 'resources_cpt_enqueue_styles' );


