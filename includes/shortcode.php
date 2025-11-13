<?php
// get default query args for resources
function resources_cpt_get_query_args( $limit = 5 ) {
	$args = array(
		'post_type' => RESOURCES_CPT_POST_TYPE,
		'posts_per_page' => absint( $limit ),
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'no_found_rows' => true, // skip pagination count for performance
	);

	return apply_filters( 'resources_cpt_query_args', $args, $limit );
}

// render a single resource card
function resources_cpt_render_item( $post ) {
	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	$post_id = $post->ID;
	$title = get_the_title( $post_id );
	$permalink = get_permalink( $post_id );
	$excerpt = has_excerpt( $post_id )
		? get_the_excerpt( $post_id )
		: wp_trim_words( get_the_content( null, false, $post_id ), 20 );

	// build featured image with basic fallback
	$featured_image = get_the_post_thumbnail(
		$post_id,
		'medium',
		array(
			'class' => 'resource-featured-image',
			'alt' => $title ? esc_attr( $title ) : '',
			'loading' => 'lazy',
		)
	);

	if ( empty( $featured_image ) ) {
		// use bundled placeholder when no thumbnail is set
		$placeholder_src = apply_filters(
			'resources_cpt_fallback_image_src',
			RESOURCES_CPT_URL . 'assets/images/placeholder.svg',
			$post_id
		);

		$featured_image = sprintf(
			'<img class="resource-featured-image is-fallback" src="%s" alt="%s" loading="lazy" />',
			esc_url( $placeholder_src ),
			esc_attr( $title ? $title : __( 'Resource', 'resources-cpt' ) )
		);
	}

	$item_data = apply_filters(
		'resources_cpt_item_data',
		array(
			'post_id' => $post_id,
			'title' => $title,
			'permalink' => $permalink,
			'excerpt' => $excerpt,
			'featured_image' => $featured_image,
		),
		$post
	);

	ob_start();
	?>
	<article class="resource-item">
		<div class="resource-image">
			<a href="<?php echo esc_url( $item_data['permalink'] ); ?>" aria-label="<?php echo esc_attr( $item_data['title'] ); ?>">
				<?php echo $item_data['featured_image']; ?>
			</a>
		</div>

		<div class="resource-content">
			<h3 class="resource-title">
				<a href="<?php echo esc_url( $item_data['permalink'] ); ?>">
					<?php echo esc_html( $item_data['title'] ); ?>
				</a>
			</h3>

			<?php if ( ! empty( $item_data['excerpt'] ) ) : ?>
				<div class="resource-excerpt">
					<?php echo wp_kses_post( $item_data['excerpt'] ); ?>
				</div>
			<?php endif; ?>

			<a href="<?php echo esc_url( $item_data['permalink'] ); ?>" class="resource-link">
				<?php esc_html_e( 'Read More', 'resources-cpt' ); ?>
			</a>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

// shortcode handler for displaying latest resources
// usage: [latest_resources limit="5"]
function resources_cpt_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit' => 5,
		),
		$atts,
		'latest_resources'
	);

	$limit = absint( $atts['limit'] );
	if ( $limit < 1 ) {
		$limit = 5;
	}

	$query_args = resources_cpt_get_query_args( $limit );
	$resources_query = new WP_Query( $query_args );

	ob_start();

	if ( $resources_query->have_posts() ) {
		$container_class = apply_filters( 'resources_cpt_container_class', 'resources-container' );
		$grid_class = apply_filters( 'resources_cpt_grid_class', 'resources-grid' );
		?>
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="<?php echo esc_attr( $grid_class ); ?>">
				<?php
				while ( $resources_query->have_posts() ) {
					$resources_query->the_post();
					echo resources_cpt_render_item( get_post() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</div>
		<?php
	} else {
		$empty_message = apply_filters( 'resources_cpt_empty_message', __( 'No resources found.', 'resources-cpt' ) );
		?>
		<p class="resources-empty">
			<?php echo esc_html( $empty_message ); ?>
		</p>
		<?php
	}

	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'latest_resources', 'resources_cpt_shortcode' );


