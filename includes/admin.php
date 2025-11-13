<?php
// add settings submenu under resources cpt
function resources_cpt_add_settings_submenu() {
	add_submenu_page(
		'edit.php?post_type=' . RESOURCES_CPT_POST_TYPE,
		__( 'Resources Settings', 'resources-cpt' ),
		__( 'Settings', 'resources-cpt' ),
		'manage_options',
		'resources-cpt-settings',
		'resources_cpt_render_settings_page'
	);
}
add_action( 'admin_menu', 'resources_cpt_add_settings_submenu' );

// register setting to store fallback image id
function resources_cpt_register_settings() {
	register_setting(
		'resources_cpt_settings_group',
		'resources_cpt_fallback_image_id',
		array(
			'type' => 'integer',
			'sanitize_callback' => 'absint',
			'default' => 0,
		)
	);
}
add_action( 'admin_init', 'resources_cpt_register_settings' );

// enqueue media and small script for media picker on our settings page
function resources_cpt_admin_enqueue( $hook ) {
	if ( empty( $_GET['page'] ) || $_GET['page'] !== 'resources-cpt-settings' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'resources-cpt-admin',
		RESOURCES_CPT_URL . 'assets/js/admin-settings.js',
		array( 'jquery' ),
		RESOURCES_CPT_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'resources_cpt_admin_enqueue' );

// render simple settings page with media selector for fallback image
function resources_cpt_render_settings_page() {
	$fallback_id = (int) get_option( 'resources_cpt_fallback_image_id', 0 );
	$fallback_src = $fallback_id ? wp_get_attachment_image_url( $fallback_id, 'medium' ) : '';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Resources Settings', 'resources-cpt' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'resources_cpt_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="resources_cpt_fallback_image_id"><?php esc_html_e( 'Fallback image', 'resources-cpt' ); ?></label>
					</th>
					<td>
						<div style="margin-bottom:10px;">
							<img id="resources-cpt-fallback-preview" src="<?php echo esc_url( $fallback_src ); ?>" style="max-width:200px;height:auto;display:<?php echo $fallback_src ? 'block' : 'none'; ?>;" />
						</div>
						<input type="hidden" id="resources_cpt_fallback_image_id" name="resources_cpt_fallback_image_id" value="<?php echo esc_attr( $fallback_id ); ?>" />
						<button type="button" class="button" id="resources-cpt-select-fallback"><?php esc_html_e( 'Select image', 'resources-cpt' ); ?></button>
						<button type="button" class="button" id="resources-cpt-remove-fallback" style="margin-left:6px;"><?php esc_html_e( 'Remove', 'resources-cpt' ); ?></button>
						<p class="description"><?php esc_html_e( 'used when a resource has no featured image.', 'resources-cpt' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}


