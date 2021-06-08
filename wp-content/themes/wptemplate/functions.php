<?php

// Dependencies
require_once 'inc/class-tf-post-type.php';
require_once 'inc/class-tf-taxonomy.php';
require_once 'inc/class-tf-ajax.php';

require_once 'inc/tf-helpers.php';
require_once 'inc/theme.php';
require_once 'inc/acf.php';
require_once 'inc/tinymce.php';
require_once 'inc/ajax-functions.php';
require_once 'inc/sub-nav.php';
require_once 'inc/rest.php';

if ( class_exists( 'GFForms' ) ) {
	require_once 'inc/gravity-forms.php';
}

require_once 'inc/cpt.php';
// require_once 'inc/acf-icons.php';

add_action( 'after_setup_theme', function() {
	register_nav_menu( 'primary', __( 'Primary Menu', 'wptemplate' ) );

	//add_image_size( 'theme_img_name', width, height, crop(true|false) );
});

add_action( 'wp_enqueue_scripts', function() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', get_stylesheet_directory_uri() . '/assets/dist/jquery.min.js', '', '2.2.4', true );

		wp_register_script( 'app', get_stylesheet_directory_uri() . '/assets/dist/app.min.js', [ 'jquery' ], '1.0', true );
		wp_enqueue_script( 'app' );

		wp_deregister_script( 'wp-embed' );

		wp_deregister_script( 'gform_placeholder' );

		wp_localize_script(
			'app',
			'theme',
			[
				'ajaxurl' => tf_get_ajaxurl(),
				'cookieMessageText' => get_field( 'cookie_message_text', 'options', false ),
				'cookieAcceptText' => get_field( 'cookie_accept_text', 'options' ),
			]
		);

		wp_register_style( 'style', get_stylesheet_directory_uri() . '/assets/dist/style.min.css', [], '1.0' );
		wp_enqueue_style( 'style' );
	}
});
