<?php

namespace repository;


class TaxonomyRepository {
	protected $taxonomy;

	public function __construct() {
		if ( ! isset( $this->taxonomy ) ) {
			throw new \InvalidArgumentException( get_class( $this ) . ' must have a $taxonomy' );
		}
	}

	private function create_object( $taxonomyObject ) {
		$obj = $this->get_obect_type();

		return $obj::create_from_taxonomy_object( $taxonomyObject );
	}

	private function create_objects() {
		$items = new \SplObjectStorage();

		$categories = get_terms( [
			'taxonomy'   => $this->taxonomy,
			'hide_empty' => false,
			'orderby'    => 'rand'
		] );

		if ( $categories ) {
			foreach ( $categories as $category ) {
				if ( $category->term_id != 1 ) {
					$returnTypeName = $this->get_obect_type();
					$item           = $returnTypeName::create_from_taxonomy_object( $category );
					$items->attach( $item );
				}
			}
		}

		return $items;
	}

	private function get_obect_type() {
		$classWithNamespace = get_class( $this );
		$exploded           = explode( '\\', $classWithNamespace );
		$className          = array_pop( $exploded );

		return '\\entity\\' . str_replace( 'Repository', '', $className );
	}

	public static function find( $id ) {
		$self     = new static();
		$taxonomy = get_term( $id );
		if ( $taxonomy ) {
			return $self->create_object( $taxonomy );
		}

		return null;
	}

	public static function find_all() {
		$self = new static;

		return $self->create_objects();
	}
}