<?php

namespace service;

class PostTypeCreator {
	private static $args;

	public static function add( $singular_name, $plural_name, $machine_name, $supports = [], $icon = 'dashicons-edit', $taxonomies = [], $capability_type = 'page', $public = true ) {
		$labels = [
			'name'                  => td_translate( $plural_name ),
			'singular_name'         => td_translate( $singular_name ),
			'menu_name'             => td_translate( $plural_name ),
			'name_admin_bar'        => td_translate( $singular_name ),
			'archives'              => td_translate( $singular_name . ' Archives' ),
			'attributes'            => td_translate( $singular_name . ' Attributes' ),
			'parent_item_colon'     => td_translate( 'Parent ' . $singular_name . ':' ),
			'all_items'             => td_translate( 'All ' . $plural_name ),
			'add_new_item'          => td_translate( 'Add New ' . $singular_name ),
			'add_new'               => td_translate( 'Add New ' . $singular_name ),
			'new_item'              => td_translate( 'New ' . $singular_name ),
			'edit_item'             => td_translate( 'Edit ' . $singular_name ),
			'update_item'           => td_translate( 'Update ' . $singular_name ),
			'view_item'             => td_translate( 'View ' . $singular_name ),
			'view_items'            => td_translate( 'View ' . $plural_name ),
			'search_items'          => td_translate( 'Search ' . $singular_name ),
			'not_found'             => td_translate( 'Not found' ),
			'not_found_in_trash'    => td_translate( 'Not found in Trash' ),
			'featured_image'        => td_translate( 'Featured Image' ),
			'set_featured_image'    => td_translate( 'Set featured image' ),
			'remove_featured_image' => td_translate( 'Remove featured image' ),
			'use_featured_image'    => td_translate( 'Use as featured image' ),
			'insert_into_item'      => td_translate( 'Insert into ' . $singular_name ),
			'uploaded_to_this_item' => td_translate( 'Uploaded to this ' . $singular_name ),
			'items_list'            => td_translate( $plural_name . ' list' ),
			'items_list_navigation' => td_translate( $plural_name . ' list navigation' ),
			'filter_items_list'     => td_translate( 'Filter ' . $plural_name . ' list' ),
		];

		self::$args[ $machine_name ] = [
			'label'               => td_translate( $singular_name ),
			'description'         => td_translate( $singular_name . ' description' ),
			'labels'              => $labels,
			'menu_icon'           => $icon,
			'supports'            => $supports,
			'taxonomies'          => $taxonomies,
			'public'              => $public,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'hierarchical'        => false,
			'exclude_from_search' => ! $public,
			'show_in_rest'        => true,
			'publicly_queryable'  => $public,
			'capability_type'     => $capability_type,
		];
	}

	public static function register() {
		if ( ! empty( self::$args ) ) {
			foreach ( self::$args as $machine_name => $args ) {
				register_post_type( $machine_name, $args );
			}
		}
	}
}