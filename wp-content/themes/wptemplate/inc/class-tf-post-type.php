<?php

/**
 * Example usage:
 *
 * new TF_Post_Type(
 * 	'employee',
 * 	[
 * 		'has_archive' => false,
 * 		'public' => true,
 * 		'show_ui' => true,
 * 		'menu_icon' => 'dashicons-exerpt-view',
 * 		'supports' => [
 * 			'title',
 * 			'thumbnail',
 * 			'editor',
 * 		],
 * 		'rewrite' => false,
 * 		'menu_position' => 24,
 * 	],
 * 	__( 'Employees', 'wptemplate' ),
 * 	__( 'Employee', 'wptemplate' )
 * );
 */

class TF_Post_Type {

	public static function register( $name, $args, $label, $singular_label = '' ) {
		return new self( $name, $args, $label, $singular_label );
	}

	public function __construct( $name, $args, $label, $singular_label = '' ) {
		if ( empty( $name ) ) {
			throw new Exception( '$name parameter required.', 1 );
		}

		if ( 'init' !== current_action() ) {
			throw new Exception( 'TF_Post_Type must be initialized in the "init" action.', 1 );
		}

		$this->name = $name;

		$this->label = $label;
		$this->singular_label = ( $singular_label ) ? $singular_label : $label;

		$this->args = array_merge_recursive(
			[
				'labels' => $this->get_labels(),
			],
			$args
		);


		$this->register_post_type();
		$this->add_revision_support();
	}

	public function get_labels() {
		return [
			'name'					=> $this->label,
			'singular_name'			=> $this->singular_label,
			'add_new'				=> sprintf( __( 'Add %s', 'wptemplate' ), $this->singular_label ),
			'add_new_item'			=> sprintf( __( 'Add new %s', 'wptemplate' ), $this->singular_label ),
			'edit_item'				=> sprintf( __( 'Edit %s', 'wptemplate' ), $this->singular_label ),
			'view_item'				=> sprintf( __( 'View %s', 'wptemplate' ), $this->singular_label ),
			'view_items'			=> sprintf( __( 'View %s', 'wptemplate' ), $this->label ),
			'search_items'			=> sprintf( __( 'Search %s', 'wptemplate' ), $this->label ),
			'not_found'				=> sprintf( __( 'No %s found', 'wptemplate' ), $this->label ),
			'not_found_in_trash'	=> sprintf( __( 'No %s found in trash', 'wptemplate' ), $this->label ),
			'parent_item_colon'		=> sprintf( __( 'Parent %s:', 'wptemplate' ), $this->singular_label ),
			'all_items'				=> sprintf( __( 'All %s', 'wptemplate' ), $this->label ),
			'menu_name'				=> $this->label,
		];
	}

	private function register_post_type() {
		register_post_type(
			$this->name,
			$this->args
		);
	}

	private function add_revision_support() {
		add_post_type_support(
			$this->name,
			'revisions'
		);
	}
}
