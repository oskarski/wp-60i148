<?php

namespace service;

class TaxonomyCreator {
	private static $args;
	private static $post_types;

	public static function add( $single_name, $plural_name, $machine_name, $post_types, $hierarchical = false, $public = true ) {
		$labels = [
			'name'              => td_translate( $plural_name ),
			'singular_name'     => td_translate( $single_name ),
			'search_items'      => td_translate( 'Szukaj ' . $single_name ),
			'all_items'         => td_translate( 'Wszystkie ' . $plural_name ),
			'parent_item'       => td_translate( 'Rodzic ' . $single_name ),
			'parent_item_colon' => td_translate( 'Rodzic ' . $single_name . ':' ),
			'edit_item'         => td_translate( 'Edytuj ' . $single_name ),
			'update_item'       => td_translate( 'Zaktualizuj ' . $single_name ),
			'add_new_item'      => td_translate( 'Dodaj nowy ' . $single_name ),
			'new_item_name'     => td_translate( 'Nowy ' . $single_name ),
			'menu_name'         => td_translate( $plural_name ),
		];

		self::$args[ $machine_name ] = [
			'labels'             => $labels,
			'description'        => td_translate( $single_name . ' description' ),
			'hierarchical'       => $hierarchical,
			'public'             => $public,
			'publicly_queryable' => $public,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => $public,
			'show_in_rest'       => false,
			'show_tagcloud'      => $public,
			'show_in_quick_edit' => true,
			'show_admin_column'  => false,
		];

		self::$post_types[ $machine_name ] = $post_types;
	}

	public static function register() {
		if ( ! empty( self::$args ) ) {
			foreach ( self::$args as $machine_name => $args ) {
				register_taxonomy( $machine_name, self::$post_types[ $machine_name ], $args );
			}
		}
	}
}