<?php

define( 'TF_ACF_PRO_LICENSE_KEY', 'b3JkZXJfaWQ9MzkzMTh8dHlwZT1kZXZlbG9wZXJ8ZGF0ZT0yMDE0LTA5LTA5IDE0OjE0OjUx' );

/**
 * Update ACF PRO license key automatically when using WP CLI search-replace command.
 */
if ( defined( 'WP_CLI' ) && WP_CLI && defined( 'TF_ACF_PRO_LICENSE_KEY' ) ) {
	WP_CLI::add_hook( 'after_invoke:search-replace', function() {
		if ( function_exists( 'acf_pro_update_license' ) ) {
			acf_pro_update_license( TF_ACF_PRO_LICENSE_KEY );
		}
	});
}

/**
 * Polylang compability on ACF option pages
 */
if ( function_exists( 'pll_default_language' ) ) {
	add_filter('acf/settings/default_language', function( $language ) {
		return pll_default_language();
	});

	add_filter('acf/settings/current_language', function( $language ) {
		return pll_current_language();
	});
}

/**
 * Filter for Gravity Forms
 */
add_filter( 'acf/load_field/name=form', function( $field = [] ) {
	$choices = [];

	if ( class_exists( 'GFAPI' ) ) {
		$forms = GFAPI::get_forms();

		foreach ( $forms as $form ) {
			$choices[ $form['id'] ] = $form['title'];
		}
	}

	$field['choices'] = $choices;

	return $field;
});

/**
 * Filter for colors
 */
add_filter( 'acf/load_field/name=color', function( $field = [] ) {
	$field['choices'] = [
		'green' => __( 'Green', 'wptemplate' ),
	];

	return $field;
});

/**
 * Option pages
 */
add_action( 'acf/init', function() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page([
			'page_title' => __( 'Theme Settings', 'wptemplate' ),
			'menu_title' => __( 'Theme Settings', 'wptemplate' ),
			'menu_slug' => 'wptemplate_options',
			'redirect' => false,
			'icon_url' => 'dashicons-welcome-widgets-menus',
			'position' => 25,
		]);

		if ( function_exists( 'acf_add_options_sub_page' ) ) {
			// Pages
			// acf_add_options_sub_page( array(
			//     'title' => __( 'Posts Settings', 'wptemplate' ),
			//     'menu' =>  __( 'Posts Settings', 'wptemplate' ),
			//     'parent' => 'edit.php?post_type=page',
			//     'capability' => 'manage_options'
			// ));
		}
	}
});

/**
 * Change select2 version
 */
add_filter( 'acf/settings/select2_version', function() {
	return 4;
});

/**
 * Default the target parameter of the ACF link field to the HTML default "_self".
 */
add_filter( 'acf/format_value/type=link', function( $value ) {
	if ( isset( $value['target'] ) && empty( $value['target'] ) ) {
		$value['target'] = '_self';
	}

	return $value;
}, 20 );
