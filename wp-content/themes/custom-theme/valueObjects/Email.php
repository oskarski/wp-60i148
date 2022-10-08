<?php

namespace valueObjects;


class Email {
	/**
	 * @var EmailAddress $to
	 */
	private $to;
	/**
	 * @var EmailAddress $from
	 */
	private $from;
	private $subject;
	private $template_name;
	private $params;

	public function __construct( EmailAddress $to, EmailAddress $from, $subject, $template_name, array $params ) {
		$this->to            = $to;
		$this->from          = $from;
		$this->subject       = $subject;
		$this->template_name = $template_name;

		$params['to']      = $to->get_email_address();
		$params['from']    = $from->get_email_address();
		$params['subject'] = $subject;

		$this->params = $params;
	}

	/**
	 * @return EmailAddress
	 */
	public function get_to() {
		return $this->to;
	}

	/**
	 * @return EmailAddress
	 */
	public function get_from() {
		return $this->from;
	}

	public function get_subject() {
		return $this->subject;
	}

	public function get_params() {
		return $this->params;
	}

	public function get_template_name() {
		return $this->template_name;
	}
}