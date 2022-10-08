<?php

namespace valueObjects;


class FormControl {
	private $name;
	private $type;
	private $label;
	private $required;
	private $placeholder;
	private $value;
	private $form_id;
	private $types = [
		'text',
		'password',
		'email',
		'tel',
		'url',
		'number',
		'textarea',
		'hidden',
		'submit',
		'button',
		'search'
	];

	public function __construct( $name, $type, $label, $required = false, $placeholder = '', $value = '' ) {
		if ( ! in_array( $type, $this->types ) ) {
			throw new \Exception( 'Type: ' . $type . ' is either not supported or invalid!' );
		}

		$this->name        = str_replace( '-', '_', str_replace( ' ', '_', $name ) );
		$this->type        = $type;
		$this->label       = $label;
		$this->required    = $required;
		$this->value       = $value;
		$this->placeholder = $placeholder;
	}

	public function render() {
		return $this->render_label() . $this->render_control();
	}

	public function render_label() {
		$error_class = isset( $_SESSION[ $this->get_form_id() ] ) && isset( $_SESSION[ $this->get_form_id() ][ $this->get_name() . '_error' ] ) ? 'error' : '';

		return $this->type !== 'hidden' &&
		       $this->type !== 'button' &&
		       $this->label !== ''
			? '<label id="' . $this->get_name() . '-label" class="' . $this->get_name() . '-label ' . $error_class . '" for="' . $this->get_name() . '">' . $this->get_label() . '</label>'
			: '';
	}

	public function get_form_id() {
		return $this->form_id;
	}

	public function set_form_id( $form_id ) {
		$this->form_id = $form_id;
	}

	public function get_name() {
		return $this->name;
	}

	public function set_name( $name ) {
		$this->name = $name;
	}

	public function get_label() {
		return $this->label;
	}

	public function render_control() {
		$html        = '';
		$error_class = isset( $_SESSION[ $this->get_form_id() ] ) && isset( $_SESSION[ $this->get_form_id() ][ $this->get_name() . '_error' ] ) ? 'error' : '';

		switch ( $this->get_type() ) {
			case 'text':
			case 'password':
			case 'email':
			case 'tel':
			case 'url':
			case 'number':
			case 'hidden':
			case 'search':
			case 'submit':
				$required = $this->get_required() ? 'required' : '';
				$name     = $this->get_name() !== 'action' ? $this->get_form_id() . '[' . $this->get_name() . ']' : $this->get_name();

				$html = '<input 
						 id="' . $this->get_name() . '" 
						 name="' . $name . '" 
						 class="' . $this->get_name() . '-control ' . $error_class . '" 
						 type="' . $this->get_type() . '"
						 ' . $required . ' 
						 placeholder="' . $this->get_placeholder() . '"
						 value="' . $this->get_value() . '"
						 />';
				break;
			case 'textarea':
				$html = '<textarea 
						 id="' . $this->get_name() . '" 
						 name="' . $this->get_form_id() . '[' . $this->get_name() . ']" 
						 class="' . $this->get_name() . '-control ' . $error_class . '"
						 placeholder="' . $this->get_placeholder() . '"
						 >' . $this->get_value() . '</textarea>';
				break;
			case 'button':
				$html = '<button 
						 id="' . $this->get_name() . '"
						 class="' . $this->get_name() . '-control ' . $error_class . '"
						 >' . $this->get_label() . '</button>';
				break;
		}

		return $html;
	}

	public function get_type() {
		return $this->type;
	}

	public function get_required() {
		return $this->required;
	}

	public function get_placeholder() {
		return $this->placeholder;
	}

	public function get_value() {
		return isset( $_SESSION[ $this->get_form_id() ] ) && isset( $_SESSION[ $this->get_form_id() ][ $this->get_name() ] ) ? $_SESSION[ $this->get_form_id() ][ $this->get_name() ] : $this->value;
	}
}