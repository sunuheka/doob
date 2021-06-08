<?php

/**
 * Allow TF Office to access the site even though WP Force Login is active
 */
add_filter( 'v_forcelogin_bypass', function() {
	$allowed_ip_addresses = [
		'158.174.69.114',
	];

	return in_array( $_SERVER['REMOTE_ADDR'], $allowed_ip_addresses );
});

/**
 * Pre get posts filter
 */
add_filter( 'pre_get_posts', function( $query ) {

	if ( $query->is_main_query() && ! is_admin() ) {

		// Redirect empty searches to search page, not home
		if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
			$query->is_search = true;
			$query->is_home = false;
		}

		/*
			$query->set( 'posts_per_page', 7 );
			$query->set( 'nopaging', true );
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
			$query->set( 'post_type', 'wptemplate_cpt' );
		*/
	}

	return $query;
});

add_action( 'admin_bar_menu', function( $admin_bar ) {
	if ( ! current_user_can( 'administrator' ) || is_admin() ) {
		return;
	}
	global $template;

	$template_name = str_replace( get_template_directory() . '/', '', $template );
	$args = [
		'id'    => 'my-template',
		'title' => 'Template: ' . $template_name,
		'href'  => '#',
		'meta'  => [
			'title' => $template_name,
		],
	];

	$admin_bar->add_menu( $args );
},  100 );
