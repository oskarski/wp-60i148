<?php

namespace entity;


use valueObjects\Link;

class Taxonomy {
	protected $id;
	protected $name;
	protected $description;
	protected $count;
	/**
	 * @var Link $taxonomy_page_link
	 */
	protected $taxonomy_page_link;

	public static function create_from_taxonomy_object( \WP_Term $wp_term ) {
		$self = new self();

		$self->generate_basic_data( $wp_term );

		return $self;
	}

	protected function generate_basic_data( \WP_Term $wp_term ) {
		$this->id                 = $wp_term->term_id;
		$this->name               = $wp_term->name;
		$this->description        = $wp_term->description;
		$this->count              = $wp_term->count;
		$this->taxonomy_page_link = new Link( get_term_link( $wp_term->term_id ), $wp_term->name );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_count() {
		return $this->count;
	}

	/**
	 * @return Link
	 */
	public function get_taxonomy_page_link() {
		return $this->taxonomy_page_link;
	}
}