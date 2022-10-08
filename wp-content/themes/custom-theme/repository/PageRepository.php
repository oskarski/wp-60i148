<?php

namespace repository;


class PageRepository extends PostTypeRepository {
	protected $post_type = 'page';

	public static function get_all_by_template_name( $name ) {
		return parent::find_all_by( [
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'page-' . $name . '-template.php'
		] );
	}

	public static function get_one_by_template_name( $name ) {
		return parent::find_one_by( [
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'page-' . $name . '-template.php'
		] );
	}
}