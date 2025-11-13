<?php
// register the custom post type on init
function resources_cpt_register_post_type() {
	$labels = array(
		'name' => _x( 'Resources', 'Post Type General Name', 'resources-cpt' ),
		'singular_name' => _x( 'Resource', 'Post Type Singular Name', 'resources-cpt' ),
		'menu_name' => __( 'Resources', 'resources-cpt' ),
		'name_admin_bar' => __( 'Resource', 'resources-cpt' ),
		'archives' => __( 'Resource Archives', 'resources-cpt' ),
		'attributes' => __( 'Resource Attributes', 'resources-cpt' ),
		'parent_item_colon' => __( 'Parent Resource:', 'resources-cpt' ),
		'all_items' => __( 'All Resources', 'resources-cpt' ),
		'add_new_item' => __( 'Add New Resource', 'resources-cpt' ),
		'add_new' => __( 'Add New', 'resources-cpt' ),
		'new_item' => __( 'New Resource', 'resources-cpt' ),
		'edit_item' => __( 'Edit Resource', 'resources-cpt' ),
		'update_item' => __( 'Update Resource', 'resources-cpt' ),
		'view_item' => __( 'View Resource', 'resources-cpt' ),
		'view_items' => __( 'View Resources', 'resources-cpt' ),
		'search_items' => __( 'Search Resource', 'resources-cpt' ),
		'not_found' => __( 'Not found', 'resources-cpt' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'resources-cpt' ),
		'featured_image' => __( 'Featured Image', 'resources-cpt' ),
		'set_featured_image' => __( 'Set featured image', 'resources-cpt' ),
		'remove_featured_image' => __( 'Remove featured image', 'resources-cpt' ),
		'use_featured_image' => __( 'Use as featured image', 'resources-cpt' ),
		'insert_into_item' => __( 'Insert into resource', 'resources-cpt' ),
		'uploaded_to_this_item' => __( 'Uploaded to this resource', 'resources-cpt' ),
		'items_list' => __( 'Resources list', 'resources-cpt' ),
		'items_list_navigation' => __( 'Resources list navigation', 'resources-cpt' ),
		'filter_items_list' => __( 'Filter resources list', 'resources-cpt' ),
	);

	$args = array(
		'label' => __( 'Resource', 'resources-cpt' ),
		'description' => __( 'Resources custom post type', 'resources-cpt' ),
		'labels' => $labels,
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
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
		'rewrite' => array( 'slug' => 'resources' ),
	);

	// allow filtering of args before registration
	$args = apply_filters( 'resources_cpt_register_args', $args );

	register_post_type( RESOURCES_CPT_POST_TYPE, $args );
}
add_action( 'init', 'resources_cpt_register_post_type', 0 );


