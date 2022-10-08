<?php

use Dotenv\Dotenv;
use service\AcfCreator;
use service\EmailCreator;
use service\FormCreator;
use service\MenuCreator;
use service\PostTypeCreator;
use service\ShortCodeCreator;
use service\TaxonomyCreator;
use service\WidgetCreator;
use valueObjects\Email;
use valueObjects\EmailAddress;
use valueObjects\Form;
use valueObjects\FormControl;

require_once( __DIR__ . '/vendor/autoload.php' );
( new Dotenv( __DIR__ ) )->load();

define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true );

error_reporting( getenv( 'DEV' ) === '1' ? E_ALL : 0 );
ini_set( 'display_errors', getenv( 'DEV' ) === '1' );
session_start();

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );
add_filter( 'the_generator', '__return_false' );
add_filter( 'show_admin_bar', '__return_false' );

function td_login_duration( $seconds, $user_id, $remember ) {
	$expiration = $remember ? 7 * 24 * 60 * 60 : 6 * 60 * 60;

	if ( PHP_INT_MAX - time() < $expiration ) {
		$expiration = PHP_INT_MAX - time() - 5;
	}

	return $expiration;
}

add_filter( 'auth_cookie_expiration', 'td_login_duration', 99, 3 );

function td_styles() {
	wp_register_style( 'styles', get_template_directory_uri() . '/front/dist/styles.css', [], '1.0', 'all' );

	wp_enqueue_style( 'styles' );
}

add_action( 'wp_enqueue_scripts', 'td_styles' );

function td_scripts() {
	wp_register_script( 'scripts', get_template_directory_uri() . '/front/dist/main.js', [], null, true );

	wp_deregister_script( 'jquery' );
	wp_deregister_script( 'wp-embed' );
	wp_enqueue_script( 'scripts' );
}

add_action( 'wp_enqueue_scripts', 'td_scripts' );

function td_menus() {
	MenuCreator::add( td_translate( 'Menu Główne' ), 'main-menu', 'main-menu', 'main-nav' );

	MenuCreator::register();
}

function td_short_codes() {
	ShortCodeCreator::register( 'example', [ 'b' => '' ] );
}

function td_post_types() {
//	PostTypeCreator::add( ... );

	PostTypeCreator::register();
}

function td_taxonomies() {
	unregister_taxonomy_for_object_type( 'post_tag', 'post' );
	unregister_taxonomy_for_object_type( 'category', 'post' );

//	TaxonomyCreator::add( ... )

	TaxonomyCreator::register();
}

function td_forms() {
	try {
		$contact_form = new Form( 'contact', function ( $form_values ) {
			EmailCreator::send( new Email(
				new EmailAddress( AcfCreator::get_option( 'contact_email' ) ),
				new EmailAddress( $form_values['contact_email'] ),
				td_translate( 'Nowa wiadomość ze strony: ' ) . get_home_url(),
				'contact',
				[
					'message' => $form_values['contact_message'],
					'name'    => $form_values['contact_name']
				]
			) );
		} );

		$contact_form->add_form_control( new FormControl( 'name', 'text', 'Name' ) );
		$contact_form->add_form_control( new FormControl( 'email', 'email', 'E-mail *', true ) );
		$contact_form->add_form_control( new FormControl( 'message', 'textarea', 'Message *', true ) );
		$contact_form->add_form_control( new FormControl( 'submit', 'button', 'Submit', true, '', 'Submit' ) );
	} catch ( Exception $ex ) {
		var_dump( $ex->getMessage() );
		die();
	}

	FormCreator::add( $contact_form );
}

function td_init() {
	td_menus();

	td_short_codes();

	td_post_types();

	td_taxonomies();

	td_forms();
}

add_action( 'init', 'td_init' );

function td_widgets() {
	WidgetCreator::add_widget_area( 'front_page_widget_area', td_translate( 'Front Page Widget Area' ) );

	$widgets = [
		'ExampleWidget'
	];

	foreach ( $widgets as $widget ) {
		WidgetCreator::add( $widget );
	}

	WidgetCreator::register();
}

add_action( 'widgets_init', 'td_widgets' );

function td_acf_init() {
	acf_update_setting( 'google_api_key', getenv( 'GOOGLE_MAPS_API_KEY' ) );

	AcfCreator::add_section( td_translate( 'General' ) );

	/** @noinspection PhpUnhandledExceptionInspection */
	AcfCreator::build();
}

add_action( 'acf/init', 'td_acf_init' );

function td_setup() {
	add_theme_support( 'post-thumbnails' );

	add_post_type_support( 'post', 'page-attributes' );

	add_post_type_support( 'post', 'excerpt' );

	add_post_type_support( 'page', 'excerpt' );
}

add_action( 'after_setup_theme', 'td_setup' );

function document_ready_start() {
	ob_start();
}

function document_ready_stop() {
	global $script_js;

	$script = ob_get_clean();
	$script = str_replace( '<script>', '', $script );
	$script = str_replace( '</script>', '', $script );

	$script_js .= trim( $script );
}

function get_file_content( $file_path, array $params = [] ) {
	ob_start();
	extract( $params );

	if ( ! empty( $file_path ) && isset( $file_path ) && realpath( $file_path ) ) {
		include $file_path;
	} else {
		throw new \InvalidArgumentException( 'Can not find file: ' . $file_path );
	}

	return ob_get_clean();
}

function get_previous_page_url() {
	return isset( $_SERVER["HTTP_REFERER"] ) ? $_SERVER["HTTP_REFERER"] : '';
}

function mailtrap( $phpmailer ) {
	$phpmailer->isSMTP();
	$phpmailer->Host     = 'smtp.mailtrap.io';
	$phpmailer->SMTPAuth = true;
	$phpmailer->Port     = 2525;
	$phpmailer->Username = getenv( 'MAILTRAP_USER' );
	$phpmailer->Password = getenv( 'MAILTRAP_PASS' );
}

if ( getenv( 'SEND_EMAILS' ) == '0' ) {
	add_action( 'phpmailer_init', 'mailtrap' );
}

function td_translate( $text ) {
	return __( $text, 'titan-design' );
}