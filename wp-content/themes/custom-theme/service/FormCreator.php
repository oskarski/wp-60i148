<?php

namespace service;


use Form\Validator;
use valueObjects\Form;
use valueObjects\FormControl;

class FormCreator {
	private static $forms = [];

	public static function add( Form $form ) {
		self::$forms[ $form->get_id() ] = $form;

		add_action( 'wp_ajax_td_' . $form->get_id() . '_request', function () use ( $form ) {
			self::handle_submit( $form );
		} );
		add_action( 'wp_ajax_nopriv_td_' . $form->get_id() . '_request', function () use ( $form ) {
			self::handle_submit( $form );
		} );
	}

	public static function handle_submit( Form $form ) {
		unset( $_SESSION[ $form->get_id() ] );
		$form_post = $_SESSION[ $form->get_id() ] = wp_unslash( $_POST[ $form->get_id() ] );

		if ( $form_post ) {
			$validator_array = [];

			/**
			 * @var FormControl $form_control
			 */
			foreach ( $form->get_form_controls() as $form_control ) {
				$validators = [ 'trim', 'length' => [ 1, 49 ] ];

				if ( $form_control->get_type() === 'textarea' ) {
					$validators['length'] = [ 10, 2000 ];
				} else if ( $form_control->get_type() === 'email' ) {
					$validators[] = 'email';
				}

				if ( $form_control->get_required() ) {
					$validators[] = 'required';
				}

				if ( strpos( $form_control->get_name(), $form->get_id() ) !== false && $form_control->get_type() !== 'button' && $form_control->get_type() !== 'submit' ) {
					$validator_array[ $form_control->get_name() ] = $validators;
				}
			}

			$form_validator = new Validator( $validator_array );

			if ( $form_validator->validate( $form_post ) ) {
				$form_values = $form_validator->getValues();

				call_user_func( $form->get_success_callback(), $form_values );

				unset( $_SESSION[ $form->get_id() ] );
			} else {

				foreach ( $form_validator->getErrors() as $error => $error_array ) {
					$_SESSION[ $form->get_id() ][ $error . '_error' ] = true;
				}

			}
		} else {
			MessageCreator::add( td_translate( 'Wystąpił błąd, prosimy spróbować ponownie.' ) );
		}

		wp_redirect( get_previous_page_url() );
		exit();
	}

	public static function render_form( $form_id ) {
		/**
		 * @var Form $form
		 */
		$form = self::$forms[ $form_id ];

		if ( isset( $form ) && $form instanceof Form ) {
			echo get_file_content( get_stylesheet_directory() . '/templates/forms/' . $form->get_id() . '-form.php', array_merge( $form->get_form_controls(), [
				'form_id'    => $form->get_id(),
				'form_class' => $form->get_class()
			] ) );
		}

		unset( $_SESSION[ $form->get_id() ] );
	}
}