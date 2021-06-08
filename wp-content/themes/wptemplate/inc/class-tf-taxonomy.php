<?php

/**
 * Example usage:
 *
 * new TF_Taxonomy(
 *	'department',
 *	'employee',
 *	[
 *		'public' => true,
 *		'publicly_queryable' => false,
 *		'show_admin_column' => true,
 *	],
 *	__( 'Departments', 'wptemplate' ),
 *	__( 'Department', 'wptemplate' )
 * );
 */

class TF_Taxonomy {

	public static function register( $name, $post_type, $args, $label, $singular_label = '' ) {
		return new self( $name, $post_type, $args, $label, $singular_label );
	}

	public function __construct( $name, $post_type, $args, $label, $singular_label = '' ) {
		if ( empty( $name ) ) {
			throw new Exception( '$name parameter required.', 1 );
		}

		if ( 'init' !== current_action() ) {
			throw new Exception( 'TF_Taxonomy must be initialized in the "init" action.', 1 );
		}

		$this->name = $name;
		$this->object_type = $post_type;

		$this->label = $label;
		$this->singular_label = ( $singular_label ) ? $singular_label : $label;

		$this->args = array_merge_recursive(
			[
				'labels' => $this->get_labels(),
			],
			$args
		);

		$this->register_taxonomy();
	}

	public function get_labels() {
		return [
			'name'					=> $this->label,
			'singular_name'			=> $this->singular_label,
			'menu_name'				=> $this->label,
			'all_items'				=> sprintf( __( 'All %s', 'wptemplate' ), $this->label ),
			'edit_item'				=> sprintf( __( 'Edit %s', 'wptemplate' ), $this->singular_label ),
			'view_item'				=> sprintf( __( 'View %s', 'wptemplate' ), $this->singular_label ),
			'update_item'			=> sprintf( __( 'Update %s', 'wptemplate' ), $this->singular_label ),
			'add_new_item'			=> sprintf( __( 'Add new %s', 'wptemplate' ), $this->singular_label ),
			'new_item_name'			=> sprintf( __( 'New %s Name', 'wptemplate' ), $this->singular_label ),
			'parent_item'			=> sprintf( __( 'Parent %s', 'wptemplate' ), $this->singular_label ),
			'parent_item_colon'		=> sprintf( __( 'Parent %s:', 'wptemplate' ), $this->singular_label ),
			'search_items'			=> sprintf( __( 'Search %s', 'wptemplate' ), $this->label ),
			'not_found'			=> sprintf( __( 'No %s found.', 'wptemplate' ), $this->label ),
		];
	}

	private function register_taxonomy() {
		register_taxonomy(
			$this->name,
			$this->object_type,
			$this->args
		);

		foreach ( (array) $this->object_type as $post_type ) {
			register_taxonomy_for_object_type(
				$this->name,
				$post_type
			);
		}
	}
}
