<?php

namespace TF\REST;

use WP_Error;

function remove_author_links( $response ) {
	$response->remove_link( 'author' );

	$data = $response->get_data();

	unset( $data['author'] );

	$response->set_data( $data );

	return $response;
}

add_action( 'init', function() {
	$post_types = get_post_types( [ 'show_in_rest' => true ] );

	foreach ( $post_types as $post_type ) {
		add_filter( 'rest_prepare_' . $post_type, __NAMESPACE__ . '\remove_author_links' );
	}
}, 100 );

add_filter( 'rest_user_query', function( $query_args ) {
	$query_args['search'] = 'theoneringtorulethemall';

	return $query_args;
});

add_filter( 'rest_prepare_user', function( $response ) {
	return new WP_Error( 'rest_user_invalid_id', __( 'Invalid user ID.' ), array( 'status' => 404 ) );
});
