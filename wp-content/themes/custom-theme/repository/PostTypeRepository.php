<?php

namespace repository;


class PostTypeRepository {
	protected $post_type;
	/**
	 * @param \WP_Query $query
	 */
	protected $query;

	public function __construct() {
		if ( ! isset( $this->post_type ) ) {
			throw new \InvalidArgumentException( get_class( $this ) . ' must have a $post_type' );
		}

		$this->query = new \WP_Query( [
			'post_status'    => 'publish',
			'post_type'      => $this->post_type,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'posts_per_page' => - 1,
			'nopaging'       => true
		] );
	}

	private function get_object_type() {
		$classWithNamespace = get_class( $this );
		$exploded           = explode( '\\', $classWithNamespace );
		$className          = array_pop( $exploded );

		return '\\entity\\' . str_replace( 'Repository', '', $className );
	}

	private function create_object( $postObject ) {
		$obj_type = $this->get_object_type();

		return $obj_type::create_from_post_object( $postObject );
	}

	private function create_objects() {
		$items = new \SplObjectStorage();

		if ( $posts = $this->query->get_posts() ) {
			foreach ( $posts as $post ) {
				$return_type_name = $this->get_object_type();
				$item             = $return_type_name::create_from_post_object( $post );
				$items->attach( $item );
			}
		}

		return $items;
	}

	public static function find( $id ) {
		$self = new static();

		$post = get_post( $id );

		if ( $post ) {
			return $self->create_object( $post );
		}

		return null;
	}

	public static function find_one_by( array $params ) {
		$self = new static;

		foreach ( $params as $key => $param ) {
			$self->query->set( $key, $param );
		}

		if ( isset( $self->query->get_posts()[0] ) ) {
			return $self->create_object( $self->query->get_posts()[0] );
		}

		return null;
	}

	public static function find_all() {
		$self = new static;

		return $self->create_objects();
	}

	public static function find_all_by( array $params ) {
		$self = new static;

		foreach ( $params as $key => $param ) {
			$self->query->set( $key, $param );
		}

		return $self->create_objects();
	}

	public static function find_all_paginated( $paged, $per_page = 10 ) {
		return self::find_all_by( [
			'paged'          => $paged,
			'posts_per_page' => $per_page,
			'nopaging'       => false
		] );
	}

	public static function find_all_paginated_by_taxonomy_id( $taxonomy, $term_id, $paged, $per_page = 10 ) {
		return self::find_all_by( [
			'paged'          => $paged,
			'posts_per_page' => $per_page,
			'nopaging'       => false,
			'tax_query'      => [
				[
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_id
				]
			]
		] );
	}

	public static function find_all_by_taxonomy_id( $taxonomy, $term_id ) {
		return self::find_all_by( [
			'tax_query' => [
				[
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_id
				]
			]
		] );
	}

	public static function find_last( $number ) {
		return self::find_all_by( [
			'posts_per_page' => $number
		] );
	}

	public static function get_pagination_links( $base_url, $paged, $prev_text = '', $next_text = '', $per_page = 10 ) {
		$self = new static;

		$params = [
			'paged'          => $paged,
			'posts_per_page' => $per_page,
			'nopaging'       => false
		];

		foreach ( $params as $key => $param ) {
			$self->query->set( $key, $param );
		}

		$self->query->get_posts();

		return paginate_links( [
			'base'      => $base_url . '%_%',
			'format'    => '?strona=%#%',
			'current'   => $paged,
			'total'     => isset( $self->query->max_num_pages ) ? $self->query->max_num_pages : 1,
			'mid_size'  => 2,
			'end_size'  => 2,
			'prev_text' => $prev_text,
			'next_text' => $next_text,
			'type'      => 'array'
		] );
	}
}