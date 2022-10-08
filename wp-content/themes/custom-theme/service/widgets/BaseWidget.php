<?php

namespace service\widgets;


abstract class BaseWidget extends \WP_Widget {
	public $name = '';
	protected $description = '';
	protected $widget_id = '';

	public function __construct() {
		if ( ! $this->widget_id ) {
			throw new \Exception( 'Widget Id is required' );
		}

		$widget_options = [
			'description' => td_translate( $this->description ),
			'classname'   => $this->widget_id . '-widget__container'
		];

		$control_options = [
			'id_base' => $this->widget_id
		];

		parent::__construct( $this->widget_id, td_translate( $this->name ), $widget_options, $control_options );
	}

	public function widget( $args, $instance ) {
		echo get_file_content( get_stylesheet_directory() . '/templates/widgets/' . $this->widget_id . '-widget.php', $args );
	}
}