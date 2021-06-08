<?php
/**
 * Sub nav filter for wp_nav_menu
 * Use with 'sub_menu' => true as argument in wp_nav_menu()
 */
add_filter( 'wp_nav_menu_objects', function( $sorted_menu_items, $args ) {
	if ( ! isset( $args->sub_menu ) ) {
		return $sorted_menu_items;
	}

	$root_id = 0;

	foreach ( $sorted_menu_items as $menu_item ) {
		if ( $menu_item->current ) {
			$root_id = ( $menu_item->menu_item_parent ) ? $menu_item->menu_item_parent : $menu_item->ID;
			break;
		}
	}

	if ( ! isset( $args->direct_parent ) ) {
		$prev_root_id = $root_id;

		while ( 0 != $prev_root_id ) {
			foreach ( $sorted_menu_items as $menu_item ) {
				if ( $menu_item->ID == $prev_root_id ) {
					$prev_root_id = $menu_item->menu_item_parent;

					if ( 0 != $prev_root_id ) {
						$root_id = $menu_item->menu_item_parent;
					}

					break;
				}
			}
		}
	}

	$menu_item_parents = [];

	foreach ( $sorted_menu_items as $key => $item ) {
		if ( $item->ID == $root_id ) {
			$menu_item_parents[] = $item->ID;
		}

		if ( in_array( $item->menu_item_parent, $menu_item_parents ) ) {
			$menu_item_parents[] = $item->ID;
		} else if ( ! ( isset( $args->show_parent ) && in_array( $item->ID, $menu_item_parents ) ) ) {
			unset( $sorted_menu_items[ $key ] );
		}
	}

	return $sorted_menu_items;
}, 10, 2 );
