<?php
/**
 * Plugin Name: Resources Custom Post Type
 * Plugin URI: https://github.com/rajzshah/grepsr-assessment-task
 * Description: A custom post type for Resources with a shortcode to display latest resources in a responsive grid/list.
 * Version: 1.0.1
 * Author: Raj Shah
 * Author URI: https://raj-shah.com.np/about/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: resources-cpt
 */

if (!defined('ABSPATH')) {
	exit;
}

// plugin version for cache busting
define('RESOURCES_CPT_VERSION', '1.0.0');
define('RESOURCES_CPT_POST_TYPE', 'resources');

/**
 * register the custom post type on init
 */
function resources_cpt_register_post_type()
{
	$labels = array(
		'name' => _x('Resources', 'Post Type General Name', 'resources-cpt'),
		'singular_name' => _x('Resource', 'Post Type Singular Name', 'resources-cpt'),
		'menu_name' => __('Resources', 'resources-cpt'),
		'name_admin_bar' => __('Resource', 'resources-cpt'),
		'archives' => __('Resource Archives', 'resources-cpt'),
		'attributes' => __('Resource Attributes', 'resources-cpt'),
		'parent_item_colon' => __('Parent Resource:', 'resources-cpt'),
		'all_items' => __('All Resources', 'resources-cpt'),
		'add_new_item' => __('Add New Resource', 'resources-cpt'),
		'add_new' => __('Add New', 'resources-cpt'),
		'new_item' => __('New Resource', 'resources-cpt'),
		'edit_item' => __('Edit Resource', 'resources-cpt'),
		'update_item' => __('Update Resource', 'resources-cpt'),
		'view_item' => __('View Resource', 'resources-cpt'),
		'view_items' => __('View Resources', 'resources-cpt'),
		'search_items' => __('Search Resource', 'resources-cpt'),
		'not_found' => __('Not found', 'resources-cpt'),
		'not_found_in_trash' => __('Not found in Trash', 'resources-cpt'),
		'featured_image' => __('Featured Image', 'resources-cpt'),
		'set_featured_image' => __('Set featured image', 'resources-cpt'),
		'remove_featured_image' => __('Remove featured image', 'resources-cpt'),
		'use_featured_image' => __('Use as featured image', 'resources-cpt'),
		'insert_into_item' => __('Insert into resource', 'resources-cpt'),
		'uploaded_to_this_item' => __('Uploaded to this resource', 'resources-cpt'),
		'items_list' => __('Resources list', 'resources-cpt'),
		'items_list_navigation' => __('Resources list navigation', 'resources-cpt'),
		'filter_items_list' => __('Filter resources list', 'resources-cpt'),
	);

	$args = array(
		'label' => __('Resource', 'resources-cpt'),
		'description' => __('Resources custom post type', 'resources-cpt'),
		'labels' => $labels,
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
		'taxonomies' => array(),
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-book-alt',
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
		'show_in_rest' => true,
		'rewrite' => array('slug' => 'resources'),
	);

	// allow filtering of args before registration
	$args = apply_filters('resources_cpt_register_args', $args);

	register_post_type(RESOURCES_CPT_POST_TYPE, $args);
}
add_action('init', 'resources_cpt_register_post_type', 0);

/**
 * enqueue styles only when shortcode is used
 */
function resources_cpt_enqueue_styles()
{
	// only load on frontend
	if (is_admin()) {
		return;
	}

	$css_url = plugin_dir_url(__FILE__) . 'assets/css/resources-style.css';
	$css_path = plugin_dir_path(__FILE__) . 'assets/css/resources-style.css';

	// use filemtime for cache busting in dev
	$version = defined('WP_DEBUG') && WP_DEBUG && file_exists($css_path)
		? filemtime($css_path)
		: RESOURCES_CPT_VERSION;

	wp_enqueue_style(
		'resources-cpt-style',
		$css_url,
		array(),
		$version,
	);
}
add_action('wp_enqueue_scripts', 'resources_cpt_enqueue_styles');

/**
 * get default query args for resources
 * 
 * @param int $limit number of posts to retrieve
 * @return array query arguments
 */
function resources_cpt_get_query_args($limit = 5)
{
	$args = array(
		'post_type' => RESOURCES_CPT_POST_TYPE,
		'posts_per_page' => absint($limit),
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'no_found_rows' => true, // performance: skip pagination count
	);

	// allow filtering query args
	return apply_filters('resources_cpt_query_args', $args, $limit);
}

/**
 * render a single resource item
 * 
 * @param WP_Post $post post object
 * @return string html output
 */
function resources_cpt_render_item($post)
{
	if (!$post instanceof WP_Post) {
		return '';
	}

	$post_id = $post->ID;
	$title = get_the_title($post_id);
	$permalink = get_permalink($post_id);
	$excerpt = has_excerpt($post_id)
		? get_the_excerpt($post_id)
		: wp_trim_words(get_the_content(null, false, $post_id), 20);

	$featured_image = get_the_post_thumbnail(
		$post_id,
		'medium',
		array(
			'class' => 'resource-featured-image',
			'alt' => $title ? esc_attr($title) : '',
		),
	);

	// allow filtering of item data before render
	$item_data = apply_filters('resources_cpt_item_data', array(
		'post_id' => $post_id,
		'title' => $title,
		'permalink' => $permalink,
		'excerpt' => $excerpt,
		'featured_image' => $featured_image,
	), $post);

	ob_start();
	?>
	<article class="resource-item">
		<?php if (!empty($item_data['featured_image'])): ?>
			<div class="resource-image">
				<a href="<?php echo esc_url($item_data['permalink']); ?>" aria-label="<?php echo esc_attr($item_data['title']); ?>">
					<?php echo $item_data['featured_image']; ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="resource-content">
			<h3 class="resource-title">
				<a href="<?php echo esc_url($item_data['permalink']); ?>">
					<?php echo esc_html($item_data['title']); ?>
				</a>
			</h3>

			<?php if (!empty($item_data['excerpt'])): ?>
				<div class="resource-excerpt">
					<?php echo wp_kses_post($item_data['excerpt']); ?>
				</div>
			<?php endif; ?>

			<a href="<?php echo esc_url($item_data['permalink']); ?>" class="resource-link">
				<?php esc_html_e('Read More', 'resources-cpt'); ?>
			</a>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * shortcode handler for displaying latest resources
 * usage: [latest_resources limit="5"]
 * 
 * @param array $atts shortcode attributes
 * @return string html output
 */
function resources_cpt_shortcode($atts)
{
	$atts = shortcode_atts(
		array(
			'limit' => 5,
		),
		$atts,
		'latest_resources',
	);

	$limit = absint($atts['limit']);
	if ($limit < 1) {
		$limit = 5;
	}

	$query_args = resources_cpt_get_query_args($limit);
	$resources_query = new WP_Query($query_args);

	ob_start();

	if ($resources_query->have_posts()) {
		// allow filtering of wrapper classes
		$container_class = apply_filters('resources_cpt_container_class', 'resources-container');
		$grid_class = apply_filters('resources_cpt_grid_class', 'resources-grid');
		?>
		<div class="<?php echo esc_attr($container_class); ?>">
			<div class="<?php echo esc_attr($grid_class); ?>">
				<?php
				while ($resources_query->have_posts()) {
					$resources_query->the_post();
					echo resources_cpt_render_item(get_post());
				}
				?>
			</div>
		</div>
		<?php
	} else {
		$empty_message = apply_filters('resources_cpt_empty_message', __('No resources found.', 'resources-cpt'));
		?>
		<p class="resources-empty">
			<?php echo esc_html($empty_message); ?>
		</p>
		<?php
	}

	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode('latest_resources', 'resources_cpt_shortcode');

