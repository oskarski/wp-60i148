<?php

namespace service;

class MenuCreator {
	private static $menus;
	private static $location_menus;

	public static function add( $menu_name, $location, $menu_class, $container_class, $container = 'nav' ) {
		self::$menus[ $location ] = [
			'menu_class'      => $menu_class,
			'container'       => $container,
			'container_class' => $container_class,
			'container_id'    => $container_class,
			'fallback_cb'     => '',
			/* before <a href="#">link_before LINK TEXT link_after</a> after */
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'echo'            => false,
			'depth'           => 0,
			'theme_location'  => $location
		];

		self::$location_menus[ $location ] = $menu_name;
	}

	public static function register() {
		register_nav_menus( self::$location_menus );
	}

	public static function display( $location_name ) {
		if ( ! isset( self::$menus[ $location_name ] ) ) {
			throw new \InvalidArgumentException( 'There is no registered menu with name: ' . $location_name );
		}

		return wp_nav_menu( self::$menus[ $location_name ] );
	}

	public static function get_menu_title( $location_name ) {
		if ( ! isset( self::$menus[ $location_name ] ) ) {
			throw new \InvalidArgumentException( 'There is no registered menu with name: ' . $location_name );
		}

		$menu_obj = wp_get_nav_menu_object( $location_name );

		return $menu_obj && $menu_obj->name ? $menu_obj->name : '';
	}
}