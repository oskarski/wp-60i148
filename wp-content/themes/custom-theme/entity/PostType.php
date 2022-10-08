<?php

namespace entity;

use repository\AttachmentRepository;
use valueObjects\Link;

class PostType {
	protected $id;
	protected $title;
	protected $content;
	protected $excerpt;
	/**
	 * @var Attachment $image
	 */
	protected $image;
	/**
	 * @var Link $post_page_link
	 */
	protected $post_page_link;

	public static function create_from_post_object( \WP_Post $wp_post ) {
		$self = new self();

		$self->generate_basic_data( $wp_post );

		return $self;
	}

	protected function generate_basic_data( \WP_Post $wp_post ) {
		$this->id             = $wp_post->ID;
		$this->title          = $wp_post->post_title;
		$this->content        = nl2br( $wp_post->post_content );
		$this->excerpt        = $wp_post->post_excerpt;
		$this->image          = AttachmentRepository::find( get_post_thumbnail_id( $wp_post->ID ) );
		$this->post_page_link = new Link( get_page_link( $wp_post ), $wp_post->post_title );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_content() {
		return $this->content;
	}

	public function get_excerpt() {
		return $this->excerpt;
	}

	/**
	 * @return Attachment
	 */
	public function get_image() {
		return $this->image;
	}

	/**
	 * @return Link
	 */
	public function get_post_page_link() {
		return $this->post_page_link;
	}
}