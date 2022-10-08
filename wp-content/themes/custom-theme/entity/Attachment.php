<?php

namespace entity;

class Attachment extends PostType {
	protected $label;
	protected $src;
	protected $alt;
	protected $description;
	protected $mime_type;

	public static function create_from_post_object( \WP_Post $wp_post ) {
		$self = new self();

		$self->label       = $wp_post->post_excerpt;
		$self->src         = strpos( $wp_post->guid, 'uploads' ) == false ? '' : $wp_post->guid;
		$self->alt         = get_post_meta( $wp_post->ID, '_wp_attachment_image_alt', true );
		$self->description = $wp_post->post_content;
		$self->mime_type   = $wp_post->post_mime_type;

		return $self;
	}

	public function get_img( $class = '', $id = '' ) {
		return '<img id="' . $id . '" src="' . $this->src . '" alt="' . $this->alt . '" title="' . $this->title . '" class="' . $class . '" />';
	}

	public function get_label() {
		return $this->label;
	}

	public function get_src() {
		return $this->src;
	}

	public function get_alt() {
		return $this->alt;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_mime_type() {
		return $this->mime_type;
	}

	public function is_video() {
		return strpos($this->get_mime_type(), 'video') > -1;
	}
}