<?php

namespace service;


class ShortCodeCreator {
	private $name;
	private $params;

	public static function register( $name, $params = [] ) {
		$self = new self();

		$self->name   = $name;
		$self->params = array_merge( $params, [
			'class' => '',
			'id'    => ''
		] );

		add_shortcode( $name, [ &$self, 'create' ] );
	}

	public function create( $atts, $content = '' ) {
		$args            = shortcode_atts( $this->params, $atts );
		$args['content'] = $content;

		$file_content = get_file_content( get_stylesheet_directory() . '/templates/short-codes/' . $this->name . '-short-code.php', $args );

		return $file_content ? $file_content : '';
	}
}