<?php

/**
 * Use $params instead of $_GET or $_POST.
 */
tf_add_ajax_handler( 'successful_action', function( $params ) {
	$limit = absint( $params['limit'] );

	return [
		'the_limit' => $limit,
	];
});

tf_add_ajax_handler( 'error_action', function( $params ) {
	if ( empty( $params['category'] ) ) {
		return new WP_Error( 'code', 'You did wrong.', [ 'status' => 400 ] );
	}

	return true;
});
