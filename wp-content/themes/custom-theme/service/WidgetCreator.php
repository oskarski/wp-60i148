<?php

namespace service;


class WidgetCreator {
	private static $widgets = [];
	private static $widget_areas = [];

	public static function register() {
		foreach ( self::$widgets as $widget ) {
			register_widget( $widget );
		}
	}

	public static function add( $widget_class_name ) {
		$widget_full_class_name = '\service\widgets\\' . $widget_class_name;

		if ( ! class_exists( $widget_full_class_name ) ) {
			throw new \InvalidArgumentException( 'There is no widget inside widgets directory with class name: ' . $widget_class_name );
		}

		self::$widgets[] = $widget_full_class_name;
	}

	public static function add_widget_area( $widget_area_machine_name, $widget_area_name ) {
		if ( function_exists( 'register_sidebar' ) && ! isset( self::$widget_areas[ $widget_area_name ] ) ) {
			self::$widget_areas[] = $widget_area_machine_name;
			register_sidebar( [
				'id'            => $widget_area_machine_name,
				'name'          => $widget_area_name,
				'before_widget' => '<div id="%1$s" class="%2$s">',
				'after_widget'  => '</div>'
			] );
		}
	}

	public static function render_widget_area( $widget_area_machine_name ) {
		if ( in_array( $widget_area_machine_name, self::$widget_areas, true ) && ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $widget_area_machine_name ) ) {
		}
	}
}
