<?php

namespace service;

use entity\Attachment;
use repository\AttachmentRepository;
use valueObjects\Link;

class AcfCreator {
	private static $sections;

	public static function build() {
		if ( ! class_exists( 'ACF' ) ) {
			throw new \Exception( 'ACF plugin is not installed!' );
		}

		self::create_sections();
	}

	private static function create_sections() {
		acf_add_options_page( [
			'page_title' => td_translate( 'General Settings' ),
			'menu_title' => td_translate( 'General Settings' ),
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit-posts',
			'redirect'   => false
		] );

		if ( isset( self::$sections ) ) {
			foreach ( self::$sections as $section ) {
				acf_add_options_page( [
					'page_title'  => td_translate( $section ),
					'menu_title'  => td_translate( $section ),
					'parent_slug' => 'theme-general-settings',
				] );
			}
		}
	}

	public static function add_section( $name ) {
		self::$sections[] = $name;
	}

	public static function get( $selector, $post_id, $tag = '', $class = '' ) {
		$acf_value = get_field( $selector, $post_id, false );
		$start_tag = '';
		$end_tag   = '';

		if ( $tag !== '' ) {
			$start_tag = '<' . $tag . ' class="' . $class . '">';
			$end_tag   = '</' . $tag . '>';
		}

		return $start_tag . $acf_value . $end_tag;
	}

	public static function get_option( $selector ) {
		return get_field( $selector, 'options', false );
	}

	public static function get_option_image( $selector ) {
		$image    = new Attachment();
		$image_id = AcfCreator::get_option($selector);

		if ( $image_id ) {
			/**
			 * @var \entity\Attachment $image
			 */
			$image = AttachmentRepository::find( $image_id );
		}

		return $image;
	}


	public static function get_option_link( $selector ) {
		$acf_link = AcfCreator::get_option($selector);

		return new Link(
			$acf_link['url'],
			$acf_link['title'],
			$acf_link['target']
		);
	}

	public static function get_repeater( $selector, $post_id ) {
		return get_field( $selector, $post_id, true );
	}

	public static function get_link( $selector, $post_id ) {
		$acf_link = get_field( $selector, $post_id, true );

		return new Link(
			$acf_link['url'],
			$acf_link['title'],
			$acf_link['target']
		);
	}

	public static function get_image( $selector, $post_id ) {
		$image    = new Attachment();
		$image_id = get_field( $selector, $post_id, false );

		if ( $image_id ) {
			/**
			 * @var \entity\Attachment $image
			 */
			$image = AttachmentRepository::find( $image_id );
		}

		return $image;
	}

	public static function get_gallery( $selector, $post_id ) {
		$gallery    = new \SplObjectStorage();
		$images_ids = get_field( $selector, $post_id, false );

		foreach ( $images_ids as $image_id ) {
			/**
			 * @var \entity\Attachment $image
			 */
			$image = AttachmentRepository::find( $image_id );
			if ( $image ) {
				$gallery->attach( $image );
			}
		}

		return $gallery;
	}
}
