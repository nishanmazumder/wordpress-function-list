<?php

// SITE A
// register api.
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'properties/v1',
			'/brouchure',
			array(
				'methods'  => 'GET',
				'callback' => 'brouchure_download',
			)
		);
	}
);

function bti_brouchure_download() {
	$args = array(
		'posts_per_page' => -1,
		'post_type'      => 'properties',
		'post_status'    => 'any',
	);

	$posts = get_posts( $args );

	if ( empty( $posts ) ) {
		return new WP_Error( 'no_posts', 'No posts found', array( 'status' => 404 ) );
	}

	$response = array();

	foreach ( $posts as $post ) {
		$response[] = array(
			'id'        => $post->ID,
			'title'     => $post->post_title,
			'brouchure' => get_field( 'property_brochure', $post->ID ),
		);
	}

	return rest_ensure_response( $response );
}

// SITE B

function fetch_brochure_data_from_bti() {
	$site_a_url = 'https://site.com/wp-json/properties/v1/brouchure';

	$response = wp_remote_get( $site_a_url );

	if ( is_wp_error( $response ) ) {
		return 'Unable to fetch posts.';
	}

	$posts = json_decode( wp_remote_retrieve_body( $response ), true );

	// Sort posts alphabetically by title
	usort(
		$posts,
		function ( $a, $b ) {
			return strcmp( $a['title'], $b['title'] );
		}
	);

	if ( empty( $posts ) ) {
		return 'No posts found.';
	}

	$output = '<ul class="brochure_list">';
	foreach ( $posts as $post ) {
		$link        = ! $post['brouchure'] ? '#' : esc_url( $post['brouchure'] );
		$no_brochure = ! $post['brouchure'] ? '<span class="b_warning">Brochure not available!</span>' : '';
		$output     .= '<li><a target="_blank" href="' . $link . '">' . esc_html( $post['title'] ) . '</a>  ' . $no_brochure . ' </li>';
	}
	$output .= '</ul>';

	return $output;
}

add_shortcode( 'brochure_data', 'fetch_brochure_data_from' );
