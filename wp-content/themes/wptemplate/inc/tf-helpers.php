<?php

define( 'TF_FONTELLO_CONFIG_PATH', 'assets/config/fontello-config.json' );

/**
 * Disable comments for posts and pages.
 */
add_action( 'init', function() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' );
});

// Remove REST API info from head and headers
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

add_filter( 'wp_nav_menu_items', 'tf_fix_target_blank' );
add_filter( 'the_content', 'tf_fix_target_blank' );
add_filter( 'acf_the_content', 'tf_fix_target_blank' );

function tf_fix_target_blank( $html ) {
	return preg_replace( '/(target="_blank")/', '\1 rel="noopener noreferrer"', $html );
}

add_filter( 'script_loader_src', 'tf_filter_double_slash_src' );
add_filter( 'style_loader_src', 'tf_filter_double_slash_src' );

function tf_filter_double_slash_src( $src ) {
	if ( false !== strpos( $src, home_url() ) ) {
		$src = preg_replace( '/^https?\:/', '', $src );
	}

	return $src;
}

function tf_wp_error( $message, $data = '', $code = '' ) {
	if ( empty( $code ) ) {
		$code = sanitize_key( $message );
	}

	return new WP_Error( $code, $message, $data );
}

/**
 * Helps outputting HTML classes in a comfortable way.
 *
 * Example:
 *
 *     $class = tf_class([
 *         'first-class' => 1,
 *         'second-class' => get_field( 'boolean_field', 'option' ),
 *      ]);
 *
 *      <div<?php $class; ?>></div>
 *
 * Output: ' class="first-class"'
 * If the get_field() function returns a "falsy" value.
 *
 * @param  array   $array		Associative array with keys as the desired HTML classes and values.
 *                         		The values will be converted to booleans and therefor values that evaluates to "false"
 *                         		will not have their respective HTML class (key) be returned or outputted.
 * @param  boolean $echo		True to echo the result.
 * @param  boolean $as_array	True to return the result. (Regardless of the value of $echo.)
 * @return mixed Return null, string or array, se params above.
 */
function tf_class( $array = [], $echo = true, $as_array = false ) {
	$class_array = array_filter( $array );

	if ( empty( $class_array ) ) {
		if ( $as_array ) {
			$class_array = [];
		} else {
			if ( $echo ) {
				echo '';
				$class_array = null;
			} else {
				$class_array = '';
			}
		}

		return $class_array;
	}

	$classes = array_keys( $class_array );

	if ( $as_array ) {
		return $classes;
	}

	$class_string = implode( ' ', $classes );

	if ( $echo ) {
		printf( ' class="%s"', esc_attr( $class_string ) );

		$class_string = null;
	}

	return $class_string;
}

function tf_get_posts_with_component( $component ) {
	global $wpdb;

	return $wpdb->get_col(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->posts} p, {$wpdb->postmeta} pm WHERE p.ID = pm.post_id AND p.post_status = 'publish' AND pm.meta_value LIKE '%s'",
			sprintf( '%1$s%2$s%1$s', '%', $wpdb->esc_like( $component ) )
		)
	);
}

function tf_purge_url( $url ) {
	$handle = curl_init( $url );

	curl_setopt( $handle, CURLOPT_CUSTOMREQUEST, 'PURGE' );
	curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );

	return curl_exec( $handle );
}

/**
* Add Favicons to wp-admin and wp-login.php
**/
function add_favicons() {
	get_template_part( 'templates/parts/favicons' );
}

add_action( 'login_head', 'add_favicons' );
add_action( 'admin_head', 'add_favicons' );
add_action( 'wp_head', 'add_favicons' );

function tf_add_ajax_handler( $action, $handler_function ) {
	return TF\TF_Ajax::add_handler( $action, $handler_function );
}

add_action( 'after_setup_theme', function() {
	load_theme_textdomain( 'wptemplate', get_template_directory() . '/inc/languages' );

	add_editor_style( '/assets/dist/editor-style.min.css' );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
});

/**
 * Remove inline width set on WP captions.
 */
add_filter( 'img_caption_shortcode_width', '__return_zero' );

add_action( 'wp_head', function() {
	?>
	<style>
		body {
			opacity: 0;
			background-color: #fff;
		}
	</style>
	<?php
});

add_action( 'admin_head', function() {
	?>
	<style>
		#wp-admin-bar-comments {
			display: none !important;
		}
	</style>
	<?php
});

/**
 * Remove unnecessary meta boxes.
 */
add_action( 'admin_init', function() {
	remove_meta_box( 'postcustom', 'post', 'normal' );
	remove_meta_box( 'postcustom', 'page', 'normal' );
});

add_filter( 'single_template', function( $template ) {
	return tf_theme_path_helper( 'templates/single/' . get_post_type() . '.php', $template );
});
add_filter( 'archive_template', function( $template ) {
	if ( is_tax() || is_category() || is_tag() ) {
		return tf_theme_path_helper( 'templates/archive/' . get_queried_object()->taxonomy . '.php', $template );
	}

	if ( is_post_type_archive() ) {
		return tf_theme_path_helper( 'templates/archive/' . get_queried_object()->name . '.php', $template );
	}

	return $template;
});
add_filter( 'home_template', function( $template ) {
	return tf_theme_path_helper( 'templates/archive/' . get_post_type() . '.php', $template );
});

function tf_theme_path_helper( $path, $fallback = '' ) {
	$path = sprintf( '%s/%s', get_stylesheet_directory(), trim( $path, '/' ) );

	return ( file_exists( $path ) ? $path : $fallback );
}

/**
 * Filters several urls to fix cross-domain css and js problem when using multiple domains with Polylang.
 */
if (
	function_exists( 'pll_home_url' ) &&
	get_option( 'polylang', false ) &&
	isset( get_option( 'polylang', false )['force_lang'] ) &&
	3 === get_option( 'polylang', false )['force_lang']
) {
	function tf_filter_polylang_url( $url ) {
		return str_replace( untrailingslashit( get_option( 'home' ) ), 'http://' . $_SERVER['HTTP_HOST'], $url );
	}

	add_filter( 'stylesheet_directory_uri', 'tf_filter_polylang_url' );
	add_filter( 'template_directory_uri', 'tf_filter_polylang_url' );
	add_filter( 'plugins_url', 'tf_filter_polylang_url' );
	add_filter( 'upload_dir', function( $wp_upload_dir ) {
		$wp_upload_dir['url'] = tf_filter_polylang_url( $wp_upload_dir['url'] );
		$wp_upload_dir['baseurl'] = tf_filter_polylang_url( $wp_upload_dir['baseurl'] );

		return $wp_upload_dir;
	});
}

/**
 * Fetch Fontello icon-classes as numerical array with the classes being values or
 * as associative array with the classes being both keys and values.
 *
 * @param  boolean $for_select true for a associative array with the classes being both keys and values.
 * @return array              The Fontello icon-classes.
 */
function tf_get_fontello_classes( $for_select = false ) {
	$classes = [];

	if ( ! defined( 'TF_FONTELLO_CONFIG_PATH' ) ) {
		return $classes;
	}

	$config_path = sprintf( '%s/%s', get_stylesheet_directory(), TF_FONTELLO_CONFIG_PATH );

	if ( ! file_exists( $config_path ) ) {
		return $classes;
	}

	// Fetch modification times
	$saved_modification_time = get_option( 'tf_fontello_config_modification_time', false );
	$file_modification_time = filemtime( $config_path );

	if (
		false === $saved_modification_time ||
		$file_modification_time > $saved_modification_time
	) {
		$config = file_get_contents( $config_path );
		$config = json_decode( $config );

		if ( ! isset( $config->css_prefix_text ) ) {
			return $classes;
		}

		$class_prefix = $config->css_prefix_text;

		foreach ( $config->glyphs as $icon ) {
			if (
				! isset( $icon->selected ) ||
				true === $icon->selected
			) {
				$classes[] = sanitize_html_class( $class_prefix . $icon->css );
			}
		}

		update_option( 'tf_fontello_classes', $classes, false );
		update_option( 'tf_fontello_config_modification_time', $file_modification_time, false );
	} else {
		$classes = get_option( 'tf_fontello_classes', [] );
	}

	if (
		true === $for_select &&
		! empty( $classes )
	) {
		$classes = array_combine( $classes, $classes );
	}

	return $classes;
}

/**
 * Remove current classes from page_for_posts menu item when on an custom post type archive or single url.
 */
add_filter( 'nav_menu_css_class', function( $classes, $item ) {
	if (
		get_option( 'page_for_posts' ) === $item->object_id &&
		(
			( is_post_type_archive() || is_singular() ) &&
			! is_singular( 'post' )
		)
	) {
		return [];
	}

	return $classes;
}, 10, 2 );

if ( ! function_exists( '_wp_render_title_tag' ) ) :
	add_action( 'wp_head', function() {
		?>
		<title><?php wp_title( '' ); ?></title>
		<?php
	});
endif;

/**
 * Remove WP gallery styles
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Custom login screen css
 */
add_action( 'login_head', function() {
	echo "<style>
		.login h1 a {
			background-image: url( '" . get_template_directory_uri() . "/assets/img/logo.svg' );
			background-size: 223px 38px;
			width: 223px;
			height: 38px;
		}
		body.login #nav, body.login #backtoblog { text-align: center; }
	</style>";
});

/**
 * Pretty print
 */
function pr() {
	$output = '<pre>';

	foreach ( func_get_args() as $var ) {
		ob_start(); is_scalar( $var ) ? var_dump( $var ) : print_r( $var ); $output .= ob_get_clean();
	}

	$output .= '</pre>';

	echo $output;
}

function pr_log( $var, $label = '' ) {
	if ( is_null( $var ) ) {
		$value = 'NULL';
	} elseif ( is_bool( $var ) ) {
		$value = ( $var ) ? 'true' : 'false';
	} else {
		$value = print_r( $var, true );
	}

	error_log( sprintf( '%s%s', $label ? $label . ' ' : '', $value ) );
}

/**
 * Retrieve the URL of the ajaxhandler
 * @return string URL of the file.
 */
function tf_get_ajaxurl() {
	return esc_url( TF\TF_Ajax::get_url() );
}

/**
 * Removes admin bar items
 */
add_action( 'wp_before_admin_bar_render', function(){
	global $wp_admin_bar;

	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'comments' );
	$wp_admin_bar->remove_menu( 'customize' );
});

/**
 * Removes Emoji support
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Widgets init
 *
 */
add_action( 'widgets_init', function(){
	// Remove widgets
	unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Text' );

	//Polylang
	unregister_widget( 'PLL_Widget_Calendar' );
	unregister_widget( 'PLL_Widget_Languages' );

	//Gravity forms
	unregister_widget( 'GFWidget' );

}, 11 );

/**
 * Hide admin menu pages
 */
add_action( 'admin_menu', function(){
	remove_menu_page( 'edit-comments.php' );

	if ( ! current_user_can( 'manage_options' ) ) {
		remove_menu_page( 'tools.php' );
		remove_submenu_page( 'themes.php', 'customize.php' );
	}
});

/**
 * Filter for file names
 */
add_filter( 'sanitize_file_name', function( $filename ) {
	if ( false !== mb_strpos( $filename, '.' ) ) {
		$extension = explode( '.', $filename );
		$extension = end( $extension );

		$filename = mb_substr( $filename, 0, mb_strrpos( $filename, '.' ) );
		$filename = sprintf( '%s.%s', sanitize_key( $filename ), $extension );
	}

	return $filename;
}, 99 );

/**
 * Remove WP version nr
 */
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Filter function to alter the length of excerpts
 */
add_filter( 'excerpt_length', function( $length ) {
	return 25;
});

/**
 * Filter function to alter the ending of excerpts
 */
add_filter( 'excerpt_more', function( $more ) {
	return '...';
});

/**
 * Action to keep tree structure for categories.
 */
add_action( 'wp_terms_checklist_args', function( $args ) {
	if ( is_admin() && 'post' === get_current_screen()->base ) {
		$args['checked_ontop'] = false;
	}

	return $args;
});

/**
 * Filter oEmbed HTML
 */
add_filter( 'oembed_result', function( $html, $url, $args ) {
	$regexes = [
		'youtube' => [
			'#http://((m|www)\.)?youtube\.com/watch.*#i',
			'#https://((m|www)\.)?youtube\.com/watch.*#i',
			'#http://((m|www)\.)?youtube\.com/playlist.*#i',
			'#https://((m|www)\.)?youtube\.com/playlist.*#i',
			'#http://youtu\.be/.*#i',
			'#https://youtu\.be/.*#i',
		],
		'vimeo' => [
			'#https?://(.+\.)?vimeo\.com/.*#i',
		],
		'container' => [
			'#http://((m|www)\.)?youtube\.com/watch.*#i',
			'#https://((m|www)\.)?youtube\.com/watch.*#i',
			'#http://((m|www)\.)?youtube\.com/playlist.*#i',
			'#https://((m|www)\.)?youtube\.com/playlist.*#i',
			'#http://youtu\.be/.*#i',
			'#https://youtu\.be/.*#i',
			'#https?://(.+\.)?vimeo\.com/.*#i',
		],
	];

	$types = [];

	foreach ( $regexes as $type => $type_regexes ) {
		foreach ( $type_regexes as $type_regex ) {
			if ( 1 === preg_match( $type_regex, $url ) ) {
				$types[] = $type;

				break;
			}
		}
	}

	if ( empty( $types ) ) {
		return $html;
	}

	if ( array_intersect( [ 'youtube', 'vimeo' ], $types ) ) {
		$src = preg_match(
			'/src="([^"]+)/i',
			$html,
			$matches
		);

		if ( ! empty( $matches[1] ) ) {
			$args = [];

			if ( in_array( 'youtube', $types ) ) {
				$src = add_query_arg(
					[
						'feature' => 'oembed',
						'rel' => 0,
						'showinfo' => 0,
					],
					$matches[1]
				);
			} elseif ( in_array( 'vimeo', $types ) ) {
				$src = add_query_arg(
					[
						'byline' => 'false',
						'title' => 'false',
					],
					$matches[1]
				);
			}

			$src = esc_url( $src );

			$html = preg_replace(
				'/(src=")([^"]+)/i',
				'${1}' . $src,
				$html
			);
		}
	}

	if ( in_array( 'container', $types ) ) {
		$html = preg_replace(
			'/\s+width="[^"]+"/i',
			'',
			$html
		);

		$html = preg_replace(
			'/\s+height="[^"]+"/i',
			'',
			$html
		);

		$html = '<div class="embed-container">' . $html . '</div>';
	}

	return $html;
}, 10, 3 );

/**
 * Stop Redirect Canonical from trying to redirect 404 errors.
 * @link https://core.trac.wordpress.org/ticket/16557
 **/
add_filter( 'redirect_canonical', function( $url ) {
	return ( is_404() ) ? false : $url;
});

add_filter( 'upload_mimes', function( $mimes ) {
	$mimes['vcf'] = 'text/x-vcard';

	return $mimes;
});
