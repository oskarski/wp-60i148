<?php

namespace valueObjects;


class EmailAddress {
	private $email_address;

	public function __construct( $email_address ) {
		if ( ! filter_var( $email_address, FILTER_VALIDATE_EMAIL ) ) {
			throw new \InvalidArgumentException( $email_address . ' is not correct email address.' );
		}

		$this->email_address = $email_address;
	}

	public function get_email_address() {
		return $this->email_address;
	}
}