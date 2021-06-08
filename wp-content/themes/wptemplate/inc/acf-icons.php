<?php

/**
* Filter for icons
*/

add_filter( 'acf/load_field/name=icon', function( $field = [] ) {

	$icons = tf_get_fontello_classes();

	foreach ( $icons as $icon ) {
		$field['choices'][ $icon ] = $icon;
	}

	return $field;
});

add_action('acf/input/admin_footer', function() {
	?>
	<script type="text/javascript">
		(function($) {

			acf.add_filter('select2_args', function( args, $select, settings ){

				// do something to args

				args.formatResult = select2IconsFormat;
				args.formatSelection = select2IconsFormat;

				// return
				return args;

			});

			function select2IconsFormat( icon ) {

				if ( icon ) {
					if ( icon.text.indexOf( 'icon-' ) > -1 ) {
						var text = '<span class="select2-icon-text">' + icon.text.replace( 'icon-', '' ) + '</span>';
						return '<i class="' + icon.text + '"></i>';
					}
					return icon.text;
				}

			};

		})(jQuery);
	</script>
	<?php
});

/**
 * Add styling for acf/select2
 *
 */
add_action( 'admin_head', function() {
	echo "
		<style>
			.select2-result-label i[class*='icon-'],
			.select2-chosen i[class*='icon-'] {
				font-size: 20px;
			}

			.select2-results .select2-highlighted i[class*='icon-'],
			.select2-results .select2-highlighted .select2-icon-text {
				color: inherit;
			}

			.select2-icon-text {
				display: block;
				float: left;
				text-overflow: ellipsis;
				overflow: hidden;
				width: 70%;
				white-space: nowrap;
			}

		</style>
	";
});

/**
 * Enqueue fontello for use in acf/select2
 *
 */
add_action( 'admin_enqueue_scripts', function() {
	wp_register_style( 'fontello', get_template_directory_uri() . '/assets/dist/fontello.css', array(), '1.0' );
	wp_enqueue_style( 'fontello' );
});
