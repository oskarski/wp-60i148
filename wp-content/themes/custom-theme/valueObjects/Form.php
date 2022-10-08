<?php

namespace valueObjects;


class Form {
	private $id;
	private $form_controls = [];
	private $class = '';
	private $success_callback;

	public function __construct( $id, $success_callback ) {
		$this->id               = $id;
		$this->class            = $id . '-form';
		$this->success_callback = $success_callback;

		/** @noinspection PhpUnhandledExceptionInspection */
		$this->add_form_control( new FormControl(
			'action',
			'hidden',
			'',
			false,
			'',
			'td_' . $this->get_id() . '_request'
		), true );
	}

	public function add_form_control( FormControl $form_control, $force_control_name = false ) {
		$form_control->set_form_id( $this->get_id() );
		if ( ! $force_control_name ) {
			$form_control->set_name( $this->get_id() . '_' . $form_control->get_name() );
		}

		$this->form_controls[ $form_control->get_name() . '_control' ] = $form_control;
	}

	public function get_id() {
		return $this->id;
	}

	public function get_form_controls() {
		return $this->form_controls;
	}

	public function get_class() {
		return $this->class;
	}

	public function get_success_callback() {
		return $this->success_callback;
	}
}