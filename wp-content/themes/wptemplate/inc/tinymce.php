<?php

/**
 * Add styles/classes to the "Styles" drop-down
 */
add_filter( 'mce_buttons_2', function( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
});

add_filter( 'tiny_mce_before_init', function( $settings ) {
	$style_formats = [
		[
			'title' => __( 'Text Styles', 'wptemplate' ),
			'items' => [
				[
					'title' => 'Preamble',
					'selector' => 'p',
					'classes' => 'preamble',
				],
			],
		],
		[
			'title' => __( 'Buttons', 'wptemplate' ),
			'items' => [
				[
					'title' => __( 'Default', 'wptemplate' ),
					'selector' => 'a',
					'attributes' => [ 'class' => 'btn' ],
				],
			],
		],
	];

	$block_formats = [
		'p' => __( 'Paragraph', 'wptemplate' ),
		'h1' => sprintf( '%s 1', __( 'Heading', 'wptemplate' ) ),
		'h2' => sprintf( '%s 2', __( 'Heading', 'wptemplate' ) ),
		'h3' => sprintf( '%s 3', __( 'Heading', 'wptemplate' ) ),
		'h4' => sprintf( '%s 4', __( 'Heading', 'wptemplate' ) ),
	];

	$test = array_map( function( $label, $element ) {
		return sprintf( '%s=%s', $label, $element );
	}, array_values( $block_formats ), array_keys( $block_formats ) );

	$settings['block_formats'] = implode( ';', $test );

	$settings['style_formats'] = json_encode( $style_formats );

	$settings['body_class'] .= ' entry-content';

	return $settings;
});

/**
 * Remove tinymce buttons row 1
 */
add_filter( 'mce_buttons', function( $buttons ) {
	//unset( $buttons[0] ); // bold
	//unset( $buttons[1] ); // italic
	unset( $buttons[2] ); // strikethrough
	//unset( $buttons[3] ); // bullist
	//unset( $buttons[4] ); // numlist
	//unset( $buttons[5] ); // blockquote
	//unset( $buttons[6] ); // hr
	//unset( $buttons[7] ); // alignleft
	//unset( $buttons[8] ); // aligncenter
	//unset( $buttons[9] ); // alignright
	//unset( $buttons[10] ); // unlink
	unset( $buttons[11] ); // wp_more
	unset( $buttons[12] ); // spellchecker
	//unset( $buttons[13] ); // fullscreen
	//unset( $buttons[14] ); // wp_adv
	$buttons[] = 'table';

	return $buttons;
});

/**
 * Remove tinymce buttons row 2
 */
add_filter( 'mce_buttons_2', function( $buttons ) {
	//unset( $buttons[0] ); // formatselect
	//unset( $buttons[1] ); // underline
	unset( $buttons[2] ); // alignjustify
	unset( $buttons[3] ); // forecolor
	//unset( $buttons[4] ); // pastetext
	//unset( $buttons[5] ); // removeformat
	unset( $buttons[6] ); // charmap
	unset( $buttons[7] ); // outdent
	unset( $buttons[8] ); // indent
	unset( $buttons[9] ); // undo
	unset( $buttons[10] ); // redo
	unset( $buttons[11] ); // wp_help

	return $buttons;
});
