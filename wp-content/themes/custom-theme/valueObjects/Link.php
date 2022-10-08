<?php

namespace valueObjects;


class Link {
	private $href;
	private $title;
	private $target;

	public function __construct( $href, $title, $target = '' ) {
		$this->href   = $href;
		$this->title  = $title;
		$this->target = $target;
	}

	public function get_href() {
		return $this->href;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_target() {
		return $this->target;
	}

	public function get_link( $class = '' ) {
		return '<a href="' . $this->href . '" class="' . $class . '" target="' . $this->target . '">' . $this->title . '</a>';
	}
}