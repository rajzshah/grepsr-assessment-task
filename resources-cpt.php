<?php
/**
 * Plugin Name: Resources Custom Post Type
 * Plugin URI: https://github.com/yourusername/resources-cpt
 * Description: A custom post type for Resources with a shortcode to display latest resources in a responsive grid/list.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: resources-cpt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Resources Custom Post Type
 */
function resources_cpt_register_post_type() {
	$labels = array(
		'name'                  => _x( 'Resources', 'Post Type General Name', 'resources-cpt' ),
		'singular_name'         => _x( 'Resource', 'Post Type Singular Name', 'resources-cpt' ),
		'menu_name'             => __( 'Resources', 'resources-cpt' ),
		'name_admin_bar'        => __( 'Resource', 'resources-cpt' ),
		'archives'              => __( 'Resource Archives', 'resources-cpt' ),
		'attributes'            => __( 'Resource Attributes', 'resources-cpt' ),
		'parent_item_colon'     => __( 'Parent Resource:', 'resources-cpt' ),
		'all_items'             => __( 'All Resources', 'resources-cpt' ),
		'add_new_item'          => __( 'Add New Resource', 'resources-cpt' ),
		'add_new'               => __( 'Add New', 'resources-cpt' ),
		'new_item'              => __( 'New Resource', 'resources-cpt' ),
		'edit_item'             => __( 'Edit Resource', 'resources-cpt' ),
		'update_item'           => __( 'Update Resource', 'resources-cpt' ),
		'view_item'             => __( 'View Resource', 'resources-cpt' ),
		'view_items'            => __( 'View Resources', 'resources-cpt' ),
		'search_items'          => __( 'Search Resource', 'resources-cpt' ),
		'not_found'             => __( 'Not found', 'resources-cpt' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'resources-cpt' ),
		'featured_image'        => __( 'Featured Image', 'resources-cpt' ),
		'set_featured_image'    => __( 'Set featured image', 'resources-cpt' ),
		'remove_featured_image' => __( 'Remove featured image', 'resources-cpt' ),
		'use_featured_image'    => __( 'Use as featured image', 'resources-cpt' ),
		'insert_into_item'      => __( 'Insert into resource', 'resources-cpt' ),
		'uploaded_to_this_item' => __( 'Uploaded to this resource', 'resources-cpt' ),
		'items_list'            => __( 'Resources list', 'resources-cpt' ),
		'items_list_navigation' => __( 'Resources list navigation', 'resources-cpt' ),
		'filter_items_list'     => __( 'Filter resources list', 'resources-cpt' ),
	);

	$args = array(
		'label'                 => __( 'Resource', 'resources-cpt' ),
		'description'           => __( 'Resources custom post type', 'resources-cpt' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-book-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true, // Enable Gutenberg support
	);

	register_post_type( 'resources', $args );
}
add_action( 'init', 'resources_cpt_register_post_type', 0 );

/**
 * Enqueue styles for the resources display
 */
function resources_cpt_enqueue_styles() {
	wp_enqueue_style(
		'resources-cpt-style',
		plugin_dir_url( __FILE__ ) . 'assets/css/resources-style.css',
		array(),
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'resources_cpt_enqueue_styles' );

/**
 * Shortcode to display latest resources
 * Usage: [latest_resources limit="5"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function resources_cpt_shortcode( $atts ) {
	// Parse shortcode attributes with defaults
	$atts = shortcode_atts(
		array(
			'limit' => 5,
		),
		$atts,
		'latest_resources'
	);

	// Sanitize the limit attribute - ensure it's a positive integer
	$limit = absint( $atts['limit'] );
	if ( $limit < 1 ) {
		$limit = 5; // Default to 5 if invalid
	}

	// Query arguments for latest resources
	$args = array(
		'post_type'      => 'resources',
		'posts_per_page' => $limit,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$resources_query = new WP_Query( $args );

	// Start output buffering
	ob_start();

	if ( $resources_query->have_posts() ) {
		?>
		<div class="resources-container">
			<div class="resources-grid">
				<?php
				while ( $resources_query->have_posts() ) {
					$resources_query->the_post();
					
					// Get the post ID for escaping
					$post_id = get_the_ID();
					
					// Get featured image
					$featured_image = get_the_post_thumbnail( $post_id, 'medium', array( 'class' => 'resource-featured-image' ) );
					
					// Get title and escape it
					$title = get_the_title();
					
					// Get excerpt or short description
					$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 20 );
					
					// Get permalink
					$permalink = esc_url( get_permalink( $post_id ) );
					?>
					<article class="resource-item">
						<?php if ( $featured_image ) : ?>
							<div class="resource-image">
								<a href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
									<?php echo $featured_image; // Featured image is already escaped by WordPress ?>
								</a>
							</div>
						<?php endif; ?>
						
						<div class="resource-content">
							<h3 class="resource-title">
								<a href="<?php echo esc_url( $permalink ); ?>">
									<?php echo esc_html( $title ); ?>
								</a>
							</h3>
							
							<?php if ( $excerpt ) : ?>
								<div class="resource-excerpt">
									<?php echo wp_kses_post( $excerpt ); ?>
								</div>
							<?php endif; ?>
							
							<a href="<?php echo esc_url( $permalink ); ?>" class="resource-link">
								<?php esc_html_e( 'Read More', 'resources-cpt' ); ?>
							</a>
						</div>
					</article>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	} else {
		?>
		<p class="resources-empty">
			<?php esc_html_e( 'No resources found.', 'resources-cpt' ); ?>
		</p>
		<?php
	}

	// Reset post data
	wp_reset_postdata();

	// Return the buffered output
	return ob_get_clean();
}
add_shortcode( 'latest_resources', 'resources_cpt_shortcode' );

